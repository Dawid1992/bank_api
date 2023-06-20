<?php
namespace App\classes;
include_once(\ROOT_PATH . "/config/db.php");

class Converter
{
    public function convert(float $sourceCurrencyRate, float $targetCurrencyRate, float $amount) { 
        $convertedAmount = $amount * ($sourceCurrencyRate / $targetCurrencyRate);
        
        return number_format($convertedAmount, 5, ',', '');
    }
}

?>
