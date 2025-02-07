<?php
include_once("system/inc/config.php");
include_once("system/inc/functions.php");
include_once("system/inc/class.php");
include_once("system/inc/CSRF_Protect.php");

$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

$c = new CustomizeClass();
$csrf = new CSRF_Protect();

$allOrders = "
    SELECT 
        pp.total_amount AS total_price, 
        pp.transaction_id AS transaction_id, 
        o.*, 
        o.estimated_time AS estimated_time,
        u.full_name, 
        u.email AS rider_email,
        r.vehicle_type,
        r.vehicle_model,
        r.vehicle_plate_no,
        pi.seller_id
    FROM tbl_purchase_order o
    JOIN tbl_purchase_payment pp ON o.order_id = pp.order_id
    LEFT JOIN tbl_user u ON o.rider_id = u.id
    LEFT JOIN tbl_rider r ON o.rider_id = r.user_id
    LEFT JOIN tbl_purchase_item pi ON o.order_id = pi.order_id
    WHERE o.customer_id = :c_id 
    ORDER BY o.id DESC
";

$allOrdersP = [
    ":c_id" => $_SESSION['customer']['id']
];

$allOrdersS = $c->fetchData($pdo, $allOrders, $allOrdersP);

$orderId = "";
$totalPrice = "";
$transactionId = "";
$dat = "";
$status = "";
$rider_id = "";
$full_name = "";
$vehicle_type = "";
$vehicle_model = "";
$vehicle_plate_no = "";
$seller_status = "";
$driver_status = "";
$seller_rating_status = "";
$rider_rating_status = "";
$seller_rating_status= "";

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
                <th rowspan="2" class="border">Rider</th>
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
                    $full_name = $r['full_name'];
                    $vehicle_type = $r['vehicle_type'];
                    $vehicle_model = $r['vehicle_model'];
                    $vehicle_type = $r['vehicle_type'];
                    $rider_id = $r['rider_id'];
                    $report_seller = $r['report_seller_status'];
                    $report_rider = $r['report_rider_status'];
                    $rate_rider = $r['rate_rider_status'];
                    $rate_seller = $r['rate_seller_status'];
                    $return_items = $r['return_status'];
    
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
                    if($res1){
                        $itemNo = 1;
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
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $val['p_name']; ?><br><span><small><?= $showSizeName; ?></span></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $php; ?><?= $val['product_price']; ?></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $val['product_qty']; ?></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $php; ?><?= $totalPricePerItem; ?></td>
                                <?php
                                if($itemNo == 1){
                                    ?>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $php; ?><?= number_format($totalPrice, 2); ?></td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $transactionId; ?></td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px"><?= $dat; ?></td>
                                    <?php if (!empty($rider_id)): ?>
                                        <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px">
                                            <?= $full_name; ?><br>
                                            <?= $vehicle_type; ?><br>
                                            <?= $vehicle_model; ?><br>
                                            <?= $vehicle_plate_no; ?>
      
                                            <?php
                                            if ($report_rider == 0): ?>
                                               <button class="btn btn-danger btn-sm text-nowrap mb-1" id="reportRiderButton" data-toggle="modal" data-target="#reportRiderModal">Report Rider</button>
                                            <?php endif; ?>
                                            <?php
                                            if($rate_rider == 0): ?>
                                            <button class="btn btn-warning btn-sm text-nowrap text-white fw-bold" id="rateRiderButton" data-toggle="modal" data-target="#rateRiderModal">Rate Rider</button>
                                            <?php endif; ?>
                                        </td>
                                    <?php else: ?>
                                        <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px">Pending</td>
                                    <?php endif; ?>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $r['remarks']; ?><?php if($stat == "Pending" || $stat == "In Transit"){ echo '<button type="button" class="btn btn-secondary" style="border-radius: 5px !important;"><span class="icon-map-marker"></span>&nbsp;Track</button>'; } ?>
                                    
                                    <?php if (!empty($rider_id)): ?>
                                        <?php if ($report_seller == 0): ?>
                                            <button class="btn btn-danger btn-sm text-nowrap mb-1" id="reportSellerButton" data-toggle="modal" data-target="#reportSellerModal">Report Seller</button>
                                        <?php endif; ?>
                                        <?php if ($return_items == 0): ?>
                                            <button class="btn btn-info btn-sm text-nowrap text-white fw-bold mb-1" id="return-items" data-toggle="modal" data-target="#returnItems">Return Items</button>
                                        <?php endif; ?>
                                        <?php if ($rate_seller == 0): ?>
                                            <button class="btn btn-warning btn-sm text-nowrap text-white fw-bold" id="rateSellerButton" data-toggle="modal" data-target="#rateSellerModal">Rate Seller</button>
                                        <?php endif; ?>   
                                           
                                    <?php endif; ?>   

                                    <div class="modal fade" id="returnItems" tabindex="-1" role="dialog" aria-labelledby="returnItemsLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="returnItemsLabel">Return Items</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="returnForm" enctype="multipart/form-data">
                                                
                                                <!-- Reason for Return -->
                                                <div class="form-group">
                                                    <label for="reason">Reason for Return</label>
                                                    <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                                                </div>

                                                <!-- Upload Picture -->
                                                <div class="form-group">
                                                    <label for="gcashImage">Upload Picture</label>
                                                    <input type="file" name="gcash_image" id="gcashImage" class="form-control" required>
                                                </div>

                                                <!-- Gcash Name -->
                                                <div class="form-group">
                                                    <label for="gcashName">Gcash Name</label>
                                                    <input type="text" name="gcash_name" id="gcashName" class="form-control" required>
                                                </div>

                                                <!-- Gcash Number -->
                                                <div class="form-group">
                                                    <label for="gcashNumber">Gcash Number</label>
                                                    <input type="number" name="gcash_number" id="gcashNumber" class="form-control" required>
                                                </div>

                                                <!-- Hidden Input for Order ID -->
                                                <input type="hidden" name="order_id" value="<?= $orderId; ?>">

                                                <!-- Submit Button -->
                                                <div class="form-group text-center">
                                                    <button type="submit" class="btn btn-primary">Submit Return Request</button>
                                                </div>

                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                        </div>

                                   
                                    <div class="modal fade" id="rateRiderModal" tabindex="-1" aria-labelledby="rateRiderModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rateRiderModalLabel">Rate Rider</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="rateRiderForm">
                                                        <input type="hidden" name="order_id" value="<?= $orderId; ?>">
                                                        <input type="hidden" name="customer_id" value="<?= $r['customer_id']; ?>">
                                                        <input type="hidden" name="customer_id" value="<?= $r['rider_id']; ?>">
                                                        <input type="hidden" name="rider_id" value="<?= $rider_id; ?>">
                                                     
                                                        

                                                        <div class="mb-3">
                                                            <label for="rider_rating" class="form-label">Rating</label>
                                                            <div class="star-rating d-flex justify-content-center">
                                                                <input type="radio" id="star5" name="rider_rating" value="5" />
                                                                <label for="star5" title="5 stars"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star4" name="rider_rating" value="4" />
                                                                <label for="star4" title="4 stars"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star3" name="rider_rating" value="3" />
                                                                <label for="star3" title="3 stars"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star2" name="rider_rating" value="2" />
                                                                <label for="star2" title="2 stars"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star1" name="rider_rating" value="1" />
                                                                <label for="star1" title="1 star"><i class="fa fa-star"></i>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="feedback" class="form-label">Feedback</label>
                                                            <textarea class="form-control" id="feedback" name="feedback" rows="3" required maxlength="100" placeholder="Max 100 letters."></textarea>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit Rating</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="rateSellerModal" tabindex="-1" aria-labelledby="rateSellerLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rateSellerLabel">Rate Seller</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="rateSellerForm">
                                                    <div class="modal-body">
                                                    <input type="hidden" name="order_id" value="<?= $orderId; ?>">
                                                        <input type="hidden" name="customer_id" value="<?= $r['customer_id']; ?>">
                                                        <input type="hidden" name="seller_id" value="<?= $r['seller_id']; ?>">
      

                                                        <div class="mb-3">
                                                            <label class="form-label">Rating</label>
                                                            <div class="star-rating d-flex justify-content-center">
                                                                <input type="radio" id="star5s" name="seller_rating" value="5" required />
                                                                <label for="star5s"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star4s" name="seller_rating" value="4" />
                                                                <label for="star4s"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star3s" name="seller_rating" value="3" />
                                                                <label for="star3s"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star2s" name="seller_rating" value="2" />
                                                                <label for="star2s"><i class="fa fa-star"></i></label>

                                                                <input type="radio" id="star1s" name="seller_rating" value="1" />
                                                                <label for="star1s"><i class="fa fa-star"></i></label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Feedback</label>
                                                            <textarea class="form-control" name="feedback" rows="3" required maxlength="100" placeholder="Max 100 letters."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal fade" id="reportRiderModal" tabindex="-1" aria-labelledby="#reportRiderModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Report Rider</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="reportRiderForm">
                                                            <input type="hidden" name="order_id" value="<?= $orderId; ?>">
                                                            <input type="hidden" name="customer_id" value="<?= $r['customer_id']; ?>">
                                                            <input type="hidden" name="rider_id" value="<?= $r['rider_id']; ?>">
                                                            <input type="hidden" name="seller_id" value="<?= $r['seller_id']; ?>">

                                                            <div class="mb-3">
                                                                <label for="report_reason_rider" class="form-label">Reason for Report</label>
                                                                <textarea class="form-control" name="report_reason" id="report_reason_rider" rows="4" required></textarea>
                                                            </div>

                                                            <button type="submit" class="btn btn-danger">Submit Report</button>
                                                        </form>
                                                        <div id="reportRiderMessage" class="floating-message"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="modal fade" id="reportSellerModal" tabindex="-1" aria-labelledby="reportSellerModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Report Seller</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="reportForm">
                                                        <input type="hidden" name="order_id" value="<?= $orderId; ?>">
                                                        <input type="hidden" name="customer_id" value="<?= $r['customer_id']; ?>">
                                                        <input type="hidden" name="rider_id" value="<?= $r['rider_id']; ?>">
                                                        <input type="hidden" name="seller_id" value="<?= $r['seller_id']; ?>">

                                                        <div class="mb-3">
                                                            <label for="report_reason" class="form-label">Reason for Report</label>
                                                            <textarea class="form-control" name="report_reason" id="report_reason" rows="4" required></textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-danger">Submit Report</button>
                                                    </form>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div id="reportMessage" class="floating-message"></div>
                                     <div id="rateRiderMessage" class="floating-message"></div>
                                     <div id="rateSellerMessage" class="floating-message"></div>
                                     <div id="rateSellerMessage" class="floating-message" style="display: none;">Return request submitted successfully!</div>
                                     

                                    </div>
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
 
<style>
    .floating-message {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    font-size: 14px;
    z-index: 1050; 
}

/*Star Section*/

.star-rating {
    display: flex;
    justify-content: flex-start;
    font-size: 1.5rem; 
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    color: #ccc;
    cursor: pointer; 
    transition: color 0.3s ease; 
}

.star-rating input[type="radio"]:checked ~ label i {
    color: #ffca08; 
}

.star-rating label:hover i,
.star-rating label:hover ~ label i {
    color: #ffca08;
}

.star-rating {
    flex-direction: row-reverse;
}

.star-rating i {
    font-size: 1.5rem;
}

.star-rating input[type="radio"]:not(:checked) ~ label:hover i {
    color: #ffca08;
}

/*Star Section End*/
</style>


<script>
$(document).ready(function () {
    $("#reportForm").submit(function (e) {
        e.preventDefault(); 

        $.ajax({
            type: "POST",
            url: "submit_report.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#reportSellerButton").hide();

                    $("#reportSellerModal").modal("hide");

                    setTimeout(function () {
                        $("#reportMessage").text(response.message).fadeIn().delay(2000).fadeOut();
                    }, 500);
                } else {
                    alert(response.message);  
                }
            },
            error: function () {
                alert("An error occurred while submitting the report.");
            }
        });
    });
});



