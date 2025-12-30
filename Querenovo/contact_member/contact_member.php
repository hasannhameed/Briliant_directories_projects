<style>
    .img_section, .mid_section {
        padding: 30px 30px !important;
        margin: 0px;
    }
    .custom_contact_member {
        background-color: #ffffff;
        border: 2px solid red;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 35px;
    }
    .custom_contact_member .btn-secondary{
        background-color: white !important;
        color: black;
       
    }
    .content_w_sidebar{
        margin-top: 30px;
        
    }
    .parent-div{
        background-color: #fafafa;
        padding: 0px;
        margin-bottom: 25px;
    }
    .custom_dep_member{
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 35px;
        box-shadow: -1px 1px 8px rgb(0 0 0 / 8%);
    }
    .btn-holder{
        padding-top: 15px;
    }
    .progress, .table-view .table-view-group:nth-child(2n-1), .tab-content .well, .tab-content .module, #account-tabs .well, #account-tabs .module, .account-form-box .module, .account-form-box .well, .module .module, .well .well, .module .well, .well .module {
        background-color: rgb(255 255 255);
        color: rgb(34, 34, 34);
    }
    .feature-post #post-content .alert-secondary.btn-block, .feature-search .img_section .alert-secondary.btn-block, .search_result .img_section .alert-secondary {
        border-radius: 10px;
        background-color: rgb(255 255 255);
    }
    .dep_button{
        background-color: #f5f5f5;
    }
</style>

<div class='col-sm-12 parent-div'>

    <div class='col-sm-12 custom_contact_member'>
            <div class="col-sm-12 tmargin profile-header-send-message" bis_skin_checked="1">
                <a class="" title="Contact Blabs" href="/blabs/connect"><h4><b>France Rénov Conseil Ain<b></h4></a>
            </div>
        
            <?php
            //can send leads
            if ($sub['receive_messages'] != 1) { ?>
                <div class="col-sm-12 tmargin profile-header-send-message">
                    <a class="btn btn-primary btn-block btn-lg btn-send_message_action" title="%%%contact_label%%% <?php echo $user['full_name']; ?>" href="/<?php echo $user['filename']; ?>/<?php echo $w['default_connect_url'];?>">
                    <?php if ($w['enable_direct_chat_messages'] == "1" && $subscription['enable_direct_messages'] == "1") { ?>
                        %%%send_message_action%%%
                    <?php } else { ?>
                        %%%profile_send_message_button%%%
                    <?php } ?>
                    </a>
                </div>
            <?php } ?>
        <p></p>
        <?php
        //show phone number
        if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>
            <div class="col-sm-12 tmargin">
                <?php
                $clickPhoneAddOn = getAddOnInfo("click_to_phone","16c3439fea1f8b6d897987ea402dcd8e");
                $statisticsAddOn = getAddOnInfo("user_statistics_addon","7f778bc02f0e6acbbd847b4061c7b76d");

                if(isset($clickPhoneAddOn['status']) && $clickPhoneAddOn['status'] === 'success'){
                    echo widget($clickPhoneAddOn['widget'],"",$w['website_id'],$w);
                } else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
                    echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
                } else {
                    if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>
                        <span style="padding:10px 16px;" class="well nobmargin text-center btn-lg btn-block author-phone">
                            %%%show_phone_number_icon%%%
                            <?php echo $user['phone_number']; ?>
                        </span>
                    <? }
                } ?>
            </div>
        <? } ?>

    </div>
    <?php if($user['department_code'] != ''){ ?>
    <div class='col-sm-12 custom_dep_member'>
        <div class="col-sm-12 tmargin profile-header-send-message" bis_skin_checked="1">
                <a class="" title="Contact Blabs" href="/blabs/connect">
                    <h4>
                        <b>
                            <font dir="auto" style="vertical-align: inherit;">
                                <font dir="auto" style="vertical-align: inherit;">
                                    France Renov Conseil 
                                    <?php  
                                        $dep_query0 = mysql_query("SELECT dep_name FROM `departments` WHERE dep_id =".$user['department_code']);
                                        $dep_fetch0 = mysql_fetch_assoc($dep_query0);
                                        if($dep_fetch0['dep_name'] != ''){ 
                                            echo $dep_fetch0['dep_name'];
                                        }
                                    ?>
                                </font>
                            </font>
                            <b>

                            </b>
                        </b>
                    </h4>
                </a>
                <b>
                    <b>
            </b></b>
        </div>
        <div class="col-sm-12 tmargin profile-header-send-message btn-holder" id='<?php echo $user['user_id']; ?>'bis_skin_checked="1">
                <?php  
                $dep_query = mysql_query("SELECT department_code FROM users_data WHERE user_id =".$user['user_id']);
                $dep_fetch = mysql_fetch_assoc($dep_query);
                if($dep_fetch['department_code'] != ''){
                ?>
                
                <span class='btn btn-default dep_button'><?php echo $dep_fetch['department_code']; ?></span>
                <?php } ?>
        </div>
    </div>
    <?php } ?>
 </div>