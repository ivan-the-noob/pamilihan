<?php
require 'system/inc/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'] ?? null;
    $customer_id = $_POST['customer_id'] ?? null;
    $seller_id = $_POST['seller_id'] ?? null;
    $rating = $_POST['seller_rating'] ?? null;
    $feedback = $_POST['feedback'] ?? null;

    if (!$order_id || !$customer_id || !$seller_id || !$rating || !$feedback) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO tbl_seller_ratings (order_id, customer_id, seller_id, rating, feedback) 
                               VALUES (:order_id, :customer_id, :seller_id, :rating, :feedback)");

        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $updateStmt = $pdo->prepare("UPDATE tbl_purchase_order SET rate_seller_status = 1 WHERE order_id = :order_id");
            $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $updateStmt->execute();

            echo json_encode([
                "success" => true,
                "message" => "Thank you for your rating!"
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
