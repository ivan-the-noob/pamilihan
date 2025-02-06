<?php include_once('header.php');
if(!isset($_SESSION['customer'])){
	header("Location: login.php?page=shop.php");
}
if(isset($_SESSION['customer'])){
    // Fetch customer details
    $u = "SELECT * FROM tbl_user WHERE id=:user_id";
    $p = [":user_id" => $_SESSION['customer']['id']];
    $m = $c->fetchData($pdo, $u, $p);
    
    if($m){
        foreach($m as $pp){
            $userFullName = $pp['full_name'];
        }
    }

    // Get today's date (Unix timestamp)
    $today = time();  
    $startOfDay = strtotime("today", $today); 
    $endOfDay = strtotime("tomorrow", $today) - 1; 

    // Modify SQL to fetch payment method along with total amount
    $sql = "SELECT o.order_id, p.total_amount, p.payment_method
            FROM tbl_purchase_order o
            JOIN tbl_purchase_payment p ON o.order_id = p.order_id
            WHERE o.customer_id = :customer_id AND p.date_and_time BETWEEN :start_of_day AND :end_of_day";
    
    $p = [
        ":customer_id" => $_SESSION['customer']['id'],
        ":start_of_day" => $startOfDay,
        ":end_of_day" => $endOfDay
    ];

    $orders = $c->fetchData($pdo, $sql, $p);

    $totalAmountToday = 0;
    $disableCodOption = false;  // Variable to check if COD should be disabled
    $disableGcashOption = false;  // Variable to check if Gcash should be disabled
    
    if($orders){
        foreach($orders as $order){
            $totalAmountToday += $order['total_amount'];
            if ($order['payment_method'] == 'cod' && $order['total_amount'] >= 1000) {
                $disableCodOption = true;  // Disable COD option if condition is met
            }
            if ($order['payment_method'] == 'gcash' && $order['total_amount'] >= 1000) {
                $disableGcashOption = true;  // Disable Gcash option if condition is met
            }
        }
    }

    // Check if the total amount exceeds or is equal to 1000
    if($totalAmountToday >= 1000){
        echo '<script>
            $(document).ready(function(){
                $("#maxAmountModal").modal("show");
            });
        </script>';
    }
}
$B_fullName = "";
$B_phone = "";
$B_address = "";
$B_city = "";
$S_fullName = "";
$S_phone = "";
$S_address = "";
$S_city = "";
$sql1 = "SELECT * FROM tbl_shipping_address WHERE user_id=:u_id";
$p1 = [
	':u_id' =>  $_SESSION['customer']['id'],
];
$res1 = $c->fetchData($pdo, $sql1, $p1);
if($res1 != false){
	foreach($res1 as $row1){
		$B_fullName = $row1['full_name'];
		$B_phone = $row1['phone'];
		$B_address = $row1['address'];
		$B_city = $row1['city'];
	}
}
if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items'];
    $count = count($selected_items);
    $totalAmount = 0;
    ?>

    <div class="container mt-4">
        <div class="row">
		<?php
if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items'];
    $count = count($selected_items);
    $totalAmount = 0;
    ?>

    <div class="container mt-4">
        <div class="row">
            <?php
            foreach ($selected_items as $key => $productId) {
                $index = array_search($productId, $_SESSION['cart_p_id']);
                $quantity = $_SESSION['cart_p_qty'][$index];
                $price = $_SESSION['cart_p_current_price'][$index];
                $totalAmount += $price * $quantity;
            }
            ?>

            
        </div>
    </div>

    <?php
} else {
    echo "<script>alert('No items selected!'); window.location.href='cart.php';</script>";
}
?>

           
        </div>
    </div>

    <?php
} else {
    echo "<script>alert('No items selected!'); window.location.href='cart.php';</script>";
}

if(isset($_SESSION['cart_p_id'])){
	if(!empty($_SESSION['cart_p_id'])){
		$count = 0;
		$count = count($_SESSION['cart_p_id']);
		$total = 0;
		$actualPrice = 0;
		$totalAmount = 0;
		for($i=1;$i<=$count;$i++){
			$totalAmount += $_SESSION['cart_p_qty'][$i] * $_SESSION['cart_p_current_price'][$i];
			$actualPrice += $_SESSION['cart_p_qty'][$i] * $_SESSION['cart_p_current_price'][$i];
		}
		$sql = "SELECT * 
		FROM tbl_service_fee 
		WHERE to_p = (
			SELECT MAX(to_p) 
			FROM tbl_service_fee 
			WHERE from_p <= $count AND to_p >= $count
		) AND from_p <= $count AND to_p >= $count";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$serviceFee = 0;
		$deliveryFee = 0;
		$noService = false;

		if($stmt->rowCount() > 0){
			$noService = true;
			$res100 = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($res100 as $row100){
				$serviceFee = $row100['cost'];
				$deliveryFee = $row100['delivery_fee'];
			}
		}else{
			$sql1 = "SELECT * FROM tbl_service_fee_all";
			$res1 = $c->fetchData($pdo, $sql1);
			if($res1){
				$noService = true;
				foreach($res1 as $row59){
					$serviceFee = $row59['cost'];
					$deliveryFee = $row59['delivery_fee'];
				}
			}else{
				$noService = false;
				$serviceFee = 0;
			}
		}
		$totalAmount = $totalAmount + $serviceFee + $deliveryFee;
	}else{
		echo "<script>alert('Your cart is empty! Add a product in your cart!'); window.location.href='./';</script>";
	}
}else{
	echo "<script>alert('Your cart is empty! Add a product in your cart!'); window.location.href='./';</script>";
}

