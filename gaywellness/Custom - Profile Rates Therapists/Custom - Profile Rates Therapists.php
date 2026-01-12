<?php if ($user['duration1'] != "" || $user['duration2'] != "" )  { ?>


<div style="padding-bottom:20px; padding-top:20px;"><span class="button-header"><strong>Rates</strong></span><br></div>
<div style="margin-left:-15px;">

<?php if ($user['duration1'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong><?php echo $user['duration1']?> </strong><br><i>$<?php echo $user['rate1']?></i>
</div>
<?php } ?> 
<?php if ($user['duration2'] != "") { ?> 
<div class="col-xs-6 col-md-3 bpad">
<strong><?php echo $user['duration2']?> </strong><br><i>$<?php echo $user['rate2']?></i>
</div>
<?php } ?> 

</div>
<?php } ?> 

