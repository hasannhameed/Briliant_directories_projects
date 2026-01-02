<?
//print_r($_POST);
if($_POST['action']=="buy_now")
{
$_SESSION['buy_products']=$_POST['gid'];
$_SESSION['buy_products_qty']=1;
echo "success";
die();
}
else if($_POST['action']=="check_user_email")
{
$ex=mysql($w['database'],"select * from users_data where email = '".$_POST['email']."'");
$_SESSION['cart_products_email']=$_POST['email'];
if(mysql_num_rows($ex)>0) $exist=1;
else $exist=0;
echo $exist;
die();
}else if($_POST['action']=="buy_action")
{ 
$data=array();
if($_COOKIE['userid']=="")
{
$salt = substr(hash("md5",$w['website_id']."qmzpalvt193764"), -22);
$_POST['pass'] = crypt($_POST['pass'], '$2a$11$'.$salt);



mysql($w['database'],"insert into users_data set first_name='".$_POST['billing_address_first_name']."',last_name='".$_POST['billing_address_last_name']."',email='".$_POST['email']."',subscription_id=7,filename='pro/".date("Ymdhis")."',password='".$_POST['pass']."',active=2,token='".md5($_POST['billing_address_first_name'].'pro/'.date("Ymdhis"))."',listing_type='Individual'");
$user_id=mysql_insert_id();
$w['user_email']=$_POST['email'];
$email = prepareEmail('ecommerce-company-account', $w);
sendEmailTemplate( $email[sender],$_POST['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
}
else{
$user_id=$_COOKIE['userid'];
}
if($_POST['same_address']==1)
$same_addr=1;else $same_addr=0;

if($_POST['action_type']=="")
{

$rq=mysql($w['database'],"select * from users_portfolio_groups where group_id = '".$_SESSION['buy_products']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);

$iq=mysql($w['database'],"select * from users_portfolio where group_id=$res[group_id]");
$imageFile=mysql_fetch_assoc($iq);
$qty=$_SESSION['buy_products_qty'];
if($res['ship_type']=="all")
{
$tot_ship_price=$res['car_post_shipping_price'];
$tot_ship=$res['car_post_shipping'];
}
else if($res['ship_type']=="per")
{
	if($qty<=10)
	{
	$tot_ship_price=$res["car_post_shipping_price_$qty"];
	$tot_ship=$res["car_post_shipping_$qty"];
	}
	else{
		$tot_ship_price=$res["car_post_shipping_price_10"];
	$tot_ship=$res["car_post_shipping_10"];
		}
}
$res['imagefile']= $imageFile['file'];
$data['group_id']=$res['group_id'];
$data['user_id']=$res['user_id'];
$data['group_name']=$res['group_name'];
$data['group_token']=$res['group_token'];
$data['group_filename']=$res['group_filename'];
$data['car_post_type']=$res['car_post_type'];
$data['property_price']=$res['now_price'];
$data['cat']=$res['cat'];
$data['post_promo']=$res['post_promo'];
$data['Manufacturer']=$res['Manufacturer'];
$data['Model_Year']=$res['Model_Year'];
$data['Capacity_(cc)']=$res['Capacity_(cc)'];
$data['start_type']=$res['start_type'];
$data['Mileage']=$res['Mileage'];
if($_POST['ship_type']=="ship")
{
$data['car_post_shipping']=$tot_ship;
$data['car_post_shipping_price']=$tot_ship_price;
}else
{
$data['car_post_shipping']=0;
$data['car_post_shipping_price']=0;
}
$data['car_post_quantity']=$res['car_post_quantity'];
$data['car_post_deliver_service']=$res['car_post_deliver_service'];
$data['car_post_estimated_delivery_time']=$res['car_post_estimated_delivery_time'];
$data['shop_type']=$res['shop_type'];
$data['ship']=$_POST['ship_type'];
$data['post_location']=$res['post_location'];
$data['imagefile']=$res['imagefile'];
$data['quantity']=$_SESSION['buy_products_qty'];
$data['product_ship_type']=$_POST['ship_type'];


mysql($w['database'],"insert into car_online_orders set purchased_user='".$user_id."',user_id='".$res['user_id']."',payment_status=0,deliver_status=0,date=now(),same_address='".$same_addr."',billing_address_first_name='".$_POST['billing_address_first_name']."',billing_address_last_name='".$_POST['billing_address_last_name']."',billing_address_email='".$_POST['email']."',billing_address_house_number='".$_POST['billing_address_house_number']."',billing_address_pincode='".$_POST['billing_address_pincode']."',billing_address_locality='".$_POST['billing_address_locality']."',billing_address_street='".$_POST['billing_address_street']."',billing_address_town='".$_POST['billing_address_town']."',billing_address_county ='".$_POST['billing_address_county']."',billing_address_country ='".$_POST['billing_address_country']."',shipping_address_pincode='".$_POST['shipping_address_pincode']."',shipping_address_locality='".$_POST['shipping_address_locality']."',shipping_address_street='".$_POST['shipping_address_street ']."',shipping_address_town='".$_POST['shipping_address_town']."',shipping_address_county='".$_POST['shipping_address_county']."',shipping_address_house_number='".$_POST['shipping_address_house_number']."',shipping_address_country='".$_POST['shipping_address_country']."',ship_type='".$_POST['ship_type']."'");

$ins_id=mysql_insert_id();
mysql($w['database'],"insert into car_order_items set order_id='".$ins_id."',product_data='".addslashes(serialize($data))."',product_id=$res[group_id],ship_type='".$_POST['ship_type']."'");
}
else if($_POST['action_type']=='cart')
{
$post_user=array();
foreach($_SESSION['add_to_cart_products'] as $se)
{
$post_user[]=$se['user_id'];
}
mysql($w['database'],"insert into car_online_orders set purchased_user='".$user_id."',user_id='".implode(",",$post_user)."',payment_status=0,deliver_status=0,date=now(),same_address='".$same_addr."',billing_address_first_name='".$_POST['billing_address_first_name']."',billing_address_last_name='".$_POST['billing_address_last_name']."',billing_address_email='".$_POST['email']."',billing_address_house_number='".$_POST['billing_address_house_number']."',billing_address_pincode='".$_POST['billing_address_pincode']."',billing_address_locality='".$_POST['billing_address_locality']."',billing_address_street='".$_POST['billing_address_street']."',billing_address_town='".$_POST['billing_address_town']."',billing_address_county ='".$_POST['billing_address_county']."',billing_address_country ='".$_POST['billing_address_country']."',shipping_address_pincode='".$_POST['shipping_address_pincode']."',shipping_address_locality='".$_POST['shipping_address_locality']."',shipping_address_street='".$_POST['shipping_address_street ']."',shipping_address_town='".$_POST['shipping_address_town']."',shipping_address_county='".$_POST['shipping_address_county']."',shipping_address_house_number='".$_POST['shipping_address_house_number']."',shipping_address_country='".$_POST['shipping_address_country']."'");

$ins_id=mysql_insert_id();
foreach($_SESSION['add_to_cart_products'] as $se)
{
$rq=mysql($w['database'],"select * from users_portfolio_groups where group_id = '".$se['gid']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$qty=$se['product_quantity'];
if($res['ship_type']=="all")
{
$tot_ship_price=$res['car_post_shipping_price'];
$tot_ship=$res['car_post_shipping'];
}
else if($res['ship_type']=="per")
{
	if($qty<=10)
	{
	$tot_ship_price=$res["car_post_shipping_price_$qty"];
	$tot_ship=$res["car_post_shipping_$qty"];
	}
	else{
		$tot_ship_price=$res["car_post_shipping_price_10"];
		$tot_ship=$res["car_post_shipping_10"];
		}
}
$iq=mysql($w['database'],"select * from users_portfolio where group_id=$res[group_id]");
$imageFile=mysql_fetch_assoc($iq);
$res['imagefile']= $imageFile['file'];
$res['quantity']=$se['product_quantity'];
$data['group_id']=$res['group_id'];
$data['user_id']=$res['user_id'];
$data['group_name']=$res['group_name'];
$data['group_token']=$res['group_token'];
$data['group_filename']=$res['group_filename'];
$data['car_post_type']=$res['car_post_type'];
$data['property_price']=$res['now_price'];
$data['cat']=$res['cat'];
$data['post_promo']=$res['post_promo'];
$data['Manufacturer']=$res['Manufacturer'];
$data['Model_Year']=$res['Model_Year'];
$data['Capacity_(cc)']=$res['Capacity_(cc)'];
$data['start_type']=$res['start_type'];
$data['Mileage']=$res['Mileage'];
$data['product_ship_type']=$_POST['ship_type_'.$res['group_id']];

if($_POST['ship_type_'.$res['group_id']]=="ship")
{
$data['car_post_shipping']=$tot_ship;
$data['car_post_shipping_price']=$tot_ship_price;
}else
{
$data['car_post_shipping']=0;
$data['car_post_shipping_price']=0;
}

$data['car_post_quantity']=$res['car_post_quantity'];
$data['car_post_deliver_service']=$res['car_post_deliver_service'];
$data['car_post_estimated_delivery_time']=$res['car_post_estimated_delivery_time'];
$data['shop_type']=$res['shop_type'];
$data['ship']=$_POST['ship_type'];
$data['post_location']=$res['post_location'];
$data['imagefile']=$res['imagefile'];
$data['quantity']=$res['quantity'];
mysql($w['database'],"insert into car_order_items set order_id='".$ins_id."',product_data='".addslashes(serialize($data))."',product_id=$res[group_id],ship_type='".$_POST['ship_type_'.$res['group_id']]."'");
}
}



header("location: /confirmorder?ord=$ins_id&type=$_POST[action_type]");
die();


}else if($_POST['action']=="confirm_buy")
{
$rq=mysql($w['database'],"select * from users_portfolio_groups where group_id = '".$_SESSION['buy_products']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$usr=getUser($res['user_id'],$w);

?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="buy_now_paypal_form">
<!-- Identify your business so that you can collect the payments. -->
<input type="hidden" name="business" value="<?=$usr['paypal_email']?>">

<!-- Specify a Buy Now button. -->
<input type="hidden" name="cmd" value="_xclick">

<!-- Specify details about the item that buyers will purchase. -->
<input type="hidden" name="item_name" value="<?=$res['group_name']?>">
<input type="hidden" name="item_number" value="<?=$_POST['order_id']?>">
<input type="hidden" name="amount" value="<?=$res['now_price']+$res['car_post_shipping_price']?>">
<input type="hidden" name="currency_code" value="<?=$w['currency_code']?>">

<!-- Specify URLs -->

<input type='hidden' name='return' value='<?="http://".$w['website_url']?>/api/widget/html/get/ecommerce_pay_success?order_id=<?=$_POST['order_id']?>'>
<input type="hidden" name="cancel_return" value="<?="http://".$w['website_url']?>/order_cancel">
</form>
<script>
var form=document.getElementById("buy_now_paypal_form");
form.submit();
</script>
<?


}
else if($_POST['action']=="cart_buy")
{
	$st=mysql($w['database'],"select * from admin_paypal_settings");
	$str=mysql_fetch_assoc($st);
	$od=mysql($w['database'],"select * from car_online_orders where order_id=$_POST[order_id]");
	$odr=mysql_fetch_assoc($od);
	$or=mysql($w['database'],"select * from car_order_items where order_id=$_POST[order_id]");
	 $tot=0;
	while($orr=mysql_fetch_assoc($or))
	{
		$pro=array();
		$pro=unserialize($orr['product_data']);
		$usr=getUser($pro['user_id'],$w);
	  
	   
		$tot+=($pro['property_price']*$pro['quantity'])+$pro['car_post_shipping_price'];
		 
	}
	/*$com_charge=0;
	$items=array();
	while($orr=mysql_fetch_assoc($or))
	{
		$pro=array();
		$pro=unserialize($orr['product_data']);
		$usr=getUser($pro['user_id'],$w);
	  
	   
		$tot=($pro['property_price']*$pro['quantity'])+$pro['car_post_shipping_price'];
		$com_charge1=($tot*$str['comission'])/100;
		$com_charge+=$com_charge1;
		$receiverAmount = $tot-$com_charge1; 
		$items[]=array("recipient_type"=>"EMAIL","amount"=>array("value"=>$receiverAmount,"currency"=>$w['currency_code']),"note"=>"Thanks For Choosing ".$w['website_name'],"sender_item_id"=>"order_".$_POST['order_id'],"receiver"=>$usr['paypal_email']);
		
	}
	$items[]=array("recipient_type"=>"EMAIL","amount"=>array("value"=>$com_charge,"currency"=>$w['currency_code']),"note"=>"Thanks For Choosing ".$w['website_name'],"sender_item_id"=>"order_".$_POST['order_id'],"receiver"=>$str['email']);
	
	$users=array("sender_batch_header"=>array("sender_batch_id"=>"payouts_".date("Y")."_".$_POST['order_id'],"email_subject"=>"You Have a Payment","email_message"=>"You have received a payout! Thanks for using our service!"),"items"=>$items);
	print_r($users);
	$result=makePayment($w,$users,2);
	print_r($result);
	die();*/?>
    
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="buy_now_paypal_form">
	<!-- Identify your business so that you can collect the payments. -->
	<input type="hidden" name="business" value="<?=$str['email']?>">
	
	<!-- Specify a Buy Now button. -->
	<input type="hidden" name="cmd" value="_xclick">
	
	<!-- Specify details about the item that buyers will purchase. -->
	<input type="hidden" name="item_name" value="<?=$_POST['order_id']."_Order"?>">
	<input type="hidden" name="item_number" value="<?=$_POST['order_id']?>">
	<input type="hidden" name="amount" value="<?=$tot?>">
	<input type="hidden" name="currency_code" value="<?=$w['currency_code']?>">
	
	<!-- Specify URLs -->
	
	<input type='hidden' name='return' value='<?="http://".$w['website_url']?>/api/widget/html/get/ecommerce_pay_success?or=<?=$_POST['order_id']?>&type=cart'>
	<input type="hidden" name="cancel_return" value="<?="http://".$w['website_url']?>/order_cancel">
	</form>
	<script>
	var form=document.getElementById("buy_now_paypal_form");
	form.submit();
	</script><?
}
else if($_POST['action']=="add_to_cart")
{
$rq=mysql($w['database'],"select * from users_portfolio_groups where group_id = '".$_POST['gid']."'");
$rqr=mysql_fetch_assoc($rq); 
$rqr=getMetaData("users_portfolio_groups",$rqr['group_id'],$rqr,$w);
$tot=0;
$rqr['shop_type']=explode(",",$rqr['shop_type']);
if(in_array("ship",$rqr['shop_type']))
$shop_type="ship";
else if(in_array("pick",$rqr['shop_type']) && sizeof($rqr['shop_type']==1))
$shop_type="pick";
$qty=$_SESSION['add_to_cart_products'][$_POST['gid']]['product_quantity']+1;
$_SESSION['add_to_cart_products'][$_POST['gid']]=array("gid"=>$_POST['gid'],"user_id"=>$rqr['user_id'],"product_quantity"=>$qty,"shop_type"=>$shop_type);
 

foreach($_SESSION['add_to_cart_products'] as $se)
{
$tot+=$se['product_quantity'];
}
echo $tot;
die();
}else if($_POST['action']=='inc_cart')
{
$_SESSION['add_to_cart_products'][$_POST['gid']]['product_quantity']=$_POST['qty'];
$tot=0;
foreach($_SESSION['add_to_cart_products'] as $se)
{
$tot+=$se['product_quantity'];
}
echo $tot;
die();
}else if($_POST['action']=='update_deliver')
{
mysql($w['database'],"update car_online_orders set deliver_status='".$_POST['status']."' where order_id=$_POST[order_id]");
$or=mysql($w['database'],"select * from car_online_orders where order_id=$_POST[order_id]");
$orr=mysql_fetch_assoc($or);
$puser=getUser($orr['purchased_user'],$w);
$w['user_first_name']="<a href='".$w['website_url']."/".$puser['filename']."' target='_blank'>".$puser['full_name']."</a>";
$w['order_id']=$orr['order_id'];
$w['order_on']=date("d/m/Y",strtotime($orr['date']));
$w['action']="Seller";
if($_POST['status']==0)
$w['status']="In Progress";
else if($_POST['status']==1)
$w['status']="Shipped";
else if($_POST['status']==2)
$w['status']="Delivered";
$sell=array();
$uu=array_unique(explode(",",$orr['user_id']));
foreach($uu as $su)
{
$suser=getUser($su,$w);
$sell[]="<a href='".$w['website_url']."/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a>";
}
$w['user_nm']=implode(",",$sell);
$pr=mysql($w['database'],"select * from car_order_items where order_id=$_POST[order_id]");
$prd=array();
while($prr=mysql_fetch_assoc($pr))
{
$it=unserialize($prr['product_data']);
$prd[]="<a href='".$w['website_url']."/".$it['group_filename']."' target='_blank'>".$it['group_name']."</a>";
}
$w['products']=implode(",",$prd);
$email = prepareEmail('order_shipping_email', $w);
sendEmailTemplate( $email[sender],$puser['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
foreach($uu as $su)
{
$suser=getUser($su,$w);
$w['user_first_name']="<a href='".$w['website_url']."/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a>";
$w['action']="Buyer";
$w['user_nm']="<a href='".$w['website_url']."/".$puser['filename']."' target='_blank'>".$puser['full_name']."</a>";
$email = prepareEmail('order_shipping_email', $w);
sendEmailTemplate( $email[sender],$suser['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
}
echo "success";
die();
}else if($_POST['action']=="search_category"){
$cat=mysql($w['database'],"select * from car_categories where category_id=$_POST[category_id]");
$catr=mysql_fetch_assoc($cat);
$cstr="<option value=''>%%%all_categories_label%%%</option>";
$all=explode(",",$catr['feature_categories']);
foreach($all as $a)
{
$cstr.="<option value='".trim($a)."'>".trim($a)."</option>";
}
echo $cstr;
die();
}
else if($_POST['action']=="buy_now_inc")
{
	$_SESSION['buy_products_qty']=$_POST['qty'];
	die();
}
else if($_POST['action']=="change_ship_cart")
{
	$_SESSION['add_to_cart_products'][$_POST['gid']]['shop_type']=$_POST['type'];
	die();
}
?>



