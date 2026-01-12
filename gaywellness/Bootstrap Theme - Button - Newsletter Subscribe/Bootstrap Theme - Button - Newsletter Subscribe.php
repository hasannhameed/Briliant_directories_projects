<span data-toggle="modal" data-target="#newsletter_subscribe_modal" class="btn btn-lg newsletter_footer_button col-xs-12 col-md-6 nofloat fpad bold">
    <?php if($label['newsletter_subscribe_footer_text'] != '' && $label['newsletter_subscribe_click'] != ''){ ?>
        <div class="col-sm-6 nopad newsletter_button_left">
            %%%newsletter_subscribe_footer_text%%%
        </div> 
        <div class="col-sm-6 nopad newsletter_button_right">
            %%%newsletter_subscribe_click%%%
            %%%newsletter_subscribe_button_icon%%%
        </div>
    <?php } else if ($label['newsletter_subscribe_footer_text'] != '' && $label['newsletter_subscribe_click'] == ''){ ?>
        <div class="col-sm-12">
            %%%newsletter_subscribe_footer_text%%%
        </div> 
    <?php } else if ($label['newsletter_subscribe_footer_text'] == '' && $label['newsletter_subscribe_click'] != ''){ ?>
        <div class="col-sm-12">
            %%%newsletter_subscribe_click%%%
            %%%newsletter_subscribe_button_icon%%%
        </div>
    <?php } ?>
    
	<div class="clearfix"></div>
</span>