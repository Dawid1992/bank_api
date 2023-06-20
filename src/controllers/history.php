<?php 
    include_once(\ROOT_PATH . "/config/db.php");
    
    try {
        $query = "SELECT cs.currency as source_currency, ct.currency as target_currency, cs.code as source_code, ct.code as target_code, cc.source_amount, cc.target_amount, cc.created_at 
        FROM currency_conversions cc 
        JOIN currencies cs ON cc.source_currency_id = cs.id
        JOIN currencies ct ON cc.target_currency_id = ct.id
        ORDER BY cc.created_at DESC";

        $history_list = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
    require_once ROOT_PATH . '/templates/history.php';
?>
