<?php
require 'system/inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = trim($_POST['order_id']); 
    $cancelReason = trim($_POST['cancel_reason']);

    if (empty($orderId)) {
        echo "Order ID is missing!";
        exit;
    }

    if (empty($cancelReason)) {
        echo "Please provide a reason for cancellation.";
        exit;
    }

    try {
        $sql = "UPDATE tbl_purchase_payment SET cancel_reason = :cancelReason WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cancelReason', $cancelReason, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_STR); 

        if ($stmt->execute()) {
           header('Location:settings.php');
           exit();
        } else {
            echo "error";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
