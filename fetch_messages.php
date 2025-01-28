<?php
include 'system/inc/config.php';

$senderEmail = isset($_GET['sender_email']) ? $_GET['sender_email'] : '';
$customerEmail = isset($_SESSION['customer']['email']) ? $_SESSION['customer']['email'] : 'No email found';

if (empty($senderEmail) || $senderEmail !== $customerEmail) {
    echo json_encode(["error" => "Invalid sender email"]);
    exit;
}

$sql = "SELECT sender_email, receiver_email, message, created_at 
        FROM messages 
        WHERE sender_email = :sender_email
        ORDER BY created_at ASC";


$stmt = $pdo->prepare($sql);


$stmt->bindParam(':sender_email', $customerEmail);


$stmt->execute();


$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($messages);
?>
