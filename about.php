<?php include_once('header.php');?>

      <div class="container mt-3 mb-5">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="./">Home</a></span>/ <span>About us</span></p>
            <h1 class="mb-0 bread">About us</h1>
          </div>
        </div>
      </div>

    <section class="ftco-section ftco-no-pb ftco-no-pt bg-light">
			<div class="container">
				<div class="row">
					<div class="col-md-5 p-md-5 img img-2 d-flex justify-content-center align-items-center" style="background-image: url(images/about.jpg);">
						<a href="https://www.youtube.com/watch?v=kFhtIzYDw-o" class="icon popup-youtube d-flex justify-content-center align-items-center">
							<span class="icon-play"></span>
						</a>
					</div>
					<div class="col-md-7 py-5 wrap-about pb-md-5 ftco-animate">
	          <div class="heading-section-bold mb-4 mt-md-5">
	          	<div class="ml-md-0">
		            <h2 class="mb-4">Welcome to Pamilihan</h2>
	            </div>
	          </div>
	          <div class="pb-md-5">
	          	<p>Shop fresh, high-quality food delivered to your doorstep, including farm-fresh produce, meats, and dairy for healthy, delicious meals.</p>
							<?php
              if(isset($_SESSION['customer'])){
                ?>
                <p><a href="shop.php" class="btn btn-primary">Shop now</a></p>
                <?php
              }else{
                ?>
                <p><a href="login.php?page=shop.php" class="btn btn-primary">Shop now</a></p>
                <?php
              }
              ?>
						</div>
					</div>
				</div>
			</div>
		</section>

    <?php include_once('footer.php');?>