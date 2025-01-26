<?php 
include 'system/inc/config.php';
if(isset($_SESSION['customer'])){
    unset($_SESSION['customer']);
}
header("location: ".BASE_URL.'index.php'); 
?>