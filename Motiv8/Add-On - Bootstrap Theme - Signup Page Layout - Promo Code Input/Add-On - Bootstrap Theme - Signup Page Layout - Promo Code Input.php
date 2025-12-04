<?php

/**
 * This widget follows BD code standards
 * Widget Name: Add-On - Bootstrap Theme - Signup Page Layout - Promo Code Input
 * Short Description: Add on that shows the coupon code entry box and the order summary for signup and upgrade pages
 * Version: 0.2
 * Developed By: Matthew Brooks
 * Collaborators: Jason H.
 */

if (addonController::isAddonActive('promo_codes') === true) { ?>
<div class="promo-code-module">
	<div class="clearfix"></div>
	<div class="col-md-6 nolpad sm-norpad sm-bmargin promo-code-group">
		<div class="well nomargin nobpad form-group promo-code-input">
			<label>%%%have_promo_code_label%%%</label>
			<div class="input-group">
				<input name="refcode" value="<?php echo $_REQUEST['refcode']?>" placeholder="<?php echo $label['enter_code_here_label'];?>" autocomplete="off" class="form-control " data-fv-field="refcode" type="text">
				<span class="input-group-btn">
					<button class="btn btn-success" type="button">%%%apply_label%%%</button>
				</span>
			</div>
			<small class="help-block refcode-block" data-fv-validator="remote" data-fv-for="refcode" data-fv-result="NOT_VALIDATED"></small>
		</div>
	</div>

	<div class="col-md-6 norpad sm-nolpad order-summ-module">
		<div class="well nomargin" id="ordersumm">
			<div class="ordersummarytitle">%%%order_summary_label%%%</div>
		</div>
		<textarea id="rawordersummary" style="display:none;"></textarea>
	</div>
	<div class="clearfix clearfix-lg"></div>
	<div class="alert alert-primary" role="alert" id="promo-code-alert-message" style="display: none;">
	</div>
	<div class="clearfix clearfix-lg"></div>
</div>
<?php } ?>