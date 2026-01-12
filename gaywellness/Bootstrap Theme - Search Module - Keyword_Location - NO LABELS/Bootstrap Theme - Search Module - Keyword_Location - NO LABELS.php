<div class="module">
	<h3>%%%find_profession_label%%%</h3>
	<form action="/<?php echo $w['default_search_url'];?>" class="website-search"  accept-charset="UTF-8" method="get">
				
		<div class="form-group normal-autosuggest">
			<input type="text" name="q" value="<?php echo stripslashes($_REQUEST['q']);?>" placeholder="%%%keyword_search_default%%%" class="form-control member_search">
		</div>
		
		<div class="form-group">
			<input type=text placeholder="%%%location_search_default%%%" class="googleSuggest googleLocation form-control" id="location_google_maps_homepage" name="location_value" value="<?php if ($_GET[location_value] != '') { echo htmlspecialchars($_GET['location_value']); } ?>">
		</div>
		
		<div class="form-group nomargin">
			<button class="btn btn-primary btn-block" type="submit">%%%home_search_submit%%%</button>    
		</div>    
	</form>
</div>