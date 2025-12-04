<?php
if ($_ENV['total'] > 0) { 
    $sortingStart 	= displayNumberFormat($_ENV['start']);
    $sortingEnd 	= displayNumberFormat($_ENV['end']);
    $sortingTotal 	= displayNumberFormat($_ENV['total']);
	if( ($dc['form_name'] == "event_fields" || $dc['is_event_feature'] == 1) && $dc['enable_recurring_events_addon'] == 1 && addonController::isAddonActive('recurring_events') ){
		$sortingStart++;
		if($sortingEnd > $sortingTotal){
			$sortingEnd = $sortingTotal;
		}
	}
?>
<div class="post-search-result-count-container" style='display:none;'>
	<div class="col-sm-4 nopad xs-hpad bmargin post-search-result-count">
		%%%showing_sorting_label%%% <?php echo $sortingStart; ?>
		- <span class="current__amount__js"><?php echo $sortingEnd ?></span> %%%of_sorting_label%%%

		<?php if (intval($_ENV['total']) == 10000) {
		?>10,000+ %%result%%;

		<?php } else {
		echo   '<span class="total__js">' . $sortingTotal . '</span>'; ?> %%Result%%
		<?php } ?>
	</div>
	<div class="col-sm-8 nopad text-right bmargin post-search-result-sorting">
		<div class="form-inline">
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<?php } ?>