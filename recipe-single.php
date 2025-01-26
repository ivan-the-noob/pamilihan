<?php include_once('header.php');?>

<?php
$prod_id = "";
$message1 = "";

if(isset($_SESSION['customer'])){
	if(!isset($_GET['id'])){
		header('Location: index.php');
		exit;
	}else{
		error_reporting();
		$prod_id = "";
		$type = "";
		if($_GET['id'] > 0){
			$name = $_GET['id'];
			if (strpos($name, "'") !== false || strpos($name, '"') !== false) {
				// Redirect to index.php if quotes are found
				header("Location: index.php");
				exit;
			}else{
				if($_GET['type'] == "recipe"){
					$prod_id = $_GET['id'];
					$type = $_GET['type'];
				}else{
					header("Location: index.php");
					exit;
				}
			}
		}else{
			header("Location: shop.php");
			exit;
		}
		$q1 = "SELECT r.*, s.business_title FROM tbl_recipe r LEFT JOIN tbl_seller s ON r.u_id=s.user_id WHERE r.r_id=:rid";
		$p = [
			':rid'	=>	$prod_id
		];
		$prod = $c->fetchData($pdo, $q1, $p);
		if($prod == true){
			foreach($prod as $row) {
				$r_seller_id = $row['u_id'];
				$r_seller_name = $row['business_title'];
				$r_name = $row['r_name'];
				if($row['r_total_view'] == ""){
					$p_total_view = 0;
				}else{
					$p_total_view = $row['r_total_view'];
				}
				$r_featured_photo = $row['r_featured_photo'];
			}
			$p_total_view = $p_total_view + 1;
			$statement = "UPDATE tbl_recipe SET r_total_view=:totalView WHERE r_id=:r_id";
			$p = [
				':totalView'	=>	$p_total_view,
				':r_id'			=>	$prod_id
			];
	
			$res = $c->updateData($pdo, $statement, $p);
		}else{
			header("Location: shop.php");
			exit;
		}
	
		if(isset($_POST['form_add_to_cart'])) {

			//getting the currect stock of this product
			$setSize = "0";
			if(isset($_POST['p_size'])){
				$setSize = $_POST['p_size'];
			}else{
				$setSize = "0";
			}
			$arr_cart_p_id = array();
			$arr_cart_p_qty = array();
			$arr_cart_p_current_price = array();
			$arr_cart_p_size = array();
			$cp = 1;
			if(isset($_SESSION['cart_p_id'])){
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
					if(isset($_POST['checkedProducts'])){
						$iii = 0;
						foreach($_SESSION['cart_p_id'] as $key => $res) 
						{
							$i++;
						}
						$new_key = $i+1;
						foreach($_POST['checkedProducts'] as $checked){
							$_SESSION['cart_p_seller_id'][$new_key] = $r_seller_id;
							$_SESSION['cart_p_type'][$new_key] = "product";
							$_SESSION['cart_p_size'][$new_key] = $_POST['p_size'][$iii];
							$_SESSION['cart_p_id'][$new_key] = $checked;
							$_SESSION['cart_p_qty'][$new_key] = $_POST['p_qty'][$iii];
							$_SESSION['cart_p_current_price'][$new_key] = $_POST['p_current_price'][$iii];
							$_SESSION['cart_p_name'][$new_key] = $_POST['p_name'][$iii];
							$_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'][$iii];
							$iii++;
							$new_key++;
						}
					}

					$message1 = 'Product is added to the cart successfully!';

				} else {
					$message1 = 'This product is already added to the shopping cart.';
				}
				
			}
			else
			{
				if(isset($_POST['checkedProducts'])){
					$i = 0;
					$ii = 1;
					foreach($_POST['checkedProducts'] as $checked){
						$_SESSION['cart_p_seller_id'][$ii] = $r_seller_id;
						$_SESSION['cart_p_type'][$ii] = "product";
						$_SESSION['cart_p_size'][$ii] = $_POST['p_size'][$i];
						$_SESSION['cart_p_id'][$ii] = $checked;
						$_SESSION['cart_p_qty'][$ii] = $_POST['p_qty'][$i];
						$_SESSION['cart_p_current_price'][$ii] = $_POST['p_current_price'][$i];
						$_SESSION['cart_p_name'][$ii] = $_POST['p_name'][$i];
						$_SESSION['cart_p_featured_photo'][$ii] = $_POST['p_featured_photo'][$i];
						$ii++;
						$i++;
					}
				}

				$message1 = 'Product is added to the cart successfully!';
			}
			$redirect = "";
			$redirect = "recipe-single.php?id=";
			$redirect .= $prod_id."&type=recipe";
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
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span>/ <span class="mr-2"><a href="recipe.php">Recipe</a></span>/ <span><?= $r_name; ?></span></p>
            <h1 class="mb-0 bread"><?= $r_name ?></h1>
          </div>
        </div>
      </div>
	
    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-5 mb-5 ftco-animate">
    				<a href="assets/uploads/<?= $r_featured_photo; ?>" class="image-popup w-100"><img src="assets/uploads/<?= $r_featured_photo; ?>" class="img-fluid w-100" alt="<?= $r_name; ?>"></a>
    			</div>
    			<div class="col-lg-7 product-details pl-md-5 ftco-animate">
    				<h3><?= $r_name; ?><span class="text text-secondary"><small>(<?= $r_seller_name; ?>)</small></span></h3>
					<hr/>
    				<!-- <div class="rating d-flex">
						<p class="text-left mr-4">
							<a href="#" class="mr-2">5.0</a>
							<a href="#"><span class="ion-ios-star"></span></a>
							<a href="#"><span class="ion-ios-star-half"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
							<a href="#"><span class="ion-ios-star-outline"></span></a>
						</p>
					</div> -->
					<label for=""><b style="color: black;">Description:</b></label>
					<div class="col-md-12" id="dsa">
						<?= $row['r_description'] ?>
					</div>
					<hr/>
					<div class="row mt-3">
						<div class="col-md-12">
							<label for=""><b style="color: black;">Ingredients:</b></label>
						</div>
						<div class="w-100"></div>
						<form action="" method="post">
							<div class="col-md-12">
								<?php
								$sql = "SELECT p.p_name, p.p_featured_photo, p.u_id, p.p_id, p.p_retail, s.size_id, s.size_name, s.size_value, rp.quantity, rp.r_id, rp.p_id AS prodId FROM tbl_recipe_product rp LEFT JOIN tbl_product p ON rp.p_id=p.p_id LEFT JOIN tbl_size s ON rp.s_id=s.size_id WHERE rp.r_id=:rid";
								$p1 = [
									":rid"	=>	$_REQUEST['id']
								];
								$r1 = $c->fetchData($pdo, $sql, $p1);
								$totalPrice = 0;
								if($r1){
									foreach($r1 as $row1){
										?>
										<div class="form-check">
											<input class="form-check-input checkedProducts" data-id="<?= $row1['prodId']; ?>" type="checkbox" name="checkedProducts[]" value="<?= $row1['prodId']; ?>" id="" checked>
											<label class="form-check-label" for="">
												<?= $row1['p_name']; ?><small>(<?= $row1['size_name']; ?>)</small> x<?= $row1['quantity']; ?> (<b style="color: black !important;"><?php echo $php; echo number_format($row1['p_retail']/$row1['size_value']); ?></b>)
											</label>
										</div>
										<input hidden type="checkbox" name="p_size[]" data-id="<?= $row1['prodId']; ?>"  class="checkedProducts" value="<?= $row1['size_id']; ?>" checked>
										<input hidden type="checkbox" name="p_seller[]" data-id="<?= $row1['prodId']; ?>"  class="checkedProducts" value="<?php echo $row1['u_id']; ?>" checked>
										<input hidden type="checkbox" name="p_current_price[]" data-id="<?= $row1['prodId']; ?>"  class="checkedProducts" id="p_current_price" value="<?php echo (number_format($row1['p_retail']/$row1['size_value'])); ?>" checked>
										<input hidden type="checkbox" name="p_name[]" data-id="<?= $row1['prodId']; ?>"  class="checkedProducts" value="<?php echo $row1['p_name']; ?>" checked>
										<input hidden type="checkbox" name="p_featured_photo[]" data-id="<?= $row1['prodId']; ?>"  class="checkedProducts" value="<?php echo $row1['p_featured_photo']; ?>" checked>
										<input hidden type="checkbox" id="quantity" name="p_qty[]" data-id="<?= $row1['prodId']; ?>"  class="checkedProducts" class="form-control input-number" value="<?= $row1['quantity'];?>" checked>
										<?php
										$totalPrice += $row1['p_retail']/$row1['size_value'];
									}
								}
								?>
								<br>
								<p class="text-danger"><small>You can change the quantity of each product selected when you add it to your cart!</small></p>
							</div>
							<br>
							<!-- <div class="input-group col-md-7 d-flex mb-3">
								<p class="text-danger"><small><i>You can adjust the quantity per product inside your cart.</i></small></p>
								<span class="input-group-btn mr-2">
									<button type="button" class="quantity-left-minus btn"  data-type="minus" data-field="">
									<i class="ion-ios-remove"></i>
									</button>
								</span>
								<input type="text" id="quantity" name="p_qty" class="form-control input-number" value="1" min="1" max="50" readonly>
								<span class="input-group-btn ml-2">
									<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
									<i class="ion-ios-add"></i>
								</button>
								</span>
							</div> -->
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
$s = "SELECT *, s.business_title FROM tbl_recipe r LEFT JOIN tbl_seller s ON r.u_id=s.user_id WHERE r.r_id != :rid AND r.r_name LIKE :r_name AND r.r_is_active=1";
$p = [
	':rid'		=>	$_REQUEST['id'],
	':r_name'	=>	"%$r_name%",
];
$r = $c->fetchData($pdo, $s, $p);
if($r){
?>
	<section class="ftco-section">
    	<div class="container">
				<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
          	<span class="subheading">Recipes</span>
            <h2 class="mb-4">Related Recipes</h2>
          </div>
        </div>
    	</div>
    	<div class="container">
    		<div class="row">
				<?php
				foreach($r as $row){
					?>
					<div class="col-md-5 col-lg-3 ftco-animate">
						<div class="recipe product" style="height: 625px !important;">
							<a href="recipe-single.php?id=<?= $row['r_id']; ?>&type=recipe" class="img-prod"><img class="img-fluid" src="assets/uploads/<?= $row['r_featured_photo']; ?>" style="height: 250px !important; width: 90% !important; margin: 5%;" alt="<?= $row['r_name']; ?>">
								<!-- <span class="status"></span> -->
								<div class="overlay"></div>
							</a>
							<div class="text py-3 pb-4 px-3 justify-content-left">
								<h3 class="text-center"><a href="#"><?= $row['r_name']; ?></a></h3>
								<p class="text-center"><a href="#" style="text-decoration: underline;"><?= $row['business_title']; ?></a></p>
								<hr>
								<div class="content">
									<ul>
										<?php
										$sql = "SELECT p.p_name, s.size_name, rp.quantity FROM tbl_recipe_product rp LEFT JOIN tbl_product p ON rp.p_id=p.p_id LEFT JOIN tbl_size s ON rp.s_id=s.size_id WHERE rp.r_id=:pid LIMIT 3";
										$p1 = [
											":pid"	=>	$row['r_id']
										];
										$r1 = $c->fetchData($pdo, $sql, $p1);
										if($r1){
											foreach($r1 as $row1){
												?>
												<li><?= $row1['p_name']; ?><small>(<?= $row1['size_name']; ?>)</small> x<?= $row1['quantity']; ?></li>
												<?php
											}
											?>
											<a href="recipe-single.php?id=<?= $row['r_id']; ?>&type=recipe">More...</a>
											<?php
										}
										?>
									</ul>
								</div>
								<div class="pricing">
									<p class="price"><span></span></p><br>
								</div>
								<div class="bottom-area d-flex px-3">
									<div class="m-auto d-flex">
										<a href="recipe-single.php?id=<?= $row['r_id']; ?>&type=recipe" class="add-to-cart d-flex justify-content-center align-items-center text-center" style="border-radius: 5% !important; width: 100% !important;">
											<span class="px-2">View Details <i class="ion-ios-document"></i></span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}?>
			</div>
    	</div>
    </section>
	<?php
?>
    		
	<?php include_once('footer.php');?>
	<script>
		$(document).ready(function(){
			$(document).on('click', '.form-check-input.checkedProducts', function() {
				var dataId = $(this).data('id');
				if($(this).is(':checked')) {
					$('.checkedProducts[data-id="' + dataId + '"]').prop('checked', true);
				}else{
					$('.checkedProducts[data-id="' + dataId + '"]').prop('checked', false);
				}
			});
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