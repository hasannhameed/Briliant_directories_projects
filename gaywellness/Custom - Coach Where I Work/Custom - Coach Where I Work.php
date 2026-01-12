 <?php if ($user['where_i_work'] != "") { ?>
 <div class="clearfix"></div>
<div style="padding-bottom:20px; margin-top:20px;"><span class="button-header"><strong>Where I Work</strong></span><br></div>

  <div class="member-search-description">
	  
	 <?php if ($user['where_i_work'] == 'remotely_telehealth, my_office') {
      echo 'Remotely (Telehealth), My Office';
   } else {
      echo $user['where_i_work']; 
   } ?>
    
		</div>

  <?php } ?>