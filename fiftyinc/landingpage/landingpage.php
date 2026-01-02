<link rel='stylesheet' id='layers-responsive-css'  href='https://bestwpdevelopers.com/landingpage/css/responsive.css' type='text/css' media='all' />
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
$class_one="bd-template";
?>
  <div class="alert alert-warning" style="margin: auto;text-align: center;width: 50%;margin-top: 20px;margin-bottom: 20px;">
  <strong>Warning!</strong> Your page got deactivated. Please contact Admin
</div>  
 <?   
}
$sqlbwp= "SELECT * FROM landingpages where id=2 AND status=1";							
$resultbwp = mysqli_query($conn, $sqlbwp);
$rowbwp=mysqli_num_rows($resultbwp);	
if($rowbwp==0){
$class_one="bd-template";
?>
  <div class="alert alert-warning" style="margin: auto;text-align: center;width: 50%;margin-top: 20px;margin-bottom: 20px;">
  <strong>Warning!</strong> This page got deactivated. Please contact Admin
</div>  
 <?   
}
}
}
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"UPDATE `purchased_landingpages` SET `lpage_path='".$pars[0]."' WHERE lp_id=2");
 $pageld=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landing_page_fields` WHERE id=39"); 
$pagesd=mysql_fetch_assoc($pageld);
?>


<div id="wrapper-content"  class="wrapper-content <?=$class_one?>"> 
  <!--section 1 -->
  <div id="layers-widget-column-82" class="widget layers-content-widget content-vertical-massive layers-parallax">
    <div class="container clearfix">
      <div class="section-title clearfix medium text-center invert">
        <h3 class="heading" ><span class="editable" data-value="main_head">
          <?=$pagesd['main_head']?>
          </span> </h3>
      </div>
    </div>
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-82-840" class="layers-widget-column-82-840 layers-masonry-column layers-widget-column-840 span-12  last  column ">
          <div class="media invert image-top medium">
            <div class="media-body text-center">
              <h1 class="heading"> <span class="editable" data-value="heading">
                <?=$pagesd['heading']?>
                </span></h1>
              <div class="excerpt">
                <span class="editable" data-value="subtitle">
                  <?=$pagesd['subtitle']?>
                  </span>
                <p><br>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-82-982" class="layers-widget-column-82-982 layers-masonry-column layers-widget-column-982 span-12  last  column  has-image">
          <div class="media invert image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=1");
$imgdata1=mysql_fetch_assoc($imgup);
if($imgdata1['status']==1){
$imagepath1="uploads/".$imgdata1[file];
}else{
$imagepath1="https://bestwpdevelopers.com/uploads/".$imgdata1[file];
} ?>
              <img data_value='1' width="652" height="358" src="<?=$imagepath1?>" class="attachment-layers-landscape-large size-layers-landscape-large jetpack-lazy-image jetpack-lazy-image--handled" alt="" data-lazy-loaded="1"   onClick="openPopup('img_1');">
              <div id="img_1" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form1">
                  <label for="file-upload1" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i>  Upload Image  </label>
                  <input id="file-upload1" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg1"></label>
                  <input type="hidden" value="1" name="img_id">
                  <input type="hidden" value="" id="image_path1" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form1',event)" value="Save Image" disabled="disabled">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-82-596" class="layers-widget-column-82-596 layers-masonry-column layers-widget-column-596 span-12  last  column  has-image">
          <div class="media invert image-top medium">
            <div class="media-image">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=2");
$imgdata2=mysql_fetch_assoc($imgup);
if($imgdata2['status']==1){
$imagepath2="uploads/".$imgdata2[file];
}else{
$imagepath2="https://bestwpdevelopers.com/uploads/".$imgdata2[file];
} ?>
              <img data_value='2' width="651" height="51" src="<?=$imagepath2;?>" class="attachment-full size-full jetpack-lazy-image jetpack-lazy-image--handled" alt=""  data-lazy-loaded="1"  onClick="openPopup('img_2');">
              <div id="img_2" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form2">
                  <label for="file-upload2" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload image</label>
                  <input id="file-upload2" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg2"></label>
                  <input type="hidden" value="2" name="img_id" >
                  <input type="hidden" value="" id="image_path2" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form2',event)" value="Save Image" disabled="disabled">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <!--section2 -->
  <div id="layers-widget-column-83" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-83-885" class="layers-widget-column-83-885 layers-masonry-column layers-widget-column-885 span-12  last  column ">
          <div class="media image-top medium">
            <div class="media-body text-center">
              <h5 class="heading"><span class="editable" data-value="heading_2">
                <?=$pagesd['heading_2']?>
                </span></h5>
              <div class="excerpt">
                <p>
                  <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=3");
$imgdata3=mysql_fetch_assoc($imgup);
if($imgdata3['status']==1){
$imagepath3="uploads/".$imgdata3[file];
}else{
$imagepath3="https://bestwpdevelopers.com/uploads/".$imgdata3[file];
} ?>
                  <img data_value='3' src="<?=$imagepath3;?>"  scale="0" data-recalc-dims="1" class=" jetpack-lazy-image"   onClick="openPopup('img_3');">
                <div id="img_3" class="popup_one" style="display:none;">
                  <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
                 
                  <form enctype="multipart/form-data" method="post" id="img_form3">
                    <label for="file-upload3" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i>Upload Image</label>
                    <input id="file-upload3" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                    <label id="msg3"></label>
                    <input type="hidden" value="3" name="img_id">
                    <input type="hidden" value="" id="image_path3" name="file_name">
                    <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form3',event)" value="Save Image" disabled="disabled">
                  </form>
                </div>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-83-598" class="layers-widget-column-83-598 layers-masonry-column layers-widget-column-598 span-2  first  column ">
          <div class="media image-top medium"> </div>
        </div>
        <div id="layers-widget-column-83-906" class="layers-widget-column-83-906 layers-masonry-column layers-widget-column-906 span-8    column ">
          <div class="media image-top medium">
            <div class="media-body text-center">
              <div class="excerpt"> 
<? if($pagesd['embed_link']!=""){              
echo $pagesd['embed_link'];
}else{ ?>
<iframe width="560" height="315" src="<?=$pagesd['embed_link_url']?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>	
	<? } ?>
              </div>
<? if($_GET['edit_token']!=""){ ?>              
<input type="button" class="btn btn-danger" value="Paste Embed Link" style="display:block;margin:auto;margin-top:20px;background-color:red;" onClick="openPopup('emb_link');">
<br>
<? } ?>
<div id="emb_link" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="emb_link1">
<label for="embd_link"> Paste the embed code: </label>
                  <textarea class="form-control" name="embd_link" style="margin: auto;"><? echo $pagesd['embed_link'];?></textarea>
                  <div style="display:block;text-align:center">(OR)</div>
<label for="embed_link_url"> Paste the URL: </label>
 <input type="text" class="form-control" value="<? echo $pagesd['embed_link_url'];?>" name="embed_link_url">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('emb_link1',event)" value="Save">
					
                </form>
               
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-83-661" class="layers-widget-column-83-661 layers-masonry-column layers-widget-column-661 span-2  last  column ">
          <div class="media image-top medium"> </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <!--section 3 -->
  <div id="layers-widget-column-85" class="widget layers-content-widget content-vertical-massive">
    <div class="container clearfix">
      <div class="section-title clearfix medium text-center ">
        <h3 class="heading"><span class="editable" data-value="heading_3">
          <?=$pagesd['heading_3']?>
          </span> </h3>
        <div class="excerpt text-center">
          <section>
            <article>
              <p> <span class="editable" data-value="heading_3_sub">
                <?=$pagesd['heading_3_sub']?>
                </span></p>
            </article>
          </section>
        </div>
      </div>
    </div>
    <div class="full-width list-grid">
      <div class="grid ">
        <div id="layers-widget-column-85-300" class="layers-widget-column-85-300 layers-masonry-column layers-widget-column-300 span-4  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image "> 
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=4");
$imgdata4=mysql_fetch_assoc($imgup);
if($imgdata4['status']==1){
$imagepath4="uploads/".$imgdata4[file];
}else{
$imagepath4="https://bestwpdevelopers.com/uploads/".$imgdata4[file];
}  ?>
              <img data_value='4' src="<?=$imagepath4;?>"  class="attachment-full size-full jetpack-lazy-image" alt=""   onClick="openPopup('img_4');">
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
            <div class="media-body text-center">
              <div class="excerpt ">
               <span class="editable" data-value="classic">
                <?=$pagesd['classic']?>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-85-876" class="layers-widget-column-85-876 layers-masonry-column layers-widget-column-876 span-4    column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=5");
$imgdata5=mysql_fetch_assoc($imgup);
if($imgdata5['status']==1){
$imagepath5="uploads/".$imgdata5[file];
}else{
$imagepath5="https://bestwpdevelopers.com/uploads/".$imgdata5[file];
}  ?>
              <img data_value='5' src="<?=$imagepath5;?>"class="attachment-full size-full jetpack-lazy-image" alt=""  onClick="openPopup('img_5');">
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
            <div class="media-body text-center">
              <div class="excerpt tmargin">
              
              <span class="editable" data-value="restaurantpro">
                <?=$pagesd['restaurantpro']?>
                </span>
               
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-85-354" class="layers-widget-column-85-354 layers-masonry-column layers-widget-column-354 span-4  last  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=6");
$imgdata6=mysql_fetch_assoc($imgup);
if($imgdata6['status']==1){
$imagepath6="uploads/".$imgdata6[file];
}else{
$imagepath6="https://bestwpdevelopers.com/uploads/".$imgdata6[file];
} ?>
              <img data_value='6' src="<?=$imagepath6;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  onClick="openPopup('img_6');">
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
            <div class="media-body text-center">
              <div class="excerpt ">
                <span class="editable" data-value="placespro">
                <?=$pagesd['placespro']?>
                </span>
               
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <!--section 4-->
  <div id="layers-widget-column-86" class="widget layers-content-widget content-vertical-massive tmargin    ">
    <div class="container clearfix">
      <div class="section-title clearfix medium text-center ">
        <h2 class="heading ptop tmargin"><span class="editable" data-value="heading_4">
          <?=$pagesd['heading_4']?>
          </span> </h2>
        <div class="excerpt">
          <p style=""><span class="editable" data-value="heading_4_sub">
            <?=$pagesd['heading_4_sub']?>
            </span></p>
        </div>
      </div>
    </div>
  </div>
  <div id="layers-widget-column-95" class="widget layers-content-widget content-vertical-massive    ">
    <div class="full-width list-grid">
      <div class="grid ">
        <div id="layers-widget-column-95-910" class="layers-widget-column-95-910 layers-masonry-column layers-widget-column-910 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=7");
$imgdata7=mysql_fetch_assoc($imgup);
if($imgdata7['status']==1){
$imagepath7="uploads/".$imgdata7[file];
}else{
$imagepath7="https://bestwpdevelopers.com/uploads/".$imgdata7[file];
} ?>
              <img data_value='7' src="<?=$imagepath7;?>" class="attachment-layers--large size-layers--large jetpack-lazy-image" alt=""  onClick="openPopup('img_7');">
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
          </div>
        </div>
        <div id="layers-widget-column-95-348" class="layers-widget-column-95-348 layers-masonry-column layers-widget-column-348 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
             <span class="editable" data-value="heading_4_one">
                <?=$pagesd['heading_4_one']?>
                </span> 
              
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-99" class="widget layers-content-widget content-vertical-massive    ">
    <div class="full-width list-grid">
      <div class="grid ">
        <div id="layers-widget-column-99-993" class="layers-widget-column-99-993 layers-masonry-column layers-widget-column-993 span-6  first  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_4_two">
                <?=$pagesd['heading_4_two']?>
                </span> 
              
            </div>
          </div>
        </div>
        <div id="layers-widget-column-99-619" class="layers-widget-column-99-619 layers-masonry-column layers-widget-column-619 span-6  last  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=10");
$imgdata10=mysql_fetch_assoc($imgup);
if($imgdata10['status']==1){
$imagepath10="uploads/".$imgdata10[file];
}else{
$imagepath10="https://bestwpdevelopers.com/uploads/".$imgdata10[file];
} ?>
              <img data_value='10'  src="<?=$imagepath10;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  data-lazy-sizes="(max-width: 1166px) 100vw, 1166px"   onClick="openPopup('img_10');">
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
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <!--section 5 -->
  <div id="layers-widget-column-100" class="widget layers-content-widget content-vertical-massive darken    layers-parallax" >
    <div class="full-width list-grid">
      <div class="grid">
        <div id="layers-widget-column-100-993" class="layers-widget-column-100-993 layers-masonry-column layers-widget-column-993 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=11");
$imgdata11=mysql_fetch_assoc($imgup);
if($imgdata11['status']==1){
$imagepath11="uploads/".$imgdata11[file];
}else{
$imagepath11="https://bestwpdevelopers.com/uploads/".$imgdata11[file];
} ?>
              <img data_value='11'width="1309" height="864"   src="<?=$imagepath11;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  data-lazy-sizes="(max-width: 1309px) 100vw, 1309px"   onClick="openPopup('img_11');">
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
            </div>
          </div>
        </div>
        <div id="layers-widget-column-100-380" class="layers-widget-column-100-380 layers-masonry-column layers-widget-column-380 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_5">
                <?=$pagesd['heading_5']?>
                </span> 
              
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
    <!--section6--> 
  </div>
  <div id="layers-widget-column-96" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-96-348" class="layers-widget-column-96-348 layers-masonry-column layers-widget-column-348 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=13");
$imgdata13=mysql_fetch_assoc($imgup);
if($imgdata13['status']==1){
$imagepath13="uploads/".$imgdata13[file];
}else{
$imagepath13="https://bestwpdevelopers.com/uploads/".$imgdata13[file];
} ?>
              <img data_value='13'width="1166" height="864"   src="<?=$imagepath13;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  onClick="openPopup('img_13');">
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
            </div>
          </div>
        </div>
        <div id="layers-widget-column-96-910" class="layers-widget-column-96-910 layers-masonry-column layers-widget-column-910 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_6_one">
                <?=$pagesd['heading_6_one']?>
                </span> 
              
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-106" class="widget layers-content-widget content-vertical-massive ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-106-295" class="layers-widget-column-106-295 layers-masonry-column layers-widget-column-295 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=14");
$imgdata14=mysql_fetch_assoc($imgup);
if($imgdata14['status']==1){
$imagepath14="uploads/".$imgdata14[file];
}else{
$imagepath14="https://bestwpdevelopers.com/uploads/".$imgdata14[file];
} ?>
              <img data_value='14' width="1149" height="864" src="<?=$imagepath14;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  onClick="openPopup('img_14');">
              <div id="img_14" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form1">
                  <label for="file-upload14" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                  <input id="file-upload14" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg14"></label>
                  <input type="hidden" value="14" name="img_id">
                  <input type="hidden" value="" id="image_path14" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form14',event)" value="Save Image" disabled="disabled">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-106-435" class="layers-widget-column-106-435 layers-masonry-column layers-widget-column-435 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
           <span class="editable" data-value="heading_6_two">
                <?=$pagesd['heading_6_two']?>
                </span>
              
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-108" class="widget layers-content-widget content-vertical-massive ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-108-543" class="layers-widget-column-108-543 layers-masonry-column layers-widget-column-543 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=15");
$imgdata15=mysql_fetch_assoc($imgup);
if($imgdata15['status']==1){
$imagepath15="uploads/".$imgdata15[file];
}else{
$imagepath15="https://bestwpdevelopers.com/uploads/".$imgdata15[file];
} ?>
              <img data_value='15' width="500" height="370" src="<?=$imagepath15;?>" class="attachment-full size-full jetpack-lazy-image" alt="" data-lazy-sizes="(max-width: 500px) 100vw, 500px"  onClick="openPopup('img_15');">
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
            </div>
          </div>
        </div>
        <div id="layers-widget-column-108-248" class="layers-widget-column-108-248 layers-masonry-column layers-widget-column-248 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_6_three">
                <?=$pagesd['heading_6_three']?>
                </span> 
              
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-87" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-87-43" class="layers-widget-column-87-43 layers-masonry-column layers-widget-column-43 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=16");
$imgdata16=mysql_fetch_assoc($imgup);
if($imgdata16['status']==1){
$imagepath16="uploads/".$imgdata16[file];
}else{
$imagepath16="https://bestwpdevelopers.com/uploads/".$imgdata16[file];
} ?>
              <img  data_value='16' width="1166" height="864" src="<?=$imagepath16;?>"class="attachment-full size-full jetpack-lazy-image" alt=""  onClick="openPopup('img_16');">
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

            </div>
          </div>
        </div>
        <div id="layers-widget-column-87-162" class="layers-widget-column-87-162 layers-masonry-column layers-widget-column-162 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_6_four">
                <?=$pagesd['heading_6_four']?>
                </span> 
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-109" class="widget layers-content-widget content-vertical-massive">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-109-543" class="layers-widget-column-109-543 layers-masonry-column layers-widget-column-543 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=17");
$imgdata17=mysql_fetch_assoc($imgup);
if($imgdata17['status']==1){
$imagepath17="uploads/".$imgdata17[file];
}else{
$imagepath17="https://bestwpdevelopers.com/uploads/".$imgdata17[file];
}  ?>
              <img data_value='17' width="500" height="370" src="<?=$imagepath17;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  onClick="openPopup('img_17');">
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
            </div>
          </div>
        </div>
        <div id="layers-widget-column-109-248" class="layers-widget-column-109-248 layers-masonry-column layers-widget-column-248 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_6_five">
                <?=$pagesd['heading_6_five']?>
                </span> 
                
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-101" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-101-993" class="layers-widget-column-101-993 layers-masonry-column layers-widget-column-993 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=18");
$imgdata18=mysql_fetch_assoc($imgup);
if($imgdata18['status']==1){
$imagepath18="uploads/".$imgdata18[file];
}else{
$imagepath18="https://bestwpdevelopers.com/uploads/".$imgdata18[file];
} ?>
              <img  data_value='18' width="1166" height="864" src="<?=$imagepath18;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  data-lazy-sizes="(max-width: 1166px) 100vw, 1166px"  
           onClick="openPopup('img_18');">
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
            </div>
          </div>
        </div>
        <div id="layers-widget-column-101-380" class="layers-widget-column-101-380 layers-masonry-column layers-widget-column-380 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
             <span class="editable" data-value="heading_6_six">
                <?=$pagesd['heading_6_six']?>
                </span> 
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-102" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-102-993" class="layers-widget-column-102-993 layers-masonry-column layers-widget-column-993 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=19");
$imgdata19=mysql_fetch_assoc($imgup);
if($imgdata19['status']==1){
$imagepath19="uploads/".$imgdata19[file];
}else{
$imagepath19="https://bestwpdevelopers.com/uploads/".$imgdata19[file];
}  ?>
              <img data_value='19' width="540" height="400" src="<?=$imagepath19;?>" class="attachment-full size-full jetpack-lazy-image" alt=""  data-lazy-sizes="(max-width: 540px) 100vw, 540px"  onClick="openPopup('img_19');">
              <div id="img_19" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form19">
                  <label for="file-upload19" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                  <input id="file-upload19" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg19"></label>
                  <input type="hidden" value="19" name="img_id">
                  <input type="hidden" value="" id="image_path19" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form19',event)" value="Save Image" disabled="disabled">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-102-380" class="layers-widget-column-102-380 layers-masonry-column layers-widget-column-380 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_6_seven">
                <?=$pagesd['heading_6_seven']?>
                </span> 
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <div id="layers-widget-column-88" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container clearfix">
      <div class="section-title clearfix medium text-left ">
        <h3 class="heading"> Checkout </h3>
      </div>
    </div>
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-88-162" class="layers-widget-column-88-162 layers-masonry-column layers-widget-column-162 span-6  first  column  has-image">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=20");
$imgdata20=mysql_fetch_assoc($imgup);
if($imgdata20['status']==1){
$imagepath20="uploads/".$imgdata20[file];
}else{
$imagepath20="https://bestwpdevelopers.com/uploads/".$imgdata20[file];
} ?>
              <img  data_value='20' width="500" height="370" src="<?=$imagepath20;?>"class="attachment-full size-full jetpack-lazy-image" alt=""  data-lazy-sizes="(max-width: 500px) 100vw, 500px"  onClick="openPopup('img_20');">
              <div id="img_20" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form20">
                  <label for="file-upload20" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                  <input id="file-upload20" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg20"></label>
                  <input type="hidden" value="20" name="img_id">
                  <input type="hidden" value="" id="image_path20" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form20',event)" value="Save Image" disabled="disabled">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-88-43" class="layers-widget-column-88-43 layers-masonry-column layers-widget-column-43 span-6  last  column ">
          <div class="media image-top medium">
            <div class="media-body excerpt">
              <span class="editable" data-value="heading_6_eight">
                <?=$pagesd['heading_6_eight']?>
                </span> 
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  
  <!--section 7-->
  
  <div id="layers-widget-column-134" class="widget layers-content-widget content-vertical-massive">
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-134-186" class="layers-widget-column-134-186 layers-masonry-column layers-widget-column-186 span-12  last  column  content" onClick="openPopup('img_22');">
          <div class="media invert image-top medium"> </div>
        </div>
        <div id="img_22" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form22">
                  <label for="file-upload22" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                  <input id="file-upload22" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg20"></label>
                  <input type="hidden" value="22" name="img_id">
                  <input type="hidden" value="" id="image_path22" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form22',event)" value="Save Image" disabled="disabled">
                </form>
              </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <a name="pricing"></a>
  <div id="layers-widget-column-90" class="widget layers-content-widget content-vertical-massive    ">
    <div class="container clearfix">
      <div class="section-title clearfix medium text-center invert">
        <h3 class="heading"><span class="editable" data-value="pricing_head">
          <?=$pagesd['pricing_head']?>
          </span></h3>
        <div class="excerpt">
         <span style="background-color: initial; text-align: initial;text-align:center; font-family: -apple-system, BlinkMacSystemFont, "><span class="editable" data-value="pricing_sub">
            <?=$pagesd['pricing_sub']?>
            </span></span>
        </div>
      </div>
    </div>

    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-90-291" class="layers-widget-column-90-291 layers-masonry-column layers-widget-column-291 span-4  first  column ">
          <div class="media invert image-top medium"> </div>
        </div>
        <div id="layers-widget-column-90-146" class="layers-widget-column-90-146 layers-masonry-column layers-widget-column-146 span-4    column  content">
          <div class="media image-top medium">
            <div class="media-body text-center">
              <div class="heading excerpt"> <span class="editable" data-value="pricing_cost">
                <?=$pagesd['pricing_cost']?>
                </span></div>
              
              <a href="  " class="button " target="_blank"> CLICK TO BUY </a> </div>
          </div>
        </div>
        <div id="layers-widget-column-90-550" class="layers-widget-column-90-550 layers-masonry-column layers-widget-column-550 span-4  last  column ">
          <div class="media invert image-top medium"> </div>
        </div>
        <div id="layers-widget-column-90-315" class="layers-widget-column-90-315 layers-masonry-column layers-widget-column-315 span-12  last  column ">
          <div class="media invert image-top medium">
            <div class="media-body text-center">
              <div class="excerpt">
                <p><em>*<span class="editable" data-value="pricing_tag">
                  <?=$pagesd['pricing_tag']?>

                  </span></em></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
  </div>
  <!--section 9-->
  
  <div id="layers-widget-column-91" class="widget layers-content-widget content-vertical-massive     hide-phone">
    <div class="container clearfix">
      <div class="section-title clearfix medium text-center ">
        <h3 class="heading"><span class="editable" data-value="heading_9">
          <?=$pagesd['heading_9']?>
          </span> </h3>
      </div>
    </div>
    <div class="container list-grid">
      <div class="grid">
        <div id="layers-widget-column-91-980" class="layers-widget-column-91-980 layers-masonry-column layers-widget-column-980 span-12  last  column ">
          <div class="media image-top medium">
            <div class="media-body text-center">
              <h5 class="heading"><span class="editable" data-value="heading_quote">"
                <?=$pagesd['heading_quote']?>
                "</span> </h5>
              <div class="excerpt">
                <strong><span class="editable" data-value="footer_auth">
                  <?=$pagesd['footer_auth']?>
                  </span>
                  </strong><em><span class="editable" data-value="footer_authcompany">
                  <?=$pagesd['footer_authcompany']?>
                  </span>&nbsp;</em>
              </div>
            </div>
          </div>
        </div>
        <div id="layers-widget-column-91-170" class="layers-widget-column-91-170 layers-masonry-column layers-widget-column-170 span-12  last  column  has-image" style="margin-bottom: 0px;">
          <div class="media image-top medium">
            <div class="media-image ">
              <? $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=21");
$imgdata21=mysql_fetch_assoc($imgup);
if($imgdata21['status']==1){
$imagepath21="uploads/".$imgdata21[file];
}else{
$imagepath21="https://bestwpdevelopers.com/uploads/".$imgdata21[file];
} ?>
              <img  data_value='21' width="497" height="429" src="<?=$imagepath21;?>" class="attachment-layers--large size-layers--large jetpack-lazy-image" alt="" onClick="openPopup('img_21'); on();">
              
            
              <div id="img_21" class="popup_one" style="display:none;">
                <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
               
                <form enctype="multipart/form-data" method="post" id="img_form21">
                  <label for="file-upload21" class="custom-file-upload" > <i class="fa fa-cloud-upload"></i> Upload Image </label>
                  <input id="file-upload21" class="filestyle_uploadpdf" name='upload_coverone' type="file" style="display:none;" accept="image/x-png,image/gif,image/jpeg" >
                  <label id="msg21"></label>
                  <input type="hidden" value="21" name="img_id">
                  <input type="hidden" value="" id="image_path21" name="file_name">
                  <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmit('img_form21',event)" value="Save Image" disabled="disabled">
					
                </form>
               
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /row --> 
    </div>
