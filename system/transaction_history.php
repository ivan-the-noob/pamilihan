<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Transaction History</h1>
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
                                <th>Order No.</th>
                                <th>Actual Payment</th>
                                <th>Total Payment</th>
                                <th>Income</th>
                                <th>Transaction ID</th>
                                <th>Transaction Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT o.*, SUM(i.product_price * product_qty) AS actualPayment, p.transaction_status, p.total_amount AS total_amount, p.transaction_id, p.date_and_time AS dateTimeTrans FROM tbl_purchase_order o LEFT JOIN tbl_purchase_item i ON o.order_id=i.order_id LEFT JOIN tbl_purchase_payment p ON o.order_id=p.order_id WHERE o.rider_id=:rid GROUP BY o.order_id ORDER BY id DESC";
                            $p = [
                                ":rid"  =>  $_SESSION['user']['id']
                            ];
                            $res = $c->fetchData($pdo, $sql, $p);
                            if($res){
                                $no = 1;
                                foreach($res as $row){
                                    if($row['order_id'] != null){
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['order_id']; ?></td>
                                            <td><?= $php; ?><?= number_format($row['actualPayment'], 2); ?></td>
                                            <td><?= $php; ?><?= number_format($row['total_amount']); ?></td>
                                            <td><?= $php; ?><?= number_format($row['total_amount'] - $row['actualPayment'], 2); ?></td>
                                            <td><?= $row['transaction_id']; ?></td>
                                            <td><?= $row['transaction_status'];?></td>
                                            <td><?= date("M. d, Y h:i:s A", $row['dateTimeTrans']);?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>            
    </div>
</section>

<?php require_once('footer.php'); ?>
