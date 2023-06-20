<?php 
    use App\classes\Validation;
    use App\classes\Converter;
    include_once(\ROOT_PATH . "/config/db.php");

    $data = $_POST['data'];

    $validaton = new Validation();

    if($validaton->validateRequiredField($data['amount']) &&
        $validaton->validateRequiredField($data['source']) &&
        $validaton->validateRequiredField($data['target']) &&
        $validaton->validateSameCurrency($data['source'],$data['target']) &&
        $validaton->validateNumeric($data['amount'])){
            echo json_encode(calculateResult($db, $data));
        }
        else{
            echo json_encode($validaton->getErrors());
        }
    function calculateResult($db, $data)
    {
        $sourceCurrencyRate = getLatestCurrencyRate($db, $data['source']);
        $targetCurrencyRate = getLatestCurrencyRate($db, $data['target']);

        $converter = new Converter();

        $newAmount = $converter->convert($sourceCurrencyRate, $targetCurrencyRate, $data['amount']);

        saveHistory($db, $data['source'], $data['target'], $data['amount'], $newAmount);

        return ['success'=> true, 'new_amount' => $newAmount];
    }

    function getLatestCurrencyRate($db, $currencyId) {
        $statement = $db->prepare("SELECT mid FROM currency_rates WHERE currency_id = :currency_id ORDER BY created_at DESC LIMIT 1");
        $statement->bindParam(':currency_id', $currencyId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    function saveHistory($db, int $source, int $target, string $amount, string $newAmount)
    {
        try {
            $db->beginTransaction();
        
            $historyStatement = $db->prepare('INSERT INTO currency_conversions(source_currency_id, target_currency_id, source_amount, target_amount) VALUES(:source_currency_id, :target_currency_id, :source_amount, :target_amount)');
            $historyStatement->bindParam(':source_currency_id', $source, PDO::PARAM_INT);
            $historyStatement->bindParam(':target_currency_id', $target, PDO::PARAM_INT);
            $historyStatement->bindParam(':source_amount', $amount, PDO::PARAM_STR);
            $historyStatement->bindParam(':target_amount', $newAmount, PDO::PARAM_STR);
            $historyStatement->execute();
            
            $db->commit(); 

        }catch (PDOException $e) {
            $db->rollBack();
        
            die('An error occurred while writing data to the database.');
        }
        
    }
?>
