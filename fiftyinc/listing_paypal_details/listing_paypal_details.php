<?
if(in_array(73,$subscription['data_settings']))
{
	?>

<div class="form-group">
  <div class="col-sm-3 text-right norpad">
    <label class="control-label bd-text">Paypal email:</label>
  </div>
  <div class="col-sm-9">
<input type="text" name="paypal_email" class="form-control" autocomplete="off" placeholder="paypal business email"  value="<?=$user['paypal_email']?>" /> <label>Note: Enable Auto Return In Your Paypal Account <a href="https://developer.paypal.com/docs/admin/checkout-settings/" target="_blank" style="text-decoration:underline">click here</a> for more info.</label> </div>
</div>

<?
}
?>
