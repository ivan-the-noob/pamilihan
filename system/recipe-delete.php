<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_recipe WHERE r_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}else{
		// Getting photo ID to unlink from folder
		$statement = $pdo->prepare("SELECT * FROM tbl_recipe WHERE r_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
		foreach ($result as $row) {
			$p_featured_photo = $row['r_featured_photo'];
			unlink('../assets/uploads/'.$p_featured_photo);
		}

		// Getting other photo ID to unlink from folder
		// $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
		// $statement->execute(array($_REQUEST['id']));
		// $result = $statement->fetchAll(PDO::FETCH_ASSOC);							
		// foreach ($result as $row) {
		// 	$photo = $row['photo'];
		// 	unlink('../assets/uploads/product_photos/'.$photo);
		// }


		// Delete from tbl_recipe
		$statement = $pdo->prepare("DELETE FROM tbl_recipe WHERE r_id=?");
		$statement->execute(array($_REQUEST['id']));
		
		$statement = $pdo->prepare("SELECT * FROM tbl_recipe_product WHERE r_id=?");
		$statement->execute(array($_REQUEST['id']));
		$total = $statement->rowCount();
		if($total > 0){
			// Delete from tbl_recipe_product
			$statement = $pdo->prepare("DELETE FROM tbl_recipe_product WHERE r_id=?");
			$statement->execute(array($_REQUEST['id']));
		}

		header('location: recipe.php');
	}
}
?>