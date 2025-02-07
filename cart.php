<?php include_once('header.php');?>

<?php
error_reporting();
$error_message = '';
// unset($_SESSION['cart_p_seller_id']);
// unset($_SESSION['cart_p_type']);
// unset($_SESSION['cart_p_size']);
// unset($_SESSION['cart_p_id']);
// unset($_SESSION['cart_p_qty']);
// unset($_SESSION['cart_p_current_price']);
// unset($_SESSION['cart_p_name']);
// unset($_SESSION['cart_p_featured_photo']);
// unset($_SESSION['cart_p_id']);
if(isset($_POST['form1'])) {

    $i = 0;
    $statement = $pdo->prepare("SELECT p.*, inv.stock_in AS qty FROM tbl_product p JOIN tbl_inventory inv ON p.p_id=inv.p_id");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $i++;
        $table_product_id[$i] = $row['p_id'];
        $table_quantity[$i] = $row['qty'];
    }

    $i=0;
    foreach($_POST['product_id'] as $val) {
        $i++;
        $arr1[$i] = $val;
    }
    $i=0;
    foreach($_POST['quantity'] as $val) {
        $i++;
        $arr2[$i] = $val;
    }
    $i=0;
    foreach($_POST['product_name'] as $val) {
        $i++;
        $arr3[$i] = $val;
    }
    
    $allow_update = 1;
    for($i=1;$i<=count($arr1);$i++) {
        for($j=1;$j<=count($table_product_id);$j++) {
            if($arr1[$i] == $table_product_id[$j]) {
                $temp_index = $j;
                break;
            }
        }
        if($table_quantity[$temp_index] < $arr2[$i]) {
        	$allow_update = 0;
            $error_message .= '"'.$arr2[$i].'" items are not available for "'.$arr3[$i].'"\n';
        } else {
            $_SESSION['cart_p_qty'][$i] = $arr2[$i];
        }
    }
    $error_message .= '\nOther items quantity are updated successfully!';
    ?>
    
    <?php if($allow_update == 0): ?>
    	<script>alert('<?php echo $error_message; ?>');</script>
	<?php else: ?>
		<script>alert('All Items Quantity Update is Successful!');</script>
	<?php endif; ?>
    <?php

}
?>

	<div class="container mt-3">
		<div class="row no-gutters slider-text align-items-center justify-content-center">
			<div class="col-md-9 ftco-animate text-center">
				<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span>/ <span>Cart</span></p>
				<h1 class="mb-0 bread">My Cart</h1>
			</div>
		</div>
	</div>
	
    	<section class="mt-5 ftco-cart">
			<div class="container">
				<div class="row">
					<div class="col-md-12 ftco-animate">
					
						<div class="cart-list">
							<table class="table">
								<thead class="thead-primary">
								<tr class="text-center">
									<th>Select</th>
									<th>No. </th>
									<th>Product Name</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total</th>
                                    <th>Actions</th>
									<th>&nbsp;</th>
								</tr>
								</thead>
								<form method="POST" action="checkout.php">
                                <tbody>
    <?php
    if(!isset($_SESSION['cart_p_id'])){
        ?>
        <tr class="text-center">
            <td colspan="7"><span class="text-danger">Cart is Empty!</span><br><span>Just click <a href="shop.php">here</a>, if you want to add a product in your cart.</span></td>
        </tr>
        <?php
    } else {
        $totalAmount = 0;
        $showSeller = array();
        $showSellerId = array();
        $showShortDescription = array();
        $showQuantity = array();
        $showSizeName = array();
        $index = 1;
        $i = 0;


        foreach($_SESSION['cart_p_id'] as $key => $value) {
            $i++;
            $arr_cart_p_id[$i] = $value;
            $stmt1 = $pdo->prepare("SELECT inv.stock_in AS qty, seller.business_title, p.* FROM tbl_product p LEFT JOIN tbl_inventory inv ON p.p_id=inv.p_id JOIN tbl_seller seller ON p.u_id=seller.user_id WHERE p.p_id=?");
            $stmt1->execute(array($value));
            $res = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            foreach($res as $r) {
                $showSellerId[$index] = $r['u_id'];
                $showSeller[$index] = $r['business_title'];
                $showShortDescription[$index] = $r['p_short_description'];
                $showQuantity[$index] = $r['qty'];
                $index++;
            }
        }

        $i = 0;
        foreach($_SESSION['cart_p_qty'] as $key => $value) {
            $i++;
            $arr_cart_p_qty[$i] = $value;
        }

        $index = 1;
        $i = 0;
        foreach($_SESSION['cart_p_size'] as $key => $value) {
            $i++;
            $arr_cart_p_size[$i] = $value;
            $stmt2 = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
            $stmt2->execute(array($value));
            $res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            if($stmt2->rowCount() > 0) {
                foreach($res2 as $r2) {
                    $showSizeName[$i] = $r2['size_name'];
                    $index++;
                }
            }
        }

        $i = 0;
        foreach($_SESSION['cart_p_current_price'] as $key => $value) {
            $i++;
            $arr_cart_p_current_price[$i] = $value;
        }

        $i = 0;
        foreach($_SESSION['cart_p_name'] as $key => $value) {
            $i++;
            $arr_cart_p_name[$i] = $value;
        }

        $i = 0;
        foreach($_SESSION['cart_p_featured_photo'] as $key => $value) {
            $i++;
            $arr_cart_p_featured_photo[$i] = $value;
        }
    ?>
    <?php for($i=1; $i<=count($arr_cart_p_id); $i++): ?>
    <tr class="text-center">
        <td class="no"><?php echo $i; ?></td>
        <td class="image-prod">
            <div class="img" style="background-image:url(assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>);"></div>
        </td>
        <td class="product-name">
            <h3><?php echo $arr_cart_p_name[$i]; ?></h3>
            <p><?php $output = !empty($showSizeName[$i]) ? $showSizeName[$i].'<br>' : ''; echo $output; ?><a href="#<?php echo $showSellerId[$i];?>"><?php echo $showSeller[$i]; ?></a></p>
        </td>
        <td class="price"><?php echo $php; ?><span class="price_per_product"><?php echo number_format($arr_cart_p_current_price[$i], 2); ?></span></td>
        <td class="quantity">
            <div class="input-group col-md-12 d-flex mb-3">
                <span class="input-group-btn mr-2">
                    <button type="button" class="quantity-left-minus btn" data-id="<?= $arr_cart_p_id[$i]; ?>" data-type="minus" data-field="">
                        <i class="ion-ios-remove"></i>
                    </button>
                </span>
                <input type="text" name="quantity" step="1" class="form-control input-number quantity1" data-qty="<?php echo $i; ?>" data-id="<?php echo $arr_cart_p_id[$i]; ?>" value="<?php echo $arr_cart_p_qty[$i]; ?>" min="1" max="<?php echo $showQuantity[$i]; ?>" readonly>
                <span class="input-group-btn ml-2">
                    <button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
                        <i class="ion-ios-add"></i>
                    </button>
                </span>
            </div>
        </td>
        <td class="total">
            <span><?php echo $php; ?></span><span class="total_amount_per_product"><?php echo number_format($arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i], 2); ?></span>
        </td>
        <td class="product-remove">
            <a onclick="return confirmDelete();" href="cart-delete.php?id=<?php echo $arr_cart_p_id[$i]; ?>&s=<?= $arr_cart_p_size[$i]; ?>"><span class="text-danger ion-ios-trash"></span></a>
        </td>
        <td class="product-select">
            <input type="checkbox" name="selected_items[]" value="<?php echo $arr_cart_p_id[$i]; ?>" class="cart-item-checkbox">
        </td>
    </tr>
    <?php endfor; ?>
    <tr class="text-right">
        <td colspan="5"></td>
        <td><b>Total Amount:</b></td>
        <td colspan="1"><?php echo $php; ?><span class="forTotalAmount">0.00</span></td>
    </tr>
    <tr>
        <td colspan="7">
        <div id="modalButtonContainer" style="display: none;">
    <button type="button" class="btn btn-danger btn-block py-3 w-25 text-white d-flex justify-content-center mx-auto" data-toggle="modal" data-target="#maxWeightModal">
        20Kg Max Per CheckOut
    </button>
    </div>

    <button id="checkoutBtn" type="submit" class="btn btn-primary btn-block py-3 w-25 text-white d-flex justify-content-center mx-auto">
        Checkout Selected Items
    </button>


        </td>
    </tr>
    <?php
    }
    ?>
