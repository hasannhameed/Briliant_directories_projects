<?
//print_r($g);
$event=explode(",",$g['event']);?>

<div class="form-group"><label class="vertical-label bd-" for="form_dresses-element-20">Event</label><select name="event[]" autocomplete="off" class="dressevent form-control " multiple="multiple" id="form_dresses-element-20" >
	<option value="">Select</option>

	<option value="Ball" <? if(in_array("Ball",$event)){echo "selected='selected';";} ?>>Ball</option>
	<option value="Cocktail" <? if(in_array("Cocktail",$event)){echo "selected='selected';";} ?>>Cocktail</option>
	<option value="Evening" <? if(in_array("Evening",$event)){echo "selected='selected';";} ?>>Evening</option>
	<option value="Formal" <? if(in_array("Formal",$event)){echo "selected='selected';";} ?>>Formal</option>
	<option value="Gala" <? if(in_array("Gala",$event)){echo "selected='selected';";} ?>>Gala</option>
	<option value="Homecoming" <? if(in_array("Homecoming",$event)){echo "selected='selected';";} ?>>Homecoming</option>
	<option value="Pageant" <? if(in_array("Pageant",$event)){echo "selected='selected';";} ?>>Pageant</option>
	<option value="Prom" <? if(in_array("Prom",$event)){echo "selected='selected';";} ?>>Prom</option>
	<option value="Semi-Formal" <? if(in_array("Semi-Formal",$event)){echo "selected='selected';";} ?>>Semi-Formal</option>
	<option value="Wedding Guest" <? if(in_array("Wedding Guest",$event)){echo "selected='selected';";} ?>>Wedding Guest</option></select>
</div>

