<div class="hidden-sm hidden-md hidden-lg hidden-xl module center" style="text-align: center"><div style="display: inline-block"> 


<?php if ($user['thirty_minutes'] != "" || $user['fourtyfive_minutes'] != "" || $user['sixty_minutes'] != "" || $user['seventyfive_minutes'] != "" || $user['ninety_minutes'] != "" || $user['onehundred_minutes'] != "" || $user['onehundredtwenty_minutes'] != "")  { ?>


<div style="padding-bottom:20px;"><span class="button-header"><strong>In-Studio Rates</strong></span><br></div>
<div style="margin-left:0px;">

<?php if ($user['thirty_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>30 min</strong><br><i><?php echo $user['thirty_minutes']?></i>
</div>
<?php } ?> 
<?php if ($user['fourtyfive_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>45 min</strong><br><i><?php echo $user['fourtyfive_minutes']?></i>
</div>
<?php } ?> 
<?php if ($user['sixty_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>60 min</strong><br><i><?php echo $user['sixty_minutes']?></i>
</div>
<?php } ?> 
<?php if ($user['seventyfive_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>75 min</strong><br><i><?php echo $user['seventyfive_minutes']?></i>
</div>
<?php } ?> 
<?php if ($user['ninety_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>90 min</strong><br><i><?php echo $user['ninety_minutes']?></i>
</div>
<?php } ?> 
<?php if ($user['onehundred_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>100 min</strong><br><i><?php echo $user['onehundred_minutes']?></i>
</div>
<?php } ?> 
<?php if ($user['onehundredtwenty_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>120 min</strong><br><i><?php echo $user['onehundredtwenty_minutes']?></i>
</div>
<?php } ?> 

<div class="clearfix"></div>
<br>
</div>
<?php } ?> 

<?php if ($user['thirty_minutes_out'] != "" || $user['fourtyfive_minutes_out'] != "" || $user['sixty_minutes_out'] != "" || $user['seventyfive_minutes_out'] != "" || $user['ninety_minutes_out'] != "" || $user['onehundred_minutes_out'] != "" || $user['onehundredtwenty_minutes_out'] != "")  { ?>

<div style="padding-bottom:20px;"><span class="button-header"><strong>Out-Call Rates</strong></span><br></div>
<div style="margin-left:0px;">

<?php if ($user['thirty_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>30 min</strong><br><i><?php echo $user['thirty_minutes_out']?></i>
</div>
<?php } ?> 
<?php if ($user['fourtyfive_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>45 min</strong><br><i><?php echo $user['fourtyfive_minutes_out']?></i>
</div>
<?php } ?> 
<?php if ($user['sixty_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>60 min</strong><br><i><?php echo $user['sixty_minutes_out']?></i>
</div>
<?php } ?> 
<?php if ($user['seventyfive_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>75 min</strong><br><i><?php echo $user['seventyfive_minutes_out']?></i>
</div>
<?php } ?> 
<?php if ($user['ninety_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>90 min</strong><br><i><?php echo $user['ninety_minutes_out']?></i>
</div>
<?php } ?> 
<?php if ($user['onehundred_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>100 min</strong><br><i><?php echo $user['onehundred_minutes_out']?></i>
</div>
<?php } ?> 
<?php if ($user['onehundredtwenty_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>120 min</strong><br><i><?php echo $user['onehundredtwenty_minutes_out']?></i>
</div>
<?php } ?> 

<div class="clearfix"></div>
<br></div>  <?php } ?>


</div></div>