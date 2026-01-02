<?php
                    if(isset($_POST['btn_belowtitle'])){
						mysql($w['database'],"update `search_page_content` set `message`='$_POST[belowtitle_message]' where `id`=1");
						//echo "insert into `search_page_content` set `message`='$_POST[belowtitle_message]',`id`=1";
						header("LOCATION: https://www.managemydirectory.com/admin/go.php?widget=Search Results Content");
					}
  if(isset($_POST['btn_belowsearch'])){
						mysql($w['database'],"update `search_page_content` set `message`='$_POST[belowsearch_messgae]' where `id`=2");
						//echo "insert into `search_page_content` set `message`='$_POST[belowtitle_message]',`id`=1";
						header("LOCATION: https://www.managemydirectory.com/admin/go.php?widget=Search Results Content&belowsearch=1");
					}
					 if(isset($_POST['btn_abovefooter'])){
						mysql($w['database'],"update `search_page_content` set `message`='$_POST[abovefooter_message]' where `id`=3");
						//echo "insert into `search_page_content` set `message`='$_POST[belowtitle_message]',`id`=1";
						header("LOCATION: https://www.managemydirectory.com/admin/go.php?widget=Search Results Content&abovefooter=1");
					}
					 if(isset($_POST['btn_readmore'])){
						mysql($w['database'],"update `search_page_content` set `message`='$_POST[readmore_message]' where `id`=4");
						//echo "insert into `search_page_content` set `message`='$_POST[belowtitle_message]',`id`=1";
						header("LOCATION: https://www.managemydirectory.com/admin/go.php?widget=Search Results Content&readmore=1");
					}

						
						?>
<div class="blabsorg">
    <div class="row">
        <div class="col-md-12 col-sm-12 text-center">
            <h1 style="font-size: 35px;">Search Results content</h1>
        </div>
        <div role="tabpanel">
            <ul role="tablist" class="nav nav-tabs">
                <li class="<?php if ($_GET['widget'] == "Search Results Content"&& $_GET['belowsearch'] == ""&& $_GET['abovefooter'] == ""&& $_GET['readmore'] == "") { ?>active<? } ?>">
                    <a href="/admin/go.php?widget=Search Results Content" style="font-size:14px;">Below Title</a>
                </li>
                <li class="<?php if ($_GET['belowsearch'] == "1") { ?>active<? } ?>">
                    <a href="/admin/go.php?widget=Search Results Content&belowsearch=1" style="font-size:14px;">Below Search Results</a>
                </li>
                <li class="<? if ($_GET['abovefooter'] == "1") { ?>active<? } ?>">
                    <a href="/admin/go.php?widget=Search Results Content&abovefooter=1" style="font-size:14px;">Above Footer</a>
                </li>
                <li class="<? if ($_GET['readmore'] == "1") { ?>active<? } ?>">
                    <a href="/admin/go.php?widget=Search Results Content&readmore=1" style="font-size:14px;">Under Read More Tab</a>
                </li>
              
            </ul>
        </div>
		<?php if ($_GET['widget'] == "Search Results Content"&& $_GET['belowsearch'] == ""&& $_GET['abovefooter'] == ""&& $_GET['readmore'] == "") {?>
 <div class="col-md-offset-2 col-md-8 col-sm-12">
<h2>Below Title</h2>
	 
		  <form role="form" method="post"  action="" enctype="multipart/form-data">

	
		<div class="form-group">
			<? $row=mysql($w['database'],"select * from `search_page_content` where `id`=1");
					$row_data=mysql_fetch_assoc($row);?>
                                    <label class="na">Content:</label>
			
                                    <textarea name='belowtitle_message' id="belowtitle_message" class="form-control froala-editor-admin"><?=$row_data[message]?></textarea>
                               </div> 
		 <div style="text-align: center;">
	 <input type="submit" name="btn_belowtitle" class="btn btn-success btn_belowtitle text-center" value="Save">
			 </div>
	 </form>
	 </div>
	<?
}?>
		<?php if ($_GET['widget'] == "Search Results Content"&& $_GET['belowsearch'] == "1") {?>
 <div class="col-md-12">
<h2>Below Search Content</h2>
	 <form role="form" method="post"  action="" enctype="multipart/form-data">

	
		<div class="form-group">
			<? $row=mysql($w['database'],"select * from `search_page_content` where `id`=2");
					$row_data=mysql_fetch_assoc($row);?>																		  
                                    <label class="na">Content:</label>
                                    <textarea name='belowsearch_messgae' id="belowsearch_messgae" class="form-control froala-editor-admin"><?=$row_data[message]?></textarea>
                               </div> 
		 
		 <div style="text-align: center;">
	 <input type="submit" name="btn_belowsearch" class="btn btn-success text-center" value="Save">
			 </div>
	 </form>
	 </div>
	<?
}?>
		<?php if ($_GET['widget'] == "Search Results Content"&&$_GET['abovefooter'] == "1") {?>
 <div class="col-md-12">
<h2>Above Footer Content</h2>
	 <form role="form" method="post"  action="" enctype="multipart/form-data">

	
		<div class="form-group">
			<? $row=mysql($w['database'],"select * from `search_page_content` where `id`=3");
					$row_data=mysql_fetch_assoc($row);?>
                                    <label class="na">Content:</label>
                                    <textarea name='abovefooter_message' id="abovefooter_message" class="form-control froala-editor-admin"><?=$row_data[message]?></textarea>
                               </div> 
		 <div style="text-align: center;">
	 <input type="submit" name="btn_abovefooter" class="btn btn-success text-center" value="Save">
			 </div>
	 </form>
	 </div>
	<?
}?>
		<?php if ($_GET['widget'] == "Search Results Content"&& $_GET['readmore'] == "1") {?>
 <div class="col-md-12">
<h2>Read More</h2>
	 <form role="form" method="post"  action="" enctype="multipart/form-data">

	
		<div class="form-group">
			<? $row=mysql($w['database'],"select * from `search_page_content` where `id`=4");
					$row_data=mysql_fetch_assoc($row);?>
                                    <label class="na">Content:</label>
                                    <textarea name='readmore_message' id="abovefooter_message" class="form-control froala-editor-admin"><?=$row_data[message]?></textarea>
                               </div> 
		 <div style="text-align: center;">
	 <input type="submit" name="btn_readmore" class="btn btn-success text-center" value="Save">
			 </div>
	 </form>
	 </div>
	<?
}?>
		</div></div>