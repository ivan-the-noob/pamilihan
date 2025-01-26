<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if($_POST['from_p'] == '') {
        $valid = 0;
        $error_message .= '"From No." can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['from_p'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if($_POST['to_p'] == '') {
        $valid = 0;
        $error_message .= '"To No." can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['cost'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if((int)$_POST['from_p'] > (int)$_POST['to_p']){
        $valid = 0;
        $error_message .= '"From No." must be less than to "To No."<br>';
    }


    // Check Duplication Data
    $statement = $pdo->prepare("SELECT * FROM tbl_service_fee WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row) {
        $current_from = $row['from_p'];
        $current_to = $row['to_p'];
    }

    $statement = $pdo->prepare("SELECT * FROM tbl_service_fee WHERE from_p=? and to_p=? and from_p!=? and to_p!=?");
    $statement->execute(array($_POST['from_p'],$_POST['to_p'],$current_from,$current_to));
    $total = $statement->rowCount();							
    if($total) {
        $valid = 0;
        $error_message .= 'Service Fee already exists<br>';
    }
    

    if($valid == 1) {
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_service_fee SET from_p=?,to_p=?,cost=?,delivery_fee=? WHERE id=?");
		$statement->execute(array($_POST['from_p'],$_POST['to_p'],$_POST['cost'],$_POST['dev_fee'],$_REQUEST['id']));

    	$success_message = 'Service Fee has been updated!';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_service_fee WHERE id=?");
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
		<h1>Edit Service Fee</h1>
	</div>
	<div class="content-header-right">
		<a href="service-fee.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<?php
foreach ($result as $row) {
	$current_from = $row['from_p'];
    $current_to = $row['to_p'];
    $current_cost = $row['cost'];
    $current_delivery_fee = $row['delivery_fee'];
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
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-2">
                            <label for="" class="control-label">From No.: <span>*</span></label>
                            <input type="text" class="form-control from_p" name="from_p" placeholder="Ex. 1" value="<?= $current_from; ?>">
                        </div>
                        <div class="col-sm-2">
                            <label for="" class="control-label">To No.: <span>*</span></label>
                            <input type="text" class="form-control to_p" name="to_p" placeholder="Ex. 10" value="<?= $current_to; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <label for="" class="control-label">Service Fee <span>*</span></label>
                            <input type="text" class="form-control" name="cost" value="<?= $current_cost; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <label for="" class="control-label">Delivery Fee <span>*</span></label>
                                <input type="text" class="form-control" name="dev_fee" placeholder="50" value="<?= $current_delivery_fee; ?>">
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
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