</tbody>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        const totalAmountSpan = document.querySelector('.forTotalAmount');
        const checkoutButton = document.getElementById('checkoutBtn'); 
        const modalButtonContainer = document.getElementById('modalButtonContainer'); 

        function parseWeight(sizeText) {
            let weight = 0;
            let match = sizeText.match(/(\d+)\/?(\d*)kg/i);

            if (match) {
                let numerator = parseInt(match[1]);
                let denominator = match[2] ? parseInt(match[2]) : 1;
                weight = numerator / denominator;
            }
            return weight;
        }

        function updateTotalAmount() {
            let total = 0;
            let totalWeight = 0;

            checkboxes.forEach(function (checkbox, index) {
                if (checkbox.checked) {
                    const price = parseFloat(document.querySelectorAll('.price_per_product')[index].textContent.replace(/[^0-9.-]+/g, ""));
                    const quantity = parseInt(document.querySelectorAll('.quantity1')[index].value);
                    const sizeText = document.querySelectorAll('.product-name p')[index].textContent.trim(); 

                    let weightPerUnit = parseWeight(sizeText);
                    totalWeight += (weightPerUnit * quantity);
                    
                    total += price * quantity;
                }
            });

            totalAmountSpan.textContent = total.toFixed(2);
            console.log("Total Weight: " + totalWeight + "kg");

            if (totalWeight < 20) {
                checkoutButton.style.visibility = "visible"; 
                modalButtonContainer.style.visibility = "hidden"; 
            } else {
                checkoutButton.style.visibility = "hidden";
                modalButtonContainer.style.display = "block";
                modalButtonContainer.style.visibility = "visible"; 
                
            }
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateTotalAmount);
        });

        updateTotalAmount();
    });
