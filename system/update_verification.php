<?php
require 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Output the received form data
    var_dump($_POST);  // This will output all received form data

    if (isset($_POST['id']) && isset($_POST['verification'])) {
        $userId = $_POST['id'];
        $verification = $_POST['verification'];

        // Debug: Log verification and user ID
        echo "User ID: $userId, Verification Status: $verification<br>";

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE tbl_user SET verification = :verification WHERE id = :id");

        // Bind the parameters to avoid SQL injection
        $stmt->bindParam(':verification', $verification, PDO::PARAM_INT);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // If successful, redirect back
            header("Location: verification.php");
            exit(); // Ensure no further code is executed after redirect
        } else {
            // Debugging: Output error message
            echo "Error updating verification status: " . implode(", ", $stmt->errorInfo());
        }
    } else {
        echo "Invalid request: missing required parameters.";
    }
} else {
    echo "Invalid request method.";
}
?>
