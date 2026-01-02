<? $fabric=explode(",",$g['fabric']);?>
<div class="form-group">
<label class="vertical-label bd-" for="form_dresses-element-22">Fabric</label>
<select name="fabric[]" autocomplete="off" class=" fabric-ma form-control " multiple="multiple" id="form_dresses-element-22">
<option value="Chiffon" <? if(in_array("Chiffon",$fabric)){echo "selected='selected';";} ?>>Chiffon</option>
<option value="Lace1" <? if(in_array("Lace1",$fabric)){echo "selected='selected';";} ?>>Lace</option>
<option value="Mesh" <? if(in_array("Mesh",$fabric)){echo "selected='selected';";} ?>>Mesh</option>
<option value="Satin" <? if(in_array("Satin",$fabric)){echo "selected='selected';";} ?>>Satin</option>
<option value="Sequin1" <? if(in_array("Sequin1",$fabric)){echo "selected='selected';";} ?>>Sequin</option>
<option value="Spandex" <? if(in_array("Spandex",$fabric)){echo "selected='selected';";} ?>>Spandex</option>
<option value="Tulle" <? if(in_array("Tulle",$fabric)){echo "selected='selected';";} ?>>Tulle</option>
<option value="Velvet" <? if(in_array("Velvet",$fabric)){echo "selected='selected';";} ?>>Velvet</option>
</select>
</div>