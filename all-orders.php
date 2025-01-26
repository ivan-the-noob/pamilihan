<?php
include_once("system/inc/config.php");
include_once("system/inc/functions.php");
include_once("system/inc/class.php");
include_once("system/inc/CSRF_Protect.php");

$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

$c = new CustomizeClass();
$csrf = new CSRF_Protect();

$allOrders = "SELECT pp.total_amount as total_price, pp.transaction_id as transaction_id, o.* FROM tbl_purchase_order o JOIN tbl_purchase_payment pp ON o.order_id=pp.order_id WHERE o.customer_id=:c_id ORDER BY o.id DESC";
$allOrdersP = [
    ":c_id"     =>  $_SESSION['customer']['id']
];
$allOrdersS = $c->fetchData($pdo, $allOrders, $allOrdersP);

$orderId = "";
$totalPrice = "";
$transactionId = "";
$dat = "";
$status = "";

?>
<div class="cart-list">
    <table class="table border" style="width:100% !important;">
        <thead class="thead-secondary" style="color: black !important;">
            <tr>
                <th rowspan="2" class="border">No.</th>
                <th rowspan="2" class="border">Order ID</th>
                <th colspan="5" class="border">Product Details</th>
                <th rowspan="2" class="border">Total Amount</th>
                <th rowspan="2" class="border">Transaction ID</th>
                <th rowspan="2" class="border">Date</th>
                <th rowspan="2" class="border">Order Status</th>
            </tr>
            <tr>
                <th class="border" width="165px">&nbsp;</th>
                <th class="border" width="100px">Product Name</th>
                <th class="border">Price</th>
                <th class="border">Quantity</th>
                <th class="border">Total Price</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $no = 1;
            if($allOrdersS){
                foreach($allOrdersS as $r){
                    $orderId = $r['order_id'];
                    $totalPrice = $r['total_price'];
                    $transactionId =  $r['transaction_id'];
                    $dat = date("M. d, Y (h:i A)", $r['date_and_time']);
                    $stat = $r['status'];
    
                    $sql = "SELECT COUNT(order_id) AS totalOrder FROM tbl_purchase_item WHERE order_id=:ordId";
                    $p = [
                        ":ordId"    =>  $orderId
                    ];
                    $totalOrder = 0;
                    $item = $c->fetchData($pdo, $sql, $p);
                    foreach($item as $ro){
                        $totalOrder = $ro['totalOrder'] + 1;
                    }
                    ?>
                    <tr>
                        <td rowspan="<?= $totalOrder; ?>" class="border" style="border: 1px solid #E5E5E5 !important;"><?= $no; ?>.</td>
                        <td rowspan="<?= $totalOrder; ?>" class="border" style="border: 1px solid #E5E5E5 !important;"><span><b><?= $orderId; ?></b></span></td>
                    </tr>
                    <?php
                    $totalPricePerItem = 0;
                    $sql1 = "SELECT p.*, pi.product_qty, pi.product_price, pi.size_id FROM tbl_purchase_item pi JOIN tbl_product p ON pi.product_id=p.p_id WHERE pi.order_id=:ordId";
                    $p1 = [
                        ":ordId"    =>  $orderId
                    ];
                    $res1 = $c->fetchData($pdo, $sql1, $p1);
                    $itemNo = 1;
                    if($res1){
                        foreach($res1 as $val){
                            $totalPricePerItem = $val['product_qty'] * $val['product_price'];
                            ?>
                            <tr>
                                <td style="border: 1px solid #E5E5E5 !important;" class="image-prod">
                                    <div class="img" style="background-image:url(assets/uploads/<?= $val['p_featured_photo']; ?>);"></div>
                                </td>
                                <?php
                                $sname = "SELECT * FROM tbl_size WHERE size_id=:sizId";
                                $p2 = [
                                    ":sizId"    =>  $val['size_id']
                                ];
                                $rr = $c->fetchData($pdo, $sname, $p2);
                                $showSizeName = "";
                                if($rr){
                                    foreach($rr as $rrr){
                                        if($val['size_id'] != "0"){
                                            $showSizeName = "(".$rrr['size_name'].")";
                                        }else{
                                            $showSizeName = "";
                                        }
                                    }
                                }else{
                                    $showSizeName = "";
                                }
                                ?>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $val['p_name']; ?><br><span><small><?= $showSizeName; ?></small></span></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $php; ?><?= $val['product_price']; ?></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $val['product_qty']; ?></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $php; ?><?= $totalPricePerItem; ?></td>
                                <?php
                                if($itemNo == 1){
                                    ?>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $php; ?><?= number_format($totalPrice, 2); ?></td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $transactionId; ?></td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px"><?= $dat; ?></td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $r['remarks']; ?><?php if($stat == "Delivering Items"){ echo '<a href="chat.php <button type="button" class="btn btn-success" style="border-radius: 5px !important;"><span class="icon-message"></span>&nbsp;Chat</a></button>'; }else if($stat == "Pending"){ ?><button class="btn btn-sm btn-danger cancelOrder" data-order="<?= $orderId;?>" style="border-radius: 5px !important;">Cancel</button><?php } ?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                            $itemNo++;
                        }
                    }
                    $no++;
                }
            }else{
                ?>
                <tr>
                    <td colspan="11">Empty order!</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>