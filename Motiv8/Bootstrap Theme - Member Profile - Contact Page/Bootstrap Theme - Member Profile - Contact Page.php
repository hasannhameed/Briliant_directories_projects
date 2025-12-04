<?php
$dataCategoriesModel = new data_categories();
$loggedUser = getUser($_COOKIE['userid'], $w);
$listingFeature = $dataCategoriesModel->get('10','data_type');
if (addonController::isAddonActive('members_only') && ((((strlen($listingFeature->always_on) == 1 && ($listingFeature->always_on == '0' || $listingFeature->always_on == '2')) || (strlen($listingFeature->always_on) > 1 && $listingFeature->always_on[2] == "1")))) && (!isset($_COOKIE['userid']) || $loggedUser['active'] != "2")){
    addonController::showWidget('members_only','389ffec8e86fc926655fab47a7b01a5a');

} else {
    $MemberSub = getSubscription($user['subscription_id'],$w);
    $userPhoto = getUserPhoto($user['user_id'], $user['listing_type'], $w);
    $userPhoto = $userPhoto['file'];

    if ($MemberSub['receive_messages'] == 1 || $user['active']!="2" || $MemberSub['searchable']!=1) {  ?>
    
    <div class="col-md-10 col-md-offset-1">
        <div class="alert alert-warning text-center tmargin">
            <h3>%%%profile_inactive%%%</h3>
                <p class="bmargin">%%%profile_inactive_text%%% <a href="%%%default_contact_us_url%%%">%%%contact_us%%%</a> %%%profile_restore_listing%%%.</p>
            <div class="clearfix"></div>
        </div>
    </div>
    <style>.breadcrumb{display:none;}</style> 

    <? } else { 

        $w['show_override'] = 1;

        if ($message != "") { 
            echo showMessage($message,'good',1); 
        } 
        if ($formerror != "") { 
            echo showMessage($formerror,'error',1); 
        } 
        
        if ($pars[$last] == "complete") { ?>
            <div class="col-md-9 col-md-push-3">
                <div class="well">
                    <h3>%%%join_success_message_label%%%</h3>
                    <a class="btn btn-success" href="/<?php echo $user['filename']; ?>" title="Back to Listing">%%%back_to_listing%%%</a>
                </div>
            </div>

        <? } else { ?>
            <div class="col-md-9 col-md-push-3">
                <?php  

                $addOnDirectMessages = getAddOnInfo("member_direct_messages","13854510c810cf1665cbb29bd14223e2");
                if (isset($addOnDirectMessages['status']) && $addOnDirectMessages['status'] == "success" && (isset($MemberSub['enable_direct_messages']) && $MemberSub['enable_direct_messages'] == "1" && $w['enable_direct_chat_messages'] == "1")) {
                    echo widget($addOnDirectMessages['widget'],"",$w['website_id'],$w);
                } else if ($user['user_id'] == $_COOKIE['userid'] && $w['disable_self_leads'] == "1") { ?>
                    <div class="well bd-chat-well-container">
                        <h3 class="text-center">%%%member_lead_message_self_warning%%%</h3>
                    </div>
                <? } else if ($w['enable_data_flows'] > 0) { ?>
                    <h1 class="h4 line-height-lg bold alert bg-primary vpad img-rounded nomargin no-radius-bottom contact-member-title">
                         %%%sidebar_send_message%%%
                    </h1>
                    <div class="well fpad-lg no-radius-top contact-member-form">
                        <?php echo form("bootstrap_get_match","",$w['website_id'],$w); ?>
                    </div>
                <?php } else { ?>
                    <div class="well">
                        <? if ($pars[4] == "request" || $pars[3] == "request") { ?>
                            <h1 class="h2">%%%request_quote_for%%% <?php echo "".$_GET["service"]."";?></h1>
                        
                        <? } else { ?>
                            <h1 class="h2">%%%contact_label%%% <?php echo $user['full_name']; ?></h1>
                        <? }
                        $formname = "listing_send_message";
                        $fd = getForm("",$formname,$w);
                
                        if ($fd['form_name'] == $formname) {
                            $w['form_url'] = "/" . $user['filename'] . "/connect/" . $user['token'] . "/complete";
                            $_GET['usertokenid'] = $user['user_id'];
                            $_ENV['vals1'] = rand(1,4);
                            $_ENV['vals2'] = rand(1,4);
                            $_GET['vals'] = $_ENV['vals1'] . "|" . $_ENV['vals2'];
                            $_GET['answerqmatch'] = $_ENV['vals1'] + $_ENV['vals2'];
                            echo form($formname,"",$w['website_id'],$w);
                        
                        } else { ?>
                            <form action="/<?php echo $user['filename']; ?>/connect/<?php echo $user['token']; ?>/complete" method="post" name=matched onSubmit="return validate();">
                                <input type="hidden" name="vals" VALUE="<?php echo $val1; ?>|<?php echo $val2; ?>">
                                <input type="hidden" name="usertokenid" value="<?php echo $user['user_id']; ?>">
                                <div class="form-group">
                                    <label>%%%name_label%%%</label>
                                    <input required type="text" class="form-control" name="first_name" <? if ($_COOKIE['lead_first_name'] != "") { ?>style="color:#000000;" value="<?php echo $_COOKIE['lead_first_name']; ?>"<? } else { ?>value = ""<? } ?>>
                                </div>
                                <div class="form-group">
                                    <label>%%%email_label%%%</label>
                                    <input required type="email" class="form-control" <? if ($_COOKIE['lead_email'] != "") { ?> value="<?php echo $_COOKIE['lead_email']; ?>"<? } else { ?>value=""<? } ?> id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label>%%%phone_label%%%</label>
                                    <input  type="text" class="form-control" id="phone" name="phone" <? if ($_COOKIE['lead_phone'] != "") { ?> value="<?php echo $_COOKIE['lead_phone']; ?>"<? } else { ?>value=""<? } ?> >
                                </div>
                                <div class="form-group">
                                    <label>%location_search_default%</label>
                                    <input class="form-control locationSuggest" type="text" name="zip" <? if ($_COOKIE['lead_city'] != "") { ?> value="<?php echo $_COOKIE['lead_city']; ?>"<? } else { ?>value=""<? } ?>  id="zip">
                                </div>
                                <div class="form-group">
                                    <label>%%%enter_message_label%%%</label>
                                    <textarea  name="description" id="description" class="form-control"></textarea> 
                                    <input type="hidden" name="saveinfo" value=1>
                                </div>
                                <div class="form-group">
                                    <label>%%%security_question_label%%%</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">%%%security_question_verification%%% <?php echo $val1; ?> + <?php echo $val2; ?>?</span>
                                        <input class="form-control" required type=text name=answerq>
                                    </div>
                                </div>
                                <input type=submit class="btn btn-block btn-lg btn-primary" value="%%%sidebar_send_message%%% &raquo;"/>
                                <div class="col-md-12 nohpad tpad text-center">
                                    <b>%%%safe_secure_label%%%</b><br>
                                    <small style="font-size:10px;">%%%safe_secure_subtitle%%%</small>
                                </div> 
                                <div class="clearfix"></div>
                            </form> 
                        <? } ?>
                    </div><?/*END <div class="well">*/?>
                <? } ?>

            </div>
    <?  } ?>
    <div class="col-md-3 col-md-pull-9" style="position: sticky;top: 70px;">
        <div class="well">
            <a href="/<?php echo $user['filename']; ?>" title="<?php echo $user['full_name']; ?>">
                <img class="img-responsive center-block img-rounded contact-image" src="<?php echo $userPhoto;?>" alt="<?=$w['profession']?>" title="<?=$user['full_name']?> <?=$w['profession']?>">
            </a>
            <a class="btn btn-primary btn-block tmargin" href="/<?php echo $user['filename']; ?>">%%%back_to_listing%%%</a>
        </div>
    </div>

    <? } 
}

if (isset($_GET['action'])) {
    $get_user_sql = mysql_query("SELECT first_name, last_name, company FROM users_data WHERE user_id = ".$_COOKIE['userid']);
    $get_user_data = mysql_fetch_assoc($get_user_sql);
    if ($_GET['action'] == 'call') { ?>
        <script>
            $(document).ready(function() {
                $('#bd-chat-pmbrfc-message').val("<?php echo $get_user_data['first_name']." ".$get_user_data['last_name'] ?> is requesting for a video call. ");
                $('#bd-chat-pmb-sm-sm').click();
            });
        </script>
    <?php }
}

?>