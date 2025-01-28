<?php
session_start();

header('Content-Type: application/json');

if (isset($_POST['input_code'])) {
    $output = [];
    $input_code = $_POST['input_code'];

    // Retrieve the stored verification code from the session
    if (isset($_SESSION['verification_code'])) {
        $stored_code = $_SESSION['verification_code'];

        // Check if the input code matches the stored code
        if ($input_code === $stored_code) {
            $output['success'] = "Email verified successfully!";
        } else {
            $output['error'] = "Invalid verification code.";
        }
    } else {
        $output['error'] = "No verification code found. Please request a new one.";
    }

    echo json_encode($output);
} else {
    echo json_encode(['error' => 'Verification code not provided.']);
}
?>
