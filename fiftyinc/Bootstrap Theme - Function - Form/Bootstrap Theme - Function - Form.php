<?php

/*

***IMPORTANT***

This widget has many important functions that impact many areas of your website and can frequently receive updates.

To ensure the proper functioning of your site, it is highly recommended that you do not copy this widget to your site unless you are an advanced user and are absolutely sure that you need to customize its contents.

If you have just copied this widget to your website and want to avoid any potential issues, please click on the BACK button in your browser and DELETE this widget.

***IMPORTANT***

*/

////error_reporting(E_ERROR | E_WARNING | E_PARSE);
$_SERVER['form_views'] = array(
    "preview" => "field_search_view",
    "full" => "field_email_view",
    "email" => "field_email_view",
    "grid" => "field_grid_view",
    "table" => "field_table_view",
    "display" => "field_display_view"
);
$_SERVER['form_view_code'] = array(
    "preview" => "field_code_preview",
    "full" => "field_code_email",
    "email" => "field_code_email",
    "grid" => "field_code_grid",
    "table" => "field_code_table",
    "search" => "field_code_search",
    "display" => "field_code_display",
    "input" => "field_code_input",
    "edit" => "field_code_input");
$_SERVER['form_vars']['view_key'] = $_SERVER['form_views'][$_SERVER['form_vars']['view']];
$_SERVER['form_vars']['view_code'] = $_SERVER['form_view_code'][$_SERVER['form_vars']['view']];

