<?php if ($wa['custom_29'] == "horizontal") { ?>
    <div class="col-xs-6 search_box fpad img-rounded">
       
        <div class="clearfix"></div>
       
        <div class="clearfix"></div>
        <form class="fpad form-inline website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">
            <div class="form-group col-sm-12 col-md-6 nolpad sm-norpad">
                <div class="input-group input-group-lg col-xs-12">
                    <select placeholder="%%%home_search_default_1%%%" name="sid" id="sid" class="form-control input-lg">
                        <option value=""></option>
                        <?php echo listProfessions($_GET['sid'],"option",$w)?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-6 nopad nomargin">
                <button type="submit" class="btn btn-lg btn-block btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <div class="col-md-6 col-xs-12 search_box fpad img-rounded center-block">
       
        <div class="clearfix"></div>
        <form class="fpad form-horizontal website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">
            
            <div class="form-group nomargin bpad">
                <select placeholder="%%%home_search_default_1%%%" name="sid" id="sid" class="form-control input-lg">
                    <option value=""></option>
                    <?php echo listProfessions($_GET['sid'],"option",$w);?>
                </select>
            </div>
            <div class="col-md-12 nopad tmargin">
                <button type="submit" class="btn btn-lg btn-block btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?>  
[widget=Custom - Homepage Search - Postal Code Search]