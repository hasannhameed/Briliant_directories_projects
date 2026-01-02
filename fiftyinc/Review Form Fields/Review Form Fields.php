<?
if(in_array("writeareview",$pars)){
$user_one=getUser($_COOKIE[userid],$w); ?>
<div class="form-group">
<label class="vertical-label" for="member_review-element-15">Enter Your Name or Company</label>
<? if($user_one['company']!=""){ ?>
<input type="text" name="review_name" id="rev_name" value="<?=$user_one['company']?>" class="form-control" readonly>

<? } else{ ?>
<input type="text" name="review_name" id="rev_name" value="<?=$user_one['first_name']." ".$user_one['last_name']?>" class="form-control" readonly>
<? } ?>
</div>
<div class="form-group">
<label class="vertical-label" for="member_review-element-16">Enter Email Address</label>
<input type="email" name="review_email" id="rev_email" value="<?=$user_one['email']?>" class="form-control" readonly>
</div>
<? if($_GET['ptoken']!=""){
$review_token=md5(uniqid(rand(), true));
?>
<input type="hidden" name="port_token" value="<?=$_GET['ptoken']?>">
<input type="hidden" name="review_token" value="<?=$review_token?>">
<? } 
}else{?>
<div class="control-group">
<label class="control-label" for="myform-element-5"><span class="required">* </span>Enter Your Name or Company</label>
<div class="controls">
<input type="text" name="review_name" required="" autocomplete="off" value="FindHotels" class="form-control">
</div>
</div>
<div class="control-group">
<label class="control-label" for="myform-element-6"><span class="required">* </span>Enter Email Address</label>
<div class="controls"><input type="email" name="review_email" required="" autocomplete="off" value="editor@findhotels.pro" class="form-control">
</div>
</div>
<? } ?>	