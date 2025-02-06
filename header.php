<?php

include_once("system/inc/config.php");
include_once("system/inc/functions.php");
include_once("system/inc/class.php");
include_once("system/inc/CSRF_Protect.php");

$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

$c = new CustomizeClass();
$csrf = new CSRF_Protect();
// For settings
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

    $cta_title = $row['cta_title'];
    $cta_content = $row['cta_content'];
    $cta_read_more_text = $row['cta_read_more_text'];
    $cta_read_more_url = $row['cta_read_more_url'];
    $cta_photo = $row['cta_photo'];
    $featured_product_title = $row['featured_product_title'];
    $featured_product_subtitle = $row['featured_product_subtitle'];
    $latest_product_title = $row['latest_product_title'];
    $latest_product_subtitle = $row['latest_product_subtitle'];
    $popular_product_title = $row['popular_product_title'];
    $popular_product_subtitle = $row['popular_product_subtitle'];
    $total_featured_product_home = $row['total_featured_product_home'];
    $total_latest_product_home = $row['total_latest_product_home'];
    $total_popular_product_home = $row['total_popular_product_home'];
    $home_service_on_off = $row['home_service_on_off'];
    $home_welcome_on_off = $row['home_welcome_on_off'];
    $home_featured_product_on_off = $row['home_featured_product_on_off'];
    $home_latest_product_on_off = $row['home_latest_product_on_off'];
    $home_popular_product_on_off = $row['home_popular_product_on_off'];

    $banner_cart = $row['banner_cart'];

}

if(isset($_SESSION['customer'])){
  $u = "SELECT * FROM tbl_user WHERE id=:user_id";
  $p = [
    ":user_id"  =>  $_SESSION['customer']['id']
  ];
  $m = $c->fetchData($pdo, $u, $p);
  if($m){
    foreach($m as $pp){
      $userFullName = $pp['full_name'];
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Pamilihan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
	<link rel="icon" type="image/png" href="assets/uploads/<?php echo $favicon; ?>">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">
    
  
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/select2.min.css">
    
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/message.css">
  </head>
  <body class="goto-here">
      <div class="py-1 bg-primary">
    	<div class="container">
    		<div class="row no-gutters d-flex align-items-start align-items-center px-md-0">
	    		<div class="col-lg-12 d-block">
		    		<div class="row d-flex">
		    			<div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
						    <span class="text">+63 9876 543 210</span>
					    </div>
					    <div class="col-md pr-4 d-flex topper align-items-center">
					    	<div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
						    <span class="text">store@pamilihannet.com</span>
					    </div>
					    <div class="col-md pr-4 d-flex topper align-items-center">
						    <?php
                            if(!isset($_SESSION['customer'])){
                                ?>
                                <div class="icon mr-2 d-flex justify-content-right align-items-center"><span class="icon-user"></span></div>
						                    <span class="text"><a href="login.php" class="text-white">Login as Customer</a></span>
                                <?php
                            }
                            ?>
					    </div>
              <form id="searchProductSeller">
                  <div class="col-md pr-4 d-flex topper align-items-center">
                      <div class="button">
                          <div class="icon mr-2 d-flex justify-content-center align-items center"><button type="submit" class="btn btn-secondary" style="font-size: 10px !important; border-radius: 5px;"><span class="icon-search"></span></button></div>
                      </div>
                      <!-- <div class="icon mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div> -->
                      <input type="text" class="form-control px-1 py-1" name="keyword" id="keyword" maxlength="255" required style="height: 25px !important; font-size: 10px !important; width: 200px;" placeholder="Search product, seller...">
                  </div>
              </form>
				    </div>
			    </div>
		    </div>
		  </div>
      </div>
      <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar" style="border-bottom: 0.5px solid silver;">
	    <div class="container">
	      <a class="navbar-brand" href="./">Pamilihan</a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item <?php if($cur_page == "index.php"){ echo "active"; }?>"><a href="index.php" class="nav-link">Home</a></li>
			      <li class="nav-item <?php if($cur_page == "shop.php" || $cur_page == "product-singe.php"){ echo "active"; }?>"><a href="shop.php" class="nav-link">Shop</a></li>
            <li class="nav-item <?php if($cur_page == "recipe.php" || $cur_page == "recipe-singe.php"){ echo "active"; }?>"><a href="recipe.php" class="nav-link">Recipe</a></li>
			  <!-- FOR DROP DOWN TEMPLATE -->
	          <!-- <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
				<div class="dropdown-menu" aria-labelledby="dropdown04">
					<a class="dropdown-item" href="shop.html">Shop</a>
					<a class="dropdown-item" href="wishlist.html">Wishlist</a>
					<a class="dropdown-item" href="product-single.html">Single Product</a>
					<a class="dropdown-item" href="cart.html">Cart</a>
					<a class="dropdown-item" href="checkout.html">Checkout</a>
				</div>
              </li> -->
	          <li class="nav-item <?php if($cur_page == "about.php"){ echo "active"; }?>"><a href="about.php" class="nav-link">About</a></li>
	          <!-- <li class="nav-item <?php if($cur_page == "blog.php"){ echo "active"; }?>"><a href="blog.php" class="nav-link">Blog</a></li> -->
	          <li class="nav-item <?php if($cur_page == "contact.php"){ echo "active"; }?>"><a href="contact.php" class="nav-link">Contact</a></li>
	          <?php
              if(isset($_SESSION['customer'])){
                ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" style="cursor: pointer;" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="icon-user"></span> <?php if(isset($_SESSION['customer'])){ echo $userFullName; } ?></a>
                  <div class="dropdown-menu" aria-labelledby="dropdown04">
                    <a class="dropdown-item" style="border-bottom: 1px solid silver;" href="settings.php?t=myAccount" id="myAccount">My Account</a>
                    <a class="dropdown-item" style="border-bottom: 1px solid silver;" href="settings.php?t=myOrder" id="myOrder">My Order</a>
                    <a class="dropdown-item" style="border-bottom: 1px solid silver;" href="settings.php?t=changePassword" id="changePassword">Change Password</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                  </div>
                </li>
                <li class="nav-item cta cta-colored"><a href="cart.php" class="nav-link"><span class="icon-shopping_cart"></span>[<?php if(isset($_SESSION['cart_p_id'])){ echo count($_SESSION['cart_p_id']); }else{ echo 0;} ?>]</a></li>
                <?php
              }
              ?>

	        </ul>
	      </div>
	    </div>
	  </nav>
    <div class="newContent"></div>
    <!-- END nav -->