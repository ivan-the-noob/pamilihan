		</div>

	</div>

	<script src="js/jquery-2.2.4.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/select2.full.min.js"></script>
	<script src="js/jquery.inputmask.js"></script>
	<script src="js/jquery.inputmask.date.extensions.js"></script>
	<script src="js/jquery.inputmask.extensions.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/icheck.min.js"></script>
	<script src="js/fastclick.js"></script>
	<script src="js/jquery.sparkline.min.js"></script>
	<script src="js/jquery.slimscroll.min.js"></script>
	<script src="js/jquery.fancybox.pack.js"></script>
	<script src="js/app.min.js"></script>
	<script src="js/jscolor.js"></script>
	<script src="js/on-off-switch.js"></script>
    <script src="js/on-off-switch-onload.js"></script>
    <script src="js/clipboard.min.js"></script>
	<script src="js/demo.js"></script>
	<script src="js/summernote.js"></script>

	<script>
		$(document).ready(function() {
	        $('#editor1').summernote({
	        	height: 300
	        });
	        $('#editor2').summernote({
	        	height: 300
	        });
	        $('#editor3').summernote({
	        	height: 300
	        });
	        $('#editor4').summernote({
	        	height: 300
	        });
	        $('#editor5').summernote({
	        	height: 300
	        });
	    });
		$(".top-cat").on('change',function(){
			var id=$(this).val();
			var dataString = 'id='+ id;
			$.ajax
			({
				type: "POST",
				url: "get-mid-category.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(".mid-cat").html(html);
				}
			});			
		});
		$(".mid-cat").on('change',function(){
			var id=$(this).val();
			var dataString = 'id='+ id;
			$.ajax
			({
				type: "POST",
				url: "get-end-category.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(".end-cat").html(html);
				}
			});			
		});
	</script>

	<script>
		// for expiration
		$(document).ready(function(){
			$('.p_w_confirm').on('change', function(e){
				e.preventDefault();
				var myVal = $(this).val();
				if(myVal == 0){
					$('.nearly_expiration').removeAttr('required');
					$('.nearly_expiration').attr('disabled', 'disabled');
				}else{
					$('.nearly_expiration').removeAttr('disabled');
					$('.nearly_expiration').attr('required', 'true');
				}
			});
		});
	</script>

	<script>
	  $(function () {

	    //Initialize Select2 Elements
	    $(".select2").select2();

	    //Datemask dd/mm/yyyy
	    $("#datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
	    //Datemask2 mm/dd/yyyy
	    $("#datemask2").inputmask("mm-dd-yyyy", {"placeholder": "mm-dd-yyyy"});
	    //Money Euro
	    $("[data-mask]").inputmask();

	    //Date picker
	    $('#datepicker').datepicker({
	      autoclose: true,
	      format: 'dd-mm-yyyy',
	      todayBtn: 'linked',
	    });

	    $('#datepicker1').datepicker({
	      autoclose: true,
	      format: 'dd-mm-yyyy',
	      todayBtn: 'linked',
	    });

	    //iCheck for checkbox and radio inputs
	    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	      checkboxClass: 'icheckbox_minimal-blue',
	      radioClass: 'iradio_minimal-blue'
	    });
	    //Red color scheme for iCheck
	    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
	      checkboxClass: 'icheckbox_minimal-red',
	      radioClass: 'iradio_minimal-red'
	    });
	    //Flat red color scheme for iCheck
	    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	      checkboxClass: 'icheckbox_flat-green',
	      radioClass: 'iradio_flat-green'
	    });



	    $("#example1").DataTable();
	    $('#example2').DataTable({
	      "paging": true,
	      "lengthChange": false,
	      "searching": false,
	      "ordering": true,
	      "info": true,
	      "autoWidth": false
	    });

	    $('#confirm-delete').on('show.bs.modal', function(e) {
	      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	    });
		
		$('#confirm-approve').on('show.bs.modal', function(e) {
	      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	    });
 
	  });

		function confirmDelete()
	    {
	        return confirm("Are you sure want to delete this data?");
	    }
	    function confirmActive()
	    {
	        return confirm("Are you sure want to Active?");
	    }
	    function confirmInactive()
	    {
	        return confirm("Are you sure want to Inactive?");
	    }

	</script>

	<script type="text/javascript">
		function showDiv(elem){
			if(elem.value == 0) {
		      	document.getElementById('photo_div').style.display = "none";
		      	document.getElementById('icon_div').style.display = "none";
		   	}
		   	if(elem.value == 1) {
		      	document.getElementById('photo_div').style.display = "block";
		      	document.getElementById('photo_div_existing').style.display = "block";
		      	document.getElementById('icon_div').style.display = "none";
		   	}
		   	if(elem.value == 2) {
		      	document.getElementById('photo_div').style.display = "none";
		      	document.getElementById('photo_div_existing').style.display = "none";
		      	document.getElementById('icon_div').style.display = "block";
		   	}
		}
		function showContentInputArea(elem){
		   if(elem.value == 'Full Width Page Layout') {
		      	document.getElementById('showPageContent').style.display = "block";
		   } else {
		   		document.getElementById('showPageContent').style.display = "none";
		   }
		}
	</script>

	<script type="text/javascript">

        $(document).ready(function () {

            $("#btnAddNew").click(function () {

		        var rowNumber = $("#ProductTable tbody tr").length;

		        var trNew = "";              

		        var addLink = "<div class=\"upload-btn" + rowNumber + "\"><input type=\"file\" name=\"photo[]\"  style=\"margin-bottom:5px;\"></div>";
		           
		        var deleteRow = "<a href=\"javascript:void()\" class=\"Delete btn btn-danger btn-xs\">X</a>";

		        trNew = trNew + "<tr> ";

		        trNew += "<td>" + addLink + "</td>";
		        trNew += "<td style=\"width:28px;\">" + deleteRow + "</td>";

		        trNew = trNew + " </tr>";

		        $("#ProductTable tbody").append(trNew);

		    });

		    $('#ProductTable').delegate('a.Delete', 'click', function () {
		        $(this).parent().parent().fadeOut('slow').remove();
		        return false;
		    });

			// Add new product row
			$(document).on('click', '#btnAddNewProduct', function () {
				let newRow = `
					<div class="recipe-product">
						<div class="col-md-4" style="margin-top: 5px;">
							<select name="product[]" class="form-control select2 product" required>
								<option value="">Select a product</option>
								<?php
								$sql = "SELECT p.*, seller.business_title FROM tbl_product p LEFT JOIN tbl_seller seller ON p.u_id=seller.user_id ORDER BY id DESC";
								$res = $c->fetchData($pdo, $sql);
								if($res){
									foreach($res as $row101){
										?>
										<option value="<?= $row101['p_id'];?>"><?= $row101['p_name'];?> <small>(<?= $row101['business_title']; ?>)</small></option>
										<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-md-3" style="margin-top: 5px;">
							<select name="size[]" class="form-control select2 size" required>
								<option value="">Select size</option>
								<?php
								$sql = "SELECT * FROM tbl_size ORDER BY size_name DESC";
								$res = $c->fetchData($pdo, $sql);
								if($res){
									foreach($res as $row10){
										?>
										<option value="<?= $row10['size_id'];?>"><?= $row10['size_name'];?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-md-4" style="margin-top: 5px;">
							<div class="row">
								<div class="col-md-3">
									<button class="btn btn-default btn-xl minusQuantity">-</button>
								</div>
								<div class="col-md-6">
									<input type="text" name="quantity[]" class="form-control quantity text-center" placeholder="Quantity..." min="1" max="50" value="1" readonly>
								</div>
								<div class="col-md-3">
									<button class="btn btn-default btn-xl plusQuantity">+</button>
								</div>
							</div>
						</div>
						<div class="col-md-1" style="margin-top: 5px;">
							<button type="button" class="btn btn-danger btn-xs delete-row" style="display: block; margin-top: 5px;">X</button>
						</div>
					</div>`;
				$('#product-list').append(newRow);
			});

			// Delete product row
			$(document).on('click', '.delete-row', function () {
				$(this).closest('.recipe-product').remove();
			});

			// Increase & Decrease Quantity
			$(document).on('click', '.minusQuantity', function (e) {
				e.preventDefault();
				let quantityInput = $(this).closest('.row').find('.quantity');
				let currentValue = parseInt(quantityInput.val()) || 1;
				if (currentValue > 1) { // Ensure minimum value is 1
					quantityInput.val(currentValue - 1);
				}
			});

			$(document).on('click', '.plusQuantity', function (e) {
				e.preventDefault();
				let quantityInput = $(this).closest('.row').find('.quantity');
				let currentValue = parseInt(quantityInput.val()) || 1;
				let maxValue = parseInt(quantityInput.attr('max')) || 50;
				if (currentValue < maxValue) {
					quantityInput.val(currentValue + 1);
				}
			});

        });



        var items = [];
        for( i=1; i<=24; i++ ) {
        	items[i] = document.getElementById("tabField"+i);
        }

		items[1].style.display = 'block';
		items[2].style.display = 'block';
		items[3].style.display = 'block';
		items[4].style.display = 'none';

		items[5].style.display = 'block';
		items[6].style.display = 'block';
		items[7].style.display = 'block';
		items[8].style.display = 'none';

		items[9].style.display = 'block';
		items[10].style.display = 'block';
		items[11].style.display = 'block';
		items[12].style.display = 'none';

		items[13].style.display = 'block';
		items[14].style.display = 'block';
		items[15].style.display = 'block';
		items[16].style.display = 'none';

		items[17].style.display = 'block';
		items[18].style.display = 'block';
		items[19].style.display = 'block';
		items[20].style.display = 'none';

		items[21].style.display = 'block';
		items[22].style.display = 'block';
		items[23].style.display = 'block';
		items[24].style.display = 'none';

		function funcTab1(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[1].style.display = 'block';
		       	items[2].style.display = 'block';
		       	items[3].style.display = 'block';
		       	items[4].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[1].style.display = 'none';
		       	items[2].style.display = 'none';
		       	items[3].style.display = 'none';
		       	items[4].style.display = 'block';
			}
		};

		function funcTab2(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[5].style.display = 'block';
		       	items[6].style.display = 'block';
		       	items[7].style.display = 'block';
		       	items[8].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[5].style.display = 'none';
		       	items[6].style.display = 'none';
		       	items[7].style.display = 'none';
		       	items[8].style.display = 'block';
			}
		};

		function funcTab3(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[9].style.display = 'block';
		       	items[10].style.display = 'block';
		       	items[11].style.display = 'block';
		       	items[12].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[9].style.display = 'none';
		       	items[10].style.display = 'none';
		       	items[11].style.display = 'none';
		       	items[12].style.display = 'block';
			}
		};

		function funcTab4(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[13].style.display = 'block';
		       	items[14].style.display = 'block';
		       	items[15].style.display = 'block';
		       	items[16].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[13].style.display = 'none';
		       	items[14].style.display = 'none';
		       	items[15].style.display = 'none';
		       	items[16].style.display = 'block';
			}
		};

		function funcTab5(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[17].style.display = 'block';
		       	items[18].style.display = 'block';
		       	items[19].style.display = 'block';
		       	items[20].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[17].style.display = 'none';
		       	items[18].style.display = 'none';
		       	items[19].style.display = 'none';
		       	items[20].style.display = 'block';
			}
		};

		function funcTab6(elem) {
			var txt = elem.value;
			if(txt == 'Image Advertisement') {
				items[21].style.display = 'block';
		       	items[22].style.display = 'block';
		       	items[23].style.display = 'block';
		       	items[24].style.display = 'none';
			} 
			if(txt == 'Adsense Code') {
				items[21].style.display = 'none';
		       	items[22].style.display = 'none';
		       	items[23].style.display = 'none';
		       	items[24].style.display = 'block';
			}
		};



        
    </script>
