<div class="tab-content">

<?php echo widget("states and cities","",$w[website_id],$w);?>
<?php echo widget("faq-showcase","",$w[website_id],$w);?>
	</div>
<? 

$headcontent=mysql($w['database'],"select * from `search_page_content` where `id`=3");
$headcontent_data=mysql_fetch_assoc($headcontent);
if($headcontent_data[message]!=""){?>
<div class="tab-content">
<? 
$n=$page[keyword];

?>
<div class="container">
<?
echo str_replace("{location}",$n[0],$headcontent_data[message]);

?>
</div>
	
</div>

<? } ?>