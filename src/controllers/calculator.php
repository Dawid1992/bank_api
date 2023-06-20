<?php 
    include_once(\ROOT_PATH . "/config/db.php");

    try {
        $query = "SELECT cr.currency_id as id, c.currency, c.code, cr.mid, cr.created_at 
        FROM currency_rates cr 
        JOIN currencies c ON cr.currency_id = c.id 
        WHERE cr.created_at = (SELECT MAX(created_at) FROM currency_rates)";

        $currencies = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
    
    require_once ROOT_PATH . '/templates/calculator.php';
?>
