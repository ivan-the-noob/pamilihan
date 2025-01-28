<?php
session_start();
include('inc/config.php'); // Ensure you have the correct database connection

if (isset($_GET['sender_email']) && isset($_GET['receiver_email'])) {
    $senderEmail = $_GET['sender_email'];
    $receiverEmail = $_GET['receiver_email'];

    // Query to fetch messages between sender and receiver
    $query = "SELECT * FROM messages WHERE (sender_email = :sender_email AND receiver_email = :receiver_email) 
              OR (sender_email = :receiver_email AND receiver_email = :sender_email) ORDER BY created_at ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':sender_email', $senderEmail);
    $stmt->bindParam(':receiver_email', $receiverEmail);
    $stmt->execute();

    // Return messages as JSON
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo json_encode(['error' => 'Invalid request parameters']);
}
?>
