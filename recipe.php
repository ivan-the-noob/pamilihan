<?php include_once('header.php'); //unset($_SESSION['cart_p_id']); ?>

	<div class="container mt-5 mb-5">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          	<div class="col-md-9 ftco-animate text-center">
          		<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span>/ <span>Recipe</span></p>
            	<h1 class="mb-0 bread">Recipe</h1>
          	</div>
        </div>
    </div>
	<?php
	error_reporting(); // TO SHOW ALL ERRORS.
	?>
    <section>
    	<div class="container">
    		<div class="container">
				<div class="row align-items-end justify-content-left">
					<form id="searchProduct" method="GET">
						<div class="row">
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
							<div class="col-md-8">
								<label for="">Search Recipe...</label>
								<div class="form-group">
									<input type="text" name="keyword" class="form-control keyword" placeholder="Recipe name..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<label for=""></label>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="submit" name="filter" value="Find" class="btn btn-primary p-3" style="border-radius: 5% !important;">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<a href="./recipe.php" name="clear" value="Clear" class="btn btn-primary p-3" style="border-radius: 5% !important;">Clear</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<hr/>
    		<div class="row align-items-end">
			<?php
			$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

			$s = "SELECT *, s.business_title 
				FROM tbl_recipe r 
				LEFT JOIN tbl_seller s ON r.u_id = s.user_id 
				WHERE r.r_is_active = 1";

			if (!empty($keyword)) {
				$s .= " AND r.r_name LIKE :keyword";
			}

			$params = [];
			if (!empty($keyword)) {
				$params[':keyword'] = '%' . $keyword . '%';
			}

			$r = $c->fetchData($pdo, $s, $params);

			if ($r) {
				foreach ($r as $row) {
        ?>
        <div class="col-md-5 col-lg-3 ftco-animate">
            <div class="recipe product" style="height: 625px !important;">
                <a href="recipe-single.php?id=<?= $row['r_id']; ?>&type=recipe" class="img-prod">
                    <img class="img-fluid" src="assets/uploads/<?= $row['r_featured_photo']; ?>" style="height: 250px !important; width: 90% !important; margin: 5%;" alt="<?= $row['r_name']; ?>">
                    <div class="overlay"></div>
                </a>
                <div class="text py-3 pb-4 px-3 justify-content-left">
                    <h3 class="text-center"><a href="#"><?= $row['r_name']; ?></a></h3>
                    <p class="text-center"><a href="#" style="text-decoration: underline;"><?= $row['business_title']; ?></a></p>
                    <hr>
                    <div class="content">
                        <ul>
                            <?php
                            $sql = "SELECT p.p_name, s.size_name, rp.quantity 
                                    FROM tbl_recipe_product rp 
                                    LEFT JOIN tbl_product p ON rp.p_id = p.p_id 
                                    LEFT JOIN tbl_size s ON rp.s_id = s.size_id 
                                    WHERE rp.r_id = :pid LIMIT 3";
                            $p1 = [":pid" => $row['r_id']];
                            $r1 = $c->fetchData($pdo, $sql, $p1);
                            if ($r1) {
                                foreach ($r1 as $row1) {
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
}
?>
				<div class="col-md-12">
					<p class="text-danger text-center">No recipe found.</p>
				</div>
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
			$('.sellerName').select2();
			$(document).on('change', '#category', function(e){
				e.preventDefault();
				var catVal = $(this).val();
				if(catVal !== 'all'){
					
				}else{

				}
			});
		});
	</script>