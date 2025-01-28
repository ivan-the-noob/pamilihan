<?php 
// Include the configuration file
include_once "php/config.php";

// Check if the user is logged in
if (!isset($_SESSION['customer']['id'])) {
    header("Location: login.php"); 
    exit();
}

// Get the customer ID from the session
$customer_id = $_SESSION['customer']['id'];

// Use PDO to fetch the email from the database using the customer ID
$stmt = $pdo->prepare("SELECT email FROM tbl_user WHERE id = :customer_id");
$stmt->execute([':customer_id' => $customer_id]);

// Check if a matching customer was found
if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $customer_email = $row['email']; // This will be the email to use for further queries
} else {
    // If no customer found, redirect
    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Area</title>
    <link rel="stylesheet" href="styles.css"> <!-- Replace with your stylesheet -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper">
    <section class="chat-area">
        <header>
            <?php 
            // Now that we have the customer_email, use it to fetch the user's information
            $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE email = :email");
            $stmt->execute([':email' => $customer_email]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // Redirect if the user is not found in the database
                header("Location: users.php");
                exit();
            }
            ?>
            <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
            
        </header>
        <div class="chat-box">
            <!-- Chat messages will be dynamically loaded here -->
        </div>
        <form action="#" class="typing-area">
            <input type="text" class="incoming_id" name="incoming_id" value="<?php echo htmlspecialchars($customer_email); ?>" hidden>
            <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
            <button><i class="fab fa-telegram-plane"></i></button>
        </form>
    </section>
</div>

<script src="javascript/chat.js"></script>

</body>
</html>
