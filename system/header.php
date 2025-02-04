<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
include("inc/class.php");
$c = new CustomizeClass();
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';
 
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
	$logo = $row['logo'];
	$favicon = $row['logo'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$meta_title_home = $row['meta_title_home'];
    $meta_keyword_home = $row['meta_keyword_home'];
    $meta_description_home = $row['meta_description_home'];
    $before_head = $row['before_head'];
    $after_body = $row['after_body'];
}
$role = "";
// Check if the user is logged in or not
if(!isset($_SESSION['user'])) {
	header('location: login.php');
	exit;
}else{
	$role = $_SESSION['user']['role'];
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php if($role == 'Admin'){
		echo "Admin";
	}else if($role == 'Seller'){
		echo "Seller";
	}else if($role == 'Rider'){
		echo "Rider";
	}
	?> Panel</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">
	<link rel="stylesheet" href="css/on-off-switch.css"/>
	<link rel="stylesheet" href="css/summernote.css">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/message.css">

</head>

<body class="hold-transition fixed skin-blue sidebar-mini">

	<div class="wrapper">

		<header class="main-header">

			<a href="index.php" class="logo"><img src="../assets/uploads/<?php echo $logo; ?>" alt="logo image" style="width:50%;"></a>
			<!-- <a href="index.php" class="logo">
				<span class="logo-lg">eCommerce PHP</span>
			</a> -->

			<nav class="navbar navbar-static-top">
				
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>

				<span style="float:left;line-height:50px;color:#00000;padding-left:15px;font-size:18px;"><?php if($role == 'Admin'){
		echo "Admin";
	}else if($role == 'Seller'){
		echo "Seller";
	}else if($role == 'Rider'){
		echo "Rider";
	}
	$myName = "";
	if($_SESSION['user']['role'] == "Seller"){
		$s = "SELECT * FROM tbl_seller WHERE user_id=?";
		$r = $pdo->prepare($s);
		$r->execute(array($_SESSION['user']['id']));
		if($r->rowCount() > 0){
			foreach($r as $rr){
				$myName = $rr['business_title'];
			}
		}
	}else{
		$myName = $_SESSION['user']['full_name'];
	}
	?> Panel</span>
    <!-- Top Bar ... User Inforamtion .. Login/Log out Area -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" class="user-image" alt="User Image">
								<span class="hidden-xs" style="color:#00000"><?php echo $myName; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-footer">
									<div>
										<a href="profile-edit.php" class="btn btn-default btn-flat">Edit Profile</a>
									</div>
									<div>
										<a href="logout.php" class="btn btn-default btn-flat">Log out</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>

			</nav>
		</header>

  		<?php $cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); ?>
