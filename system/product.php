<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Products</h1>
	</div>
	<div class="content-header-right">
		<a href="product-add.php" class="btn btn-primary btn-sm">Add Product</a>
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
								<th width="120">Seller</th>
								<th width="120">Product Name</th>
								<th>Category</th>
								<th width="100">Size</th>
								<th width="60">Retail</th>
								<th>Featured?</th>
								<th>Active?</th>
								<th width="80">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														s1.business_title,
														t1.p_id,
														t1.p_name,
														t1.p_retail,
														t1.p_wholesale,
														t1.p_qty,
														t1.p_featured_photo,
														t1.p_is_featured,
														t1.p_is_active,

														t3.mcat_id,
														t3.mcat_name,

														t4.tcat_id,
														t4.tcat_name

							                           	FROM tbl_product t1
							                           	JOIN tbl_product_category t2
							                           	ON t1.p_id = t2.p_id
							                           	JOIN tbl_mid_category t3
							                           	ON t2.mcat_id = t3.mcat_id
							                           	JOIN tbl_top_category t4
							                           	ON t2.tcat_id = t4.tcat_id
														JOIN tbl_seller s1
														ON t1.u_id=s1.user_id
							                           	ORDER BY t1.p_id DESC
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:82px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:80px;"></td>
									<td><?php echo $row['business_title']; ?></td>
									<td><?php echo $row['p_name']; ?></td>
									<td>
										<ul>
											<li><?php echo $row['tcat_name']; ?></li>
											<li><?php echo $row['mcat_name']; ?></li>
										</ul>
									</td>
									<td>
										<?php
										$sql1 = "SELECT s.size_name, ps.* FROM tbl_product_size ps JOIN tbl_size s ON ps.size_id=s.size_id WHERE ps.p_id=:p_id ORDER BY s.size_name DESC";
										$p1 = [
											':p_id'	=>	$row['p_id']
										];
										$res1 = $c->fetchData($pdo,$sql1,$p1);
										if($res1){
											?>
											<ul>
											<?php
											foreach($res1 as $row1){
												?>
												<li><?= $row1['size_name'];?></li>
												<?php
											}
											?>
											</ul>
											<?php
										}else{
											?>
											-
											<?php
										}
										?>
									</td>
									<td>₱<?php echo $row['p_retail']; ?></td>
									<td>
										<?php if($row['p_is_featured'] == 1) {echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';} else {echo '<span class="badge badge-success" style="background-color:red;">No</span>';} ?>
									</td>
									<td>
										<?php if($row['p_is_active'] == 1) {echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';} else {echo '<span class="badge badge-danger" style="background-color:red;">No</span>';} ?>
									</td>
									<td>										
										<a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="product-delete.php?id=<?php echo $row['p_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
									</td>
								</tr>
								<?php
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
									<th width="120">Product Name</th>
									<th>Category</th>
									<th width="100">Size</th>
									<th width="60">Retail</th>
									<th>Featured?</th>
									<th>Active?</th>
									<th width="80">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i=0;
								$statement = $pdo->prepare("SELECT
															t1.p_id,
															t1.p_name,
															t1.p_retail,
															t1.p_wholesale,
															t1.p_qty,
															t1.p_featured_photo,
															t1.p_is_featured,
															t1.p_is_active,
															t1.ecat_id,

															t3.mcat_id,
															t3.mcat_name,

															t4.tcat_id,
															t4.tcat_name

															FROM tbl_product t1
															JOIN tbl_product_category t2
															ON t1.p_id = t2.p_id
															JOIN tbl_mid_category t3
															ON t2.mcat_id = t3.mcat_id
															JOIN tbl_top_category t4
															ON t2.tcat_id = t4.tcat_id
															JOIN tbl_seller s1
															ON t1.u_id=s1.user_id
															WHERE t1.u_id=?
															ORDER BY t1.p_id DESC
															");
								$statement->execute(array($userId));
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									$i++;
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td style="width:82px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:80px;"></td>
										<td><?php echo $row['p_name']; ?></td>
										<td>
											<ul>
												<li><?php echo $row['tcat_name']; ?></li>
												<li><?php echo $row['mcat_name']; ?></li>
											</ul>
										</td>
										<td>
											<?php
											$sql1 = "SELECT s.size_name, ps.* FROM tbl_product_size ps JOIN tbl_size s ON ps.size_id=s.size_id WHERE ps.p_id=:p_id ORDER BY s.size_name DESC";
											$p1 = [
												':p_id'	=>	$row['p_id']
											];
											$res1 = $c->fetchData($pdo,$sql1,$p1);
											if($res1){
												?>
												<ul>
												<?php
												foreach($res1 as $row1){
													?>
													<li><?= $row1['size_name'];?></li>
													<?php
												}
												?>
												</ul>
												<?php
											}else{
												?>
												-
												<?php
											}
											?>
										</td>
										<td>₱<?php echo $row['p_retail']; ?></td>
										<td>
											<?php if($row['p_is_featured'] == 1) {echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';} else {echo '<span class="badge badge-success" style="background-color:red;">No</span>';} ?>
										</td>
										<td>
											<?php if($row['p_is_active'] == 1) {echo '<span class="badge badge-success" style="background-color:green;">Yes</span>';} else {echo '<span class="badge badge-danger" style="background-color:red;">No</span>';} ?>
										</td>
										<td>										
											<a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
											<a href="#" class="btn btn-danger btn-xs" data-href="product-delete.php?id=<?php echo $row['p_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
										</td>
									</tr>
									<?php
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
                <p>Are you sure want to delete this item?</p>
                <p style="color:red;">Be careful! This product will be deleted from the order table, payment table, and size table also.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>