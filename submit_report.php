<?php
    require 'system/inc/config.php';

    header('Content-Type: application/json');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $order_id = $_POST['order_id'] ?? null;
        $customer_id = $_POST['customer_id'] ?? null;
        $rider_id = $_POST['rider_id'] ?? null;
        $seller_id = $_POST['seller_id'] ?? null;
        $report_reason = $_POST['report_reason'] ?? null;

        if (!$order_id || !$customer_id || !$rider_id || !$seller_id || !$report_reason) {
            echo json_encode(["success" => false, "message" => "All fields are required."]);
            exit();
        }

        $report_to = 'seller';

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO tbl_report (order_id, customer_id, rider_id, seller_id, report_reason, report_to) 
                                VALUES (:order_id, :customer_id, :rider_id, :seller_id, :report_reason, :report_to)");

            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
            $stmt->bindParam(':rider_id', $rider_id, PDO::PARAM_INT);
            $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
            $stmt->bindParam(':report_reason', $report_reason, PDO::PARAM_STR);
            $stmt->bindParam(':report_to', $report_to, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $updateStmt = $pdo->prepare("UPDATE tbl_purchase_order SET report_seller_status = 1 WHERE order_id = :order_id");
                $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
                $updateStmt->execute();

                $pdo->commit();
                echo json_encode(["success" => true, "message" => "Reported Successfully."]);
            } else {
                $pdo->rollBack();
                echo json_encode(["success" => false, "message" => "Failed to submit report."]);
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
?>
