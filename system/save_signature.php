<?php
require 'inc/config.php';

if (isset($_POST['save_signature'])) {
    $userId = $_POST['id'];

    if (isset($_FILES['e-signature']) && $_FILES['e-signature']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['e-signature']['tmp_name'];
        $fileName = $_FILES['e-signature']['name'];
        $fileSize = $_FILES['e-signature']['size'];
        $fileType = $_FILES['e-signature']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            $uniqueFileName = uniqid('signature_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

            $uploadDir = '../assets/img/verification/';
            $uploadPath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                // Update the user verification and e-signature
                $stmt = $pdo->prepare("UPDATE tbl_user SET `e-signature` = :signature, `verification` = 2 WHERE id = :id");
                $stmt->bindParam(':signature', $uniqueFileName);
                $stmt->bindParam(':id', $userId);
                $stmt->execute();

                // Fetch the updated user data to update session
                $stmt = $pdo->prepare("SELECT id, role, verification FROM tbl_user WHERE id = :id");
                $stmt->bindParam(':id', $userId);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Update the session with the new data
                $_SESSION['user']['id'] = $user['id'];
                $_SESSION['user']['role'] = $user['role'];
                $_SESSION['user']['verification'] = $user['verification'];

                // Redirect to the index page after saving
                header('Location: index.php');
                exit();
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Only image files are allowed (JPEG, PNG, GIF).";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>
