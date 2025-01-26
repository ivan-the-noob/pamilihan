<?php
session_start();
if (isset($_GET['export_csv'])) {
    // Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone
date_default_timezone_set('Asia/Manila');

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'pamilihan_ecomerce';

// Database Username
$dbuser = 'root';

// Database Password
$dbpass = '';

// Defining base url
define("BASE_URL", "");

// Getting Admin url
define("ADMIN_URL", BASE_URL . "admin" . "/");

try {
	$pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the data within the date range
    $from_date = $_GET['from_date'] ?? date('Y-m-01');
    $to_date = $_GET['to_date'] ?? date('Y-m-d');
    $sql = "SELECT DISTINCT order_id FROM tbl_purchase_item WHERE seller_id=:seller_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':seller_id'    =>  $_SESSION['user']['id']
    ]);
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        $query = "SELECT DISTINCT p.*, o.status as shipped_status, o.date_and_time as shipped_date, u.full_name, u.email FROM tbl_purchase_payment p JOIN tbl_purchase_order o ON p.order_id=o.order_id JOIN tbl_user u ON o.customer_id=u.id WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date ORDER BY p.date_and_time DESC";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':from_date' => strtotime("$from_date 00:00:00"),
            ':to_date' => strtotime("$to_date 23:59:59")
        ]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($statement->rowCount() > 0){
            // Define the file name
            $filename = "sales_report_" . date('Ymd') . ".csv";

            // Set headers to download the file
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");

            // Open output stream
            $output = fopen("php://output", "w");

            // Write the CSV column headers
            fputcsv($output, [
                'ID',
                'Customer Name',
                'Customer Email',
                'Payment Date',
                'Transaction ID',
                'Paid Amount',
                'Payment Status',
                'Shipping Status',
                'Shipped Date'
            ]);

            // Write rows to the CSV
            $i = 1;
            foreach($res as $val){
                foreach ($result as $row) {
                    if($row['order_id'] == $val['order_id']){
                        fputcsv($output, [
                            $i,
                            $row['full_name'],
                            $row['email'],
                            date("M. d, Y H:i:s A", $row['date_and_time']),
                            $row['transaction_id'],
                            $row['total_amount'],
                            $row['transaction_status'],
                            $row['shipped_status'],
                            date("M. d, Y H:i:s A", $row['shipped_date'])
                        ]);
                    }
                }
                $i++;
            }

            // Close output stream
            fclose($output);

            exit;
        }else{
            // Define the file name
            $filename = "sales_report_" . date('Ymd') . ".csv";

            // Set headers to download the file
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");

            // Open output stream
            $output = fopen("php://output", "w");

            // Write the CSV column headers
            fputcsv($output, [
                'ID',
                'Customer Name',
                'Customer Email',
                'Payment Date',
                'Transaction ID',
                'Paid Amount',
                'Payment Status',
                'Shipping Status',
                'Shipped Date'
            ]);

            // Close output stream
            fclose($output);

            exit;
        }
    }else{
        $query = "SELECT DISTINCT p.*, o.status as shipped_status, o.date_and_time as shipped_date, u.full_name, u.email FROM tbl_purchase_payment p JOIN tbl_purchase_order o ON p.order_id=o.order_id JOIN tbl_user u ON o.customer_id=u.id WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date ORDER BY p.date_and_time DESC";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':from_date' => strtotime("$from_date 00:00:00"),
            ':to_date' => strtotime("$to_date 23:59:59")
        ]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($statement->rowCount() > 0){
            // Define the file name
            $filename = "sales_report_" . date('Ymd') . ".csv";

            // Set headers to download the file
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");

            // Open output stream
            $output = fopen("php://output", "w");

            // Write the CSV column headers
            fputcsv($output, [
                'ID',
                'Customer Name',
                'Customer Email',
                'Payment Date',
                'Transaction ID',
                'Paid Amount',
                'Payment Status',
                'Shipping Status',
                'Shipped Date'
            ]);

            // Write rows to the CSV
            $i = 1;
            foreach ($result as $row) {
                fputcsv($output, [
                    $i,
                    $row['full_name'],
                    $row['email'],
                    date("M. d, Y H:i:s A", $row['date_and_time']),
                    $row['transaction_id'],
                    $row['total_amount'],
                    $row['transaction_status'],
                    $row['shipped_status'],
                    date("M. d, Y H:i:s A", $row['shipped_date'])
                ]);
                $i++;
            }

            // Close output stream
            fclose($output);

            exit;
        }else{
            // Define the file name
            $filename = "sales_report_" . date('Ymd') . ".csv";

            // Set headers to download the file
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");

            // Open output stream
            $output = fopen("php://output", "w");

            // Write the CSV column headers
            fputcsv($output, [
                'ID',
                'Customer Name',
                'Customer Email',
                'Payment Date',
                'Transaction ID',
                'Paid Amount',
                'Payment Status',
                'Shipping Status',
                'Shipped Date'
            ]);

            // Close output stream
            fclose($output);

            exit;
        }
    }
}
catch( PDOException $exception ) {
	echo "Connection error :" . $exception->getMessage();
}
}
?>
<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Sales Report</h1>
	</div>
	<div class="content-header-right">
		<a href="subscriber-csv.php" class="btn btn-primary btn-sm">Export as CSV</a>
	</div>
