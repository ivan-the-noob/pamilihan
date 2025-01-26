<?php require_once('header.php'); ?>
<?php
// Include database connection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the POST request
    $riderId = $_POST['rider_id'];  // This is the email, so it should be treated as a string
    $riderStatus = $_POST['rider_status'];
    $accountStatus = $_POST['account_status'];
  
    try {
        // Update the rider status in the tbl_rider table
        $query = "UPDATE tbl_rider SET r_status = :rider_status WHERE email = :rider_id";
        $stmt = $pdo->prepare($query);

        // Bind the rider status (integer) and rider_id (email string)
        $stmt->bindParam(':rider_status', $riderStatus, PDO::PARAM_INT);  // r_status is an integer
        $stmt->bindParam(':rider_id', $riderId, PDO::PARAM_STR);  // rider_id is an email (string)

        // Execute the query
        if ($stmt->execute()) {
            // Now update the account status in the tbl_user table (assuming it's related to rider via email)
            $query2 = "UPDATE tbl_user SET status = :status WHERE email = :rider_id";
            $stmt2 = $pdo->prepare($query2);

            // Bind the account status (integer) and rider_id (email string)
            $stmt2->bindParam(':status', $accountStatus, PDO::PARAM_STR);  // status is an integer
            $stmt2->bindParam(':rider_id', $riderId, PDO::PARAM_STR);  // rider_id is an email (string)

            $stmt2->execute();

            echo "Rider and account status updated successfully!";
        } else {
            echo "Error updating statuses.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
