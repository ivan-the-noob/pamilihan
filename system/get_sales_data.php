<?php
$period = $_GET['period'] ?? 'monthly';  // Default is 'monthly'
$pdo = new PDO("mysql:host=localhost;dbname=pamilihan_ecomerce", "root", "");

$from_date = date('Y-01-01'); // Start of current year
$to_date = date('Y-12-31');   // End of current year

// Weekly period
if ($period === 'weekly') {
    $query = "SELECT CEIL(DAYOFMONTH(FROM_UNIXTIME(p.date_and_time)) / 7) AS week_number, 
                     SUM(p.total_amount) AS totalAmount,
                     COUNT(p.order_id) AS countOrder
              FROM tbl_purchase_payment p
              WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date
              GROUP BY week_number
              ORDER BY week_number ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':from_date' => strtotime("$from_date 00:00:00"),
        ':to_date' => strtotime("$to_date 23:59:59")
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Define the four weeks manually
    $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
    $sales = array_fill(0, 4, 0);
    $transactions = array_fill(0, 4, 0);

    foreach ($data as $row) {
        $week_index = (int)$row['week_number'] - 1;
        if ($week_index >= 0 && $week_index < 4) {
            $sales[$week_index] = $row['totalAmount'];
            $transactions[$week_index] = $row['countOrder'];
        }
    }

    echo json_encode([
        'labels' => $labels,
        'sales' => $sales,
        'transactions' => $transactions,
    ]);
    exit;
}

// Monthly period
if ($period === 'monthly') {
    $from_date = date('Y-01-01'); // Start of current year
    $to_date = date('Y-12-31');   // End of current year

    $query = "SELECT MONTH(FROM_UNIXTIME(p.date_and_time)) AS month_number, 
                     SUM(p.total_amount) AS totalAmount,
                     COUNT(p.order_id) AS countOrder
              FROM tbl_purchase_payment p
              WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date
              GROUP BY month_number
              ORDER BY month_number ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':from_date' => strtotime("$from_date 00:00:00"),
        ':to_date' => strtotime("$to_date 23:59:59")
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define the months manually
    $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $sales = array_fill(0, 12, 0);
    $transactions = array_fill(0, 12, 0);

    foreach ($data as $row) {
        $month_index = (int)$row['month_number'] - 1;  // Convert 1-12 to 0-11 index
        if ($month_index >= 0 && $month_index < 12) {
            $sales[$month_index] = $row['totalAmount'];
            $transactions[$month_index] = $row['countOrder'];
        }
    }

    echo json_encode([
        'labels' => $labels,
        'sales' => $sales,
        'transactions' => $transactions,
    ]);
    exit;
}

// Quarterly period
if ($period === 'quarterly') {
    $from_date = date('Y-01-01'); // Start of current year
    $to_date = date('Y-12-31');   // End of current year

    $query = "SELECT QUARTER(FROM_UNIXTIME(p.date_and_time)) AS quarter_number, 
                     SUM(p.total_amount) AS totalAmount,
                     COUNT(p.order_id) AS countOrder
              FROM tbl_purchase_payment p
              WHERE p.date_and_time >= :from_date AND p.date_and_time <= :to_date
              GROUP BY quarter_number
              ORDER BY quarter_number ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':from_date' => strtotime("$from_date 00:00:00"),
        ':to_date' => strtotime("$to_date 23:59:59")
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define the quarters manually
    $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
    $sales = array_fill(0, 4, 0);
    $transactions = array_fill(0, 4, 0);

    foreach ($data as $row) {
        $quarter_index = (int)$row['quarter_number'] - 1; // Convert 1-4 to 0-3 index
        if ($quarter_index >= 0 && $quarter_index < 4) {
            $sales[$quarter_index] = $row['totalAmount'];
            $transactions[$quarter_index] = $row['countOrder'];
        }
    }

    echo json_encode([
        'labels' => $labels,
        'sales' => $sales,
        'transactions' => $transactions,
    ]);
    exit;
}

// Yearly period
if ($period === 'yearly') {
    // Get the current year and define the range
    $current_year = date('Y');
    $start_year = $current_year - 4;
    $end_year = $current_year;

    $query = "SELECT YEAR(FROM_UNIXTIME(p.date_and_time)) AS year_number, 
                     SUM(p.total_amount) AS totalAmount,
                     COUNT(p.order_id) AS countOrder
              FROM tbl_purchase_payment p
              WHERE YEAR(FROM_UNIXTIME(p.date_and_time)) BETWEEN :start_year AND :end_year
              GROUP BY year_number
              ORDER BY year_number ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':start_year' => $start_year,
        ':end_year' => $end_year,
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    for ($i = $start_year; $i <= $end_year; $i++) {
        $labels[] = (string)$i;  
    }

    $sales = array_fill(0, 5, 0);
    $transactions = array_fill(0, 5, 0);

    foreach ($data as $row) {
        $year_index = $row['year_number'] - $start_year;  
        if ($year_index >= 0 && $year_index < 5) {
            $sales[$year_index] = $row['totalAmount'];
            $transactions[$year_index] = $row['countOrder'];
        }
    }

    echo json_encode([
        'labels' => $labels,
        'sales' => $sales,
        'transactions' => $transactions,
    ]);
    exit;
}
?>
