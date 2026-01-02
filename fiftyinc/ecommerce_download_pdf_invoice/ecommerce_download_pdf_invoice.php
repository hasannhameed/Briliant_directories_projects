<?
 $or=mysql($w['database'],"select * from car_online_orders where order_id=$_GET[or]");
  $orr=mysql_fetch_assoc($or);
  if($_GET['action']=="order"){ $uu=$orr['purchased_user'];
  $puser=getUser($uu,$w);}
  else if($_GET['action']=="purchases")
  {
	 $us=array_unique(explode(",",$orr['user_id']));
	 $sell=array();
	 foreach($us as $u)
	 {
		 $puser=getUser($u,$w);	
		 $sell[]=$puser['full_name'];
	}
  }
  // print_r($puser);
  $tot_amt=0;
  $temp="";
 $ship=0;
	  $billing=array();
	  $shipping=array();
	  if($orr['billing_address_house_number']!="") $billing[]=$orr['billing_address_house_number'];
	  if($orr['billing_address_street']!="") $billing[]=$orr['billing_address_street'];	  	
	  if($orr['billing_address_locality']!="") $billing[]=$orr['billing_address_locality'];  	  	  
	  if($orr['billing_address_town']!="") $billing[]=$orr['billing_address_town'];
	  if($orr['billing_address_county']!="") $billing[]=$orr['billing_address_county'];
	  if($orr['billing_address_country']!="") $billing[]=$orr['billing_address_country']." - ".$orr['billing_address_pincode'];	
	   if($orr['shipping_address_house_number']!="") $shipping[]=$orr['shipping_address_house_number'];	
	  if($orr['shipping_address_street']!="") $shipping[]=$orr['shipping_address_street'];	
	  if($orr['shipping_address_locality']!="") $shipping[]=$orr['shipping_address_locality'];  	  	  	  
	  if($orr['shipping_address_town']!="") $shipping[]=$orr['shipping_address_town'];
	  if($orr['shipping_address_county']!="") $shipping[]=$orr['shipping_address_county'];
	  if($orr['shipping_address_country']!="") $shipping[]=$orr['shipping_address_country']." - ".$orr['shipping_address_pincode'];
	  if($orr['same_address']==1)  $shipping=$billing; 	
	$it=mysql($w['database'],"select * from car_order_items where order_id=$orr[order_id]");
	while($itr=mysql_fetch_assoc($it))
	{
		$pro=unserialize($itr['product_data']);
		//print_r($pro);
		if($_GET['action']=="purchases")
		{
		$tot_amt+=$pro['property_price']*$pro['quantity'];
		$ship+=$pro['car_post_shipping_price'];
		}else if($_GET['action']=="order"){
				/*if($_COOKIE['userid']==$pro['user_id'])
				{*/
					$tot_amt+=$pro['property_price']*$pro['quantity'];
					$ship+=$pro['car_post_shipping_price'];
				/*}*/
			}
	}

	  // $temp.="<div class='row'>".echo $puser[company]."</div>";
  $newadd="";
  if($puser[address1]!=''){
    $newadd.=$puser[address1];
  }
    if($puser[address2]!=''){
      if($puser[address1]!=''){
        $newadd.=",";
      }
      $newadd.=$puser[address2]."<br>";
    }
    if($puser[city]!=''){
      $newadd.=$puser[city];
    }
    if($puser[state_code]!=''){
      if($puser[city]!=''){
        $newadd.=",";
      }
      $newadd.=$puser[state_code];
    }
    if($puser[country_code]!=''){
      if($puser[city]!='' || $puser[state_code]!=''){
        $newadd.=",";
      }
      $newadd.=$puser[country_code];
    }
