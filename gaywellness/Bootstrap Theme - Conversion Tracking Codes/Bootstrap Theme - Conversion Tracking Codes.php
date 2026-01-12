<?php
/**
 * This widget follows BD code standards
 * Widget Name: Bootstrap Theme - Conversion Tracking Codes
 * Short Description: Prints the conversion tracking codes for signup pages
 * Version: 2.0
 * Developed By: Matthew Brooks
 * Collaborators: Jason H.
 */

$conversion_tracking=conversionTrackingCode($b,$w);

?>

    <!-- *******
NOTE ABOUT THIS WIDGET

This widget is where you can place tracking code for affiliate programs, analytics and more.
This widget only loads on pages after a member sign ups or upgrades, so you can track successful sign ups. 

COMMON VARIABLES AFFILIATE TRACKERS USE

FIRST PAYMENT AMOUNT = <?php echo $conversion_tracking['amount']; ?>
RECURRING PAYMENT AMOUNT = <?php echo $conversion_tracking['recurring_amount']; ?>
ORDER ID / MEMBER ID = <?php echo $conversion_tracking['user_id']; ?>
COUPON CODE = <?php echo $conversion_tracking['coupon_code']; ?>
PRODUCT NAME = <?php echo $conversion_tracking['product_name']; ?>
PRODUCT TYPE = <?php echo $conversion_tracking['product_type']; ?> 
FREE TRIAL = <?php echo $conversion_tracking['free_trial']; ?>
FREE TRIAL DAYS = <?php echo $conversion_tracking['free_trial_period']; ?> 
FIRST BILL DATE = <?php echo $conversion_tracking['first_bill_date']; ?> 

ADVANCED VARIABLES AFFILIATE TRACKERS USE
For more advanced tracking variables, they are available if there is an invoice or subscription generated.
$conversion_tracking['invoice']['variable']; /// Invoice Variables from the tblinvoices table
$conversion_tracking['product']['variable']; /// Member Subscription Vars from the tblhosting table

Enter Conversion Tracking Codes To Track Sign Ups & Upgraded Members Below ******* -->

<script>
    po('transactions', 'create', {
        data: {
            key: 'transaction_<?php echo $conversion_tracking['user_id']; ?>',
            amount: <?php echo $conversion_tracking['amount']; ?>,
            customer: {
                key: '<?php echo $conversion_tracking['email']; ?>',
                name: '<?php echo $conversion_tracking['full_name']; ?>',
                email: '<?php echo $conversion_tracking['email']; ?>'
            }
        },
        options: {
            create_customer: true
        }
    });
</script>
<?php
/**** Remove the /// on the next line to show all the variables in the success window. Make a test payment on your website to see them ***/
///echo "<pre>".print_r($conversion_tracking,true)."</pre>";
?>



<?php
$w['shareasale_id'] = trim($w['shareasale_id']);
$w['master_tag_id'] = trim($w['master_tag_id']);
if ($w['shareasale_id']!="" && $w['shareasale_id'] !="12345" && $w['master_tag_id']!="" && $w['master_tag_id'] !="12345") { ?>
    <!-- Shareasale Tracking Code -->
    <img src="https://shareasale.com/sale.cfm?amount=<?php echo $conversion_tracking['amount']; ?>&tracking=<?php echo $conversion_tracking['user_id']; ?>&transtype=sale&merchantID=<?php echo $w['shareasale_id']; ?>" width="1" height="1">
    <script src='https://www.dwin1.com/<?php echo $w['master_tag_id'];?>.js' type='text/javascript' defer='defer'></script>
<?php } ?>

<?php if ($w['URL_TO_PostAffiliatePro'] !="" && $w['URL_TO_PostAffiliatePro'] !="mysite.postaffiliatepro.com") { ?>
    <!-- PostAffiliatePro Tracking Code -->
    <?php
    $productName = urlencode($conversion_tracking['product_name']);
    $imageUrl ="https://".$w['URL_TO_PostAffiliatePro']."/scripts/sale.php?TotalCost=".$conversion_tracking['amount']."&OrderID=".$conversion_tracking['user_id']."&ProductID=".$productName."&data1=".$conversion_tracking['email'];
    ?>
    <img src="<?php echo $imageUrl; ?>" width="1" height="1" >
<?php } ?>

<?php
if($subscription['conversion_tracking_code'] != ''){
    echo widget($subscription['conversion_tracking_code']);
}
?>