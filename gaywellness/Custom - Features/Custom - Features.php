<?php if ($user['features'] != "") { ?><div class="clearfix"></div><br><div style="padding-bottom:20px;"><span class="button-header"><strong>Amenities</strong></span><br></div>


<div class="table-view-group clearfix" style="text-align: center">
                                        <li class="col-sm-12" style="display: inline-block"><div class="col-sm-12">
	<?php
if (strpos($user['status_check'], "vaccinated") !== false) { ?>
                <div title="Vaccinated (Covid-19)" class="col-xs-6 col-sm-2 bpad" >              
<img src="/images/COVID.png" >
                </div>
               
                <?php }
	
	if (strpos($user['status_check'], "vaccinated_monkeypox") !== false) { ?>
                <div title="Vaccinated (Monkey-Pox)" class="col-xs-6 col-sm-2 bpad" >              
<img src="/images/MPX.png" >
                </div>
               
                <?php }

            if (strpos($user['features'], "Hot Towels") !== false) { ?>
                <div title="Hot Towels" class="col-xs-6 col-sm-2 bpad" >              
<img src="/images/icons/Heated-Towels--icon-profile-2.png" >
                </div>
               
                <?php }
            if (strpos($user['features'], "Heated Massage Table") !== false) { ?>
                <div title="Heated Massage Table" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
              <img src="/images/icons/Heated-Table--icon-profile-2.png" >
                </div>
               
                <?php }
            if ( strpos($user['features'], "Candles") !== false) { ?>
                <div title="Candles" class="col-xs-6 col-sm-2 bpad " style="display: inline-block">
                     <img src="/images/icons/Candles--icon-profile-2.png" >
                </div>
                
                <?php }
            if ( strpos($user['features'], "Music") !== false) { ?>
                <div title="Music" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                    <img src="/images/icons/Music-icon-profile2.png" >
                </div>
<?php } 
       if ( strpos($user['features'], "Shower") !== false) { ?>
                    <div title="Shower" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                         <img src="/images/icons/Shower--icon-profile-2.png">
                    </div>
<?php } 
       if ( strpos($user['features'], "Free Parking") !== false) { ?>
                    <div title="Free Parking" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                       <img src="/images/icons/Free-Parking--icon-profile-2.png" >
                    </div>
<?php } 
   if ( strpos($user['features'], "Paid Parking") !== false) { ?>
                    <div title="Paid Parking" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                       <img src="/images/icons/Paid-Parking--icon-profile-2.png" >
                    </div>
<?php } 
   if ( strpos($user['features'], "Drinking Water") !== false) { ?>
                    <div title="Drinking Water" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                       <img src="/images/icons/Drinking-Water--icon-profile-2.png" >
                    </div>
<?php } 

   if ( strpos($user['features'], "Aromatherapy Enhanced") !== false) { ?>
                    <div title="Aromatherapy Enhanced" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                       <img src="/images/icons/Aromatherapy-icon-profile-2.png" >
                   </div>
<?php } 

   if ( strpos($user['features'], "Heated Oil") !== false) { ?>
                    <div title="Heated Oil" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                       <img src="/images/icons/Heated-Oil--icon-profile-2.png" >
                   </div>
<?php }
											   if ( strpos($user['features'], "Private Parking") !== false) { ?>
                    <div title="Heated Oil" class="col-xs-6 col-sm-2 bpad" style="display: inline-block">
                       <img src="/images/icons/Private-Parking--icon-profile-2.png" >
                   </div>
<?php } ?>

	</div>
	 </li>
                                    </div>
<?php } ?>