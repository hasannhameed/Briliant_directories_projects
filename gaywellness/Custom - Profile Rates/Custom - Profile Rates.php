<div class="hidden-xs module center" style="text-align: center"><div style=""> 

<?php if ($user['thirty_minutes'] != "" || $user['fourtyfive_minutes'] != "" || $user['sixty_minutes'] != "" || $user['seventyfive_minutes'] != "" || $user['ninety_minutes'] != "" || $user['onehundred_minutes'] != "" || $user['onehundredtwenty_minutes'] != "")  { ?>


<div style="padding-bottom:20px;"><span class="button-header"><strong>In-Studio Rates</strong></span><br></div>
<div style="margin-left:0px;">

<?php if ($user['thirty_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>30<br>min</strong><br><i><small><?php echo $user['thirty_minutes']?></small></i>
</div>
<?php } ?> 
<?php if ($user['fourtyfive_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>45<br>min</strong><br><i><small><?php echo $user['fourtyfive_minutes']?></small></i>
</div>
<?php } ?> 
<?php if ($user['sixty_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>60<br>min</strong><br><i><small><?php echo $user['sixty_minutes']?></small></i>
</div>
<?php } ?> 
<?php if ($user['seventyfive_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>75<br>min</strong><br><i><small><?php echo $user['seventyfive_minutes']?></small></i>
</div>
<?php } ?> 
<?php if ($user['ninety_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>90<br>min</strong><br><i><small><?php echo $user['ninety_minutes']?></small></i>
</div>
<?php } ?> 
<?php if ($user['onehundred_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>100<br>min</strong><br><i><small><?php echo $user['onehundred_minutes']?></small></i>
</div>
<?php } ?> 
<?php if ($user['onehundredtwenty_minutes'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>120<br>min</strong><br><i><small><?php echo $user['onehundredtwenty_minutes']?></small></i>
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
<strong>30<br>min</strong><br><i><small><?php echo $user['thirty_minutes_out']?></small></i>
</div>
<?php } ?> 
<?php if ($user['fourtyfive_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-3 col-md-offset-1 bpad">
<strong>45<br>min</strong><br><i><small><?php echo $user['fourtyfive_minutes_out']?></small></i>
</div>
<?php } ?> 
<?php if ($user['sixty_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>60<br>min</strong><br><i><small><?php echo $user['sixty_minutes_out']?></small></i>
</div>
<?php } ?> 
<?php if ($user['seventyfive_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>75<br>min</strong><br><i><small><?php echo $user['seventyfive_minutes_out']?></small></i>
</div>
<?php } ?> 
<?php if ($user['ninety_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>90<br>min</strong><br><i><small><?php echo $user['ninety_minutes_out']?></small></i>
</div>
<?php } ?> 
<?php if ($user['onehundred_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>100<br>min</strong><br><i><small><?php echo $user['onehundred_minutes_out']?></small></i>
</div>
<?php } ?> 
<?php if ($user['onehundredtwenty_minutes_out'] != "") { ?> 
<div class="col-xs-6 col-md-4 bpad">
<strong>120<br>min</strong><br><i><small><?php echo $user['onehundredtwenty_minutes_out']?></small></i>
</div>
<?php } ?> 

<div class="clearfix"></div>
<br></div>  <?php } ?>

</div></div>