<script>

	function updatesummary() {

		$("#ordersumm").html("<div class='ordersummarytitle'><?php echo $label['order_summary_label']; ?></div><img src='/directory/cdn/images/bars-loading.gif' style='width:40px;margin-left:39%;'>");
		setTimeout(function() {

			<?php
			//// If statement if the request comes from the signup or the upgrade form
			if ($pars[1]=="upgrade") {
				$subpid = getSubscription($pars[2],$w);
			} else {	
				$subpid = getSubscription($pars[1],$w);
			
			}
			?>
			
			if ($("select[name=country_code]").val() == "UK") {
				var userid = 11;	
			} else { 
				var userid = 1;
			}
			<? if ($user['clientid'] > 0) { ?>
				var userid = <?php echo $user['clientid']; ?>
			<?php } ?>
			
			var pid="<?php echo $subpid['pid'];?>";
			var billingcycle='';
			if (!$('input[name="member_type"]:checked').val()) { var billingcycle=''; } else {
				var billingcycle=$('input[name="member_type"]:checked').val();
				if (billingcycle=="yearly") { billingcycle="Annually"; }
				else if (billingcycle=="monthly") { billingcycle="Monthly"; }
				else if (billingcycle=="quarterly") { billingcycle="Quarterly"; }
				else if (billingcycle=="semiyearly") { billingcycle="Semi Annually"; }
				else if (billingcycle=="onetime") { billingcycle="One Time"; }
			}
			if (!$('input[name="refcode"]').val()) {
				var promocode='dfdf';
			} else {
				var promocode=$('input[name="refcode"]').val();
			}
			var varsw="userid="+userid+"&pid[]="+pid+"&billingcycle[]="+billingcycle+"&qty[]=1&promocode="+promocode+"&adminauth=addorder&widget=WHMCS%20-%20AdminAuth&apitype=html";

	    	$.get("/api/widget/html/summary/WHMCS%20-%20AdminAuth", varsw,function(data){
				<?php if(brilliantDirectories::isStripePaymentGateway() === true){ ?>
				$("#rawordersummary").html(data);
				<?php } ?>
				data = data.replace("Total", "<?php echo $label['promo_code_total']?>");
				data = data.replace("Subtotal", "<?php echo $label['promo_code_subtotal']?>");
				data = data.replace("Order Summary", "<?php echo $label['order_summary_label']?>");
				data = data.replace("Promo Discount", "<?php echo $label['promo_discount_label']?>");
		        $("#ordersumm").html(data);
				
	    	});
		}, 100);
	}
	updatesummary();

$("input[name='refcode']").keydown(function(event) {

    $(this).val($(this).val().replace(/ +?/g, ''));
	if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
});
$("input[name='refcode']").change(function(event) {


		////if ($(this).val()=="") {
			$(this).val($(this).val().replace(/ +?/g, ''));
				$("#promo-code-alert-message").html("").hide();
				$.ajax({
                    url: '/api/widget/json/addpromocode/Bootstrap%20Theme%20-%20Function%20-%20Form%20Promotion%20Code%20Validation',
                    type: "GET",
					data: { "refcode": $(this).val(),
						   "pid":<?php echo $subpid['pid'];?>
						   <?php if ($pars[1]=="upgrade") { echo ',"upgrade_form": 1'; } ?> },
                    dataType: "json",
                    success: function(data){
						if (data['valid']==true) {
							$(".promo-code-input").addClass("has-success").removeClass("has-error");
							$(".refcode-block").html(data['message']).show();
							$("#promo-code-alert-message").html(data['discount_text']).show();
							updatesummary();

						} else {
							$(".promo-code-input").addClass("has-error").removeClass("has-success");
							setTimeout(function() {
								$("input[name='refcode']").val('');
								updatesummary();
								$(".promo-code-input").removeClass("has-success").removeClass("has-error");
								$(".refcode-block").html("<?php echo $label['enter_valid_promo']?>").fadeIn();
								}, 500);
							$(".refcode-block").html(data['message']).show();

						}
					}
			});
		///} else {

		///	$(".promo-code-input").removeClass("has-success").removeClass("has-error");
		///	$(".refcode-block").html("<?php echo $label['enter_valid_promo']?>").show();
		///}
	});

	$('input[name="member_type"]').click(function(event) {
	updatesummary();
});
	$("#country-chained").change(function () {
                updatesummary();
            });
</script>