      <?php 
    if ($pars[0]!='search_results' && $pars[1] != '') {
    $tidquery=mysql($w['database'],"SELECT * FROM `list_services` WHERE filename = '$pars[1]'");
      while ($u=mysql_fetch_row($tidquery)) {
            $_GET['tid']=$u[0];
      }
      } else if ($pars[0]!='search_results') {
    $sidquery=mysql($w['database'],"SELECT * FROM `list_professions` WHERE filename = '$pars[0]'");
      while ($u=mysql_fetch_row($sidquery)) {
      $_GET['sid']=$u[0];
      }
    }
    if ($pars[1]!='') {
    $sidquery=mysql($w['database'],"SELECT * FROM `list_professions` WHERE filename = '$pars[0]'");
      while ($u=mysql_fetch_row($sidquery)) {
            $_GET['sid']=$u[0];
      }
      }
    ?>
	<div class="module">
		<h3>%%%find_profession_label%%%</h3>
		<form action="/search_results" class="website-search"  accept-charset="UTF-8" method="get">
								
			<div class="form-group normal-autosuggest">				
				<label>%%%home_search_dropdown_1%%%</label>
				<input type="text" name="q" value="<?php echo $_REQUEST['q']?>" placeholder="%%%keyword_search_default%%%" class="form-control member_search1">
			</div>
			
			<div class="form-group">				
				<label>%%%home_search_dropdown_3%%%</label>
				<input type=text placeholder="%%%location_search_default%%%" class="googleSuggest googleLocation form-control" id="location_google_maps_homepage" name="location_value" value="<?php if ($_GET[location_value] != '') { echo htmlspecialchars($_GET['location_value']); } ?>">
			</div>
			
			<div class="form-group nomargin">
				<button class="btn btn-primary btn-block" type="submit">%%%home_search_submit%%%</button>    
			</div>    
		</form>
	</div>