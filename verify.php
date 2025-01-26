<?php
include('header.php');

if(isset($_GET['email']) && isset($_GET['token'])){
    $getUserId = "";
    $sql = "SELECT * FROM tbl_customer WHERE cust_email=:email AND cust_token=:token AND cust_status='0'";
    $p = [
        ':email'    =>  $_GET['email'],
        ':token'    =>  $_GET['token']
    ];
    $res = $c->countData($pdo, $sql, $p);
    if($res > 0){
        // GET USER ID
        $getVal = $c->fetchData($pdo, $sql, $p);
        if($getVal){
            foreach($getVal as $row){
                $getUserId = $row['user_id'];
            }
                // UPDATE THE CUSTOMER STATUS TO 1
            $upd = "UPDATE tbl_customer SET cust_status='1' WHERE user_id=:u_id";
            $p = [
                ':u_id'     =>  $getUserId
            ];
            $res1 = $c->updateData($pdo, $upd, $p);
            if($res1 == true){
                // UPDATE THE USER TO ACTIVE
                $upd1 = "UPDATE tbl_user SET status='Active' WHERE id=:u_id";
                $p1 = [
                    ':u_id'     =>  $getUserId
                ];
                $res2 = $c->updateData($pdo, $upd1, $p1);
                if($res2 == true){
                    ?>
                    <section class="ftco-section">
                        <div class="container">
                            <div class="text-center">
                                <h3>Email Verification Successfully!</h3>
                                <p><a href="login.php">Go back to login.</a></p>
                            </div>
                        </div><br>
                        <hr/>
                    </section>
                    <?php
                }else{
                    ?>
                    <section class="ftco-section">
                        <div class="container">
                            <div class="text-center">
                                <h3>Failed to verify your email!</h3>
                                <p><a href="register.php">Go back to register.</a></p>
                            </div>
                        </div><br>
                        <hr/>
                    </section>
                    <?php
                }
            }else{
                ?>
                <section class="ftco-section">
                    <div class="container">
                        <div class="text-center">
                            <h3>Failed to verify your email!</h3>
                            <p><a href="register.php">Go back to register.</a></p>
                        </div>
                    </div><br>
                    <hr/>
                </section>
                <?php
            }
        }else{
            ?>
            <section class="ftco-section">
                <div class="container">
                    <div class="text-center">
                        <h3>Failed to verify your email!</h3>
                        <p><a href="register.php">Go back to register.</a></p>
                    </div>
                </div><br>
                <hr/>
            </section>
            <?php
        }
    }else{
        ?>
        <section class="ftco-section">
            <div class="container">
                <div class="text-center">
                    <h3>Failed to verify your email!</h3>
                    <p><a href="register.php">Go back to register.</a></p>
                </div>
            </div><br>
            <hr/>
        </section>
        <?php
    }
}else{
    header("Location: ./");
    exit;
}
include('footer.php');
?>