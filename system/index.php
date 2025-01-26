<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Dashboard</h1>
</section>

<?php
$myRole = "";
$myId = "";
if(isset($_SESSION['user'])){
  $myId = $_SESSION['user']['id'];
  $myRole = $_SESSION['user']['role'];
}
$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$total_top_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$total_mid_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$total_end_category = $statement->rowCount();

if($myRole == "Admin"){
  $statement = $pdo->prepare("SELECT * FROM tbl_product");
  $statement->execute();
}else if($myRole == "Seller"){
  $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE u_id=?");
  $statement->execute(array($_SESSION['user']['id']));
}
$total_product = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_status='1'");
$statement->execute();
$total_customers = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_active='1'");
$statement->execute();
$total_subscriber = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost");
$statement->execute();
$available_shipping = $statement->rowCount();

if($myRole == "Admin"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
  $statement->execute(array('Completed'));
}else if($myRole == "Seller"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_id=? AND payment_status=?");
  $statement->execute(array($_SESSION['user']['id'],'Completed'));
}
$total_order_completed = $statement->rowCount();
$total_order_completed = $statement->rowCount();

if($myRole == "Admin"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?");
  $statement->execute(array('Completed'));
}else if($myRole == "Seller"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_id=? AND shipping_status=?");
  $statement->execute(array($_SESSION['user']['id'],'Completed'));
}
$total_order_completed = $statement->rowCount();
$total_shipping_completed = $statement->rowCount();

if($myRole == "Admin"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
  $statement->execute(array('Pending'));
}else if($myRole == "Seller"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_id=? AND payment_status=?");
  $statement->execute(array($_SESSION['user']['id'],'Pending'));
}
$total_order_pending = $statement->rowCount();


