

<?php require_once('header.php'); 


?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Reports</h1>
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
                                <th width="30">#</th>
                                <th width="150">Order ID</th>
                                <th width="150">Customer Name</th>
                                <th width="150">Rider Name</th>
                                <th width="150">Seller Name</th>
                                <th width="150">Report Reason</th>
                                <th width="150">Report To</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                           
                            $statement = $pdo->prepare("
                                SELECT r.order_id, r.customer_id, r.rider_id, r.seller_id, r.report_reason, r.report_to,
                                    u_customer.full_name AS customer_name,
                                    u_rider.full_name AS rider_name,
                                    u_seller.full_name AS seller_name
                                FROM tbl_report r
                                LEFT JOIN tbl_user u_customer ON u_customer.id = r.customer_id
                                LEFT JOIN tbl_user u_rider ON u_rider.id = r.rider_id
                                LEFT JOIN tbl_user u_seller ON u_seller.id = r.seller_id
                            ");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['order_id']; ?></td>
                                    <td><?php echo $row['customer_name']; ?></td>
                                    <td><?php echo $row['rider_name']; ?></td>
                                    <td><?php echo $row['seller_name']; ?></td>
                                    <td><?php echo $row['report_reason']; ?></td>
                                    <td><?php echo $row['report_to']; ?></td>
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

<div class="modal fade" id="confirm-action" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Action Confirmation</h4>
            </div>
            <div class="modal-body">
             
                <form id="confirm-form" method="POST" action="update_verification.php">
                    <input type="hidden" name="id" id="user-id" value ="<?php echo $row['id']; ?>">
                    <input type="hidden" name="verification" id="verification-status">
					
                    <div id="modal-message"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="confirm-form">Proceed</button> 
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
$('#confirm-action').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);  // Button that triggered the modal
    var userId = button.data('id');  // Extract user ID from data-* attributes
    var verificationStatus = button.data('verification');  // Extract verification status

    // Debugging: Check values before setting the form
    console.log('Button data-id:', userId);
    console.log('Button data-verification:', verificationStatus);

    // Check if modal body is being updated
    $('#modal-message').text('Modal opened!');

    // Set the hidden input fields for ID and verification status
    $('#user-id').val(userId);
    $('#verification-status').val(verificationStatus);

    // Set the action text dynamically
    if (verificationStatus == 3) {
        $('#modal-message').text('Are you sure you want to Confirm this action?');
    } else if (verificationStatus == 1) {
        $('#modal-message').text('Are you sure you want to Decline this action?');
    }
});




</script>


<?php require_once('footer.php'); ?>