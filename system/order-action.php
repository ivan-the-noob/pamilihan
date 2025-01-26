<?php
include("inc/config.php");
include("inc/class.php");

$c = new CustomizeClass();

if(isset($_POST['acceptOrder'])){
    $orderId = $_POST['order_id'];
    $remarks = "Accepted by Seller";
    $p = [
        ":remarks"  =>  $remarks,
        ":ordId"    =>  $orderId
    ];
    $sql = "UPDATE tbl_purchase_order SET status='Accepted', remarks=:remarks WHERE order_id=:ordId";
    $res = $c->updateData($pdo, $sql, $p);

    $sql2 = "UPDATE tbl_purchase_item SET status='Accepted' WHERE order_id=:ordId";
    $res2 = $c->updateData($pdo, $sql2, $p2);

    $sql1 = "UPDATE tbl_purchase_payment SET status='Accepted' WHERE order_id=:ordId";
    $res1 = $c->updateData($pdo, $sql1, $p);

    echo "success";
}

if(isset($_POST['riderAccept'])){
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];
    $urId = $_POST['urId'];
    $rider = false;
    $active = false;
    $remarks = "";
    $p = [
        ":ordId"    =>  $orderId
    ];
    $p1 = [
        ":ordId"    =>  $orderId,
        ":stat"     =>  $status,
    ];
    $p2 = [
        ":urId"     =>  $urId
    ];
    $p3 = [
        ":ordId"    =>  $orderId,
        ":remarks"  =>  $remarks,
        ":ridId"    =>  $urId
    ];

    $sql5 = "SELECT * FROM tbl_user WHERE id=:urId";
    $check = $c->fetchData($pdo, $sql5, $p2);
    if($check){
        foreach($check as $row){
            if($row['role'] != "Rider"){
                echo "Only rider can update this order!";
            }else if($row['status'] != "Active"){
                echo "Your account is not active, please contact the admin for this issue.";
            }else{
                $rider = true;
                $active = true;
            }
        }
    }else{
        echo "error";
    }
    if($rider == true && $active == true){
        $checkRider = "SELECT * FROM tbl_purchase_order WHERE order_id=:ordId";
        $show = $c->fetchData($pdo, $checkRider, $p);
        foreach($show as $rider){
            if($rider['rider_id'] == $_SESSION['user']['id'] || $rider['rider_id'] == ""){
                $remarks = "";
                if($status == "Accepted"){
                    $remarks = "Transferred to Rider";
                    $sql = "UPDATE tbl_purchase_order SET rider_id=:ridId, remarks=:remarks, status='Rider' WHERE order_id=:ordId";
                    $p3 = [
                        ":ordId"    =>  $orderId,
                        ":remarks"  =>  $remarks,
                        ":ridId"    =>  $urId
                    ];
                    $res = $c->updateData($pdo, $sql, $p3);
                
                    $sql1 = "UPDATE tbl_purchase_payment SET status='Pending' WHERE order_id=:ordId";
                    $res1 = $c->updateData($pdo, $sql1, $p);
                
                    echo "success";
                }else if($status == "Rider"){
                    $remarks = "Purchasing items";
                    $sql = "UPDATE tbl_purchase_order SET rider_id=:ridId, remarks=:remarks, status='Buying Items' WHERE order_id=:ordId";
                    $p3 = [
                        ":ordId"    =>  $orderId,
                        ":remarks"  =>  $remarks,
                        ":ridId"    =>  $urId
                    ];
                    $res = $c->updateData($pdo, $sql, $p3);
                
                    echo "success";
                }else if($status == "Buying Items"){
                    $remarks = "On the way for delivery";
                    $sql = "UPDATE tbl_purchase_order SET rider_id=:ridId, remarks=:remarks, status='Delivering Items' WHERE order_id=:ordId";
                    $p3 = [
                        ":ordId"    =>  $orderId,
                        ":remarks"  =>  $remarks,
                        ":ridId"    =>  $urId
                    ];
                    $res = $c->updateData($pdo, $sql, $p3);
                
                    echo "success";
                }else if($status == "Delivering Items"){
                    $remarks = "Completed";
                    $sql = "UPDATE tbl_purchase_order SET rider_id=:ridId, remarks=:remarks, status='Completed' WHERE order_id=:ordId";
                    $p3 = [
                        ":ordId"    =>  $orderId,
                        ":remarks"  =>  $remarks,
                        ":ridId"    =>  $urId
                    ];
                    $res = $c->updateData($pdo, $sql, $p3);
                
                    echo "success";
                }else{
                    echo "error";
                }
            }else{
                echo "This order was already taken by other riders!";
            }
        }
    }
    unset($_POST['riderAccept']);
}

if(isset($_POST['completePayment'])){
    $orderId = $_POST['order_id'];
    $transactId = $_POST['transactId'];
    $count = 0;
    $transact = false;
    $checkRider = "SELECT * FROM tbl_purchase_order WHERE rider_id=:urId";
    $checkR = [
        ":urId"     =>  $_SESSION['user']['id']
    ];
    $ch = $c->fetchData($pdo, $checkRider, $checkR);
    if($ch){
        foreach($ch as $dsa){
            $transact = true;
        }
    }
    if($transact == true){
        $sql = "SELECT COUNT(order_id) AS count FROM tbl_purchase_order WHERE order_id=:ordId AND status='Completed'";
        $p =[
            ":ordId"    =>  $orderId
        ];
        $n = $c->fetchData($pdo, $sql, $p);
        foreach($n as $row){
            $count = $row['count'];
        }
        if($count == 1){
            $checkPayment = "SELECT COUNT(order_id) AS countTransact FROM tbl_purchase_payment WHERE order_id=:ordId AND transaction_id=:transactId AND transaction_status='Pending'";
            $check = [
                ":ordId"        =>  $orderId,
                ":transactId"   =>  $transactId,
            ];
            $show2 = $c->fetchData($pdo, $checkPayment, $check);
            $countTransact = 0;
            if($show2){
                foreach($show2 as $vals){
                    $countTransact = $vals['countTransact'];
                }
                if($countTransact == 1){
                    $updatePayment = "UPDATE tbl_purchase_payment SET transaction_status='Completed' WHERE order_id=:ordId AND transaction_id=:transactId";
                    $param = [
                        ":ordId"    =>  $orderId,
                        ":transactId"   =>  $transactId
                    ];
                    $res = $c->updateData($pdo,$updatePayment,$param);
        
                    echo "success";
                }else{
                    echo "You failed to update this order, we'll refresh the page for you!";
                }
            }else{
                echo "You failed to update this order, we'll refresh the page for you!";
            }
        }else{
            echo "Before you update the payment, the order must be delivered or completed.";
        }
        unset($_POST['completePayment']);
    }else{
        echo "Only rider can update this order";
    }
}
?>