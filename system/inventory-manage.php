<?php require_once('header.php'); ?>

<?php
date_default_timezone_set("Asia/Singapore");
if(isset($_SESSION['user']['role'])) {
	$ownRole = $_SESSION['user']['role'];
	$u_id = "";
	if($ownRole == "Seller"){
		$u_id = $_SESSION['user']['id'];
	}
	$year = date("Y");
	$month = date("m");
	$maxDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
}
if(isset($_POST['form1'])) {
	$valid = 1;
    if(empty($_POST['b_id'])) {
        $valid = 0;
        $error_message .= "Batch ID can not be empty<br>";
    }

    if($ownRole == "Admin"){
		if(empty($_POST['seller_id'])) {
			$valid = 0;
			$error_message .= "You must have to select a Seller<br>";
		}
	}else{
		$seller = $_SESSION['user']['id'];
	}

    if(empty($_POST['p_name'])) {
        $valid = 0;
        $error_message .= "Product name can not be empty<br>";
    }

    if(empty($_POST['quantity'])) {
        $valid = 0;
        $error_message .= "Quantity can not be empty<br>";
    }

    if($valid == 1) {
    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_inventory'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}
		if($ownRole == "Admin"){
			$u_id = $_POST['seller_id'];
		}
		$day = date('d');
		$getExpDate = "";
		$expDate = "";
		$countDay = "";
		$pId = $_POST['p_name'];
		$stmt = $pdo->prepare("SELECT p_id, nearly_expiration FROM tbl_product WHERE p_id=?");
		$stmt->execute(array($pId));
		if($stmt->rowCount() > 0){
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($res as $r){
				if($r['nearly_expiration'] > 0){
					$countDay = (int)$day+(int)$r['nearly_expiration'];
					$getExpDate = $year."-".$month."-".$countDay." ".date("H:i:s");
					$expDate = strtotime($getExpDate);
				}
			}
		}
		//Check if Batch ID is existing
		$bId = $_POST['b_id'];
		$chck = $pdo->prepare("SELECT * FROM tbl_inventory WHERE b_id=? AND p_id=?");
		$chck->execute(array($bId,$pId));
		if($chck->rowCount() > 0){
			$error_message .= "Data already exist!<br>";
		}else{
			//Saving data into the main table tbl_inventory
			$statement = $pdo->prepare("INSERT INTO tbl_inventory SET
										p_id=?,
										b_id=?,
										stock_in=?,
										stock_out=0,
										exp_date=?,
										stock_status='Available'
										");
			$statement->execute(array(
										$_POST['p_name'],
										$_POST['b_id'],
										$_POST['quantity'],
										$expDate
									));
			$success_message = 'New Inventory is added successfully.';
		}
    	
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Inventory</h1>
	</div>
	<div class="content-header-right">
		<a href="inventory.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Batch ID <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="b_id" class="form-control" required>
							</div>
						</div>
						<?php
							if($ownRole == "Admin"){
							?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Seller <span>*</span></label>
								<div class="col-sm-4">
									<select name="seller_id" class="form-control select2 seller" required>
										<?php
										$statement = $pdo->prepare("SELECT * FROM tbl_seller WHERE status='Verified' ORDER BY business_title ASC");
										$statement->execute();
										if($statement->rowCount() > 0){
											?>
											<option value="">Select Seller</option>
											<?php
											$result = $statement->fetchAll(PDO::FETCH_ASSOC);   
											foreach ($result as $row) {
												?>
												<option value="<?php echo $row['user_id']; ?>"><?php echo $row['business_title']; ?></option>
												<?php
											}
										}else{
											?>
											<option value="" selected disabled>No seller available!</option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<?php
							}
						?>
						<?php
						if($ownRole == "Admin"){
							?>
							<div class="form-group showProduct">
							</div>
							<?php
						}else{
							?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Product Name <span>*</span></label>
								<div class="col-sm-4">
									<select name="p_name" class="form-control select2 p_name" required>
										<?php
										$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE u_id=? ORDER BY p_name ASC");
										$statement->execute(array($u_id));
										if($statement->rowCount() > 0){
											?>
											<option value="">Select Product</option>
											<?php
											$result = $statement->fetchAll(PDO::FETCH_ASSOC);   
											foreach ($result as $row) {
												?>
												<option value="<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></option>
												<?php
											}
										}else{
											?>
											<option value="" selected disabled>No product available!</option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<?php
						}
						?>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Quantity <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="quantity" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Add Iventory</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>
<?php require_once('footer.php'); ?>
<?php
if($ownRole == "Admin"){
?>
<script>
	$(document).ready(function(){
		$(document).on('change', '.seller', function(e){
			e.preventDefault();
			var u_id = $(this).val();
			$.ajax({
				method:"POST",
				url:"inventory-show-product.php",
				data:{ u_id: u_id},
				dataType:"html",
				success:function(d){
					$('.showProduct').empty('');
					setTimeout(function(){
						$('.showProduct').html(d);
					},150);
				}
			});
		});
	});
</script>
<?php
}
?>