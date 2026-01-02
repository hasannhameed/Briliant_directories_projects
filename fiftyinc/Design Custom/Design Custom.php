<? $design=explode(",",$g['design_style']);?>

<div class="form-group">
    <label class="vertical-label bd-" for="form_dresses-element-21">Design Details</label>
    <select name="design_style[]" autocomplete="off" class="designdetail form-control " multiple="multiple" id="form_dresses-element-21">
        <option value="Appliques" <? if(in_array("Appliques",$design)){echo "selected='selected';";} ?>>Appliques</option>
        <option value="Asymmetrical" <? if(in_array("Asymmetrical",$design)){echo "selected='selected';";} ?>>Asymmetrical</option>
        <option value="Backless" <? if(in_array("Backless",$design)){echo "selected='selected';";} ?>>Backless</option>
        <option value="Corset"<? if(in_array("Corset",$design)){echo "selected='selected';";} ?> >Corset</option>
        <option value="Feathers" <? if(in_array("Feathers",$design)){echo "selected='selected';";} ?>>Feathers</option>
        <option value="Fringe" <? if(in_array("Fringe",$design)){echo "selected='selected';";} ?>>Fringe</option>
        <option value="Glitter" <? if(in_array("Glitter",$design)){echo "selected='selected';";} ?>>Glitter</option>
        <option value="Halter" <? if(in_array("Halter",$design)){echo "selected='selected';";} ?>>Halter</option>
        <option value="High Slit" <? if(in_array("High Slit",$design)){echo "selected='selected';";} ?>>High Slit</option>
        <option value="Jeweled" <? if(in_array("Jeweled",$design)){echo "selected='selected';";} ?>>Jeweled</option>
        <option value="Lace" <? if(in_array("Lace",$design)){echo "selected='selected';";} ?>>Lace</option>
        <option value="Lace Up" <? if(in_array("Lace Up",$design)){echo "selected='selected';";} ?>>Lace Up</option>
        <option value="Mesh/Sheer" <? if(in_array("Mesh/Sheer",$design)){echo "selected='selected';";} ?>>Mesh/Sheer</option>
        <option value="Off Shoulder" <? if(in_array("Off Shoulder",$design)){echo "selected='selected';";} ?>>Off Shoulder</option>
        <option value="Pockets" <? if(in_array("Pockets",$design)){echo "selected='selected';";} ?>>Pockets</option>
        <option value="Rhinestones" <? if(in_array("Rhinestones",$design)){echo "selected='selected';";} ?>>Rhinestones</option>
        <option value="Sequin" <? if(in_array("Sequin",$design)){echo "selected='selected';";} ?>>Sequin</option>
        <option value="Sleeveless" <? if(in_array("Sleeveless",$design)){echo "selected='selected';";} ?>>Sleeveless</option>
        <option value="Sleeves" <? if(in_array("Sleeves",$design)){echo "selected='selected';";} ?>>Sleeves</option>
        <option value="Sparkly" <? if(in_array("Sparkly",$design)){echo "selected='selected';";} ?>>Sparkly</option>
        <option value="Strapless" <? if(in_array("Strapless",$design)){echo "selected='selected';";} ?>>Strapless</option>
        <option value="Straps" <? if(in_array("Straps",$design)){echo "selected='selected';";} ?>>Straps</option>
        <option value="Sweetheart" <? if(in_array("Sweetheart",$design)){echo "selected='selected';";} ?>>Sweetheart</option>
    </select></div>