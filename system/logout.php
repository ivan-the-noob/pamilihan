<?php 
ob_start();
session_start();
error_reporting(0);
include 'inc/config.php'; 
unset($_SESSION['user']);
header("location: login.php"); 
?>