<?php
if ($w['verified_member_image'] != "") {
    $verifiedIcon = $w['verified_member_image'];
} else {
    $verifiedIcon = "/images/verified_icon.png";
}
?>
<p>%%%verify_listing_one%%% %%%verify_listing_two%%%</p>
<hr/>
<p class="bold alert alert-default inline-block h4 nomargin">
    %%%verify_listing_badge%%%
    <img style="vertical-align:middle;width:40px;margin-left:5px;" src="<?php echo $verifiedIcon ?>">
</p>
<hr/>
<h2 class="info">%%%verify_listing_required_information%%%</h2>
<p>%%%verify_listing_text%%%</p>
<div class="clearfix"></div>