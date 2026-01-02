<? 
if(isset($_POST['save']))
{
	mysql($w['database'],"delete from admin_paypal_settings");
	mysql($w['database'],"insert into admin_paypal_settings set email='".$_POST['email']."',user_name='".$_POST['user_name']."',password='".$_POST['password']."',signature='".$_POST['signature']."',appid='".$_POST['appid']."',comission='".$_POST['comission']."',secret_key='".$_POST[secret_key]."'");
}

$st=mysql($w['database'],"select * from admin_paypal_settings");
$str=mysql_fetch_assoc($st);
?>
<form method="post" action="">
<h3 class="text-center bold bmargin">Paypal Admin Settings</h3>
<div class="row tmargin">
<div class="col-md-6 col-md-offset-3">
<div class="well">
<div class="form-group">
<label>Paypal Email</label>
<input type="text" name="email" placeholder="paypal email" class="form-control" value="<?=$str['email']?>"/>
</div>
<div class="form-group">
<label>Paypal User Name</label>
<input type="text" name="user_name" placeholder="paypal user name" class="form-control" value="<?=$str['user_name']?>"/>
</div>
<div class="form-group">
<label>Paypal Password</label>
<input type="text" name="password" placeholder="paypal password" class="form-control" value="<?=$str['password']?>"/>
</div>
<div class="form-group">
<label>Signature (Client Id)</label>
<input type="text" name="signature" placeholder="paypal signature" class="form-control" value="<?=$str['signature']?>"/>
</div>
	<div class="form-group">
<label>Secret Key</label>
<input type="text" name="secret_key" placeholder="paypal Secret Key" class="form-control" value="<?=$str['secret_key']?>"/>
</div>
	<div class="form-group">
<label>Comission</label>
<input type="text" name="comission" placeholder="paypal Comission" class="form-control" value="<?=$str['comission']?>"/>in Percent
</div>
	<div class="form-group">
<label>APP Id</label>
<input type="text" name="appid" placeholder="paypal APP Id" class="form-control" value="<?=$str['appid']?>"/>
</div>
</div>
</div>
</div>
<div class="text-center"><input type="submit" name="save" value="Save"  class="btn btn-primary btn-sm"/></div>
</form>