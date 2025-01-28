<?php
require_once 'inc/config.php';

header('Content-Type: application/json'); // Set content type to JSON

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate that the necessary POST data is provided
    if (empty($_POST['message']) || empty($_POST['receiver_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Message and receiver email are required']);
        exit;
    }

    // Check if the user is authenticated
    if (isset($_SESSION['user'])) {
        $myId = $_SESSION['user']['id'];        // User ID from session
        $myRole = $_SESSION['user']['role'];    // User Role from session
        $sender_email = $_SESSION['user']['email']; // User email from session

        // Get the message and receiver's email from the POST request
        $message = $_POST['message'];
        $receiver_email = $_POST['receiver_email'];

        // Insert the message into the database
        $created_at = date('Y-m-d H:i:s');  // Get the current timestamp for the message
        try {
            $insert_sql = "INSERT INTO messages (sender_email, receiver_email, message, created_at) 
                           VALUES (?, ?, ?, ?)";
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([$sender_email, $receiver_email, $message, $created_at]);

            // Return a JSON response indicating success
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
        } catch (PDOException $e) {
            // Handle any database errors
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message: ' . $e->getMessage()]);
        }
    } else {
        // Handle user authentication failure
        echo json_encode(['status' => 'error', 'message' => 'User is not authenticated']);
    }
} else {
    // Handle invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