</script>




</form>

							</table>
						</div>
						
					</div>
				</div>
			</div>
			<br><hr/>
		</section><br><br>

<?php include_once('footer.php');?>
<script>
	function confirmDelete()
	{
	    return confirm("You want to delete this data?");
	}
		$(document).ready(function(){

		var quantitiy=0;
		   $(document).on('keyup', '.quantity1', function(){
			var quantity1 = parseInt($(this).data('id'));
			var maxQuantity = parseInt($(this).attr('max'));
			if(quantity > maxQuantity){
				$('.quantity1').val(maxQuantity);
			}
		   });
		   	$(document).on('click', '.quantity-right-plus', function(e) {
				e.preventDefault();
				var $input = $(this).closest('.input-group').find('.quantity1');
				var quantity = parseInt($input.val());
				var maxQuantity = parseInt($input.attr('max'));
				var pId = parseInt($input.data('id'));
				var sesId = parseInt($input.data('qty'));
				if (quantity < maxQuantity) {
					$input.val(quantity + 1);
					quantity++;
				} else {
					$input.val(maxQuantity);
				}

				$.ajax({
					url: "action.php",
					method: "POST",
					data: { 'dec_inc_quantity': true, 'pId': pId, 'sesId': sesId, 'quantity': quantity },
					dataType: "HTML",
					success: function(data) {
						var $totalAmount = $input.closest('tr').find('.total_amount_per_product');
						$totalAmount.html('<img src="assets/uploads/loading.gif" title="Loading..." width="25px" height="25px" alt="Loading..."/>');
						$('.quantity-right-plus').attr('disabled', true);
						setTimeout(function(){
           					$totalAmount.html(data);
							$('.quantity-right-plus').removeAttr('disabled');
							$.ajax({
								url:"action.php",
								method:"POST",
								data:{"forTotalAmount": true},
								dataType: "HTML",
								success:function(data){
									$('.forTotalAmount').html(data);
								}
							});
						},750);
					}
				});
			});

			$(document).on('click', '.quantity-left-minus', function(e) {
				e.preventDefault();
				var $input = $(this).closest('.input-group').find('.quantity1');
				var dataId = $input.data('id');
				var quantity = parseInt($input.val());
				var pId = parseInt($input.data('id'));
				var sesId = parseInt($input.data('qty'));
				if (quantity >= 1) {
					if(quantity == 1){
						if(confirm("Are you sure you want to remove this product in your cart?")){
							window.location.href="cart-delete.php?id="+dataId;
						}else{
							$input.val(1);
							quantity;
						}
					}else{
						$input.val(quantity - 1);
						quantity--;
					}
				}
				

				$.ajax({
					url: "action.php",
					method: "POST",
					data: { 'dec_inc_quantity': true, 'pId': pId, 'sesId': sesId, 'quantity': quantity },
					dataType: "HTML",
					success: function(data) {
						var $totalAmount = $input.closest('tr').find('.total_amount_per_product');
						$totalAmount.html('<img src="assets/uploads/loading.gif" title="Loading..." width="25px" height="25px" alt="Loading..."/>');
						$('.quantity-left-minus').attr('disabled', true);
						setTimeout(function(){
           					$totalAmount.html(data);
							$('.quantity-left-minus').removeAttr('disabled');
							$.ajax({
								url:"action.php",
								method:"POST",
								data:{"forTotalAmount": true},
								dataType: "HTML",
								success:function(data){
									$('.forTotalAmount').html(data);
								}
							});
						},750);
					}
				});
			});
		    
		});
	</script>