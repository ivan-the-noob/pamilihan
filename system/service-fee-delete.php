<?php require_once('header.php'); ?>

<?php
if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $sql = "SELECT * FROM tbl_service_fee WHERE id=:id";
    $p = [
        ":id"   =>  $id,
    ];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($p);
    if($stmt->rowCount() > 0){
        $del = "DELETE FROM tbl_service_fee WHERE id=:id";
        $p = [
            ":id"   =>  $id,
        ];
        $stmt1 = $pdo->prepare($del);
        $stmt1->execute($p);
    }
    header("Location: service-fee.php");
    exit;
}else{
    header("Location: service-fee.php");
    exit;
}
?>


<?php require_once('footer.php'); ?>