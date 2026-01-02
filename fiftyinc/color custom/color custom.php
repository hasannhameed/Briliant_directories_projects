<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<? $color=explode(",",$g['color']);?>
<div class="form-group">
<label class="vertical-label bd-" for="form_dresses-element-18">Color Family</label>
<select name="color[]"  autocomplete="off" class=" dresscolor form-control "  multiple="multiple" id="form_dresses-element-18">
<option value="Black" <? if(in_array("Black",$color)){echo "selected='selected';";} ?>>Black</option>
<option value="Blue" <? if(in_array("Blue",$color)){echo "selected='selected';";} ?>>Blue</option>
<option value="Brown" <? if(in_array("Brown",$color)){echo "selected='selected';";} ?>>Brown</option>
<option value="Green" <? if(in_array("Green",$color)){echo "selected='selected';";} ?>>Green</option>
<option value="Gold" <? if(in_array("Gold",$color)){echo "selected='selected';";} ?>>Gold</option>
<option value="Ivory" <? if(in_array("Ivory",$color)){echo "selected='selected';";} ?>>Ivory</option>
<option value="Nude" <? if(in_array("Nude",$color)){echo "selected='selected';";} ?>>Nude</option>
<option value="Orange" <? if(in_array("Orange",$color)){echo "selected='selected';";} ?>>Orange</option>
<option value="Pink" <? if(in_array("Pink",$color)){echo "selected='selected';";} ?>>Pink</option>
<option value="Purple" <? if(in_array("Purple",$color)){echo "selected='selected';";} ?>>Purple</option>
<option value="Red" <? if(in_array("Red",$color)){echo "selected='selected';";} ?>>Red</option>
<option value="Silver" <? if(in_array("Silver",$color)){echo "selected='selected';";} ?>>Silver</option>
<option value="White" <? if(in_array("White",$color)){echo "selected='selected';";} ?>>White</option>
<option value="Yellow" <? if(in_array("Yellow",$color)){echo "selected='selected';";} ?>>Yellow</option>
</select></div>