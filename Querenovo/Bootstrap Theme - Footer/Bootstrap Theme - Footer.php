<?php if($pars[0]=='join') {?>
[widget=join_footer_banner]
<?php } ?>
[widget=footer-banner]
[widget=jobs-footer]

<div class="clearfix footer-clear-element <?php if ($page['seo_type'] != "home") { ?>clearfix-lg<?php } ?>"></div>
[widget=Bootstrap Theme - Banner - 970_90]
<?php
if ($wa['custom_113'] == "1") {
    echo widget("Bootstrap Theme - Module - Footer Newsletter Sign Up Form","",$w[website_id],$w);
} ?>
<div class="footer">
    <div class="container">
        <div class="row">
            <ul class="footer_menu sm-text-center">
                <?php echo menuArray("footer_menu",0,$w); ?>
            </ul>
        </div>
		<?php if ($w['hide_terms_footer'] != "1") { ?>
		[widget=Bootstrap Theme - Footer - Terms of Use]
		<?php } ?>
    </div>
</div>
