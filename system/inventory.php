<?php require_once('header.php'); date_default_timezone_set("Asia/Singapore"); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Inventory</h1>
	</div>
	<div class="content-header-right">
		<a href="inventory-manage.php" class="btn btn-primary btn-sm">Add Inventory</a>
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
								<th width="75">Batch ID</th>
								<th width="120">Seller</th>
								<th width="160">Product Name</th>
								<th width="60">Quantity</th>
								<th>Expiration Date</th>
								<th>Stock Status</th>
								<th width="80">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							date_default_timezone_set("Asia/Singapore");
							$getDateNow = strtotime(date("M-d-Y H:i:sa"));
							$i=0;
							$statement = $pdo->prepare("SELECT
														i.id,
														i.b_id,
														i.stock_in,
														i.stock_out,
														i.exp_date,
														i.stock_status,

														s1.business_title AS d_name,
														
														p1.p_name

														FROM tbl_inventory i
														JOIN tbl_product p1
														ON i.p_id=p1.p_id
														JOIN tbl_seller s1
														ON p1.u_id=s1.user_id
														ORDER BY d_name ASC
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								if($row['exp_date'] != ''){
									$showDate = date('M-d-Y H:i:sa', $row['exp_date']);
								}else{
									$showDate = 'No Expiration';
								}
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['b_id']; ?></td>
									<td><?php echo $row['d_name']; ?></td>
									<td><?php echo $row['p_name']; ?></td>
									<!-- <td style="width:82px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:80px;"></td> -->
									<td><?php echo $row['stock_in']; ?></td>
									<td> <?php if($row['exp_date'] > $getDateNow) {echo '<span class="badge badge-primary" style="background-color:#007BFF;">'.$showDate.'</span>';} else {echo '<span class="badge badge-danger" style="background-color:#DC3545;">'.$showDate.'</span>';} ?> </td>
									<td>
										<?php if($row['stock_status'] == "Available") {echo '<span class="badge badge-success" style="background-color:green;">Available</span>';} else {echo '<span class="badge badge-danger" style="background-color:#DC3545;">Out of Stock</span>';} ?>
									</td>
									<td>									
										<a href="product-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="inventory-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
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
									<th width="75">Batch ID</th>
									<th width="160">Product Name</th>
									<th width="60">Quantity</th>
									<th>Expiration Date</th>
									<th>Stock Status</th>
									<th width="80">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								date_default_timezone_set("Asia/Singapore");
								$getDateNow = strtotime(date("M-d-Y H:i:sa"));
								$i=0;
								$statement = $pdo->prepare("SELECT
															i.id,
															i.b_id,
															i.stock_in,
															i.stock_out,
															i.exp_date,
															i.stock_status,
															
															p1.p_name

															FROM tbl_inventory i
															JOIN tbl_product p1
															ON i.p_id=p1.p_id
															JOIN tbl_seller s1
															ON p1.u_id=s1.user_id
															WHERE p1.u_id=?
															ORDER BY exp_date DESC
															");
								$statement->execute(array($_SESSION['user']['id']));
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									if($row['exp_date'] != ''){
										$showDate = date('M-d-Y H:i:sa', $row['exp_date']);
									}else{
										$showDate = 'No Expiration';
									}
									$i++;
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $row['b_id']; ?></td>
										<td><?php echo $row['p_name']; ?></td>
										<!-- <td style="width:82px;"><img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" style="width:80px;"></td> -->
										<td><?php echo $row['stock_in']; ?></td>
										<td> <?php if($row['exp_date'] > $getDateNow) {echo '<span class="badge badge-primary" style="background-color:#007BFF;">'.$showDate.'</span>';} else {echo '<span class="badge badge-danger" style="background-color:#DC3545;">'.$showDate.'</span>';} ?> </td>
										<td>
											<?php if($row['stock_status'] == "Available") {echo '<span class="badge badge-success" style="background-color:green;">Available</span>';} else {echo '<span class="badge badge-danger" style="background-color:#DC3545;">Out of Stock</span>';} ?>
										</td>
										<td>									
											<a href="product-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Edit</a>
											<a href="#" class="btn btn-danger btn-xs" data-href="inventory-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
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
                <p style="color:red;">Be careful! This product will be deleted from the order table, payment table, size table, color table and rating table also.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>