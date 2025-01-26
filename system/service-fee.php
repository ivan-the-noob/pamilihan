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
    $sql = "SELECT * FROM tbl_service_fee WHERE from_p=:from_p AND to_p=:to_p";
    $p = [
        ":from_p"   =>  $_POST['from_p'],
        ":to_p"     =>  $_POST['to_p']
    ];
    $res = $c->fetchData($pdo, $sql, $p);
    if($res == true){
        $valid = 0;
        $error_message .= 'Service Fee already exist!';
    }

    if($_POST['dev_fee'] == '') {
        $valid = 0;
        $error_message .= 'Cost can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['dev_fee'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if($_POST['cost'] == '') {
        $valid = 0;
        $error_message .= 'Cost can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['cost'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if($valid == 1) {
        $statement = $pdo->prepare("INSERT INTO tbl_service_fee (from_p,to_p,cost,delivery_fee) VALUES (?,?,?,?)");
        $statement->execute(array($_POST['from_p'],$_POST['to_p'],$_POST['cost'],$_POST['dev_fee']));

        $success_message = 'Service Fee added successfully!';
    }

}


if(isset($_POST['form2'])) {
    $valid = 1;

    if($_POST['cost'] == '') {
        $valid = 0;
        $error_message .= 'Cost can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['cost'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if($_POST['dev_fee'] == '') {
        $valid = 0;
        $error_message .= 'Cost can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['dev_fee'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if($valid == 1) {
        $sql = "SELECT * FROM tbl_service_fee_all";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $statement = $pdo->prepare("UPDATE tbl_service_fee_all SET cost=?, delivery_fee=? WHERE id=1");
            $statement->execute(array($_POST['cost'], $_POST['dev_fee']));
        }else{
            $statement = $pdo->prepare("INSERT tbl_service_fee_all SET cost=?, delivery_fee=?");
            $statement->execute(array($_POST['cost'], $_POST['dev_fee']));
        }
        $success_message = 'Service fee for the rest count products has been updated.';

    }
}
?>


<section class="content-header">
    <div class="content-header-left">
        <h1>Add Shipping Cost</h1>
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

            <form class="form-horizontal" action="" method="post">

                <div class="box box-info">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2">
                                <label for="" class="control-label">From No.: <span>*</span></label>
                                <input type="text" class="form-control from_p" name="from_p" placeholder="Ex. 1">
                            </div>
                            <div class="col-sm-2">
                                <label for="" class="control-label">To No.: <span>*</span></label>
                                <input type="text" class="form-control to_p" name="to_p" placeholder="Ex. 10">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <label for="" class="control-label">Service Fee <span>*</span></label>
                                <input type="text" class="form-control" name="cost" placeholder="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <label for="" class="control-label">Delivery Fee <span>*</span></label>
                                <input type="text" class="form-control" name="dev_fee" placeholder="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success pull-left" name="form1">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>


        </div>
    </div>
</section>




<section class="content-header">
	<div class="content-header-left">
		<h1>View Shipping Costs</h1>
	</div>
</section>


<section class="content">

  <div class="row">
    <div class="col-md-12">


      <div class="box box-info">
        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-hover table-striped">
			<thead>
			    <tr>
			        <th>#</th>
			        <th>Item Count</th>
                    <th>Service Fee</th>
                    <th>Delivery Fee</th>
			        <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * 
                                        FROM tbl_service_fee ORDER BY id DESC");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr>
	                    <td><?php echo $i; ?></td>
	                    <td><?php echo $row['from_p']; ?> - <?= $row['to_p']; ?></td>
                        <td><?php echo $row['cost']; ?></td>
                        <td><?php echo $row['delivery_fee']; ?></td>
	                    <td>
	                        <a href="service-fee-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Edit</a>
	                        <a href="#" class="btn btn-danger btn-xs" data-href="service-fee-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>
	                    </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div> 

      <h4 style="background: #dd4b39;color:#fff;padding:10px 20px;">Note: If item count does not exist in the above list, "Rest Count" will be applied upon that.</h4>

</section>


<section class="content-header">
    <div class="content-header-left">
        <h1>Service Fee (Rest Count)</h1>
    </div>
</section>

<section class="content">

    <?php
    $amount = '';
    $deliveryFee = '';
    $statement = $pdo->prepare("SELECT * FROM tbl_service_fee_all WHERE id=1");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
    foreach ($result as $row) {
        $amount = $row['cost'];
        $deliveryFee = $row['delivery_fee'];
    }
    ?>

    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" action="" method="post">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <label for="" class="control-label">Fees <span>*</span></label>
                                <input type="text" class="form-control" name="cost" value="<?= $amount; ?>" placeholder="Cost">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-4">
                                <label for="" class="control-label">Delivery Fee <span>*</span></label>
                                <input type="text" class="form-control" name="dev_fee" placeholder="50" value="<?= $deliveryFee; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success pull-left" name="form2">Update</button>
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