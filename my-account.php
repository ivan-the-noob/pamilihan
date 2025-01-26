<?php
if(isset($_SESSION['customer'])){
    // ACCOUNT DETAILS
    $email = "";
    $fullName = "";
    $phone = "";
    $photo = "";
    // BILLING ADDRESS
    $B_fullName = "";
    $B_phone = "";
    $B_address = "";
    $B_city = "";
    // SHIPPING ADDRESS
    $S_fullName = "";
    $S_phone = "";
    $S_address = "";
    $S_city = "";
    // RETRIEVE ACCOUNT DETAILS
    $sql = "SELECT * FROM tbl_user WHERE id=:u_id AND role='customer'";
    $p = [
        ':u_id' =>  $_SESSION['customer']['id'],
    ];
    $res = $c->fetchData($pdo, $sql, $p);
    foreach($res as $row){
        $email = $row['email'];
        $fullName = $row['full_name'];
        $phone = $row['phone'];
        $photo = $row['photo'];
    }
    // RETRIEVE BILLING ADDRESS
    $sql1 = "SELECT * FROM tbl_billing_address WHERE user_id=:u_id";
    $p1 = [
        ':u_id' =>  $_SESSION['customer']['id'],
    ];
    $res1 = $c->fetchData($pdo, $sql1, $p1);
    if($res1 != false){
        foreach($res1 as $row1){
            $B_fullName = $row1['full_name'];
            $B_phone = $row1['phone'];
            $B_address = $row1['address'];
            $B_city = $row1['city'];
        }
    }
    // RETRIEVE SHIPPING ADDRESS
    $sql1 = "SELECT * FROM tbl_shipping_address WHERE user_id=:u_id";
    $p1 = [
        ':u_id' =>  $_SESSION['customer']['id'],
    ];
    $res1 = $c->fetchData($pdo, $sql1, $p1);
    if($res1 != false){
        foreach($res1 as $row1){
            $S_fullName = $row1['full_name'];
            $S_phone = $row1['phone'];
            $S_address = $row1['address'];
            $S_city = $row1['city'];
        }
    }
}else{
    header("Location: ".BASE_URL."login.php");
    exit();
}
?>
<section class="mt-4 mb-4">
    <div class="container">
        <h4><span class="icon-user"> </span>My Account</h4>
        <hr/>
        <div class="row justify-content-center">
            <div class="col-md-3 ftco-animate p-3 m-2 border">
                <div class="mt-3">
                    <div class="accountMessage">
                    </div>
                    <b>Update Account</b>
                </div>
                <hr/>
                <form id="updateAccount" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="<?= $email; ?>" placeholder="Email address..." readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="f_name" value="<?= $fullName; ?>" placeholder="Full name...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Phone No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="11" name="phone_no" value="<?= $phone; ?>" placeholder="Phone number...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-block" style="border-radius: 5px !important;" name="submit" value="Update Account">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 ftco-animate p-3 m-2 border">
                <div class="mt-3">
                    <div class="billingMessage">
                    </div>
                    <b>Update Billing Address</b>
                </div>
                <hr/>
                <form method="POST" id="updateBilling">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="f_name" name="B_fname" value="<?= $B_fullName; ?>" placeholder="Full name...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Phone No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="11" id="phone_no" name="B_phone" value="<?= $B_phone; ?>" placeholder="Phone no...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Country</label>
                                <input type="text" class="form-control" id="country" name="B_country" value="Philippines" style="background-color: lightgray !important;" placeholder="Country..." readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Address <span class="text-danger">*</span></label>
                                <textarea rows="6" class="form-control" id="bAddress" name="B_address" placeholder="House No./Lot No., Street, Barangay"><?= $B_address; ?></textarea>
                                <small><b>Format: </b>House No./Lot No., Street, Barangay</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">City <span class="text-danger">*</span></label>
                                <select name="B_city" class="form-control" id="city">
                                    <option value="" selected>Select an option</option>
                                    <option value="Palayan" <?php if($B_city == 'Palayan'){ echo 'selected'; } ?>>Palayan</option>
                                    <option value="Cavite" <?php if($B_city == 'Cavite'){ echo 'selected'; } ?>>Cavite</option>
                                    <option value="Taguig" <?php if($B_city == 'Taguig'){ echo 'selected'; } ?>>Taguig</option>
                                    <option value="Manila" <?php if($B_city == 'Manila'){ echo 'selected'; } ?>>Manila</option>
                                    <option value="Davao" <?php if($B_city == 'Davao'){ echo 'selected'; } ?>>Davao</option>
                                    <option value="Muntinlupa" <?php if($B_city == 'Muntinlupa'){ echo 'selected'; } ?>>Muntinlupa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" hidden>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label for="sameAsShipping"><input type="checkbox" class="mr-2" name="sameAsShipping" value="" id="sameAsShipping">Use this address for shipping address.</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-block" style="border-radius: 5px !important;" name="submit" value="Update Billing">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 ftco-animate p-3 m-2 border">
                <div class="mt-3">
                    <div class="shippingMessage">
                    </div>
                    <b>Update Shipping Address</b>
                </div>
                <hr/>
                <form method="POST" id="updateShipping">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="f_name" name="S_fname" value="<?= $S_fullName; ?>" placeholder="Full name...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Phone No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="11" id="phone_no" name="S_phone" value="<?= $S_phone; ?>" placeholder="Phone no...">
                            </div>
                        </div>
                        <div class="col-md-12" hidden>
                            <div class="form-group">
                                <label for="">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="Philippines" style="background-color: lightgray !important;" placeholder="Country..." readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Address <span class="text-danger">*</span></label>
                                <textarea rows="6" class="form-control" id="sAddress" name="S_address" placeholder="House No./Lot No., Street, Barangay"><?= $S_address; ?></textarea>
                                <small><b>Format: </b>House No./Lot No., Street, Barangay</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">City <span class="text-danger">*</span></label>
                                <select name="S_city" class="form-control" id="city">
                                    <option value="" selected>Select an option</option>
                                    <option value="Palayan" <?php if($S_city == 'Palayan'){ echo 'selected'; } ?>>Palayan</option>
                                    <option value="Cavite" <?php if($S_city == 'Cavite'){ echo 'selected'; } ?>>Cavite</option>
                                    <option value="Taguig" <?php if($S_city == 'Taguig'){ echo 'selected'; } ?>>Taguig</option>
                                    <option value="Manila" <?php if($S_city == 'Manila'){ echo 'selected'; } ?>>Manila</option>
                                    <option value="Davao" <?php if($S_city == 'Davao'){ echo 'selected'; } ?>>Davao</option>
                                    <option value="Muntinlupa" <?php if($S_city == 'Muntinlupa'){ echo 'selected'; } ?>>Muntinlupa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-block" style="border-radius: 5px !important;" name="submit" value="Update Billing">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<hr/><br><br>