if($myRole == "Admin"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=? AND shipping_status=?");
  $statement->execute(array('Completed','Pending'));
}else if($myRole == "Seller"){
  $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_id=? AND payment_status=? AND shipping_status=?");
  $statement->execute(array($_SESSION['user']['id'],'Completed','Pending'));
}
$total_order_complete_shipping_pending = $statement->rowCount();
?>
<?php
if($myRole == "Rider"){
?>
<section class="content">

<div class="row">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-custom">
      <div class="inner">
        
        <?php
        $income = "SELECT o.*, SUM(i.product_price * product_qty) AS actualPayment, p.total_amount AS total_amount, p.transaction_id, p.date_and_time AS dateTimeTrans FROM tbl_purchase_order o LEFT JOIN tbl_purchase_item i ON o.order_id=i.order_id LEFT JOIN tbl_purchase_payment p ON o.order_id=p.order_id WHERE o.rider_id=:rid AND p.transaction_status='Completed' GROUP BY o.order_id ORDER BY id DESC";
        $inc = [
          ":rid"  =>  $_SESSION['user']['id']
        ];
        $incRes = $c->fetchData($pdo, $income, $inc);
        
        $totalIncome = 0;
        if($incRes){
          foreach($incRes as $incRow){
            $totalIncome += $incRow['total_amount'] - $incRow['actualPayment'];
          }
        }
        ?>
        <h3><?= $php; ?><?php echo $totalIncome; ?></h3>
        <?php
        ?>
        <p>Total Income</p>
      </div>
      <div class="icon">
        <i class="ionicons ion-cash"></i>
      </div>
      
    </div>
  </div>
  <div class="col-md-12">


    <div class="box box-info">
      
      <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-hover table-striped">
              <thead>
                  <tr>
                      <th width="5px">#</th>
                      <th>Order ID</th>
                      <th>Customer</th>
                      <th>Order Product</th>
                      <th>Total Amount</th>
                      <th>Payment Status</th>
                      <th>Order Status</th>
                      <th width="50px" class="text-center">Assigned Rider</th>
                      <th>Remarks</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                      $p = "";
                      if($myRole == "Rider"){
                          $sql = "SELECT DISTINCT o.*, p.* FROM tbl_purchase_order o JOIN tbl_purchase_payment p ON o.order_id=p.order_id JOIN tbl_purchase_item i ON o.order_id=i.order_id WHERE o.status='Accepted' OR o.status='Rider' OR o.status='Buying Items' OR o.status='Delivering Items' OR o.status='Completed' ORDER BY o.id DESC";
                          $res = $c->fetchData($pdo, $sql);
                      }
                      if($_SESSION['user']['role'] == "Seller"){
                          $sql = "SELECT o.*, p.* FROM tbl_purchase_order o JOIN tbl_purchase_payment p ON o.order_id=p.order_id JOIN tbl_purchase_item i ON o.order_id=i.order_id WHERE i.seller_id=:selId";
                          $p = [
                              ":selId"    =>  $_SESSION['user']['id']
                          ];
                          $res = $c->fetchData($pdo, $sql, $p);
                      }
                      if($res){
                          $no1 = 1;
                          foreach($res as $row){
                              if($row['rider_id'] == "" || $row['rider_id'] == $myId):
                              ?>
                              <tr>
                                  <td><?= $no1++; ?></td>
                                  <td><?= $row['order_id']; ?></td>
                                  <td>
                                      <?php
                                      $sql1 = "SELECT s.* FROM tbl_user u JOIN tbl_shipping_address s ON u.id=s.user_id  WHERE u.id=:customer_id";
                                      $p1 = [
                                          ":customer_id"      =>  $row['customer_id']
                                      ];
                                      $res1 = $c->fetchData($pdo, $sql1, $p1);
                                      foreach($res1 as $row1){
                                          ?>
                                          <ul>
                                              <li><b>Name: </b><span><?= $row1['full_name'];?></span></li>
                                              <li><b>Phone: </b><span><?= $row1['phone'];?></span></li>
                                              <li><b>Address: </b><span><?= $row1['address']; ?>, <?= $row1['city'];?></span></li>
                                          </ul>
                                          <?php
                                      }
                                      ?>
                                  </td>
                                  <td>

                                  <!-- View Product -->
                                  <?php
                                  if($row['status'] != "Completed"){
                                    ?>
                                    <button class="btn-block btn btn-sm btn-success viewProduct" data-id="<?= $row['order_id']; ?>" data-rider="<?= $row['rider_id']; ?>">View Order</button>
                                    <?php
                                  }else{
                                    echo '<span class="text-success">Completed</span>';
                                  }
                                  ?>
                                  </td>
                                  <td><?= $php; ?><?= number_format($row['total_amount']); ?></td>
                                  <td><?php 
                                      if($row['transaction_status'] == "Pending" && $row['status'] == "In Transit" || $row['transaction_status'] == "Pending" && $row['status'] == "Delivered" || $row['transaction_status'] == "Pending" && $row['status'] == "Completed"){
                                        ?>
                                        <button class="btn-block btn btn-sm btn-success completePayment" data-id="<?= $row['order_id']; ?>" data-payment="<?= $row['transaction_id']; ?>">Complete</button>
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
                                  ?></td>
                                  <td>
                                    <?php if($row['status'] == "Pending"){ ?>
                                        Pending
                                    <?php }else if($row['status'] == "Accepted"){ ?>
                                        <button class="btn-block btn btn-sm btn-primary riderAccept" data-order="<?= $row['order_id']; ?>" data-id="<?= $_SESSION['user']['id'];?>" data-status="<?= $row['status'];?>">Accept?</button>
                                    <?php  }else if($row['status'] == "Rider"){ ?>
                                      <button class="btn-block btn btn-sm btn-warning riderAccept" data-order="<?= $row['order_id']; ?>" data-id="<?= $_SESSION['user']['id'];?>" data-status="<?= $row['status'];?>">Buying Items?</button>
                                    <?php  }else if($row['status'] == "Buying Items"){ ?>
                                        <button class="btn-block btn btn-sm btn-warning riderAccept" data-order="<?= $row['order_id']; ?>" data-id="<?= $_SESSION['user']['id'];?>" data-status="<?= $row['status'];?>">Deliver?</button>
                                    <?php }else if($row['status'] == "Delivering Items"){ ?>
                                        <button class="btn-block btn btn-sm btn-success riderAccept" data-order="<?= $row['order_id']; ?>" data-id="<?= $_SESSION['user']['id'];?>" data-status="<?= $row['status'];?>">Done?</button>
                                    <?php }else if($row['status'] == "Cancelled"){ ?>
                                        <span class="text text-danger"><?= $row['status']; ?></span>
                                    <?php }else{ ?>
                                        <span class="text text-success"><?= $row['status']; ?></span>
                                    <?php } ?>
                                  </td>
                                  <td width="200px"><?php if($row['rider_id'] == ""){ echo '-';}else{ ?>
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
                                  <td><?php if($row['remarks'] == ""){ echo '-';}else{ ?><?= $row['remarks']; ?><?php } ?></td>
                                  <td><?php 
                                      if($row['status'] == "Pending"){
                                          if($myRole != "Rider"){
                                            ?>
                                            <button class="btn btn-sm btn-block btn-success acceptOrder" data-order="<?= $row['order_id'];?>" data-seller="<?= $_SESSION['user']['id']; ?>">Accept Order</button>
                                            <?php
                                          }
                                      }
                                      if($row['status'] != "Pending" && $row['status'] != "Cancelled"){
                                          ?>
                                          <button class="btn btn-sm btn-block btn-success showMessage" data-toggle="modal" data-target="#showMessage" data-user="<?= $row1['full_name']; ?>" data-id="<?= $row1['id']; ?>" data-rider="<?= $_SESSION['user']['id']; ?>" data-name="<?= $_SESSION['user']['full_name']; ?>" >Message</button>
                                          <?php
                                          $sql101 = "SELECT * FROM tbl_shipping_address WHERE user_id=:id";
                                          $p101 = [
                                            ":id"   =>  $row1['user_id']
                                          ];
                                          $r101 = $c->fetchData($pdo, $sql101, $p101);
                                          if($r101){
                                            foreach($r101 as $row101){
                                              ?>
                                              <a class="btn btn-sm btn-block btn-default address-link" data-toggle="modal" data-target="#mapModal" data-lat="<?= $row101['custLat']; ?>" data-lng="<?= $row101['custLng']; ?>" data-address="" data-rider-lat="14.408133" data-rider-lng="121.041466"><span class='glyphicon glyphicon-map-marker'></span> Track Order</a>
                                              <?php
                                            }
                                          }
                                          ?>
                                          <?php
                                      }else{
                                          echo '-';
                                      }
                                      ?>
                                  </td>
                              </tr>
                              <?php
                              endif;
                          }
                      }
                  ?>
              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
</section>
<?php
}
?>
<section class="content">
<div class="row">
        <?php
        if($myRole != "Rider"){
        ?>
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-custom">
                <div class="inner">
                  <h3><?php echo $total_product; ?></h3>

                  <p>Products</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-android-cart"></i>
                </div>
                
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-custom">
                <div class="inner">
                  <h3><?php echo $total_order_pending; ?></h3>

                  <p>Pending Orders</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-clipboard"></i>
                </div>
                
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-custom">
                <div class="inner">
                  <h3><?php echo $total_order_completed; ?></h3>

                  <p>Completed Orders</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-android-checkbox-outline"></i>
                </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-custom">
                <div class="inner">
                  <h3><?php echo $total_shipping_completed; ?></h3>

                  <p>Completed Shipping</p>
                </div>
                <div class="icon">
                  <i class="ionicons ion-checkmark-circled"></i>
                </div>
              </div>
            </div>
			<!-- ./col -->
			
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-custom">
				  <div class="inner">
					<h3><?php echo $total_order_complete_shipping_pending; ?></h3>
  
					<p>Pending Shippings</p>
				  </div>
				  <div class="icon">
					<i class="ionicons ion-load-a"></i>
				  </div>
				</div>
			  </div>
				<!-- small box -->
        <?php
        }
        ?>
				<?php
          if($myRole == "Admin"){
            ?>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-custom">
                <div class="inner">
                <h3><?php echo $total_customers; ?></h3>
                <p>Active Customers</p>
                </div>
                <div class="icon">
                <i class="ionicons ion-person-stalker"></i>
                </div>
                
              </div>
            </div>
            <?php
          }
        ?>


			  <?php
        if($myRole != "Rider"){
        ?>
        <div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-custom">
				  <div class="inner">
					<h3><?php echo $available_shipping; ?></h3>
  
					<p>Available Shippings</p>
				  </div>
				  <div class="icon">
					<i class="ionicons ion-location"></i>
				  </div>
				  
				</div>
			  </div>
        <?php
        }
        ?>

			  
				<!-- small box -->
        <?php
        if($myRole == "Admin"){
          ?>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-custom">
            <div class="inner">
            <h3><?php echo $total_top_category; ?></h3>
    
            <p>Categories</p>
            </div>
            <div class="icon">
            <i class="ionicons ion-arrow-up-b"></i>
            </div>
            
          </div>
          <?php
        }
        ?>
				
			  </div>

			  <?php
        if($_SESSION['user']['role'] != "Rider"){
        ?>
        <!-- Revenue and Overview Chart -->
			  <div class="col-md-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">Overview Chart</h3>
						</div>
						<div class="box-body">
							<canvas id="overviewChart"></canvas>
						</div>
					</div>
				</div>
        <?php 
        }
        ?>

		  </div>
</section>
<?php
  if($myRole == "Rider"){
  ?>
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
<div class="modal fade" id="viewOrderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Customer Orders</h4>
            </div>
            <div class="modal-body">
              <div class="messages">

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
    </div>
</div>
<div class="modal fade" id="showMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="chat-container">
                  <div class="chat-header" id="chatHeader">
                      Chat with <span id="chatUserName">...</span>
                      <button class="close-button" data-dismiss="modal" aria-hidden="true" onclick="closeChat()">×</button>
                  </div>
                  <div class="messages" id="messages">
                      <span style="text-align: center; margin-top: 15%;">Loading...</span>
                  </div>
                  <form method="POST" id="sendaMessage">
                    <div id="input-container" style="display: none;">
                        <!-- <p class="text-center">Chat session ended.</p> -->
                        <div class="input-container">
                            <input type="text" id="messageInput" placeholder="Type a message...">
                            <button type="submit">Send</button>
                        </div>
                    </div>
                  </form>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
  <?php
  }
  ?>
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<!-- Leaflet Polyline Encoded JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.polylineencoded/1.0.0/leaflet.polylineencoded.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let map = null;
    let riderMarker = null;

    document.addEventListener('click', function (event) {
        if (event.target.closest('.address-link')) {
            event.preventDefault();
            const link = event.target.closest('.address-link');
            const lat = parseFloat(link.getAttribute('data-lat'));
            const lng = parseFloat(link.getAttribute('data-lng'));
            const address = link.getAttribute('data-address');
            let riderLat = parseFloat(link.getAttribute('data-rider-lat'));
            let riderLng = parseFloat(link.getAttribute('data-rider-lng'));

            $('#mapModal').modal('show');

            $('#mapModal').on('shown.bs.modal', function () {
                requestUserLocation(lat, lng, address, riderLat, riderLng);
            });
        }
    });

    let isMapInitialized = false; // Flag to check if the map has been initialized

    function requestUserLocation(destLat, destLng, address, riderLat, riderLng) {
        // Use geolocation to get the rider's current location and start tracking it
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                (position) => {
                    // Update riderLat and riderLng with current position
                    riderLat = position.coords.latitude;
                    riderLng = position.coords.longitude;

                    // Initialize the map only once, on the first location update
                    if (!isMapInitialized) {
                        updateMap(destLat, destLng, address, riderLat, riderLng);
                        isMapInitialized = true; // Set flag to prevent further map updates
                    }

                    // Optionally update the rider's location without reinitializing the map
                    updateRiderLocation(riderLat, riderLng);

                    
                    let routePolyline = '';
                    const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${riderLng},${riderLat};${destLng},${destLat}?overview=full&steps=true`;

                    fetch(osrmUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data.routes && data.routes.length > 0) {
                                const route = data.routes[0].geometry;
                                const decodedRoute = decodePolyline(route);

                                // Check if the routePolyline already exists
                                if (routePolyline) {
                                    // If it exists, update the polyline by removing the old one
                                    routePolyline.setLatLngs(decodedRoute);
                                } else {
                                    // If it doesn't exist, create a new polyline and add it to the map
                                    routePolyline = L.polyline(decodedRoute, { color: 'blue', weight: 5, opacity: 0.7 }).addTo(map);
                                }

                                // Use fitBounds to adjust the map view to the new route's bounds (without forcing setView)
                                map.fitBounds(routePolyline.getBounds());
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching route from OSRM:', error);
                        });
                },
                (error) => {
                    console.error('Error getting rider location:', error.message);
                },
                { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
            );
        } else {
            console.error('Geolocation is not supported by this browser.');
        }
    }

    function updateMap(destLat, destLng, address, riderLat, riderLng) {
        const mapContainer = document.getElementById('map');

        if (!mapContainer) {
            console.error('Map container not found.');
            return;
        }

        if (map !== null) {
            map.remove();
        }

        // Initialize the map centered on the destination location
        map = L.map('map', { attributionControl: false }).setView([destLat, destLng], 15);

        // Add OpenStreetMap tiles to the map
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap',
        }).addTo(map);

        // Customer Icon
        const customerIcon = L.divIcon({
            className: 'customer-icon',
            html: '<div style="background-color: red; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
            iconSize: [30, 30],
        });

        // Add the customer's location marker
        L.marker([destLat, destLng], { icon: customerIcon }).addTo(map).bindPopup('Customer: ' + address).openPopup();

        // If rider location is available, add rider marker
        if (riderLat !== null && riderLng !== null) {
            const riderIcon = L.divIcon({
                className: 'rider-icon',
                html: '<div style="background-color: blue; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
                iconSize: [30, 30],
            });

            riderMarker = L.marker([riderLat, riderLng], { icon: riderIcon }).addTo(map).bindPopup('Rider Location').openPopup();

        }
    }

    function updateRiderLocation(riderLat, riderLng) {
        // If riderMarker exists, update its position
        if (riderMarker) {
            riderMarker.setLatLng([riderLat, riderLng]);
        } else {
            // Otherwise, create a new marker for the rider
            const riderIcon = L.divIcon({
                className: 'rider-icon',
                html: '<div style="background-color: blue; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
                iconSize: [30, 30],
            });

            riderMarker = L.marker([riderLat, riderLng], { icon: riderIcon }).addTo(map).bindPopup('Rider Location').openPopup();
        }

        // Optionally, adjust map view to the rider's location
        map.setView([riderLat, riderLng]);
    }

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
});

</script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        let map = null;

        document.addEventListener('click', function (event) {
            if (event.target.closest('.address-link')) {
                event.preventDefault();
                const link = event.target.closest('.address-link');
                const lat = parseFloat(link.getAttribute('data-lat'));
                const lng = parseFloat(link.getAttribute('data-lng'));
                const address = link.getAttribute('data-address');
                const riderLat = parseFloat(link.getAttribute('data-rider-lat'));
                const riderLng = parseFloat(link.getAttribute('data-rider-lng'));

                $('#mapModal').modal('show');

                $('#mapModal').on('shown.bs.modal', function () {
                    updateMap(lat, lng, address, riderLat, riderLng);
                });
            }
        });

        function updateMap(lat, lng, address, riderLat, riderLng) {
            const mapContainer = document.getElementById('map');

            if (!mapContainer) {
                console.error("Map container not found.");
                return;
            }

            if (map !== null) {
                map.remove();
            }

            map = L.map('map', { attributionControl: false }).setView([lat, lng], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap',
            }).addTo(map);

            const customerIcon = L.divIcon({
                className: 'customer-icon',
                html: '<div style="background-color: red; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
                iconSize: [30, 30],
            });

            L.marker([lat, lng], { icon: customerIcon }).addTo(map).bindPopup('Customer: ' + address).openPopup();

            if (isNaN(riderLat) || isNaN(riderLng)) {
                console.error("Invalid rider coordinates");
                return;
            }

            const riderIcon = L.divIcon({
                className: 'rider-icon',
                html: '<div style="background-color: blue; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white;"></div>',
                iconSize: [30, 30],
            });

            L.marker([riderLat, riderLng], { icon: riderIcon }).addTo(map).bindPopup('Rider').openPopup();

            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${lng},${lat};${riderLng},${riderLat}?overview=full&steps=true`;

            fetch(osrmUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.routes && data.routes.length > 0) {
                        const route = data.routes[0].geometry;
                        const decodedRoute = decodePolyline(route);
                        const routePolyline = L.polyline(decodedRoute, { color: 'blue', weight: 5, opacity: 0.7 });
                        routePolyline.addTo(map);
                        map.fitBounds(routePolyline.getBounds());
                    }
                })
                .catch(error => {
                    console.error("Error fetching route from OSRM:", error);
                });
        }

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
    });