$p=$puser[photo_file];
//$p='<img src='."$photo".' alt="test car">';
//$content= '<p><img src="'.$p.'" alt="'.$puser[company].'" /></p>';
  $temp.="<div><table><tbody><tr><td colspan='2'><b>Seller Info</b></td></tr>
  <tr><td width='500'><img src='https://reglamed.com/".$p."'  alt='test car' style='width: 150px;height: 100px; float:left;margin-right:15px;'></td><td width='300'>".$puser[company]."<br>".$newadd."<br>Email: ".$puser[email]."<br>Phone Number: ".$puser[phone_number]."</td></tr>
  </tbody></table></div>";
   $temp.="<br>";
  
    $temp.="<table ><tr><td width='300'> <div><label style='font-weight: 700;'>Order ID:</label><span> ".$orr['order_id']."</span></div>";
    
	if($_GET['action']=="order")
	{
	$temp.="<div><label style=' font-weight: 700;'>Purchased User:";
	$temp.="</label><span> ".$puser['full_name']."</span></div>";
	}
	else if($_GET['action']=="purchases")
	{
	$temp.="<div><label style=' font-weight: 700;'>Seller:";
	$temp.="</label><span> ".implode(",",$sell)."</span></div>";
	}
    $temp.="<div><label style=' font-weight: 700;'>Ordered On:</label><span> ".date("d/m/Y",strtotime($orr['date']))."</span></div>
    <div><label style=' font-weight: 700;'>Payment Status:</label><span> ";
	if($orr['payment_status']==0) $temp.= "Pending"; else $temp.= "Paid";
	$temp.="</span></div>
    <div><label style=' font-weight: 700;'>Deliver Status:</label><span> ";
	if($orr['deliver_status']==0) $temp.= " In Progress"; else if($orr['deliver_status']==1) $temp.= " Shipped"; else if($orr['deliver_status']==2) $temp.= " Delivered"; 
	$temp.="</span></div>";
	if($orr['deliver_status']==1){
		$temp.="<div><label style=' font-weight: 700;'>Tracking Number:</label><span> ".$orr['tracking_number']."</span></div>";
		$temp.="<div><label style=' font-weight: 700;'>Delivery Service:</label><span> ".$orr['delivery_service']."</span></div>";
		}
	$temp.="</td>";

		if(sizeof($billing)>0){
	 $temp.=" <td width='150'><label style=' font-weight: 700;' >Billing Address:</label>
	".implode(",<br>",$billing)."
     </td>";
	}
	 $temp.="   <td width='150'>   <div style='padding-right: 15px;'><label style=' font-weight: 700;'>Amount:</label><span style='float:right'>".$w['currency_symbol'].number_format($tot_amt,2)."</span></div>
    <div style='padding-right: 15px;'><label style=' font-weight: 700;'>Shipping Charge:</label><span style='float:right'>".$w['currency_symbol'].number_format($ship,2)."</span></div>
    <div style='padding-right: 15px;'><label style=' font-weight: 700;'>Total Amount:</label><span style='float:right'>".$w['currency_symbol'].number_format($tot_amt+$ship,2)."</span></div></td>
   </tr></table>";
      
   $temp.="
    <table >
    <tr>
    <th width='300'  style='background:#0FA54A;color:#fff;border-top: 0;border-color: rgb(238, 238, 238);border-bottom: 0;border-bottom-color: currentcolor;font-weight: 600;vertical-align: bottom;padding: 8px;text-align: left;line-height: 1.42857143;' >Product</th>
    <th width='100'  style='background:#0FA54A;color:#fff;border-top: 0;border-color: rgb(238, 238, 238);border-bottom: 0;border-bottom-color: currentcolor;font-weight: 600;vertical-align: bottom;padding: 8px;text-align: left;line-height: 1.42857143;' >Price</th>
    <th width='100'  style='background:#0FA54A;color:#fff;border-top: 0;border-color: rgb(238, 238, 238);border-bottom: 0;border-bottom-color: currentcolor;font-weight: 600;vertical-align: bottom;padding: 8px;text-align: left;line-height: 1.42857143;' >Quantity</th>
    <th width='100'  style='background:#0FA54A;color:#fff;border-top: 0;border-color: rgb(238, 238, 238);border-bottom: 0;border-bottom-color: currentcolor;font-weight: 600;vertical-align: bottom;padding: 8px;text-align: left;line-height: 1.42857143;' >Amount</th>
    </tr>
    <tbody>";
    $it1=mysql($w['database'],"select * from car_order_items where order_id=$orr[order_id]");
	while($itr1=mysql_fetch_assoc($it1))
	{
		$pro1=unserialize($itr1['product_data']);
		if($_GET['action']=="purchases")
		 {
		$pro1['shop_type']=explode(",",$pro1['shop_type']);
	 $suser=getUser($pro1['user_id'],$w);
		
		$temp.="<tr><td width='300' style='border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$pro1['group_name']."<br/><label>Seller: </label>".$suser['full_name']."<br/>";
		if(in_array("ship",$pro1['shop_type'])){
		$temp.="<label>Estimated Deliver Time: </label>".$pro1['car_post_estimated_delivery_time']."<br/><label>Delivery Service: </label>";
		if($orr['deliver_status']==0) $temp.= $pro1['car_post_deliver_service']; else $temp.= $orr['delivery_service']; 
		} 	if(sizeof($shipping)>0 && $pro1['product_ship_type']=="ship"){
	$temp.="<label >Shipping Address:</label>".implode(",<br>",$shipping);
	}if( $pro1['product_ship_type']=="pick"){
			$temp.="<label >Pick Up Location:</label>
	".$pro1['post_location'];
		}
        $temp.="<br/></td><td width='100' style='border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($pro1['property_price'],2)."</td><td width='100' style='border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$pro1['quantity']."</td><td width='100' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($pro1['property_price']*$pro1['quantity'],2)."</td></tr>";}
		else if($_GET['action']=="order")
	{
			
			if($_COOKIE['userid']==$pro1['user_id'])
			{
				$pro1['shop_type']=explode(",",$pro1['shop_type']);
	 $suser=getUser($pro1['user_id'],$w);
		
		$temp.="<tr><td width='300' style='border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$pro1['group_name']."<br/><label>Seller: </label>".$suser['full_name']."<br/>";
		if(in_array("ship",$pro1['shop_type'])){
		$temp.="<label>Estimated Deliver Time: </label>".$pro1['car_post_estimated_delivery_time']."<br/><label>Delivery Service: </label>";
		if($orr['deliver_status']==0) $temp.= $pro1['car_post_deliver_service']; else $temp.= $orr['delivery_service']; 
		} 	if(sizeof($shipping)>0 && $pro1['product_ship_type']=="ship"){
	$temp.="<label >Shipping Address:</label>".implode(",<br>",$shipping);
	}if( $pro1['product_ship_type']=="pick"){
			$temp.="<label >Pick Up Location:</label>
	".$pro1['post_location'];
		}
        $temp.="<br/></td><td width='100' style='border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($pro1['property_price'],2)."</td><td width='100' style='border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$pro1['quantity']."</td><td width='100' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($pro1['property_price']*$pro1['quantity'],2)."</td></tr>";
	}
	}
	
	}
	
    $temp.="<tr><td colspan='3' width='500' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>Total Amount</td><td  width='100' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($tot_amt,2)."</td></tr>
    <tr><td colspan='3' width='500'  style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>Shipping Charge</td><td width='100' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($ship,2)."</td></tr>";

    $temp.=" <tr><td colspan='3'  width='500' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>Grand Total</td><td width='100' style='text-align:right;border-color: rgb(238, 238, 238);padding: 8px;line-height: 1.42857143;vertical-align: top;border-top: 1px solid #ddd;box-sizing: border-box;'>".$w['currency_symbol'].number_format($tot_amt+$ship,2)."</td></tr>
    </tbody>
    </table>";
/*echo $temp;*/
require('html2pdf/vendor/autoload.php');
$html2pdf = new HTML2PDF('P', 'A4', 'fr');
$html2pdf->writeHTML($temp);

$html2pdf->output($orr['order_id']."_".$_GET['action']."_".date('Y').".pdf");
	 ?>
  