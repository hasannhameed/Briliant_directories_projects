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
$pickup=array();
$rq=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$_SESSION['buy_products']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$pickup[]=array("pro"=>$res['group_name'],"loc"=>$res['post_location']);
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
<tr><?php if($imageFile['file']!=""){?><td style="min-width: 10%;"><img src="<?="/photos/display/".$imageFile['file']?>"/></td><? }?>
<td style="min-width: 60%;" colspan="<?php if($imageFile['file']!=""){ echo "3";}else echo "4";?>"><div><?=$res['group_name']?></div>
<? if(in_array("ship",$res['shop_type'])){
    $user_shop_type="ship";
	if($res['car_post_estimated_delivery_time']=="Onetwodays"){
		 $newdel="1-2 business days";
	 }
	 else  if($res['car_post_estimated_delivery_time']=="Threefourdays"){
		 $newdel="3-4 business days";
	 }?>
     ?>
<div style="font-size:12px"><label style="font-size:12px">Estimated Delivery Time:</label> <?=$newdel?></div>
<div style="font-size:12px"><label style="font-size:12px">Service:</label><?=$res['car_post_deliver_service']?></div>
<? }  if(in_array("pick",$res['shop_type'])){
    if(sizeof($res[shop_type])==1)
    $user_shop_type="pick";
    ?>
<div style="font-size:12px"><label style="font-size:12px">Pick Up Location:</label><?=$res['post_location']?></div>
<? }?>
</td>
<td><select data-gid="<?=$res['group_id']?>" data-tot="<?=$res['now_price']?>" class="form-control pro_quantity">
    <? for($i=1;$i<=$res['car_post_quantity'];$i++){?>
    <option value="<?=$i?>" <? if($i==$qty) echo "selected"; ?>><?=$i?></option>
    <? }?>
</select></td>
<td style="text-align:right" class="p_promo" data-promo="<?=$res['now_price']?>" id="post_promo_<?=$res['group_id']?>"><?=$w['currency_symbol'].(number_format($res['now_price']*$qty))?></td>
</tr>

<tr><td colspan="5" class="text-right">Shipping:</td><td  class="text-right p_ship" data-tship="<?=$tot_ship?>" data-ship="<?=$tot_ship?>"><? if($res['ship_type']=="all"){ if($res['car_post_shipping']!="" || $res['car_post_shipping']>0) echo $res['car_post_shipping']; else echo "Free Shipping"; }else if($res['ship_type']=="per"){echo $res["car_post_shipping_$qty"];}?></td></tr>
<tr><td colspan="5" class="text-right">Grand Total:</td><td class="text-right" id="grand_total"><?=$w['currency_symbol'].number_format(($res['now_price']*$qty)+$tot_ship,2)?></td></tr>

</tbody>
</table>
</div>
</div>
</div>
<script>
var cur_sym="<?=$w['currency_symbol']?>";
var allship=JSON.parse('<?=$allship?>');
 
var ship_type="<?=$res['ship_type']?>";
var user_ship_type="<?=$user_shop_type?>";
$(".pro_quantity").change(function(e){
        var ele=$(this);
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
                                              
                                      if(user_ship_type=="pick")
                                      {
                                        ship=0; 
                                      }
                                      else {
                                           $(".p_ship").attr("data-tship",ship); 
                                          }
                                      $(".p_ship").html("<?=$w['currency_symbol']?>"+numberFormatter(ship));
                                     gtot+=parseFloat(ship);
                                 $('#grand_total').html(cur_sym+numberFormatter(gtot));
                            }
        });
    });
function numberFormatter(x)
{
   
return parseFloat(x).toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>
<? } else if($_GET['type']=='cart'){
    $show_ship=false; 
     
    
    if($_GET['rmv']!='')
{
    unset($_SESSION['add_to_cart_products'][$_GET['rmv']]);
    
    if(sizeof($_SESSION['add_to_cart_products'])==0)
    {
        unset($_SESSION['add_to_cart_products']);
    }
}
    $total=0;
    $tot_ship=0;
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
    $pickup=array();
    $allship=array();
    $total=0;
    $tot_ship=0;
foreach($_SESSION['add_to_cart_products'] as $se)
{
    

$rq=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$se['gid']."'");
$res=mysql_fetch_assoc($rq);
$res=getMetaData("users_portfolio_groups",$res['group_id'],$res,$w);
$pickup[]=array("pro"=>$res['group_name'],"loc"=>$res['post_location']);
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
$total+=(($res['property_price']*$se['product_quantity'])+$tot_ship_cal);
$tot_ship+=$tot_ship_cal;
}
else
$total+=(($res['property_price']*$se['product_quantity'])); 
$res['shop_type']=explode(",",$res['shop_type']);
 
