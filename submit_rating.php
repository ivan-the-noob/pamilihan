<?php
require 'system/inc/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'] ?? null;
    $customer_id = $_POST['customer_id'] ?? null;
    $rider_id = $_POST['rider_id'] ?? null;
    $rating = $_POST['rider_rating'] ?? null;
    $feedback = $_POST['feedback'] ?? null;

   

    try {
        // Insert the rating and feedback into tbl_ratings
        $stmt = $pdo->prepare("INSERT INTO tbl_ratings (order_id, customer_id, rider_id, rating, feedback) 
                               VALUES (:order_id, :customer_id, :rider_id, :rating, :feedback)");

        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':rider_id', $rider_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $updateStatus = $pdo->prepare("UPDATE tbl_purchase_order SET rate_rider_status = 1 WHERE order_id = :order_id");
            $updateStatus->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            $updateStatus->execute();

            echo json_encode([
                "success" => true,
                "message" => "Thank you for your rating!."
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to submit rating."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
