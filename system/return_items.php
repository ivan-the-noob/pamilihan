

<?php require_once('header.php'); 


?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Return Items</h1>
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
                                <th width="150">Proof</th>
                                <th width="150">Gcash Number</th>
                                <th width="150">Gcash Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $statement = $pdo->prepare("
                                SELECT ri.order_id, ri.reason, ri.gcash_name, ri.gcash_number, ri.gcash_image,
                                       po.customer_id,
                                       u_customer.full_name AS customer_name
                                FROM tbl_return_items ri
                                LEFT JOIN tbl_purchase_order po ON po.order_id = ri.order_id
                                LEFT JOIN tbl_user u_customer ON u_customer.id = po.customer_id
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
                                    <td>
                                        <?php if ($row['gcash_image']) { ?>
                                            <a href="#" data-toggle="modal" data-target="#imageModal" 
                                               data-image="../assets/img/return_items/<?php echo $row['gcash_image']; ?>">
                                                <img src="../assets/img/return_items/<?php echo $row['gcash_image']; ?>" alt="Proof Image" style="width: 100%; height: 100%;">
                                            </a>
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $row['gcash_number']; ?></td>
                                    <td><?php echo $row['gcash_name']; ?></td>
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

<!-- Modal for Image Zoom -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Proof Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Proof Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#imageModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var imageUrl = button.data('image'); 
        var modal = $(this);
        modal.find('#modalImage').attr('src', imageUrl); 
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>



<?php require_once('footer.php'); ?>