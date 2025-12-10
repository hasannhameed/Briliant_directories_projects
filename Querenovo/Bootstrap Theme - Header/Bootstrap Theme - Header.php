


<?php $addOnAnnouncement = getAddOnInfo('announcement_bar', 'ac85b610729d1fe23f88aae7ea12b9c0');
if (isset($addOnAnnouncement['status']) && $addOnAnnouncement['status'] === 'success') {
    echo widget($addOnAnnouncement['widget'], "", $w['website_id'], $w);
} ?>
<div class="header">
    <?php if ($wa['custom_201'] != "none") { ?>
        <div class="container">
			<div class="row vmargin header-main-row">
				[widget=Bootstrap Theme - Header - Left]
				<?php if ( !isset($page['hide_top_right']) || $page['hide_top_right'] != 1) { ?>
					[widget=Bootstrap Theme - Header - Right]
				<?php } else if ($_COOKIE['userid'] > 0) { ?>
					<div class="col-md-7 text-right sm-text-center header-right-container nolpad xs-hpad">
						[widget=Bootstrap Theme - Header - Member Links]
					</div>
				<?php } ?>
            </div>
        </div>
    <?php } ?>
    [widget=Bootstrap Theme - Header - Main Menu]
</div>
[widget=Bootstrap Theme - Banner - Header - 970_90]

[widget=video-title]
[widget=job-title]
<?php if($pars[0]=='join'){ ?>
[widget=join_header_banner]
<?php } ?>