?>
<tr><td style="vertical-align:middle"><a href="cartactioncon?rmv=<?=$res['group_id']?>&type=cart"  class="rmv_btn btn btn-warning btn-sm"><i class="fa fa-times" aria-hidden="true"></i></a></td><?php if($imageFile['file']!=""){?><td style="min-width: 10%;"><img src="<?="/photos/display/".$imageFile['file']?>"/></td><? }?>
<td style="min-width: 60%;" colspan="<?php if($imageFile['file']!=""){echo "3";}else echo "4";?>"><div><?=$res['group_name']?></div>
<?  if(in_array("ship",$res['shop_type'])) {
	 if($res['car_post_estimated_delivery_time']=="Onetwodays"){
		 $newdel="1-2 business days";
	 }
	 else  if($res['car_post_estimated_delivery_time']=="Threefourdays"){
		 $newdel="3-4 business days";
	 }
	?>
<div style="font-size:12px"><label style="font-size:12px">Estimated Delivery Time: </label> <?=$newdel?></div>
<div style="font-size:12px"><label style="font-size:12px">Service:</label><?=$res['car_post_deliver_service']?></div>
<? }  if(in_array("pick",$res['shop_type'])){?>
<div style="font-size:12px"><label style="font-size:12px">Pick Up Location:</label><?=$res['post_location']?></div>
<? }?>
</td>
<td><? if(in_array("ship",$res['shop_type'])){?><div class="radio">
<label><input type="radio" name="ship_select_<?=$res['group_id']?>" data-ship="<?=$in_ship?>"   data-tship="<?=$in_ship?>"  data-groupid="<?=$res['group_id']?>" <? if($se['shop_type']=="ship"){ echo "checked"; $show_ship=true; } ?> class="ship_select ship_row" value="ship"/>Ship</label>
</div><?   }if(in_array("pick",$res['shop_type'])){?>
<div class="radio">
<label><input type="radio"  data-groupid="<?=$res['group_id']?>" data-ship="<?=$in_ship?>"  data-tship="<?=$in_ship?>"  name="ship_select_<?=$res['group_id']?>" <? if($se['shop_type']=="pick") echo "checked"; ?> class="ship_select" value="pick"/>Pick Up</label>
</div>
<? }?></td>
<td><select data-gid="<?=$res['group_id']?>" data-tot="<?=$res['now_price']?>" data-tship="<?=$in_ship?>" class="form-control pro_quantity">
    <? for($i=1;$i<=$res['car_post_quantity'];$i++){?>
    <option value="<?=$i?>" <? if($se['product_quantity']==$i) echo "selected"; ?>><?=$i?></option>
    <? }?>
</select></td>
<td style="text-align:right" class="p_promo" data-promo="<?=$res['now_price']?>" id="post_promo_<?=$res['group_id']?>"><?=$w['currency_symbol'].(number_format($res['property_price']*$se['product_quantity'],2))?></td>
</tr>
<? }?>

<tr><td colspan="6" class="text-right">Shipping:</td><td  colspan="2" class="text-right p_ship" data-ship="<?=$tot_ship?>"  data-tship="<?=$tot_ship?>"><? if($tot_ship!="" || $tot_ship>0) echo number_format($tot_ship,2); else echo "Free Shipping"; ?></td></tr>
<tr><td colspan="6" class="text-right">Grand Total:</td><td   colspan="2" class="text-right" id="grand_total"><?=$w['currency_symbol'].number_format($total,2)?></td></tr>
<? }else {?>
<tr><td colspan="7" class="text-center">No products in the cart.</td></tr>
<? } $allship=json_encode($allship);?>

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

