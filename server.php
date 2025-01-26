<?php
include('conn.php');

header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM actions ORDER BY created_at DESC");
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $output = array();
    $no = 1;
    $i = 0;
    foreach($data as $row){
        $output[$i]['id']               =   $no;
        $output[$i]['action_name']      =   $row['action_name'];
        $output[$i]['created_at']       =   $row['created_at'];
        $output[$i]['action']           =   '<button type="button" class="btn btn-danger btn-sm delete" data-id="'.$row['id'].'">Delete</button>';
        $no++;
        $i++;
    }
    $data1 = $output;
    echo json_encode(["data" => $data1]); // Return data with "data" key for DataTables
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action_name'])){
        if($_POST['action_name'] == "delete"){
            $dataId = $_POST['deleteId'];
            $conn->query("DELETE FROM actions WHERE id=$dataId");
        }else{
            $actionName = $_POST['action_name'];
            $conn->query("INSERT INTO actions (action_name) VALUES ('$actionName')");
        }
        echo json_encode(["status" => "success"]);
    }

    // For sending message
    if(isset($_POST['sendMessage'])){
        $from_user = $_POST['from_user'];
        $to_user = $_POST['to_user'];
        $myMessage = $_POST['myMessage'];
        $sql = "SELECT * FROM account WHERE username=?";
        $check = $pdo->prepare($sql);
        $check->execute(array($from_user));
        if($check->rowCount() > 0){
            $insert = "INSERT INTO messages SET from_user=?, to_user=?, message=?";
            $con = $pdo->prepare($insert);
            $con->execute(array($from_user, $to_user, $myMessage));
        }
        echo json_encode(["status" => "success"]);
    }

    // For retrieving messages
    if(isset($_POST['viewMessage'])){
        $username = $_SESSION['user'];
        $sql = "SELECT * FROM messages WHERE from_user=? OR to_user = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($username,$username));
        $messages = [];
        if($stmt->rowCount() > 0){
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($res as $row){
                $messages[] = [
                    'from_user' =>  $row['from_user'],
                    'to_user'   =>  $row['to_user'],
                    'message'   =>  $row['message']
                ];
            }
        }
        echo json_encode($messages);
    }

}
?>
