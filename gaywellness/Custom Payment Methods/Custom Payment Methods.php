<?php if ($user['payment_methods'] != "") { ?>
<div style="padding-bottom:20px; padding-top:20px;"><span class="button-header"><strong>Payment Methods</strong></span><br></div>
	<?php if (strpos($user['payment_methods'], "americanexpress") !== false) { ?>
               American Express
               
<?php } 

   if ( strpos($user['payment_methods'], "applepay") !== false) { ?>
   - Apple Pay
     
<?php } 

   if ( strpos($user['payment_methods'], "barter") !== false) { ?>
   - Barter
     
<?php } 

   if ( strpos($user['payment_methods'], "bitcoin") !== false) { ?>
   - Bitcoin
     
<?php } 

   if ( strpos($user['payment_methods'], "cash") !== false) { ?>
   - Cash
     
<?php } 

   if ( strpos($user['payment_methods'], "check") !== false) { ?>
   - Check
     
<?php } 

   if ( strpos($user['payment_methods'], "dash") !== false) { ?>
   - Dash
     
<?php } 

   if ( strpos($user['payment_methods'], "discover") !== false) { ?>
   - Discover
     
<?php } 

   if ( strpos($user['payment_methods'], "ether") !== false) { ?>
   - 
<?php } 

   if ( strpos($user['payment_methods'], "googlewallet") !== false) { ?>
   - Google Wallet
     
<?php } 

   if ( strpos($user['payment_methods'], "interacdebit") !== false) { ?>
   - Interac
     
<?php } 

   if ( strpos($user['payment_methods'], "mastercard") !== false) { ?>
   - Mastercard
     
<?php } 

   if ( strpos($user['payment_methods'], "paypal") !== false) { ?>
   - PayPal
     
<?php } 

   if ( strpos($user['payment_methods'], "quickpay") !== false) { ?>
   - Quick Pay
     
<?php } 

   if ( strpos($user['payment_methods'], "ripple") !== false) { ?>
   - Ripple
     
<?php } 

   if ( strpos($user['payment_methods'], "samsungpay") !== false) { ?>
   - Samsumg Pay
     
<?php } 

   if ( strpos($user['payment_methods'], "square_cash") !== false) { ?>
   - Square Cash
     
<?php } 

   if ( strpos($user['payment_methods'], "venmo") !== false) { ?>
   - Venmo
     
<?php } 

   if ( strpos($user['payment_methods'], "visa") !== false) { ?>
   - Visa
                  
<?php } ?>
<?php if ($user['other_payment'] != "") { ?><?php if ($user['payment_methods'] != "") { ?>
- <?php } ?> <?php echo $user['other_payment']; ?>  <?php } ?><?php } ?>

