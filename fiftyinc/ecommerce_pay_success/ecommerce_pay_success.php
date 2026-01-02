<?

function makePayment($w,$users,$mode)
{
	 
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=$mode");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	if($paypal_credentials_result['mode']=="live") $url="https://api.paypal.com/v1/payments/payouts";
	else $url="https://api.sandbox.paypal.com/v1/payments/payouts";
	$ch = curl_init();
 
	 
	
	curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode($users),
				CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".$paypal_credentials_result['access_token'],
				"content-type: application/json"
				),
				));
	$result = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch); 
		if(empty($result)){ print_r($err); die();}
	else
	{
		echo $result;
	}
	
	
}   
function generatePaypalAccessToken($w,$mode)
{
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=$mode");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	if($paypal_credentials_result['mode']=="live") $url="https://api.paypal.com/v1/oauth2/token";
	else $url="https://api.sandbox.paypal.com/v1/oauth2/token";
$ch = curl_init();
 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, "Accept: application/json, Accept-Language: en_US");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_SSLVERSION , 6); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $paypal_credentials_result['client_id'].":".$paypal_credentials_result['secret_key']);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
$result = curl_exec($ch);
$err = curl_error($ch);

if(empty($result)){ print_r($err); die();}
else
{
    $json = json_decode($result);
	$curdate=strtotime(date("Y-m-d H:i:s"));
 	$next=$curdate+$json->expires_in;
	mysql($w['database'],"update paypal_settings set access_token='".$json->access_token."',expires_in='".$json->expires_in."',expire_time='".date("Y-m-d H:i:s",$next)."',response='".serialize($json)."' where id=$mode");
    
}

