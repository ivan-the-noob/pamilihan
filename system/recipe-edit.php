<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top level category<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a mid level category<br>";
    }

    if(empty($_POST['p_name'])) {
        $valid = 0;
        $error_message .= "Product name can not be empty<br>";
    }

    if(empty($_POST['p_retail'])) {
        $valid = 0;
        $error_message .= "Retail can not be empty<br>";
    }

    $path = $_FILES['p_featured_photo']['name'];
    $path_tmp = $_FILES['p_featured_photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }


    if($valid == 1) {

    	if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) )
        {

        	$photo = array();
            $photo = $_FILES['photo']["name"];
            $photo = array_values(array_filter($photo));

        	$photo_temp = array();
            $photo_temp = $_FILES['photo']["tmp_name"];
            $photo_temp = array_values(array_filter($photo_temp));

        	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row) {
				$next_id1=$row[10];
			}
			$z = $next_id1;

            $m=0;
            for($i=0;$i<count($photo);$i++)
            {
                $my_ext1 = pathinfo( $photo[$i], PATHINFO_EXTENSION );
		        if( $my_ext1=='jpg' || $my_ext1=='png' || $my_ext1=='jpeg' || $my_ext1=='gif' ) {
		            $final_name1[$m] = $z.'.'.$my_ext1;
                    move_uploaded_file($photo_temp[$i],"../assets/uploads/product_photos/".$final_name1[$m]);
                    $m++;
                    $z++;
		        }
            }

            if(isset($final_name1)) {
            	for($i=0;$i<count($final_name1);$i++)
		        {
		        	$statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo,p_id) VALUES (?,?)");
		        	$statement->execute(array($final_name1[$i],$_REQUEST['id']));
		        }
            }            
        }
		$p_n_e = 0;
		if(!empty($_POST['nearly_expiration'])){
			$p_n_e = $_POST['nearly_expiration'];
		}else{
			$p_n_e = 0;
		}
		$u_id = "";
		if($_SESSION['user']['role'] == "Admin"){
			$u_id = $_POST['seller_id'];
		}else{
			$u_id = $_SESSION['user']['id'];
		}
        if($path == '') {
        	$statement = $pdo->prepare("UPDATE tbl_product SET 
									u_id=?,
        							p_name=?, 
        							p_retail=?, 
									p_w_confirm=?,
									nearly_expiration=?,
									critical_level=?,
									stocks_reorder=?,
        							p_short_description=?,
        							p_return_policy=?,
        							p_is_featured=?,
        							p_is_active=?

        							WHERE p_id=?");
        	$statement->execute(array(
									$u_id,
        							$_POST['p_name'],
        							$_POST['p_retail'],
									$_POST['p_w_confirm'],
									$p_n_e,
									$_POST['critical_level'],
									$_POST['stocks_reorder'],
        							$_POST['p_short_description'],
        							$_POST['p_return_policy'],
        							$_POST['p_is_featured'],
        							$_POST['p_is_active'],
        							$_REQUEST['id']
        						));
        } else {

        	unlink('../assets/uploads/'.$_POST['current_photo']);

			$final_name = 'product-featured-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );


        	$statement = $pdo->prepare("UPDATE tbl_product SET 
									u_id=?,
        							p_name=?, 
        							p_retail=?, 
									p_w_confirm=?,
									nearly_expiration=?,
									critical_level=?,
									stocks_reorder=?,
        							p_featured_photo=?,
        							p_short_description=?,
        							p_return_policy=?,
        							p_is_featured=?,
        							p_is_active=?

        							WHERE p_id=?");
        	$statement->execute(array(
									$u_id,
        							$_POST['p_name'],
        							$_POST['p_retail'],
									$_POST['p_w_confirm'],
									$p_n_e,
									$_POST['critical_level'],
									$_POST['stocks_reorder'],
        							$final_name,
        							$_POST['p_short_description'],
        							$_POST['p_return_policy'],
        							$_POST['p_is_featured'],
        							$_POST['p_is_active'],
        							$_REQUEST['id']
        						));
        }
		

        if(isset($_POST['p_size'])) {
			$sql = "SELECT * FROM tbl_product_size WHERE p_id=:d";
			$p = [
				":d"	=>	$_REQUEST['id']
			];
			$r = $c->fetchData($pdo, $sql, $p);
			if($r){
				$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        		$statement->execute(array($_REQUEST['id']));
			}

			foreach($_POST['p_size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$_REQUEST['id']));
			}
		} else {
			$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));
		}
	
    	$success_message = 'Product is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Product</h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$user_id = $row['u_id'];
	$p_name = $row['p_name'];
	$p_retail = $row['p_retail'];
	$p_wholesale = $row['p_wholesale'];
	$p_w_confirm = $row['p_w_confirm'];
	$nearly_expiration = $row['nearly_expiration'];
	$critical_level = $row['critical_level'];
	$stocks_reorder = $row['stocks_reorder'];
	$p_qty = $row['p_qty'];
	$p_featured_photo = $row['p_featured_photo'];
	$p_description = $row['p_description'];
	$p_short_description = $row['p_short_description'];
	$p_feature = $row['p_feature'];
	$p_condition = $row['p_condition'];
	$p_return_policy = $row['p_return_policy'];
	$p_is_featured = $row['p_is_featured'];
	$p_is_active = $row['p_is_active'];
	$ecat_id = $row['ecat_id'];
}

