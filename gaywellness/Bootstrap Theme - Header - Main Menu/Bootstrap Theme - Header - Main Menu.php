<?php if ($_COOKIE['userid'] > 0 && $wa['members_main_menu'] != '') { 
    $selectedMenu = $wa['members_main_menu'];
} else if ($wa['public_main_menu'] != '') { 
    $selectedMenu = $wa['public_main_menu'];
} else {
    $selectedMenu = 'main_menu';
} ?>
<div class="mobile-main-menu">
	<ul class="sidebar-nav">
	<?php echo menuArray($selectedMenu,0,$w);?>
	</ul>
</div>
<nav class="navbar navbar-default <?php echo $wa['custom_152'];?>">
    <div class="container container-fluid">

      <div class="navbar-header">
		<?php if (($w['website_logo']!='' && $wa['custom_295'] == '2') || ($w['favicon']!='' && $wa['custom_295'] == '3')) { ?>
		<div class="mobile_website_logo">
			<a href="<?php echo brilliantDirectories::getWebsiteURL();?>" title="<?php echo $w['website_name'];?>" class="visible-xs">
				<?php if ($w['website_logo']!='' && $wa['custom_295'] == '2') { 
				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $w['website_logo']);
				?>
					<img alt="Member Profile Image" <?php echo $attr; ?> src="<?php echo trim($w['website_logo']);?>" alt="<?php echo $w['website_name'];?>">
				<?php } else { 
				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $w['favicon']);
				?>
					<img alt="Member Profile Image" <?php echo $attr; ?> src="<?php echo trim($w['favicon']);?>" alt="<?php echo $w['website_name'];?>">
				<?php } ?>
			</a>
		</div>
		<?php } ?>
        <button type="button" class="navbar-toggle collapsed main_menu" data-toggle="collapse" aria-label="main_menu">
			<?php if ($label['mobile_main_menu_icon'] != "") {?>
			%%%mobile_main_menu_icon%%%
			<?php } else { ?>
				<i class="fa fa-bars" aria-hidden="true"></i>
			<?php } ?>
        </button>

        <?php
			if ($_COOKIE['userid'] > 0) { ?>
        		<button type="button" id="member_sidebar_toggle" class="navbar-toggle collapsed pull-left user_sidebar hidden-lg hidden-md">
                    <?php 
                    $user_data = getUser($_COOKIE['userid'],$w);
                    $pic = getUserPhoto($user_data['user_id'],$user_data['listing_type'],$w);
                    if($pic['file'] != '' && $pic['file'] != $w['default_profile_image']){
						echo '<style>#member_sidebar_toggle{padding-left: 28px;}</style><img alt="Member Profile Image" src="'.$pic['file'].'">';
                        echo $label['mobile_profile_sidebar_icon'];
                    } else{
                        if ($label['mobile_profile_sidebar_icon'] != "") { ?>
                            %%%mobile_profile_sidebar_icon%%%
                        <?php } else { ?>
                        	<i class="fa fa-user" aria-hidden="true"></i>
                        <?php } 
                    } ?>
        		</button>
        <?php } ?>

      </div>

      <div class="tablet-menu collapse navbar-collapse nopad" id="bs-main_menu">
        <ul class="tablet-menu-ul nav navbar-nav nav-justified">
          <?php echo menuArray($selectedMenu,0,$w);?>
        </ul>
      </div>
    </div>
</nav>
<!--CSS IF MENU IS FIXED TOP-->
<?php if ($wa['custom_152'] == "navbar-fixed-top" && $wa['custom_44'] != "1" && $page['hide_header_links'] != "1") { ?>
	<style>
		body {
		 	padding-top:50px;
		}
	</style>
<?php }
if ($wa['custom_152'] == "navbar-fixed-bottom") { ?>
	<style>
		body {
  			margin-bottom:40px;
		}
		@media only screen and (min-width: 1024px){
			.header ul.nav.navbar-nav li:hover > ul {
    			position: absolute;
    			bottom: 50px;
    			border-radius: 3px 3px 0 0;
			}
		}
		@media only screen and (max-width: 1024px){
			.header nav.navbar-default {
				top: inherit!important;
			}
		}
	</style>
<?php }
    if ($wa['public_main_menu'] == "hidden_admin_menu" && !$_COOKIE['userid']) { ?>
    <style>
        @media only screen and (max-width:1100px){body .header{margin-top:0}
    </style>
<?php } ?>