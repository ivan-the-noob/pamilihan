

<?php require_once('header.php'); 


?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Rider Verification</h1>
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
                                <th width="150">Full Name</th>
                                <th width="150">Email</th>
                                <th width="150">E-Signature</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
							$statement = $pdo->prepare("SELECT * FROM tbl_user WHERE role = 'rider' AND verification = 2");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <?php if ($row['e-signature']): ?>
                                            <img src="../assets/img/verification/<?php echo $row['e-signature']; ?>" width="100" />
                                        <?php else: ?>
                                            No Signature
                                        <?php endif; ?>
                                    </td>
									<td>
										<!-- Confirm button -->
										<button class="btn btn-success btn-xs" data-toggle="modal" data-target="#confirm-action" data-id="<?php echo $row['id']; ?>" data-verification="3">Confirm</button>
										<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm-action" data-id="<?php echo $row['id']; ?>" data-verification="1">Decline</button>

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