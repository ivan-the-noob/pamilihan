<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form-add'])) {
	$valid = 1;

	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a photo<br>';
    }

	if($valid == 1) {
		$final_name = 'user-'.$file_name.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );
	
		$statement = $pdo->prepare("INSERT INTO tbl_user (photo,full_name,email,phone,password,role,status) VALUES (?,?,?,?,?,'Seller',?)");
		$statement->execute(array($final_name,$_POST['name'],$_POST['email'],$_POST['phone'],md5($_POST['password']),$_POST['status']));
		$getLastId = $pdo->lastInsertId();

		$bType = "Open-Air Business Venue";
		$statement2 = $pdo->prepare("INSERT INTO tbl_seller (user_id,business_title,business_name,business_type,tin_id,status) VALUES (?,?,?,?,?,?)");
		$statement2->execute(array($getLastId,$_POST['d_name'],$_POST['b_name'],$bType,$_POST['tin_id'],$_POST['status']));


			
		$success_message = 'Account is added successfully!';
	}
}
if(isset($_POST['form1'])) {
	$valid = 1;

	
    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

	if($valid == 1) {

		if($path == '') {
			$statement = $pdo->prepare("UPDATE tbl_user SET full_name=?, email=?, phone=?, status=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['status'],$_REQUEST['id']));

			$statement2 = $pdo->prepare("UPDATE tbl_seller SET business_title=?, business_name=?, tin_id=?, status=? WHERE user_id=?");
    		$statement2->execute(array($_POST['d_name'],$_POST['b_name'],$_POST['tin_id'],$_POST['b_status'],$_REQUEST['id']));
		} else {

			unlink('../assets/uploads/'.$_POST['current_photo']);

			$final_name = 'user-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        	$statement = $pdo->prepare("UPDATE tbl_user SET full_name=?, email=?, phone=?, status=?, role=?, photo=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$_POST['email'],$_POST['phone'],$_POST['status'],$_POST['position'],$final_name,$_REQUEST['id']));

			$statement2 = $pdo->prepare("UPDATE tbl_seller SET business_title=?, business_name=?, tin_id=?, status=? WHERE user_id=?");
    		$statement2->execute(array($_POST['d_name'],$_POST['b_name'],$_POST['tin_id'],$_POST['b_status'],$_REQUEST['id']));
		}	   

	    $success_message = 'Account is updated successfully!';
	}
}
$purpose = "";
$newTitle = "";
if(isset($_GET['purpose'])){
	$purpose = $_GET['purpose'];
	if($purpose == "add"){
		$newTitle = "Add";
	}else if($purpose == "edit" && $_REQUEST['id']){
		$newTitle = "Edit";
		$statement = $pdo->prepare("SELECT * FROM tbl_user WHERE id=?");
		$statement->execute(array($_REQUEST['id']));
		$statement2 = $pdo->prepare("SELECT * FROM tbl_seller WHERE user_id=?");
		$statement2->execute(array($_REQUEST['id']));
		if($statement->rowCount() > 0){
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				$photo       	= $row['photo'];
				$full_name     	= $row['full_name'];
				$email     		= $row['email'];
				$phone 			= $row['phone'];
				$status    		= $row['status'];
			}
			$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
			if($statement2->rowCount() > 0){
				foreach($result2 as $row2){
					$d_name 		= $row2['business_title'];
					$b_name 		= $row2['business_name'];
					$tin_id 		= $row2['tin_id'];
					$b_status 		= $row2['status'];
				}
			}else{
				$d_name 		= "";
				$b_name 		= "";
				$tin_id 		= "";
				$b_status 		= "";
			}
		}else{
			echo "<script>window.location.href='./seller.php';</script>";
		}
	}else{
		echo "<script>window.location.href='./seller.php';</script>";
	}
}else{
	echo "<script>window.location.href='./seller.php';</script>";
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1><?php echo $newTitle; ?> Account</h1>
	</div>
	<div class="content-header-right">
		<a href="seller.php" class="btn btn-primary btn-sm">View All</a>
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

			<?php
			if(isset($_GET['purpose'])){
				$purpose = $_GET['purpose'];
				if($purpose == "add"){
					?>
					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-6">
								<div class="box box-info">
									<div class="box-header">
										<h4>Account Information</h4>
									</div><hr/>
									<div class="box-body">
										
										<div class="form-group">
											<label for="" class="col-md-2 control-label">Photo <span>*</span></label>
											<div class="col-sm-9" style="padding-top:5px">
												<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-2 control-label">Full Name </label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="name" Placeholder="Full Name">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-2 control-label">Email </label>
											<div class="col-sm-6">
												<input type="email"class="form-control" name="email" Placeholder="Email"> 
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-2 control-label">Phone</label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="phone" Placeholder="Phone">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-2 control-label">Password </label>
											<div class="col-sm-6">
												<input type="password" autocomplete="off" class="form-control" name="password" Placeholder ="Type Your Password" >
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-2 control-label">Status </label>
											<div class="col-sm-6">
												<select name="status" class="form-control">
													<option value="Active">Active</option>
													<option value="Inactive">Inactive</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="box box-info">
									<div class="box-header">
										<h4>Business Information</h4>
									</div><hr/>
									<div class="box-body">
										<div class="form-group">
											<label for="" class="col-md-3 control-label">Display Name<span>*</span></label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="d_name" Placeholder ="Display public name" required>
												<small><b>Format: </b>mypublicname1</small>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-3 control-label">Business Name<span>*</span></label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="b_name" Placeholder ="Business name" required>
												<small><b>Format: </b>My Business Name</small>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-3 control-label">Business Type</label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="b_type" value="Open-Air Business Venue" readonly>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-3 control-label">TIN ID<span>*</span></label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="tin_id" required>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-3 control-label">Business Status </label>
											<div class="col-sm-6">
												<select name="status" class="form-control">
													<option value="Verified">Verified</option>
													<option value="Pending">Pending</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label"></label>
								<div class="col-sm-3">
									<button type="submit" class="btn btn-success pull-right" name="form-add">Add</button>
								</div>
							</div>
						</div>
					</form>
				<?php
				}else if($purpose == "edit"){
					?>
					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
						<div class="row">
							<div class="col-md-6">
								<div class="box box-info">
									<div class="box-header">
										<h4>Account Information</h4>
									</div><hr/>
									<div class="box-body">
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Existing Photo</label>
											<div class="col-sm-9" style="padding-top:5px">
												<img src="../assets/uploads/<?php echo $photo; ?>" alt="Slider Photo" style="width:250px;">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Photo </label>
											<div class="col-sm-6" style="padding-top:5px">
												<input type="file" name="photo">(Only jpg, jpeg, gif and png are allowed)
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Full Name </label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $full_name; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Email </label>
											<div class="col-sm-6">
												<textarea class="form-control" name="email" style="height:140px;"><?php echo $email; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Phone </label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="phone" value="<?php echo $phone; ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-2 control-label">Status </label>
											<div class="col-sm-6">
												<select name="status" class="form-control">
													<option value="Active" <?php if($status == 'Active') {echo 'selected';} ?>>Active</option>
													<option value="Inactive" <?php if($status == 'Inactive') {echo 'selected';} ?>>Inactive</option>
													
												</select>
											</div>
										</div>	
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="box box-info">
									<div class="box-header">
										<h4>Business Information</h4>
									</div><hr/>
									<div class="box-body">
										<div class="form-group">
											<label for="" class="col-md-3 control-label">Display Name<span>*</span></label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="d_name" Placeholder ="Display public name" value="<?php echo $d_name;?>" required>
												<small><b>Format: </b>mypublicname1</small>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-3 control-label">Business Name<span>*</span></label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="b_name" Placeholder ="Business name" value="<?php echo $b_name;?>" required>
												<small><b>Format: </b>My Business Name</small>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-3 control-label">Business Type</label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="b_type" value="Open-Air Business Venue" readonly>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-md-3 control-label">TIN ID<span>*</span></label>
											<div class="col-sm-6">
												<input type="text" autocomplete="off" class="form-control" name="tin_id" value="<?php echo $tin_id;?>" required>
											</div>
										</div>
										<div class="form-group">
											<label for="" class="col-sm-3 control-label">Business Status </label>
											<div class="col-sm-6">
												<select name="b_status" class="form-control">
													<option value="Verified" <?php if($b_status == "Verified"){ echo 'selected'; }?>>Verified</option>
													<option value="Pending" <?php if($b_status == "Pending"){ echo 'selected'; }?>>Pending</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label"></label>
								<div class="col-sm-3">
									<button type="submit" class="btn btn-success pull-right" name="form1">Edit</button>
								</div>
							</div>
						</div>
					</form>
					<?php
				}
			}
			?>
		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>