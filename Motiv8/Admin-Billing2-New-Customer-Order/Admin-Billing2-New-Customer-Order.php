<? 

require_once $w['flocation']."/functions_payment.php";
require_once $w['flocation']."/functionsWhmcs.php";

global $vals;

if ($_REQUEST['userid']!="") {

	$user=getUser($_REQUEST['userid'],$w);
	$_ENV['clientid']=$user['clientid'];
	
	ob_start();
echo nestedMenuArray("whmcs_admin_member",0,$w,1);
$_ENV['whmcs_member_menu']=ob_get_contents();
	ob_end_clean();
	
	if ($user['user_id']<1) { echo "User ID is invalid or no longer exists."; exit; }
	else {
	$gateway 	= getGateway($w['whmcs_payment_gateway'],$w);
	$client 	= getWebsiteWhmcs("getclientsdetails",array("clientid"=>$user['clientid']),$w);
	
	if(isset($gateway['gateway_name']) && $gateway['gateway_name'] == 'stripe' && isset($client['id'])){
		$stripeController = new stripeClient($client['id'],'');
  		
  		if( $client['cclastfour'] != '' && $stripeController->collectCardInfo()){
            $stripeController->updateWHMCSClient();
            $stripeController->fillUpClient($client);
        }else if($stripeController->collectCardInfo()){
            $stripeController->fillUpClient($client);
        }
	}

	if ($client['cclastfour']!="") {
		$client['label']="success";
		$client['action']="Edit";
	} else {
		$client['label']="danger";
		$client['action']="Add";
	}
?>

<form name="recurform" id="migrate_legacy_subscription" action="/admin/go.php" method="post" class="form-vertical payment-form">
<input type="hidden" name="action" value="addorder">
<input type="hidden" name="subaction" value="addorder">
<input type="hidden" name="apitype" value="json">
<input type="hidden" name="widget" value="Website-Billing2-Functions">
<input type="hidden" name="userid" value="<?=$user['user_id']?>">

	
	<div class="row">
		
<div class="col-sm-6 box-card">

              
				<h2>Create New Order: <div class="btn-group quick-member quick-member-success">
	  
	  <? if (strlen(trim($user['full_name']))<5) { $user['full_name']="Member #".$user['user_id']; } ?>
	  <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" type="button"><?=$user['full_name']?> <span class="caret"></span>
	
						
						<span class="label label-lg label-<?=$client['label']?>" style="display:inline-block;position:relative;left:0px;bottom:0px;"><i class="fa fa-credit-card" style="border:0px;margin:0px;padding:1px;" title="Credit card on file"></i></span> 
						</button>
						  <ul class="dropdown-menu">
							<li>
								<a href="/admin/whmcs2.php?faction=addcreditcard&amp;apitype=json&amp;userid=<?=$user['user_id']?>" id="link156" class="updatecreditcard"><?=$client['action']?> Credit Card</a> 
							  </li>
						  </ul>
  </div></h2>	 
	<? if ($client['cclastfour']=="" && $client['cardtype']=="") { ?>	
			<div class="alert alert-warning fade in col-xs-12" style="margin-top:-12px;font-size:13px;">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				This member does not have a credit card on file.<a href="/admin/whmcs2.php?faction=addcreditcard&amp;apitype=json&amp;userid=<?=$user['user_id']?>" id="link156" class="btn btn-xs btn-primary updatecreditcard" style="margin-left:15px;">Add Card</a>
			</div>
	<? } ?>
                 <div class="clear"></div>
              
	
	


                       <div class="form-group">
                        <label for="concept" class="control-label">Membership Plan</label>
      <?
		 $sub=mysql($w['database'],"SELECT * FROM `subscription_types` WHERE `profile_type`='paid' ORDER BY `subscription_name` ASC");
		$totalsubsriptions=mysql_num_rows($sub);
		if ($totalsubsriptions<2) { $totalsubsriptions=2; }
		$totalsubsriptions=1;
		?>
                                <select name="pid" id="pid" class="select2" size="<?=$totalsubsriptions?>" start_size="<?=$totalsubsriptions?>" style="width:100%;font-size:14px;" onchange="getProductCycles(this.value,'gu');"><option value="" selected="selected">No membership plan selected</option>
    <?
        while ($s=mysql_fetch_array($sub)) {
            $s=getSubscription($s['subscription_id'],$w);
			if ($s['yearly_amount']>0 || $s['semiyearly_amount']>0 || $s['initial_amount']>0 || $s['monthly_amount']>0 || $s['quarterly_amount']>0) { 
             echo "<option value='".$s['pid']."'>".$s['subscription_name']."</option>"; 
			}
		}
    ?>  
    </select>
						
                        
                    </div>
	<div  id="billingcyclediv">


            		<div class="row">
								
						
					<div class="col-xs-12">
					<div class="form-group">
                        <label for="amount" class="control-label">Billing Cycle</label>    
                        
						
							    <select style="overflow:hidden;padding:4px;" size=1 class="form-control" id="paymentype" name="billingcycle" onchange="updatesummary()">								
                            </select>	
						
						
                    </div>
						</div>
						
								
						</div>			
				<div class="row">
					<div class="col-xs-6">
							<div class="form-group">
								<label for="amount" class="control-label">Price Override:</label>
								<div class="input-group">
									<span class="input-group-addon input-prepend" id="currencyprefix"><?=$w['member_feature_currency']?></span>
									<input type="text" class="form-control" id="priceoverride" name="priceoverride" value="<?=$vals['AMT']?>" onkeyup="updatesummary()"> <span class="input-group-addon input-append" id="currencysuffix"><?=$w['currency_code']?></span>
								</div>
								
							</div>
						</div>	
				  <div class="col-xs-6">
                    <div class="form-group">
                        <label for="invoicedate" class="control-label">Start Billing On</label>
                        
							<div class="input-group input-append date" id="dateRangePicker">
								<? if ($w['default_date_format']=="d/m/Y") { $default_date=date($w['default_date_format']); } else { $default_date=date("m/d/Y"); } ?>
								<input type="text" class="invoicedate form-control" id="date" value="<?=date($w['default_date_format'])?>" name="nextduedate">
								<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>
						
                    </div>
				</div>
				</div>
							<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label for="order_notes" class="control-label">Admin Notes:</label>    
								<textarea class="form-control" name="order_notes" placeholder="Enter info to save with the subscription"></textarea>
							</div>
						</div>
					</div>
				<div class="row">
				<div class="col-xs-3" style="display:none;">
						<div class="form-group">
							<label for="promocode" class="control-label">Promo:</label>    
							<input type="text" class="form-control" name="promocode" id="promocode" onchange="updatesummary()">
						</div>
					</div>
			
					<div class="col-xs-6" style="display:none;">
				<div class="form-group">
					<label for="amount" class="control-label">Status</label>    
					<select class="form-control" id="status" name="status" onchange="updatesummary()">
						<option value="Active" selected="selected">Active</option>
						<option value="Pending">Pending</option>
                    </select>
				</div>
			</div>

	</div>
				
			</div>
					</div>
				  <div class="col-sm-6 box-card">
					  
				 	<div id="ordersumm"></div>
					  
					
					 <div class="form-group" id="buttonrow">
						 <div class="col-xs-6">
								<button type="reset" class="resetbutton btn btn-sm btn-default">Start Over</button>
							 
							 
							</div>
							<div class="col-xs-6">
								<button type="submit" id="savebutton" class="btn btn-md btn-success pull-right disabled">Create Order</button>	
							</div>
					  </div>
		</div>
	</div>	
		
				</form>				
<? } } ?>