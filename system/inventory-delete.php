<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Delete from tbl_inventory
	$statement = $pdo->prepare("DELETE FROM tbl_inventory WHERE id=?");
	$statement->execute(array($_REQUEST['id']));

	header('location: inventory.php');
}
?>