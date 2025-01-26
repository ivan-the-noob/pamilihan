<?php include_once('header.php');?>

<?php
$prod_id = "";
$message1 = "";
// unset($_SESSION['cart_p_id']);
if(isset($_SESSION['customer'])){
	if(!isset($_GET['id'])){
		header('Location: index.php');
		exit;
	}else{
		error_reporting();
		if($_GET['id'] > 0){
			$name = $_GET['id'];
			if (strpos($name, "'") !== false || strpos($name, '"') !== false) {
				// Redirect to index.php if quotes are found
				header("Location: index.php");
				exit;
			}else{
				$prod_id = $_GET['id'];
			}
		}else{
			header("Location: shop.php");
			exit;
		}
		$q1 = "SELECT inv.stock_in AS qty, seller.business_title, p.* FROM tbl_product p JOIN tbl_seller seller ON p.u_id=seller.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_id=:p_id";
		$p = [
			':p_id'	=>	$prod_id
		];
		$prod = $c->fetchData($pdo, $q1, $p);
		if($prod == true){
			foreach($prod as $row) {
				$p_seller_id = $row['u_id'];
				$p_seller = $row['business_title'];
				$p_name = $row['p_name'];
				$p_old_price = "";
				$p_current_price = $row['p_retail'];
				$p_qty = $row['qty'];
				$p_featured_photo = $row['p_featured_photo'];
				$p_description = $row['p_description'];
				$p_short_description = $row['p_short_description'];
				$p_feature = $row['p_feature'];
				$p_condition = $row['p_condition'];
				$p_return_policy = $row['p_return_policy'];
				$p_total_view = $row['p_total_view'];
				$p_is_featured = $row['p_is_featured'];
				$p_is_active = $row['p_is_active'];
				$ecat_id = $row['ecat_id'];
			}
			$p_total_view = $p_total_view + 1;
			$statement = "UPDATE tbl_product SET p_total_view=:totalView WHERE p_id=:p_id";
			$p = [
				':totalView'	=>	$p_total_view,
				':p_id'			=>	$prod_id
			];
	
			$res = $c->updateData($pdo, $statement, $p);
		}else{
			header("Location: shop.php");
			exit;
		}
	
		if(isset($_POST['form_add_to_cart'])) {
	
			// getting the currect stock of this product
			$statement = $pdo->prepare("SELECT inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_id=?");
			$statement->execute(array($prod_id));
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
			foreach ($result as $row) {
				$current_p_qty = $row['qty'];
			}
			$setSize = "0";
			if(isset($_POST['p_size'])){
				$setSize = $_POST['p_size'];
			}else{
				$setSize = "0";
			}
			if($_POST['p_qty'] > $current_p_qty):
				$temp_msg = 'Sorry! There are only '.$current_p_qty.' item(s) in stock';
				?>
				<script type="text/javascript">alert('<?php echo $temp_msg; ?>');</script>
				<?php
			else:
			if(isset($_SESSION['cart_p_id']))
			{
				$arr_cart_p_id = array();
				$arr_cart_p_qty = array();
				$arr_cart_p_current_price = array();
				$arr_cart_p_size = array();
		
				$i=0;
				foreach($_SESSION['cart_p_id'] as $key => $value) 
				{
					$i++;
					$arr_cart_p_id[$i] = $value;
				}

				$i=0;
				foreach($_SESSION['cart_p_size'] as $key => $value) 
				{
					$i++;
					$arr_cart_p_size[$i] = $value;
				}
		
				$added = 0;
	
				for($i=1;$i<=count($arr_cart_p_id);$i++) {
					if( ($arr_cart_p_id[$i]==$_REQUEST['id']) && ($arr_cart_p_size[$i]==$setSize)) {
						$added = 1;
						break;
					}
				}
				if($added == 0) {
					$i=0;
					foreach($_SESSION['cart_p_id'] as $key => $res) 
					{
						$i++;
					}
					$new_key = $i+1;
					$_SESSION['cart_p_seller_id'][$new_key] = $_POST['p_seller'];
					$_SESSION['cart_p_type'][$new_key] = "product";
					$_SESSION['cart_p_size'][$new_key] = $setSize;
					$_SESSION['cart_p_id'][$new_key] = $prod_id;
					$_SESSION['cart_p_qty'][$new_key] = $_POST['p_qty'];
					$_SESSION['cart_p_current_price'][$new_key] = $_POST['p_current_price'];
					$_SESSION['cart_p_name'][$new_key] = $_POST['p_name'];
					$_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'];
		
					$message1 = 'Product is added to the cart successfully!';

				} else {
					$message1 = 'This product is already added to the shopping cart.';
				}
				
			}
			else
			{
				$_SESSION['cart_p_seller_id'][1] = $_POST['p_seller'];
				$_SESSION['cart_p_type'][1] = "product";
				$_SESSION['cart_p_size'][1] = $setSize;
				$_SESSION['cart_p_id'][1] = $prod_id;
				$_SESSION['cart_p_qty'][1] = $_POST['p_qty'];
				$_SESSION['cart_p_current_price'][1] = $_POST['p_current_price'];
				$_SESSION['cart_p_name'][1] = $_POST['p_name'];
				$_SESSION['cart_p_featured_photo'][1] = $_POST['p_featured_photo'];
		
				$message1 = 'Product is added to the cart successfully!';
				
			}
			endif;
			$redirect = "";
			$redirect = "cart.php?id=";
			$redirect .= $prod_id;
			echo "<script>alert('$message1'); window.location.href='$redirect';</script>";
			
		}
	}
}else{
	header("Location: login.php");
	exit;
}
?>

