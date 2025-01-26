<?php require_once('header.php'); ?>

<?php

if(isset($_POST['form1'])) {

    $valid = 1;

    if($_POST['cost'] == '') {
        $valid = 0;
        $error_message .= 'Fees can not be empty.<br>';
    } else {
        if(!is_numeric($_POST['cost'])) {
            $valid = 0;
            $error_message .= 'You must have to enter a valid number.<br>';
        }
    }

    if($valid == 1) {
        $statement = $pdo->prepare("INSERT INTO tbl_service_fee (from_p,to_p,cost) VALUES (?,?,?)");
        $statement->execute(array($_POST['from_p'],$_POST['to_p'],$_POST['cost']));

        $success_message = 'Service Fee added successfully!';
    }

}
?>


<section class="content-header">
    <div class="content-header-left">
        <h1>Set Delivery Fee</h1>
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
                            <div class="col-sm-4">
                                <label for="" class="control-label">Delivery Fee <span>*</span></label>
                                <input type="text" class="form-control" name="cost" placeholder="100">
                            </div>
                        </div>
                        <?php
                        $sql = "SELECT * FROM tbl_delivery_fee WHERE id=:id";
                        $res = $c->fetchData($pdo,$sql);
                        $current_delivery_fee = 0;
                        if($res){
                            foreach($res as $row){
                                $current_delivery_fee = $row['cost'];
                                ?>
                                <div class="form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-4">
                                        <label for="" class="control-label"><b>Current Delivery Fee: </b></label><br>
                                        <?= number_format($current_delivery_fee,2); ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
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

<?php require_once('footer.php'); ?>