<!-- Side Bar to Manage Shop Activities -->
  		<aside class="main-sidebar">
    		<section class="sidebar" style="background-color:#CCEEBC;">
      			<ul class="sidebar-menu">

			        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
			          <a href="index.php">
			            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
			          </a>
			        </li>

					<?php
					if($_SESSION['user']['role'] == "Rider"){
					?>
					<li class="treeview <?php if($cur_page == 'transaction_history.php') {echo 'active';} ?>">
			          <a href="transaction_history.php">
			            <i class="fa fa-file-text"></i> <span>Transaction History</span>
			          </a>
			        </li>
					<?php
					}
					?>
					
                    <?php
					if($_SESSION['user']['role'] != "Rider"){
					?>
					<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
                        <a href="product.php">
                            <i class="fa fa-shopping-bag"></i> <span>Product Management</span>
                        </a>
                    </li>
					<li class="treeview <?php if( ($cur_page == 'recipe.php') || ($cur_page == 'recipe-add.php') || ($cur_page == 'recipe-edit.php') ) {echo 'active';} ?>">
                        <a href="recipe.php">
                            <i class="fa fa-shopping-bag"></i> <span>Recipe Management</span>
                        </a>
                    </li>

					<li class="treeview <?php if( ($cur_page == 'inventory.php') || ($cur_page == 'inventory-manage.php')) {echo 'active';} ?>">
                        <a href="inventory.php">
                            <i class="fa fa-shopping-bag"></i> <span>Inventory Management</span>
                        </a>
                    </li>


                    <li class="treeview <?php if( ($cur_page == 'order.php') ) {echo 'active';} ?>">
                        <a href="order.php">
                            <i class="fa fa-sticky-note"></i> <span>Order Management</span>
                        </a>
                    </li>
					<!-- <li class="treeview <?php if( ($cur_page == 'customer.php') || ($cur_page == 'customer-add.php') || ($cur_page == 'customer-edit.php') ) {echo 'active';} ?>">
			          <a href="customer.php">
			            <i class="fa fa-user-plus"></i> <span>Manage Customers</span>
			          </a>
			        </li>
					  <li class="treeview <?php if( ($cur_page == 'rider.php') || ($cur_page == 'rider.php') || ($cur_page == 'rider.php') ) {echo 'active';} ?>">
			          <a href="rider.php">
			            <i class="fa fa-user-plus"></i> <span>Manage Riders</span>
			          </a>
			        </li> -->
					<!-- Icons to be displayed on Shop -->
					<li class="treeview <?php if( ($cur_page == 'sales.php') ) {echo 'active';} ?>">
			          <a href="sales.php">
			            <i class="fa fa-check-square-o"></i> <span>Sales Report</span>
			          </a>
			        </li>
					<li class="treeview <?php if( ($cur_page == 'livechat.php') ) {echo 'active';} ?>">
						<a href="https://dashboard.tawk.to/#/chat" target="_blank">
							<i class="fa fa-comment-o"></i> <span>Live Chat Support</span>
						</a>
					</li>
					<?php
					}
					?>
					<?php if ($_SESSION['user']['role'] == 'Admin'): ?>
					<li class="treeview <?php if( ($cur_page == 'slider.php') ) {echo 'active';} ?>">
			          <a href="slider.php">
			            <i class="fa fa-picture-o"></i> <span>Manage Sliders</span>
			          </a>
			        </li>
			        <li class="treeview <?php if( ($cur_page == 'service.php') ) {echo 'active';} ?>">
			          <a href="service.php">
			            <i class="fa fa-list-ol"></i> <span>Services</span>
			          </a>
			        </li>
			      	<li class="treeview <?php if( ($cur_page == 'faq.php') ) {echo 'active';} ?>">
			          <a href="faq.php">
			            <i class="fa fa-question-circle"></i> <span>FAQ</span>
			          </a>
			        </li>
					<li class="treeview <?php if($cur_page == 'seller.php' || $cur_page == 'customer.php' || $cur_page == 'rider.php' ) {echo 'active';} ?>">
						<a href="#">
                            <i class="fa fa-users"></i>
                            <span>User Management</span>
                            <span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
                        </a>
						<ul class="treeview-menu">
							<li><a href="seller.php"><i class="fa fa-user-plus"></i>Seller Accounts</a></li>
							<li><a href="customer.php"><i class="fa fa-user-plus"></i>Buyer Accounts</a></li>
							<li><a href="rider.php"><i class="fa fa-user-plus"></i>Rider Accounts</a></li>
						</ul>
			        </li>
					<li class="treeview <?php if( ($cur_page == 'verification.php') ) {echo 'active';} ?>">
			          <a href="verification.php">
					  <i class="fa fa-bicycle"></i> <span>Rider Verify</span>
			          </a>
			        </li>
					<li class="treeview <?php if( ($cur_page == 'size.php') || $cur_page == 'account-manage.php' || ($cur_page == 'size-add.php') || ($cur_page == 'size-edit.php') || ($cur_page == 'color.php') || ($cur_page == 'color-add.php') || ($cur_page == 'color-edit.php') || ($cur_page == 'country.php') || ($cur_page == 'country-add.php') || ($cur_page == 'country-edit.php') || ($cur_page == 'shipping-cost.php') || ($cur_page == 'shipping-cost-edit.php') || ($cur_page == 'top-category.php') || ($cur_page == 'top-category-add.php') || ($cur_page == 'top-category-edit.php') || ($cur_page == 'mid-category.php') || ($cur_page == 'mid-category-add.php') || ($cur_page == 'mid-category-edit.php') || ($cur_page == 'end-category.php') || ($cur_page == 'end-category-add.php') || ($cur_page == 'end-category-edit.php') ) {echo 'active';} ?>">
                        <a href="#">
                            <i class="fa fa-cogs"></i>
                            <span>Control Center</span>
                            <span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
                        </a>
                        <ul class="treeview-menu">
                            
								<li class="treeview <?php if( ($cur_page == 'page.php') ) {echo 'active';} ?>">
								<a href="page.php">
									<i class="fa fa-tasks"></i> <span>Page Settings</span>
								</a>
						</li>
								<li class="treeview <?php if( ($cur_page == 'social-media.php') ) {echo 'active';} ?>">
							<a href="social-media.php">
								<i class="fa fa-globe"></i> <span>Social Media</span>
							</a>
							</li>
							<!-- <li class="treeview <?php if( ($cur_page == 'subscriber.php')||($cur_page == 'subscriber.php') ) {echo 'active';} ?>">
							<a href="subscriber.php">
								<i class="fa fa-hand-o-right"></i> <span>Subscriber</span>
							</a>
							</li> -->
							<li class="treeview <?php if( ($cur_page == 'settings.php') ) {echo 'active';} ?>">
							<a href="settings.php">
								<i class="fa fa-sliders"></i> <span>Website Settings</span>
							</a>
							</li>
							<li class="treeview <?php if( ($cur_page == 'service-fee.php') || ($cur_page == 'service-fee-edit.php')){ echo 'active'; } ?>">
								<a href="#">
									<i class="fa fa-cogs"></i>
									<span>Additional Fee</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<li><a href="service-fee.php"><i class="fa fa-circle-o"></i> Service Fee</a></li>
								</ul>
							</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-cogs"></i>
									<span>Shop Settings</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<li><a href="size.php"><i class="fa fa-circle-o"></i> Size</a></li>
									<!-- <li><a href="color.php"><i class="fa fa-circle-o"></i> Color</a></li> -->
									<!-- <li><a href="country.php"><i class="fa fa-circle-o"></i> Country</a></li> -->
									<!-- <li><a href="shipping-cost.php"><i class="fa fa-circle-o"></i> Shipping Cost</a></li> -->
									<li><a href="top-category.php"><i class="fa fa-circle-o"></i> Category</a></li>
									<li><a href="mid-category.php"><i class="fa fa-circle-o"></i> Sub Category</a></li>
									<!-- <li><a href="end-category.php"><i class="fa fa-circle-o"></i> End Level Category</a></li> -->
								</ul>
							</li>
                        </ul>
                    </li>
					<?php endif;?>
					<?php
					if(isset($_SESSION['user'])){
						if($_SESSION['user']['role'] == "Seller"){
							?>
							<li class="treeview <?php if($cur_page == 'top-category.php' || $cur_page == 'mid-category.php') {echo 'active';} ?>">
								<a href="#">
									<i class="fa fa-cogs"></i>
									<span>Shop Settings</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<li><a href="top-category.php"><i class="fa fa-circle-o"></i> Category</a></li>
									<li><a href="mid-category.php"><i class="fa fa-circle-o"></i> Sub Category</a></li>
								</ul>
							</li>
							<?php
						}
					}
					?>
      			</ul>
    		</section>
  		</aside>

  		<div class="content-wrapper">