<? if($_GET['type']==""){
	?>

<div class="row">
<div class="" >
<div class="carts">
 <h3 class='pro-header text-center'>Cart Details</h3>
<table class="cart_table">
<thead>
<th colspan="4">Product</th>
<th>Quantity</th>
<th style="text-align:right">Price</th>
</thead>
<tbody>
<?

$rq=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$_SESSION['buy_products']."'");
$res=mysql_fetch_assoc($rq);
	//print_r($res);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);

$iq=mysql($w['database'],"select * from users_portfolio where group_id=$res[group_id]");
$imageFile=mysql_fetch_assoc($iq);
$allship=array();
 $qty=$_SESSION['buy_products_qty'];
if($res['ship_type']=="all")
$tot_ship=$res['car_post_shipping_price'];
else if($res['ship_type']=="per")
{
	if($qty<=10)
	$tot_ship=$res["car_post_shipping_price_$qty"];
	else
	$tot_ship=$res['car_post_shipping_price_10'];
	$allship=array();
	$allship[1]=$res['car_post_shipping_price_1'];
	$allship[2]=$res['car_post_shipping_price_2'];
	$allship[3]=$res['car_post_shipping_price_3'];
	$allship[4]=$res['car_post_shipping_price_4'];
	$allship[5]=$res['car_post_shipping_price_5'];
	$allship[6]=$res['car_post_shipping_price_6'];
	$allship[7]=$res['car_post_shipping_price_7'];
	$allship[8]=$res['car_post_shipping_price_8'];
	$allship[9]=$res['car_post_shipping_price_9'];
	$allship[10]=$res['car_post_shipping_price_10'];
}
	$tot_ship=$res['car_post_shipping_price'];
$allship=json_encode($allship);
$res['shop_type']=explode(",",$res['shop_type']);
	//echo $res['property_price'];
	//echo $qty;
	//echo $tot_ship;
?>
<tr><?php if($imageFile['file']!=""){?><td style="min-width: 10%;"><img src="<?="/photos/display/".$imageFile['file']?>"/></td><? }?>
<td style="min-width: 60%;" colspan="<?php if($imageFile['file']!=""){echo "3";}else echo "4";?>"><div><?=$res['group_name']?></div>
<? if(in_array("ship",$res['shop_type'])){ 
	if($res['car_post_estimated_delivery_time']=="Onetwodays"){
		 $newdel="1-2 business days";
	 }
	 else  if($res['car_post_estimated_delivery_time']=="Threefourdays"){
		 $newdel="3-4 business days";
	 }?>
<div style="font-size:12px"><label style="font-size:12px">Estimated Delivery Time:</label> <?=$newdel?></div>
<div style="font-size:12px"><label style="font-size:12px">Service:</label><?=$res['car_post_deliver_service']?></div>
<? }  if(in_array("pick",$res['shop_type'])){?>
<div style="font-size:12px"><label style="font-size:12px">Pick Up Location:</label><?=$res['post_location']?></div>
<? }?>
</td>
<td><select data-gid="<?=$res['group_id']?>" data-tot="<?=$res['now_price']?>" class="form-control pro_quantity">
	<? for($i=1;$i<=$res['car_post_quantity'];$i++){?>
	<option value="<?=$i?>"  <? if($i==$qty) echo "selected"; ?>><?=$i?></option>
	<? }?>
</select></td>
<td style="text-align:right" class="p_promo" data-promo="<?=$res['now_price']?>" id="post_promo_<?=$res['group_id']?>"><?=$w['currency_symbol'].(number_format($res['now_price']*$qty))?></td>
</tr>

<tr><td colspan="5" class="text-right">Shipping:</td><td  class="text-right p_ship" data-ship="<?=$tot_ship?>"><? if($res['ship_type']=="all"){ if($res['car_post_shipping']!="" || $res['car_post_shipping']>0) echo $res['car_post_shipping']; else echo "Free Shipping"; }else if($res['ship_type']=="per"){echo $res["car_post_shipping_$qty"];}?></td></tr>
<tr><td colspan="5" class="text-right">Grand Total:</td><td class="text-right" id="grand_total"><?=$w['currency_symbol'].number_format(($res['now_price']*$qty)+$tot_ship,2)?></td></tr>