</section>


<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-body">
          <!-- Date Filter Form -->
          <form method="get" action="">
                <div class="row">
                    <div class="col-md-4">
                        <label for="from_date">From Date:</label>
                        <input type="date" name="from_date" class="form-control" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date">To Date:</label>
                        <input type="date" name="to_date" class="form-control" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                        <button type="submit" name="export_csv" class="btn btn-success btn-block">Export as CSV</button>
                    </div>
                </div>
            </form>

          <hr>

          <!-- PHP for Summary Metrics and Chart Data -->
          <?php
          $from_date = $_GET['from_date'] ?? date('Y-m-01');
          $to_date = $_GET['to_date'] ?? date('Y-m-d');

            // Fetch summary metrics
            $chart_labels = [];
            $chart_sales = [];
            $chart_transactions = [];
            $total_sales = 0;
            $date = "";
            if($_SESSION['user']['role'] == "Admin"){
                $sql = "SELECT DISTINCT order_id FROM tbl_purchase_item";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }
            if($_SESSION['user']['role'] == "Seller"){
                $sql = "SELECT DISTINCT order_id FROM tbl_purchase_item WHERE seller_id=:seller_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':seller_id'    =>  $_SESSION['user']['id']
                ]);
            }
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($stmt->rowCount() > 0){
                $query = "SELECT DISTINCT p.*, DATE(FROM_UNIXTIME(p.date_and_time)) AS date1, SUM(p.total_amount) AS totalAmount, COUNT(p.order_id) AS countOrder, o.status as shipped_status, o.date_and_time as shipped_date, u.full_name, u.email FROM tbl_purchase_payment p JOIN tbl_purchase_order o ON p.order_id=o.order_id JOIN tbl_user u ON o.customer_id=u.id WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date GROUP BY date1 ORDER BY p.date_and_time DESC";
                $statement = $pdo->prepare($query);
                $statement->execute([
                    ':from_date' => strtotime("$from_date 00:00:00"),
                    ':to_date' =>  strtotime("$to_date 23:59:59")
                ]);
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                $i = 1;
                if($statement->rowCount() > 0){
                    foreach($res as $val){
                        foreach ($result as $row) {
                            if($row['order_id'] == $val['order_id']){
                                $date = $row['date1'];
                                $chart_labels[] = $date;
                                $chart_sales[] = $row['totalAmount'];
                                $total_sales += $row['totalAmount'];
                                $chart_transactions[] = $row['countOrder'];
                                $i++;
                            }
                        }
                    }
                }
            }
          ?>

          <!-- Summary Metrics -->
          <h4>Summary Metrics</h4>
          <p><strong>Total Sales:</strong> <?php echo number_format($total_sales, 2); ?></p>
          <canvas id="salesChart" width="400" height="200"></canvas>
          <hr>

          <!-- Detailed Report -->
          <h4>Detailed Sales Report</h4>
          <table id="example1" class="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Payment Date</th>
                <th>Paid Amount</th>
                <th>Payment Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = "";

              if($_SESSION['user']['role'] == "Seller"){
                $query = "SELECT p.date_and_time as payment_date, p.total_amount as paid_amount, p.transaction_status as payment_status, u.full_name as customer_name  FROM tbl_purchase_payment p JOIN tbl_purchase_order o ON p.order_id=o.order_id JOIN tbl_user u ON o.customer_id=u.id WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date ORDER BY p.date_and_time DESC";
                $statement = $pdo->prepare($query);
                $statement->execute([
                  ':from_date' => strtotime("$from_date 00:00:00"),
                  ':to_date' =>  strtotime("$to_date 23:59:59")
                ]);
              }
              if($_SESSION['user']['role'] == "Admin"){
                $query = "SELECT p.date_and_time as payment_date, p.total_amount as paid_amount, p.transaction_status as payment_status, u.full_name as customer_name  FROM tbl_purchase_payment p JOIN tbl_purchase_order o ON p.order_id=o.order_id JOIN tbl_user u ON o.customer_id=u.id WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date ORDER BY p.date_and_time DESC";
                $statement = $pdo->prepare($query);
                $statement->execute([
                  ':from_date' => strtotime("$from_date 00:00:00"),
                  ':to_date' =>  strtotime("$to_date 23:59:59")
                ]);
              }

              $result = $statement->fetchAll(PDO::FETCH_ASSOC);
              if($statement->rowCount() > 0){
                if ($result) {
                    $i = 0;
                    foreach ($result as $row) {
                        $i++;
                        ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                          <td><?php echo htmlspecialchars(date("M. d, Y h:i:s A", $row['payment_date'])); ?></td>
                          <td><?php echo number_format($row['paid_amount'], 2); ?></td>
                          <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No sales data found for the selected date range.</td></tr>";
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

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>, // Dates
            datasets: [
                {
                    label: 'Total Sales',
                    data: <?php echo json_encode($chart_sales); ?>, // Sales amounts
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Transactions',
                    data: <?php echo json_encode($chart_transactions); ?>, // Transactions counts
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>





<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#example1').DataTable();
  });
</script>

<?php require_once('footer.php'); ?>