curl_close($ch);
}
function orderEmailTemplate($order_id,$user_id,$user_type,$w)
{
	 	$total=0;
		$ship=0;
		$temp="<table border='0' cellpadding='6' cellspacing='6' style='font-weight: bold; background: #e3e3e3;'>
	<tbody>";
		$sqs=mysql($w['database'],"select * from car_order_items where 	order_id=$order_id");
		while($sqsr=mysql_fetch_assoc($sqs))
		{
			$pro=unserialize($sqsr['product_data']);
			if($user_type=="buyer")
			{
			$pro['shop_type']=explode(",",$pro['shop_type']);
			$suser=getUser($pro['user_id'],$w);
			$total+=$pro['property_price']*$pro['quantity'];
				//$total+=$pro['now_price']*$pro['quantity'];
			$ship+=$pro['car_post_shipping_price'];
			$temp.="<tr><td style='background: #ffffff;'><img src='http://localbulls.com/photos/display/".$pro['imagefile']."' alt='".$pro['group_name']."' /></td><td><span style='font-size: 14px;'>".$pro['group_name']."</span><br><span style='font-size: 14px;'>Price: ".$w['currency_symbol'].number_format($pro['property_price'],2)."</span><br><span style='font-size: 14px;'>Shipping Price: ".$w['currency_symbol'].number_format($pro['car_post_shipping_price'],2)."</span><br><span style='font-size: 14px;'>Quantity: ".$pro['quantity']."</span><br><span style='font-size: 14px;'>Seller: <a href='http://localbulls.com/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a></span><br>";
			if(in_array("ship",$pro['shop_type']))
			{
				if($res['car_post_estimated_delivery_time']=="Onetwodays"){
		 $newdel="1-2 business days";
	 }
	 else  if($res['car_post_estimated_delivery_time']=="Threefourdays"){
		 $newdel="3-4 business days";
	 }
			$temp.="<span style='font-size: 14px;'>Estimated Delivery Time: ".$newdel."</span><br><span style='font-size: 14px;'>Service: ".$pro['car_post_deliver_service']."</span>";
			}  if(in_array("pick",$pro['shop_type']))
			{
				$temp.="<span style='font-size: 14px;'>Pick Up Location: ".$pro['post_location']."</span>";
			}
			$temp.="</td></tr>";
			}
			else
			{
				if($user_id==$pro['user_id'])
				{
					$pro['shop_type']=explode(",",$pro['shop_type']);
					$suser=getUser($pro['user_id'],$w);
					$total+=$pro['property_price']*$pro['quantity'];
					$ship+=$pro['car_post_shipping_price'];
					$temp.="<tr><td style='background: #ffffff;'><img src='http://localbulls.com/photos/display/".$pro['imagefile']."' alt='".$pro['group_name']."' /></td><td><span style='font-size: 14px;'>".$pro['group_name']."</span><br><span style='font-size: 14px;'>Price: ".$w['currency_symbol'].number_format($pro['property_price'],2)."</span><br><span style='font-size: 14px;'>Shipping Price: ".$w['currency_symbol'].number_format($pro['car_post_shipping_price'],2)."</span><br><span style='font-size: 14px;'>Quantity: ".$pro['quantity']."</span><br><span style='font-size: 14px;'>Seller: <a href='http://localbulls.com/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a></span><br>";
					if(in_array("ship",$pro['shop_type']))
					{
					$temp.="<span style='font-size: 14px;'>Estimated Delivery Time: ".$pro['car_post_estimated_delivery_time']."</span><br><span style='font-size: 14px;'>Service: ".$pro['car_post_deliver_service']."</span>";
					}  if(in_array("pick",$pro['shop_type']))
					{
					$temp.="<span style='font-size: 14px;'>Pick Up Location: ".$pro['post_location']."</span>";
					}
					$temp.="</td></tr>";
				}
			}
			
		}
		$temp.="</tbody></table>";
		return json_encode(array("total"=>$total,"ship"=>$ship,"temp"=>$temp));
		
}
if($_GET['type']=="")
{
if($_GET['st']=="Completed" || $_GET['st']=="Pending")
	{
		$total=0;
		$ship=0;
		$temp="<table border='0' cellpadding='6' cellspacing='6' style='font-weight: bold; background: #e3e3e3;'>
	<tbody>";
		$sqs=mysql($w['database'],"select * from car_order_items where 	order_id=$_GET[item_number]");
		while($sqsr=mysql_fetch_assoc($sqs))
		{
			$pro=unserialize($sqsr['product_data']);
			$pro['shop_type']=explode(",",$pro['shop_type']);
			$suser=getUser($pro['user_id'],$w);
			$pro['car_post_quantity']=$pro['car_post_quantity']-1;
			$total+=$pro['property_price']*$pro['quantity'];
			$ship+=$pro['car_post_shipping_price'];
			mysql($w['database'],"update users_meta set value='".$pro['car_post_quantity']."' where `database`='users_portfolio_groups' and `database_id`='".$pro['group_id']."' and `key`='car_post_quantity'");
			$temp.="<tr><td style='background: #ffffff;'><img src='http://localbulls.com/photos/display/".$pro['imagefile']."' alt='".$pro['group_name']."' /></td><td><span style='font-size: 14px;'>".$pro['group_name']."</span><br><span style='font-size: 14px;'>Price: ".$w['currency_symbol'].number_format($pro['property_price'],2)."</span><br><span style='font-size: 14px;'>Shipping Price: ".$w['currency_symbol'].number_format($pro['car_post_shipping_price'],2)."</span><br><span style='font-size: 14px;'>Quantity: ".$pro['quantity']."</span><br><span style='font-size: 14px;'>Seller: <a href='http://localbulls.com/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a></span><br>";
			if(in_array("ship",$pro['shop_type'])){
			$temp.="<span style='font-size: 14px;'>Estimated Delivery Time: ".$pro['car_post_estimated_delivery_time']."</span><br><span style='font-size: 14px;'>Service: ".$pro['car_post_deliver_service']."</span>";
			} if(in_array("pick",$pro['shop_type']))
			{ 
			$temp.="<span style='font-size: 14px;'>Pick Up Location: ".$pro['post_location']."</span>" ;
			}
			$temp.="</td></tr>";
			
		}
		$temp.="</tbody></table>";
		$or=mysql($w['database'],"select * from	car_online_orders where order_id=$_GET[item_number]");
			$orr=mysql_fetch_assoc($or);
			$puser=getUser($orr['purchased_user'],$w);
			$w['user_first_name']=$puser['full_name'];
			$w['order_id']=$orr['order_id'];
			$w['order_on']=$orr['date'];
			$w['amount']=$w['currency_symbol'].number_format($total,2);
			$w['shipping_charge']=$w['currency_symbol'].number_format($ship,2);
			$w['total_amount']=$w['currency_symbol'].number_format($total+$ship,2);
			$w['table_temp']=$temp;
			
			$email = prepareEmail('thankyou-purchase', $w);
	sendEmailTemplate( $email[sender],$puser['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
	
	$uu=array_unique(explode(",",$orr['user_id']));
	foreach($uu as $su)
	{
		$suser1=getUser($su,$w);
		$w['user_first_name']=$suser1['full_name'];
		$email = prepareEmail('order-placed', $w);
	sendEmailTemplate( $email[sender],$suser1['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
		
	}
		mysql($w['database'],"update car_online_orders set payment_status=1,response='".serialize($_GET)."' where order_id=$_GET[item_number]");
		header("location: http://".$w['website_url']."/order_success");
	}
	else{
		$total=0;
		$ship=0;
		$temp="<table border='0' cellpadding='6' cellspacing='6' style='font-weight: bold; background: #e3e3e3;'>
	<tbody>";
		$sqs=mysql($w['database'],"select * from car_order_items where 	order_id=$_GET[order_id]");
		while($sqsr=mysql_fetch_assoc($sqs))
		{
			$pro=unserialize($sqsr['product_data']);
			$pro['shop_type']=explode(",",$pro['shop_type']);
			$suser=getUser($pro['user_id'],$w);
			$pro['car_post_quantity']=$pro['car_post_quantity']-1;
			$total+=$pro['property_price']*$pro['quantity'];
			$ship+=$pro['car_post_shipping_price'];
			mysql($w['database'],"update users_meta set value='".$pro['car_post_quantity']."' where `database`='users_portfolio_groups' and `database_id`='".$pro['group_id']."' and `key`='car_post_quantity'");

			$temp.="<tr><td style='background: #ffffff;'><img src='http://localbulls.com/photos/display/".$pro['imagefile']."' alt='".$pro['group_name']."' /></td><td><span style='font-size: 14px;'>".$pro['group_name']."</span><br><span style='font-size: 14px;'>Price: ".$w['currency_symbol'].number_format($pro['property_price'],2)."</span><br><span style='font-size: 14px;'>Shipping Price: ".$w['currency_symbol'].number_format($pro['car_post_shipping_price'],2)."</span><br><span style='font-size: 14px;'>Quantity: ".$pro['quantity']."</span><br><span style='font-size: 14px;'>Seller: <a href='http://localbulls.com/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a></span><br>";
			if(in_array("ship",$pro['shop_type']))
			{
			$temp.="<span style='font-size: 14px;'>Estimated Delivery Time: ".$pro['car_post_estimated_delivery_time']."</span><br><span style='font-size: 14px;'>Service: ".$pro['car_post_deliver_service']."</span>";
			}  if(in_array("pick",$pro['shop_type']))
			{
				$temp.="<span style='font-size: 14px;'>Pick Up Location: ".$pro['post_location']."</span>";
			}
			$temp.="</td></tr>";
			
		}
		$temp.="</tbody></table>";
		$or=mysql($w['database'],"select * from	car_online_orders where order_id=$_GET[order_id]");
			$orr=mysql_fetch_assoc($or);
			$puser=getUser($orr['purchased_user'],$w);
			$w['user_first_name']=$puser['full_name'];
			$w['order_id']=$orr['order_id'];
			$w['order_on']=$orr['date'];
			$w['amount']=$w['currency_symbol'].number_format($total,2);
			$w['shipping_charge']=$w['currency_symbol'].number_format($ship,2);
			$w['total_amount']=$w['currency_symbol'].number_format($total+$ship,2);
			$w['table_temp']=$temp;
			
			$email = prepareEmail('thankyou-purchase', $w);
	sendEmailTemplate( $email[sender],$puser['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
	
	$uu=array_unique(explode(",",$orr['user_id']));
	foreach($uu as $su)
	{
		$suser1=getUser($su,$w);
		$w['user_first_name']=$suser1['full_name'];
		$email = prepareEmail('order-placed', $w);
	sendEmailTemplate( $email[sender],$suser1['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);
		
	}
		mysql($w['database'],"update car_online_orders set payment_status=1,response='".serialize($_GET)."' where order_id=$_GET[order_id]");
		header("location: http://".$w['website_url']."/order_success");
	}

}else if($_GET['type']=='cart')
{
	 $st=mysql($w['database'],"select * from admin_paypal_settings");
	$str=mysql_fetch_assoc($st);
	$od=mysql($w['database'],"select * from car_online_orders where order_id=$_GET[or]");
	$odr=mysql_fetch_assoc($od);
	$or=mysql($w['database'],"select * from car_order_items where order_id=$_GET[or]");
	
	$com_charge=0;
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
		$items[]=array("recipient_type"=>"EMAIL","amount"=>array("value"=>number_format($receiverAmount,2),"currency"=>$w['currency_code']),"note"=>"Payments for your Orders on ".$w['website_name'],"sender_item_id"=>"order_".$usr['user_id']."_".$pro['group_id'].$_GET['or'],"receiver"=>$usr['paypal_email']);
		
	}
	
	$users=array("sender_batch_header"=>array("sender_batch_id"=>"payouts_".date("Y")."_".$_GET['or'],"email_subject"=>"You Have a Payment","email_message"=>"You have received a payout! Thanks for using our service!"),"items"=>$items);
 
	$paypal_credentials=mysql($w['database'],"select * from paypal_settings where id=2");
	$paypal_credentials_result=mysql_fetch_assoc($paypal_credentials);
	$curdate=time();
	$next=strtotime(date_format(date_create_from_format("Y-m-d H:i:s",$paypal_credentials_result['expire_time']),"Y-m-d H:i:s"));
	if($curdate>$next)
	{
		generatePaypalAccessToken($w,1);
	}
	 
	$result=makePayment($w,$users,1);
	
	
	
	$sqs=mysql($w['database'],"select * from car_order_items where order_id=$_GET[or]");
		while($sqsr=mysql_fetch_assoc($sqs))
		{
			$pro=unserialize($sqsr['product_data']);
			$pro['car_post_quantity']=$pro['car_post_quantity']-$pro['quantity'];
			mysql($w['database'],"update users_meta set value='".$pro['car_post_quantity']."' where `database`='users_portfolio_groups' and `database_id`='".$pro['group_id']."' and `key`='car_post_quantity'");
		}
mysql($w['database'],"update car_online_orders set response='".$result."',payment_status=1 where order_id=$_GET[or]"); 
	
	// $resp=file_get_contents("http://".$w['website_url']."/adaptivepaypal/success.php?dnb=".base64_encode($w['database'])."&dpb=".base64_encode($w['database_pass'])."&dub=".base64_encode($w['database_user'])."&order_id=$_GET[or]");
		
		$or=mysql($w['database'],"select * from	car_online_orders where order_id=$_GET[or]");
			$orr=mysql_fetch_assoc($or);
			$puser=getUser($orr['purchased_user'],$w);
			$content=orderEmailTemplate($_GET['or'],$orr['purchased_user'],"buyer",$w);
			$content=json_decode($content); 
			$w['user_first_name']=$puser['full_name'];
			$w['order_id']=$orr['order_id'];
			$w['order_on']=$orr['date'];
			$w['amount']=$w['currency_symbol'].number_format($content->total,2);
			$w['shipping_charge']=$w['currency_symbol'].number_format($content->ship,2);
			$w['total_amount']=$w['currency_symbol'].number_format($content->total+$content->ship,2);
			$w['table_temp']=$content->temp;
			
			$email = prepareEmail('thankyou-purchase', $w);
 	sendEmailTemplate( $email[sender],$puser['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email);  
	
	$uu=array_unique(explode(",",$orr['user_id']));
	 
	foreach($uu as $su)
	{
		
		$suser1=getUser($su,$w);
		$content=orderEmailTemplate($_GET['or'],$su,"seller",$w);
		$content=json_decode($content);
		
		$w['user_first_name']=$suser1['full_name'];
		$w['amount']=$w['currency_symbol'].number_format($content->total,2);
		$w['shipping_charge']=$w['currency_symbol'].number_format($content->ship,2);
		$w['total_amount']=$w['currency_symbol'].number_format($content->total+$content->ship,2);
		$w['table_temp']=$content->temp.$su;
		$email = prepareEmail('order-placed', $w);
 	sendEmailTemplate( $email[sender],$suser1['email'], $email[subject], $email[html], $email[text], $email[priority], $w, $email); 
		
	}
	 
	 header("location: http://".$w['website_url']."/order_success"); 
}else if($_GET['type']=="test")
{
	$users=array("sender_batch_header" => array("sender_batch_id" => "payouts_2020_103",
"email_subject" => "You Have a Payment",
"email_message" => "You have received a payout! Thanks for using our service! "),
"items" => array(array("recipient_type" => "EMAIL",
"amount" => array("value" => "12.6",
"currency" => "USA"),
"note" => "Thanks For Choosing Local Bulls",
"sender_item_id" => "order_103",
"receiver" => "posa.deb@gmail.com "),
 array("recipient_type" => "EMAIL",
"amount" => array("value" => "450",
"currency" => "NZD"),
"note" => "Thanks For Choosing Local Bulls",
"sender_item_id" => "order_102",
"receiver" => "support@localbulls.com" ) ) );
print_r($users); 
makePayment($w,$users,2);
}
 
	
?>