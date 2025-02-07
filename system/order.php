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
            <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th width="5px">#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th  class="text-center">Assigned Rider</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $p = "";
                        $res = "";
                        if($_SESSION['user']['role'] == "Admin"){
                            $sql = "SELECT DISTINCT o.*, p.* FROM tbl_purchase_order o JOIN tbl_purchase_payment p ON o.order_id=p.order_id JOIN tbl_purchase_item i ON o.order_id=i.order_id ORDER BY o.id DESC";
                            $res = $c->fetchData($pdo, $sql);
                        }
                        if($_SESSION['user']['role'] == "Seller"){
                            $sql = "SELECT DISTINCT o.*, p.* FROM tbl_purchase_order o JOIN tbl_purchase_payment p ON o.order_id=p.order_id JOIN tbl_purchase_item i ON o.order_id=i.order_id WHERE i.seller_id=:selId ORDER BY o.id DESC";
                            $p = [
                                ":selId"    =>  $_SESSION['user']['id']
                            ];
                            $res = $c->fetchData($pdo, $sql, $p);
                        }
                        if($res){
                            $no1 = 1;
                            foreach($res as $row){
                                $orderID = $row['order_id'];
                                ?>
                                <tr>
                                    <td><?= $no1++; ?></td>
                                    <td><?= $row['order_id']; ?></td>
                                    <td>
                                    <?php
                                    $orderID = isset($orderID) ? $orderID : 0;  
                                    if ($orderID) {
                                        $sql1 = "SELECT p.full_name, p.phone_no, p.country, p.address, p.city 
                                                FROM tbl_purchase_payment p 
                                                WHERE p.order_id = :order_id";

                                        $p1 = [":order_id" => $orderID];
                                        $res1 = $c->fetchData($pdo, $sql1, $p1);

                                        foreach ($res1 as $row1) {
                                            ?>
                                            <ul>
                                                <li><b>Name: </b><span><?= htmlspecialchars($row1['full_name']); ?></span></li>
                                                <li><b>Phone: </b><span><?= htmlspecialchars($row1['phone_no']); ?></span></li>
                                                <li><b>Country: </b><span><?= htmlspecialchars($row1['country']); ?></span></li>
                                                <li><b>Address: </b><span><?= htmlspecialchars($row1['address']); ?>, <?= htmlspecialchars($row1['city']); ?></span></li>
                                            </ul>
                                            <?php
                                        }
                                    } else {
                                        echo "No order found.";
                                    }
                                    ?>



                                    </td>
                                        <?php
                                            $order_id = $row['order_id'];
                                            $sql = "SELECT i.product_qty, pr.p_name 
                                                    FROM tbl_purchase_item i
                                                    LEFT JOIN tbl_product pr ON i.product_id = pr.p_id
                                                    WHERE i.order_id = :order_id";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([':order_id' => $order_id]);
                                            $item = $stmt->fetch(PDO::FETCH_ASSOC);

                                            $product_qty = $item ? htmlspecialchars($item['product_qty']): 'N/A';
                                            $p_name = $item ? htmlspecialchars($item['p_name']) : 'N/A';
                                        ?>
                                    <td><?= $p_name; ?>, <?= $product_qty; ?>qty<br><hr></td>

                                    <td><?= $php; ?><?= number_format($row['total_amount']); ?></td>
                                    <td><?php 
                                        if($row['transaction_status'] == "Pending" && $row['status'] == "In Transit" || $row['transaction_status'] == "Pending" && $row['status'] == "Delivered" || $row['transaction_status'] == "Pending" && $row['status'] == "Completed"){
                                            ?>
                                            <button class="btn-block btn btn-sm btn-success completePayment" data-id="<?= $row['order_id'];?>" data-payment="<?= $row['transaction_id']; ?>">Complete</button>
                                            <?php 
                                        }else if($row['transaction_status'] == "Completed"){
                                            ?>
                                            <span class="text text-success">Completed</span>
                                            <?php 
                                        }else{
                                            ?>
                                            -
                                            <?php 
                                        }
                                    ?><?php
                                    $sql = "SELECT p.payment_method, p.gcash_name, p.gcash_image, p.gcash_reference, p.cancel_reason
                                            FROM tbl_purchase_payment p
                                            WHERE p.order_id = :order_id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([':order_id' => $order_id]);
                                    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
                                    
                                    $payment_method = $payment ? htmlspecialchars($payment['payment_method']) : 'N/A';
                                    $gcash_name = $payment ? htmlspecialchars($payment['gcash_name']) : 'N/A';
                                    $gcash_image = $payment ? htmlspecialchars($payment['gcash_image']) : 'N/A';
                                    $gcash_reference = $payment ? htmlspecialchars($payment['gcash_reference']) : 'N/A';
                                    $cancel_reason = htmlspecialchars($payment['cancel_reason'] ?? '');
                                    ?><br>
                                    <?= $payment_method; ?><br>
                                    <?= $gcash_name; ?><br>
                                    <?= $gcash_reference; ?><br>
                                    
                                    
                                    <?php if ($gcash_image != 'N/A' && $payment_method != 'cod') : ?>
                                        <a href="#" data-toggle="modal" data-target="#gcashImageModal">
                                            <img src="../assets/img/gcash/<?= $gcash_image; ?>" alt="GCash Image" class="img-thumbnail" style="max-width: 100px; cursor: pointer;">
                                        </a>
                                    <?php endif; ?>

                                    <div class="modal fade" id="gcashImageModal" tabindex="-1" role="dialog" aria-labelledby="gcashImageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="../assets/img/gcash/<?= $gcash_image; ?>" alt="GCash Image" class="img-fluid" style="width: 100%; height: 100%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>
                                        <?php if($row['status'] == "Pending"){ ?>
                                            Pending
                                        <?php }else if($row['status'] == "Accepted"){ ?>
                                            <span class="text text-default">No one driver yet!</span>
                                        <?php }else if($row['status'] == "Rider"){ ?>
                                            <span class="text text-warning">Buying Items</span>
                                        <?php  }else if($row['status'] == "Buying items"){ ?>
                                            <span class="text text-warning">Delivering Items</span>
                                        <?php }else if($row['status'] == "Delivering Items"){ ?>
                                            <span class="text text-warning">Completed</span>
                                        <?php }else if($row['status'] == "Cancelled"){ ?>
                                            <span class="text text-danger"><?= $row['status']; ?></span>
                                        <?php }else{ ?>
                                            <span class="text text-success"><?= $row['status']; ?></span>
                                        <?php } ?>
                                    </td>
                                    <td width="100px"><?php if($row['rider_id'] == ""){ echo '-';}else{ ?>
                                        <?php
                                            $sql10 = "SELECT r.license_number AS license, r.vehicle_type AS vType, r.vehicle_model AS model, r.vehicle_plate_no AS plate_no, u.* FROM tbl_user u JOIN tbl_rider r ON u.id=r.user_id WHERE u.id=:ridId";
                                            $p10 = [
                                            ":ridId"  =>  $row['rider_id']
                                            ];
                                            $sh = $c->fetchData($pdo, $sql10, $p10);
                                            foreach($sh as $hs){
                                            ?>
                                            <ul>
                                                <li><b>Rider: </b><?= $hs['full_name'];?></li>
                                                <li><b>Vehicle: </b><?= $hs['vType'];?></li>
                                                <li><b>Model: </b><?= $hs['model'];?></li>
                                                <li><b>Plate No.: </b><?= $hs['plate_no'];?></li>
                                            </ul>
                                            <?php
                                            }
                                        ?>
                                        <?php } ?>
                                    </td>
                                    <td><?php if($row['remarks'] == ""){ echo '-';}else{ ?><?= $row['remarks']; ?><br>
                                        Reason: <?= $cancel_reason; ?><?php } ?></td>
                                    <td><?php 
                                        if($row['status'] == "Pending"){
                                            ?>
                                            <button class="btn btn-sm btn-block btn-success acceptOrder" data-order="<?= $row['order_id'];?>" data-seller="<?= $_SESSION['user']['id']; ?>">Accept Order</button>
                                            <?php
                                        }
                                        if($row['status'] != "Pending" && $row['status'] != "Cancelled"){
                                            ?>
                                            -
                                            <!-- <button class="btn btn-sm btn-block btn-default" data-toggle="modal" data-target="#mapModal"><span class='glyphicon glyphicon-map-marker'></span> Track Order</button> -->
                                            <?php
                                        }else{
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }else{
                            // do nothing
                        }
                    ?>
                </tbody>
            </table>
        </div>
      </div>
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
                                // $query = "SELECT tbl_rider.id, CONCAT(tbl_rider.fname, ' ', tbl_rider.lname) AS rider_name, tbl_rider.r_d_status, tbl_rider.email
                                //           FROM tbl_rider 
                                //           INNER JOIN tbl_user ON tbl_rider.email = tbl_user.email
                                //           WHERE tbl_user.status = 'verified' AND tbl_rider.r_d_status = '1'";

                                // $stmt = $pdo->prepare($query);
                                // $stmt->execute();

                                // // Populate the dropdown with the verified riders
                                // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                //     echo "<option value='" . $row['id'] . "' data-email='" . $row['email'] . "'>" . $row['rider_name'] . "</option>";
                                // }
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
<script>
  $(document).ready(function(){
    $(document).on('click', '.acceptOrder', function(e){
      e.preventDefault();
      if(confirm("Are you sure you want to accept this order?")){
        var orderId = $(this).data('order');
        $.ajax({
          url:"order-action.php",
          method:"POST",
          data:{'order_id':orderId, 'acceptOrder':true},
          dataType:"HTML",
          success:function(response){
            alert("Updated Successfully!");
            window.location.reload();
          }
        });
      }
    });
    $(document).on('click', '.riderAccept', function(e){
        e.preventDefault();
        var status = $(this).data('status');
        var message = "";
        if(status == "Accepted"){
            message = "Are you sure the order is in transit?";
        }
        if(status == "In Transit"){
            message = "You delivered the product successfully?";
        }
        if(status == "Delivered"){
            message = "Order is successfully delivered?";
        }
        if(confirm(""+message+"")){
            var orderId = $(this).data('order');
            var urId = $(this).data('id');
            var status = $(this).data('status');
            $.ajax({
            url:"order-action.php",
            method:"POST",
            data:{'order_id':orderId, 'status':status, 'urId':urId, 'riderAccept':true},
            dataType:"HTML",
            success:function(response){
                if(response == "success"){
                    alert("Order has been updated!");
                    window.location.reload();
                }else if(response == "error"){
                    alert("Something is wrong, we'll refresh the page for you.");
                    window.location.reload();
                }else{
                    alert(response);
                }
            }
            });
        }
    });

    $(document).on('click','.completePayment', function(e){
      e.preventDefault();
      var transactId = $(this).data("payment");
      var orderId = $(this).data("id");
      if(confirm("Are you sure you want to update this order?")){
        $.ajax({
            url:"order-action.php",
            method:"POST",
            data:{'order_id':orderId, 'transactId':transactId, 'completePayment':true},
            dataType:"HTML",
            success:function(response){
              if(response == "success"){
                alert("Payment has been updated successfully!");
                window.location.reload();
              }else{
                alert(response);
                window.location.reload();
              }
            }
        });
      }
    });
  });
</script>

<?php require_once('footer.php'); ?>