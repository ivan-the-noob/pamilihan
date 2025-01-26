<?php
include 'inc/config.php';
if($_POST['id'])
{
	$id = $_POST['id'];
	
	if(isset($_SESSION['user'])){
		if($_SESSION['user']['role'] == "Admin"){
			$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
			$statement->execute(array($id));
		}
		if($_SESSION['user']['role'] == "Seller"){
			$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE seller_id=? AND tcat_id=?");
			$statement->execute(array($_SESSION['user']['id'],$id));
		}
	}
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	?><option value="">Select Sub Category</option><?php						
	foreach ($result as $row) {
		?>
        <option value="<?php echo $row['mcat_id']; ?>"><?php echo $row['mcat_name']; ?></option>
        <?php
	}
}