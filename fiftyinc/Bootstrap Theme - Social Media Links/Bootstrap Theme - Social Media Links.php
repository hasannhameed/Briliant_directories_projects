<div class="list-social-links">
	<a class="network-icon contact" href="/about/contact" title="Contact <?=$w[website_name]?>">
		<i class="fa fa-envelope"></i>
	</a> 
	<? if ($w[facebook_page]!="") { ?>
		<a class="network-icon facebook" href="<?=$w[facebook_page]?>" target="_blank" title="<?=$w[website_name]?> Facebook">
			<i class="fa fa-facebook"></i>
		</a>
	<? } ?>
	<? if ($w[google_profile]!="") { ?>
		<a class="network-icon googleplus" rel="publisher" href="<?=$w[google_profile]?>" target="_blank" title="<?=$w[website_name]?> Google"  rel="publisher">
			<i class="fa fa-google-plus"></i>
		</a>
	<? } ?>
	<? if ($w[twitter]!="" || $w[44] != "") { ?>
		<a class="network-icon twitter" href="<?if($w[twitter] != ""){ echo $w[twitter];}else{ echo $w[44];}?>" target="_blank" title="<?=$w[website_name]?> Twitter">
			<i class="fa fa-twitter"></i>
		</a>
	<? } ?>     
	<? if ($w[pinterest]!="" || $w[56] != "") { ?>
		<a class="network-icon pinterest" href="<?if($w[pinterest] != ""){ echo $w[pinterest];}else{ echo $w[56];}?>" target="_blank" title="<?=$w[website_name]?> Pinterest">
			<i class="fa fa-pinterest-p"></i>
		</a>
	<? } ?>      
	<? if ($w[linkedin]!="" || $w[43] != "") { ?>
		<a class="network-icon linkedin" href="<?if($w[linkedin] != ""){ echo $w[linkedin];}else{ echo $w[43];}?>" target="_blank" title="<?=$w[website_name]?> LinkedIn">
			<i class="fa fa-linkedin"></i>
		</a>
	<? } ?>
	<? if ($w[youtube]!="" || $w[74] != "") { ?>
		<a class="network-icon youtube" href="<?if($w[youtube] != ""){ echo $w[youtube];}else{ echo $w[74];}?>" target="_blank" title="<?=$w[website_name]?> YouTube">
			<i class="fa fa-youtube"></i>
		</a>
	<? } ?> 
	<? if ($w[instagram]!="" || $w[98] != "") { ?>
		<a class="network-icon instagram" href="<?if($w[instagram] != ""){ echo $w[instagram];}else{ echo $w[98];}?>" target="_blank" title="<?=$w[website_name]?> Instagram">
			<i class="fa fa-instagram"></i>
		</a>
	<? } ?>
	
</div>