</script> -->
<script>
    // Pass PHP metrics to JavaScript
    const metrics = {
        products: <?php echo $total_product; ?>,
        pendingOrders: <?php echo $total_order_pending; ?>,
        completedOrders: <?php echo $total_order_completed; ?>,
        completedShipping: <?php echo $total_shipping_completed; ?>,
        pendingShipping: <?php echo $total_order_complete_shipping_pending; ?>,
        customers: <?php echo $total_customers; ?>,
        shippingAvailable: <?php echo $available_shipping; ?>,
    };

    // Chart.js Configuration
    const ctx = document.getElementById('overviewChart').getContext('2d');
    const overviewChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Products', 
                'Pending Orders', 
                'Completed Orders', 
                'Completed Shipping', 
                'Pending Shipping', 
                'Active Customers', 
                'Available Shipping',
            ],
            datasets: [{
                label: 'Overview Metrics',
                data: [
                    metrics.products,
                    metrics.pendingOrders,
                    metrics.completedOrders,
                    metrics.completedShipping,
                    metrics.pendingShipping,
                    metrics.customers,
                    metrics.shippingAvailable,
                    metrics.topCategories
                ],
                backgroundColor: '#CCEEBC', // Bar color
                borderColor: '#88CC88', // Border color for bars
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Counts' }
                },
                x: {
                    title: { display: true, text: 'Metrics' }
                }
            }
        }
    });
