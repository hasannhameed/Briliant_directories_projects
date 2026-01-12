<?php if ($user['thirty_minutes_coach'] != "" || $user['fourtyfive_minutes_coach'] != "" || $user['sixty_minutes_coach'] != "" || $user['seventyfive_minutes_coach'] != "" || $user['ninety_minutes_coach'] != "" || $user['onehundred_minutes_coach'] != "" || $user['onehundredtwenty_minutes_coach'] != "")  { ?>


<div style="padding-bottom:20px; padding-top:20px;"><span class="button-header"><strong>Rates</strong></span><br></div>
<div style="margin-left:0px;">

<?php if ($user['thirty_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>30 min</strong><br><i>	<?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?>   
<?php echo $user['thirty_minutes_coach']?></i>
</div>
<?php } ?> 
<?php if ($user['fourtyfive_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>45 min</strong><br><i><?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?>      <?php echo $user['fourtyfive_minutes_coach']?></i>
</div>
<?php } ?> 
<?php if ($user['sixty_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>60 min</strong><br><i><?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?><?php echo $user['sixty_minutes_coach']?></i>
</div>
<?php } ?> 
<?php if ($user['seventyfive_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>75 min</strong><br><i><?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?><?php echo $user['seventyfive_minutes_coach']?></i>
</div>
<?php } ?> 
<?php if ($user['ninety_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>90 min</strong><br><i><?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?><?php echo $user['ninety_minutes_coach']?></i>
</div>
<?php } ?> 
<?php if ($user['onehundred_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>100 min</strong><br><i><?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?><?php echo $user['onehundred_minutes_coach']?></i>
</div>
<?php } ?> 
<?php if ($user['onehundredtwenty_minutes_coach'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong>120 min</strong><br><i><?php if (strpos($user['currency'], "dollar") !== false) { ?>$<?php } ?><?php if (strpos($user['currency'], "pounds") !== false) { ?>£<?php } ?> <?php if (strpos($user['currency'], "euro") !== false) { ?>€<?php } ?><?php echo $user['onehundredtwenty_minutes_coach']?></i>
</div>
<?php } ?> 

</div>
<?php } ?> 

