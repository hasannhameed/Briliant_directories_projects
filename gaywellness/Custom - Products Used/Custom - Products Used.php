 <?php if ($user['products_used'] != "") { ?>
 <div class="clearfix"></div>
<div style="padding-bottom:20px; margin-top:20px;"><span class="button-header"><strong>Products Used</strong></span><br></div>

  <div class="member-search-description">
	  
	 <?php echo $user['products_used']; ?><?php if ($user['additional_products'] != "") { ?><?php if ($user['products_used'] != "") { ?>, <?php } ?> <?php echo $user['additional_products']; ?> 
		</div>

<?php } } ?>