$(".cart_loading").show();

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
                                 $(".pro_quantity").each(function(index,element){
                                    
                                        var ele1=$(this);
                                        console.log(ele1);
                                        gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));
                                         
                                          if($("input[name=ship_select_"+ele1.data('gid')+"]:checked").val()=="pick")
                                      {
                                           if(allship[ele1.data('gid')]['ship_type']=="per")
                                             {
                                             if(parseFloat(ele1.val())<=10)
                                             {
                                             
                                             $(ele1).attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]));
                                              $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]));
                                             }
                                             else
                                             {
                                             
                                              $(ele1).attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][10]));
                                                 $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][10]));
                                             }
                                             
                                             
                                             }
                                        ship+=0;    
                                      }
                                      else {
                                          
                                           if(allship[ele1.data('gid')]['ship_type']=="per")
                                             {
                                                 
                                             if(parseFloat(ele1.val())<=10)
                                             {
                                             ship+=parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]);
                                             $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-ship",parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]));
                                             $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]));
                                            
                                                 $(ele1).attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][ele1.val()]));
                                             }
                                             else
                                             {
                                             ship+=parseFloat(allship[ele1.data('gid')]['ship'][10]);
                                              $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-ship",parseFloat(allship[ele1.data('gid')]['ship'][10]));
                                               $("input[name=ship_select_"+ele1.data('gid')+"]").attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][10]));
                                              $(ele1).attr("data-tship",parseFloat(allship[ele1.data('gid')]['ship'][10]));
                                             }
                                             
                                             
                                             }else if(allship[ele1.data('gid')]['ship_type']=="all")
                                             {
                                                 ship+=$("input[name=ship_select_"+ele1.data('gid')+"]:checked").data("ship");
                                             }
                                           
                                           
                                            $(".p_ship").attr("data-ship",ship);
                                           $(".p_ship").attr("data-tship",ship); 
                                          
                                          }
                                        
                                        
                                         
                                        
                                     });
                                
                             
                                            
                                            
                                              
                                    
                                      $(".p_ship").html("<?=$w['currency_symbol']?>"+numberFormatter(ship));
                                     gtot+=parseFloat(ship);
                                 $('#grand_total').html(cur_sym+numberFormatter(gtot));
                             $(".cart-div").find('span').text(response);
                                /* $(".cart-div").find('span').text(response);
                                 var tot=parseFloat(ele.data('tot'))*parseFloat(ele.val());



                                 var gtot=0;
                                 $("#post_promo_"+ele.data('gid')).attr("data-promo",tot);
                                 $("#post_promo_"+ele.data('gid')).html(cur_sym+numberFormatter(tot));
                                
                                 $(".pro_quantity").each(function(index,element){
                                    
                                        var ele1=$(this);
                                        gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));
                                        
                                     });
                                     gtot+=parseFloat($(".p_ship").data("ship"));
                                 $('#grand_total').html(cur_sym+numberFormatter(gtot));*/
                                  $(".cart_loading").hide();
                            }
            });
    });
