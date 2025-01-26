<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Customers</h1>
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
								<th width="10">#</th>
								<th width="180">Name</th>
								<th width="150">Email Address</th>
								<th width="180">Address</th>
								<th>Status</th>
								<th width="100">Change Status</th>
								<th width="100">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT * 
														FROM tbl_customer t1
														JOIN tbl_country t2
														ON t1.cust_country = t2.country_id
													");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);						
							foreach ($result as $row) {
								$i++;
								?>
								<tr class="<?php if($row['cust_status']==1) {echo 'bg-g';}else {echo 'bg-r';} ?>">
									<td><?php echo $i; ?></td>
									<td><?php echo $row['cust_name']; ?></td>
									<td><?php echo $row['cust_email']; ?></td>
									<td>
										<?php  $address = $row['cust_address'];
                                            $latitude = $row['map_lat']; // Ensure this column exists in tbl_customer
                                            $longitude = $row['map_long'];  
                                            echo "<a href='#' class='address-link btn btn-default' data-lat='{$latitude}' data-lng='{$longitude}' data-address='{$address}'>
                                            <span class='glyphicon glyphicon-map-marker'></span> {$address}
                                            </a>";
                                    ?>
									</td>
									<td><?php if($row['cust_status']==1) {echo 'Active';} else {echo 'Inactive';} ?></td>
									<td>
										<a href="customer-change-status.php?id=<?php echo $row['cust_id']; ?>" class="btn btn-success btn-xs">Change Status</a>
									</td>
									<td>
										<a href="#" class="btn btn-danger btn-xs" data-href="customer-delete.php?id=<?php echo $row['cust_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>
									</td>
								</tr>
								<?php
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
                <p>Are you sure want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>


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

            // Open the modal
            $('#mapModal').modal('show');

            // Delay map initialization until the modal is fully shown
            $('#mapModal').on('shown.bs.modal', function () {
                updateMap(lat, lng, address);
            });
        }
    });

    // Function to update the map
    function updateMap(lat, lng, address) {
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

        // Add a marker and popup for the given address
        L.marker([lat, lng]).addTo(map).bindPopup(address).openPopup();

        // Invalidate map size to ensure proper rendering
        map.invalidateSize();
    }

    // When the modal is closed, refresh the page
    $('#mapModal').on('hidden.bs.modal', function () {
        location.reload(); // Refresh the page when modal is closed
    });
      // Handle the Close button click
      $('#mapModalCloseBtn').on('click', function() {
        map = null; // Reset the map instance when modal is closed manually
        location.reload(); // Refresh the page
    });
</script>

<?php require_once('footer.php'); ?>