</tbody>
</table>
</div>
</div>
</div>
<script>
var cur_sym="<?=$w['currency_symbol']?>";
var allship=JSON.parse('<?=$allship?>');
console.log(allship);
var ship_type="<?=$res['ship_type']?>";
$(".pro_quantity").change(function(e){
		var ele=$(this);
		$(".cart_loading").show();
		$.ajax({
		
							type: "POST",		
							url: "/api/widget/html/get/ecommerce_ajax_actions",		
							data: {'action':"buy_now_inc",'qty':ele.val()},		
							cache: false,		
							success: function (response) {
		 var tot=parseFloat(ele.data('tot'))*parseFloat(ele.val());
								 var gtot=0;
								 $("#post_promo_"+ele.data('gid')).attr("data-promo",tot);
								 $("#post_promo_"+ele.data('gid')).html(cur_sym+numberFormatter(tot));
								
								 $(".pro_quantity").each(function(index,element){
									
									 	var ele1=$(this);
										gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));

										
									 });
									 var ship=0;
									 if(ship_type=="per")
									 {
									 if(parseFloat(ele.val())<=10)
									 ship=parseFloat(allship[ele.val()]);
									 else
									 ship=parseFloat(allship[10]);
									 }else if(ship_type=="all")
									 {
										 ship=$(".p_ship").data("ship");
									 }
									 $(".p_ship").attr("data-ship",ship);
									  $(".p_ship").html("<?=$w['currency_symbol']?>"+numberFormatter(ship));
									 gtot+=parseFloat(ship);
								 $('#grand_total').html(cur_sym+numberFormatter(gtot));
								 $(".cart_loading").hide();
							}
		});
	});
