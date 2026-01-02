<?php
if ($pars[0] != 'search_results' && $pars[1] != '') {
    $tidquery = mysql($w['database'],"SELECT
            *
        FROM
            `list_services`
        WHERE
            filename = '".$pars[0]."'");

    while ($u = mysql_fetch_row($tidquery)) {
        $_GET['tid'] = $u[0];
    }
}
$djang=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classic_page_fields` WHERE id=39"); 
$rock=mysql_fetch_assoc($djang);
?>
<?php if ($wa['custom_29']=="horizontal") { ?>
    <div class="col-xs-12 search_box fpad topsection">
    <div class="rush">
        <?php if ($wa['custom_131']!="") { ?>
           <div class="mainsection" ><span class="fpad bold nomargin text-center rock" onClick="openPopup('head');">
			   <h1 style="color:#fff;margin-top:60px;">
			   <?=$rock['heading']?></h1></span>
            <div id="head" class="popup_one"  style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="mainheading">
      <input type="text" name="edited_value" value="<?=$rock['heading']?>">
        <input type="hidden" value="heading" id="heading" name="db_name">
         
      <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSave('mainheading',event)" value="Save">
      </form>
    </div>
            
            
		<div class="lp-txt" onClick="openPopup('head_one');"><?=$rock['heading_one']?></div>
        
        <div id="head_one" class="popup_one"  style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="header_one">
       <input type="text" name="edited_value" value="<?=$rock['heading_one']?>">
        <input type="hidden" value="heading_one" id="heading_one" name="db_name">
         
      <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSave('header_one',event)" value="Save">
      </form>
    </div>
        </div>
        
        
        
        <?php } ?>
        <div class="clearfix"></div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-4">
        </div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad">
        </div>
        <div class="clearfix"></div>
        <form class="fpad form-inline website-search" name="frm1" action="/search_results">
           <div  class="row rich">
            <div class="form-group  col-md-6  nolpad sm-norpad neuro">
                <div class="input-group   input-group-lg col-xs-12">
					<span class="input-group-addon trex"  onClick="openPopup('list_6');"> 
					<?php
$listdata=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=6");
$content6=mysql_fetch_assoc($listdata);?>
   
        <?=$content6['list_content'];?>
      
     </span>
                    <select placeholder="<?=$content6['placeholder'];?>" name="tid" id="bd-chained" class="form-control input-lg">
                        <option></option>
                        <?php
                        $topProfession = mysql($w['database'],"SELECT
                                profession_id
                            FROM
                                `list_professions`
                            LIMIT
                                1");
                            $prof = mysql_fetch_array($topProfession);
                            echo listServices(0,"list",$w,$prof['profession_id']); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 sm-norpad hero">
                <div class="input-group input-group-lg col-xs-12">	
                    <span class="input-group-addon rex axn" onClick="openPopup('list_7');"> 
					<?php
$listdata=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=7");
$content7=mysql_fetch_assoc($listdata);?>
   
       <b class="rax"> <?=$content7['list_content'];?></b>
      
     </span>
    
                    
   
                    <span class="input-group-addon rex axno"><b><i class="fa fa-location-arrow" aria-hidden="true"></i></b></span>
                    <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>" 
                    placeholder="<?=$content7['placeholder'];?>">
                </div>
            </div>
            <div class="form-group col-md-2 nopad nomargin neturo">
                <button type="submit" class="btn btn-block btn-lg btn_home_search">Search</button>
            </div>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="text-center lp-search-description gideon">
  <div style="color:#fff;"><span class="editable" data-value="heading_text"><?=$rock['heading_text']?></span>
  <img src="https://classic.listingprowp.com/wp-content/themes/listingpro/assets/images/banner-arrow.png" alt="banner-arrow" class="banner-arrow">
</div></div>
        <div class="clearfix"></div>
        </div>
    </div>
<?php } ?>  
<div class="frmfields">

<div id="list_7" class="popup_one"  style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list7">
        <input type="text"  maxlength="6"   value="<?=$content7['list_content'];?>"   name="content_data" >
      <input type= "text"  placeholder="Enter placeholder" name="placeholder"> 
        <input type="hidden" value="107" name="list_id">
        <input type="hidden" value="list7" id="list7" name="list_name">
      <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list7',event)" value="Save">
      </form>
    </div>
<div id="list_6" class="popup_one"  style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list6">
        <input type="text" maxlength="6" value="<?=$content6['list_content'];?>"   name="content_data" >
       <input type= "text" placeholder="Enter placeholder" name="placeholder"> 
        <input type="hidden" value="106" name="list_id">
        <input type="hidden" value="list6" id="list6" name="list_name">
      <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list6',event)" value="Save">
      </form>
    </div></div>