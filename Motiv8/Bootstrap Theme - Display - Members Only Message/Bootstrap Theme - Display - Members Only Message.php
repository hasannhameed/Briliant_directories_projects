<div class="col-md-offset-2 col-md-8 well text-center h3 members-only-message">
    <?php 
    $me = getUser($_COOKIE['userid'],$w); ?>
    <?php if ($_COOKIE['userid'] != '' && $me['active'] != '2'){ ?>
        <h1>%%%members_only_account_not_active%%%</h1>
        %%%members_only_protected_page%%% <a href="/account/home" target="_blank">%%%members_only_go_dashboard%%%</a> %%%members_only_for_more_details%%%
    <?php } else { ?>
        <h1>
            %%%this_content_is_for%%% 
            <?php if ($_COOKIE['userid']=="") {  ?>
            %%%members_only%%%
            <?php } else { ?>
            %%%upgraded_members%%%
            <? } ?>
        </h1>

	
        
        To access this page; 
	<br>
	<?php if ($_COOKIE['userid']=="") {  ?>
            <a href="#" data-toggle="modal" data-target="#member-login">
                %%%login_label%%%
            </a> 
           
            <?php } else { ?>
            <a href="/about/contact">
                %%%members_only_upgrade%%%		
            </a>
        <?php } ?>
    <?php } ?>
	<br>
	
	<hr>
	<a class="btn btn-lg btn-success bold back_to_previous_page" href="/" onclick="window.history.go(-1); return false;">
		%%%back_to_previous_page%%%
	</a>
</div>
<div class="clearfix clearfix-lg"></div>
<div class="modal fade" id="member-login" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body nohpad nobpad">
				<button type="button" style="z-index:10;position:absolute;right:15px;" class="close hmargin" data-dismiss="modal">&times;</button>
				<?php
					if ($pars[0] != "checkout") {
						echo stripslashes(widget("Bootstrap Theme - Member Login Page",'',$w[website_id],$w));
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php
$dataCategoriesModel = new data_categories();
$listingFeature = $dataCategoriesModel->get('10','data_type');
?>
<style>
<?php if (($page['template_id'] == "100" && !isset($_COOKIE['userid']) && ($listingFeature->always_on == '0' || $listingFeature->always_on == '2')) || $page['template_id'] != "100"){ ?>
.breadcrumb { display: none; }
<?php } ?>
.tab-content .members-only-message {
    width: 90%;
    margin: 30px auto -10px;
    padding: 40px 0;
    display: block;
    float: none;
}
.tab-content .members-only-message .back_to_previous_page, .tab-content .members-only-message hr {
    display: none;
}
</style>