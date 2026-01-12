<?php
//check if the website has the add-on
$addonGoogleLogin = getAddOnInfo("google_login", "1a6d19e7b022116626760695b465066b");
if (isset($addonGoogleLogin['status']) && $addonGoogleLogin['status'] === 'success') { ?>
    <div id="containerGoogleLogin" class="col-sm-6 col-xs-12">
        <a title="%%%google_login%%%" href="#" id="googleAction" data-action="login" class="xs-bmargin">
			<img alt="%%%google_login%%%" src="/images/icons/google-login-trans.png">
            
        </a>
    </div>
    <?php
    echo widget($addonGoogleLogin['widget'], "", $w[website_id], $w);
} ?>