?>

	<div class="container mt-5">
	<div class="row no-gutters slider-text align-items-center justify-content-center">
		<div class="col-md-9 ftco-animate text-center">
		<p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span>/ <span>Checkout</span></p>
		<h1 class="mb-0 bread">Checkout</h1>
		</div>
	</div>
	</div>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-8 ftco-animate p-3 border">
				<div class="row align-items-start">
					<div class="col-md-6">
						<h3 class="mt-3 billing-heading">Billing Details</h3>
						<hr/>
						<div class="row align-items-end">
							<div class="col-md-12">
								<div class="form-group">
									<label for="">Full Name</label>
									<input type="text" value="<?= $B_fullName; ?>" style="background-color: #E8E8E8 !important;" readonly class="form-control">
								</div>
								<div class="form-group">
									<label for="">Phone No. </label>
									<input type="text" value="<?= $B_phone; ?>" style="background-color: #E8E8E8 !important;" readonly class="form-control">
								</div>
								<div class="form-group">
									<label for="">Country</label>
									<input type="text" value="Philippines" style="background-color: #E8E8E8 !important;" readonly class="form-control">
								</div>
								<div class="form-group">
									<label for="">Address</label>
									<textarea row="3" style="background-color: #E8E8E8 !important;" readonly class="form-control"><?= $B_address; ?></textarea>
								</div>
								<div class="form-group">
									<label for="">City</label>
									<input type="text" value="<?= $B_city; ?>" style="background-color: #E8E8E8 !important;" readonly class="form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row align-items-end">
							<div class="col-md-12">
							<div class="card mb-1">
								<div class="card-body">
									<div class="card-title">Payment Method</div>
									<select id="payment-method" name="payment_method" class="form-control" required>
										<option value="" disabled selected>Select Payment</option>
										<option value="cod" <?php echo ($disableCodOption) ? 'disabled' : ''; ?> id="cod-option">
											<?php echo ($disableCodOption) ? 'COD MAXED CHECKOUT' : 'Cash on Delivery (COD)'; ?>
										</option>
										<option value="gcash" <?php echo ($disableGcashOption) ? 'disabled' : ''; ?> id="gcash-option">
											<?php echo ($disableGcashOption) ? 'Gcash MAXED CHECKOUT' : 'Gcash'; ?>
										</option>
									</select>

									<p id="payment-error" style="color: red;">Please select payment method first</p> <!-- Error message -->

									<div id="gcashFields" style="display: none; margin-top: 10px;">
										<label for="gcashName">Name</label>
										<input type="text" id="gcashName" name="gcash_name" class="form-control" placeholder="Enter your name">

										<label for="gcashImage">Upload Image</label>
										<input type="file" id="gcashImage" name="gcash_image" class="form-control">

										<label for="gcashReference">Reference Number</label>
										<input type="number" id="gcashReference" name="gcash_reference" class="form-control" placeholder="Enter reference number">
									</div>

								</div>
							</div>


						

							<div class="card bg-white" style="height: 70vh; overflow-y: 10px;">
								<div class="card-body">
									<h5 class="card-title">Order Summary</h5>
									<?php
									require_once 'system/inc/config.php';

									foreach ($selected_items as $key => $productId) {
										$stmt = $pdo->prepare("
											SELECT 
												p.p_name, 
												s.business_name 
											FROM 
												tbl_product p
											LEFT JOIN 
												tbl_seller s 
											ON 
												p.u_id = s.user_id
											WHERE 
												p.p_id = :productId
										");
										$stmt->execute(['productId' => $productId]);
										$productData = $stmt->fetch(PDO::FETCH_ASSOC);
									
										$index = array_search($productId, $_SESSION['cart_p_id']);
										$quantity = $_SESSION['cart_p_qty'][$index];
										$price = $_SESSION['cart_p_current_price'][$index];
										$productName = $productData ? $productData['p_name'] : 'Unknown Product';
										$businessName = $productData ? $productData['business_name'] : 'Unknown Business';
									
										?>
									
									<?php
										$price = (float)$price;
										$quantity = (int)$quantity;
										$totalPrice = $price * $quantity;
										?>
										<p class="card-text">Product Name: <?php echo htmlspecialchars($productName); ?></p>
										<p class="card-text">Business Name: <?php echo htmlspecialchars($businessName); ?></p>
										<p class="card-text">Quantity: <?php echo $quantity; ?></p>
										<p class="card-text">Price: ₱<?php echo number_format($totalPrice, 2); ?></p>											
											<hr>
										<?php
									}
									?>

								</div>
							</div><br><br>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<p><b><span class="text text-danger">Note:</span></b>&nbsp;If you want to update your billing and shipping details, just click <a href="settings.php?t=myAccount" class="text text-primary">here</a>.</p>
					</div>
				</div>
		  </div>
			<div class="col-xl-4">
	          <div class="row mt-5 pt-3">
	          	<div class="col-md-12 d-flex mb-5">
	          		<div class="cart-detail cart-total p-3 p-md-4">
	          			<h3 class="billing-heading mb-4 text-danger">Reminder</h3>
						<hr/>
						<ul>
							<li>Payment method is determined by you and the rider via message.</li>
							<li>Check your billing and shipping information to ensure it is right.</li>
						</ul>
					</div>
	          	</div>
				  <div class="col-md-12 d-flex mb-5">
	          		<div class="cart-detail cart-total p-3 p-md-4">
	          			<h3 class="billing-heading mb-4">Cart Total</h3>
						<hr/>
	          			<p class="d-flex">
							<span>Subtotal</span>
							<span><?= $php; ?><?= number_format($actualPrice, 2); ?></span>
						</p>
						<?php
						if($noService){
							?>
							<p class="d-flex">
								<span>Delivery Fee</span>
								<span><?= $php; ?><?= $deliveryFee; ?></span>
							</p>
							<p class="d-flex">
								<span>Serivce Fee</span>
								<span><?= $php; ?><?= number_format($serviceFee, 2); ?></span>
							</p>
							<?php
						}
						?>
						<hr>
						<p class="d-flex total-price">
							<span>Total</span>
							<span><?= $php; ?><?= number_format($totalAmount, 2); ?></span>
						</p>
						<p><a href="#" class="btn btn-primary btn-block py-3 px-4 placeOrder" id="place-order-btn" disabled>Select payment method first</a></p> <!-- Place Order Button -->
					</div>
	          	</div>
	          </div>
          </div> <!-- .col-md-8 -->
        </div>
      </div>
	  <br><hr/>
    </section> <!-- .section -->

<?php include_once('footer.php');?>
<script>
	$(document).ready(function(){
    $(document).on('click', '.placeOrder', function(e){
        e.preventDefault();
        var myId = "<?= $_SESSION['customer']['id']; ?>";
        var myTotal = "<?= $totalAmount; ?>";

        if(confirm("Are you sure you want to order these items for at least <?= number_format($totalAmount,2);?>php?")){

            var formData = new FormData();
            formData.append('myId', myId);
            formData.append('myTotal', myTotal);
            formData.append('checkout', true);
            formData.append('payment_method', $("#payment-method").val());  // This matches the name attribute in HTML
            formData.append('gcash_name', $("#gcashName").val());
            formData.append('gcash_reference', $("#gcashReference").val());

            // Handle file input for Gcash image
            var gcashImage = $("#gcashImage")[0].files[0];
            if (gcashImage) {
                formData.append('gcash_image', gcashImage);
            }

            $.ajax({
                url: "action.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false, 
                dataType: "JSON",
                success: function(data) {
                    if (data.error != "") {
                        alert(data.error);
                    } else {
                        alert(data.success);
                        window.location.href = "checkout-process.php";
                    }
                }
            });
        }
    });
});

</script>

<script>
    document.getElementById("payment-method").addEventListener("change", function() {
        var gcashFields = document.getElementById("gcashFields");
        var gcashInputs = gcashFields.querySelectorAll("input");

        if (this.value === "gcash") {
            gcashFields.style.display = "block";
            gcashInputs.forEach(input => input.required = true);
        } else {
            gcashFields.style.display = "none";
            gcashInputs.forEach(input => input.required = false);
        }
    });
</script>

<!-- JavaScript to handle the "Place an Order" button -->
<script>
    // Show the error message initially since no selection is made
    document.getElementById('payment-error').style.display = 'block';

    // Disable the "Place an order" button initially
    var placeOrderBtn = document.getElementById('place-order-btn');
    placeOrderBtn.disabled = true;

    document.getElementById('payment-method').addEventListener('change', function() {
        var paymentMethod = this.value;

        // Hide the error message when a valid payment method is selected
        if (paymentMethod) {
            document.getElementById('payment-error').style.display = 'none';
            placeOrderBtn.disabled = false; // Enable the "Place an order" button
            placeOrderBtn.textContent = 'Place an order'; // Reset the text
        } else {
            document.getElementById('payment-error').style.display = 'block';
            placeOrderBtn.disabled = true; // Disable the "Place an order" button
            placeOrderBtn.textContent = 'Select payment method first'; // Update the text
        }
    });
</script>