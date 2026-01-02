<div class="cllist">
  <ul class="lp-home-categoires padding-left-0 new-banner-category-view4">
    <li onClick="openPopup('list_1');">
      <?php
$listdata=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=1");
$content1=mysql_fetch_assoc($listdata);?>
      <div class="ref"> <span> <i class="fa fa-bandcamp" aria-hidden="true"></i> <br>
        <?=$content1['list_content'];?>
        </span> </div>
    </li>
    <div id="list_1" class="popup_one" style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list1">
        <input type="text" value="<?=$content1['list_content'];?>"   name="content_data" >
      
        <input type="hidden" value="101" name="list_id">
        <input type="hidden" value="list1" id="list1" name="list_name">
        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list1',event)" value="save">
      </form>
    </div>
    <li  onClick="openPopup('list_2');">
      <div class="ref">
        <?php
$listdata2=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=2");
$content2=mysql_fetch_assoc($listdata2);?>
        <span><i class="fa fa-bandcamp  " aria-hidden="true"></i> <br>
        <?=$content2['list_content'];?>
        </span></div>
    </li>
    <div id="list_2" class="popup_one" style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list2">
        <input type="text" value="<?=$content2['list_content'];?>"  name="content_data" >
       
        <input type="hidden" value="102" name="list_id">
        <input type="hidden" value="list2" id="list2" name="list_name">
        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list2',event)" value="save">
      </form>
    </div>
    <li  onClick="openPopup('list_3');">
      <?php
$listdata3=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=3");
$content3=mysql_fetch_assoc($listdata3);?>
      <div class="ref"><span><i class="fa fa-bandcamp  " aria-hidden="true"></i> <br>
        <?=$content3['list_content'];?>
        </span></div>
    </li>
    <div id="list_3" class="popup_one" style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list3">
        <input type="text"  value="<?=$content3['list_content'];?>"   name="content_data" >
        
        <input type="hidden" value="103" name="list_id">
        <input type="hidden" value="list3" id="list3" name="list_name">
        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list3',event)" value="save">
      </form>
    </div>
    <li  onClick="openPopup('list_4');">
      <?php
$listdata4=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=4");
$content4=mysql_fetch_assoc($listdata4);?>
      <div class="ref"> <span><i class="fa fa-bandcamp  " aria-hidden="true"></i> <br>
        <?=$content4['list_content'];?>
        </span> </div>
    </li>
    <div id="list_4" class="popup_one" style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list4">
        <input type="text"  value="<?=$content4['list_content'];?>"   name="content_data" >
        
        <input type="hidden" value="104" name="list_id">
        <input type="hidden" value="list4" id="list4" name="list_name">
        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list4',event)" value="save">
      </form>
    </div>
    <li  onClick="openPopup('list_5');">
      <?php
$listdata5=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classicpage_list` where `id`=5");
$content5=mysql_fetch_assoc($listdata5);?>
      <div class="ref"><span><i class="fa fa-bandcamp  " aria-hidden="true"></i> <br>
        <?=$content5['list_content'];?>
        </span></div>
    </li>
    <div id="list_5" class="popup_one" style="display:none;">
      <div class="cancel_one" onClick="closePopup();">&nbsp;X&nbsp;</div>
      <form enctype="multipart/form-data" method="post" id="li_list5">
        <input type="text" value=" <?=$content5['list_content'];?>" name="content_data" >
       
        <input type="hidden" value="105" name="list_id">
        <input type="hidden" value="list5" id="list5" name="list_name">
        <input type="submit" style="margin-top:10px;"  class="btn btn-warning" onClick="onSubmitcode('li_list5',event)" value="save">
      </form>
    </div>
  </ul>
</div>
