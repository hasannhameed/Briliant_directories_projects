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
  <h3>Find %%Profession%%</h3>
  <form action="/search_results" class="website-search"  accept-charset="UTF-8" method="get">

    <div class="form-group">
      <label>%%%home_search_dropdown_1%%%</label>
      <select data-placeholder="%%%home_search_default_1%%%" name="sid" id="sid" class="form-control">
        <option></option>
        <?=listProfessions($_GET['sid'],"option",$w)?>
      </select>
    </div>

    <div class="form-group">
      <label>%%%home_search_dropdown_2%%%</label>
      <select data-placeholder="%%%home_search_default_2%%%" name="tid" id="tid" class="form-control">
        <option></option>
        <?=listServices($_GET['tid'],"list",$w,$_GET['sid'])?>
      </select>
    </div>

    <div class="form-group">
      <label>%%%home_search_dropdown_3%%%</label>
      <input type=text placeholder="%%%home_search_default_3%%%" class="googleSuggest googleLocation form-control" id="location_google_maps_homepage" style="width:100%" name="location_value" value="<?php if ($_GET['location_value'] != '') { echo htmlspecialchars($_GET['location_value']); } ?>">
    </div>
    <div class="form-group nomargin">
      <button type="submit" class="btn btn-primary btn-block">%%%home_search_submit%%%</button>
    </div>    
  </form>
</div>      