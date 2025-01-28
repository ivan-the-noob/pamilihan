<?php
session_start();

if (isset($_POST['form2']) && isset($_POST['reset_code'])) {
    $input_code = $_POST['reset_code'];

    if (isset($_SESSION['reset_code']) && $input_code == $_SESSION['reset_code']) {
        echo json_encode(['status' => 'success', 'codeVerified' => true]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid reset code.']);
    }
}

?>
