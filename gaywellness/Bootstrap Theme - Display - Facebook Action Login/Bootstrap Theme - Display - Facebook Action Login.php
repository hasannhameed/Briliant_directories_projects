<?php
//check if the website has the add-on
$addonFBlogin = getAddOnInfo("fb_login", "c7303c75e37fb6af15dd8056dc19d54a");
if (isset($addonFBlogin['status']) && $addonFBlogin['status'] === 'success') { ?>
    <div id="containerFBLogin" class="col-sm-6 col-xs-12">
        <a title="%%%fb_login%%%" href="#" id="facebookAction" data-action="login" class="xs-bmargin">
			<img alt="%%%fb_login%%%" src="/images/icons/facebook-login.png">
           
		</a>
    </div>
<?php
    echo widget($addonFBlogin['widget'], "", $w[website_id], $w);
} ?>