<?php
$request_url=explode("?",$_SERVER['REQUEST_URI']);
if(($pars[1]=='sell-products') && $pars[0]=="account"){
    $fc=mysql($w['database'],"select * from data_categories where data_id=73");
	$fcr=mysql_fetch_assoc($fc);
	$featurecat=$fcr['feature_categories']; 
?>
<div class="form-group">
	<label class="vertical-label">
		<?php if ($_ENV['f']['field_required'] == 1) { ?>
			<span class="required">* </span>
		<?php } if($_GET['cat']!="") $cat_select=$_GET['cat']; else if($g['group_category']!="") $cat_select=$g['group_category'];else $cat_select="";?>
		 Category</label>
	<select name="group_category" autocomplete="off" class="form-control car_category_select" required >
    <option value="">Select category</option>
		<?php
		$myCategory = $g[group_category];
		$feature_categories = str_replace(', ',',',$featurecat);
		$optionValue = explode(',', $feature_categories);
	 
		foreach ($optionValue as $key) {
		 
			$string =  (trim($key));
 $slug  =  strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $string));
 ?>
 <option value="<?=trim($slug)?>" <?  if ( trim($slug) == trim($cat_select) ) echo "selected";  ?> ><?=trim($key)?></option>
 <? 
/*		  if ( trim($slug) == trim($_GET['cat']) ) {
		      echo '<option value="' . $slug . '" selected >' . trim($key) . '</option>';
		  }else if(trim($slug) == trim($g['car_category'])){
			   echo '<option value="' . $slug . '" selected >' . trim($key) . '</option>';
			  }else{
		    echo '<option value="' .$slug . '">' . trim($key) . '</option>';
		    }
		}
*/		}?>
	</select>
</div>


<script>
	var url="<?=$request_url['0']?>";
$(document).ready(function(e) {
    $(".car_category_select").change(function(e){
			window.location.href=url+"?cat="+$(this).val();
		});
});
</script>
<? }?>