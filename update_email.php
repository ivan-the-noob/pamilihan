<?php
session_start();

header('Content-Type: application/json');

// Check if the request is to update email
if (isset($_POST['update']) && $_POST['update'] === 'updateEmail') {
    $output = [];

    // Validate the session
    if (isset($_SESSION['customer'])) {
        $user_id = $_SESSION['customer']['id'];
        $new_email = $_POST['new_email'];

        try {
            // Database connection (use your existing connection logic)
            require_once 'system/inc/config.php';

            // Prepare the SQL statement
            $sql = "UPDATE tbl_user SET email = :email WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            // Execute the query with parameters
            $stmt->execute([
                ':email' => $new_email,
                ':id'    => $user_id,
            ]);

            // Update the session with the new email
            $_SESSION['customer']['email'] = $new_email;

            $output['success'] = "Your email has been updated successfully!";
        } catch (Exception $e) {
            $output['error'] = "Failed to update email: " . $e->getMessage();
        }
    } else {
        $output['error'] = "Your session has ended. Please log in again to update your email.";
    }

    echo json_encode($output);
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
