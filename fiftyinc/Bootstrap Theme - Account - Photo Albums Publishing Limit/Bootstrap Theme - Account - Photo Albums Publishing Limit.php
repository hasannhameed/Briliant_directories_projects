<?
if($_GET['cat']!="")
  {
	  //echo "hello";
    //$ca=mysql($w['database'],"SELECT * FROM `car_categories` where data_name=$_GET[cat]");
    //$car=mysql_fetch_assoc($ca);
      //echo form($_GET['cat'],"",$w[website_id],$w); 
  
   $dc['form_name']  =  str_replace('','_',$_GET['cat'])."_album_fields";
	
	//$dc['form_name']  =  'form_shopping_and_retail' ;
    //$dc['feature_categories'] = $car['feature_categories'];
  }
if($pars[1]=="sell-products" && $pars[2]=="editgroup")
  {
	  
   // $ca=mysql($w['database'],"SELECT * FROM `car_categories` where category_id=$g[car_category]");
    // $car=mysql_fetch_assoc($ca);
	 
	  
    $dc['form_name']=  str_replace('','_',$g['group_category'])."_album_fields";
	 //$dc['form_name']=  'form_shopping_and_retail' ;
	  
    //$dc['feature_categories']=$car['feature_categories'];
  }
$publishLimit = getAddOnInfo('post_publish_limit','eda9f7363df39706f61d98df7af5afcb');
if (isset($publishLimit['status']) && $publishLimit['status'] === 'success') {
  echo widget($publishLimit['widget'],"",$w['website_id'],$w);
}
 
?>
