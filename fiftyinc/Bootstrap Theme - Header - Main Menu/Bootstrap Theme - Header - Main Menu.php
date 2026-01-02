<div class="mobile-main-menu">
	<ul class="sidebar-nav">
	<?php echo menuArray("main_menu",0,$w);?>
	</ul>
</div>
<nav class="navbar navbar-default <?php echo $wa[custom_152];?>">
    <div class="container container-fluid">

      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed main_menu" data-toggle="collapse">
			<?php if ($label['mobile_main_menu_icon'] != "") {?>
			%%%mobile_main_menu_icon%%%
			<?php } else { ?>
				<i class="fa fa-bars" aria-hidden="true"></i>
			<?php } ?>
        </button>
        <button type="button" class="navbar-toggle collapsed btn btn_get_listed bold searching" style="margin-top: 7px;float:right;margin-right:10px;    padding: 5px 9px;"><i class="fa fa-search"></i></button>

        <?php
			if ($_COOKIE['userid'] > 0) { ?>
        		<button type="button" id="member_sidebar_toggle" class="navbar-toggle collapsed pull-left user_sidebar">
					<?php if ($label['mobile_profile_sidebar_icon'] != "") {?>
					%%%mobile_profile_sidebar_icon%%%
					<?php } else { ?>
						<i class="fa fa-user" aria-hidden="true"></i>
					<?php } ?>

        		</button>
        <?php } ?>

      </div>

      <div class="tablet-menu collapse navbar-collapse nopad" id="bs-main_menu">
        <ul class="tablet-menu-ul nav navbar-nav nav-justified">
          <?php echo menuArray("main_menu",0,$w);?>
        </ul>
      </div>
    </div>
</nav>
<!--CSS IF MENU IS FIXED TOP-->
<?php if ($wa[custom_152] == "navbar-fixed-top") { ?>
	<style>
		body {
		 	margin-top:50px;
		}
	</style>
<?php }
if ($wa[custom_152] == "navbar-fixed-bottom") { ?>
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
<?php } ?>