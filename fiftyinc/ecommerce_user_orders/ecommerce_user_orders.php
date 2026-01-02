<h2 class="bold text-center">Orders</h2>
<?
if(isset($_POST['ship_submit']))
{
	mysql($w['database'],"update car_online_orders set deliver_status='".$_POST['ship_status']."',tracking_number='".$_POST['track_number']."',delivery_service='".$_POST['deliver_service']."' where order_id=$_POST[order_id]");
	$or=mysql($w['database'],"select * from car_online_orders  where order_id=$_POST[order_id]");
	$orr=mysql_fetch_assoc($or);
	$puser=getUser($orr['purchased_user'],$w);
	$w['user_first_name']="<a href='".$w['website_url']."/".$puser['filename']."' target='_blank'>".$puser['full_name']."</a>";
	$w['order_id']=$orr['order_id'];
	$w['order_on']=date("d/m/Y",strtotime($orr['date']));
	$w['action']="Seller";
	$w['status']="Shipped";
	$w['delivery']=$orr[delivery_service];
		$w['tracking']=$orr[tracking_number];
	$sell=array();
	$uu=array_unique(explode(",",$orr['user_id']));
	foreach($uu as $su)
	{
		$suser=getUser($su,$w);
		$sell[]="<a href='".$w['website_url']."/".$suser['filename']."' target='_blank'>".$suser['full_name']."</a>";
	}
	$w['user_nm']=implode(",",$sell);
	$pr=mysql($w['database'],"select * from car_order_items  where order_id=$_POST[order_id]");
	$prd=array();
	while($prr=mysql_fetch_assoc($pr))
	{
		$it=unserialize($prr['product_data']);
		$prd[]="<a href='".$w['website_url']."/".$it['group_filename']."' target='_blank'>".$it['group_name']."</a>";
	}
	$w['products']=implode(",",$prd);
	$email = prepareEmail('buyer_order_shipping_email', $w);
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
}