function numberFormatter(x)
{
   
return parseFloat(x).toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>
<? } else if($_GET['type']=='cart'){
	
		if($_GET['rmv']!='')
{
	unset($_SESSION['add_to_cart_products'][$_GET['rmv']]);
	
	if(sizeof($_SESSION['add_to_cart_products'])==0)
	{
		unset($_SESSION['add_to_cart_products']);
	}
}

	?>
<div class="row">
<div class="">
<div class="carts">
 <h3 class='pro-header text-center'>Cart Details</h3>
<table class="cart_table">
<thead><th></th>
<th colspan="4">Product</th>
<th>Delivery Options</th>
<th>Quantity</th>
<th style="text-align:right">Price</th>
</thead>
<tbody>
<?
if(sizeof($_SESSION['add_to_cart_products'])>0){
	$allship=array();
	$total=0;
	$tot_ship=0;
foreach($_SESSION['add_to_cart_products'] as $se)
{
	

$rq=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$se['gid']."'");
$res=mysql_fetch_assoc($rq);
	//print_r($res);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$res['shop_type']=explode(",",$res['shop_type']);
$iq=mysql($w['database'],"select * from users_portfolio where group_id=$res[group_id]");
$imageFile=mysql_fetch_assoc($iq);
$tot_ship_cal=0;

if($res['ship_type']=="all")
$tot_ship_cal+=$res['car_post_shipping_price'];
else if($res['ship_type']=="per")
{
	if($se['product_quantity']<=10)
	$tot_ship_cal+=$res["car_post_shipping_price_".$se['product_quantity']];
	else
	$tot_ship_cal+=$res["car_post_shipping_price_10"];
 
	$allship[$res['group_id']]['ship'][1]=$res['car_post_shipping_price_1'];
	$allship[$res['group_id']]['ship'][2]=$res['car_post_shipping_price_2'];
	$allship[$res['group_id']]['ship'][3]=$res['car_post_shipping_price_3'];
	$allship[$res['group_id']]['ship'][4]=$res['car_post_shipping_price_4'];
	$allship[$res['group_id']]['ship'][5]=$res['car_post_shipping_price_5'];
	$allship[$res['group_id']]['ship'][6]=$res['car_post_shipping_price_6'];
	$allship[$res['group_id']]['ship'][7]=$res['car_post_shipping_price_7'];
	$allship[$res['group_id']]['ship'][8]=$res['car_post_shipping_price_8'];
	$allship[$res['group_id']]['ship'][9]=$res['car_post_shipping_price_9'];
	$allship[$res['group_id']]['ship'][10]=$res['car_post_shipping_price_10'];
	
}
$allship[$res['group_id']]['ship_type']=$res['ship_type'];
$in_ship=$tot_ship_cal;
if($se['shop_type']=="ship")
{
$total+=(($res['now_price']*$se['product_quantity'])+$tot_ship_cal);
$tot_ship+=$tot_ship_cal;
}
else
$total+=(($res['now_price']*$se['product_quantity']));

  
?>
<tr><td style="vertical-align:middle"><a href="cartaction?rmv=<?=$res['group_id']?>&type=cart"  class="rmv_btn btn btn-warning btn-sm"><i class="fa fa-times" aria-hidden="true"></i></a></td><?php if($imageFile['file']!=""){?><td style="min-width: 10%;"><img src="<?="/photos/display/".$imageFile['file']?>"/></td><? }?>
<td style="min-width: 60%;" colspan="<?php if($imageFile['file']!=""){ echo "3";}else echo "4";?>"><div><?=$res['group_name']?></div>
<? if(in_array("ship",$res['shop_type'])){ ?>
<div style="font-size:12px"><label style="font-size:12px">Estimated Delivery Time:</label><?=$res['car_post_estimated_delivery_time']?></div>
<div style="font-size:12px"><label style="font-size:12px">Service:</label><?=$res['car_post_deliver_service']?></div>
<? }else if(in_array("pick",$res['shop_type'])){?>
<div style="font-size:12px"><label style="font-size:12px">Pick Up Location:</label><?=$res['post_location']?></div>
<? }?>
</td>
<td><? if(in_array("ship",$res['shop_type'])){?><div class="radio">
<label><input type="radio" name="ship_select_<?=$res['group_id']?>" data-ship="<?=$in_ship?>"   data-tship="<?=$in_ship?>"  data-groupid="<?=$res['group_id']?>" <? if($se['shop_type']=="ship") echo "checked"; ?> class="ship_select ship_row" value="ship"/>Ship</label>
</div><?    }if(in_array("pick",$res['shop_type'])){?>
<div class="radio">
<label><input type="radio"  data-groupid="<?=$res['group_id']?>" data-ship="<?=$in_ship?>"  data-tship="<?=$in_ship?>"  name="ship_select_<?=$res['group_id']?>" <? if($se['shop_type']=="pick") echo "checked"; ?> class="ship_select" value="pick"/>Pick Up</label>
</div>
<? }?></td>
<td><select data-gid="<?=$res['group_id']?>" data-tot="<?=$res['now_price']?>" data-tship="<?=$in_ship?>" class="form-control pro_quantity">
	<? for($i=1;$i<=$res['car_post_quantity'];$i++){?>
	<option value="<?=$i?>" <? if($se['product_quantity']==$i) echo "selected"; ?>><?=$i?></option>
	<? }?>
</select></td>
<td style="text-align:right" class="p_promo" data-promo="<?=$res['now_price']?>" id="post_promo_<?=$res['group_id']?>"><?=$w['currency_symbol'].(number_format($res['now_price']*$se['product_quantity'],2))?></td>
</tr>
<? }?>

<tr><td colspan="7" class="text-right">Shipping:</td><td class="text-right p_ship" data-ship="<?=$tot_ship?>"><? if($tot_ship!="" || $tot_ship>0) echo number_format($tot_ship,2); else echo "Free Shipping"; ?></td></tr>
<tr><td colspan="7" class="text-right">Grand Total:</td><td class="text-right" id="grand_total"><?=$w['currency_symbol'].number_format($total,2)?></td></tr>
<? }else {?>
<tr><td colspan="7" class="text-center">No products in the cart.</td></tr>
<? }$allship=json_encode($allship); ?>
</tbody>
</table>
</div>
</div>
</div>
<script>
var cur_sym="<?=$w['currency_symbol']?>";
var allship=JSON.parse('<?=$allship?>');
console.log(allship);
$(".pro_quantity").change(function(e){
		var ele=$(this);
			$.ajax({
		
							type: "POST",		
							url: "/api/widget/html/get/ecommerce_ajax_actions",		
							data: {'action':"inc_cart",'gid':ele.data('gid'),'qty':ele.val()},		
							cache: false,		
							success: function (response) {
								
								
								 var tot=parseFloat(ele.data('tot'))*parseFloat(ele.val());
								 var gtot=0;
								 $("#post_promo_"+ele.data('gid')).attr("data-promo",tot);
								 $("#post_promo_"+ele.data('gid')).html(cur_sym+numberFormatter(tot));
									 var ship=0; 
									 var tship=0;
									 
								 $(".pro_quantity").each(function(index,element){
									
									 	var ele1=$(this);
										console.log(ele1);
										var ship_type=$("input[name=ship_select_"+ele1.data('gid')+"]:checked").val();
										gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));
										 
										 
										  
										   if(allship[ele1.data('gid')]['ship_type']=="per")
											 {
												 
											 if(parseFloat(ele1.val())<=10)
											 {
												 if(ship_type=="ship") tship+=parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]);
											 ship+=parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]);
											 $(ele1).attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]));
											 $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-ship",allship[ele1.data('gid')]['ship'][ele1.val()]);
											 $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-tship",allship[ele1.data('gid')]['ship'][ele1.val()]);
											 }
											 else
											 {
												  if(ship_type=="ship") tship+=parseFloat(allship[ele1.data('gid')]['ship'][10]);
											 ship+=parseFloat(allship[ele1.data('gid')]['ship'][10]);
											$(ele1).attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][10]));
											 $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-ship",allship[ele1.data('gid')]['ship'][10]);
											 $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-tship",allship[ele1.data('gid')]['ship'][10]);
											 }
											 
											 
											 }else if(allship[ele1.data('gid')]['ship_type']=="all")
											 {
												  if(ship_type=="ship") tship+=parseFloat($(ele1).attr("data-tship"));
												 ship+=parseFloat($(ele1).attr("data-tship"));
											 }
										 
										   
										
										 
										
									 });
								
							 
											
											
									$(".p_ship").attr("data-ship",tship);
									$(".p_ship").attr("data-tship",tship);   
									
									  $(".p_ship").html("<?=$w['currency_symbol']?>"+numberFormatter(tship));
									 gtot+=parseFloat(tship);
										  
								 $('#grand_total').html(cur_sym+numberFormatter(gtot));
							 $(".cart-div").find('span').text(response);
								 
							}
			});
	});
	$(".ship_select").on("change",function(){
			var ele=$(this);
		$(".cart_loading").show();
				$.ajax({
		
							type: "POST",		
							url: "/api/widget/html/get/ecommerce_ajax_actions",		
							data: {'action':"change_ship_cart",'gid':ele.data('groupid'),'type':ele.val()},		
							cache: false,		
							success: function (response) {
									var tot_ship=parseFloat($(".p_ship").attr("data-ship"));
									 
									if(ele.val()=="pick")
									{
									tot_ship=tot_ship-parseFloat(ele.attr("data-tship"));
									}
									else
									{
									tot_ship=tot_ship+parseFloat(ele.attr("data-tship"));
									}
									$(".p_ship").html(cur_sym+numberFormatter(tot_ship));
									$(".p_ship").attr("data-ship",tot_ship);
									 var gtot=0;
									  $(".pro_quantity").each(function(index,element){
									
									 	var ele1=$(this); 
										gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));
									  });
									  gtot+=tot_ship;
									 $('#grand_total').html(cur_sym+numberFormatter(gtot));
									 $(".cart_loading").hide();
							}
				});
		});
