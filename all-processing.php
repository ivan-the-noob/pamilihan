<?php
include_once("system/inc/config.php");
include_once("system/inc/functions.php");
include_once("system/inc/class.php");
include_once("system/inc/CSRF_Protect.php");

$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

$c = new CustomizeClass();
$csrf = new CSRF_Protect();

$allOrders = "
    SELECT 
        pp.total_amount AS total_price, 
        pp.transaction_id AS transaction_id, 
        o.*, 
        o.estimated_time AS estimated_time,
        u.full_name, 
        u.email AS rider_email,
        r.vehicle_type,
        r.vehicle_model,
        r.vehicle_plate_no
    FROM tbl_purchase_order o
    JOIN tbl_purchase_payment pp ON o.order_id = pp.order_id
    LEFT JOIN tbl_user u ON o.rider_id = u.id
    LEFT JOIN tbl_rider r ON o.rider_id = r.user_id
    WHERE o.customer_id = :c_id 
    ORDER BY o.id DESC
";

$allOrdersP = [
    ":c_id" => $_SESSION['customer']['id']
];

$allOrdersS = $c->fetchData($pdo, $allOrders, $allOrdersP);

$orderId = "";
$totalPrice = "";
$transactionId = "";
$dat = "";
$status = "";
$rider = "";
$rider_id = "";
$full_name = "";
$vehicle_type = "";
$vehicle_model = "";
$vehicle_plate_no = "";
$estimatedTime = "";


if (isset($_SESSION['email']) && isset($_SESSION['id'])) {

    $user_email = $_SESSION['email'];
    $user_id = $_SESSION['id'];
  }
  


?>
<style>
    /* General styles */
.chat-container {
    display: flex;
    flex-direction: column;
    height: 400px;
    max-height: 100vh;
    width: 100%;
    background-color: #f7f7f7;
    border-radius: 8px;
    overflow: hidden;
    padding: 10px;
}

.chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    font-size: 16px;
}

.chat-header #chatUserName {
    font-weight: bold;
}

.close-button {
    font-size: 24px;
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
}

.message-container {
    flex: 1;
    overflow-y: auto;
    margin-top: 10px;
}

.message-left, .message-right {
    max-width: 50%;
    padding: 10px;
    border-radius: 20px;
    margin-bottom: 10px;
    word-wrap: break-word;
}

.message-right {
    background-color: #e0e0e0;
    align-self: flex-start; 
    margin-right: auto; 
}

.message-left {
    background-color: #4CAF50;
    color: white;
    align-self: flex-end; 
    margin-left: auto;
}

#input-container {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #fff;
    border-top: 1px solid #ddd;
}

.input-containers {
    display: flex;
    flex-grow: 1;
}

#messageInput {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 14px;
    outline: none;
}

#messageInput:focus {
    border-color: #4CAF50;
}

button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 20px;
    margin-left: 10px;
    font-size: 14px;
    border-radius: 20px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