// $statement = $pdo->prepare("SELECT * 
//                         FROM tbl_end_category t1
//                         JOIN tbl_mid_category t2
//                         ON t1.mcat_id = t2.mcat_id
//                         JOIN tbl_top_category t3
//                         ON t2.tcat_id = t3.tcat_id
//                         WHERE t1.ecat_id=?");
// $statement->execute(array($ecat_id));
// $result = $statement->fetchAll(PDO::FETCH_ASSOC);
// foreach ($result as $row) {
// 		$ecat_name = $row['ecat_name'];
//     	$mcat_id = $row['mcat_id'];
//     	$tcat_id = $row['tcat_id'];
// }

$statement = $pdo->prepare("SELECT * FROM tbl_mid_category t1 JOIN tbl_top_category t2 ON t1.tcat_id=t2.tcat_id");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    	$mcat_id = $row['mcat_id'];
    	$tcat_id = $row['tcat_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$size_id[] = $row['size_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$color_id[] = $row['color_id'];
}
?>


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
						<?php
						if($_SESSION['user']['role'] == "Admin"){
							?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Seller <span>*</span></label>
								<div class="col-sm-4">
									<select name="seller_id" class="form-control select2 top-cat">
										<option value="">Select Seller</option>
										<?php
										$statement = $pdo->prepare("SELECT * FROM tbl_seller ORDER BY business_title ASC");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);   
										foreach ($result as $row) {
											?>
											<option value="<?php echo $row['user_id']; ?>" <?php if($row['user_id'] == $user_id){echo 'selected';} ?>><?php echo $row['business_title']; ?></option>
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
							<label for="" class="col-sm-3 control-label">Product Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_name" class="form-control" value="<?php echo $p_name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="tcat_id" class="form-control select2 top-cat">
		                            <option value="">Select Category</option>
		                            <?php
		                            if($_SESSION['user']['role'] == "Admin"){
										$statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
										$statement->execute();
									}else{
										$statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE seller_id=? ORDER BY tcat_name ASC");
										$statement->execute(array($_SESSION['user']['id']));
									}
		                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
		                            foreach ($result as $row) {
		                                ?>
		                                <option value="<?php echo $row['tcat_id']; ?>" <?php if($row['tcat_id'] == $tcat_id){echo 'selected';} ?>><?php echo $row['tcat_name']; ?></option>
		                                <?php
		                            }
		                            ?>
		                        </select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Sub Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="mcat_id" class="form-control select2 mid-cat">
									<option value="">Select Sub Category</option>
									<?php
									$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE seller_id=? ORDER BY mcat_name ASC");
									$statement->execute(array($_SESSION['user']['id']));
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['mcat_id']; ?>" <?php if($row['mcat_id'] == $mcat_id){echo 'selected';} ?>><?php echo $row['mcat_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<?php
						$sql = "SELECT s.* FROM tbl_size s ORDER BY size_name DESC";
						$res = $c->fetchData($pdo, $sql);
						$asd = array();
						if($res){
							?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Size <span>*</span></label>
								<div class="col-sm-4">
									<?php
									$sql2 = "SELECT * FROM tbl_product_size WHERE p_id=:p_id";
									$p2 = [
										":p_id"	=> $_REQUEST['id']
									];
									$res2 = $c->fetchData($pdo, $sql2, $p2);
									$i = 0;
									if($res2){
										$asd = [];
										foreach ($res2 as $row101) {
											$asd[] = $row101['size_id'];
										}
									}
									foreach ($res as $row100) {
										$isChecked = in_array($row100['size_id'], $asd) ? 'checked' : '';
										?>
										<div class="form-check">
											<label for="p_size<?= $row100['size_id']; ?>">
												<input class="form-check-input p_size" name="p_size[]" id="p_size<?= $row100['size_id']; ?>" 
													   type="checkbox" value="<?= $row100['size_id']; ?>" <?= $isChecked; ?>>
												<?= $row100['size_name']; ?>
											</label>
										</div>
										<?php
										$i++;
									}
									?>
								</div>
							</div>
							<?php
						}
						?>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Short Description</label>
							<div class="col-sm-8">
								<textarea name="p_short_description" class="form-control" cols="20" rows="6" id="editor1"><?php echo $p_short_description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Retail (Current Price) <br><span style="font-size:10px;font-weight:normal;">(In PHP)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_retail" class="form-control" value="<?php echo $p_retail; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Warning Information <span>*</span></label>
							<div class="col-sm-4">
								<div class="form-check">
									<input class="form-check-input p_w_confirm" value="1" type="radio" name="p_w_confirm" id="flexRadioDefault1" <?php if($p_w_confirm == 1){ echo 'checked'; }?> required>
									<label class="form-check-label" for="flexRadioDefault1">
										With Expiration
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input p_w_confirm" value="0" type="radio" name="p_w_confirm" id="flexRadioDefault2" <?php if($p_w_confirm == 0){ echo 'checked'; }?> required>
									<label class="form-check-label" for="flexRadioDefault2">
										Without Expiration
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Nearly Expiration (Days) <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="nearly_expiration" class="form-control nearly_expiration" value="<?php echo $nearly_expiration; ?>" required <?php if($p_w_confirm == 0){ echo 'disabled'; }?>>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Critical Level <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="critical_level" class="form-control" value="<?php echo $critical_level; ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Stocks Reorder <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="stocks_reorder" class="form-control" value="<?php echo $stocks_reorder; ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Existing Featured Photo</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<img src="../assets/uploads/<?php echo $p_featured_photo; ?>" alt="" style="width:150px;">
								<input type="hidden" name="current_photo" value="<?php echo $p_featured_photo; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Change Featured Photo </label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Other Photos</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                        	<?php
			                        	$statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
			                        	$statement->execute(array($_REQUEST['id']));
			                        	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			                        	foreach ($result as $row) {
			                        		?>
											<tr>
				                                <td>
				                                    <img src="../assets/uploads/product_photos/<?php echo $row['photo']; ?>" alt="" style="width:150px;margin-bottom:5px;">
				                                </td>
				                                <td style="width:28px;">
				                                	<a onclick="return confirmDelete();" href="product-other-photo-delete.php?id=<?php echo $row['pp_id']; ?>&id1=<?php echo $_REQUEST['id']; ?>" class="btn btn-danger btn-xs">X</a>
				                                </td>
				                            </tr>
			                        		<?php
			                        	}
			                        	?>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="+ Photo" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Return Policy</label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"><?php echo $p_return_policy; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Featured?</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_featured == '0'){echo 'selected';} ?>>No</option>
									<option value="1" <?php if($p_is_featured == '1'){echo 'selected';} ?>>Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Active?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_active == '0'){echo 'selected';} ?>>No</option>
									<option value="1" <?php if($p_is_active == '1'){echo 'selected';} ?>>Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>
</section>

<?php require_once('footer.php'); ?>