</script>
<?php require_once('footer.php'); ?>
<script>

  $(document).ready(function(){
    $(document).on('click', '.viewProduct', function(e){
      e.preventDefault();
      $('#viewOrderModal').modal('show');
      var orderId = $(this).data('id');
      var riderId = $(this).data('rider');
      $.ajax({
        url:"viewOrder.php",
        method:"POST",
        data:{'orderId':orderId, 'riderId':riderId, 'viewProduct':true},
        dataType:"HTML",
        success:function(data){
          $('#viewOrderModal .modal-body').html(data);
        }
      });
    });
    $(document).on('click', '.riderAccept', function(e){
        e.preventDefault();
        var status = $(this).data('status');
        var message = "";
        if(status == "Accepted"){
            message = "You want to accept this order?";
        }
        if(status == "Rider"){
            message = "You buying items now?";
        }
        if(status == "Buying Items"){
            message = "You delivering the items now?";
        }
        if(status == "Delivering Items"){
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

    $(document).on('change','.alreadyBuy', function(e){
        e.preventDefault();
        var checkStatus = $(this).is(':checked');
        var oId = $(this).data('id');
        var productStatus = "Accepted";
        if(checkStatus == true){
          productStatus = "Completed";
        }
        $.ajax({
          url:"viewOrder.php",
          method:"POST",
          data:{'id':oId,'productStatus':productStatus, 'checkStatus':true},
          dataType:"HTML",
          success:function(data){
            // do nothing
          }
        });
      });
  });
</script>