<?php
require_once "$w[flocation]/functionsWhmcs.php";

$user = getUser($_COOKIE['userid'], $w);
$gateway = getGateway($w['whmcs_payment_gateway'], $w);
$client_details = whmcsActions("getclientsdetails", $user['user_id'], $w);
$stripe = stripeClient::billingGatewaySettings($w);


if(brilliantDirectories::isStripePaymentGateway() === true && isset($client_details['id']) && strlen($client_details['cclastfour']) <= 0 ) {
  $stripeController = new stripeClient($client_details['id'],'');
  $stripeController->collectCardInfo();
  $stripeController->fillUpClient($client_details);
}

$http = "http";

if ($w['https_frontend'] == 1 || $w['https_redirect'] == 1) {
    $http = "https";

}

/* billingDataAction("button","admin_order_new",$w) */

if ($gateway[payment_type]=="external") {

} else {
    $client_details['ccicon'] = getCardCSS($client_details['cctype']);
?>

<div class="module fpad-lg">
    <h3 class="bold">
        <?php if ($client_details['cclastfour'] != "") { ?>
            %%%billing_details_cc_onfile%%%
        <?php } else { ?>
            %%%billing_details_information%%%
        <?php } ?>
    </h3>
    <hr>
    <div class="row">
        <?php
        if ($client_details['cclastfour'] != "") {
            $_ENV['credit_card_button'] = $label['billing_update_cc_on_file'];
        ?>
        <div class="col-sm-7">
            <div class="form-group nomargin">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="<?php echo $client_details['ccicon']; ?>"></i>
                    </span>
                    <span class="form-control">
                        <?php echo ucwords($client_details['cctype']); ?> 
                        <span class="hidden-xs">%%%cc_ending_in%%% ****</span> 
                        <?php echo $client_details['cclastfour']; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-5 xs-vmargin">
            <?php echo billingDataAction("button","account_credit_card_info",$w); ?>
            <?php echo billingDataAction("button","account_clear_credit_card",$w); ?>
        </div>
        <?php } else { ?>
        <div class="col-xs-12">
            <?php echo billingDataAction("button","account_credit_card_info",$w)?>
        </div>
        <?php } ?>
        <div class="clear clearfix"></div>
    </div>
</div>

<?php    
	
 }
    $whmcsController = new bd_whmcs(
        brilliantDirectories::getDatabaseConfiguration('whmcs_api_host'),
        brilliantDirectories::getDatabaseConfiguration('whmcs_api_username'),
        md5(brilliantDirectories::getDatabaseConfiguration('whmcs_api_password')),
        brilliantDirectories::getDatabaseConfiguration('website_user'),
        brilliantDirectories::getDatabaseConfiguration('ftp_server'),
        $client_details['userid']
    );
    $clientData = $whmcsController->getUserById($client_details['userid']);

    //we only prepare the client data that we want on the billing address form
    $jsonClientData = array(
        'state'         => $clientData['state'],
        'country'       => $clientData['country'],
        'firstname'     => $clientData['firstname'],
        'companyname'   => $clientData['companyname'],
        'id'            => $clientData['id'],
        'address1'      => $clientData['address1'],
        'city'          => $clientData['city'],
        'postcode'      => $clientData['postcode'],
    );

    ?>
        <br />
        <br />
        <div class="row">
            <div class="col-xs-12 well">
                <p class="account-menu-title">%%%cc_billing_address%%%</p>
                <br />
            <?php 
                echo form("whmcs_billing_address",array('view'=>'edit','id'=>$user['user_id']),$w['website_id'],$w, "Bootstrap Theme - Function - Form");
            ?>
            </div>
        </div>
        <script type="text/javascript">

            var clientData = jQuery.parseJSON('<?php echo json_encode($jsonClientData);?>');
            var state_code = $('#state-chained option:contains('+clientData.state+')').val();

            $("select[name=country_code]").val(clientData.country).trigger('change');
            $("input[name=firstname]").val(clientData.firstname);
            $("input[name=companyname]").val(clientData.companyname);
            $("input[name=clientid]").val(clientData.id);
            $("input[name=address1]").val(clientData.address1);
            $("select[name=state_code]").val(state_code).trigger('change');
            $("input[name=city]").val(clientData.city);
            $("input[name=zip_code]").val(clientData.postcode);
            $("input[name=state]").val(clientData.state);

            $("#state-chained").on("change",function(){
                $("input[name=state]").val($("#state-chained option:selected").text());
            });

        </script>
    <?php


if($user['clientid'] > 0 && $client_details['userid'] > 0 && $w['enable_member_billing_history'] == 1) { ?>
<ul class="nav nav-tabs alert bg-primary no-radius-bottom nobpad nomargin payment-history-tabs" id="myTab" role="tablist">
  <li class="nav-item active">
    <a class="nav-link text-default" id="payments-tab" data-toggle="tab" href="#payments" role="tab" aria-controls="payments"
      aria-selected="true">%%%payment_history_billing%%%</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-default" id="subscriptions-tab" data-toggle="tab" href="#subscriptions" role="tab" aria-controls="subscriptions"
      aria-selected="false">%%%subscription_history%%%</a>
  </li>
</ul>
<div class="tab-content billing-information table-responsive" id="myTabContent">
    <?php echo billingDataAction("page","account_my_invoices",$w); ?>
</div>
<?php } ?>

<?php if ($label['billing_assistance'] != "") { ?>
<div class="row" style="margin-top:10px;">
    <div class="col-xs-12">
        <div class="alert alert-warning">
            <?php
            if ($w['whmcs_avs_on'] == 1){
                echo $label['billing_address_confirmation'];
            }
            ?>
            %%%billing_assistance%%% <a href="<?php echo brilliantDirectories::getWebsiteUrl(); ?>/about/contact">%%%contact_us%%%</a>
        </div>
    </div>
</div>
<?php } ?>
<style>
    ul.dropdown-menu{
        z-index:1000px;
    }
    .label {
        font-size: 11px;
    }
    #example_filter {
        position: absolute;
        top: -58px;
        right: 11px;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        vertical-align: middle;
    }
</style>