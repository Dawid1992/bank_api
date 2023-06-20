<?php
namespace App\classes;
include_once(\ROOT_PATH . "/config/db.php");

class NbpApi
{
    private const URL = "http://api.nbp.pl/api";
    
    public function getApiData(string $tableName = "A", string $date = "today"): string|bool
    {
        $tableName = htmlspecialchars($tableName, ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($date, ENT_QUOTES, 'UTF-8');

        // url validation
        if (!preg_match('/^[A-Za-z0-9]+$/', $tableName)) {
            return false;
        }

        $curl = curl_init();

        // addition of headers
        $headers = [
            'User-Agent: MyCustomUserAgent',
            'Accept: application/json',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // url builder
        $url = sprintf("%s/exchangerates/tables/%s?format=json", self::URL, $tableName);
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // error handling
        curl_setopt($curl, CURLOPT_FAILONERROR, true);

        $output = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return false;
        }

        curl_close($curl);
        return $output;
    }
}

?>
