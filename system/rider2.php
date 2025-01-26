<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Riders</h1>
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
								<th width="180">Contact#</th>
								<th width="180">License #</th>
								<th>Status</th>
								<th width="100">Change Status</th>
								<th width="100">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT *,t1.id as rider_id
														FROM tbl_rider t1
														JOIN tbl_user t2
														ON t1.email = t2.email
													");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);						
							foreach ($result as $row) {
								$i++;
								?>
								<tr class="<?php if($row['r_status']==1) {echo 'bg-g';}else {echo 'bg-r';} ?>">
									<td><?php echo $i; ?></td>
									<td><?php echo $row['fname']." ".$row['lname']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td>
                                    <?php  
                                            
                                            $address = $row['address'];
                                            $latitude = $row['rmap_lat']; // Ensure this column exists in tbl_customer
                                            $longitude = $row['rmap_long'];  
                                            echo "<a href='#' class='address-link btn btn-default' data-lat='{$latitude}' data-lng='{$longitude}' data-address='{$address}'>
                                            <span class='glyphicon glyphicon-map-marker'></span> {$address}
                                            </a>";
                                    ?>
									</td>
									<td>
										<?php echo $row['contacts']; ?><br>
									</td>
									<td>
									<!-- Make the license number clickable to open modal -->
									<a href="#" data-toggle="modal" data-target="#licenseModal" 
									data-license="<?php echo $row['license_number']; ?>"
									data-driver_license_image="<?php echo $row['driver_license']; ?>" 
									data-nbi_clearance="<?php echo $row['nbi_clearance']; ?>"
									data-vehicle_type="<?php echo $row['vehicle_type']; ?>">
										<?php echo $row['license_number']; ?>
									</a>
								</td>
									<td>
											<?php 
												if($row['r_status'] == 1) {
													echo 'Active'; 
												} else {
													echo 'Inactive'; 
												}
												echo " - " . $row['status']; 
											?>
										</td>
										<td>
									<!-- Button to trigger modal -->
									<a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#statusModal" data-rider_id="<?php echo $row['email']; ?>" 
									data-current_rider_status="<?php echo $row['r_status']; ?>"
									data-current_account_status="<?php echo $row['status']; ?>">
									Change Status
									</a>
								</td>
									<td>
										<a href="#" class="btn btn-danger btn-xs" data-href="rider-delete.php?id=<?php echo $row['rider_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>
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

<!-- Modal HTML structure -->
<div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="licenseModalLabel">Driver Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"id="mapModalCloseBtn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="licenseText"></p>
                <p><strong>Vehicle Type:</strong> <span id="vehicleType"></span></p>
                <p><strong>Driver's License Image:</strong></p>
                <!-- Button to view the image in a new tab -->
                <button id="viewLicenseImage" class="btn btn-primary">View Driver License</button>

                <p><strong>NBI Clearance:</strong></p>
                <!-- Button to view NBI clearance image in a new tab -->
                <button id="viewNbiClearance" class="btn btn-primary">View NBI Clearance</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal HTML structure -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Change Rider and Account Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Rider Status Dropdown -->
                <div class="form-group">
                    <label for="riderStatus">Rider Status</label>
                    <select class="form-control" id="riderStatus">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Account Status Dropdown -->
                <div class="form-group">
                    <label for="accountStatus">Account Status</label>
                    <select class="form-control" id="accountStatus">
                        <option value="verified">Verified</option>
                        <option value="Unverified">Unverified</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStatusBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS for modal functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript to set the information inside the modal -->
<script>
    // When a license number link is clicked, show information in the modal
    $('#licenseModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var license = button.data('license'); // Extract info from data-* attributes
        var driverLicenseImage = button.data('driver_license_image'); // Driver's license image
        var nbiClearance = button.data('nbi_clearance'); // NBI clearance image
        var vehicleType = button.data('vehicle_type'); // Vehicle type

        var modal = $(this);

        // Set the text and the dynamic image URLs
        modal.find('#licenseText').text('License Number: ' + license); // Set the license number text
        modal.find('#vehicleType').text(vehicleType); // Set the vehicle type text
        
        // Attach event to view license image button to open it in a new tab
        modal.find('#viewLicenseImage').click(function() {
            window.open('http://localhost:8000/' + driverLicenseImage, '_blank');
        });
        
        // Attach event to view NBI clearance image button to open it in a new tab
        modal.find('#viewNbiClearance').click(function() {
            window.open('http://localhost:8000/' + nbiClearance, '_blank');
        });
    });
</script>
<script>
    // When the modal is shown, populate the dropdowns with the current values
    $('#statusModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var riderId = button.data('rider_id'); // Extract rider ID
        var currentRiderStatus = button.data('current_rider_status'); // Current rider status
        var currentAccountStatus = button.data('current_account_status'); // Current account status

        var modal = $(this);

        // Set the current values in the dropdowns
        modal.find('#riderStatus').val(currentRiderStatus);
        modal.find('#accountStatus').val(currentAccountStatus);

        // When the Save Changes button is clicked
        modal.find('#saveStatusBtn').click(function() {
            var newRiderStatus = modal.find('#riderStatus').val(); // Get the selected rider status
            var newAccountStatus = modal.find('#accountStatus').val(); // Get the selected account status

            // AJAX request to update the statuses in the database
            $.ajax({
                url: 'rider-change-status.php',  // The PHP file that handles the status change
                type: 'POST',
                data: {
                    rider_id: riderId,
                    rider_status: newRiderStatus,
                    account_status: newAccountStatus
                },
                success: function(response) {
					 // Display the response from PHP
					 alert("Rider and account status updated successfully!"); // This will show the message returned from PHP

					// Optionally, reload the page or update the DOM as necessary
					location.reload(); // Reload the page to show updated status
                },		
                error: function() {
                    alert('Error updating statuses');
                }
            });

            // Close the modal after saving
            $('#statusModal').modal('hide');
        });
    });
</script>
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