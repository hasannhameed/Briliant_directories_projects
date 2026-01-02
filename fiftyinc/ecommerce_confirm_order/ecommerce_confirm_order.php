<?
$or=mysql($w['database'],"select * from  car_online_orders where order_id=$_GET[ord]");
 $orr=mysql_fetch_assoc($or);
 $bill_addr=array();
 $ship_addr=array();
 if($orr['billing_address_house_number']!="") $bill_addr[]=$orr['billing_address_house_number'];
 if($orr['billing_address_street']!="") $bill_addr[]=$orr['billing_address_street'];
 if($orr['billing_address_locality']!="") $bill_addr[]=$orr['billing_address_locality'];
 if($orr['billing_address_town']!="") $bill_addr[]=$orr['billing_address_town'];
 if($orr['billing_address_county']!="") $bill_addr[]=$orr['billing_address_county'];
 if($orr['billing_address_pincode']!="") $bill_addr[]=$orr['billing_address_country']." - ".$orr['billing_address_pincode'];
 if($orr['shipping_address_house_number']!="") $ship_addr[]=$orr['shipping_address_house_number'];
 if($orr['shipping_address_street']!="") $ship_addr[]=$orr['shipping_address_street'];
 if($orr['shipping_address_locality']!="") $ship_addr[]=$orr['shipping_address_locality'];
 if($orr['shipping_address_town']!="") $ship_addr[]=$orr['shipping_address_town'];
 if($orr['shipping_address_county']!="") $ship_addr[]=$orr['shipping_address_county'];
 if($orr['shipping_address_pincode']!="") $ship_addr[]=$orr['shipping_address_country']." - ".$orr['shipping_address_pincode'];
 if($orr['same_address']=="1")
 {
	 $ship_addr=$bill_addr;
 }
?>
<?php /*?><form method="post" action="<? if($_GET['type']=="") echo "/api/widget/html/get/ecommerce_ajax_actions"; else if($_GET['type']=='cart') echo "/adaptivepaypal/payment.php";?>"><?php */?>
<form method="post" action="<? if($_GET['type']=="") echo "/api/widget/html/get/ecommerce_ajax_actions"; else if($_GET['type']=='cart') echo "/api/widget/html/get/ecommerce_ajax_actions";?>">
<?php /*?><input type="hidden" name="action" value="<? if($_GET['type']=="") echo "confirm_buy"; else if($_GET['type']=='cart') echo "cart_buy";?>"/><? */ ?>
	<input type="hidden" name="action" value="cart_buy"/>
