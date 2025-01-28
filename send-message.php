<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    include 'system/inc/config.php';

    // Get POST parameters
    $sender_email = $_POST['sender_email'] ?? '';
    $receiver_email = $_POST['receiver_email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate inputs
    if (empty($sender_email) || empty($receiver_email) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    try {
        // Insert the message into the database
        $query = "INSERT INTO messages (sender_email, receiver_email, message, created_at) VALUES (:sender_email, :receiver_email, :message, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':sender_email', $sender_email);
        $stmt->bindParam(':receiver_email', $receiver_email);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            // Send success response
            echo json_encode(['success' => true]);
        } else {
            // Handle database error
            echo json_encode(['success' => false, 'error' => 'Failed to send message']);
        }
    } catch (PDOException $e) {
        // Handle exception
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
