<?php require_once('header.php'); ?>

<?php
if(isset($_SESSION['user']['role'])) {
	$ownRole = $_SESSION['user']['role'];
}
if(isset($_POST['form1'])) {
	$valid = 1;
	$price = array();
    if($ownRole == "Admin"){
		if(empty($_POST['seller_id'])) {
			$valid = 0;
			$error_message .= "You must have to select a seller<br>";
		}
	}

	if(empty($_POST['r_name'])) {
		$valid = 0;
		$error_message .= "Recipe name can not be empty<br>";
	}

	if(empty($_POST['r_description'])) {
		$desc = "";
	}else{
		$desc = $_POST['r_description'];
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

	$u_id = "";
	if($ownRole == "Admin"){
		$u_id = $_POST['seller_id'];
	}else{
		$u_id = $_SESSION['user']['id'];
	}

	$r_name = $_POST['r_name'];
	$sql = "SELECT * FROM tbl_recipe WHERE u_id=? AND r_name=?";
	$res = $pdo->prepare($sql);
	$res->execute(array($u_id,$r_name));
	if($res->rowCount() > 0){
		$valid = 0;
		$error_message .= 'Recipe already exist!';
	}

    if($valid == 1) {

    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_recipe'");
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
                    move_uploaded_file($photo_temp[$i],"../assets/uploads/recipe_photos/".$final_name1[$m]);
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
		$final_name = 'recipe-featured-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

		//Saving data into the main table tbl_recipe and tbl_recipe_product
		//$u_id = if seller, get the id from the user, if admin, get the id of selected seller.
		//$final_name = for photo
		$i = 0;
		$instR = "INSERT INTO tbl_recipe SET
		u_id=?,
		r_name=?,
		r_description=?,
		r_featured_photo=?,
		r_is_active=1
		";
		$resR = $pdo->prepare($instR);
		$resR->execute(array($u_id,$r_name,$desc,$final_name));
		$getLastRecipeId = $pdo->lastInsertId();

		foreach($_POST['product'] as $r10 => $val){
			$rule = 0;
			$price = 0;
			$search = "SELECT p.p_retail, s.size_value FROM tbl_product p LEFT JOIN tbl_size s ON s.size_id=? WHERE p_id=?";
			$resS = $pdo->prepare($search);
			$resS->execute(array($_POST['size'][$i],$val));
			if($resS->rowCount() > 0){
				foreach($resS as $r11){
					$rule = (int)$r11['p_retail'] / (int)$r11['size_value'];
				}
			}
			$instP = "INSERT INTO tbl_recipe_product SET
			u_id=?,
			r_id=?,
			p_id=?,
			s_id=?,
			quantity=?
			";
			$resP = $pdo->prepare($instP);
			$resP->execute(array($u_id,$getLastRecipeId,$_POST['product'][$i],$_POST['size'][$i],$_POST['quantity'][$i]));
			$i++;
		}
	
    	$success_message = 'Recipe is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add Recipe</h1>
	</div>
	<div class="content-header-right">
		<a href="recipe.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-2 control-label">Seller <span>*</span></label>
							<div class="col-sm-8">
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
							<label for="" class="col-sm-2 control-label">Recipe Name <span>*</span></label>
							<div class="col-sm-8">
								<input type="text" name="r_name" class="form-control" placeholder="Recipe name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Description</label>
							<div class="col-sm-8">
								<textarea name="r_description" class="form-control" cols="20" rows="6" id="editor2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Ingredients *</label>
							<div class="col-sm-8" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                            <tr>
			                                <td>
												<div class="row">
													<div id="product-list">
														<div class="recipe-product">
															<div class="col-md-4" style="margin-top: 5px;">
																<select name="product[]" class="form-control select2 product" required>
																	<option value="">Select a product</option>
																	<?php
																	$sql = "SELECT p.*, seller.business_title FROM tbl_product p LEFT JOIN tbl_seller seller ON p.u_id=seller.user_id WHERE p.p_is_active=1 ORDER BY id DESC";
																	$res = $c->fetchData($pdo, $sql);
																	if($res){
																		foreach($res as $row101){
																			?>
																			<option value="<?= $row101['p_id'];?>"><?= $row101['p_name'];?> <small>(<?= $row101['business_title']; ?>)</small></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</div>
															<div class="col-md-3" style="margin-top: 5px;">
																<select name="size[]" class="form-control select2 size" required>
																	<option value="">Select size</option>
																	<?php
																	$sql = "SELECT * FROM tbl_size ORDER BY size_name DESC";
																	$res = $c->fetchData($pdo, $sql);
																	if($res){
																		foreach($res as $row10){
																			?>
																			<option value="<?= $row10['size_id'];?>"><?= $row10['size_name'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</div>
															<div class="col-md-4" style="margin-top: 5px;">
																<div class="row">
																	<div class="col-md-3">
																		<button class="btn btn-default btn-xl minusQuantity">-</button>
																	</div>
																	<div class="col-md-6">
																		<input type="text" name="quantity[]" class="form-control quantity text-center" placeholder="Quantity..." min="1" max="50" value="1" readonly>
																	</div>
																	<div class="col-md-3">
																		<button class="btn btn-default btn-xl plusQuantity">+</button>
																	</div>
																</div>
															</div>
															<div class="col-md-1" style="margin-top: 5px;">
															<button type="button" id="btnAddNewProduct" value="Add Item" style="margin-top: 9px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs btnAddNewProduct">Add Item</button>
															</div>
														</div>
													</div>
												</div>
			                                </td>
											<td></td>
			                                <td style="width:28px;"></td>
			                            </tr>
			                        </tbody>
			                    </table>
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
							<label for="" class="col-sm-2 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-8" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Is Active?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-2">
								<button type="submit" class="btn btn-success btn-block pull-left" name="form1">Add Recipe</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>