$('#reportRiderForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();  

    $.ajax({
        url: 'submit_report_rider.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#reportRiderModal').modal('hide');

                $('#reportMessage').text(response.message).fadeIn();
                setTimeout(function() {
                    $('#reportMessage').fadeOut();
                }, 3000);  
                $('#reportRiderButton').hide();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('An error occurred. Please try again.');
        }
    });
});

$(document).ready(function () {
    $("#rateRiderForm").submit(function (e) {
        e.preventDefault();

        // Validate required fields
        var rating = $("input[name='rider_rating']:checked").val();
        var feedback = $("#feedback").val();

        if (!rating || !feedback) {
            alert("Rating and feedback are required!");
            return;
        }

        $.ajax({
            type: "POST",
            url: "submit_rating.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#rateRiderModal").modal("hide");
                    $("#rateRiderButton").hide();

                    setTimeout(function () {
                        $("#rateRiderMessage").text(response.message).fadeIn().delay(2000).fadeOut();
                    }, 500);
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("An error occurred while submitting the rating.");
            }
        });
    });
});


$(document).ready(function () {
    $("#rateSellerForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "submit_seller_rating.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#rateSellerModal").modal("hide");

                    $("#rateSellerButton").hide();

                    setTimeout(function () {
                        $("#rateSellerMessage").text(response.message).fadeIn().delay(2000).fadeOut();
                    }, 500);
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("An error occurred while submitting the rating.");
            }
        });
    });
});

$(document).ready(function() {
    $('#returnForm').submit(function(e) {
        e.preventDefault(); 

        var formData = new FormData(this); 

        $.ajax({
            url: 'process_return.php', 
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false, 
            dataType: 'JSON',
            success: function(response) {
                if (response.success) {
                    $('#rateSellerMessage').text(response.success).fadeIn().delay(2000).fadeOut();

                    $('#return-items').hide();

                    $('#returnItems').modal('hide');
                } else {
                    alert('There was an error: ' + response.error);
                }
            },
            error: function() {
                alert('An error occurred while processing your request.');
            }
        });
    });
});






</script>