<?php 
    use App\classes\NbpApi;
    include_once(\ROOT_PATH . "/config/db.php");

    function getCurrentData(string $date)
    {
        $api = new NbpApi();
        $apiTableTypes = ['A','B'];

        $combinedTable = [];

        foreach($apiTableTypes as $tableType){
            $data = json_decode($api->getApiData($tableType, $date), true);
            $combinedTable[] = $data[0]['rates'];
        }

        $result = [];
        foreach($combinedTable as $currencies) {
            $result = array_merge($result, $currencies);
        }

        return $result;
    }

    function createCurrenciesList($db,array $currencies)
    {
        if ($currencies === false) {
            die('An error occurred while downloading data from the NBP API. <br> <a href="/">Go back to main page</a>');
        }

        // save currencies list if it doesn't exist

        $currencies_query = "SELECT COUNT(*) FROM currencies";
        $currencies_list = $db->query($currencies_query)->fetchColumn();
        
        if ($currencies_list < 1) {
            $query = $db->prepare('INSERT INTO currencies (currency, code) VALUES (:currency, :code)');
            foreach ($currencies as $singleCurrency) {
                try {
                    $query->execute([
                        ':currency' => $singleCurrency['currency'],
                        ':code' => $singleCurrency['code']
                    ]);
                } catch (Exception $e) {
                    die('An error occurred while writing data to the database.');
                }
            }
        }
        else{
            return false;
        }
    }

    function saveDataToDatabase($db,array $currencies)
    {
        $existingDataQuery = $db->prepare('SELECT COUNT(*) FROM currency_rates WHERE created_at = :created_at');
        $existingDataQuery->execute([':created_at' => date('Y-m-d')]);
        $existingData = $existingDataQuery->fetchColumn();
        // check if the course is up to date
        if ($existingData > 0) {
            $msg = 'Data with today\'s date already exists in the database.<br> <a href="/">Go back to main page</a>';
            echo $msg;
            exit();
        }else{
            try {
                $db->beginTransaction();
        
                foreach ($currencies as $currency) {
                    /* Find currency ID in DB */
                    $statement = $db->prepare('SELECT id FROM currencies WHERE code = :code');
                    $statement->bindParam(':code', $currency['code'], PDO::PARAM_STR);
                    $statement->execute();
                    $currencyId = $statement->fetchColumn();
        
                    // If no currency ID is found, add a new currency to the "currencies" table
                    if (!$currencyId) {
                        $insertStatement = $db->prepare('INSERT INTO currencies(name, code) VALUES(:currency, :code)');
                        $insertStatement->bindParam(':currency', $currency['currency'], PDO::PARAM_STR);
                        $insertStatement->bindParam(':code', $currency['code'], PDO::PARAM_STR);
                        $insertStatement->execute();
        
                        $currencyId = $db->lastInsertId();
                    }
        
                    // Add the rate information to the "currencies_rate" table
                    $rateStatement = $db->prepare('INSERT INTO currency_rates(currency_id, mid) VALUES(:currency_id, :mid)');
                    $rateStatement->bindParam(':currency_id', $currencyId, PDO::PARAM_INT);
                    $rateStatement->bindParam(':mid', $currency['mid'], PDO::PARAM_STR);
                    $rateStatement->execute();
                }
        
                $db->commit(); 
        
                $msg = 'The exchange rate data has been successfully saved to the database.<br> <a href="/">Go back to main page</a>';
            } catch (PDOException $e) {
                $db->rollBack();
        
                die('An error occurred while writing data to the database.');
            }
        }
        
        echo $msg;
    }



    $data = getCurrentData("today");
    // var_dump($data);die;
    createCurrenciesList($db,$data);
    saveDataToDatabase($db,$data);
    
?>