function numberFormatter(x)
{
   
return parseFloat(x).toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>

<? }?>

<div class="row tmargin">
<form method="post" action="/api/widget/json/get/Bootstrap Theme - Member Login Page" form_action_type="redirect" id="member_login1">
<div class="col-md-8 col-md-offset-2">
<div class="carts">
<div class="form-group">
<label>Email</label>
<input type="email" required class="form-control user_email" name="email"  placeholder="name@yoursite.com" />
<div class="email_span"></div>
 </div>
<div class="form-group password_section" style="display:none">
<label>Create a password</label>
<input type="password" placeholder="Enter Password" required name="pass" class="form-control user_password"  />
<span style=" " class="help-block bpad tmargin  bmargin ">
<p>You are an existing Local Bulls User please enter your password created at sign-up to continue or</p>
<p>Forgot your password? <a href="/login/retrieval" target="_blank">Click Here</a></p></span>
</div>
<input type="hidden" name="action" value="loggedout">
<input type="hidden" name="sized" value="0">
<input type="hidden" name="form" value="myform">
<input type="hidden" name="formname" value="member_login">
<input type="hidden" name="dowiz" value="1" >
<input type="hidden" name="save" value="1">
<input type="hidden" name="action" autocomplete="off" value="login" >
<div class="text-center check_email"><button class="btn btn-primary btn-sm check_user">Next >></button></div>
<div class="text-center check_password" style="display:none"><input type="submit" class="btn btn-primary btn-sm check_user_pass" value="Next >>" /></div>