function numberFormatter(x)
{
   
return parseFloat(x).toLocaleString('us', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>

<? }
if($_GET['type']=="")
{
     
if(in_array("ship",$res['shop_type']) && in_array("pick",$res['shop_type']))
{?>
<div class="row tmargin">
<div class="col-md-6 col-md-offset-3">
<div class="carts">
 <h3 class='pro-header text-center'>Delivery Options</h3>
 <div class="row">
 <div class="col-md-6 text-center">
 <label style="font-size: 20px;"><input type="radio" name="ship" value="ship" checked style="margin-right: 10px;"/><i class="fa fa-truck" aria-hidden="true"></i>
&nbsp;Ship</label>
 </div>
 <div class="col-md-6 text-center">
  <label style="font-size: 20px;"><input type="radio" name="ship" value="pick"  style="margin-right: 10px;"/><i class="fa fa-shopping-basket" aria-hidden="true"></i>
&nbsp;Pick up</label>
 </div>
 </div>
</div>
</div>
</div>
<div class="row tmargin">
<form method="post" action="/api/widget/html/get/ecommerce_ajax_actions" id="cart_form">
<input type="hidden" name="action" value="buy_action"/>
<input type="hidden" name="action_type" value="<?=$_GET['type']?>"/>
<input name="ship_type" type="hidden" class="ship_type" value="ship">
<div class="col-md-6 <? if($orr['same_address']==1 || $orr['same_address']=="") echo "col-md-offset-3"; ?>   billing_section">
<?
$or=mysql($w['database'],"select * from car_online_orders where purchased_user = '".$_COOKIE['userid']."' order by date desc");
$orr=mysql_fetch_assoc($or);

$usr=getUser($_COOKIE['userid'],$w);
if($_COOKIE['userid']=="")
$usr_email=$_SESSION['cart_products_email'];
else $usr_email=$usr['email'];
?>
<div class="carts">
 <h3 class='pro-header text-center billing_section_head'>Shipping/Billing Address</h3>
 <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="billing_address_first_name" class="form-control" required value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="billing_address_last_name" class="form-control" required value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="col-md-6">
 <div class="form-group">
 <label>Last name</label>
 <input type="text" name="billing_address_last_name" class="form-control" value="<?=$usr['last_name']?>"/>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Company(optional)</label>
 <input type="text" name="billing_address_company" class="form-control" value="<?=$usr['company']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Email</label>
 <input type="email" name="email" class="form-control check_email"  required value="<?=$usr_email?>"/>
 </div>
 </div>
 </div>
 <? if($_COOKIE['userid']==""){ ?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Password</label>
 <input type="password" name="pass" class="form-control" required placeholder="Enter Password"/>
 </div>
 </div>
 </div>
 <? }?>

 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="billing_address_house_number" class="form-control"  required value="<?=$orr['billing_address_house_number']?>"/>
 </div>
 </div>
 </div>

 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="billing_address_locality" class="form-control"  required value="<?=$orr['billing_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="billing_address_town" class="form-control"  required value="<?=$orr['billing_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="country" class="form-control"  required value="United States" disabled/>
 <input type="hidden" name="billing_address_country" value="United States"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="billing_address_county" class="form-control"  required value="<?=$orr['billing_address_county']?>" />
 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="billing_address_pincode" class="form-control"  required value="<?=$orr['billing_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>
 <div class="ship_same">
 <input type="checkbox" class="same_address" value="1" name="same_address" <? if($orr['same_address']==1 || $orr['same_address']=="") echo "checked"; ?> /><label class="lmargin">Shipping address is same as billing address.</label>
 </div>
  
<div class="text-center"><input type="submit" value="Continue" class="btn btn-primary continue_btn" style="background: #d9c1ab  !important;border: 1px solid #d9c1ab  !important;"/></div>


</div>
</div>
<div class=" cart_ship_addr <? if($orr['same_address']==1 || $orr['same_address']=="") echo "" ;else echo "col-md-6";?>" style="display:<? if($orr['same_address']=="1" || $orr['same_address']==""  ) echo "none"; else echo "block"; ?>">
 <div class="carts" >
 <h3 class='pro-header text-center'>Shipping Address</h3>
  <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="shipping_address_first_name" class="form-control"  value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="shipping_address_last_name" class="form-control"  value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="shipping_address_house_number" class="form-control"   value="<?=$orr['shipping_address_house_number']?>"/>
 </div>
 </div>
 </div>
 
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="shipping_address_locality" class="form-control"   value="<?=$orr['shipping_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="shipping_address_town" class="form-control"   value="<?=$orr['shipping_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="ship_country" class="form-control"   value="New Zealand" disabled/>
  <input type="hidden" name="shipping_address_country"   value="New Zealand"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="shipping_address_county" class="form-control"   value="<?=$orr['shipping_address_county']?>" />
 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="shipping_address_pincode" class="form-control"   value="<?=$orr['shipping_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>
</div> 
</div>
<div class="pickup_addr" style="display:none">
 <div class="carts" >
 <h3 class='pro-header text-center'>Pick Up Address</h3>
 <div class="row">
 <div class="col-md-12">
 <table style="width:100%">
    <?
    foreach($pickup as $pp)
    {$pp['loc']=explode(",",$pp['loc']);
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

</form>
</div>
<?  

}else if(in_array("ship",$res['shop_type'])){
?>
<div class="row tmargin">
<form method="post" action="/api/widget/html/get/ecommerce_ajax_actions" id="cart_form">
<input type="hidden" name="action" value="buy_action"/>
<input type="hidden" name="action_type" value="<?=$_GET['type']?>"/>
<input name="ship_type"  type="hidden" class="ship_type" value="ship">
<div class="col-md-6 <? if($orr['same_address']==1 || $orr['same_address']=="") echo "col-md-offset-3";?> billing_section">
<?
$or=mysql($w['database'],"select * from car_online_orders where purchased_user = '".$_COOKIE['userid']."' order by date desc");
$orr=mysql_fetch_assoc($or);

$usr=getUser($_COOKIE['userid'],$w);
if($_COOKIE['userid']=="")
$usr_email=$_SESSION['cart_products_email'];
else $usr_email=$usr['email'];
?>
<div class="carts">
 <h3 class='pro-header text-center billing_section_head'>Shipping/Billing Address</h3>
 <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="billing_address_first_name" class="form-control" required value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="billing_address_last_name" class="form-control" required value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="col-md-6">
 <div class="form-group">
 <label>Last name</label>
 <input type="text" name="billing_address_last_name" class="form-control" value="<?=$usr['last_name']?>"/>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Company(optional)</label>
 <input type="text" name="billing_address_company" class="form-control" value="<?=$usr['company']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Email</label>
 <input type="email" name="email" class="form-control check_email"  required value="<?=$usr_email?>"/>
 </div>
 </div>
 </div>
 <? if($_COOKIE['userid']==""){ ?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Password</label>
 <input type="password" name="pass" class="form-control" required placeholder="Enter Password"/>
 </div>
 </div>
 </div>
 <? }?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="billing_address_house_number" class="form-control"  required value="<?=$orr['billing_address_house_number']?>"/>
 </div>
 </div>
 </div>

 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="billing_address_locality" class="form-control"  required value="<?=$orr['billing_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="billing_address_town" class="form-control"  required value="<?=$orr['billing_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="country" class="form-control"  required value="United States" disabled/>
  <input type="hidden" name="billing_address_country" value="United States"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="billing_address_county" class="form-control"  required value="<?=$orr['billing_address_county']?>" />
 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="billing_address_pincode" class="form-control"  required value="<?=$orr['billing_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>
 <input type="checkbox" class="same_address" value="1" name="same_address" <? if($orr['same_address']==1 || $orr['same_address']=="") echo "checked"; ?> /><label class="lmargin">Shipping address is same as billing address.</label>
  
<div class="text-center"><input type="submit" value="Continue" class="btn btn-primary btn-success continue_btn" style="background: #d9c1ab  !important;border: 1px solid #d9c1ab  !important;"/></div>


</div>
</div>
<div class=" cart_ship_addr <? if($orr['same_address']==1 || $orr['same_address']=="") echo "" ;else echo "col-md-6";?>" style="display:<? if($orr['same_address']==1 || $orr['same_address']=="") echo "none"; else echo "block"; ?>">
 <div class="carts" >
 <h3 class='pro-header text-center'>Shipping Address</h3>
  <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="shipping_address_first_name" class="form-control"  value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="shipping_address_last_name" class="form-control"  value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="shipping_address_house_number" class="form-control"   value="<?=$orr['shipping_address_house_number']?>"/>
 </div>
 </div>
 </div>

 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="shipping_address_locality" class="form-control"   value="<?=$orr['shipping_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="shipping_address_town" class="form-control"   value="<?=$orr['shipping_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="ship_country" class="form-control"   value="United States" disabled/>
  <input type="hidden" name="shipping_address_country"  value="United States"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="shipping_address_county" class="form-control"   value="<?=$orr['shipping_address_county']?>" />

 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="shipping_address_pincode" class="form-control"   value="<?=$orr['shipping_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>
</div> 
</div>


</form>
</div>
<? }else if(in_array("pick",$res['shop_type'])){?>
<div class="row tmargin">
<form method="post" action="/api/widget/html/get/ecommerce_ajax_actions" id="cart_form">
<input type="hidden" name="action" value="buy_action"/>
<input type="hidden" name="action_type" value="<?=$_GET['type']?>"/>
<input name="ship_type"  type="hidden" class="ship_type" value="pick">
<div class="col-md-6 billing_section">
<?
$or=mysql($w['database'],"select * from car_online_orders where purchased_user = '".$_COOKIE['userid']."' order by date desc");
$orr=mysql_fetch_assoc($or);

$usr=getUser($_COOKIE['userid'],$w);
if($_COOKIE['userid']=="")
$usr_email=$_SESSION['cart_products_email'];
else $usr_email=$usr['email'];
?>
<div class="carts">
 <h3 class='pro-header text-center billing_section_head'>Billing Address</h3>
 <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="billing_address_first_name" class="form-control" required value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="billing_address_last_name" class="form-control" required value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="col-md-6">
 <div class="form-group">
 <label>Last name</label>
 <input type="text" name="billing_address_last_name" class="form-control" value="<?=$usr['last_name']?>"/>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Company(optional)</label>
 <input type="text" name="billing_address_company" class="form-control" value="<?=$usr['company']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Email</label>
 <input type="email" name="email" class="form-control check_email"  required value="<?=$usr_email?>"/>
 </div>
 </div>
 </div>
 <? if($_COOKIE['userid']==""){ ?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Create a password</label>
 <input type="password" name="pass" class="form-control" required placeholder="Enter Password"/>
 </div>
 </div>
 </div>
 <? }?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="billing_address_house_number" class="form-control"  required value="<?=$orr['billing_address_house_number']?>"/>
 </div>
 </div>
 </div>
<div class="row" style="display:none;">
 <div class="col-md-12">
 <div class="form-group">
 <label>Suburb(optional)</label>
 <input type="text" name="billing_address_street" class="form-control"    value="<?=$orr['billing_address_street']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="billing_address_locality" class="form-control"  required value="<?=$orr['billing_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="billing_address_town" class="form-control"  required value="<?=$orr['billing_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="country" class="form-control"  required value="United States" disabled/>
 <input type="hidden" name="billing_address_country" value="United States"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="billing_address_county" class="form-control"  required value="<?=$orr['billing_address_county']?>" />
 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="billing_address_pincode" class="form-control"  required value="<?=$orr['billing_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>

  
<div class="text-center"><input type="submit" value="Continue" class="btn btn-primary btn-success continue_btn" style="background: #d9c1ab  !important;border: 1px solid #d9c1ab  !important;"/></div>


</div>
</div>
<div class="pickup_addr col-md-6" >
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

</form>
</div>
<? } } else if($_GET['type']=="cart"){
    
    ?>
    <div class="row tmargin">
<form method="post" action="/api/widget/html/get/ecommerce_ajax_actions" id="cart_form">
<input type="hidden" name="action" value="buy_action"/>
<input type="hidden" name="action_type" value="<?=$_GET['type']?>"/>

<?
foreach($_SESSION['add_to_cart_products'] as $sep)
{ 
$rq1=mysql($w['database'],"select * from users_portfolio_groups  where group_id = '".$sep['gid']."'");
$res1=mysql_fetch_assoc($rq1);
$res1=getMetaData("users_portfolio_groups",$res1['group_id'],$res1,$w);
$res1['shop_type']=explode(",",$res1['shop_type']);
 ?>
<input name="ship_type_<?=$res1['group_id']?>"  type="hidden" value="<?=$sep['shop_type']?>">
 <?
}
?>
<div class="col-md-6 <? if($orr['same_address']==1 || $orr['same_address']=="") echo "col-md-offset-3";?> billing_section">
<?
$or=mysql($w['database'],"select * from car_online_orders where purchased_user = '".$_COOKIE['userid']."' order by date desc");
$orr=mysql_fetch_assoc($or);

$usr=getUser($_COOKIE['userid'],$w);
if($_COOKIE['userid']=="")
$usr_email=$_SESSION['cart_products_email'];
else $usr_email=$usr['email'];
?>
<div class="carts">
 <h3 class='pro-header text-center billing_section_head'><? if(!$show_ship) echo"Billing Address";else echo "Shipping/Billing Address"; ?></h3>
 <div class="row">
 <div class="col-md-6">

 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="billing_address_first_name" class="form-control" required value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="billing_address_last_name" class="form-control" required value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="col-md-6">
 <div class="form-group">
 <label>Last name</label>
 <input type="text" name="billing_address_last_name" class="form-control" value="<?=$usr['last_name']?>"/>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Company(optional)</label>
 <input type="text" name="billing_address_company" class="form-control" value="<?=$usr['company']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Email</label>
 <input type="email" name="email" class="form-control check_email"  required value="<?=$usr_email?>"/>
 </div>
 </div>
 </div>
 <? if($_COOKIE['userid']==""){ ?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Password</label>
 <input type="password" name="pass" class="form-control" required placeholder="Enter Password"/>
 </div>
 </div>
 </div>
 <? }?>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="billing_address_house_number" class="form-control"  required value="<?=$orr['billing_address_house_number']?>"/>
 </div>
 </div>
 </div>
<div class="row" style="display:none;">
 <div class="col-md-12">
 <div class="form-group">
 <label>Suburb(optional)</label>
 <input type="text" name="billing_address_street" class="form-control suburb_optional"    value="<?=$orr['billing_address_street']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="billing_address_locality" class="form-control"  required value="<?=$orr['billing_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="billing_address_town" class="form-control"  required value="<?=$orr['billing_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="country" class="form-control"  required value="United States" disabled/>
  <input type="hidden" name="billing_address_country" value="United States"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="billing_address_county" class="form-control"  required value="<?=$orr['billing_address_county']?>" />
 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="billing_address_pincode" class="form-control"  required value="<?=$orr['billing_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>
 <div id="same_address" style="display:<? if(!$show_ship) echo "none"; else echo "block"; ?>">
 <input type="checkbox" class="same_address" value="1" name="same_address" <? if($orr['same_address']==1 || $orr['same_address']=="") echo "checked"; ?> /><label class="lmargin">Shipping address is same as billing address.</label>
 </div>
  
<div class="text-center"><input type="submit" value="Continue" class="btn btn-primary btn-success continue_btn" style="background: #d9c1ab  !important;border: 1px solid #d9c1ab  !important;"/></div>


</div>
</div>
<div class=" cart_ship_addr <? if($orr['same_address']==1 || $orr['same_address']=="") echo "" ;else echo "col-md-6";?>" style="display:<? if($orr['same_address']==1 || $orr['same_address']=="" ) echo "none"; else echo "block"; ?>">
 <div class="carts" >
 <h3 class='pro-header text-center'>Shipping Address</h3>
  <div class="row">
 <div class="col-md-6">
 <div class="form-group">
 <label>First Name</label>
 <input type="text" name="shipping_address_first_name" class="form-control"  value="<?=$usr['first_name']?>"/>
 </div>
 </div>
 <div class="col-md-6">
 <div class="form-group">
 <label>Last Name</label>
 <input type="text" name="shipping_address_last_name" class="form-control"  value="<?=$usr['last_name']?>"/>
 </div>
 </div>
 </div>
 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Address</label>
 <input type="text" name="shipping_address_house_number" class="form-control"   value="<?=$orr['shipping_address_house_number']?>"/>
 </div>
 </div>
 </div>

 <div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>City/Town</label>
 <input type="text" name="shipping_address_locality" class="form-control"   value="<?=$orr['shipping_address_locality']?>"/>
 </div>
 </div>
 </div>
 <?php /*?><div class="row">
 <div class="col-md-12">
 <div class="form-group">
 <label>Town</label>
 <input type="text" name="shipping_address_town" class="form-control"   value="<?=$orr['shipping_address_town']?>"/>
 </div>
 </div>
 </div><?php */?>
 <div class="row">
 <div class="col-md-4">
 <div class="form-group">
 <label>Country</label>
 <input type="text" name="ship_country" class="form-control"   value="United States" disabled/>
  <input type="hidden" name="shipping_address_country"  value="United States"  />
 </div>
 </div>
 <div class="col-md-4">
 <div class="form-group">
 <label>State</label>
 <input type="text" name="shipping_address_county" class="form-control"   value="<?=$orr['shipping_address_county']?>" />

 </div>
 </div> 
 <div class="col-md-4">
 <div class="form-group">
 <label>Zip Code</label>
 <input type="text" name="shipping_address_pincode" class="form-control"   value="<?=$orr['shipping_address_pincode']?>"/>
 </div>
 </div> 
 
 </div>
</div> 
</div>


</form>
</div>
    <?
        
    }?>
<div id="passwordModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body text-center">
      <form method="post" action="/api/widget/json/get/Bootstrap Theme - Member Login Page" form_action_type="redirect" id="member_login1">
      <input type="hidden" name="action" value="loggedout">
<input type="hidden" name="sized" value="0">
<input type="hidden" name="form" value="myform">
<input type="hidden" name="formname" value="member_login">
<input type="hidden" name="dowiz" value="1" >
<input type="hidden" name="save" value="1">
<input type="hidden" name="action" autocomplete="off" value="login" >
<input type="hidden" name="email" id="modal_email" value=""/>
       <h3>You are an existing user please confirm your password.</h3>
       <div><input type="password" name="pass" class="form-control confirm_password" value="" placeholder="Confirm Password"/>
       <p>Forgot your password? <a href="/login/retrieval" target="_blank">Click Here</a></p></div>
       <div><input type="submit" class="btn btn-primary btn-sm confirm_password_btn" value="Confirm">&nbsp;<a class="btn btn-danger btn-sm confirm_cancel" href="#">Cancel</a></div>
       </form>
       
      </div>
      
    </div>

  </div>
</div>
<script>
var userid="<?=$_COOKIE['userid']?>";
var type="<?=$_GET['type']?>";
$(document).ready(function(e) {
    if(userid=="")
            {
                var ele_email=$(".check_email").val();
                $.ajax({
        
                            type: "POST",       
                            url: "/api/widget/html/get/ecommerce_ajax_actions",     
                            data: {'action':"check_user_email",'email':ele_email},      
                            cache: false,       
                            success: function (response) {
                                if(response==1)
                                {
                                    $("#modal_email").val(ele_email);
                                       $("#passwordModal").modal();
                                }
                                
                            }
            });
            }
    $(".confirm_cancel").on("click",function(e){
        e.preventDefault();
             $("#passwordModal").modal("hide");
             $(".check_email").val("");
             $(".confirm_password").val("");
             $("#member_login-notification").remove();
        });
    $(".confirm_password_btn").on("click",function(e){
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
                                     notification.html('You have signed into Localbulls in the past with this email. The password you have entered is incorrect. Please try again or use forgot your password link.').addClass("alert-warning");
                                }
                                
                            }
                            });
                        
                    
                }
         });
        });
    $(".check_email").blur(function(){
            if(userid=="")
            {
                var ele=$(this);
                $.ajax({
        
                            type: "POST",       
                            url: "/api/widget/html/get/ecommerce_ajax_actions",     
                            data: {'action':"check_user_email",'email':ele.val()},      
                            cache: false,       
                            success: function (response) {
                                if(response==1)
                                {
                                    $("#modal_email").val(ele.val());
                                       $("#passwordModal").modal();
                                }
                                
                            }
            });
            }
        });
    $(".same_address").change(function(e){
        var ele=$(this);
        if(ele.is(":checked"))
        {
        $(".cart_ship_addr").hide();
        $(".billing_section").toggleClass("col-md-offset-3");
        $(".cart_ship_addr").toggleClass("col-md-6");
        $(".cart_ship_addr").find(".form-control").removeAttr("required");
        $(".billing_section_head").html("Shipping/Billing Address");
        }
        else
        {
        $(".cart_ship_addr").show();
        $(".cart_ship_addr").find(".form-control").attr("required",true);
        $(".billing_section").toggleClass("col-md-offset-3");
        $(".cart_ship_addr").toggleClass("col-md-6");
        $(".billing_section_head").html("Billing Address");
        }
    });
    $(document).on("change","input[name=ship]",function(){
        
        var ele=$(this);
        $(".ship_type").val(ele.val());
        user_ship_type=ele.val();
            if(ele.val()=="ship")
            {
                var tship=parseFloat($(".p_ship").data("tship"));
                $(".p_ship").html(cur_sym+numberFormatter(tship));
                var gtot=0;
                 $(".pro_quantity").each(function(index,element){
                                    
                                        var ele1=$(this);
                                        
                                        gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));
                                        
                                     });
                 
                $("#grand_total").html(cur_sym+numberFormatter(gtot+tship));
                $(".same_address").prop("checked",true);
                $(".billing_section").show();
                $(".billing_section").find(".form-control").prop("required",true);
                $(".suburb_optional").removeAttr("required");
                $(".billing_section").removeClass("col-md-12");
                $(".billing_section").addClass("col-md-6");
                $(".billing_section").addClass("col-md-offset-3");
                $(".billing_section_head").html("Shipping/Billing Address");
                $(".cart_ship_addr").removeClass("col-md-6");
                $(".cart_ship_addr").hide();
                $(".cart_ship_addr").find(".form-control").removeAttr("required");
                $(".pickup_addr").hide();
                    $(".pickup_addr").removeClass("col-md-6");
                    $(".ship_same").show();
            }else if(ele.val()=="pick")
            { 
                $(".p_ship").attr("data-ship",0);
                $(".p_ship").html(0);
                var gtot=0;
                 $(".pro_quantity").each(function(index,element){
                                    
                                        var ele1=$(this);
                                        
                                        gtot+=(parseFloat(ele1.data('tot'))*parseFloat(ele1.val()));
                                        
                                     });
                 
                $("#grand_total").html(cur_sym+numberFormatter(gtot));
                $(".same_address").prop("checked",false);
                $(".billing_section").show();
                $(".billing_section").find(".form-control").prop("required",true);
                
                $(".suburb_optional").removeAttr("required");
                 
                $(".cart_ship_addr").hide();
                $(".cart_ship_addr").find(".form-control").removeAttr("required");
                $(".pickup_addr").show();
                $(".pickup_addr").toggleClass("col-md-6");

                $(".billing_section").removeClass("col-md-offset-3");
                $(".billing_section_head").html("Billing Address");
                $(".ship_same").hide();
                }
            });
        $(".ship_select").on("change",function(){
            $(".cart_loading").show();
                var ele=$(this);
                $("input[name=ship_type_"+ele.data("groupid")+"]").val(ele.val());
                var ship_pro=parseFloat(ele.attr("data-tship"));
                var tot_ship=parseFloat($(".p_ship").attr("data-tship"));
                if(ele.val()=="pick")
                {
                    
                    var rem_ship=tot_ship-ship_pro;
                    $(".p_ship").attr("data-tship",rem_ship);
                     $(".p_ship").html("<?=$w['currency_symbol']?>"+numberFormatter(rem_ship));
                }
                else{
                    var rem_ship=tot_ship+ship_pro;
                    $(".p_ship").attr("data-tship",rem_ship);
                     $(".p_ship").html("<?=$w['currency_symbol']?>"+numberFormatter(rem_ship));
                }
                var gtot=0;
                $(".pro_quantity").each(function(index,element){
                        var ele1=$(this);
                        var ship=0;
                        gtot+=parseFloat(parseFloat(ele1.attr("data-tot"))*parseFloat(ele1.val()));
                        if($("input[name=ship_select_"+ele1.data("gid")+"]:checked").val()=="ship") ship=parseFloat(ele1.attr("data-tship"));
                        else ship=0;
                        gtot+=ship;
                        
                        
                        
                    });
                    $('#grand_total').html(cur_sym+numberFormatter(gtot));
                                    
                
                if($(".ship_row:checked").length<=0)
                {
                    $(".billing_section_head").text("Billing Address");
                    if(!$(".billing_section").hasClass("col-md-offset-3")) $(".billing_section").addClass("col-md-offset-3");
                    $(".cart_ship_addr").hide();
                    $(".cart_ship_addr").find(".form-control").removeAttr("required");
                    $("#same_address").hide();
                    $(".cart_ship_addr").removeClass("col-md-6");
                }
                else
                { 
                    $(".billing_section_head").text("Shipping/Billing Address");
                    $("#same_address").show();
                    $(".same_address").prop("checked",true);
                    if(!$(".billing_section").hasClass("col-md-offset-3"))$(".billing_section").addClass("col-md-offset-3");
                    $(".cart_ship_addr").hide();
                    $(".cart_ship_addr").removeClass("col-md-6");
                    $(".cart_ship_addr").find(".form-control").removeAttr("required");
                 
                }
                $.ajax({
        
                            type: "POST",       
                            url: "/api/widget/html/get/ecommerce_ajax_actions",     
                            data: {'action':"change_ship_cart",'gid':ele.data('groupid'),'type':ele.val()},     
                            cache: false,       
                            success: function (response) {
                                $(".cart_loading").hide();
                            }
                });
            });
     
    /*$(".continue_btn").click(function(e){
        console.log($('#cart_form'));
             $('#cart_form').formValidation({"framework":"bootstrap"}).on('success.form.fv', function(e,fvdata) {

                e.preventDefault();
                var $form = $(e.target);
                 var   fv = $form.data('formValidation');
                var values = $(this).serialize();
                var form_action_type="redirect";
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
                            url:"/api/widget/html/get/car_finder_ajax_actions",
                            type:"POST",
                            data: values,
                            dataType: "json",
                            success: function (data) {
                                
                                alert("success");
                            }
                            });
                }
             });
                    
            
        });*/
});

</script>