<input type="hidden" name="order_id" value="<?=$_GET['ord']?>"/>
<div class="row">
<? if($_GET['type']==""){ ?>
<div class="col-md-6">
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
$pickup=array();
$rq=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$_SESSION['buy_products']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$pickup[]=array("pro"=>$res['group_name'],"loc"=>$res['post_location']);
$iq=mysql($w['database'],"select * from users_portfolio	 where group_id=$res[group_id]");
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
	$tot_ship=$res["car_post_shipping_price_10"];
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
$allship=json_encode($allship);
$res['shop_type']=explode(",",$res['shop_type']);
?>
<tr><?php if($imageFile['file']!=""){?><td style="min-width: 30%;"><img src="<?="/photos/display/".$imageFile['file']?>"/></td><? }?>
<td style="min-width: 40%;" colspan="<?php if($imageFile['file']!=""){ echo "3";}else echo "4";?>"><div><?=$res['group_name']?></div>
<? if(in_array("ship",$res['shop_type'])){ 
	if($res['car_post_estimated_delivery_time']=="Onetwodays"){
		 $newdel="1-2 business days";
	 }
	 else  if($res['car_post_estimated_delivery_time']=="Threefourdays"){
		 $newdel="3-4 business days";
	 }?>
<div style="font-size:12px"><label style="font-size:12px">Estimated Delivery Time:</label> <?=$newdel?></div>
<div style="font-size:12px"><label style="font-size:12px">Service:</label><?=$res['car_post_deliver_service']?></div>
<? }    ?>

</td>
<td><?=$qty?></td>
<td style="text-align:right"><?=$w['currency_symbol'].number_format($res['now_price']*$qty,2)?></td>
</tr>

<tr><td colspan="5" class="text-right">Shipping:</td><td class="text-right"><? if($res['ship_type']=="all"){ if($res['car_post_shipping']!="" || $res['car_post_shipping']>0) echo $res['car_post_shipping']; else echo "Free Shipping"; }else if($res['ship_type']=="per"){echo $res["car_post_shipping_$qty"];}?></td></tr>
<tr><td colspan="5" class="text-right">Grand Total:</td><td class="text-right"><?=$w['currency_symbol'].number_format(($res['now_price']*$qty)+$tot_ship,2)?></td></tr>

</tbody>
</table>
</div>
</div>	
<? } else if($_GET['type']=='cart'){
	$total=0;
	$tot_ship=0;
	?>
   <input type="hidden" name="dnb"  value="<?=base64_encode($w['database'])?>"/>
   <input type="hidden" name="dpb"  value="<?=base64_encode($w['database_pass'])?>"/>
   <input type="hidden" name="dub"  value="<?=base64_encode($w['database_user'])?>"/>
	<input type="hidden" name="cc"  value="<?=$w['currency_code']?>"/>
<div class="col-md-6">
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

$pickup=array();
$tot_ship=0;
$total=0;
$allship=array();
foreach($_SESSION['add_to_cart_products'] as $se)
{
$tot_ship_inc=0;	

$rq=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$se['gid']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$pickup[]=array("pro"=>$res['group_name'],"loc"=>$res['post_location']);
$gev=mysql($w['database'],"select * from car_order_items where order_id=$_GET[ord] and product_id=$res[group_id]");
$gevr=mysql_fetch_assoc($gev);

$res['shop_type']=explode(",",$res['shop_type']);
$iq=mysql($w['database'],"select * from users_portfolio where group_id=$res[group_id]");
$imageFile=mysql_fetch_assoc($iq);


$qty=$se['product_quantity'];
if($res['ship_type']=="all")
$tot_ship_inc=$res['car_post_shipping_price'];
else if($res['ship_type']=="per")
{
	if($qty<=10)
	$tot_ship_inc=$res["car_post_shipping_price_$qty"];
	else
	$tot_ship_inc=$res["car_post_shipping_price_10"];
	 
} 

if($se['shop_type']=="ship")
{
$total+=(($res['now_price']*$se['product_quantity'])+$tot_ship_inc);
$tot_ship+=$tot_ship_inc;
}
else
$total+=(($res['now_price']*$se['product_quantity']));
 

?>
<tr><?php if($imageFile['file']!=""){?><td style="min-width: 30%;"><img src="<?="/photos/display/".$imageFile['file']?>"/></td><? }?>
<td style="min-width: 40%;" colspan="<?php if($imageFile['file']!=""){ echo "3"; }else echo "4";?>"><div><?=$res['group_name']?></div>
<? if($se['shop_type']=="ship"){ ?>
<div style="font-size:12px"><label style="font-size:12px">Estimated Delivery Time:</label><?=$res['car_post_estimated_delivery_time']?></div>
<div style="font-size:12px"><label style="font-size:12px">Service:</label><?=$res['car_post_deliver_service']?></div>
<div style="font-size:12px"><label style="font-size:12px">Shipping Address:</label><br><?=implode(",<br>",$ship_addr)?></div>
<? }  if($se['shop_type']=="pick"){?>
 
<div style="font-size:12px"><label style="font-size:12px">Pick Up Location:</label><?=$res['post_location']?></div>
<? }?>

</td>
<td><?=$se['product_quantity']?></td>
<td style="text-align:right" class="p_promo" data-promo="<?=$res['now_price']?>" id="post_promo_<?=$res['group_id']?>"><?=$w['currency_symbol'].(number_format($res['now_price']*$se['product_quantity'],2))?></td>
</tr>
<? }?>

<tr><td colspan="5" class="text-right">Shipping:</td><td class="text-right p_ship" data-ship="<?=$tot_ship?>"><? if($tot_ship!="" || $tot_ship>0) echo number_format($tot_ship,2); else echo "Free Shipping"; ?></td></tr>
<tr><td colspan="5" class="text-right">Grand Total:</td><td class="text-right" id="grand_total"><?=$w['currency_symbol'].number_format($total,2)?></td></tr>

</tbody>
</table>
</div>
</div>
<? }?>
<div class="col-md-6">
<div class="carts">
<div class="row">
<?
 ?>
 <?
 if(sizeof($bill_addr)>0)
 {
 ?>
<div class="col-md-6" >
 
 
 <h3 class='pro-header '>Billing Address</h3>
 <div>
 <?=implode(",<br>",$bill_addr)?>


  </div>


</div>
 <? }
 if($_GET['type']==""){
	  $it1=mysql($w['database'],"select * from car_order_items where order_id=$_GET[ord]");
	  $itr1=mysql_fetch_assoc($it1);
	  $pro1=unserialize($itr1['product_data']);
  if(sizeof($ship_addr)>0 && $pro1['product_ship_type']=="ship" )
 {?>
<div class="col-md-6" >
 <h3 class='pro-header '>Shipping Address</h3>
 <div>
 <?=implode(",<br>",$ship_addr)?>
 </div>
</div>
<? } }?>
</div>
<? if($_GET['type']==""){ if($pro1['product_ship_type']=="pick")
{?>
<div class="row">
<div class="col-md-12">
<div class="carts" >
 <h3 class='pro-header text-center'>Pick Up Address</h3>
 <div class="row">
 <div class="col-md-12">
	 <table style="width:100%">
	<?
    foreach($pickup as $pp)
	{
		$pp['loc']=explode(",",$pp['loc']);
		?>
	<tr><td><?=$pp['pro']?></td><td><?=implode(",<br>",$pp['loc'])?></td></tr>
	<?
	}
	?>

    </table>
 </div>
 </div>

 </div>
</div>
</div>
<? }} ?>
</div>
</div>			
</div>
<div class="text-center tmargin">
 <input type="image" src="http://localbulls.com/images/paypal.png" alt="Submit" class="pay_btn_conf"/> 
</div>
</form>