</div>
</div>
</form>

</div>
<script>
var type="<?=$_GET['type']?>";
var checked_email="";
$(document).ready(function(e) {
	$(".user_email").blur(function(){
		if(checked_email!=$(".user_email").val())
		{
			$(".check_email").show();
			$(".password_section").hide();
			$(".check_password").hide();
		}
		if($(".email_valid").length>0) $(".email_valid").remove();
		});
    $(".check_user").click(function(e){
	
		e.preventDefault();
		if($(".user_email").val()!="")
		{
			$.ajax({
		
							type: "POST",		
							url: "/api/widget/html/get/ecommerce_ajax_actions",		
							data: {'action':"check_user_email",'email':$(".user_email").val()},		
							cache: false,		
							success: function (response) {
								if(response==1)
								{
									$(".password_section").show();
									$(".check_email").hide();
									$(".check_password").show();
									checked_email=$(".user_email").val();
								}
								else
								{
									window.location.href="/cartactioncon?type="+type;
								}
							}
			});
		}
		else
		{
			$(".email_span").html("<span class='email_valid'>Please enter Email</span>");
		}
	});
	$(".check_user_pass").click(function(e){
		 $('#member_login1').formValidation({"framework":"bootstrap"}).on('success.form.fv', function(e,fvdata) {

                e.preventDefault();
                var $form = $(e.target);
                 var   fv = $form.data('formValidation');
                var values = $(this).serialize();
var form_action_type = $(this).attr("form_action_type");
              
                if ($("#member_login-notification").html() != "") {
                    $("#member_login-notification").remove();
                }
                if ($(this).find('input[type="submit"]').length > 0) {
                    $(this).find('input[type="submit"]').before('<div id="member_login-notification" class="alert"></div>');

                } else {
                    $(this).prepend('<div id="member_login-notification" class="alert"></div>');
                }
                var notification = $("#member_login-notification");

                if ((form_action_type == "" || form_action_type == "default") && action.indexOf("account") >= 0) {
                    notification.html('Processing Request...').addClass("alert-warning");
                    fv.defaultSubmit();
                } else {
                    notification.html('Processing Request...').addClass("alert-warning");
                   
                        $.ajax({
                            url: $(this).attr("action"),
                            type: $(this).attr("method"),
                            data: values,
                            dataType: "json",
                            success: function (data) {
								if(data.result=="success")
								{
									window.location.href="/cartactioncon?type="+type;
								}
								else if(data.result=="error")
								{
									 notification.html('You have signed into Local Bulls in the past with this email. The password you have entered is incorrect. Please try again or use forgot your password link.').addClass("alert-warning");
								}
								
							}
							});
						
					
				}
		 });
		 
		});
});

</script>
