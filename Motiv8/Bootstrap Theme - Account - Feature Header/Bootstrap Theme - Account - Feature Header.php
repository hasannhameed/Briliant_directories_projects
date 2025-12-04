<?php
global $subscriptionData, $subscrpitionLimits, $subscriptionCategoryLimit, $membersTotalPostQuery;

$subscriptionData           = getSubscription($user_data['subscription_id'], $w);
$subscriptionLimits         = json_decode($subscriptionData['data_settings_limit'], true);
$subscriptionCategoryLimit  = $subscriptionLimits[$dc['data_id']];
$haveCredit                 = true;
$purchaseLimitAddOn         = getAddOnInfo('purchase_limit');

if ($dc['data_type'] == 28) { //this addon don't work with sub accounts
    unset($purchaseLimitAddOn['status']);
    $purchaseLimitAddOn = array();
}
if(isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === "success" && isset($dataPostLimitted->{$dc['data_id']}->post_limitted) && $dataPostLimitted->{$dc['data_id']}->post_limitted != 2 ){
    $haveCredit         = post_payment_controller::canPost($dc['data_id'],$user_data['user_id'],post_payment_controller::LIMIT,$subscriptionCategoryLimit);
    $dataPostLimitted   = json_decode($subscriptionData['data_post_limitted']);
    if(isset($dataPostLimitted->{$dc['data_id']}->post_limitted) && $dataPostLimitted->{$dc['data_id']}->post_limitted == 1){
        $subscriptionCategoryLimit = -1;
    }
}else if(isset($dataPostLimitted->{$dc['data_id']}->post_limitted) && $dataPostLimitted->{$dc['data_id']}->post_limitted == 2){
    $haveCredit = false;
}

if ($subscriptionCategoryLimit == 0){ $subscriptionCategoryLimit = 999999; }
if ($dc['data_type'] == 14 || $dc['data_type'] == 4 || $dc['data_type'] == 6 || $dc['data_type'] == 25) {
    $membersTotalPostQuery = mysql($w['database'], "SELECT 
            count(group_id) 
        FROM 
            `users_portfolio_groups` 
        WHERE 
            user_id = '".$user_data['user_id']."' 
        AND 
            data_id = '".$dc['data_id']."'");
} else {
    $membersTotalPostQuery = mysql($w['database'], "SELECT 
        count(post_id) 
    FROM 
        `data_posts` 
    WHERE 
        user_id = '".$user_data['user_id']."' 
    AND 
        data_id = '".$dc['data_id']."'");
}
$membersTotalPosts = mysql_fetch_array($membersTotalPostQuery);
if (!in_array($dc['data_id'], $subscription['data_settings'])) { ?>
	<div class="well fpad-lg">
		<div class="well fmargin-lg fpad-xl empty-holder text-center invalid-page">
			<h2 class="nomargin">
				<span class="font-sm">
					%%%feature_page_not_available%%%
				</span>
			</h2>
			<div class="clearfix"></div>
			<a class="btn btn-success btn-lg fmargin-xl bold bmargin" href="/account/home">
				%%%back_to_dashboard%%%
			</a>
		</div>
	</div>
    <style>.account-form-box {display: none;}</style>
    <?php
} else { ?>
    <div role="tabpanel" class="manage-post-tabs">
        <ul role="tablist" class="nav nav-tabs">

			<li class="<?php if ($pars[2]=="" || $pars[2]=="view" || $pars[2] == "deletegroup" || $pars[2] == "delete") { ?>active<? } ?>">
				<a href="/account/<?php echo $dc['data_filename'];?>/view">
					<?php if ($pars[2]=="" || $pars[2]=="view" || $pars[2] == "deletegroup" || $pars[2] == "delete") { ?>
						<?php echo $dc['icon'];?>
						<?php echo ($dc['data_name']);?>
						<?php if ($membersTotalPosts[0] > 0 && ($pars[2]=="" || $pars[2]=="view" || $pars[2] == "deletegroup" || $pars[2] == "delete")) { ?>
							<span style="vertical-align: text-top;margin-left: 3px;" class="badge img-circle bg-secondary">
								<?php echo $membersTotalPosts[0]?>
							</span>
						<?php } else if ($dc['data_type'] != 28) { ?>
							<style>
								#purschase-limit.publish-post-button,.publish-post-button{position:relative;right:0;top:0;text-transform:capitalize;font-weight:600;font-size:18px;margin:25px auto 10px;width: 85%;display:block;padding:10px}
								.first-post{text-align: center;font-size: 26px;background-color:<?php echo $wa['custom_1']?>;padding:30px;border:3px dashed <?php echo $wa['custom_72']?>;margin:20px}
								.first-post-inner{display:block;}
							</style>
						<?php }					
					} else { ?>
						<i class="fa fa-reply fa-fw"></i>
						%%%go_back_new_leads_module%%%
					<?php } ?>
				</a>
			</li>
			
			<?php if ($dc['is_digital_product'] == "1" && $pars[2] == "view") { ?>
			<li>
				<a href="/account/member-manage-digital-products?buyer=show&data_id=<?php echo $dc['data_id'];?>">
					%%%digital_product_sale%%%
				</a>
			</li>
			<?php } ?>
			
            <?php
            if ($pars[3] != "" && $pars[2]!="copy" && $pars[2]!="delete" && $pars[2]!="deletegroup") { ?>
                <li class="<?php if ($pars[2]=="edit" || $pars[2]=="editgroup") { ?>active<? } ?>">
                    <a href="/account/<?php echo $dc['data_filename'];?>/edit/<?php echo $pars[3];?>">
						%%%feature_tab_edit%%% 
						<?php echo $dc['data_name'];?>
					</a>
                </li>
                <?php
            }

            if ($dc['data_type'] == "14" || $dc['data_type'] == "4" || $dc['data_type'] == "6" || $dc['data_type'] == "25") { ?>
                <?php
                if ($pars[3] != "" && $pars[2] != 'deletegroup') { ?>
                    <li class="<?php if ($pars[3] != "" && ($pars[2] == "viewgroup" || $pars[2] == "addphotos" || $pars[2] == "editphotos" || $pars[2] == "addphotosmanual")) { ?>active<? } ?>">
                        <a href="/account/<?php echo $dc['data_filename'];?>/viewgroup/<?php echo $pars[3];?>">
							%%%feature_tab_upload%%% %%Photo%%
						</a>
                    </li>
                    <?php
                }

                if ($pars[3] != "" && $pars[2] != 'deletegroup') { ?>
                    <li class="<?php if ($pars[3]!="" && $pars[2]=="arrange") { ?>active<? } ?>">
                        <a href="/account/<?php echo $dc['data_filename'];?>/arrange/<?php echo $pars[3];?>">
							%Photo% %%%album_photo_display_order%%%
						</a>
                    </li>
                    <?php
                }

            }
	
			if ($pars[2]=="copy") { ?>
				<li class="active">
					<a href="#">
						%%%clone_label%%% 
						<?php echo $dc['data_name'];?>
					</a>
				</li>
			<?php
			}	
            if ($pars[3] == "") {
                ?>
                <?php if ($pars[2]=="add" || $pars[2]=="newgroup") { ?>
                    <li class="active">
                        <a href="/account/<?php echo $dc['data_filename'];?>/add">
							%%%add_label%%% 
							<?php echo $dc['data_name'];?>
						</a>
                    </li>
                <?php } ?>
                <?php
            } ?>
        </ul>
    </div>
    <?php
} ?>