if ($_SERVER['form_vars']['view'] == "full" || $_SERVER['form_vars']['view'] == "preview" || $_SERVER['form_vars']['view'] == "email") {
    $_ENV['form_vars'] = $_SERVER['form_vars'];

    if ($_ENV['form_name'] == "" && $_SERVER['form_name'] != "") {
        $_ENV['form_name'] = $_SERVER['form_name'];
    }
    echo widget("Bootstrap Theme - Form - View Controller","",$w['website_id'],$w);

} else {
    include_once($w['flocation']."/functions_up.php");
    include_once("/home/findades/public_html/pfbc/PFBC/Form.php");
    include_once("/home/findades/public_html/pfbc/PFBC/Element.php");
    $fd = array();
    $fd = getForm("",$_ENV['form_name'],$w);

    //check if the form is on the site or in the master
    $checkFormQuery = mysql($w['database'],"SELECT
        form_name
    FROM
        `form_fields`
    WHERE
        form_name = '".$fd[form_name]."'");

    if (mysql_num_rows($checkFormQuery) > 0) {
        $captchaDatabase = $w['database'];

    } else {
        $captchaDatabase = $DBMain;
    }


    // check if the form field for startdatetimepicker or stardatepicker are required
    $fieldStratDateQuery = mysql($captchaDatabase,"SELECT field_required FROM `form_fields` WHERE `form_name` LIKE '" . $fd[form_name] . "' AND (`field_text` LIKE '%Bootstrap Theme - Start DateTime Picker%' OR `field_text` LIKE '%Bootstrap Theme - Start Date Picker%')");


    if (mysql_num_rows($fieldStratDateQuery) > 0) {
        $field_required = mysql_fetch_assoc($fieldStratDateQuery);
    }


    //check if there is a form field with the type of ReCaptcha
    $fieldCaptchaQuery = mysql($captchaDatabase,"SELECT
            *
        FROM
            `form_fields`
        WHERE
            form_name = '".$fd[form_name]."'
        AND
            field_type = 'ReCaptcha'
        AND
            field_input_view = '1'");
    $_ENV['skip_recaptcha'] = 1;

    if (mysql_num_rows($fieldCaptchaQuery) > 0) {
        $_ENV['skip_recaptcha'] = 0;
    }
    $form = array();
    $formhead = array();
    $_SERVER['form_validator'] = array();
    $_SERVER['form_validator']['framework'] = "bootstrap";

    if ($_ENV['skip_recaptcha'] == 0) {
        $recaptcha_language = explode("-",$w['website_language']);
        $_SERVER['form_validator']['addOns']['reCaptcha2'] = array(
            'element' => 'captchaContainer',
            'language' => $recaptcha_language[0],
            'theme' => 'light',
            'siteKey' => $w['recaptcha_site_key'],
            'timeout' => '120',
            'message' => $label["form_invalid_captcha"]
        );
    }
    $_ENV['form_validator'] = $_SERVER['form_validator'];

    if ($_ENV['form_name'] == "" && $_SERVER['form_name'] != "") {
        $_ENV['form_name'] = $_SERVER['form_name'];
    }


    $form = new PFBC\\Form($fd['form_name'],"myform");

        $formhead = array(
            "prevent" => array(
                "bootstrap",
                "jQuery",
                "focus"
            ),
            "labelWidth" => 100,
            "labelPaddingTop" => "0.5em",
            "enctype" => "multipart/form-data"
        );

        if ($fd['form_url'] != "") {
            $formhead['action'] = $fd['form_url'];

        } else if ($w['form_url'] != "") {
            $formhead['action'] = $w['form_url'];

        } else {
            $formhead['action']="";
        }
        if ($fd['form_target'] != "") {
            $formhead['target'] = $fd['form_target'];
        }
        if ($fd['form_element_id'] != "") {
            $formhead['id'] = $fd['form_element_id'];

        } else {
            $formhead['id'] = $fd['form_name'];
        }
        if ($fd['short_code'] != "") {
            $formhead['class'] = $fd['short_code'];
        }
        if ($fd['form_action'] != "") {
            $formhead['method'] = $fd['form_action'];

        } else {
            $formhead['method'] = "post";
        }
        
        /// This will check to see if the form is already called on this page.
        if (is_array($_SERVER['forms_on_page']) && in_array($formhead['id'],$_SERVER['forms_on_page'])) {
            /// If the form has been called, get the total number of forms and set the identifier to the count. This will allow for unlimited forms with the same identifier on the same page.
            $count = count($_SERVER['forms_on_page'])+1;
            $formhead['id'] = $formhead['id'].$count;
        }

        $_SERVER['form_element_id'] = $formhead['id'];

        /// If the tracker has not been initalized, intialize it as an array.
        if (!is_array($_SERVER['forms_on_page'])) { 
            $_SERVER['forms_on_page'] = array(); 
        }
        /// This will keep track of the forms that we are using on the page.
        $_SERVER['forms_on_page'][] = $_SERVER['form_element_id'];      

        if ($fd['form_layout'] == "bootstrapvertical") {
            $formhead['view'] = new PFBC\\View\\BootstrapVertical;

        } else if ($fd['form_layout'] == "bootstrap") {
            $formhead['view'] = new PFBC\\View\\Bootstrap;

        } else if ($fd['form_layout'] == "vertical") {
            $formhead['view'] = new PFBC\\View\\Vertical;

        } else if ($fd['form_layout'] == "search") {
            $formhead['view'] = new PFBC\\View\\Search;

        } else if ($fd['form_layout'] == "inline") {
            $formhead['view'] = new PFBC\\View\\Inline;
        }

        if(!empty($fd['form_styles']) && strpos($fd['form_styles'],'{') === false && strpos($fd['form_styles'],'}') === false){
            $formhead['style'] = $fd['form_styles'];
        }

        $formhead['form_action_type'] = $fd['form_action_type'];
        $formhead['form_action_div'] = $fd['form_action_div'];
        $formhead['return_data_type'] = $fd['return_data_type'];
        $formhead['name'] = $fd['form_name'];
        $formhead['labelToPlaceholder'] = $fd['label_to_placeholder'];

        if ($fd['form_table'] != "" && $_SERVER['form_vars']['id'] != "") {
            $id = $_SERVER['form_vars']['id'];

            if ($fd['form_database'] == "") {
                $fd['form_database'] = $w['database'];
            }
            $ffkresults = mysql($fd['form_database'],"SELECT
                    *
                FROM
                    `".$fd[form_table]."`
                WHERE
                    `".$fd[table_index]."`='".$id."'");
            $sub = mysql_fetch_assoc($ffkresults);
            $sub = getMetaData($fd['form_table'],$id,$sub,$w);
            $_ENV['sub'] = $sub;
            $vars = array_merge($_REQUEST,$sub);

        } else {
            $vars = $_REQUEST;
        }
        $_ENV['fd'] = $fd;
        ob_start();
            $form->configure($formhead);
            $_GET['form'] = "myform";
            $_GET['formname'] = $fd['form_name'];
            $_GET['sized'] = 0;
            $_GET['dowiz'] = 1;
            $_GET['save'] = 1;

            foreach ($_GET as $getkey => $valuekey) {
               // echo $getkey;
                $form->addElement(new PFBC\\Element\\Hidden($getkey, "$valuekey",array("value"=>"$valuekey")));
            }

            $dataBaseToConnect  = $DBMain; // if it is empty the function below will put the main database;
            $queryFormCheck     = "SELECT 1 FROM `forms` as f , `form_fields` as ff WHERE f.form_name = '" . $fd['form_name'] . "' AND ff.form_name = '" . $fd['form_name'] . "'";
            $customFormExistsQuery = mysql($w['database'], $queryFormCheck);
            $customFormExists = mysql_num_rows($customFormExistsQuery);

            $formModel = new form();
            $formModel->getFormByName($fd['form_name']);

            if ($customFormExists > 0 && !$formModel->isDisable()) {
                $dataBaseToConnect = $w['database'];
            }else{
                $_ENV['fd']['form_database'] = $dataBaseToConnect;
            }

            /*
            *decode any encode value in order to display it correctly
            */
            if (is_array($_ENV['sub'])) {
                foreach ($_ENV['sub'] as $key => $value) {
                    $_ENV['sub'][$key] = rawurldecode($value);
                }
            }

            $isListingForm = bd_controller::subscription_types(WEBSITE_DB)->get($fd['form_name'],'listing_details_form');

            @nestedElementsForSignUp($fd[database_origin],$fd[form_name],0,$w,$_ENV[sub]);

            $filenameBdString = new bdString($dc['data_filename']);
            $filenameBdString->urldecode();

            if($pars[0] == 'account' && $pars[1] == $dc['data_filename']){
                $actionUrl = array();
                foreach ($pars as $key => $slug) {
                    if($key == 1){
                        $actionUrl[] = $filenameBdString->modifiedValue;
                    }else{
                        $actionUrl[] = $slug;
                    }
                }

                $form->configure(array(
                    "action" => '/'.implode('/', $actionUrl)
                ));
            }

            if(!empty($fd['form_styles']) && strpos($fd['form_styles'],'{') !== false && strpos($fd['form_styles'],'}') !== false ){
                echo '<style>'.$fd['form_styles'].'</style>';
            }

            $form->render();
            $totalform = ob_get_contents();
        ob_end_clean();
        echo eval("?>".replaceChars($w,$totalform,true)."<?");

        ob_start();

            if ($_SERVER['form_validator_scripts'] != 1) {
                $_SERVER['form_validator_scripts'] = 1; ?>
                <script src="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/bootstrap/formvalidation/current/dist/js/formValidation.min.js"></script>
                <script src="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/bootstrap/formvalidation/current/dist/js/framework/bootstrap.min.js"></script>
                <script type="text/javascript">
                    function decodeHtml(html) {
                        var txt = document.createElement("textarea");
                        txt.innerHTML = html;
                        return txt.value;
                    }
                </script>
                <?php
            } ?>
    <?php if ($_SERVER['form_element_id'] != "") { ?>
    <script type="text/javascript">

        var counterSubmit = 0;
        $(document).ready(function() {
            $('#<?php echo $_SERVER['form_element_id']; ?>').formValidation(<?php echo replaceChars($vars,json_encode($_SERVER['form_validator'])); ?>).on('success.form.fv', function(e,fvdata) {
                
                if('<?php echo $_SERVER['form_element_id']; ?>' == 'unsubscribe_email'){
                    return true;
                }
                // Prevent form submission
                e.preventDefault();
                $form = $(e.target),
                fv = $form.data('formValidation');
                var values = $(this).serialize();

                <?php if ($field_required['field_required'] > 0) { ?>
                let stardateChecker = "";

                if (document.getElementById("startdatetimepicker")) {
                    stardateChecker = "#startdatetimepicker";
                } else if (document.getElementById("stardatepicker")) {
                    stardateChecker = "#stardatepicker";
                }

                if(stardateChecker) {

                    $(stardateChecker).focusout(function(e){
                        $(this).attr("value", $(stardateChecker).val());

                        if ($(stardateChecker).val()) {
                            $("#validateStartDate").remove();
                        }

                    });

                    if (!$(stardateChecker).val()) {
                        $('html, body').animate({ scrollTop: $("#startdatetimepickerLabel").offset().top }, 700);
                        if (!$("#validateStartDate").length) {
                            $(stardateChecker).after( '<small id="validateStartDate" style="display: block;background: #a94442;border-radius: 2px;color: #fff;padding: 3px 10px;width: auto;">%%%form_validation_required_input%%%</small>');
                        }
                        return false;
                    }
                }
                <?php } ?>

                var locationFieldCorrect = true;

                if($("#<?php echo $_SERVER['form_element_id']; ?> .location_required").length > 0){
                    $("#<?php echo $_SERVER['form_element_id']; ?> .location_required").each(function (index) {
                        if($(this).data('state') == 1){
                            if($('#<?php echo $_SERVER['form_element_id']; ?> input[name="lead_location"]').val() == "" || $('#<?php echo $_SERVER['form_element_id']; ?> input[name="lat"]').val() == "" || $('#<?php echo $_SERVER['form_element_id']; ?> input[name="lng"]').val() == "") {
                                locationFieldCorrect = false;
                            }
                        }
                    });
                }

                if(locationFieldCorrect === false){
                    //check that there is a latitude or longitude
                    swal(`<?php echo $label['swal_whoops'];?>`, `<?php echo $label['location_information_required']?>`, "error");
                    return false;
                }
                        

                if (!$(this).attr("action")) {
                    var action = '';

                } else {
                    var action = $(this).attr("action");
                }
                if (!$(this).attr("method")) {
                    var method = 'post';

                } else {
                    var method = $(this).attr("method");
                }
                if (!$(this).attr("form_action_type")) {
                    var form_action_type = '<?php if ($pars[0]=="account") { ?>default<?php } else { ?>notification<? } ?>';

                } else {
                    var form_action_type = $(this).attr("form_action_type");
                }
                if (!$(this).attr("form_action_div")) {
                    var form_action_div = '#first_container';

                } else {
                    var form_action_div = $(this).attr("form_action_div");
                }
                if (!$(this).attr("return_data_type")) {
                    var return_data_type = 'json';

                } else {
                    var return_data_type = $(this).attr("return_data_type");
                }
                if ($("#<?php echo $_SERVER['form_element_id']?>-notification").html() != "") {
                    $("#<?php echo $_SERVER['form_element_id']?>-notification").remove();
                }
                if ($(this).find('input[type="submit"]').length > 0) {
                    $(this).find('input[type="submit"]').before('<div id="<?php echo $_SERVER['form_element_id']; ?>-notification" class="alert"></div>');

                } else {
                    $(this).prepend('<div id="<?php echo $_SERVER['form_element_id']; ?>-notification" class="alert"></div>');
                }
                var notification = $("#<?php echo $_SERVER['form_element_id']; ?>-notification");

                if ((form_action_type == "" || form_action_type == "default") && action.indexOf("account") >= 0) {
                    notification.html(`<?php echo $label["form_processing_request"]?>`).addClass("alert-warning");
                    setTimeout(function(){
                        fv.defaultSubmit();
                    }, 100);
                } else {
                    notification.html(`<?php echo $label["form_processing_request"]?>`).addClass("alert-warning");
                    if(counterSubmit == 0) {
                        counterSubmit++;

                        var fields      = $(this).serializeArray();
                        var formField   = [];
                        var processData = true;
                        var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
                        
                        

                        if($("#<?php echo $_SERVER['form_element_id']; ?> input[type=file]").length > 0){
                            
                            var formObject  = new FormData();
                            processData     = false;
                            contentType     = false;

                            $(fields).each(function(index,field){
                                formField.push(field.name+"="+field.value);
                            });

                            values  = formField.join("&");

                            $(formField).each(function(index,fieldValue){
                                var fieldInfo = fieldValue.split('=');
                                formObject.append(fieldInfo[0],fieldInfo[1]);
                            });

                            $("#<?php echo $_SERVER['form_element_id']; ?> input[type=file]").each(function(index,node){
                                var file = this.files[0];
                                if(typeof file != "undefined"){
                                    formObject.append("file_addon["+$(node).attr('name')+"]", file, file.name);
                                }
                            });
                        }else{
                            
                            $(fields).each(function(index,field){
                                formField.push(field.name+"="+encodeURIComponent(field.value));
                            });

                            var formObject  = formField.join("&");
                        } 
                        
                        $.ajax({
                            url: action,
                            type: method,
                            data: formObject,
                            dataType: return_data_type,
                            processData: processData,
                            contentType: contentType,
                            success: function (data) {
                                if (return_data_type == "html") {
                                    var result = 'success';

                                    if (!data != "") {
                                        var result_widget = data['result_widget'];
                                    }

                                } else {


                                    if (!data['result']) {
                                        var result = 'error';
                                    } else {
                                        var result = data['result'];
                                    }
                                    if (!data['result_widget']) {
                                        var result_widget = '';

                                    } else {
                                        var result_widget = data['result_widget'];
                                    }
                                }
                                if (result == "success") {

                                    if (!data['message']) {
                                        var message = `<?php echo $label['form_information_submitted'];?>`;

                                    } else {
                                        var message = data['message'];
                                    }
                                    if (!data['redirect_url']) {
                                        var redirect_url = '';

                                    } else {
                                        var redirect_url = data['redirect_url'];
                                    }



                                    if (form_action_type == "notification") {
                                        notification.html(message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>')
                                            .addClass('alert-dismissible')
                                            .addClass("alert-success")
                                            .removeClass("alert-danger")
                                            .removeClass("alert-warning")
                                        
                                            

                                        if(notification.parents('.modal').length && '<?php echo $_SERVER['form_element_id']; ?>' == 'whmcs_billing_address'){
                                            setTimeout(function(){
                                                notification.parents('.modal').modal('hide'); 
                                            }, 1000);
                                        }

                                    } else if (form_action_type == "widget") {

                                        if (result_widget != '') {

                                            setTimeout(function(){
                                                if(form_action_div != '.newsletter_modal_form_container'){
                                                    $("html, body").animate({ scrollTop: 0 }, 600);
                                                }                                                
                                                $(form_action_div).html(decodeHtml(result_widget)).text();

                                            }, 1000);
                                        }

                                    } else if (form_action_type == "redirect") {

                                        if (redirect_url == "") {
                                            redirect_url = decodeURIComponent("<?php echo $formhead['target']; ?>");
                                        }

                                        if (redirect_url.substring(0, 1) != "/" && redirect_url.substring(0, 1) != "h") {
                                            redirect_url = "/" + redirect_url;
                                        }
                                        current_location = "<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>";
                                        refresh_pages_string = "<?php echo $w['force_product_settings_login_redirect']?>";
                                        refresh_pages_string = refresh_pages_string.replace(/ /g,'');
                                        refresh_pages_array = refresh_pages_string.split(',');
                                        notification.html(message)
                                            .addClass("alert-success")
                                            .removeClass("alert-danger")
                                            .removeClass("alert-warning")
                                            .delay(2000).slideUp();
                                            if(action == "/api/widget/json/get/Bootstrap%20Theme%20-%20Member%20Login%20Page" && current_location.indexOf("/login") == -1 && refresh_pages_string != '' && (!refresh_pages_array.includes(current_location) || refresh_pages_string == "/login" )){
                                                <?php
                                                if (strpos($_SERVER['REQUEST_URI'], '?') !== false) { ?>
                                                    window.location.href = window.location.href+'&logged';
                                                <?php } else { ?>
                                                    window.location.href = window.location.href+'?logged';
                                                <?php } ?>
                                            } else {
                                                window.location.href = redirect_url;
                                            }

                                        fv.resetForm(true);
                                    }
                                    if(form_action_div != '.newsletter_modal_form_container' && '<?php echo $_SERVER['form_element_id']; ?>' != 'whmcs_billing_address'){
                                        $('.modal-backdrop').hide();
                                    }
                                } else {
                                    if(typeof grecaptcha !== "undefined"){
                                        grecaptcha.reset();
                                    }
                                    if (!data['message']) {
                                        var message = `<?php echo $label['form_invalid_data']?>`;

                                    } else {
                                        var message = data['message'];
                                    }
                                    $("#<?php echo $_SERVER['form_element_id']; ?>-notification").html(message)
                                        .addClass("alert-danger")
                                        .removeClass("alert-success")
                                        .removeClass("alert-warning")
                                        .fadeIn();
                                    fv.disableSubmitButtons(false);
                                }
                                counterSubmit = 0;
                            },
                            error: function (e) {
                                
                                if(typeof grecaptcha !== "undefined"){
                                        grecaptcha.reset();
                                        $("#<?php echo $_SERVER['form_element_id']; ?>-notification").html(`Recaptcha Error`);
                                } else {
                                    $("#<?php echo $_SERVER['form_element_id']; ?>-notification").html(`<?php echo $label["form_connection_error"]?>`);
                                }
                                $("#<?php echo $_SERVER['form_element_id']; ?>-notification").addClass("alert-warning")
                                    .removeClass("alert-success")
                                    .removeClass("alert-warning")
                                    .fadeIn();
                                counterSubmit = 0;
                            }
                        });/// End Ajax
                    }
                }//END else
            }).bind('keydown', function(event) {

                if (event.ctrlKey || event.metaKey) {

                    switch (String.fromCharCode(event.which).toLowerCase()) {

                        case 's':
                            $(this).submit();
                            break;
                    }
                }
            });

            /// This will turn any select2 that are required in the form builder to being required elements.
            $('#<?php echo $_SERVER['form_element_id']; ?> select').each(function (i, obj) {
                setTimeout(function(){
                    if ($(obj).prop("required") && $(obj).attr("style") == "display: none;") {
                        $(obj).css("z-index","-999");
                        $(obj).css("height","0");
                        $(obj).css("width","0");
                        $(obj).css("display","block");  
                        $(obj).css("position","absolute");  
                    }
                }, 3000);
            });

        });//END $('# echo $_SERVER[form_element_id]; ').formValidation(
    </script>
    <?php } ?>
    <?php
        $javascript = ob_get_contents();
        ob_end_clean();

        if (!stristr($_SERVER['widget_javascript'],$javascript) && !stristr($label['widget_javascript'],$javascript)) {
            $_SERVER['widget_javascript'] .= $javascript;
            $label['widget_javascript'] .= $javascript;$_SERVER['widget_master_javascript'].=$javascript;
        }
    }//END else -- if ($_SERVER[form_vars][view] == "full" || $_SERVER[form_vars][view] == "preview" || $_SERVER[form_vars][view] == "email")

?>