<!-- FOR MESSAGES -->
<script>
	let userNameOfCustomer = '';
	let myName = '';
	let currentUser = '';

	// Establish a WebSocket connection
    const ws = new WebSocket('ws://localhost:8080/ws');

    ws.onopen = function () {
        console.log('WebSocket connection established');
    };

    ws.onmessage = function (event) {
        // $.ajax({
        //     url: 'server.php',
        //     method: 'POST',
        //     data: { 'viewMessage': true },
        //     dataType: 'JSON',
        //     success: function(data) {
        //         const messagesDiv = document.getElementById('messages');

        //         // avoid duplicates
        //         messagesDiv.innerHTML = '';

        //         data.forEach(message => {
        //             if (message.from_user === '<?php //$_SESSION['user']; ?>') {
        //                 messagesDiv.innerHTML += `<div class="message from-me">${message.from_user}: ${message.message}</div>`;
        //             } else {
        //                 messagesDiv.innerHTML += `<div class="message from-them">${message.from_user}: ${message.message}</div>`;
        //             }
        //         });

        //         messagesDiv.scrollTop = messagesDiv.scrollHeight;
        //     }
        // });
    };

    ws.onclose = function () {
        // console.log('WebSocket connection closed');
    };

    ws.onerror = function (error) {
        // console.error('WebSocket error:', error);
    };
	
	const message = {};
	$(document).ready(function(){
		$(document).on('click', '.showMessage', function(e){
			e.preventDefault();
			$('#chatUserName').html($(this).data('user'));
			currentUser = 'User1';
			myName = $(this).data('rider');
			messages = {
				'User1': [{ text: "Hello! How are you?", from: 'User1' }, { text: "I'm good, thanks! How about you?", from: 'Me' }],
			};
			document.getElementById('input-container').style.display = 'block';
			displayMessages(currentUser);

			$(document).on('submit', '#sendaMessage', function(e){
				e.preventDefault();
				const input = document.getElementById('messageInput');
				const text = input.value.trim();
				if (text) {
					if (!messages[currentUser]) messages[currentUser] = [];
					messages[currentUser].push({ text, from: 'Me' });
					displayMessages(currentUser);
					input.value = '';
				}
			});
		});
	});

	function displayMessages(userName) {
		const messagesContainer = document.getElementById('messages');
		messagesContainer.innerHTML = '';
		(messages[userName] || []).forEach(message => {
			const messageBox = document.createElement('div');
			messageBox.className = 'message-box ' + (message.from === 'Me' ? 'message-right' : 'message-left');
			messageBox.textContent = message.text;
			messagesContainer.appendChild(messageBox);
		});
		messagesContainer.scrollTop = messagesContainer.scrollHeight;
	}
</script>
</body>
</html>