<div class="login-cta-buttons">
    <hr class="cta-hr">
    <ul class="list-inline nomargin inline-block btn-block">
        
        <?php 
        $redirectUrl = $_GET['redirect']; 
        $checkoutUrl = '';

        if (!empty($redirectUrl)) {
            if (strpos($redirectUrl, 'attending-supplier-staff-registration') !== false) {
                $checkoutUrl = '/checkout/36';
            } elseif (strpos($redirectUrl, 'supplier-registration') !== false) {
                $checkoutUrl = '/checkout/33';
            }
        }

        if (!empty($checkoutUrl)) {
        ?>
            <li class="inline-block btn-block">
                <a href="<?= $checkoutUrl ?>?redirect=<?= $redirectUrl ?>" id="link1135" class="btn btn-success btn-lg btn-block ">
                    <span class="inline-block">Don't have an account? Create one for free!</span>
                </a>
            </li>
        <?php } else { ?>
            <!-- $_GET['ogin_direct_url'] create the same logic for this -->
            <li class="nav btn-block">
				<a href="/createanaccount<?= urlencode($_GET['login_direct_url'] ?? '') ?>" id="link269" class="btn btn-primary btn-lg btn-block "> 
                    <span class="inline-block">Create an account</span>
                </a>
            </li>
        <?php } ?>
    </ul>
    <div class="clearfix"></div>
</div>
