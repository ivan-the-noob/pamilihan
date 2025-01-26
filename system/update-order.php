// update-order.php
<?php require_once('header.php'); ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get order_id and assigned_rider from POST data
    $orderId = $_POST['order_id'];
    $assignedRider = $_POST['assigned_rider'];
    $email = $_POST['rider_email'];
    var_dump($$email);
    // Update order with assigned rider
    $stmt = $pdo->prepare("UPDATE tbl_payment SET rider_id = ?,rider_email =? WHERE id = ?");
    $stmt->execute([$assignedRider, $email,$orderId]);

    //Redirect back to orders page or wherever appropriate
    header('Location: order.php');
    exit();
}
?>