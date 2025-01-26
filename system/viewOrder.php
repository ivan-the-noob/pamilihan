<?php
include('inc/config.php');
include('inc/class.php');

$c = new CustomizeClass();

if(isset($_POST['viewProduct'])){
    ?>
    <span class="text-danger">Note: </span><span>Check the box if you already bought the product.</span>
    <hr/>
    <table class="table table-responsive table-stripped">
        <thead>
            <tr>
                <th colspan="5"><center>Check List</center></th>
            </tr>
            <tr>
                <th rowspan="2" width="150">&nbsp;</th>
                <th rowspan="2">Product Name</th>
                <th rowspan="2">Quantity</th>
                <th rowspan="2">Price</th>
                <th rowspan="2">Status</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if(isset($_POST['orderId'])){
        $ordId = $_POST['orderId'];
        $pi = "SELECT i.*, i.status AS prodStatus, i.id AS itemId, p.*, s.size_name FROM tbl_purchase_item i LEFT JOIN tbl_product p ON i.product_id=p.p_id LEFT JOIN tbl_size s ON i.size_id=s.size_id WHERE i.order_id=:ordId";
        $p = [
            ":ordId"    =>  $ordId
        ];
        $res = $c->fetchData($pdo, $pi, $p);
        if($res){
            foreach($res as $row){
                ?>
                        <tr>
                            <td><img src="../assets/uploads/<?= $row['p_featured_photo']; ?>" class="img img-thumbnail" width="100" height="100" id="img" alt=""></td>
                            <td><center><?= $row['p_name']; ?><br><small><?= $row['size_name']; ?></small></center></td>
                            <td><?= $row['product_qty']; ?></td>
                            <td><?= $php; ?><?= number_format($row['product_price'],2); ?></td>
                            <?php
                            if(isset($_POST['riderId'])){
                                if(!empty($_POST['riderId'])){
                                    ?>
                                    <td>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="alreadyBuy" data-id="<?= $row['itemId']; ?>" value="Completed" class="form-check alreadyBuy" <?php if($row['prodStatus'] == "Completed"){ echo 'checked'; } ?>>
                                            </div>
                                        </div>
                                    </td>
                                    <?php
                                }else{
                                    echo '<td>-</td>';
                                }
                            }else{
                                echo '<td>-</td>';
                            }
                            ?>
                        </tr>
                <?php
            }
        }else{
            ?>
            <tr>
                <td colspan="4">No orders</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
    }
    ?>
        </tbody>
    </table>
    <?php
}

if(isset($_POST['checkStatus'])){
    $getId = $_POST['id'];
    $getStatus = $_POST['productStatus'];
    
    $updData = "UPDATE tbl_purchase_item SET status=:stat WHERE id=:id";
    $p1 = [
        ':stat'     =>  $getStatus,
        ':id'       =>  $getId
    ];

    $res1 = $c->updateData($pdo, $updData, $p1);
}
?>