<div class="clearfix footer-clear-element <?php if ($page['seo_type'] != "home") { ?>clearfix-lg<?php } ?>"></div>
[widget=Bootstrap Theme - Banner - 970_90]

<div class="clearfix clearfix-lg"></div> 
<div style="text-align: center;">

</div>
[widget=Custom - Homepage Cities]
[widget=Community Article Category Menu Widget]
[widget=Blogpost Category Menu Widget]

<?php
if ($wa['custom_113'] == "1") {
    echo widget("Bootstrap Theme - Module - Footer Newsletter Sign Up Form","",$w[website_id],$w);
} ?>
<div class="footer footer-image">
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