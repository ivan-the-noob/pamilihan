<?php require_once('header.php'); ?>

<?php
if(isset($_SESSION['user']['role'])) {
	$ownRole = $_SESSION['user']['role'];
}
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
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a featured photo<br>';
    }


    if($valid == 1) {

    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

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
		        	$statement->execute(array($final_name1[$i],$ai_id));
		        }
            }            
        }
		$wholesale = "";
		$final_name = 'product-featured-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );
		$p_n_e = 0;
		if(!empty($_POST['nearly_expiration'])){
			$p_n_e = $_POST['nearly_expiration'];
		}else{
			$p_n_e = 0;
		}
		$u_id = "";
		if($ownRole == "Admin"){
			$u_id = $_POST['seller_id'];
		}else{
			$u_id = $_SESSION['user']['id'];
		}
		//Saving data into the main table tbl_product
		$statement = $pdo->prepare("INSERT INTO tbl_product(
										u_id,
										p_name,
										p_retail,
										p_wholesale,
										p_w_confirm,
										nearly_expiration,
										critical_level,
										stocks_reorder,
										p_featured_photo,
										p_description,
										p_short_description,
										p_feature,
										p_condition,
										p_return_policy,
										p_total_view,
										p_is_featured,
										p_is_active
									) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute(array(
										$u_id,
										$_POST['p_name'],
										$_POST['p_retail'],
										$wholesale,
										$_POST['p_w_confirm'],
										$p_n_e,
										$_POST['critical_level'],
										$_POST['stocks_reorder'],
										$final_name,
										"",
										$_POST['p_short_description'],
										"",
										"",
										"",
										0,
										$_POST['p_is_featured'],
										$_POST['p_is_active'],
									));
		$getTheLastId = $pdo->lastInsertId();
		$stmt = $pdo->prepare("INSERT INTO tbl_product_category
								(p_id,
								tcat_id,
								mcat_id)
								VALUES (?,?,?)
								");
		$stmt->execute(array($getTheLastId,$_POST['tcat_id'],$_POST['mcat_id']));
		
		if(isset($_POST['p_size'])){
			if(!empty($_POST['p_size'])){
				$i = 0;
				foreach($_POST['p_size'] as $key){
					$statement = "INSERT INTO tbl_product_size (size_id, p_id) VALUES (:s_id, :p_id)";
					$p = [
						":s_id"	=>	$key,
						":p_id"	=>	$ai_id
					];
					$asd = $c->insertData($pdo, $statement, $p);
					$i++;
				}
			}
		}

		if(isset($_POST['color'])) {
			foreach($_POST['color'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$ai_id));
			}
		}
	
    	$success_message = 'Product is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Product</h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">View All</a>
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
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Product Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_name" class="form-control" placeholder="Product name">
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
										<option value="<?php echo $row['tcat_id']; ?>"><?php echo $row['tcat_name']; ?></option>
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
								</select>
							</div>
						</div>
						<?php
						$sql = "SELECT * FROM tbl_size ORDER BY size_name DESC";
						$res = $c->fetchData($pdo, $sql);
						if($res){
							?>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Size <span>*</span></label>
								<div class="col-sm-4">
									<?php
									foreach($res as $row100){
										?>
										<div class="form-check">
											<label for="p_size<?= $row100['size_id']; ?>"><input class="form-check-input p_size" name="p_size[]" id="p_size<?= $row100['size_id']; ?>" type="checkbox" value="<?= $row100['size_id']; ?>"> <?= $row100['size_name']; ?></label>
										</div>
										<?php
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
								<textarea name="p_short_description" class="form-control" cols="20" rows="6" id="editor2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Retail (Current Price) <br><span style="font-size:10px;font-weight:normal;">(In PHP)</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_retail" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Warning Information <span>*</span></label>
							<div class="col-sm-4">
								<div class="form-check">
									<input class="form-check-input p_w_confirm" value="1" type="radio" name="p_w_confirm" id="flexRadioDefault1" required>
									<label class="form-check-label" for="flexRadioDefault1">
										With Expiration
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input p_w_confirm" value="0" type="radio" name="p_w_confirm" id="flexRadioDefault2" required>
									<label class="form-check-label" for="flexRadioDefault2">
										Without Expiration
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Nearly Expiration (Days) <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="nearly_expiration" class="form-control nearly_expiration" required disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Critical Level <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="critical_level" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Stocks Reorder <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="stocks_reorder" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Other Photos</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                            <tr>
			                                <td>
			                                    <div class="upload-btn">
			                                        <input type="file" name="photo[]" style="margin-bottom:5px;">
			                                    </div>
			                                </td>
			                                <td style="width:28px;"><a href="javascript:void()" class="Delete btn btn-danger btn-xs">X</a></td>
			                            </tr>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="Add Item" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>
						</div>
						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Features</label>
							<div class="col-sm-8">
								<textarea name="p_feature" class="form-control" cols="30" rows="10" id="editor3"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Conditions</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Return Policy</label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"></textarea>
							</div>
						</div> -->
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Featured?</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Active?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Add Product</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>