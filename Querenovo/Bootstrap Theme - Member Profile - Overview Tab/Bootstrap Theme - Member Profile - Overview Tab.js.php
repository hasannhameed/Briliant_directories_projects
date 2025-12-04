<script>
	$(document).ready(function() {
		// Hide the first H2 title of each .table-view.list-inline section if it is the only element inside its parent div, along with a div.clearfix, meaning "Company Details" is empty
		$('.table-view.list-inline').each(function() {
			var $parentDiv = $(this);
			// Check if the parent div contains exactly two children: div.clearfix and h2.tmargin.tpad.xs-center-block.clearfix
			if ($parentDiv.children('div.clearfix').length === 1 && $parentDiv.children('h2.tmargin.tpad.xs-center-block.clearfix').length === 1 && $parentDiv.children().length === 2) {
				$parentDiv.children('h2.tmargin.tpad.xs-center-block.clearfix').attr('style', 'display: none !important;');
			}
		});
	});
</script>