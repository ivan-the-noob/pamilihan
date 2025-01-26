<?php
include_once("header.php");
if(isset($_GET['t'])){
    if($_GET['t'] == "myAccount"){
        include_once('my-account.php');
    }else if($_GET['t'] == "myOrder"){
        include_once('my-order.php');
    }else if($_GET['t'] == "changePassword"){
        include_once('change-password.php');
    }else{
        header("Location: index.php");
        exit;
    }
}else{
    header("Location: index.php");
    exit;
}
include_once("footer.php");
?>
<script>
    $(document).ready(function(){
        $(document).on('click', '.myOrderMenu', function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('.myOrderMenu').css({ "border-bottom": 'none' });
            if(name == "allOrders"){
                $(this).css({ "border-bottom": "1px solid black"});
                $('.viewPurchase').html('<center><div class="m-5 h-100"><img src="assets/uploads/loading.gif" style="width: 50%; height: 50%;"/></div></center>');
                setTimeout(function(){
                    $('.viewPurchase').load('all-orders.php');
                }, 750);
                $(document).on('click', '.cancelOrder', function(e){
                    e.preventDefault();
                    var dataOrder = $(this).data("order");
                    if(confirm("Are you sure you want to cancel your order?")){
                        $.ajax({
                            url:"action.php",
                            method:"POST",
                            data:{"order_id":dataOrder, "cancelMyOrder":true},
                            dataType:"HTML",
                            success:function(data){
                                if(data == "success"){
                                    $('.viewPurchase').html('<center><div class="m-5 h-100"><img src="assets/uploads/loading.gif" style="width: 50%; height: 50%;"/></div></center>');
                                    setTimeout(function(){
                                        $('.viewPurchase').load('all-orders.php');
                                    }, 750);
                                }
                            }
                        });
                    }
                });
            }
            if(name == "allProcessing"){
                $(this).css({ "border-bottom": "1px solid black"});
                $('.viewPurchase').html('<center><div class="m-5 h-100"><img src="assets/uploads/loading.gif" style="width: 50%; height: 50%;"/></div></center>');
                setTimeout(function(){
                    $('.viewPurchase').load('all-processing.php');
                }, 750);
                $(document).on('click', '.cancelOrder', function(e){
                    e.preventDefault();
                    var dataOrder = $(this).data("order");
                    if(confirm("Are you sure you want to cancel your order?")){
                        $.ajax({
                            url:"action.php",
                            method:"POST",
                            data:{"order_id":dataOrder, "cancelMyOrder":true},
                            dataType:"HTML",
                            success:function(data){
                                if(data == "success"){
                                    $('.viewPurchase').html('<center><div class="m-5 h-100"><img src="assets/uploads/loading.gif" style="width: 50%; height: 50%;"/></div></center>');
                                    setTimeout(function(){
                                        $('.viewPurchase').load('all-orders.php');
                                    }, 750);
                                }
                            }
                        });
                    }
                });
            }
            if(name == "allShipped"){
                $(this).css({ "border-bottom": "1px solid black"});
                $('.viewPurchase').html('<center><div class="m-5 h-100"><img src="assets/uploads/loading.gif" style="width: 50%; height: 50%;"/></div></center>');
                setTimeout(function(){
                    $('.viewPurchase').load('all-shipped.php');
                }, 750);
            }
        });
        $(document).on('change', '#sameAsShipping', function(e){
            e.preventDefault();
            var changeStatus = $(this).is(':checked');
            if(changeStatus == true){
                $('#updateShipping #f_name').val($('#updateBilling #f_name').val());
                $('#updateShipping #phone_no').val($('#updateBilling #phone_no').val());
                $('#updateShipping #address').val($('#updateBilling #address').val());
                $('#updateShipping #Brgy').val($('#updateBilling #Brgy').val());
                $('#sameAsShipping').val(true);
            }else{
                $('#sameAsShipping').val(false);
                // do nothing
            }
            console.log($(this).val());
        });

        $(document).on('submit', '#updateAccount', function(e){
            e.preventDefault();

            var formData = $(this).serialize()+'&update=updateAccount';

            $.ajax({
                url:"action.php",
                method:"POST",
                data:formData,
                dataType:"JSON",
                success:function(response){
                    if(response.error != ""){
                        // If error
                        $('.accountMessage').html('<div class="alert alert-danger"><span>'+response.error+'</span></div>');
                    }else{
                        $('.accountMessage').html('<div class="alert alert-success"><span>'+response.success+'</span></div>');
                    }
                    setTimeout(function(){
                        $('.accountMessage').html('');
                        window.location.reload();
                    }, 2000);
                    if(response.backToLogin != ""){
                        alert(response.error);
                        window.location.href="login.php";
                    }
                }
            });
        });

        $(document).on('submit', '#updateBilling', function(e){
            e.preventDefault();

            var formData = $(this).serialize()+'&update=updateBilling';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:formData,
                dataType:"JSON",
                success:function(response){
                    if(response.error != ""){
                        // If error
                        $('.billingMessage').html('<div class="alert alert-danger"><span>'+response.error+'</span></div>');
                    }else{
                        $('.billingMessage').html('<div class="alert alert-success"><span>'+response.success+'</span></div>');
                    }
                    setTimeout(function(){
                        $('.billingMessage').html('');
                        window.location.reload();
                    }, 2000);
                    if(response.backToLogin != ""){
                        alert(response.error);
                        window.location.href="login.php";
                    }
                }
            });
        });

        let lat = null;

        let lon = null;

        function getUserLocationAndAddress() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        lat = position.coords.latitude;
                        lon = position.coords.longitude;

                        // Call function to get address from latitude and longitude
                        getAddressFromLatLng(lat, lon);
                    },
                    (error) => {
                        console.error("Error getting user location:", error.message);
                    },
                    { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                );
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }

        function getAddressFromLatLng(lat, lon) {
            const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`;

            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    if (data && data.address) {
                        const address = data.display_name; // Full address
                        console.log("Retrieved Address:", address);

                        $('#sAddress').val(address);
                        // Optionally display the address in your app
                        document.getElementById("address").innerText = `Address: ${address}`;
                    } else {
                        console.error("Address not found for the given coordinates.");
                    }
                })
                .catch((error) => {
                    console.error("Error during reverse geocoding:", error);
                });
        }
        getUserLocationAndAddress();

        $(document).on('submit', '#updateShipping', function(e){
            e.preventDefault();

            var formData = $(this).serialize()+'&update=updateShipping';

            var sAddress = $('#sAddress').val();
            var Brgy = $('#Brgy').val();
            var country = "Philippines";
            const address = `${sAddress}, ${Brgy}, Cavite Noveleta, ${country}`;

            formData += `&destLat=${lat}&destLng=${lon}`;

            $.ajax({
                url: "action.php",
                method: "POST",
                data: formData,
                dataType: "JSON",
                success: function (response) {
                    if (response.error) {
                        // If error
                        $('.shippingMessage').html('<div class="alert alert-danger"><span>' + response.error + '</span></div>');
                    } else {
                        $('.shippingMessage').html('<div class="alert alert-success"><span>' + response.success + '</span></div>');
                    }
                    setTimeout(function () {
                        $('.shippingMessage').html('');
                        if (response.backToLogin) {
                            alert(response.error);
                            window.location.href = "login.php";
                        } else {
                            window.location.reload();
                        }
                    }, 2000);
                }
            });

        });

        $(document).on('submit', '#changePassword', function(e){
            e.preventDefault();

            var formData = $(this).serialize()+'&changePassword=true&u_id=<?= $_SESSION['customer']['id']; ?>';

            $.ajax({
                url:"action.php",
                method:"POST",
                data:formData,
                dataType:"JSON",
                success:function(response){
                    if(response.error != ""){
                        if(response.for == ".message"){
                            $('.message').html('<div class="alert alert-danger"><span class="danger">'+response.error+'</span></div>');
                            $('#new_pass').css({ outline: "1px solid red"});
                            $('#confirm_new_pass').css({ outline: "1px solid red"});
                        }
                        if(response.for == ".messageCurrentPass"){
                            $('.messageCurrentPass').html('<div class="text text-danger"><span>'+response.error+'</span></div>');
                            $('#current_pass').css({ outline: "1px solid red"});
                        }
                        if(response.for == ".correct"){
                            $('.messageCurrentPass').html('');
                            $('#current_pass').css({ outline: "none"});
                        }
                    }else{
                        $('.messageCurrentPass').html('');
                        $('.message').html('');
                        $('#current_pass').css({ outline: "none"});
                        $('#new_pass').css({ outline: "none"});
                        $('#confirm_new_pass').css({ outline: "none"});
                        $('.message').html('<div class="alert alert-success"><span class="success">'+response.success+'</span></div>');
                        $('#current_pass').val('');
                        $('#new_pass').val('');
                        $('#confirm_new_pass').val('');
                    }

                    $(document).on('keyup', '#current_pass', function(e){
                        e.preventDefault();
                        $(this).css({ outline: "none"});
                        $('.messageCurrentPass').html('');
                        $('.message').html('');
                    });

                    $(document).on('keyup', '#new_pass', function(e){
                        e.preventDefault();
                        $(this).css({ outline: "none"});
                        $('#confirm_new_pass').css({ outline: "none"});
                        $('.messageCurrentPass').html('');
                        $('.message').html('');
                    });

                    $(document).on('keyup', '#confirm_new_pass', function(e){
                        e.preventDefault();
                        $(this).css({ outline: "none"});
                        $('#new_pass').css({ outline: "none"});
                        $('.messageCurrentPass').html('');
                        $('.message').html('');
                    });
                }
            });
        });
    });
</script>