.modal-footer {
    background-color: #f7f7f7;
    border-top: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

.btn-default {
    background-color: #f7f7f7;
    color: #4CAF50;
    border: 1px solid #4CAF50;
    border-radius: 20px;
    padding: 10px 20px;
    cursor: pointer;
}

.btn-default:hover {
    background-color: #4CAF50;
    color: white;
}

</style>

    <table class="table border" style="width:100% !important;">
        <thead class="thead-secondary" style="color: black !important;">
            <tr>
                <th rowspan="2" class="border">No.</th>
                <th rowspan="2" class="border">Order ID</th>
                <th colspan="5" class="border">Product Details</th>
                <th rowspan="2" class="border">Total Amount</th>
                <th rowspan="2" class="border">Transaction ID</th>
                <th rowspan="2" class="border">Date</th>
                <th rowspan="2" class="border">Rider</th>
                <th rowspan="2" class="border">Order Status</th>
            </tr>
            <tr>
                <th class="border" width="165px">&nbsp;</th>
                <th class="border" width="50px">Product Name</th>
                <th class="border">Price</th>
                <th class="border">Quantity</th>
                <th class="border">Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if($allOrdersS){
                foreach($allOrdersS as $r){
                    $orderId = $r['order_id'];
                    $totalPrice = $r['total_price'];
                    $transactionId =  $r['transaction_id'];
                    $order['rider_email'] = $r['rider_email'];
                    $dat = date("M. d, Y (h:i A)", $r['date_and_time']);
                    $stat = $r['status'];
                    $rider = $r['rider_email'];
                    $rider_id = $r['rider_id'];
                    $full_name = $r['full_name'];
                    $vehicle_type = $r['vehicle_type'];
                    $vehicle_model = $r['vehicle_model'];
                    $vehicle_plate_no = $r['vehicle_plate_no'];
                    $estimatedTime = $r['estimated_time'];
        
                    $customerEmail = isset($_SESSION['customer']['email']) ? $_SESSION['customer']['email'] : 'No email found';

                    $sql = "SELECT COUNT(order_id) AS totalOrder FROM tbl_purchase_item WHERE order_id=:ordId";
                    $p = [
                        ":ordId"    =>  $orderId
                    ];
                    $totalOrder = 0;
                    $item = $c->fetchData($pdo, $sql, $p);
                    foreach($item as $ro){
                        $totalOrder = $ro['totalOrder'] + 1;
                    }
                    ?>
                    <tr>
                        <td rowspan="<?= $totalOrder; ?>" class="border" style="border: 1px solid #E5E5E5 !important;"><?= $no; ?>.</td>
                        <td rowspan="<?= $totalOrder; ?>" class="border" style="border: 1px solid #E5E5E5 !important;"><span><b><?= $orderId; ?></b></span></td>
                    </tr>
                    
                    <?php
                    $totalPricePerItem = 0;
                    $sql1 = "SELECT p.*, pi.product_qty, pi.product_price, pi.size_id FROM tbl_purchase_item pi JOIN tbl_product p ON pi.product_id=p.p_id WHERE pi.order_id=:ordId";
                    $p1 = [
                        ":ordId"    =>  $orderId
                    ];
                    $res1 = $c->fetchData($pdo, $sql1, $p1);
                    $itemNo = 1;

                    
                    if($res1){
                        foreach($res1 as $val){
                            $totalPricePerItem = $val['product_qty'] * $val['product_price'];
                            ?>
                            <tr>
                                <td style="border: 1px solid #E5E5E5 !important;" class="image-prod">
                                    <div class="img" style="background-image:url(assets/uploads/<?= $val['p_featured_photo']; ?>);"></div>
                                </td>
                                <?php
                                $sname = "SELECT * FROM tbl_size WHERE size_id=:sizId";
                                $p2 = [
                                    ":sizId"    =>  $val['size_id']
                                ];
                                $rr = $c->fetchData($pdo, $sname, $p2);
                                $showSizeName = "";
                                if($rr){
                                    foreach($rr as $rrr){
                                        if($val['size_id'] != "0"){
                                            $showSizeName = "(".$rrr['size_name'].")";
                                        }else{
                                            $showSizeName = "";
                                        }
                                    }
                                }else{
                                    $showSizeName = "";
                                }
                                ?>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $val['p_name']; ?><br><span><small><?= $showSizeName; ?></span></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $php; ?><?= $val['product_price']; ?></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $val['product_qty']; ?></td>
                                <td style="border: 1px solid #E5E5E5 !important;"><?= $php; ?><?= $totalPricePerItem; ?></td>
                                <?php
                                if($itemNo == 1){
                                    ?>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>"><?= $php; ?><?= number_format($totalPrice, 2); ?></td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>">
                                        <?= $transactionId; ?><br>
                                        <?php if($estimatedTime !== null): ?>
                                            <p class="mb-0 text-center" style="white-space: nowrap;">Deliver Time:<br><?= $estimatedTime; ?> Minutes </p>
                                        <?php endif; ?>
                                    </td>
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px"><?= $dat; ?></td>
                                    <?php if (!empty($rider_id)): ?>

                                        <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px">
                                        <?= $full_name; ?><br>
                                        <?= $vehicle_type; ?><br>
                                        <?= $vehicle_model; ?><br>
                                        <?= $vehicle_plate_no;?>

                                        </td>
                                    <?php else: ?>
                                        <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>" width="175px">Pending</td>
                                    <?php endif; ?>

                                    
                                    <td style="border: 1px solid #E5E5E5 !important;" rowspan="<?= $totalOrder; ?>">
                                        <?= $r['remarks']; ?>
                                        <?php if ($stat == "Delivering Items") { ?>
                                            <button type="button" class="btn btn-success" style="border-radius: 5px !important;">
                                                <span class="icon-message"></span>&nbsp;Chat
                                            </button>
                                        <?php } elseif ($stat == "Pending") { ?>
                                            <button class="btn btn-sm btn-danger cancelOrder" data-order="<?= $orderId; ?>" style="border-radius: 5px !important;">Cancel</button>
                                        <?php } ?>

                                        <button class="btn btn-sm btn-block btn-success showMessage"
                                            data-toggle="modal"
                                            data-target="#chatmodal"
                                            data-sender-email="<?= htmlspecialchars($customerEmail); ?>"
                                            data-receiver-email="<?= htmlspecialchars($order['rider_email']); ?>"
                                            id="sendMessageButton">
                                            Message
                                        </button>

                                        <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="cancelOrderForm" method="POST" action="cancel_reason.php">
                                                    <div class="modal-body">
                                                    <p>Are you sure you want to cancel your order?</p>

                                                    <div class="form-group">
                                                        <label for="cancelReason">Reason for Cancellation:</label>
                                                        <textarea class="form-control" id="cancelReason" name="cancel_reason" rows="3" placeholder="Enter reason for cancellation"></textarea>
                                                    </div>

                                                    <input type="hidden" id="order_id" name="order_id" value="<?= $orderId; ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>




                                        <!-- Chat Modal -->
                                        <div class="modal fade" id="chatmodal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="chat-container">
                                                            <div class="chat-header" id="chatHeader">
                                                                Chat with <span id="chatUserName">...</span>
                                                                <button class="close-button" data-dismiss="modal" aria-hidden="true" onclick="closeChat()">×</button>
                                                            </div>

                                                          
                                                            <div id="messageContainer" class="message-container">
                                                                <?php
                                                               
                                                                if (isset($_SESSION['customer']['email'])) {
                                                                    $senderEmail = $_SESSION['customer']['email']; 
                                                                } elseif (isset($_SESSION['rider']['email'])) {
                                                                    $senderEmail = $_SESSION['rider']['email'];
                                                                } else {
                                                                    echo 'No email found in session';
                                                                    exit;
                                                                }

                                                             

                                                                $query = "SELECT * FROM messages WHERE sender_email = :sender_email OR receiver_email = :sender_email ORDER BY created_at ASC";
                                                                $stmt = $pdo->prepare($query);
                                                                $stmt->bindParam(':sender_email', $senderEmail);
                                                                $stmt->execute();
  
                                                                if ($stmt->rowCount() > 0) {
                                                                    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                         
                                                                    foreach ($messages as $message) {
                                                                        $messageDiv = '<div class="message-container">';
                                                                        
                                                                       
                                                                        if ($message['sender_email'] === $senderEmail) {
                                                                            $messageDiv .= '<div class="message-right">' . htmlspecialchars($message['message']) . '</div>';
                                                                        }
                                                                        
                                                                       
                                                                        if ($message['receiver_email'] === $senderEmail) {
                                                                            $messageDiv .= '<div class="message-left">' . htmlspecialchars($message['message']) . '</div>';
                                                                        }
  
                                                                 
                                                                        $messageDiv .= '</div>';
                                                                        
                                                               
                                                                        echo $messageDiv;
                                                                    }
                                                                } else {
                                                                    echo 'No messages found';
                                                                }
                                                                ?>
                                                            </div>

                                                            <form id="sendaMessage">
                                                                <input type="hidden" id="sender_email" name="sender_email" value="<?php echo $customerEmail; ?>">
                                                                <input type="hidden" id="receiver_email" name="receiver_email" value="<?= htmlspecialchars($order['rider_email']); ?>">
                                                                <input type="text" id="messageInput" name="message" placeholder="Type your message" required>
                                                                <button type="submit">Send Message</button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeChat()">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                                        </td>
                                                        
                                                    </td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                $itemNo++;
                                            }
                                        }
                                        $no++;
                                    }
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="11">Empty order!</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <script>
                        var receiverEmail = document.getElementById('receiver_email').value;
                        var senderEmail = "<?php echo htmlspecialchars($customerEmail); ?>";
                        console.log("Sender Email:", senderEmail);
                        console.log("Receiver Email:", receiverEmail);

                        function loadMessages(receiverEmail, senderEmail) {
                            const messageContainer = document.getElementById("messageContainer");
                            messageContainer.innerHTML = ''; 

                            const xhr = new XMLHttpRequest();
                            xhr.open("GET", "fetch_messages.php?receiver_email=" + receiverEmail + "&sender_email=" + senderEmail, true);

                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    const messages = JSON.parse(xhr.responseText);
                                    messages.forEach(function(message) {
                                        const messageDiv = document.createElement("div");
                                        messageDiv.classList.add("message");

                                        if (message.sender_email === senderEmail) {
                                            messageDiv.classList.add("sender");
                                        } else {
                                            messageDiv.classList.add("receiver");
                                        }

                                        messageDiv.textContent = message.message;
                                        messageContainer.appendChild(messageDiv);
                                    });
                                    messageContainer.scrollTop = messageContainer.scrollHeight;
                                } else {
                                    console.error("Error fetching messages");
                                }
                            };
                            xhr.send();
                        }

                        function showChat(receiverEmail, senderEmail, receiverName) {
                            document.getElementById("chatUserName").textContent = receiverName;
                            loadMessages(receiverEmail, senderEmail);
                            $('#showMessages').modal('show');
                        }

                        document.getElementById('sendMessageButton').addEventListener('click', function () {
                            const senderEmail = this.getAttribute('data-sender-email');
                            const receiverEmail = this.getAttribute('data-receiver-email');
                            
                            console.log('Sender Email:', senderEmail);
                            console.log('Receiver Email:', receiverEmail);

                            document.getElementById('sender_email').value = senderEmail;
                            document.getElementById('receiver_email').value = receiverEmail;
                        });

                        document.getElementById('sendaMessage').addEventListener('submit', function (e) {
                            e.preventDefault();

                            const senderEmail = document.getElementById('sender_email').value;
                            const receiverEmail = document.getElementById('receiver_email').value;
                            const messageInput = document.getElementById('messageInput').value;

                            if (!senderEmail || !receiverEmail || !messageInput) {
                                console.error("Missing required fields: sender_email, receiver_email, or message");
                                return;
                            }

                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', 'send-message.php', true); 
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                            xhr.onload = function () {
                                if (xhr.status === 200) {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        console.log('Message sent successfully');
                                        document.getElementById('messageInput').value = '';
                                    } else {
                                        console.error('Error sending message:', response.error);
                                    }
                                } else {
                                    console.error('Error sending message: HTTP ' + xhr.status);
                                }
                            };

                            const data = `message=${encodeURIComponent(messageInput)}&sender_email=${encodeURIComponent(senderEmail)}&receiver_email=${encodeURIComponent(receiverEmail)}`;
                            xhr.send(data);
                        });
                        </script>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>