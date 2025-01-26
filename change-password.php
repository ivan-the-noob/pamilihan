<?php
if(isset($_SESSION['customer'])){
    $prevPassword = "";

    $sql = "SELECT * FROM tbl_user WHERE id=:u_id";
    $p = [
        ":u_id"     =>  $_SESSION['customer']['id']
    ];
    $res = $c->fetchData($pdo, $sql, $p);
    if($res == true){
        foreach($res as $row){
            $prevPassword = $row['password'];
        }
    }
}
?>
<section class="mt-4 mb-4">
    <div class="container">
        <h4>Change Password</h4>
        <hr/>
        <div class="row justify-content-center">
            <div class="col-xl-7 ftco-animate">
                <form id="changePassword" method="POST">
                    <div class="message">
                    </div>
                    <div class="row align-items-end">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Current Password</label>
                                <small class="messageCurrentPass"></small>
                                <input type="password" class="form-control" minlength="6" name="current_pass" id="current_pass" placeholder="Enter your current password...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">New Password</label>
                                <input type="password" class="form-control" minlength="6" name="new_pass" id="new_pass" placeholder="Enter your new password...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Confirm New Password</label>
                                <input type="password" class="form-control" minlength="6" name="confirm_new_pass" id="confirm_new_pass" placeholder="Enter your confirm new password...">
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-primary px-4 py-2" style="border-radius: 5px !important;" value="Save">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<hr/><br><br>