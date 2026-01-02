<div class="clearfix clearfix-lg"></div>
<? 

if($_GET[q]!=""){
$sub_Cat=$_GET[q];}
if($_GET[sid]!=""){
	echo "hello";
	$result=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `list_professions` where profession_id=".$_GET[sid]."");
	echo "SELECT * FROM `list_professions` where profession_id=".$_GET[sid]."";
	$s=mysql_fetch_assoc($result);
	$sub_Cat=$s[name];
	}
if($_GET[tid]!=""){
	$result=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `list_services` where service_id=".$_GET[tid]."");
	$s=mysql_fetch_assoc($result);
	$sub_Cat=$s[name];
	}
if($sub_Cat==""){
	$sub_Cat="Businesses";}
$loc=$_GET[city];
?>

    <div class="row">
    <div class="col-sm-12">
    <div class="col-sm-6 block1">
    <div class="heading" style="height:90px;  display : table-cell;
  vertical-align: middle;">
      <h3 style="vertical-align: middle;">Verification of few <?=$sub_Cat?> <? if($loc!=""){ ?>in <?=$loc?> <? } ?>is yet in process.</h3>  </div>
		<div class="para" style="height: 35px;
  vertical-align: middle;"><p style="vertical-align: middle;">
        
We can still match you to a few <?=$sub_Cat?> <? if($loc!=""){ ?> in <?=$loc?> <? } ?>from our private network.

		</p></div>
		<div class="clearfix" style=" margin-bottom: 14px;">

		</div>
		<a href="/getmatched" class="btn-lg btn btn-block sub1">Get Connected</a>
    </div>
    <div class="col-sm-6 block2">
        <div class="heading" style="height:90px;display : table-cell;
  vertical-align: middle;">
	 <h3 style="vertical-align: middle;padding-left: 19px;">Do you have a business/service?</h3></div>
		<div class="para" style="height: 35px;
  vertical-align: middle;"><p style="vertical-align: middle;">List your business or services on LocalBulls for free.</p></div>
		<div class="clearfix" style=" margin-bottom: 14px;">

		</div><a href="/payment/3" class="btn-lg btn btn-block sub2">Get Listed for Free <i class="fa fa-chevron-right lmargin"></i></a>
    </div></div></div><?php /*?>
[widget=Bootstrap Theme - Display - Get Matched Form]
<?php */?>
