<?php
require_once 'system/inc/config.php';  

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reason = $_POST['reason'];
    $gcashName = $_POST['gcash_name'];
    $gcashNumber = $_POST['gcash_number'];
    $orderId = $_POST['order_id'];



if (isset($_FILES['gcash_image']) && $_FILES['gcash_image']['error'] == 0) {
    $gcashImage = $_FILES['gcash_image'];


    $uniqueName = 'image_' . time() . '_' . rand(1000, 9999) . '.png';  
    
    $uploadDir = 'assets/img/return_items/';

    $imagePath = $uploadDir . $uniqueName;

    move_uploaded_file($gcashImage['tmp_name'], $imagePath);

    $dbImageName = $uniqueName;  
} else {
    $dbImageName = null;
}



    $sql = "INSERT INTO tbl_return_items (order_id, reason, gcash_name, gcash_number, gcash_image) 
            VALUES (:order_id, :reason, :gcash_name, :gcash_number, :gcash_image)";
    
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':order_id' => $orderId,
            ':reason' => $reason,
            ':gcash_name' => $gcashName,
            ':gcash_number' => $gcashNumber,
            ':gcash_image' => $dbImageName
        ]);

        $updateQuery = "UPDATE tbl_purchase_order SET return_status = 1 WHERE order_id = :order_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([':order_id' => $orderId]);

        $response['success'] = 'Return request submitted successfully.';
    } catch (PDOException $e) {
        $response['error'] = 'Error processing your request: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>