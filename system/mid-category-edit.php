<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;
    if(isset($_SESSION['user'])){
		if($_SESSION['user']['role'] == "Seller"){
			$myId = $_SESSION['user']['id'];
		}else{
			$myId = $_POST['seller'];
		}
	}
    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top level category<br>";
    }

    if(empty($_POST['mcat_name'])) {
        $valid = 0;
        $error_message .= "Sub Category Name can not be empty<br>";
    }

    if($valid == 1) {    	
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_mid_category SET seller_id=?, mcat_name=?,tcat_id=? WHERE mcat_id=?");
		$statement->execute(array($myId,$_POST['mcat_name'],$_POST['tcat_id'],$_REQUEST['id']));

    	$success_message = 'Sub Category is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE mcat_id=?");
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
		<h1>Edit Sub Category</h1>
	</div>
	<div class="content-header-right">
		<a href="mid-category.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<?php							
foreach ($result as $row) {
    $current_seller_id = $row['seller_id'];
	$mcat_name = $row['mcat_name'];
    $tcat_id = $row['tcat_id'];
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

        <form class="form-horizontal" action="" method="post">

        <div class="box box-info">

            <div class="box-body">
                <?php
                if($_SESSION['user']['role'] == "Admin"){
                    ?>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Seller <span>*</span></label>
                        <div class="col-sm-4">
                            <select name="seller" class="form-control">
                                <?php
                                $sql = "SELECT * FROM tbl_seller WHERE status='Verified'";
                                $res = $pdo->prepare($sql);
                                $res->execute();
                                if($res->rowCount() > 0){
                                    ?>
                                    <option value="" selected>Select an option</option>
                                    <?php
                                    foreach($res->fetchAll(PDO::FETCH_ASSOC) as $row){
                                        ?>
                                        <option value="<?= $row['user_id'];?>" <?php if($current_seller_id == $row['user_id']){ echo 'selected'; } ?>><?=$row['business_title'];?></option>
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
                    <label for="" class="col-sm-3 control-label">Category Name <span>*</span></label>
                    <div class="col-sm-4">
                        <select name="tcat_id" class="form-control select2">
                            <option value="">Select Category</option>
                            <?php
                            $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE seller_id=? ORDER BY tcat_name ASC");
                            $statement->execute(array($_SESSION['user']['id']));
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
                        <input type="text" class="form-control" name="mcat_name" value="<?php echo $mcat_name; ?>">
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

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>