if($_GET['ord']==""){
	
$or=mysql($w['database'],"SELECT * FROM `car_online_orders` where FIND_IN_SET('".$_COOKIE['userid']."',user_id) and payment_status=1 order by order_id DESC");
?>
<table class="table">
<thead>
	<th>Order ID</th>
<th>User</th>
<th>Ordered On</th>
<th>Amount</th>
<th>Status</th>
<th>Delivery Status</th>
<th></th>
</thead>
<tbody>
 <?
if(mysql_num_rows($or)>0)
{
 while($orr=mysql_fetch_assoc($or))
 {
	$puser=getUser($orr['purchased_user'],$w);	
	$tot_amt=0;
	$it=mysql($w['database'],"select * from car_order_items where order_id=$orr[order_id]");
	while($itr=mysql_fetch_assoc($it))
	{
		$pro=unserialize($itr['product_data']);
		if($_COOKIE['userid']==$pro['user_id'])
		$tot_amt+=($pro['property_price']*$pro['quantity'])+$pro['car_post_shipping_price'];
	
	}
?>
 <tr><td><?=$orr['order_id']?></td><td><a href="/<?=$puser['filename']?>" target="_blank"><?=$puser['full_name']?></a></td><td><?=date("d/m/Y",strtotime($orr['date']))?></td><td><?=$w['currency_symbol'].number_format($tot_amt,2);?></td><td><? if($orr['payment_status']==1) echo "Paid"; else echo "Pending";?></td><td>
 <select class="form-control deliver_status_drop" data-order="<?=$orr['order_id']?>"><option value="0" <? if($orr['deliver_status']==0) echo "selected"; ?>>In Progress</option><option value="1" <? if($orr['deliver_status']==1) echo "selected"; ?>>Shipped</option></select>
</td><td><a href="/account/orders?ord=<?=$orr['order_id']?>" class="btn btn-primary btn-xs">View</a></td></tr>
 <? }
}else if($_GET['ord']!="") {?>
<tr ><td colspan="6" class="text-center">No Orders.</td></tr>
<? }
  ?>
  </tbody>
  </table>
<div class="modal fade" id="shippedForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h3>Tracking Details         <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></h3>

     
      </div>
       <form id="ship_form" name="ship_form" method="post" action="">
      <div class="modal-body">
     
       <input  type="hidden" id="ship_order_id" name="order_id"  value="" />
        <input  type="hidden" id="ship_status" name="ship_status"  value="1" />
      
       <div class="row">
       <div class="col-sm-12">
       <div class="form-group">
       <label>Tracking Number</label>
        <input  type="text"  name="track_number" class="form-control" placeholder="Tracking Number"  required/>
       </div>
       </div>
       </div>
       <div class="row" style="margin-top:10px;">
       <div class="col-sm-12">
        <div class="form-group">
          <label  >Delivery Service </label>
          <input type="text"   name="deliver_service"  placeholder="Delivery Service" class="form-control" required/> 
          </div>
          </div> 
       </div>
    
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" name="ship_submit"  class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  <script>
  $(document).ready(function(e) {
    $(".deliver_status_drop").change(function(e){
			var ele=$(this);
			
			if(ele.val()==1){	
			$("#ship_order_id").val(ele.data('order'));
			$("#shippedForm").modal("show");
			}else {
				$.ajax({
		
							type: "POST",		
							url: "/api/widget/html/get/ecommerce_ajax_actions",		
							data: {'action':"update_deliver",'order_id':ele.data('order'),'status':ele.val()},		
							cache: false,		
							success: function (response) {
								 swal({  type:"success",title: 'Status updated successfully.',   html: "",showCancelButton: false, showConfirmButton: true,
									confirmButtonColor: '#3085d6',
									cancelButtonColor: '#d33',
									confirmButtonText: "Close Window",
									closeOnConfirm: true, 
									allowEscapeKey: false,
									allowOutsideClick: false
	                             });
							}
			});
				}
				
		});
});
  </script>
  <? } else {  
	  
	  $or=mysql($w['database'],"select * from car_online_orders where order_id=$_GET[ord]");
	  $orr=mysql_fetch_assoc($or);
	  $puser=getUser($orr['purchased_user'],$w);
	  $tot_amt=0;
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
		if($_COOKIE['userid']==$pro['user_id'])
		{
		$tot_amt+=$pro['property_price']*$pro['quantity'];
		$ship+=$pro['car_post_shipping_price'];
		}
	}
	  ?>
  	<div class="well">
    <div class="row">
    <div class="col-md-4">
    <div><label>Order ID:</label><span><?=" #".$orr['order_id']?></span></div>
    <div><label>Purchased User:</label><span><a href="/<?=$puser['filename']?>" target="_blank"><?=" ".$puser['full_name']?></a></span></div>
    <div><label>Ordered On:</label><span><?=" ". date("d/m/Y",strtotime($orr['date']))?></span></div>
    <div><label>Payment Status:</label><span><? if($orr['payment_status']==0) echo " Pending"; else echo " Paid";?></span></div>
    <div><label>Deliver Status:</label><span><? if($orr['deliver_status']==0) echo " In Progress"; else if($orr['deliver_status']==1) echo " Shipped"; else if($orr['deliver_status']==2) echo " Delivered"; ?></span></div>
    <? if($orr['deliver_status']==1) {?>
    <div><label>Tracking Number:</label><span><?=" ".$orr['tracking_number']?></span></div>
    <div><label>Delivery Service:</label><span><?=" ".$orr['delivery_service']?></span></div>
		
		<? }?>
   
    </div>
  <? 
	if(sizeof($billing)>0){?>
    
    <div class="col-md-4">
    <div><label>Billing Address:</label>
    <div>
    	<?=implode(",<br>",$billing)?>
    </div>
		 <div><label>Email:</label><span><?=" ".$puser['email']?></span></div>
    </div>
   
    </div>
    <? }?>
    <div class="col-md-4">
     <div style="padding-right: 15px;"><label>Amount:</label><span style="float:right"><?=$w['currency_symbol'].number_format($tot_amt,2)?></span></div>
    <div style="padding-right: 15px;"><label>Shipping Charge:</label><span style="float:right"><?=$w['currency_symbol'].number_format($ship,2)?></div>
    <div style="padding-right: 15px;"><label>Total Amount:</label><span style="float:right"><?=$w['currency_symbol'].number_format($tot_amt+$ship,2)?></span></div>
    <div style="padding-right: 15px;" class="text-right tmargin"><a href="/api/widget/html/get/ecommerce_download_pdf_invoice?action=order&or=<?=$orr['order_id']?>" target="_blank" class="btn btn-primary btn-sm download_invoce">Download PDF Invoice</a></div>
    </div>
      </div>
     
    </div>
    <div class="well">
    <table class="table">
    <thead>
    <th colspan="2">Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Amount</th>
    </thead>
    <tbody>
    <?
    $it1=mysql($w['database'],"select * from car_order_items where order_id=$orr[order_id]");
	while($itr1=mysql_fetch_assoc($it1))
	{
		$pro1=unserialize($itr1['product_data']);
		if($_COOKIE['userid']==$pro1['user_id'])
		{
		$pro1['shop_type']=explode(",",$pro1['shop_type']);
	  $suser=getUser($pro1['user_id'],$w);
		?>
		<tr><td colspan="2"><div class="dd_re"><div style="float: left;width: 150px;margin-right: 10px;"><img src="<?="/photos/display/".$pro1['imagefile']?>" alt="<?=$pro1['group_name']?>" style="width: 1005;" /></div><div style="float: left;width: calc(100% - 160px) !important;"><a href="/<?=$pro1['group_filename']?>" target="_blank"><?=$pro1['group_name']?></a><br><label>Seller: </label><a href="/<?=$suser['filename']?>" target="_blank"><?=" ".$suser['full_name']?></a><br><? if(in_array("ship",$pro1['shop_type'])){?><label>Estimated Deliver Time: </label><?=$pro1['car_post_estimated_delivery_time']?><br><label>Service:</label><? if($orr['deliver_status']==0) echo $pro1['car_post_estimated_delivery_time']; else echo $orr['delivery_service'];?><? }  if(sizeof($shipping)>0 && $pro1['product_ship_type']=="ship"){ ?> <label>Shipping Address:</label> <br>
    	<?=implode(",<br>",$shipping)?> 
    <? }
	if(  $pro1['product_ship_type']=="pick"){?> <label>Pick Up Location:</label> <br>
    	<?=$pro1['post_location']?> 
	<?
		}?></div> </div></td><td><?=$w['currency_symbol'].number_format($pro1['property_price'],2)?></td><td><?=$pro1['quantity']?></td><td class="text-right"><?=$w['currency_symbol'].number_format($pro1['property_price']*$pro1['quantity'],2)?></td></tr>
		<?
		}
		
	}
	?>
    <tr><td colspan="4" class="text-right">Total Amount</td><td class="text-right"><?=$w['currency_symbol'].number_format($tot_amt,2)?></td></tr>
    <tr><td colspan="4" class="text-right">Shipping Charge</td><td class="text-right"><?=$w['currency_symbol'].number_format($ship,2)?></td></tr>
  
    <tr><td colspan="4" class="text-right">Grand Total</td><td class="text-right"><?=$w['currency_symbol'].number_format($tot_amt+$ship,2)?></td></tr>
    </tbody>
    </table>
    </div>

  
  <? 
   }?>
