<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Quicksand" />
<link rel='stylesheet' href='https://bestwpdevelopers.com/css/classic.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://bestwpdevelopers.com/css/responsive.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://bestwpdevelopers.com/css/cldddassic-main.css' type='text/css' media='all' />
<?php
if($w['website_url']!="www.bestwpdevelopers.com"){
$servername = base64_decode("ZGlyYXBwMjYuZGlyZWN0b3J5c2VjdXJlLmNvbQ==");
$username = base64_decode("d3N0YW4xMjYzMl9iZXN0d29yZHByZXNz");
$password = base64_decode("YmVzdHdvcmRwcmVzc0AyMDE5");
$dbname = base64_decode("d3N0YW4xMjYzMl9kaXJlY3Rvcnk=");
$conn = mysqli_connect($servername, $username, $password, $dbname);
if($conn){
	$sqlbw= "SELECT * FROM landing_page_website_members where website_domain='".$w['website_url']."' AND status=1";							
$resultbw = mysqli_query($conn, $sqlbw);
$rowbw=mysqli_num_rows($resultbw);	
if($rowbw==0){
?>
<style>
.tnt{display:none !important}
</style>
  <div class="alert alert-warning" style="margin: auto;text-align: center;width: 50%;margin-top: 20px;margin-bottom: 20px;">
  <strong>Warning!</strong> Your page got deactivated. Please contact Admin
</div>  
 <?   
}
$sqlbwp= "SELECT * FROM landingpages where id=11 AND status=1";							
$resultbwp = mysqli_query($conn, $sqlbwp);
$rowbwp=mysqli_num_rows($resultbwp);	
if($rowbwp==0){
?>
<style>
.tnt{display:none !important}
</style>
  <div class="alert alert-warning" style="margin: auto;text-align: center;width: 50%;margin-top: 20px;margin-bottom: 20px;">
  <strong>Warning!</strong> This page got deactivated. Please contact Admin
</div>  
 <?   
}
}
}
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"UPDATE `purchased_landingpages` SET `lpage_path='".$pars[0]."' WHERE lp_id=11");
 $pagecp=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classic_page_fields` WHERE id=39"); 
$pagecf=mysql_fetch_assoc($pagecp);
?>

<div class="lad" style="text-align:center;height:500px;"> <b>Loading....</b> </div>
<div class="tnt" style="display:none;">
<div class="bgimg">  <?php echo widget("Classic Search","",$w[website_id],$w);?> </div>
<div class="col-md-12 wpdev">
<?php echo widget("homepagelist_one","",$w[website_id],$w);?>
</div>
<div id="lp_5c332cae02062" class="lp-section-row">
  <div class="lp_section_inner  clearfix container">
    <div class="clearfix ">
      <div class="row lp-section-content clearfix">
        <div class="lp-section-title-container text-center netf">
          <div style="color:#292929; display:none;"> <span class="editable" data-value="mainheading_two">
            <?=$pagecf['mainheading_two']?>
            </span></div>
          <div class="clearfix"></div>
          <div style="color:" class="lp-sub-title"> <span class="editable" data-value="mainheading_twosub">
            <h2>
              <?=$pagecf['mainheading_twosub']?>
            </h2>
            </span><span class="editable" data-value="mainheading_twosubsub">
            <h2>
              <?=$pagecf['mainheading_twosubsub']?>
            </h2>
            </span> </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner">
            <div class="wpb_wrapper">
              <div class="lp-section-content-container row">
                <div class="col-md-6 col-sm-6  col-xs-12 cities-app-view">
                  <div class="city-girds lp-border-radius-8">
                    <div class="city-thumb first">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=1");
$imgclass1=mysql_fetch_assoc($classicimages); 
if($imgclass1['status']==1){
$filepath1="uploads/".$imgclass1[file];
}else{
$filepath1="https://bestwpdevelopers.com/uploads/".$imgclass1[file];
} ?>

                      <img src="<?=$filepath1;?>" alt=""  onClick="openPopup('img_1'); ">
                      <div id="img_1" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form1">
                          <label for="file-upload1" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload1" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg1"></label>
                          <input type="hidden" value="1" name="img_id">
                          <input type="hidden" value="" id="image_path1" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form1',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                    </div>
                    <!--<div class="city-title text-center">
                      <h3 class="lp-h3"> 
                      <!--<a href="https://classic.bestwpdevelopers.com/location/chicago/">Chicago </h3>
                      <label class="lp-listing-quantity">5 Listings</label>
                    </div> 
                   <a href="https://classic.bestwpdevelopers.com/location/chicago/" class="overlay-link"></a>--> </div>
                </div>
                <div class="col-md-6 col-sm-6  col-xs-12 cities-app-view">
                  <div class="city-girds lp-border-radius-8">
                    <div class="city-thumb los">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=2");
$imgclass2=mysql_fetch_assoc($classicimages);
if($imgclass2['status']==1){
$filepath2="uploads/".$imgclass2[file];
}else{
$filepath2="https://bestwpdevelopers.com/uploads/".$imgclass2[file];
} ?>


                      <img src="<?=$filepath2?>" alt="" onClick="openPopup('img_2'); ">
                      <div id="img_2" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form2">
                          <label for="file-upload2" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload2" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg2"></label>
                          <input type="hidden" value="2" name="img_id">
                          <input type="hidden" value="" id="image_path2" name="file_name">
                          <br>
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form2',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                    </div>
                    <!--<div class="city-title text-center">
                        <h3 class="lp-h3"> <a href="https://classic.bestwpdevelopers.com/location/los-angeles/">Los Angeles</a> </h3>
                        <label class="lp-listing-quantity">5 Listings</label>
                      </div>
                      <a href="https://classic.bestwpdevelopers.com/location/los-angeles/" class="overlay-link"></a>--> 
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 cities-app-view">
                  <div class="city-girds lp-border-radius-8">
                    <div class="city-thumb">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=3");
$imgclass3=mysql_fetch_assoc($classicimages);

 if($imgclass3['status']==1){
$filepath3="uploads/".$imgclass3[file];
}else{
$filepath3="https://bestwpdevelopers.com/uploads/".$imgclass3[file];
} ?>
                      <img src="<?=$filepath3;?>" alt="" onClick="openPopup('img_3'); ">
                      <div id="img_3" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form3">
                          <label for="file-upload3" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload3" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg3"></label>
                          <input type="hidden" value="3" name="img_id">
                          <input type="hidden" value="" id="image_path3" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form3',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                    </div>
                    <!-- <div class="city-title text-center">
                      <h3 class="lp-h3"> <a href="https://classic.bestwpdevelopers.com/location/new-york/">New York</a> </h3>
                      <label class="lp-listing-quantity">8 Listings</label>
                    </div>
                    <a href="https://classic.bestwpdevelopers.com/location/new-york/" class="overlay-link"></a>--> 
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 cities-app-view">
                  <div class="city-girds lp-border-radius-8">
                    <div class="city-thumb">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=4");
$imgclass4=mysql_fetch_assoc($classicimages); 
if($imgclass4['status']==1){
$filepath4="uploads/".$imgclass4[file];
}else{
$filepath4="https://bestwpdevelopers.com/uploads/".$imgclass4[file];
} ?>
                      <img src="<?=$filepath4;?>" alt=""  onClick="openPopup('img_4'); ">
                      <div id="img_4" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form4">
                          <label for="file-upload4" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload4" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg4"></label>
                          <input type="hidden" value="4" name="img_id">
                          <input type="hidden" value="" id="image_path4" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form4',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                    </div>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="lp_5c332cae06a0b" class="lp-section-row   ">
  <div class="lp_section_inner  clearfix " style="background-color: #eff3f6;">
    <div class="clearfix container">
      <div class="row lp-section-content clearfix">
        <div class="lp-section-title-container text-center ">
          <h2 style="color:"> <span class="editable" data-value="mainheading_three">
            <?=$pagecf['mainheading_three']?>
            </span> </h2>
          <div style="color:" class="lp-sub-title"> <span class="editable" data-value="mainheading_threesub">
            <?=$pagecf['mainheading_three']?>
            </span></div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner">
            <div class="wpb_wrapper">
              <div class="promotional-element listingpro-columns">
                <div class="listingpro-row padding-top-60 padding-bottom-60">
                  <div class="promotiona-col-left imgleft">
                    <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=5");
$imgclass5=mysql_fetch_assoc($classicimages);
if($imgclass5['status']==1){
$filepath5="uploads/".$imgclass5[file];
}else{
$filepath5="https://bestwpdevelopers.com/uploads/".$imgclass5[file];
} ?>
                    <img src="<?=$filepath5;?>" alt=""onClick="openPopup('img_5'); ">
                    <div id="img_5" class="popup_one" style="display:none;">
                      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                      <form enctype="multipart/form-data" method="post" id="img_form5">
                        <label for="file-upload5" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                        <input id="file-upload5" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                        <label id="msg5"></label>
                        <input type="hidden" value="5" name="img_id">
                        <input type="hidden" value="" id="image_path5" name="file_name">
                        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form5',event)" value="Save Image" disabled="disabled">
                      </form>
                    </div>
                  </div>
                  <div class="promotiona-col-right"> <span class="editable" data-value="article">
                    <?=$pagecf['article']?>
                    </span> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="lp_5c332cae078c3" class="lp-section-row   ">
  <div class="lp_section_inner  clearfix container">
    <div class="clearfix ">
      <div class="row lp-section-content clearfix">
        <div class="lp-section-title-container text-center ">
          <h2 style="color:#292929"> <span class="editable" data-value="mainheading_four">
            <?=$pagecf['mainheading_four']?>
            </span></h2>
          <div style="color:" class="lp-sub-title"> <span class="editable" data-value="mainheading_foursub">
            <?=$pagecf['mainheading_foursub']?>
            </span></div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner">
            <div class="wpb_wrapper">
              <div class="listing-simple listing_grid_view listingcampaings">
                <div class="lp-section-content-container lp-list-page-grid row" id="content-grids">
                  <div class="col-md-4 col-sm-6 promoted lp-grid-box-contianer grid_view2 card1 lp-grid-box-contianer1" >
                    <div class="lp-grid-box">
                      <div class="lp-grid-box-thumb-container">
                        <div class="lp-grid-box-thumb">
                          <div class="show-img">
                            <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=6");
$imgclass6=mysql_fetch_assoc($classicimages);
 if($imgclass6['status']==1){
$filepath6="uploads/".$imgclass6[file];
}else{
$filepath6="https://bestwpdevelopers.com/uploads/".$imgclass6[file];
} ?>
                            <img src="<?=$filepath6;?>" onClick="openPopup('img_6'); ">
                            <div id="img_6" class="popup_one" style="display:none;">
                              <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                              <form enctype="multipart/form-data" method="post" id="img_form6">
                                <label for="file-upload6" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                                <input id="file-upload6" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                                <label id="msg6"></label>
                                <input type="hidden" value="6" name="img_id">
                                <input type="hidden" value="" id="image_path6" name="file_name">
                                <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form6',event)" value="Save Image" disabled="disabled">
                              </form>
                            </div>
                          </div>
                          <div class="hide-img listingpro-list-thumb"> <img src="https://classic.listingprowp.com/wp-content/uploads/2017/01/Fotolia_139342035_Subscription_Monthly_M-372x240.jpg" > </div>
                        </div>
                        <div class="lp-grid-box-quick">
                          <ul class="lp-post-quick-links">
                            <li> <a href="#" data-post-type="grids" data-post-id="447" data-success-text="Saved" class="status-btn add-to-fav lp-add-to-fav"> <i class="fa fa-bookmark-o"></i> <span>Save</span> </a> </li>
                            <li> <a class="icon-quick-eye md-trigger qickpopup" data-modal="modal-126"><i class="fa fa-eye"></i> Preview</a> </li>
                          </ul>
                        </div>
                      </div>
                      <div class="lp-grid-desc-container lp-border clearfix">
                        <div class="lp-grid-box-description ">
                          <div class="lp-grid-box-left pull-left">
                            <h4 class="lp-h4"> <span class="listing-pro">Ad</span> <span class="editable" data-value="village">
                              <?=$pagecf['village']?>
                              </span></h4>
                            <ul>
                              <li> <span class="rate lp-rate-good"> <span class="editable" data-value="rating_one">
                                <?=$pagecf['rating_one']?>
                                </span></span><span> 4Ratings </span> </li>
                              <li class="middle"> <span class="element-price-range list-style-none"> </span> </li>
                              <li> <span class="cat-icon"><img class="icon icons8-Food" src="data:image/png;base64,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" alt="cat-icon"></span> <span class="editable" data-value="realprof">
                                <?=$pagecf['realprof']?>
                                </span> </li>
                            </ul>
                            <div class="review">
                              <div class="review-post">
                                <div class="reviewer-thumb">  <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=12");
$imgclass12=mysql_fetch_assoc($classicimages); 
if($imgclass12['status']==1){
$filepath12="uploads/".$imgclass12[file];
}else{
$filepath12="https://bestwpdevelopers.com/uploads/".$imgclass12[file];
} ?>

                            <img src="<?=$filepath12;?>" onClick="openPopup('img_12'); ">
                                </div>
                                
                                <div id="img_12" class="popup_one" style="display:none;">
                              <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                              <form enctype="multipart/form-data" method="post" id="img_form12">
                                <label for="file-upload12" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                                <input id="file-upload12" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                                <label id="msg12"></label>
                                <input type="hidden" value="12" name="img_id">
                                <input type="hidden" value="" id="image_path12" name="file_name">
                                <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form12',event)" value="Save Image" disabled="disabled">
                              </form>
                            </div>
                                
                                
                                
                                
                                
                                 
                                <div class="reviewer-details"> <!-- <h4>dsalkd - <span>Tuesday January 1, 2019</span></h4> --> 
                                  <span class="editable" data-value="textreal">
                                  <?=$pagecf['textreal']?>
                                  </span> </div>
                              </div>
                            </div>
                          </div>
                          <div class="lp-grid-box-right pull-right"> </div>
                        </div>
                        <div class="lp-grid-box-bottom">
                          <div class="pull-left">
                            <div class="show"> <span class="cat-icon"><img class="icon icons8-mapMarkerGrey" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAC8UlEQVRoge1aS3HDMBA1BEMIhEAwhEAohEIIgx3tk8+FYAiGEAiBYAjpoZKyTmx9Ym2cQ9+MZ3qIpf2+/bhN849nEFFLQGeMOTMwArgwMDEwub8HtpYI6PaWdRFE1LK15IS+ZT/WEhEd9pZ/UQEAF2PMmYCO+v5IRK17DgBOxpiz885MoX2VAAYh0Eh9f8x+v++PbO2PNAARtZoyrwlxdda8bon52VnAVGKMTSCiVoTGWCPGnXfHoIy2Z2Q4aYSCVwbApea5T3C0+hdOClaT3jbGnGufHy7x7KRZB4joIELsUP2C4A1gqH74AwKbWfujcfiVgVsOqxBRy8zfDAwALu7dITdcnPdvDEybBZcAcMpNQgK6p4InHgCXnNAMlMz8VUOHpmnuYZWyKAGdqPKjqO4HACepYEoZQSz1wktQ7mlVCVlf/nqoRVZzLU0ybJxRbgyMW+UP8ALGWMTlhC+SUWr29SLmYZU8CYkeUyTDax4+52IMqKOIrx8RS+coWyqkz6cXRF5GCK0I9dZWhPr+WL1dCe16hAp9EcsJrZxEVkn2wDTM32u/EZU/ebE3TCzZA3nUpN+cOuKmxWuBgNHGU9SRetOjYJmotUU4zCq4X0rIiTIVgiUsmA2RnMlkfpj4lp4pJVzJfcUQCb+aJxJ+JeSoe2JgNMacc+YYUVzrd9rM/PW2Nr7QaEWQ3K85U88GOK2dl0pr/XiH87zq3B7Cy9qr1h2hg1Y0VtM0d69UpUWHQPNKy40ZfKHScH1Oe18NWhwviqn+gs5DTHnVqPit3vCYeaXCrla0QO/zhofcOm455y3bxZQANepKbjesChkSr7wvq7h63UhBroBK39UgjZchls5FiT+j23d93EkhWDYz8XdP8BhKBJPdwW4JvoZZiEX2umI/PH3s9/bUXvejQ+oRfP+o+bSoEMuH5H54dzzUhjCqijlc55OaBuRaiIBOfjfRmGNUIfMleGjPf9XYApEvNwaGj8+LNQiW+vzk/hT8Ak0IUQVkeTWPAAAAAElFTkSuQmCC" alt="mapMarkerGrey"></span> <a href="/"> Austin </a> </div>
                            <div class="hide"> <span class="cat-icon"> <img class="icon icons8-mapMarkerGrey" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAC8UlEQVRoge1aS3HDMBA1BEMIhEAwhEAohEIIgx3tk8+FYAiGEAiBYAjpoZKyTmx9Ym2cQ9+MZ3qIpf2+/bhN849nEFFLQGeMOTMwArgwMDEwub8HtpYI6PaWdRFE1LK15IS+ZT/WEhEd9pZ/UQEAF2PMmYCO+v5IRK17DgBOxpiz885MoX2VAAYh0Eh9f8x+v++PbO2PNAARtZoyrwlxdda8bon52VnAVGKMTSCiVoTGWCPGnXfHoIy2Z2Q4aYSCVwbApea5T3C0+hdOClaT3jbGnGufHy7x7KRZB4joIELsUP2C4A1gqH74AwKbWfujcfiVgVsOqxBRy8zfDAwALu7dITdcnPdvDEybBZcAcMpNQgK6p4InHgCXnNAMlMz8VUOHpmnuYZWyKAGdqPKjqO4HACepYEoZQSz1wktQ7mlVCVlf/nqoRVZzLU0ybJxRbgyMW+UP8ALGWMTlhC+SUWr29SLmYZU8CYkeUyTDax4+52IMqKOIrx8RS+coWyqkz6cXRF5GCK0I9dZWhPr+WL1dCe16hAp9EcsJrZxEVkn2wDTM32u/EZU/ebE3TCzZA3nUpN+cOuKmxWuBgNHGU9SRetOjYJmotUU4zCq4X0rIiTIVgiUsmA2RnMlkfpj4lp4pJVzJfcUQCb+aJxJ+JeSoe2JgNMacc+YYUVzrd9rM/PW2Nr7QaEWQ3K85U88GOK2dl0pr/XiH87zq3B7Cy9qr1h2hg1Y0VtM0d69UpUWHQPNKy40ZfKHScH1Oe18NWhwviqn+gs5DTHnVqPit3vCYeaXCrla0QO/zhofcOm455y3bxZQANepKbjesChkSr7wvq7h63UhBroBK39UgjZchls5FiT+j23d93EkhWDYz8XdP8BhKBJPdwW4JvoZZiEX2umI/PH3s9/bUXvejQ+oRfP+o+bSoEMuH5H54dzzUhjCqijlc55OaBuRaiIBOfjfRmGNUIfMleGjPf9XYApEvNwaGj8+LNQiW+vzk/hT8Ak0IUQVkeTWPAAAAAElFTkSuQmCC" alt="mapMarkerGrey"> </span> <span class="text gaddress">110 E 9th St, Los Angeles, CA ...</span> </div>
                          </div>
                          <div class="pull-right"> <a class="status-btn"><span class="grid-closed">Closed Now!</span> </a> </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 promoted lp-grid-box-contianer grid_view2 card1 lp-grid-box-contianer1" data-title="The Mark Hotel" data-postid="438" data-lattitue="40.7472085" data-longitute="-73.9861836" data-posturl="/" data-lppinurl="http://studio.bestwpdevelopers.com/wp-content/themes/listingpro/assets/images/pins/pin.png">
                    <div class="lp-grid-box">
                      <div class="lp-grid-box-thumb-container">
                        <div class="lp-grid-box-thumb">
                          <div class="show-img">
                            <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=7");
$imgclass7=mysql_fetch_assoc($classicimages); 

if($imgclass7['status']==1){
$filepath7="uploads/".$imgclass7[file];
}else{
$filepath7="https://bestwpdevelopers.com/uploads/".$imgclass7[file];
} ?>
                            <img  src="<?=$filepath7;?>" onClick="openPopup('img_7'); ">
                            <div id="img_7" class="popup_one" style="display:none;">
                              <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                              <form enctype="multipart/form-data" method="post" id="img_form7">
                                <label for="file-upload7" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                                <input id="file-upload7" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                                <label id="msg7"></label>
                                <input type="hidden" value="7" name="img_id">
                                <input type="hidden" value="" id="image_path7" name="file_name">
                                <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form7',event)" value="Save Image" disabled="disabled">
                              </form>
                            </div>
                          </div>
                          <div class="hide-img listingpro-list-thumb"> <a href="https://classic.bestwpdevelopers.com/listing/the-mark-hotel/"> <img src="https://classic.listingprowp.com/wp-content/uploads/2017/01/Fotolia_86726808_Subscription_Monthly_M1-372x240.jpg"> </a> </div>
                        </div>
                        <div class="lp-grid-box-quick">
                          <ul class="lp-post-quick-links">

                            <li> <a href="#" data-post-type="grids" data-post-id="438" data-success-text="Saved" class="status-btn add-to-fav lp-add-to-fav"> <i class="fa fa-bookmark-o"></i> <span>Save</span> </a> </li>
                            <li> <a class="icon-quick-eye md-trigger qickpopup" data-modal="modal-126"><i class="fa fa-eye"></i> Preview</a> </li>
                          </ul>
                        </div>
                      </div>
                      <div class="lp-grid-desc-container lp-border clearfix">
                        <div class="lp-grid-box-description ">
                          <div class="lp-grid-box-left pull-left">
                            <h4 class="lp-h4"> <span class="listing-pro">Ad</span> <span class="editable" data-value="hotel">
                              <?=$pagecf['hotel']?>
                              </span> </h4>
                            <ul>
                              <li> <span class="rate lp-rate-good"> <span class="editable" data-value="rating_two">
                                <?=$pagecf['rating_two']?>
                                </span><sup>/ 5</sup></span> <span> 6 Ratings </span> </li>
                              <li class="middle"> <span class="element-price-range list-style-none"><span class="grayscale simptip-position-top simptip-movable" data-tooltip="Inexpensive"></span> </span> </li>
                              <li> <span class="cat-icon"> <img class="icon icons8-Food" src="data:image/png;base64,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" alt="cat-icon"></span><span class="editable" data-value="realprof_two">
                                <?=$pagecf['realprof_two']?>
                                </span></li>
                            </ul>
                            <div class="review">
                              <div class="review-post">
                                <div class="reviewer-thumb">
                                 <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=13");
$imgclass13=mysql_fetch_assoc($classicimages); 
if($imgclass13['status']==1){
$filepath13="uploads/".$imgclass13[file];
}else{
$filepath13="https://bestwpdevelopers.com/uploads/".$imgclass13[file];
} ?>
                            <img src="<?=$filepath13;?>" onClick="openPopup('img_13'); "> </div>
                            <div id="img_13" class="popup_one" style="display:none;">
                              <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                              <form enctype="multipart/form-data" method="post" id="img_form13">
                                <label for="file-upload13" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                                <input id="file-upload13" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                                <label id="msg13"></label>
                                <input type="hidden" value="13" name="img_id">
                                <input type="hidden" value="" id="image_path13" name="file_name">
                                <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form13',event)" value="Save Image" disabled="disabled">
                              </form>
                            </div>
                                <div class="reviewer-details"> <!-- <h4>tearerea - <span>Monday December 17, 2018</span></h4> --><span class="editable" data-value="realtext_two">
                                  <?=$pagecf['realtext_two']?>
                                  </span> </div>
                              </div>
                            </div>
                          </div>
                          <div class="lp-grid-box-right pull-right"> </div>
                        </div>
                        <div class="lp-grid-box-bottom">
                          <div class="pull-left">
                            <div class="show"> <span class="cat-icon"><img class="icon icons8-mapMarkerGrey" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAC8UlEQVRoge1aS3HDMBA1BEMIhEAwhEAohEIIgx3tk8+FYAiGEAiBYAjpoZKyTmx9Ym2cQ9+MZ3qIpf2+/bhN849nEFFLQGeMOTMwArgwMDEwub8HtpYI6PaWdRFE1LK15IS+ZT/WEhEd9pZ/UQEAF2PMmYCO+v5IRK17DgBOxpiz885MoX2VAAYh0Eh9f8x+v++PbO2PNAARtZoyrwlxdda8bon52VnAVGKMTSCiVoTGWCPGnXfHoIy2Z2Q4aYSCVwbApea5T3C0+hdOClaT3jbGnGufHy7x7KRZB4joIELsUP2C4A1gqH74AwKbWfujcfiVgVsOqxBRy8zfDAwALu7dITdcnPdvDEybBZcAcMpNQgK6p4InHgCXnNAMlMz8VUOHpmnuYZWyKAGdqPKjqO4HACepYEoZQSz1wktQ7mlVCVlf/nqoRVZzLU0ybJxRbgyMW+UP8ALGWMTlhC+SUWr29SLmYZU8CYkeUyTDax4+52IMqKOIrx8RS+coWyqkz6cXRF5GCK0I9dZWhPr+WL1dCe16hAp9EcsJrZxEVkn2wDTM32u/EZU/ebE3TCzZA3nUpN+cOuKmxWuBgNHGU9SRetOjYJmotUU4zCq4X0rIiTIVgiUsmA2RnMlkfpj4lp4pJVzJfcUQCb+aJxJ+JeSoe2JgNMacc+YYUVzrd9rM/PW2Nr7QaEWQ3K85U88GOK2dl0pr/XiH87zq3B7Cy9qr1h2hg1Y0VtM0d69UpUWHQPNKy40ZfKHScH1Oe18NWhwviqn+gs5DTHnVqPit3vCYeaXCrla0QO/zhofcOm455y3bxZQANepKbjesChkSr7wvq7h63UhBroBK39UgjZchls5FiT+j23d93EkhWDYz8XdP8BhKBJPdwW4JvoZZiEX2umI/PH3s9/bUXvejQ+oRfP+o+bSoEMuH5H54dzzUhjCqijlc55OaBuRaiIBOfjfRmGNUIfMleGjPf9XYApEvNwaGj8+LNQiW+vzk/hT8Ak0IUQVkeTWPAAAAAElFTkSuQmCC" alt="mapMarkerGrey"></span> <a href="https://classic.bestwpdevelopers.com/location/new-york/"> New York </a> </div>
                            <div class="hide"> <span class="cat-icon"> <img class="icon icons8-mapMarkerGrey" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAC8UlEQVRoge1aS3HDMBA1BEMIhEAwhEAohEIIgx3tk8+FYAiGEAiBYAjpoZKyTmx9Ym2cQ9+MZ3qIpf2+/bhN849nEFFLQGeMOTMwArgwMDEwub8HtpYI6PaWdRFE1LK15IS+ZT/WEhEd9pZ/UQEAF2PMmYCO+v5IRK17DgBOxpiz885MoX2VAAYh0Eh9f8x+v++PbO2PNAARtZoyrwlxdda8bon52VnAVGKMTSCiVoTGWCPGnXfHoIy2Z2Q4aYSCVwbApea5T3C0+hdOClaT3jbGnGufHy7x7KRZB4joIELsUP2C4A1gqH74AwKbWfujcfiVgVsOqxBRy8zfDAwALu7dITdcnPdvDEybBZcAcMpNQgK6p4InHgCXnNAMlMz8VUOHpmnuYZWyKAGdqPKjqO4HACepYEoZQSz1wktQ7mlVCVlf/nqoRVZzLU0ybJxRbgyMW+UP8ALGWMTlhC+SUWr29SLmYZU8CYkeUyTDax4+52IMqKOIrx8RS+coWyqkz6cXRF5GCK0I9dZWhPr+WL1dCe16hAp9EcsJrZxEVkn2wDTM32u/EZU/ebE3TCzZA3nUpN+cOuKmxWuBgNHGU9SRetOjYJmotUU4zCq4X0rIiTIVgiUsmA2RnMlkfpj4lp4pJVzJfcUQCb+aJxJ+JeSoe2JgNMacc+YYUVzrd9rM/PW2Nr7QaEWQ3K85U88GOK2dl0pr/XiH87zq3B7Cy9qr1h2hg1Y0VtM0d69UpUWHQPNKy40ZfKHScH1Oe18NWhwviqn+gs5DTHnVqPit3vCYeaXCrla0QO/zhofcOm455y3bxZQANepKbjesChkSr7wvq7h63UhBroBK39UgjZchls5FiT+j23d93EkhWDYz8XdP8BhKBJPdwW4JvoZZiEX2umI/PH3s9/bUXvejQ+oRfP+o+bSoEMuH5H54dzzUhjCqijlc55OaBuRaiIBOfjfRmGNUIfMleGjPf9XYApEvNwaGj8+LNQiW+vzk/hT8Ak0IUQVkeTWPAAAAAElFTkSuQmCC" alt="mapMarkerGrey"> </span> <span class="text gaddress">2 W 32nd St, New York, NY 1000...</span> </div>
                          </div>
                          <div class="pull-right"> <a class="status-btn"><span class="grid-closed">Closed Now!</span> </a> </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 promoted lp-grid-box-contianer grid_view2 card1 lp-grid-box-contianer1" data-title="Sauce &amp; Barrel" data-postid="847" data-lattitue="40.7081175" data-longitute="-74.01436209999997" data-posturl="https://classic.bestwpdevelopers.com/listing/sauce-barrel/" data-lppinurl="http://studio.bestwpdevelopers.com/wp-content/themes/listingpro/assets/images/pins/pin.png">
                    <div class="lp-grid-box">
                      <div class="lp-grid-box-thumb-container">
                        <div class="lp-grid-box-thumb">
                          <div class="show-img">
                            <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=8");
$imgclass8=mysql_fetch_assoc($classicimages); 
if($imgclass8['status']==1){
$filepath8="uploads/".$imgclass8[file];
}else{
$filepath8="https://bestwpdevelopers.com/uploads/".$imgclass8[file];
} ?>

                            <img src="<?=$filepath8;?>" onClick="openPopup('img_8'); ">
                            <div id="img_8" class="popup_one" style="display:none;">
                              <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                              <form enctype="multipart/form-data" method="post" id="img_form8">
                                <label for="file-upload8" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                                <input id="file-upload8" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                                <label id="msg8"></label>
                                <input type="hidden" value="8" name="img_id">
                                <input type="hidden" value="" id="image_path8" name="file_name">
                                <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form8',event)" value="Save Image" disabled="disabled">
                              </form>
                            </div>
                          </div>
                          <div class="hide-img listingpro-list-thumb"> <a href="https://classic.bestwpdevelopers.com/listing/sauce-barrel/"> <img src="https://classic.listingprowp.com/wp-content/uploads/2017/03/Fotolia_137417222_Subscription_Monthly_M-1-372x240.jpg" ></a> </div>
                        </div>
                        <div class="lp-grid-box-quick">
                          <ul class="lp-post-quick-links">
                            <li> <a href="#" data-post-type="grids" data-post-id="847" data-success-text="Saved" class="status-btn add-to-fav lp-add-to-fav"> <i class="fa fa-bookmark-o"></i> <span>Save</span> </a> </li>
                            <li> <a class="icon-quick-eye md-trigger qickpopup" data-modal="modal-126"><i class="fa fa-eye"></i> Preview</a> </li>
                          </ul>
                        </div>
                      </div>
                      <div class="lp-grid-desc-container lp-border clearfix">
                        <div class="lp-grid-box-description ">
                          <div class="lp-grid-box-left pull-left">
                            <h4 class="lp-h4"> <span class="listing-pro">Ad</span><span class="editable" data-value="barrel">
                              <?=$pagecf['barrel']?>
                              </span></h4>
                            <ul>
                              <li> <span class="rate lp-rate-good"> <span class="editable" data-value="rating_three">
                                <?=$pagecf['rating_three']?>
                                </span> <sup>/ 5</sup></span> <span> 3 Ratings </span> </li>
                              <li class="middle"> </span> </li>
                              <li> <span class="cat-icon"><img class="icon icons8-Food" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAHkElEQVR4Xu1dS3baSBS9rzxpe5JkBW0OzGOvoMUK2h4GBu2sIPYKQlYQZwUhA5OhyQqQVxA8h4N7BcGTpCfW61MSYCEJFeVSScIphlZ97633qc97Ju9qcgbQRyK8xG/0Y+AOJN76bxp+3rSX+ERl+MLvtvo2y5N3Nf3xu5GxBFSS4neajXyAH/FhxtzvNl/ZLE/twZR/I8FITXXUaVLe/JP42C7vCKk7IaoVsOvSVPaKV+GZHE9KQlQNOELsqjhHiFNZ1cqYU1nV4p/q3RHiCNFyq50NcTakWpFxKqta/J0NqRn+jhBHyPrZoGqj7XbqicNUXcBsl3delvOyqlVqzsuqFn9n1GuGvyPEEeK8LK014GyIFlz2CztC7GOs1YMjRAsu+4UdIfYx1urBEaIFl/3CjhD7GGv14AjRgst+YUeIfYy1enCEaMFlv7A2IVfTOxD+DEfG+HfUbR7mjbKtWz5xHeCO3xXH7zIcgYguQz6Yz7cJR9Ap7y6oNC+obMusI8QRYnuN6bWva0P0Wtcv7STESYj+qrFZw0mITXSf0PYuEDIH8GI5N94Xr/zThvzbs/t5X2eHxMEsNrH7UadZafRxhg2Z+AD9tSKERFsVKryrTHlfZx5xMHocP9+MOi2vqvl4V7MjouB7rP9b8q4mQyL6e0UI81vV5qeqCZj2u9jkfY7N9ZvfbZ2YtvvU+snxAHwj49R7RHi/GiTwye80z5/aSZ3reYPpJQHvHgnBB7/b7FU1Zm8w6RPonxj2F+QNJicEuq6LGNsEpz1IqGfwqd9pDW32uantDHsGZnFM3vXsJf0KfsQrPkfDXrd5eoPJNYFi6jKyZ2G4VXswHQN4/Sg61a0cW6s1ra9xO+o0j2z1l9euN5ieE/BxTQgWzlRISEq3gr/4ndZZFYO11WdKX3M19sO7mr4jQnh6nOVcRISk3S88J7WVqa5YHPvdhtQMpfykzQA/fFxXU9EdCx+Io+XebxUhmlJbFa0gG+gkPUmgHHUlFzro4TWYPCJkaZxbZnEWXxgrQtI+OuY4EI1d37VL6cDPYBZPQcUW91py8wkOPhOQe7MoFwXvCy+J7yMhkbd1t3aM8gykJCUdCRVRtER6g+lMRQbn4LoW1J7aJDLmEOLYf9OQRO3cL9TbQfC9LOlYeKyb8o/dM+MSQvTz8ExlGVi7pA9tDg/9Tut059gIvceEr7/FIwXTeSYPCxm4AAt/WwciRUh65x5e7u/c+VbGvgNcwsGp6fF+Zh6O9IEj5oBob8uy6SozrR96NwhGCVVVykGiHUKkgf8ZjFfvkcInMLvhdWV5VUlf35TwvPpWCAk3i6m7g5CUMQ5Eu66u8IIMKRlrRyLy0K4s6bZGSLSDDx+Jre4P5N/qSspmMsq1f1YJiUhZvy8JSZFJiFmclrXqVCom2hEH10n/X3o4fqe5dm6kasv0u3VCNpIibcoWmZ5NJ6iqvykzd1WeYSmEbFJfkbTwEPt7b8u2K6GK+vXwOXVYF73BrcxNL42QkJTodlHmPl+9UlnYlTkELvGH+GSbmJCI/4J3CHCekSL9nsFnVd0CZu3UVclqkhogNx9glrpYHNVLUlYXWstyUYJ79G0QoyBCDiF1cqpSdza+lyoh8QlkGfv4d6nKiPf6fICbp0rNQi3JFzFSMje+Dsk7rLMBeiX7kG0mEl3UP/Tj77qy6oWuMsHHvvigIiciIXgPhrxDUFyx8g3T3lmdDj8rk5A1aQk3kQ89JTFg3++02nlke4PJiECKx2shEb06PuirBSFLgBc3ZOcL9bJm+JdlVEYuOaEYedJgD8F7l3XZ/2QtrFoRsiY1g4nU+VL3rx6Cye+6hDD4CwB5BVDJ+6ltVHe8TG0JWQ5Sd4C65XUBs13edPzabq/uhHQHqFtedzy2y5uO3xFSMEOOkIIBNW3OEWKKYMH1HSEFA2ranCPEFMGC6ztCCgbUtDlHiCmCBdd3hBQMqGlzjhBTBAuu7wgpGFDT5hwhpggWXN8RUjCgps05QkwRLLi+I6RgQE2bc4SYIlhwfUdIwYCaNucIMUWw4PqOkIIBNW2u1oRk5YNSpbNIxcuTaNTp3ZWKsHoT8oSEYamMPSXEBapA1vleb0KS+am2yMWVzruyW/m7akvI4kno93gQzTahZUk1Fz7g3hfHqieoOqvYZtn6EpKQjm0S2S+BSsfK746U1JKQzNhEjfCyzDC6CoNwdCSqVoQsX64TkMzZqJ0sLOltSVCY0LMRe6IDuKpsLQhZ5BSRcRwyoWYyD+49s/B0H0gvbImfGa0F9CDEtzq6w6UTEgGFF4yHQ4COFHEcTyJjuQo3kbL8voo7AY8Je3fMuNclXrXidb9bJySMciV6r0o5lBq4TIMEcWIKUEgKgmE8q8Q2IEWh2/yh7BzEJRAy/ZGhhvIwidIQHYjLolzVRVIAGeQpbVNm3EnWgGQ6EL/bfLUNgUWVsU5IezBdyw2fM/BbBvogMbSl26O8hcEJIUyXlwo6zRhb6bnddf8HVXLMytfvoQsL6sX/MRaIpb6eAzQG8Ri0N7ZFwqYFsEgqeQSmI4ClXXsJpsP4OBncK1tl6f7PquT8/gdF0yENMCiQ4gAAAABJRU5ErkJggg==" alt="cat-icon"></span><span class="editable" data-value="realprof_three">
                                <?=$pagecf['realprof_three']?>
                                </span> </li>
                            </ul>
                            <div class="review">
                              <div class="review-post">
                                <div class="reviewer-thumb"> 
								<? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=14");
$imgclass14=mysql_fetch_assoc($classicimages); 
if($imgclass14['status']==1){
$filepath14="uploads/".$imgclass14[file];
}else{
$filepath14="https://bestwpdevelopers.com/uploads/".$imgclass14[file];
} ?>
                            <img src="<?=$filepath14;?>" onClick="openPopup('img_14'); "> </div>
                            <div id="img_14" class="popup_one" style="display:none;">
                              <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                              <form enctype="multipart/form-data" method="post" id="img_form14">
                                <label for="file-upload14" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                                <input id="file-upload14" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                                <label id="msg14"></label>
                                <input type="hidden" value="14" name="img_id">
                                <input type="hidden" value="" id="image_path14" name="file_name">
                                <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form14',event)" value="Save Image" disabled="disabled">
                              </form>
                            </div>
                                <div class="reviewer-details"> <!-- <h4>Good Restaurant - <span>Thursday December 20, 2018</span></h4> --><span class="editable" data-value="realtext_three">
                                  <?=$pagecf['realtext_three']?>
                                  </span> </div>
                              </div>
                            </div>
                          </div>
                          <div class="lp-grid-box-right pull-right"> </div>
                        </div>
                        <div class="lp-grid-box-bottom">
                          <div class="pull-left">
                            <div class="show"> <span class="cat-icon"><img class="icon icons8-mapMarkerGrey" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAC8UlEQVRoge1aS3HDMBA1BEMIhEAwhEAohEIIgx3tk8+FYAiGEAiBYAjpoZKyTmx9Ym2cQ9+MZ3qIpf2+/bhN849nEFFLQGeMOTMwArgwMDEwub8HtpYI6PaWdRFE1LK15IS+ZT/WEhEd9pZ/UQEAF2PMmYCO+v5IRK17DgBOxpiz885MoX2VAAYh0Eh9f8x+v++PbO2PNAARtZoyrwlxdda8bon52VnAVGKMTSCiVoTGWCPGnXfHoIy2Z2Q4aYSCVwbApea5T3C0+hdOClaT3jbGnGufHy7x7KRZB4joIELsUP2C4A1gqH74AwKbWfujcfiVgVsOqxBRy8zfDAwALu7dITdcnPdvDEybBZcAcMpNQgK6p4InHgCXnNAMlMz8VUOHpmnuYZWyKAGdqPKjqO4HACepYEoZQSz1wktQ7mlVCVlf/nqoRVZzLU0ybJxRbgyMW+UP8ALGWMTlhC+SUWr29SLmYZU8CYkeUyTDax4+52IMqKOIrx8RS+coWyqkz6cXRF5GCK0I9dZWhPr+WL1dCe16hAp9EcsJrZxEVkn2wDTM32u/EZU/ebE3TCzZA3nUpN+cOuKmxWuBgNHGU9SRetOjYJmotUU4zCq4X0rIiTIVgiUsmA2RnMlkfpj4lp4pJVzJfcUQCb+aJxJ+JeSoe2JgNMacc+YYUVzrd9rM/PW2Nr7QaEWQ3K85U88GOK2dl0pr/XiH87zq3B7Cy9qr1h2hg1Y0VtM0d69UpUWHQPNKy40ZfKHScH1Oe18NWhwviqn+gs5DTHnVqPit3vCYeaXCrla0QO/zhofcOm455y3bxZQANepKbjesChkSr7wvq7h63UhBroBK39UgjZchls5FiT+j23d93EkhWDYz8XdP8BhKBJPdwW4JvoZZiEX2umI/PH3s9/bUXvejQ+oRfP+o+bSoEMuH5H54dzzUhjCqijlc55OaBuRaiIBOfjfRmGNUIfMleGjPf9XYApEvNwaGj8+LNQiW+vzk/hT8Ak0IUQVkeTWPAAAAAElFTkSuQmCC" alt="mapMarkerGrey"></span> <a href="https://classic.bestwpdevelopers.com/location/new-york/"> New York </a> </div>
                            <div class="hide"> <span class="cat-icon"> <img class="icon icons8-mapMarkerGrey" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAC8UlEQVRoge1aS3HDMBA1BEMIhEAwhEAohEIIgx3tk8+FYAiGEAiBYAjpoZKyTmx9Ym2cQ9+MZ3qIpf2+/bhN849nEFFLQGeMOTMwArgwMDEwub8HtpYI6PaWdRFE1LK15IS+ZT/WEhEd9pZ/UQEAF2PMmYCO+v5IRK17DgBOxpiz885MoX2VAAYh0Eh9f8x+v++PbO2PNAARtZoyrwlxdda8bon52VnAVGKMTSCiVoTGWCPGnXfHoIy2Z2Q4aYSCVwbApea5T3C0+hdOClaT3jbGnGufHy7x7KRZB4joIELsUP2C4A1gqH74AwKbWfujcfiVgVsOqxBRy8zfDAwALu7dITdcnPdvDEybBZcAcMpNQgK6p4InHgCXnNAMlMz8VUOHpmnuYZWyKAGdqPKjqO4HACepYEoZQSz1wktQ7mlVCVlf/nqoRVZzLU0ybJxRbgyMW+UP8ALGWMTlhC+SUWr29SLmYZU8CYkeUyTDax4+52IMqKOIrx8RS+coWyqkz6cXRF5GCK0I9dZWhPr+WL1dCe16hAp9EcsJrZxEVkn2wDTM32u/EZU/ebE3TCzZA3nUpN+cOuKmxWuBgNHGU9SRetOjYJmotUU4zCq4X0rIiTIVgiUsmA2RnMlkfpj4lp4pJVzJfcUQCb+aJxJ+JeSoe2JgNMacc+YYUVzrd9rM/PW2Nr7QaEWQ3K85U88GOK2dl0pr/XiH87zq3B7Cy9qr1h2hg1Y0VtM0d69UpUWHQPNKy40ZfKHScH1Oe18NWhwviqn+gs5DTHnVqPit3vCYeaXCrla0QO/zhofcOm455y3bxZQANepKbjesChkSr7wvq7h63UhBroBK39UgjZchls5FiT+j23d93EkhWDYz8XdP8BhKBJPdwW4JvoZZiEX2umI/PH3s9/bUXvejQ+oRfP+o+bSoEMuH5H54dzzUhjCqijlc55OaBuRaiIBOfjfRmGNUIfMleGjPf9XYApEvNwaGj8+LNQiW+vzk/hT8Ak0IUQVkeTWPAAAAAElFTkSuQmCC" alt="mapMarkerGrey"> </span> <span class="text gaddress">97 Washington St, New York, NY...</span> </div>
                          </div>
                          <div class="pull-right"> <a class="status-btn"><span class="grid-closed">Closed Now!</span> </a> </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="md-overlay"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="lp_5c332cae16c95" class="lp-section-row   ">
  <div class="lp_section_inner  clearfix " style="background-color: #eff3f6;">
    <div class="clearfix container">
      <div class="row lp-section-content clearfix">
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner">
            <div class="wpb_wrapper">
              <div class="testimonial lp-section-content-container row ">
                <div class="col-md-6 videolink">
                  <div class="video-thumb">
                 <? if($pagecf['embed_link']!=""){              
echo $pagecf['embed_link'];
}else{ ?>
<iframe width="560" height="315" src="<?=$pagecf['embed_link_url']?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>	
	<? } ?>
    </div>
               <? if($_GET['edit_token']!=""){ ?>              
<input type="button" class="btn btn-danger" value="Paste Embed Link" style="display:block;margin:auto;margin-top:20px;background-color:red;" onClick="openPopup('emb_link');">

<br>
<? } ?>     

<div id="emb_link" class="popup_one" style="display:none;height:260px;z-index:0;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="emb_link1">
<label for="embd_link"> Paste the embed code: </label>
                  <textarea class="form-control" id="embd_link" name="embd_link" style="margin: auto;"><? echo $pagecf['embed_link'];?></textarea>
                  <div style="display:block;text-align:center">(OR)</div>
<label for="embed_link_url"> Paste the URL: </label>
 <input type="text" class="form-control" value="<? echo $pagecf['embed_link_url'];?>" id="embed_link_url" name="embed_link_url">
<input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSave('emb_link1',event)" value="Save">
					
                </form>
               
              </div>



                    
                    
                    
                    
                  <!-- ../video-thumb --> </div>
                <div class="col-md-6">
                  <div class="testimonial-inner-box">
                    <h3 class="margin-top-0"> <span class="editable" data-value="mainheading_five">
                      <?=$pagecf['mainheading_five']?>
                      </span></h3>
                    .
                    <div class="testimonial-description lp-border-radius-5"> <span class="editable" data-value="mainheading_fivesub">
                      <p>
                        <?=$pagecf['mainheading_fivesub']?>
                      </p>
                      </span> </div>
                    <!-- ../testimonial-description -->
                    <div class="testimonial-user-info user-info">
                      <div class="testimonial-user-thumb user-thumb">
                      
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=18");
$imgclass18=mysql_fetch_assoc($classicimages); 
if($imgclass18['status']==1){
$filepath18="uploads/".$imgclass18[file];
}else{
$filepath18="https://bestwpdevelopers.com/uploads/".$imgclass18[file];
} ?>
                      <img  src="<?=$filepath18;?>"  alt="blog-grid-1-410x308"onClick="openPopup('img_18'); "></div>
                      <div id="img_18" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form18">
                          <label for="file-upload18" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload18" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg18"></label>
                          <input type="hidden" value="18" name="img_id">
                          <input type="hidden" value="" id="image_path18" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form18',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                      
                      
                                    <div class="testimonial-user-txt user-text">
                        <label class="testimonial-user-name user-name"> 
                        <span class="editable" data-value="mainheading_fiveauth">
                          <?=$pagecf['mainheading_fiveauth']?>
                          </span> </label>
                        <br>
                        <label class="testimonial-user-position user-position"> 
                        <span class="editable" data-value="mainheading_fiveauthadd">
                          <?=$pagecf['mainheading_fiveauthadd']?>
                        </span> </label>
                      </div>
                    </div>
                    <!-- ../testimonial-user-info --> </div>
                  <!-- ../testimonial-inner-box --> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="lp_5c332cae17ad1" class="lp-section-row   ">
  <div class="lp_section_inner  clearfix container">
    <div class="clearfix ">
      <div class="row lp-section-content clearfix">
        <div class="lp-section-title-container text-center ">
          <h2 style="color:#292929"> <span class="editable" data-value="mainheading_six">
            <?=$pagecf['mainheading_six']?>
            </span> </h2>
          <div style="color:" class="lp-sub-title"> <span class="editable" data-value="mainheading_sixsub">
            <?=$pagecf['mainheading_sixsub']?>
            </span> </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12">
          <div class="vc_column-inner">
            <div class="wpb_wrapper last">
              <div class="lp-section-content-container lp-blog-grid-container row">
                <div class="col-md-4 col-sm-4 lp-blog-grid-box">
                  <div class="lp-blog-grid-box-container lp-border lp-border-radius-8">
                    <div class="lp-blog-grid-box-thumb">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=9");
$imgclass9=mysql_fetch_assoc($classicimages); 
if($imgclass9['status']==1){
$filepath9="uploads/".$imgclass9[file];
}else{
$filepath9="https://bestwpdevelopers.com/uploads/".$imgclass9[file];
} ?>
                      <img src="<?=$filepath9;?>" alt="blog-grid-1-410x308"onClick="openPopup('img_9'); ">
                      <div id="img_9" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form9">
                          <label for="file-upload9" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload9" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg9"></label>
                          <input type="hidden" value="9" name="img_id">
                          <input type="hidden" value="" id="image_path9" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form9',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                    </div>
                    <div class="lp-blog-grid-box-description text-center">
                      <div class="lp-blog-user-thumb margin-top-subtract-25"> 
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=15");
$imgclass15=mysql_fetch_assoc($classicimages); 
if($imgclass15['status']==1){
$filepath15="uploads/".$imgclass15[file];
}else{
$filepath15="https://bestwpdevelopers.com/uploads/".$imgclass15[file];
} ?>
                      <img class="avatar" src="<?=$filepath15;?>"  alt="blog-grid-1-410x308"onClick="openPopup('img_15'); "> </div>
                      <div id="img_15" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form15">
                          <label for="file-upload15" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload15" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg15"></label>
                          <input type="hidden" value="15" name="img_id">
                          <input type="hidden" value="" id="image_path15" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form15',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                      
                      <div class="lp-blog-grid-category">
                      <span class="editable" data-value="source_content">
                        <?=$pagecf['source_content']?>
                        </span></div>
                      
                      <!-- ../lp-blog-grid-author --> </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 lp-blog-grid-box">
                  <div class="lp-blog-grid-box-container lp-border lp-border-radius-8">
                    <div class="lp-blog-grid-box-thumb">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=10");
$imgclass10=mysql_fetch_assoc($classicimages); 
if($imgclass10['status']==1){
$filepath10="uploads/".$imgclass10[file];
}else{
$filepath10="https://bestwpdevelopers.com/uploads/".$imgclass10[file];
} ?>
                      <img src="<?=$filepath10;?>" alt="blog-grid-1-410x308" onClick="openPopup('img_10'); ">
                      <div id="img_10" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form10">
                          <label for="file-upload10" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload10" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg10"></label>
                          <input type="hidden" value="10" name="img_id">
                          <input type="hidden" value="" id="image_path10" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form10',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                    </div>
                    <div class="lp-blog-grid-box-description text-center">
                      <div class="lp-blog-user-thumb margin-top-subtract-25">
                      
                       <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=16");
$imgclass16=mysql_fetch_assoc($classicimages); 
if($imgclass16['status']==1){
$filepath16="uploads/".$imgclass16[file];
}else{
$filepath16="https://bestwpdevelopers.com/uploads/".$imgclass16[file];
} ?>
                       <img class="avatar" src="<?=$filepath16;?>" alt="blog-grid-1-410x308"onClick="openPopup('img_16'); "> </div>
                       
                       <div id="img_16" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form16">
                          <label for="file-upload16" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload16" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg16"></label>
                          <input type="hidden" value="16" name="img_id">
                          <input type="hidden" value="" id="image_path16" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form16',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                       
                      <div class="lp-blog-grid-category"> <span class="editable" data-value="smile_content">
                        <?=$pagecf['smile_content']?>
                        </span></div>
                      
                      <!-- ../lp-blog-grid-author --> </div>
                  </div>
                </div>
                <div class="col-md-4 col-sm-4 lp-blog-grid-box">
                  <div class="lp-blog-grid-box-container lp-border lp-border-radius-8">
                    <div class="lp-blog-grid-box-thumb">
                      <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=11");
$imgclass11=mysql_fetch_assoc($classicimages); 
if($imgclass11['status']==1){
$filepath11="uploads/".$imgclass11[file];
}else{
$filepath11="https://bestwpdevelopers.com/uploads/".$imgclass11[file];
} ?>
                      <img src="<?=$filepath11;?>" alt="blog-grid-1-410x308"onClick="openPopup('img_11'); "> </div>
                    <div id="img_11" class="popup_one" style="display:none;">
                      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                      <form enctype="multipart/form-data" method="post" id="img_form11">
                        <label for="file-upload11" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                        <input id="file-upload11" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                        <label id="msg11"></label>
                        <input type="hidden" value="11" name="img_id">
                        <input type="hidden" value="" id="image_path11" name="file_name">
                        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form11',event)" value="Save Image" disabled="disabled">
                      </form>
                    </div>
                    <div class="lp-blog-grid-box-description text-center">
                      <div class="lp-blog-user-thumb margin-top-subtract-25"> 
                      
                       <? $classicimages=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_images` where id=17");
$imgclass17=mysql_fetch_assoc($classicimages);
 if($imgclass17['status']==1){
$filepath17="uploads/".$imgclass17[file];
}else{
$filepath17="https://bestwpdevelopers.com/uploads/".$imgclass17[file];
} ?>
                      
                      <img class="avatar" src="<?=$filepath17;?>" alt="blog-grid-1-410x308"onClick="openPopup('img_17'); "> </div>
                      
                      
                      <div id="img_17" class="popup_one" style="display:none;">
                        <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                        <form enctype="multipart/form-data" method="post" id="img_form17">
                          <label for="file-upload17" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                          <input id="file-upload17" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                          <label id="msg17"></label>
                          <input type="hidden" value="17" name="img_id">
                          <input type="hidden" value="" id="image_path17" name="file_name">
                          <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form17',event)" value="Save Image" disabled="disabled">
                        </form>
                      </div>
                      <div class="lp-blog-grid-category"> <span class="editable" data-value="bike_content">
                        <?=$pagecf['bike_content']?>
                        </span> </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