<?php
// if($error_message1 != '') {
//     echo "<script>alert('".$error_message1."')</script>";
// }
// if($success_message1 != '') {
// 	echo "<script>alert('".$success_message1."');</script>";
// 	header("Location: product-single.php?id=".$prod_id);
// }
?>

      <div class="container mt-3">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span>/ <span class="mr-2"><a href="shop.php">Shop</a></span>/ <span><?php echo $p_name; ?></span></p>
            <h1 class="mb-0 bread"><?php echo $p_name; ?></h1>
          </div>
        </div>
      </div>
	
    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-5 mb-5 ftco-animate">
    				<a href="assets/uploads/<?php echo $row['p_featured_photo']; ?>" class="image-popup w-100"><img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" class="img-fluid w-100" alt="<?php echo $p_name; ?>"></a>
    			</div>
    			<div class="col-lg-7 product-details pl-md-5 ftco-animate">
    				<h3><?php echo $p_name; ?><span class="text text-secondary"><small>(<?php echo $p_seller; ?>)</small></span></h3>
    				<div class="rating d-flex">
						<!-- <p class="text-left mr-4">
							<a href="#" class="mr-2">5.0</a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
						</p> -->
						<?php
						// countSold
						$countSold = 0;
						$sql4 = "SELECT COUNT(o.product_id) AS countSold FROM tbl_purchase_item o WHERE o.product_id = :p_id";
						$p4 = [
							':p_id'	=>	$_GET['id']
						];
						$stmt4 = $c->fetchData($pdo, $sql4, $p4);
						if($stmt4 != false){
							foreach($stmt4 as $row4){
								$countSold = $row4['countSold'];
							}
						}else{
							$countSold = 0;
						}

						// for Minimum Price
						$sq20 = "SELECT MAX(s.size_value) AS highestValue FROM tbl_product_size ps LEFT JOIN tbl_size s ON ps.size_id=s.size_id WHERE ps.p_id = :pid";
						$p10 = [
							":pid"	=>	$_GET['id']
						];
						$res10 = $c->fetchData($pdo, $sq20, $p10);
						$showMinPrice = "";
						$showMinPriceAtSize = "";
						$minPrice = 1;
						if($res10){
							foreach($res10 as $hV){
								if($hV['highestValue'] == ""){
									$hV['highestValue'] = 1;
								}else{
									$minPrice = $p_current_price / $hV['highestValue'];
									$showMinPrice = $php.number_format($minPrice).' - ';
								}
							}
						}
						?>
						<p class="text-left">
							<a class="mr-2" style="color: #000;"><?= $countSold; ?> <span style="color: #bbb;">Sold</span></a>
						</p>
					</div>
					<p class="price"><span><?= $showMinPrice; ?><?php echo $php; ?><?php echo number_format($p_current_price, 2);?></span></p>
					<p><?php echo $p_short_description; ?></p>
					<div class="row mt-4">
						<div class="w-100"></div>
						<form action="" method="post">
							<?php
							$sql10 = "SELECT s.size_name, s.size_value, ps.* FROM tbl_product_size ps JOIN tbl_size s ON ps.size_id=s.size_id WHERE ps.p_id=:p_id ORDER BY s.size_name DESC";
							$p2 = [
								":p_id"	=> $_GET['id']
							];
							$res10 = $c->fetchData($pdo, $sql10, $p2);
							if($res10){
								?>
								<div class="col-md-6">
									<div class="form-group d-flex">
										<div class="select-wrap">
											<div class="icon"><span class="ion-ios-arrow-down"></span></div>
											<select name="p_size" id="p_size" class="form-control" style="width: 200px;" required>
												<option value="">Select Size</option>
												<?php
												foreach($res10 as $row10){
													$showMinPriceAtSize = $p_current_price / $row10['size_value'];
													?>
													<option value="<?= $row10['size_id']; ?>"><?= $row10['size_name'];?> (<?= $php; ?><?php echo number_format($showMinPriceAtSize) ?>)</option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<?php
							}
							?>
							<input type="hidden" name="p_seller" value="<?php echo $p_seller_id; ?>">
							<input type="hidden" name="p_current_price" id="p_current_price" value="<?php echo $p_current_price; ?>">
							<input type="hidden" name="p_name" value="<?php echo $p_name; ?>">
							<input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>">
							<div class="input-group col-md-7 d-flex mb-3">
								<span class="input-group-btn mr-2">
									<button type="button" class="quantity-left-minus btn"  data-type="minus" data-field="">
									<i class="ion-ios-remove"></i>
									</button>
								</span>
								<input type="text" id="quantity" name="p_qty" class="form-control input-number" value="1" min="1" max="<?php echo $p_qty; ?>" readonly>
								<span class="input-group-btn ml-2">
									<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
									<i class="ion-ios-add"></i>
								</button>
								</span>
							</div>
							<div class="btn-cart btn-cart1">
								<input type="submit" class="btn btn-black btn-block py-3 px-5" value="Add to Cart" name="form_add_to_cart">
							</div>
						</form>
					</div>
				</div>
    		</div>
    	</div>
    </section>
	<hr/>
	<?php
	$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_id != :p_id AND p.p_name LIKE :p_name AND inv.stock_status='Available' AND p.p_is_featured='1' AND p.p_is_active='1' ORDER BY p.p_id ASC";
	
	$p = [
		':p_id'		=>	$prod_id,
		':p_name'	=>	"%$p_name%"
	];

	$prod = $c->fetchData($pdo, $sqlProduct, $p);
	
	if($prod == true){
	?>

	<section class="ftco-section">
    	<div class="container">
				<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<span class="subheading">Products</span>
            <h2 class="mb-4">Related Products</h2>
          </div>
        </div>   		
    	</div>
    	<div class="container">
    		<div class="row">
				<?php
				foreach($prod as $row){
				?>

				<div class="col-md-6 col-lg-3 ftco-animate">
					<div class="product" style="height: 400px !important;">
						<a href="#" class="img-prod"><img class="img-fluid" src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" style="height: 225px !important; width: 100% !important;" alt="<?php echo $row['p_name'];?>">
							<span class="status"><?php echo $php;?><span><?php echo number_format($row['p_retail'], 2);?></span></span>
							<div class="overlay"></div>
						</a>
						<div class="text py-3 pb-4 px-3 text-center">
							<h3><a href="product-single.php?id=<?php echo $row['p_id'];?>"><?php echo $row['p_name'];?> <?php echo $row['p_short_description'];?></a></h3>
							<p><a href="#" style="text-decoration: underline;"><?php echo $row['business_title'];?></a></p>
							<div class="pricing">
								<p class="price"><span></span></p><br>
							</div>
							<div class="bottom-area d-flex px-3">
								<div class="m-auto d-flex">
									<?php
									if(isset($_SESSION['customer'])){
										?>
										<a href="product-single.php?id=<?php echo $row['p_id'];?>" class="add-to-cart d-flex justify-content-center align-items-center text-center" style="border-radius: 5% !important; width: 100% !important;">
											<span class="px-2">Add to Cart <i class="ion-ios-cart"></i></span>
										</a>
										<?php
									}else{
										?>
										<a href="login.php?page=<?php echo $cur_page;?>" class="add-to-cart d-flex justify-content-center align-items-center text-center" style="border-radius: 5% !important; width: 100% !important;">
											<span class="px-2">Add to Cart <i class="ion-ios-cart"></i></span>
										</a>
										<?php
									}
									?>
									<!-- <a href="#" class="buy-now d-flex justify-content-center align-items-center mx-1">
										<span><i class="ion-ios-cart"></i></span>
									</a>
									<a href="#" class="heart d-flex justify-content-center align-items-center ">
										<span><i class="ion-ios-heart"></i></span>
									</a> -->
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
				}
				?>
    		</div>
    	</div>
    </section>

	<?php
	}
	?>
    
	<?php include_once('footer.php');?>
	<script>
		$(document).ready(function(){
			$(document).on('change', '#p_size', function(e){
				e.preventDefault();
				var sizeId = $(this).val();
				var prodId = "<?= $_GET['id']; ?>";
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{'sizeId':sizeId, 'prodId':prodId, 'onChangeSize':true},
					dataType:"HTML",
					success:function(data){
						if(data != "error"){
							$('#p_current_price').val(data);
							console.log(data);
						}
					}
				});
			});

		var quantitiy=0;
		   $('.quantity-right-plus').click(function(e){
		        
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        var maxQuantity = parseInt($('#quantity').attr('max'));
		        // If is not undefined
		            
		            if(quantity >= maxQuantity){
						// do nothing
					}else{
						$('#quantity').val(quantity + 1);
					}

		          
		            // Increment
		        
		    });

		     $('.quantity-left-minus').click(function(e){
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		      
		            // Increment
		            if(quantity>1){
		            $('#quantity').val(quantity - 1);
		            }
		    });
		    
		});
	</script>