<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_rider WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
    $statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<?php
    $email = $result[0]['email'];
	// Delete from tbl_customer
	$statement = $pdo->prepare("DELETE FROM tbl_rider WHERE id=?");
	$statement->execute(array($_REQUEST['id']));

	// Delete from tbl_rating
	$statement = $pdo->prepare("DELETE FROM tbl_user WHERE email=?");
    $statement->execute(array($email));

	header('location: rider.php');
?>