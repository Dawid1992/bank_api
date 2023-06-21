<?php
namespace App\classes;
include_once(\ROOT_PATH . "/config/db.php");

class Validation
{
    private $errors = [];
    function validateRequiredField($value)
    {
        // Validation for the required field
        if (isset($value) && !empty($value)){
            return true;
        }
        else{
            $this->errors[] = 'Please enter all required fields'; 
        }
    }

    function validateNumeric($value)
    {
        // Validation for numreric fiel
        if (is_numeric($value) && floatval($value) > 0){
            return true;
        }
        else{
            $this->errors[] = 'Amount is not a number greater than zero'; 
        }
    }

    function validateSameCurrency($source, $target)
    {
        // validation for the same currency error
        if ($source !== $target){
            return true;
        }
        else{
            $this->errors[] = 'Source and target currencies are the same'; 
        }
    }
    
    function getErrors()
    {
        return $this->errors;
    }

}

?>
