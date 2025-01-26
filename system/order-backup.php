<?php require_once('header.php'); ?>

<?php

require '../vendor/autoload.php';  // or 'path_to_phpmailer/PHPMailerAutoload.php'

// Load PHPMailer
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// $mail = new PHPMailer(true);
$error_message = '';
if(isset($_POST['form1'])) {
    $valid = 1;
    if(empty($_POST['subject_text'])) {
        $valid = 0;
        $error_message .= 'Subject can not be empty\n';
    }
    if(empty($_POST['message_text'])) {
        $valid = 0;
        $error_message .= 'Subject can not be empty\n';
    }
    if($valid == 1) {

        $subject_text = strip_tags($_POST['subject_text']);
        $message_text = strip_tags($_POST['message_text']);

        // Getting Customer Email Address
        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=?");
        $statement->execute(array($_POST['cust_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $cust_email = $row['cust_email'];
        }

        // Getting Admin Email Address
        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $admin_email = $row['contact_email'];
        }

        $order_detail = '';
        $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
        $statement->execute(array($_POST['payment_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
        	
        	if($row['payment_method'] == 'PayPal'):
        		$payment_details = '
Transaction Id: '.$row['txnid'].'<br>
        		';
        	elseif($row['payment_method'] == 'COD'):
				$payment_details = '
Transaction Details: <br>'."";
        	elseif($row['payment_method'] == 'Bank Deposit'):
				$payment_details = '
Transaction Details: <br>'.$row['bank_transaction_info'];
        	endif;

            $order_detail .= '
Customer Name: '.$row['customer_name'].'<br>
Customer Email: '.$row['customer_email'].'<br>
Payment Method: '.$row['payment_method'].'<br>
Payment Date: '.$row['payment_date'].'<br>
Payment Details: <br>'.$payment_details.'<br>
Paid Amount: '.$row['paid_amount'].'<br>
Payment Status: '.$row['payment_status'].'<br>
Shipping Status: '.$row['shipping_status'].'<br>
Payment Id: '.$row['payment_id'].'<br>
            ';
        }

        $i=0;
        $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
        $statement->execute(array($_POST['payment_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $i++;
            $order_detail .= '
<br><b><u>Product Item '.$i.'</u></b><br>
Product Name: '.$row['product_name'].'<br>
Size: '.$row['size'].'<br>
Color: '.$row['color'].'<br>
Quantity: '.$row['quantity'].'<br>
Unit Price: '.$row['unit_price'].'<br>
            ';
        }

        $statement = $pdo->prepare("INSERT INTO tbl_customer_message (subject,message,order_detail,cust_id) VALUES (?,?,?,?)");
        $statement->execute(array($subject_text,$message_text,$order_detail,$_POST['cust_id']));

        // sending email
            $to_customer = $cust_email;
                $message = '
        <html><body>
        <h3>Message: </h3>
        '.$message_text.'
        <h3>Order Details: </h3>
        '.$order_detail.'
        </body></html>
        ';


        // Sending email to admin                  
       //Server settings
            // $mail->isSMTP();
            // $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to use
            // $mail->SMTPAuth = true;
            // $mail->Username = 'laraphilmail@gmail.com';  // Use your Gmail address
            // $mail->Password = 'woyr ronm umwu dfhy';   // Use the app password you generated
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->Port = 587;  // Use the SMTP port 587 for TLS

            // //Recipients
            // $mail->setFrom('laraphilmail@gmail.com', 'Pamilihan');
            // $mail->addAddress($to_customer, $subject_text);  // Add a recipient

            // //Content
            // $mail->isHTML(true);
            // $mail->Subject = $subject_text; 
            // $mail->Body = $message;
            // $mail->send();
            // $success_message = 'Your email to customer is sent successfully.';

    }
}
?>
<?php
if($error_message != '') {
    echo "<script>alert('".$error_message."')</script>";
}
if($success_message != '') {
    echo "<script>alert('".$success_message."')</script>";
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Orders</h1>
	</div>
</section>


<section class="content">

  <div class="row">
    <div class="col-md-12">


      <div class="box box-info">
        
        <div class="box-body table-responsive">
          <?php
          if(isset($_SESSION['user'])){
            $ownRole = $_SESSION['user']['role'];
            if($ownRole == "Admin"){
                ?>
                <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Product Details</th>
                        <th>
                            Payment Information
                        </th>
                        <th>Paid Amount</th>
                        <th>Payment Status</th>
                        <th>Shipping Status</th>
                        <th>Assigned Rider</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=0;
                    $statement = $pdo->prepare("
                    SELECT tbl_payment.*, 
                        tbl_rider.id AS rider_id, 
                        tbl_rider.fname, 
                        tbl_rider.lname, 
                        tbl_rider.rmap_lat, 
                        tbl_rider.rmap_long, 
                        tbl_rider.contacts, 
                        tbl_customer.map_lat AS customer_lat, 
                        tbl_customer.map_long AS customer_long
                    FROM tbl_payment
                    LEFT JOIN tbl_rider ON tbl_payment.rider_id = tbl_rider.id
                    LEFT JOIN tbl_customer ON tbl_payment.customer_email = tbl_customer.cust_email
                    ORDER BY tbl_payment.id DESC
                ");

                        $statement->execute();
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        
                    foreach ($result as $row) {
                        $i++;
                        ?>
                        <tr class="<?php if($row['payment_status']=='Pending'){echo 'bg-r';}else{echo 'bg-g';} ?>">
                            <td><?php echo $i; ?></td>
                            <td>
                                <b>Id:</b> <?php echo $row['customer_id']; ?><br>
                                <b>Name:</b><br> <?php echo $row['customer_name']; ?><br>
                                <b>Email:</b><br> <?php echo $row['customer_email']; ?><br><br>
                                <a href="#" data-toggle="modal" data-target="#model-<?php echo $i; ?>"class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Send Message</a>
                                <div id="model-<?php echo $i; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title" style="font-weight: bold;">Send Message</h4>
                                            </div>
                                            <div class="modal-body" style="font-size: 14px">
                                                <form action="" method="post">
                                                    <input type="hidden" name="cust_id" value="<?php echo $row['customer_id']; ?>">
                                                    <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>Subject</td>
                                                            <td>
                                                                <input type="text" name="subject_text" class="form-control" style="width: 100%;">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Message</td>
                                                            <td>
                                                                <textarea name="message_text" class="form-control" cols="30" rows="10" style="width:100%;height: 200px;"></textarea>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><input type="submit" value="Send Message" name="form1"></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                            <?php
                            $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                            $statement1->execute(array($row['payment_id']));
                            $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result1 as $row1) {
                                    echo '<b>Product:</b> '.$row1['product_name'];
                                    echo '<br>(<b>Size:</b> '.$row1['size'];
                                    echo ', <b>Color:</b> '.$row1['color'].')';
                                    echo '<br>(<b>Quantity:</b> '.$row1['quantity'];
                                    echo ', <b>Unit Price:</b> ₱ '.$row1['unit_price'].')';
                                    echo '<br><br>';
                            }
                            ?>
                            </td>
                            <td>
                                <?php if($row['payment_method'] == 'PayPal'): ?>
                                    <b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                    <b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
                                    <b>Date:</b> <?php echo $row['payment_date']; ?><br>
                                    <b>Transaction Id:</b> <?php echo $row['txnid']; ?><br>
                                <?php elseif($row['payment_method'] == 'COD'): ?>
                                    <b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                <?php elseif($row['payment_method'] == 'Bank Deposit'): ?>
                                    <b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                    <b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
                                    <b>Date:</b> <?php echo $row['payment_date']; ?><br>
                                    <b>Transaction Information:</b> <br><?php echo $row['bank_transaction_info']; ?><br>
                                <?php endif; ?>
                            </td>
                            <td>₱<?php echo $row['paid_amount']; ?></td>
                            <td>
                                <?php echo $row['payment_status']; ?>
                                <br><br>
                                <?php
                                    if($row['payment_status']=='Pending'){
                                        ?>
                                        <a href="order-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-success btn-xs" style="width:100%;margin-bottom:4px;">Mark Complete</a>
                                        <?php
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo $row['shipping_status']; ?>
                                <br><br>
                                <?php
                                if($row['payment_status']=='Completed') {
                                    if($row['shipping_status']=='Pending'){
                                        ?>
                                        <a href="shipping-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Mark Complete</a>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                            <td>
                            <?php echo $row['fname']." ".$row['lname']; ?> 
                            </td>
                            <td>
                                    <a href="#" class="btn btn-danger btn-xs" data-href="order-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete" style="width:100%;">Delete</a>
                                    
                                    <!-- Update Button, show only if fname or lname is null -->
                                    <?php if (is_null($row['fname']) || is_null($row['lname'])): ?>
                                        <a href="#" class="btn btn-primary btn-xs" 
                                        data-order-id="<?php echo $row['id']; ?>" 
                                        data-toggle="modal" 
                                        data-target="#confirm-update" 
                                        style="width:100%;">Update</a>
                                    <?php endif; ?>

                                    <?php  
                                            
                                                $latitude = $row['customer_lat']; // Customer latitude
                                                $longitude = $row['customer_long']; // Customer longitude
                                                $rmap_lat = $row['rmap_lat'];
                                                $rmap_long = $row['rmap_long'];

                                            // Display the address with the latitude and longitude
                                            echo "<a href='#' class='address-link btn btn-default' data-lat='{$latitude}' data-lng='{$longitude}' data-address='' data-rider-lat='{$rmap_lat}' data-rider-lng='{$rmap_long}'>
                                            <span class='glyphicon glyphicon-map-marker'></span>Track Order 
                                            </a>";
                                    ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
                <?php
            }else if($ownRole == "Seller"){
            ?>
                            <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Product Details</th>
                        <th>
                            Payment Information
                        </th>
                        <th>Paid Amount</th>
                        <th>Payment Status</th>
                        <th>Shipping Status</th>
                        <th>Assigned Rider</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt1 = $pdo->prepare("SELECT o.product_id, p.p_id FROM tbl_product p JOIN tbl_order o ON p.p_id=o.product_id WHERE p.u_id=?");
                    $stmt1->execute(array($_SESSION['user']['id']));
                    if($stmt1->rowCount() > 0){
                        ?>
                        <?php
                        $i=0;
                        $statement = $pdo->prepare("
                        SELECT tbl_payment.*,
                            tbl_rider.id AS rider_id, 
                            tbl_rider.fname, 
                            tbl_rider.lname, 
                            tbl_rider.rmap_lat, 
                            tbl_rider.rmap_long, 
                            tbl_rider.contacts, 
                            tbl_customer.map_lat AS customer_lat, 
                            tbl_customer.map_long AS customer_long
                        FROM tbl_payment
                        LEFT JOIN tbl_rider ON tbl_payment.rider_id = tbl_rider.id
                        LEFT JOIN tbl_customer ON tbl_payment.customer_email = tbl_customer.cust_email
                        ORDER BY tbl_payment.id DESC
                        ");

                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            
                        foreach ($result as $row) {
                            $i++;
                            ?>
                            <tr class="<?php if($row['payment_status']=='Pending'){echo 'bg-r';}else{echo 'bg-g';} ?>">
                                <td><?php echo $i; ?></td>
                                <td>
                                    <b>Id:</b> <?php echo $row['customer_id']; ?><br>
                                    <b>Name:</b><br> <?php echo $row['customer_name']; ?><br>
                                    <b>Email:</b><br> <?php echo $row['customer_email']; ?><br><br>
                                    <a href="#" data-toggle="modal" data-target="#model-<?php echo $i; ?>"class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Send Message</a>
                                    <div id="model-<?php echo $i; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title" style="font-weight: bold;">Send Message</h4>
                                                </div>
                                                <div class="modal-body" style="font-size: 14px">
                                                    <form action="" method="post">
                                                        <input type="hidden" name="cust_id" value="<?php echo $row['customer_id']; ?>">
                                                        <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td>Subject</td>
                                                                <td>
                                                                    <input type="text" name="subject_text" class="form-control" style="width: 100%;">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Message</td>
                                                                <td>
                                                                    <textarea name="message_text" class="form-control" cols="30" rows="10" style="width:100%;height: 200px;"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><input type="submit" value="Send Message" name="form1"></td>
                                                            </tr>
                                                        </table>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                <?php
                                $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                                $statement1->execute(array($row['payment_id']));
                                $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result1 as $row1) {
                                        echo '<b>Product:</b> '.$row1['product_name'];
                                        echo '<br>(<b>Size:</b> '.$row1['size'];
                                        echo ', <b>Color:</b> '.$row1['color'].')';
                                        echo '<br>(<b>Quantity:</b> '.$row1['quantity'];
                                        echo ', <b>Unit Price:</b> ₱ '.$row1['unit_price'].')';
                                        echo '<br><br>';
                                }
                                ?>
                                </td>
                                <td>
                                    <?php if($row['payment_method'] == 'PayPal'): ?>
                                        <b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                        <b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
                                        <b>Date:</b> <?php echo $row['payment_date']; ?><br>
                                        <b>Transaction Id:</b> <?php echo $row['txnid']; ?><br>
                                    <?php elseif($row['payment_method'] == 'COD'): ?>
                                        <b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                    <?php elseif($row['payment_method'] == 'Bank Deposit'): ?>
                                        <b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                        <b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
                                        <b>Date:</b> <?php echo $row['payment_date']; ?><br>
                                        <b>Transaction Information:</b> <br><?php echo $row['bank_transaction_info']; ?><br>
                                    <?php endif; ?>
                                </td>
                                <td>₱<?php echo $row['paid_amount']; ?></td>
                                <td>
                                    <?php echo $row['payment_status']; ?>
                                    <br><br>
                                    <?php
                                        if($row['payment_status']=='Pending'){
                                            ?>
                                            <a href="order-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-success btn-xs" style="width:100%;margin-bottom:4px;">Mark Complete</a>
                                            <?php
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php echo $row['shipping_status']; ?>
                                    <br><br>
                                    <?php
                                    if($row['payment_status']=='Completed') {
                                        if($row['shipping_status']=='Pending'){
                                            ?>
                                            <a href="shipping-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Mark Complete</a>
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                <?php echo $row['fname']." ".$row['lname']; ?> 
                                </td>
                                <td>
                                        <a href="#" class="btn btn-danger btn-xs" data-href="order-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete" style="width:100%;">Delete</a>
                                        
                                        <!-- Update Button, show only if fname or lname is null -->
                                        <?php if (is_null($row['fname']) || is_null($row['lname'])): ?>
                                            <a href="#" class="btn btn-primary btn-xs" 
                                            data-order-id="<?php echo $row['id']; ?>" 
                                            data-toggle="modal" 
                                            data-target="#confirm-update" 
                                            style="width:100%;">Update</a>
                                        <?php endif; ?>

                                        <?php  
                                                
                                                    $latitude = $row['customer_lat']; // Customer latitude
                                                    $longitude = $row['customer_long']; // Customer longitude
                                                    $rmap_lat = $row['rmap_lat'];
                                                    $rmap_long = $row['rmap_long'];

                                                // Display the address with the latitude and longitude
                                                echo "<a href='#' class='address-link btn btn-default' data-lat='{$latitude}' data-lng='{$longitude}' data-address='' data-rider-lat='{$rmap_lat}' data-rider-lng='{$rmap_long}'>
                                                <span class='glyphicon glyphicon-map-marker'></span>Track Order 
                                                </a>";
                                        ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            }
          }
          ?>
        </div>
      </div>
  

</section>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: none;">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="mapModalLabel">Location Map</h4>
            </div>
            <div class="modal-body" style="height: calc(100% - 60px); padding: 0;">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Update with Assigned Rider -->
<div class="modal fade" id="confirm-update" tabindex="-1" role="dialog" aria-labelledby="confirmUpdateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmUpdateLabel">Update Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update-order-form" method="POST" action="update-order.php">
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <input type="hidden" name="rider_email" id="rider_email" value=""> <!-- Hidden field for rider email -->

                    <!-- Dropdown for assigning a rider -->
                    <div class="form-group">
                        <label for="assigned_rider">Available Rider</label>
                        <select name="assigned_rider" id="assigned_rider" class="form-control">
                            <option value="">Select Rider</option>
                            <?php
                                // Fetch only the verified riders from the database
                                $query = "SELECT tbl_rider.id, CONCAT(tbl_rider.fname, ' ', tbl_rider.lname) AS rider_name, tbl_rider.r_d_status, tbl_rider.email
                                          FROM tbl_rider 
                                          INNER JOIN tbl_user ON tbl_rider.email = tbl_user.email
                                          WHERE tbl_user.status = 'verified' AND tbl_rider.r_d_status = '1'";

                                $stmt = $pdo->prepare($query);
                                $stmt->execute();

                                // Populate the dropdown with the verified riders
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['id'] . "' data-email='" . $row['email'] . "'>" . $row['rider_name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Add more fields as needed for updating other details -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<!-- Leaflet Polyline Encoded JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.polylineencoded/1.0.0/leaflet.polylineencoded.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let map = null; // Global map variable to store the map instance

    // Handle the click event for the address link
    document.addEventListener('click', function (event) {
        if (event.target.closest('.address-link')) {
            event.preventDefault();
            const link = event.target.closest('.address-link');
            const lat = parseFloat(link.getAttribute('data-lat'));
            const lng = parseFloat(link.getAttribute('data-lng'));
            const address = link.getAttribute('data-address');
            const riderLat = parseFloat(link.getAttribute('data-rider-lat'));
            const riderLng = parseFloat(link.getAttribute('data-rider-lng'));

            // Open the modal
            $('#mapModal').modal('show');

            // Delay map initialization until the modal is fully shown
            $('#mapModal').on('shown.bs.modal', function () {
                updateMap(lat, lng, address, riderLat, riderLng);
            });
        }
    });

    // Function to update the map
    function updateMap(lat, lng, address, riderLat, riderLng) {
        const mapContainer = document.getElementById('map');

        // Ensure the map container exists before initializing the map
        if (!mapContainer) {
            console.error("Map container not found.");
            return;
        }

        // If a map already exists, remove it before creating a new one
        if (map !== null) {
            map.remove(); // Remove the existing map instance
        }

        // Initialize the map
        map = L.map('map', { attributionControl: false }).setView([lat, lng], 15);

        // Add the tile layer
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap',
        }).addTo(map);

        // Create a custom marker for the customer (red marker)
        const customerIcon = L.divIcon({
            className: 'customer-icon',
            html: '<div style="background-color: red; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
            iconSize: [30, 30], // Size of the marker
        });

        // Add a marker for the customer's location
        L.marker([lat, lng], { icon: customerIcon }).addTo(map).bindPopup('Customer: ' + address).openPopup();

        // Ensure rider coordinates are valid
        if (isNaN(riderLat) || isNaN(riderLng)) {
            console.error("Invalid rider coordinates");
            return;
        }

        // Create a custom marker for the rider (blue marker)
        const riderIcon = L.divIcon({
            className: 'rider-icon',
            html: '<div style="background-color: blue; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
            iconSize: [30, 30], // Size of the marker
        });

        // Add a marker for the rider's location
        L.marker([riderLat, riderLng], { icon: riderIcon }).addTo(map).bindPopup('Rider').openPopup();

        // Make an OSRM API request to get the route between the customer and rider
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${lng},${lat};${riderLng},${riderLat}?overview=full&steps=true`;

        // Fetch the route data from OSRM API
        fetch(osrmUrl)
            .then(response => response.json())
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    // Get the route geometry
                    const route = data.routes[0].geometry;

                    // Decode the polyline using Leaflet's native decode method
                    const decodedRoute = decodePolyline(route);

                    // Create a polyline from the decoded points
                    const routePolyline = L.polyline(decodedRoute, { color: 'blue', weight: 5, opacity: 0.7 });

                    // Draw the route on the map
                    routePolyline.addTo(map);

                    // Zoom the map to fit the route
                    map.fitBounds(routePolyline.getBounds());
                }
            })
            .catch(error => {
                console.error("Error fetching route from OSRM:", error);
            });
    }

    // Helper function to decode the polyline string (native implementation)
    function decodePolyline(encoded) {
        let polyline = [];
        let index = 0;
        let lat = 0;
        let lng = 0;

        while (index < encoded.length) {
            let byte, shift = 0, result = 0;
            do {
                byte = encoded.charCodeAt(index++) - 63;
                result |= (byte & 0x1f) << shift;
                shift += 5;
            } while (byte >= 0x20);

            let dlat = (result & 1) ? ~(result >> 1) : (result >> 1);
            lat += dlat;

            shift = 0;
            result = 0;
            do {
                byte = encoded.charCodeAt(index++) - 63;
                result |= (byte & 0x1f) << shift;
                shift += 5;
            } while (byte >= 0x20);

            let dlng = (result & 1) ? ~(result >> 1) : (result >> 1);
            lng += dlng;

            polyline.push([lat / 1e5, lng / 1e5]);
        }

        return polyline;
    }
</script>

<!-- Other necessary scripts (Bootstrap JS, etc.) -->
<script src="path/to/bootstrap.js"></script>

<!-- Your custom script -->
<script type="text/javascript">
$(document).ready(function() {
    // When the Update button is clicked
    $('a[data-target="#confirm-update"]').on('click', function() {
        var orderId = $(this).data('order-id'); // Get the order_id directly from the button
        $('#order_id').val(orderId); // Set the order_id in the hidden input field in the modal form
    });

    // When a rider is selected, update the rider email hidden field
    $('#assigned_rider').on('change', function() {
        var riderEmail = $('#assigned_rider option:selected').data('email'); // Get the email from the selected rider option
        $('#rider_email').val(riderEmail); // Set the rider email in the hidden input field
    });
});

</script>

<?php require_once('footer.php'); ?>