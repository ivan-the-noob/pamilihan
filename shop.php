<?php include_once('header.php'); //unset($_SESSION['cart_p_id']); ?>

	<div class="container mt-5 mb-5">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          	<div class="col-md-9 ftco-animate text-center">
          		<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span>/ <span>Shop</span></p>
            	<h1 class="mb-0 bread">Shop</h1>
          	</div>
        </div>
    </div>
	<?php
	error_reporting(); // TO SHOW ALL ERRORS.
	// error_reporting(0); // TO HIDE ALL ERRORS.

	// SHOW ALL PRODUCTS
	if(!isset($_GET['keyword']) && !isset($_GET['type']) && !isset($_GET['category']) && !isset($_GET['sub_category']) && !isset($_GET['sort_by'])){
		$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE inv.stock_status='Available' AND p.p_is_featured='1' AND p.p_is_active='1' ORDER BY p.p_name ASC";
		$prod = $c->fetchData($pdo, $sqlProduct);
	}else{
		$search = "";
		$category = "";
		$type = "";
		$sub_category = "";
		$p = [];
		if(isset($_GET['keyword'])){
			$search = $_GET['keyword'];
		}
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}
		if(isset($_GET['category'])){
			$category = $_GET['category'];
		}else{
			$category = "all";
		}
		if(isset($_GET['sub_category'])){
			$sub_category = $_GET['sub_category'];
		}else{
			$sub_category = "all";
		}
		if($search != "" && $category != "all" && $sub_category != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_name LIKE :search AND cat.tcat_id = :category AND cat.mcat_id = :sub_category AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":search"		=>	"%$search%",
				":category"		=>	$category,
				":sub_category"	=>	$sub_category
			];
		}else if($category != "all" && $sub_category != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE cat.tcat_id = :category AND cat.mcat_id = :sub_category AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":category"		=>	$category,
				":sub_category"	=>	$sub_category
			];
		}else if($search != "" && $sub_category != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_name LIKE :search AND cat.mcat_id = :sub_category AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":search"		=>	"%$search%",
				":sub_category"	=>	$sub_category
			];
		}else if($search != "" && $category != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_name LIKE :search AND cat.tcat_id = :category AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":search"		=>	"%$search%",
				":category"	=>	$category
			];
		}else if($sub_category != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE cat.mcat_id = :sub_category AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":sub_category"	=>	$sub_category
			];
		}else if($category != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE cat.tcat_id = :category AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":category"	=>	$category
			];
		}else if($search != "all"){
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE p.p_name LIKE :search AND inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_name ASC";
			$p = [
				":search"	=>	"%$search%"
			];
		}else{
			$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_product_category cat ON p.p_id=cat.p_id JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE inv.stock_status='Available' AND p.p_is_active='1' ORDER BY p.p_id ASC";
		}
		$prod = $c->fetchData($pdo, $sqlProduct, $p);
	}
	?>
    <section>
    	<div class="container">
			<?php
			if(isset($_GET['keyword'])){
			?>
			<?php
			$keyword = $_GET['keyword'];
			$sql2 = "SELECT seller.*, user.photo FROM tbl_seller seller LEFT JOIN tbl_user user ON seller.user_id=user.id WHERE seller.business_title LIKE :seller AND seller.status='Verified'";
			$p2 = [
				':seller'		=>	"%$keyword%"
			];
			$stmt1 = $c->fetchData($pdo, $sql2, $p2);
			if($stmt1 != false){
				?>
				<hr/>
				<div class="col-md">
					<p>Shops related to "<span class="text-primary "><?php echo $_GET['keyword']; ?></span>"</p>
				</div>
				<div class="container">
						<div class="row">
				<?php
				foreach($stmt1 as $row1){
					$u_id = $row1['user_id'];
					$sellerId = 0;
					if(isset($_GET['id'])){
						$sellerId = $_GET['id'];
					}
					$countProduct = 0;
					$countRatings = 0;
					$countSold = 0;

					// countProduct
					$sql3 = "SELECT COUNT(p_id) AS countProduct FROM tbl_product WHERE u_id = :u_id";
					$p3 = [
						':u_id'	=>	$u_id
					];
					$stmt3 = $c->fetchData($pdo, $sql3, $p3);
					if($stmt3 != false){
						foreach($stmt3 as $row3){
							$countProduct = $row3['countProduct'];
						}
					}else{
						$countProduct = 0;
					}

					// countSold
					$sql4 = "SELECT COUNT(o.order_id) AS countSold FROM tbl_purchase_item o WHERE o.seller_id = :u_id";
					$p4 = [
						':u_id'	=>	$u_id
					];
					$stmt4 = $c->fetchData($pdo, $sql4, $p4);
					if($stmt4 != false){
						foreach($stmt4 as $row4){
							$countSold = $row4['countSold'];
						}
					}else{
						$countSold = 0;
					}

					if($row1['photo'] == null){
						$photo = "no-photo.png";
					}else{
						$photo = $row1['photo'];
					}
					// countRatings
					// $sql4 = "SELECT COUNT(p_id) AS countProduct FROM tbl_product WHERE u_id = :u_id";
					// $p3 = [
					// 	':u_id'	=>	$u_id
					// ];
					// $stmt3 = $c->fetchData($pdo, $sql3, $p3);
					// if($stmt3 != false){
					// 	foreach($stmt3 as $row2){
					// 		$countProduct = $row2['countProduct'];
					// 	}
					// }else{
					// 	$countProduct = 0;
					// }
					?>
							<?php
							// if(!isset($_GET['search']) && !isset($_GET['type']) && !isset($_GET['category']) && !isset($_GET['sub_category'])){
							// 	$sqlProduct = "SELECT DISTINCT s.business_title, inv.stock_in AS qty, p.* FROM tbl_product p JOIN tbl_seller s ON p.u_id=s.user_id JOIN tbl_inventory inv ON p.p_id=inv.p_id WHERE inv.stock_status='Available' AND p.p_is_featured='1' AND p.p_is_active='1' ORDER BY p.p_name ASC";
							// }
							?>
							<div class="col-md-6 ftco-animate">
								<div class="product px-3 py-3">
									<div class="row">
										<div class="col-md-3">
											<a class="img-prod"><img class="img-fluid" src="assets/uploads/<?php echo $photo;?>" width="100%" height="80%" alt=""></a>
										</div>
										<div class="col-md-8">
											<p class="text-primary"><?php echo $row1['business_title'];?></p>
											<div class="row">
												<div class="col-md-6">
													<p></span><span class="text-secondary"><b>Products: </b></span><?php echo $countProduct;?></p>
												</div>
												<div class="col-md-4">
													<p><span class="text-secondary"><b>Sold: </b></span><?php echo $countSold; ?></p>
												</div>
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
				<?php
			}
			?>
			<hr/>
			<div class="col-md-12 mt-4">
				<p>Products related to "<span class="text-primary"><?php if(isset($_GET['keyword'])){ echo $_GET['keyword'];} ?></span>"</p>
			</div>
			<?php
			}
			?>
    		<div class="container">
				<div class="row align-items-end justify-content-left">
					<form id="searchProduct" method="GET">
						<?php
						if(isset($_GET['keyword'])){
							?>
							<input type="text" name="keyword" value="<?php echo $_GET['keyword'];?>" hidden readonly>
							<?php
						}
						?>
						<div class="row" style="display: none;">
							<!-- <div class="col-md-3">
								<label for="">Type</label>
								<div class="form-group">
									<select name="type" class="form-control" id="type" required>
										<option value="all">Show All</option>
										<option value="product">Product(s)</option>
										<option value="recipe">Recipe(s)</option>
									</select>
								</div>
							</div> -->
							<div class="col-md-5">
								<label for="">Category</label>
								<div class="form-group">
									<select name="category" class="form-control" id="category">
										<?php
										$q1 = "SELECT * FROM tbl_top_category WHERE show_on_menu='1' ORDER BY tcat_name ASC";
										$listCategory = $c->fetchData($pdo, $q1);
										if($listCategory == false){
											?>
											<option value="">No available category</option>
											<?php
										}else{
											?>
											<option value="all">Show All</option>
											<?php
											foreach($listCategory as $cat){
												?>
												<option value="<?php echo $cat['tcat_id'];?>" <?php if(isset($_GET['category'])){ if($_GET['category'] == $cat['tcat_id']){ echo "selected"; } } ?>><?php echo $cat['tcat_name']; ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<label for="">Sub Category</label>
								<div class="form-group">
									<select name="sub_category" class="form-control" id="sub_category">
										
										<?php
										$q1 = "SELECT * FROM tbl_mid_category ORDER BY mcat_name";
										$listCategory = $c->fetchData($pdo, $q1);
										if($listCategory == false){
											?>
											<option value="">No available sub category</option>
											<?php
										}else{
											?>
											<option value="all">Show All</option>
											<?php
											foreach($listCategory as $cat){
												?>
												<option value="<?php echo $cat['mcat_id'];?>" <?php if(isset($_GET['sub_category'])){ if($_GET['sub_category'] == $cat['mcat_id']){ echo "selected"; } } ?>><?php echo $cat['mcat_name']; ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
							</div>
							<!-- <div class="col-md-2">
								<label for="">Sort by</label>
								<div class="form-group">
									<select name="sort_by" class="form-control" id="sort_by" required>
										<option value="asc">A-Z</option>
										<option value="desc">Z-A</option>
									</select>
								</div>
							</div> -->
							<div class="col-md-3">
								<label for=""></label>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="submit" name="filter" value="Find" class="btn btn-primary p-3" style="border-radius: 5% !important;">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<a href="./shop.php" name="clear" value="Clear" class="btn btn-primary p-3" style="border-radius: 5% !important;">Clear</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!-- <div class="col-md-10 mb-5 text-center">
						<ul class="product-category">
							<li><a href="#" class="active">All</a></li>
							<li><a href="#">Vegetables</a></li>
							<li><a href="#">Fruits</a></li>
							<li><a href="#">Juice</a></li>
							<li><a href="#">Dried</a></li>
						</ul>
					</div> -->
				</div>
			</div>
			<!-- <hr/> -->
    		<div class="row align-items-end">
				<?php
				if($prod != false){
					foreach($prod as $row){
						$count = $row['p_id'];

						// for Minimum Price
						$sq20 = "SELECT MAX(s.size_value) AS highestValue FROM tbl_product_size ps LEFT JOIN tbl_size s ON ps.size_id=s.size_id WHERE ps.p_id = :pid";
						$p10 = [
							":pid"	=>	$row['p_id']
						];
						$res10 = $c->fetchData($pdo, $sq20, $p10);
						$showMinPrice = "";
						$minPrice = 1;
						if($res10){
							foreach($res10 as $hV){
								if($hV['highestValue'] == ""){
									$hV['highestValue'] = 1;
								}else{
									$minPrice = $row['p_retail'] / $hV['highestValue'];
									$showMinPrice = $php.number_format($minPrice).' - ';
								}
							}
						}
						?>
						<div class="col-md-6 col-lg-3 ftco-animate">
							<div class="product" style="height: 400px !important;">
								<a href="product-single.php?id=<?php echo $row['p_id'];?>" class="img-prod"><img class="img-fluid" src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" style="height: 225px !important; width: 100% !important;" alt="<?php echo $row['p_name'];?>">
									<span class="status"><?= $showMinPrice; ?><?php echo $php;?><span><?php echo number_format($row['p_retail'], 2);?></span></span>
									<div class="overlay"></div>
								</a>
								<div class="text py-3 pb-4 px-3 text-center">
									<h3><a href="product-single.php?id=<?php echo $row['p_id'];?>"><?php echo $row['p_name'];?> <?php echo $row['p_short_description'];?></a></h3>
									<p><a href="?keyword=<?= $row['business_title'];?>" style="text-decoration: underline;"><?php echo $row['business_title'];?></a></p>
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
				}else{
					?>
					<div class="col-md-12">
						<p class="text-danger text-center">No products found.</p>
					</div>
					<?php
				}
				?>
    		</div>
			<hr/>
    		<!-- <div class="row mt-5">
				<div class="col text-center">
					<div class="block-27">
					<ul>
						<li><a href="#">&lt;</a></li>
						<li class="active"><span>1</span></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#">5</a></li>
						<li><a href="#">&gt;</a></li>
					</ul>
					</div>
				</div>
        	</div> -->
    	</div>
    </section>

	<?php include_once('footer.php');?>
	<script>
		$(document).ready(function(){
			$(document).on('change', '#category', function(e){
				e.preventDefault();
				var catVal = $(this).val();
				if(catVal !== 'all'){
					
				}else{

				}
			});
		});
	</script>