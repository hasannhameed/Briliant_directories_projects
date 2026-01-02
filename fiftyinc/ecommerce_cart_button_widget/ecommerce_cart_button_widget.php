<div class="cart_loading" style="display:none;position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/loading.gif') 50% 50% no-repeat #00000094;"></div>
<? 
$items1=0;
	
	foreach($_SESSION['add_to_cart_products'] as $se)
	{
		$items1+=$se['product_quantity'];
	}

	
	 ?>
<a target="_blank" class="cart-div" data-puser="<?=$_COOKIE['userid']?>" style='display:<? if(sizeof($_SESSION['add_to_cart_products'])>0) echo "block"; else echo "none";?>' href="#"><span><?=$items1?></span><i class="fa fa-shopping-cart cart-icon" aria-hidden="true"></i></a> 
<script>
var user_id="<?=$_COOKIE['userid']?>";
$(document).ready(function(e){
		$(".cart-div").click(function(e){
				e.preventDefault();
				if(user_id=="")
				window.location.href="/cartaction?type=cart";
				else 
				window.location.href="/cartactioncon?type=cart";
			});
	});
</script>