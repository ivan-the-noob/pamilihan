<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Recipe</h1>
	</div>
	<div class="content-header-right">
		<a href="recipe-add.php" class="btn btn-primary btn-sm">Add Recipe</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<?php 
					$userId = "";
					$userRole = "";
					if(isset($_SESSION['user'])){
						$userId = $_SESSION['user']['id'];
						$userRole = $_SESSION['user']['role'];
					}
					if($userRole == 'Admin') { ?>
					<table id="example1" class="table table-bordered table-hover table-striped">
					<thead class="thead-dark">
							<tr>
								<th width="10">#</th>
								<th>Photo</th>
								<th width="100">Seller</th>
								<th width="150">Recipe Name</th>
								<th width="250">Product Details</th>
								<th>Active?</th>
								<th width="80">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$sql = "SELECT * FROM tbl_recipe r LEFT JOIN tbl_seller seller ON r.u_id=seller.user_id ORDER BY r.r_id DESC";
							$result = $c->fetchData($pdo, $sql);
							if($result){
								foreach ($result as $row) {
									$i++;
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td style="width:82px;"><img src="../assets/uploads/<?php echo $row['r_featured_photo']; ?>" alt="<?php echo $row['r_name']; ?>" style="width:80px;"></td>
										<td><?php echo $row['business_title']; ?></td>
										<td><?php echo $row['r_name']; ?></td>
										<td>
											<ul>
												<?php
												$sql1 = "SELECT rp.r_id, p.p_name AS prodName, s.size_name AS sizeName, rp.quantity FROM tbl_recipe_product rp LEFT JOIN tbl_product p ON rp.p_id=p.p_id LEFT JOIN tbl_size s ON rp.s_id=s.size_id WHERE rp.r_id=:rid";
												$p1 = [
													":rid"	=>	$row['r_id']
												];
												$res1 = $c->fetchData($pdo, $sql1, $p1);
												if($res1){
													foreach($res1 as $row1){
														?>
														<li><?= $row1['prodName']; ?><small>(<?= $row1['sizeName']; ?>)</small> x<?= $row1['quantity']; ?></li>
														<?php
													}
												}else{
													echo '-';
												}
												?>
											</ul>
										</td>
										<td>
											<?php if($row['r_is_active'] == 1) {echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';} else {echo '<span class="badge badge-danger" style="background-color:red;">No</span>';} ?>
										</td>
										<td>
											<a href="#" class="btn btn-danger btn-xs" data-href="recipe-delete.php?id=<?php echo $row['r_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
										</td>
									</tr>
									<?php
								}
							}
							?>						
						</tbody>
					</table>
					<?php }else if($userRole == 'Seller'){
						?>
						<table id="example1" class="table table-bordered table-hover table-striped">
						<thead class="thead-dark">
								<tr>
									<th width="10">#</th>
									<th>Photo</th>
									<th width="150">Recipe Name</th>
									<th width="150">Description</th>
									<th width="250">Product Details</th>
									<th>Active?</th>
									<th width="80">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i=0;
								$sql = "SELECT * FROM tbl_recipe r LEFT JOIN tbl_seller seller ON r.u_id=seller.user_id WHERE r.u_id=:uid ORDER BY r.r_id DESC";
								$p = [
									":uid"	=>	$_SESSION['user']['id']
								];
								$result = $c->fetchData($pdo, $sql, $p);
								if($result){
									foreach ($result as $row) {
										$i++;
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td style="width:82px;"><img src="../assets/uploads/<?php echo $row['r_featured_photo']; ?>" alt="<?php echo $row['r_name']; ?>" style="width:80px;"></td>
											<td><?php echo $row['r_name']; ?></td>
											<?php
											if($row['r_description'] == ""){
												echo '<td>-</td>';
											}else{
												?>
												<td><?php echo $row['r_description']; ?></td>
												<?php
											}
											?>
											<td>
												<ul>
													<?php
													$sql1 = "SELECT rp.r_id, p.p_name AS prodName, s.size_name AS sizeName, rp.quantity FROM tbl_recipe_product rp LEFT JOIN tbl_product p ON rp.p_id=p.p_id LEFT JOIN tbl_size s ON rp.s_id=s.size_id WHERE rp.r_id=:rid";
													$p1 = [
														":rid"	=>	$row['r_id']
													];
													$res1 = $c->fetchData($pdo, $sql1, $p1);
													if($res1){
														foreach($res1 as $row1){
															?>
															<li><?= $row1['prodName']; ?><small>(<?= $row1['sizeName']; ?>)</small> x<?= $row1['quantity']; ?></li>
															<?php
														}
													}else{
														echo '-';
													}
													?>
												</ul>
											</td>
											<td>
												<?php if($row['r_is_active'] == 1) {echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';} else {echo '<span class="badge badge-danger" style="background-color:red;">No</span>';} ?>
											</td>
											<td>
												<a href="#" class="btn btn-danger btn-xs" data-href="recipe-delete.php?id=<?php echo $row['r_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
											</td>
										</tr>
										<?php
									}
								}
								?>						
							</tbody>
						</table>
						<?php
					} ?>	
				</div>
			</div>
		</div>
	</div>
</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete this recipe?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>