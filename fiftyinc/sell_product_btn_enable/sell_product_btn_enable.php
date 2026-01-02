<?
     
if($pars[0]=="account" && ($pars[2]=="newgroup"||$pars[2]=="editgroup"))
{
$g['shop_type']=explode(",",$g['shop_type']);

?>

<div class="form-group" style="display:none;">
  <div class="radio-inline ">
    <label>
      <input type="radio" name="ecom_sale" autocomplete="off" value="0" <? if($g['ecom_sale']=="0" || $g['ecom_sale']=="") echo "checked"; ?>>
      Ecommerce Direct</label>
  </div>
  <div class="radio-inline ">
    <label>
      <input type="radio" name="ecom_sale" autocomplete="off" value="1" <? if($g['ecom_sale']=="1" ) echo "checked"; ?>>
      Reglamed Ecommerce </label>
  </div>
</div>
<input type="hidden" name="ecom_sale" value="1">
<div class="ecom_sale">
  <div class="form-group">
    <div class="checkbox">
      <label>
        <input  type="checkbox" class="shop_type" name="shop_type[]" autocomplete="off" value="ship" <? if(in_array("ship",$g['shop_type']) || sizeof($g['shop_type'])==0) echo "checked"; ?>>
        Shipping </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="shop_type[]" class="shop_type" autocomplete="off" value="pick" <? if(in_array("pick",$g['shop_type'])) echo "checked"; ?> >
        Pick Up </label>
    </div>
  </div>
  <div class="post_for_sale" style="display:<? if(in_array("ship",$g['shop_type'])) echo "block";else echo "none"; ?>">
    <div class="form-group" style="display:none;">
      <div class="radio-inline ">
        <label>
          <input  type="radio" name="ship_type" autocomplete="off" value="all" <? if($g['ship_type']=="all"||$g['ship_type']=="") echo "checked"; ?>>
          One shipping price for unlimited item </label>
      </div>
      <div class="radio-inline" style="display:none;">
        <label>
          <input type="radio" name="ship_type" autocomplete="off" value="per" <? if($g['ship_type']=="per") echo "checked"; ?> >
          Varied shipping prices </label>
      </div>
    </div>
    <div class="form-group shipping_cost" style="display:<? if($g['ship_type']=="all" || $g['ship_type']=="") echo "block"; else echo "none";?>" >
      <label>Shipping Cost</label>&nbsp;<span>( Enter $0 for no shipping cost. )</span>
      <input  type="text" name="car_post_shipping" class="form-control" value="<?=$g['car_post_shipping']?>" required />
      <input  type="hidden" name="car_post_shipping_price" class="form-control" value="<?=$g['car_post_shipping_price']?>" required />
      
    </div>
    <div class=" ship_per_item" style="display:<? if($g['ship_type']=="per") echo "block"; else echo "none";?>" >
      <label>Shipping Costs</label>
      <div class="form-group ">
        <div>
          <label style="float: left;margin-right: 2px;">1</label>
          <input style="float:left;width:98%;"  type="text" name="car_post_shipping_1" class="form-control" value="<?=$g['car_post_shipping_1']?>" required />
          <input  type="hidden" name="car_post_shipping_price_1" class="form-control" value="<?=$g['car_post_shipping_price_1']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">2</label>
          <input  type="text" style="float:left;width:98%;" name="car_post_shipping_2" class="form-control" value="<?=$g['car_post_shipping_2']?>" required />
          <input  type="hidden" name="car_post_shipping_price_2" class="form-control" value="<?=$g['car_post_shipping_price_2']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">3</label>
          <input  type="text" name="car_post_shipping_3" style="float:left;width:98%;"  class="form-control" value="<?=$g['car_post_shipping_3']?>" required />
          <input  type="hidden" name="car_post_shipping_price_3" class="form-control" value="<?=$g['car_post_shipping_price_3']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">4</label>
          <input  type="text" name="car_post_shipping_4" class="form-control" style="float:left;width:98%;"  value="<?=$g['car_post_shipping_4']?>" required />
          <input  type="hidden" name="car_post_shipping_price_4" class="form-control" value="<?=$g['car_post_shipping_price_4']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">5</label>
          <input  type="text" name="car_post_shipping_5" style="float:left;width:98%;" class="form-control" value="<?=$g['car_post_shipping_5']?>" required />
          <input  type="hidden" name="car_post_shipping_price_5" class="form-control" value="<?=$g['car_post_shipping_price_5']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">6</label>
          <input  type="text" name="car_post_shipping_6" style="float:left;width:98%;" class="form-control" value="<?=$g['car_post_shipping_6']?>" required />
          <input  type="hidden" name="car_post_shipping_price_6" class="form-control" value="<?=$g['car_post_shipping_price_6']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">7</label>
          <input  type="text" name="car_post_shipping_7" style="float:left;width:98%;" class="form-control" value="<?=$g['car_post_shipping_7']?>" required />
          <input  type="hidden" name="car_post_shipping_price_7" class="form-control" value="<?=$g['car_post_shipping_price_7']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">8</label>
          <input  type="text" name="car_post_shipping_8" style="float:left;width:98%;" class="form-control" value="<?=$g['car_post_shipping_8']?>" required />
          <input  type="hidden" name="car_post_shipping_price_8" class="form-control" value="<?=$g['car_post_shipping_price_8']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">9</label>
          <input  type="text" name="car_post_shipping_9" style="float:left;width:98%;" class="form-control" value="<?=$g['car_post_shipping_9']?>" required />
          <input  type="hidden" name="car_post_shipping_price_9" class="form-control" value="<?=$g['car_post_shipping_price_9']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="form-group">
        <div>
          <label style="float: left;margin-right: 2px;">10</label>
          <input  type="text" name="car_post_shipping_10" style="float:left;width:97%;" class="form-control" value="<?=$g['car_post_shipping_10']?>" required />
          <input  type="hidden" name="car_post_shipping_price_10" class="form-control" value="<?=$g['car_post_shipping_price_10']?>" required />
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <div class="form-group product_delivery" >
      <label>Ships Within</label>
      <select class="form-control" name="car_post_estimated_delivery_time">
		  <option value="" <? if($g['car_post_estimated_delivery_time']=="") echo "selected"; ?>>Select Ships Within</option>
		   <option value="Onetwodays" <? if($g['car_post_estimated_delivery_time']=="Onetwodays") echo "selected"; ?>>1-2 business days</option>
		   <option value="Threefourdays" <? if($g['car_post_estimated_delivery_time']=="Threefourdays") echo "selected"; ?>>3-4 business days</option>
		   </select>
    </div>
    <div class="form-group product_service" style="display:none;">
      <label>Delivery Service</label>
      <select class="form-control" name="car_post_deliver_service">
        <option value="" <? if($g['car_post_deliver_service']=="") echo "selected"; ?>>Select Delivery Service</option>
        <option value="24 Hour" <? if($g['car_post_deliver_service']=="24 Hour") echo "selected"; ?> >24 Hour</option>
        <option value="48 Hour" <? if($g['car_post_deliver_service']=="48 Hour") echo "selected"; ?>>48 Hour</option>
        <option value="Economy" <? if($g['car_post_deliver_service']=="Economy") echo "selected"; ?>>Economy</option>
      </select>
    </div>
  </div>
</div>
<div class="form-group web_link"  style="display:none">
  <label class="vertical-label bd-url">External Web Link</label>
  <input type="url" name="post_uri" autocomplete="off" value="<?php echo $g['post_uri'] ?>" class="form-control website_url_field" required>
</div>
<!--<div class="form-group product_quantity" >
  <label>Quantity Available</label>
  <input  type="text" name="car_post_quantity" class="form-control" value="<?=$g['car_post_quantity']?>" required />
  <span class="help-block form-field-help-block">If you have have 1000's of this product available then enter amount of maximum order would be.</span>
</div>-->
<?
if(in_array("pick",$g['shop_type'])) $pick=1;else $pick=0;
  ?>
<script>
 $(document).ready(function(e) {
     var pro_avial="<?=$g['post_availability']?>";
     var shop_type="<?=$pick?>";
     if(shop_type=="1")
                {
                    $("textarea[name=post_location]").parents(".form-group").show();
                }else{
                $("textarea[name=post_location]").parents(".form-group").hide();
                }
     
     if(pro_avial=="Pre-Order")
            {
                $(".pre_order_delivery").parents(".form-group").show();
            }
            else
            {
                $(".pre_order_delivery").parents(".form-group").hide();
            }
     $(".car_post_type").change(function(e){
        var ele=$(this);
        if(ele.val()=="1")
        {
        $(".post_for_sale").hide();
        }
        else if(ele.val()=="2")
        {
        $(".post_for_sale").show();
        
        }
     });
     $('input[name="car_post_shipping"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      // $('input[name="car_post_quantity"]').autoNumeric('init', {aForm: false, aSign:"", mDec: '0' });
    $('input[name="car_post_shipping"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping"]').val('');
        }
        $('input[name="car_post_shipping"]').closest("form").formValidation('revalidateField', 'car_post_shipping');
    });
    
         $('input[name="car_post_shipping_1"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_1"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_1']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_1"]').val('');
        }
        $('input[name="car_post_shipping_1"]').closest("form").formValidation('revalidateField', 'car_post_shipping_1');
    });
     $('input[name="car_post_shipping_2"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_2"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_2']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_2"]').val('');
        }
        $('input[name="car_post_shipping_2"]').closest("form").formValidation('revalidateField', 'car_post_shipping_2');
    });
     $('input[name="car_post_shipping_3"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_3"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_3']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_3"]').val('');
        }
        $('input[name="car_post_shipping_3"]').closest("form").formValidation('revalidateField', 'car_post_shipping_3');
    });
     $('input[name="car_post_shipping_4"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_4"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_4']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_4"]').val('');
        }
        $('input[name="car_post_shipping_4"]').closest("form").formValidation('revalidateField', 'car_post_shipping_4');
    });
     $('input[name="car_post_shipping_5"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_5"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_5']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_5"]').val('');
        }
        $('input[name="car_post_shipping_5"]').closest("form").formValidation('revalidateField', 'car_post_shipping_5');
    });
     $('input[name="car_post_shipping_6"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_6"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_6']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_6"]').val('');
        }
        $('input[name="car_post_shipping_6"]').closest("form").formValidation('revalidateField', 'car_post_shipping_6');
    });
     $('input[name="car_post_shipping_7"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_7"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_7']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_7"]').val('');
        }
        $('input[name="car_post_shipping_7"]').closest("form").formValidation('revalidateField', 'car_post_shipping_7');
    });
     $('input[name="car_post_shipping_8"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_8"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_8']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_8"]').val('');
        }
        $('input[name="car_post_shipping_8"]').closest("form").formValidation('revalidateField', 'car_post_shipping_8');
    });
     $('input[name="car_post_shipping_9"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_9"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_9']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_9"]').val('');
        }
        $('input[name="car_post_shipping_9"]').closest("form").formValidation('revalidateField', 'car_post_shipping_9');
    });
     $('input[name="car_post_shipping_10"]').autoNumeric('init', {aForm: false, aSign:"<?=$w['currency_symbol']?>" });
      
    $('input[name="car_post_shipping_10"]').keyup(function() {
        var post_promo = $(this).val().replace(/,/g , "" ).replace("<?=$w['currency_symbol']?>","");
        $("input[name='car_post_shipping_price_10']").val(post_promo);
        if(post_promo == '' || parseInt(post_promo) < 0){
            $('input[name="car_post_shipping_10"]').val('');
        }
        $('input[name="car_post_shipping_10"]').closest("form").formValidation('revalidateField', 'car_post_shipping_10');
    });
    
    $(".pro_availability").change(function(e){
            var ele=$(this);
            if(ele.val()=="Pre-Order")
            {
                $(".pre_order_delivery").parents(".form-group").show();
            }
            else
            {
                $(".pre_order_delivery").parents(".form-group").hide();
            }
        });
        $(".shop_type").change(function(e){
                var ele=$(this);
                if(ele.is(":checked"))
                {
                    if(ele.val()=="pick")
                    {
                        $("textarea[name=post_location]").parents(".form-group").show();
                        $('#pac-input').closest('.well').show();
						
                    }
                    else
                    {$(".post_for_sale").show();}
                }
                else
                {
                    if(ele.val()=="pick")
                    {
                        $("textarea[name=post_location]").parents(".form-group").hide();
                        $('#pac-input').closest('.well').hide();
                    }
                    else
                    {$(".post_for_sale").hide();
					$("input[name=car_post_shipping]").val(0);
						$("input[name=car_post_shipping_price]").val(0);
						$("input[name=car_post_shipping_1]").val(0);
						$("input[name=car_post_shipping_price_1]").val(0);
						$("input[name=car_post_shipping_2]").val(0);
						$("input[name=car_post_shipping_price_2]").val(0);
						$("input[name=car_post_shipping_3]").val(0);
						$("input[name=car_post_shipping_price_3]").val(0);
						$("input[name=car_post_shipping_4]").val(0);
						$("input[name=car_post_shipping_price_4]").val(0);
						$("input[name=car_post_shipping_5]").val(0);
						$("input[name=car_post_shipping_price_5]").val(0);
						$("input[name=car_post_shipping_6]").val(0);
						$("input[name=car_post_shipping_price_6]").val(0);
						$("input[name=car_post_shipping_7]").val(0);
						$("input[name=car_post_shipping_price_7]").val(0);
						$("input[name=car_post_shipping_8]").val(0);
						$("input[name=car_post_shipping_price_8]").val(0);
						$("input[name=car_post_shipping_9]").val(0);
						$("input[name=car_post_shipping_price_9]").val(0);
						$("input[name=car_post_shipping_10]").val(0);
						$("input[name=car_post_shipping_price_10]").val(0);}
                }
            
            });
            $("input[name=ship_type]").change(function(){
                var ele=$(this);
                 
                if(ele.val()=="all")
                {
                    $(".shipping_cost").show();
                    $(".ship_per_item").hide();
                }
                else{$(".shipping_cost").hide();
                    $(".ship_per_item").show();}
                });
                
        $("input[name='ecom_sale']").change(function(){
                var ele=$(this);
                if(ele.val()=="0")
                {$(".ecom_sale").hide();$(".web_link").show();}
                else
                {$(".ecom_sale").show();$(".web_link").hide();}
            });
});

 
 </script>
<? }else if($pars[0]=="sell-products" || $pars[0]=="products"){
    if($group['shop_type']=="ship")
    {
    ?>

<div class="table-view-group clearfix">
  <li class="col-sm-4 bold"> Shipping Cost </li>
  <li class="col-sm-8">
    <?=$group['car_post_shipping']?>
  </li>
</div>
<div class="table-view-group clearfix">
  <li class="col-sm-4 bold"> Estimated Delivery Time </li>
  <li class="col-sm-8">
    <?=$group['car_post_estimated_delivery_time']?>
  </li>
</div>
<div class="table-view-group clearfix">
  <li class="col-sm-4 bold"> Delivery Service </li>
  <li class="col-sm-8">
    <?=$group['car_post_deliver_service']?>
  </li>
</div>
<?  }?>
<div class="table-view-group clearfix">
  <li class="col-sm-4 bold"> Quantity Available </li>
  <li class="col-sm-8">
    <?=$group['car_post_quantity']?>
  </li>
</div>
<? }?>
