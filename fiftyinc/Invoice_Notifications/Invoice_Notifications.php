<? 
if($_GET[delete]!=''){
$result=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"DELETE FROM `email_templates` WHERE email_id=".$_GET['delete']);
	 $msg='template deleted successfully.';	
}


if(isset($_POST['add_coupon'])){
	
	if($_POST[days]=="") $days="14"; 
	if($_POST[ma_id]==""){
	$result=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"insert into invoice_mapping set
	`title`='$title',`days`='$days',`email_template`='$email',`schedule`='$schedule'");
	 $msg='Mapping done successfully.';
	 header("Location: /admin/go.php?widget=Invoice_Notifications");
	}
	else{mysql(brilliantDirectories::getDatabaseConfiguration('database'),"update invoice_mapping set
	`title`='$title',`days`='$days',`email_template`='$email',`schedule`='$schedule' where id=".$_POST[ma_id]."");}
	header("Location: /admin/go.php?widget=Invoice_Notifications");
	}
	if($_POST[email_subject]!='')
{
	if($edit_id==''){
	$result=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"insert into email_templates set `email_name`='$email_name',`email_subject`='$email_subject',`email_body`='$email_body',`category_id`='0',signature='$signature',notemplate='$notemplate'");
	 $msg='Template added successfully.';
	}else{
		
		$result=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"update email_templates set `email_name`='$email_name',`email_subject`='$email_subject',`email_body`='$email_body',`category_id`='0',signature='$signature',notemplate='$notemplate' where email_id='$edit_id'");
	 $msg='Template Updated successfully.';
	}
}
	
	if($_GET[map_id]!=""){
		$result1=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"select * from invoice_mapping where id=".$_GET[map_id]."");
		$row1=mysql_fetch_assoc($result1);
		}
?><div class="container-fluied">
<div class="">
  <? if($_GET['tab']=='' || $_GET['tab']=='invoice') $invoice="in active";else if($_GET['tab']=='email') $emailtab='in active'?>

<ul class="nav nav-tabs">
    <li class="<?=$invoice?>"><a  href="/admin/go.php?widget=Invoice_Notifications&tab=invoice"> Invoice Sequence</a></li>
    <li class="<?=$emailtab?>"><a  href="/admin/go.php?widget=Invoice_Notifications&tab=email&view=1">Email Template</a></li>
  </ul>
<div class="tab-content">
<div id="invoice" class="tab-pane fade <?=$invoice?>">
<div class="first">
<h3 style="text-align:center">Mapping of Email Templates & Schedule</h3>
<div class="mapping">
 <form action="" method="post" id="coouponform">
 <input type="hidden" name="ma_id" value="<?=$row1[id]?>">
   <div class='row'>
    <div class="form-group col-sm-offset-1 col-sm-3" style="font-size:17px;text-align:right;">
    <label for="pwd">Name of this Schedule<br><p style="font-size:11px;text-align:left;">(this is for your reference only)</p></label>
   
  </div>
  <div class="form-group col-sm-6">
   <input type="text" name='title' class="form-control"  value='<?=$row1[title]?>' id="title" required <? if($_GET[map_action]=="view"){echo "readonly";}?>></div>
   </div>
   <div class='row'>
     <div class="form-group col-sm-4">
     <? $data=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `email_templates`");
	?>
    <label for="pwd">Select Email Template</label>
    <select name='email' class='form-control' required <? if($_GET[map_action]=="view"){?>disabled="disabled"<? }?>>
    <option value=''>Select</option>
     <? if(mysql_num_rows($data)){ 
	 while($d=mysql_fetch_assoc($data)){ ?>
     <option value='<?=$d[email_id]?>' <? if($row1['email_template']=="$d[email_id]") echo "selected"?>><?=$d[email_name]?></option><? } } 
	 ?>
    </select>
  </div>
  <div class="form-group col-sm-2 tnt">
    <label for="pwd">Number of Days</label>
    <input type="number" name='days' id="days" class="form-control"  value='<? if($row1[schedule]=="onpay" || $row1[schedule]=="onlead"){echo "0"; }else { echo $row1[days];}?>' required <? if($_GET[map_action]=="view"){echo "readonly";}?> min="1" max="14"></div>
    <div class="form-group col-sm-4">
    <label for="pwd">Select Schedule</label>
    <select name='schedule' id="schedule" class='form-control' required <? if($_GET[map_action]=="view"){?>disabled="disabled"<? }?>>
    <option value=''>Select</option>
     <option value='ontheday' <? if($row1['schedule']=="ontheday") echo "selected"?>>On the Day of invoice Generation(14days from actual due date)</option>
      <option value='before' <? if($row1['schedule']=="before") echo "selected"?>>Before Due Date</option>
      <option value='after'  <? if($row1['schedule']=="after") echo "selected"?>>After Due Date</option>
      <option value='onpay'  <? if($row1['schedule']=="onpay") echo "selected"?>>On Membership fee Payment</option>
      <option value='onlead'  <? if($row1['schedule']=="onlead") echo "selected"?>>On Lead Successful Purchase</option>

    </select>
  </div>
  <? if($_GET[map_action]!="view"){?>
  <div class="form-group col-sm-2" style=" margin-top: 20px;">
  <button type="submit" class="btn btn-primary add_coupon" name='add_coupon'><? if($_GET[map_action]=="edit"){echo "Save";} else{ echo "Add";} ?></button>

   </div>
  <? } ?>
  
    </div>
    
     </form>
  </div>
   
   </div>
   <? if($_GET['map_action']!='edit'){ ?>
<div class="List_schedules">
<h3>List of Schedules</h3>
<table class='table table-bordered table-striped'>
<tr><th>#</th><th>Name of Schedule</th><th>Email Template</th><th>Number of Days</th><th>Schedule</th><th>Action</th></tr>
<? $list=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `invoice_mapping`");
if(mysql_num_rows($list)>0){
$i=1; while ($row = mysql_fetch_assoc($list)) {
	if($row[schedule]=="ontheday"){$schedule="On the Day of invoice Generation<br>(14days from actual due date)";} 
	else if($row[schedule]=="before"){$schedule="Before Due Date";}
	else if($row[schedule]=="after"){$schedule="After Due Date";}
	else if($row[schedule]=="onpay"){$schedule="On Membership fee Payment";}
	else if($row[schedule]=="onlead"){$schedule="On Lead Successful Purchase";}
	
	$email=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `email_templates` where email_id=".$row[email_template]."");
	$email_name=mysql_fetch_assoc($email);
	?>
 
 <tr id="<?php echo $row[id] ?>"><td><?=$i?></td><td><?=$row[title]?></td><td><?=$email_name[email_name]?> <a href="/admin/emailTemplates.php?faction=preview&email=<?=$row[email_template]?>"><i class="fa fa-eye"></i></a></td><td><? if($row[schedule]=="onpay" || $row[schedule]=="onlead"){echo "On Pay"; }else { echo $row[days];}?></td>
 <td><?=$schedule?></td><td style='font-size: 18px;'>
 <a href="/admin/go.php?widget=Invoice_Notifications&map_id=<?=$row[id]?>&map_action=view"><i class="fa fa-eye"></i></a><a href='#' class="map_delete"><i class="fa fa-trash"></i></a>
<a href='/admin/go.php?widget=Invoice_Notifications&map_id=<?=$row[id]?>&map_action=edit'><i class="fa fa-pencil-square-o"></i></a>

</td></tr>

 <? $i++;}}else{?>
<tr><td colspan="8" class='text-center'>No data available</td></tr>
<? }?>

</table>
</div>
</div>
<? } ?>
<div id="email" class="tab-pane fade <?=$emailtab?>">
<div class="email_tem" style="margin-top: 20px;">
<? if($msg!=''){?>
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> <?=$msg?>.
</div>
<? } ?>
<? if($_GET[view]!=''){ ?>
<div class='row'>
   <div class="splash-content" id="load-html">
    <div style="display: inline-block; width: 100%; ">
      <h1 class="nomargin" style="display:inline-block;">Email Templates</h1>
      <a style="float:right;" href="/admin/go.php?widget=Invoice_Notifications&tab=email" class="sbuttonwiz">New Email Template <i aria-hidden="true" class="fa fa-plus" style="vertical-align: middle; margin-left: 2px;"></i></a> </div>
      <hr>
<table class='table templates'>
<thead>
<tr><th>S.no</th><th>Template Name</th><th>Actions</th></tr></thead>
<tbody>
<?
	$rs=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `email_templates` order by email_id ");
	$i=1;
	while($row=mysql_fetch_assoc($rs)){
?>
<tr><td><?=$i?></td><td><?=$row['email_name']?></td><td><a href='/admin/go.php?widget=Invoice_Notifications&tab=email&edit=<?=$row[email_id]?>' class='btn btn-primary btn-xs' style='margin-right:10px;'>Edit</a><a href='/admin/go.php?widget=Invoice_Notifications&tab=email&view=1&delete=<?=$row[email_id]?>' class='btn btn-danger btn-xs delete_temp'>Delete</a></td></tr>
<? $i++;}?>
</tbody>
</table>

</div>
</div>
<? }else{
	if($_GET['edit']!=''){
$rs=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `email_templates` where email_id=".$_GET['edit']);		
$row=mysql_fetch_assoc($rs);		
		
	}
	?>
<div class='row'>
  <div class="splash-content" id="load-html">
    <div style="display: inline-block; width: 100%; ">
      <h1 class="nomargin" style="display:inline-block;"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> Edit Email Template</h1>
      <a style="float:right;" href="/admin/go.php?widget=Invoice_Notifications&tab=email&view=1" class="sbuttonwiz">Back to Templates</a> </div>
    <hr style="margin: 15px 0px;">
    <div class="clear"></div>
	  <form  action="/admin/go.php?widget=Invoice_Notifications&tab=email&view=1" method="post">
      <input type='hidden' name='edit_id' value='<?=$_GET[edit]?>'>
      <table style="width:100%;">
        <tbody>
          <tr>
            <td valign="top"><input style="width: 98.6%;width: calc(100% - 17px); font-size: 18px;  line-height: 1.4em;margin-bottom:10px;" placeholder="Enter Email Subject Line" name="email_subject" value="<?=$row[email_subject]?>" size="100" type="text" >
             
              <div class="clear"></div>
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.min.css">
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css">
              <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
              <textarea id="elm1" name="email_body" class="froala-editor" style="display: none;"><?=$row[email_body]?></textarea>
			  </td>
   
            <td style="font-size: 12px; padding-left: 15px; white-space: nowrap; width: 367px;" valign="top">
            <div class="slim-sidebar">
                <table>
                  <tbody>
                    <tr>
                      <td><input style="display:block;width:100%;" value="Save Template" class="sbuttonwiz2" type="submit">
                        <div class="clear"></div>
                        <hr style="margin: 12px 0 4px 0;"></td>
                    </tr>
                    <tr>
                      <td><b>Template Name:</b> <br>
                        <input name="email_name" value="<?=$row[email_name]?>" size="60" type="text">
                        <span class="help-block">No Spaces. Only enter lower cases letters and dashes.</span>
                        <input name="category_id" value="" size="60" type="hidden">
                        <hr style="margin: 31px 0 3px;"></td>
                    </tr>
                    <tr>
                      <td nowrap=""><b>Template Category:</b> <br>
                        <select name="category_id">
                          <option value="1" <? if($row[category_id]==1) echo "selected";?>>Customer Service</option>
                          <option value="4" <? if($row[category_id]==4) echo "selected";?>>Lead Emails</option>
                          <option value="0" selected="">My Saved Templates</option>
                          <option value="3" <? if($row[category_id]==3) echo "selected";?>>System Email</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td><b>Include Signature:</b> <br>
                        <select name="signature">
                          <option value="0" <? if($row[signature]==0) echo "selected";?>> No </option>

                          <option value="1" <? if($row[signature]==1) echo "selected";?>> Website Signature </option>
                          <option value="2" <? if($row[signature]==2) echo "selected";?>> Operator Signature </option>
                        </select>
                        <span class="help-block">Add a signature at the end of this email.</span>
                        <div class="clearfix"></div>
                        <br>
                        <b>Include Website Logo:</b> <br>
                        <select name="notemplate">
                          <option value="0" selected="selected"> Yes </option>
                          <option value="1" <? if($row[notemplate]==1) echo "selected";?>> No </option>
                        </select></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="clear"></div>
              <div class="slim-sidebar email-variables">
        <h2 style="margin-top: 0px; text-align: center; font-size: 20px;">Email Variables</h2>

        <div id="variable_map_wrapper" class="dataTables_wrapper no-footer"><div id="variable_map_filter" class="dataTables_filter"></div>
        <table id="variable_map" class="dataTable no-footer strings" role="grid" aria-describedby="variable_map_info">
            <thead style="display:none;"><tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;"></th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;"></th></tr></thead>
            <tbody>
             <tr role="row" class="odd">
                <td >
                    <h3 style="margin-bottom: 5px;font-size:14px;">Invoice Variables</h3>
                    <hr>
                </td>
               <td></td>
            </tr><tr role="row" class="even">
                <td>Invoice Id</td>
                 <td><input type='text' value='%invoiceid%' readonly></td>
            </tr><tr role="row" class="odd">
                <td>Due Date</td>
                <td><input type='text' value='%duedate%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Due Amount</td>
                <td><input type='text' value='%total%' readonly></td></tr>
                <tr role="row" class="even">
                <td>Link for Payment</td>
                <td><input type='text' value='%payment_link%' readonly></td></tr>

              <tr role="row" class="odd">
                <td >
                    <h3 style="margin-bottom: 5px;font-size:14px;">Member Variables</h3>
                    <hr>
                </td>
               <td></td>
            </tr><tr role="row" class="even">
                <td>User ID</td>
                 <td><input type='text' value='%user_id%' readonly></td>
            </tr><tr role="row" class="odd">
                <td>First Name</td>
                <td><input type='text' value='%first_name%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Last Name</td>
                <td><input type='text' value='%last_name%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Email</td>
                <td><input type='text' value='%email%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Company</td>
                <td><input type='text' value='%company%' readonly></td>

            </tr><!--<tr role="row" class="odd">
                <td>Phone Number</td>
                <td><input type='text' value='%phone_number%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Address 1</td>
                <td><input type='text' value='%address1%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Address 2</td>
                <td><input type='text' value='%address2%' readonly></td>

            </tr><tr role="row" class="even">
                <td>City</td>
                <td><input type='text' value='%city%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Zip Code</td>
                <td><input type='text' value='%zip_code%' readonly></td>

            </tr><tr role="row" class="even">
                <td>State Code</td>
                <td><input type='text' value='%state_code%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>State Ln</td>
                <td><input type='text' value='%state_ln%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Country Code</td>
                <td><input type='text' value='%country_code%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Country Ln</td>
                <td><input type='text' value='%country_ln%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Website</td>
                <td><input type='text' value='%website%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Blog Link</td>
                <td><input type='text' value='%blog%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Subscription ID</td>
                <td><input type='text' value='%subscription_id%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Filename</td>
                <td><input type='text' value='%filename%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Active</td>
                <td><input type='text' value='%active%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Signup Date</td>
                <td><input type='text' value='%signup_date%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Account Type</td>
                <td><input type='text' value='%account_type%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Last Login</td>
                <td><input type='text' value='%last_login%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Position</td>
                <td><input type='text' value='%position%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Profession ID</td>
                <td><input type='text' value='%profession_id%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Verified</td>
                <td><input type='text' value='%verified%' readonly></td>

            </tr><tr role="row" class="odd">
                <td>Nationwide</td>
                <td><input type='text' value='%nationwide%' readonly></td>

            </tr><tr role="row" class="even">
                <td>Video</td>
                <td><input type='text' value='%video%' readonly></td>

            </tr>
            <tr role="row" class="odd">
                <td>Listing Type</td>
                <td><input type='text' value='%listing_type%' readonly></td>
            </tr>-->
          </tbody>
       </table>
     </div>
   </div>
              
              </td>
            </tr>
        </tbody>
      </table>
    </form>

  </div>
</div>


<? } ?>
</div>
</div>
 </div>
 </div>
 </div>
<?php /*?><?
$db=brilliantDirectories::getDatabaseConfiguration('database');
mysql($db,"TRUNCATE TABLE `invoice_data`"); 
$json=file_get_contents('http://localbulls.com/email_invoice.php');
$invoices=json_decode($json);
foreach ($invoices as $invoice){
	
	$id= $invoice-> id;
	$userid= $invoice-> userid;
	$invoicenum= $invoice-> invoicenum;
	$date= $invoice-> date;
	$duedate= $invoice-> duedate;
	$datepaid= $invoice-> datepaid;
	$subtotal= $invoice-> subtotal;
	$credit= $invoice-> credit;
	$tax= $invoice-> tax;
	$tax2= $invoice-> tax2;
	$total= $invoice-> total;
	$taxrate= $invoice-> taxrate;
	$taxrate2= $invoice-> taxrate2;
	$status= $invoice-> status;
	$paymentmethod= $invoice-> paymentmethod; 
	$notes= $invoice-> notes ;
	  mysql($db,"INSERT INTO `invoice_data` set id='$id',userid='$userid',invoicenum='$invoicenum',date='$date',duedate='$duedate',datepaid='$datepaid',subtotal='$subtotal',credit='$credit',tax='$tax',tax2='$tax2',total='$total',taxrate='$taxrate',taxrate2='$taxrate2',status='$status',paymentmethod='$paymentmethod',notes='$notes'");
	}
mysql($db,"TRUNCATE TABLE `invoice_data_items`"); 
$json_one=file_get_contents('http://localbulls.com/email_invoice_items.php');
$invoice_items=json_decode($json_one);
foreach ($invoice_items as $invoice_item){
	
	$id= $invoice_item-> invoiceid;
	$userid= $invoice_item-> userid;
	$invoicetype= $invoice_item-> type;
	$relid= $invoice_item-> relid;
	$description= $invoice_item-> description;
	$amount= $invoice_item-> amount;
	$duedate= $invoice_item-> duedate;
	  mysql($db,"INSERT INTO `invoice_data_items` set invoiceid='$id',userid='$userid',type='$invoicetype',relid='$relid',description='$description',amount=$amount,duedate='$duedate'");
	}

$sub_id=mysql($db,"SELECT * FROM `subscription_types` WHERE `profile_type`='paid' OR `profile_type`='paid'");
$sub_array=array();
//while($sub_on=mysql_fetch_assoc($sub_id)) $sub_array[]=$sub_on['subscription_id'];
//print_r($sub_array);
//$sub_types = implode(',', $sub_array); WHERE `subscription_id` IN($sub_types)
$usr_result=mysql($db,"SELECT * FROM `users_data`");
while($usr_id=mysql_fetch_assoc($usr_result)){
$user_one=mysql($db,"SELECT * FROM `users_meta` WHERE `key` LIKE 'clientid' AND database_id=$usr_id[user_id]");
$usr_cl=mysql_fetch_assoc($user_one);
if($usr_id['user_id']!="" && $usr_cl['value']!=""){
$inv_schedule=mysql($db,"SELECT * FROM `invoice_mapping`");
while($inv_row=mysql_fetch_assoc($inv_schedule)){
	if($inv_row['schedule']=='before'){
$NewDate= date('Y-m-d', strtotime("+$inv_row[days] days"));
//echo "SELECT * FROM `tblinvoices` WHERE duedate='$NewDate' AND status='Unpaid' AND userid=$usr_cl[value]";
$result_one=mysql($db,"SELECT * FROM `invoice_data` WHERE duedate='$NewDate' AND status='Unpaid' AND userid=$usr_cl[value]");
$row_one=mysql_fetch_assoc($result_one);
$row_count=mysql_num_rows($result_one);
if($row_count!=0 && $usr_id['invoice_sent']!=1){
$result_item=mysql($db,"SELECT * FROM `invoice_data_items` WHERE invoiceid='$row_one[id]'");
$row_item=mysql_fetch_assoc($result_item);
if($row_item['relid']!=0 && $row_item['type']!=""){
$em_template=mysql($db,"SELECT * FROM `email_templates` WHERE email_id='$inv_row[email_template]'");
$em_result=mysql_fetch_assoc($em_template);
//echo $row_one['userid']."id".$usr_id['user_id']."email".$em_result['email_name'];
	$w[user_id]=$usr_id['user_id'];
	$w[first_name]=$usr_id['first_name'];
	$w[last_name]=$usr_id['last_name'];
	$w[company]=$usr_id['company'];
	$w[invoiceid]=$row_one['id'];
	$w[total]=$row_one['subtotal'];
	$w[duedate]=$row_one['duedate'];
	$w[email]=$usr_id['email'];
	$w[payment_link]="http://localbulls.com/account/billing";
	$email= $usr_id['email'];
	 $emailPrepared = prepareEmail("$em_result[email_name]",$w);  /// Send Welcome email based on account type
   sendEmailTemplate($w[website_email],$email,$emailPrepared[subject],$emailPrepared[html],$emailPrepared[text],$emailPrepared[priority],$w);
mysql($db,"UPDATE users_data SET `invoice_sent`=1 WHERE user_id=$usr_id[user_id]"); 
}
} 
	} else if($inv_row['schedule']=='after'){
$NewDate= date('Y-m-d', strtotime("-$inv_row[days] days"));
//echo "SELECT * FROM `tblinvoices` WHERE duedate='$NewDate' AND status='Unpaid' AND userid=$usr_cl[value]";
$result_one=mysql($db,"SELECT * FROM `invoice_data` WHERE duedate='$NewDate' AND status='Unpaid' AND userid=$usr_cl[value]");
$row_one=mysql_fetch_assoc($result_one);
$row_count=mysql_num_rows($result_one);
if($row_count!=0 && $usr_id['invoice_sent']!=2){
$result_item=mysql($db,"SELECT * FROM `invoice_data_items` WHERE invoiceid='$row_one[id]'");
$row_item=mysql_fetch_assoc($result_item);
if($row_item['relid']!=0 && $row_item['type']!=""){
$em_template=mysql($db,"SELECT * FROM `email_templates` WHERE email_id='$inv_row[email_template]'");
$em_result=mysql_fetch_assoc($em_template);
//echo $row_one['userid']."id".$usr_id['user_id']."email".$em_result['email_name'];
	$w[user_id]=$usr_id['user_id'];
	$w[first_name]=$usr_id['first_name'];
	$w[last_name]=$usr_id['last_name'];
	$w[company]=$usr_id['company'];
	$w[invoiceid]=$row_one['id'];
	$w[total]=$row_one['subtotal'];
	$w[duedate]=$row_one['duedate'];
	$w[email]=$usr_id['email'];
	$w[payment_link]="http://localbulls.com/account/billing";
	$email= $usr_id['email'];
	 $emailPrepared = prepareEmail("$em_result[email_name]",$w);  /// Send Welcome email based on account type
   sendEmailTemplate($w[website_email],$email,$emailPrepared[subject],$emailPrepared[html],$emailPrepared[text],$emailPrepared[priority],$w);
mysql($db,"UPDATE users_data SET `invoice_sent`=2 WHERE user_id=$usr_id[user_id]"); 
}
} 
	} else if($inv_row['schedule']=='ontheday'){
$NewDate= date('Y-m-d', strtotime("+$inv_row[days] days"));
//echo $NewDate;
//echo "SELECT * FROM `tblinvoices` WHERE duedate='$NewDate' AND status='Unpaid' AND userid=$usr_cl[value]";
$result_one=mysql($db,"SELECT * FROM `invoice_data` WHERE duedate='$NewDate' AND status='Unpaid' AND userid=$usr_cl[value]");
$row_one=mysql_fetch_assoc($result_one);
$row_count=mysql_num_rows($result_one);
if($row_count!=0 && $usr_id['invoice_sent']!=3){
$result_item=mysql($db,"SELECT * FROM `invoice_data_items` WHERE invoiceid='$row_one[id]'");
$row_item=mysql_fetch_assoc($result_item);
if($row_item['relid']!=0 && $row_item['type']!=""){
$em_template=mysql($db,"SELECT * FROM `email_templates` WHERE email_id='$inv_row[email_template]'");
$em_result=mysql_fetch_assoc($em_template);
//echo $row_one['userid']."id".$usr_id['user_id']."email".$em_result['email_name'];
	$w[user_id]=$usr_id['user_id'];
	$w[first_name]=$usr_id['first_name'];
	$w[last_name]=$usr_id['last_name'];
	$w[company]=$usr_id['company'];
	$w[invoiceid]=$row_one['id'];
	$w[total]=$row_one['subtotal'];
	$w[duedate]=$row_one['duedate'];
	$w[email]=$usr_id['email'];
	$w[payment_link]="http://localbulls.com/account/billing";
	$email= $usr_id['email'];
	 $emailPrepared = prepareEmail("$em_result[email_name]",$w);  /// Send Welcome email based on account type
   sendEmailTemplate($w[website_email],$email,$emailPrepared[subject],$emailPrepared[html],$emailPrepared[text],$emailPrepared[priority],$w);
mysql($db,"UPDATE users_data SET `invoice_sent`=3 WHERE user_id=$usr_id[user_id]"); 
}
} 
	} else if($inv_row['schedule']=='onpay'){
//echo "SELECT * FROM `invoice_data` WHERE datepaid > date_sub(now(), interval 2 minute) AND status='Paid' AND userid=$usr_cl[value]";
$result_one=mysql($db,"SELECT * FROM `invoice_data` WHERE datepaid > date_sub(now(), interval 5 minute) AND status='Paid' AND userid=$usr_cl[value]");
$row_one=mysql_fetch_assoc($result_one);
$row_count=mysql_num_rows($result_one);
if($row_count!=0 && $usr_id['invoice_sent']!=4){
$result_item=mysql($db,"SELECT * FROM `invoice_data_items` WHERE invoiceid='$row_one[id]'");
$row_item=mysql_fetch_assoc($result_item);
if($row_item['relid']!=0 && $row_item['type']!=""){
$em_template=mysql($db,"SELECT * FROM `email_templates` WHERE email_id='$inv_row[email_template]'");
$em_result=mysql_fetch_assoc($em_template);
//echo $row_one['userid']."id".$usr_id['user_id']."email".$em_result['email_name'];
	$w[user_id]=$usr_id['user_id'];
	$w[first_name]=$usr_id['first_name'];
	$w[last_name]=$usr_id['last_name'];
	$w[company]=$usr_id['company'];
	$w[invoiceid]=$row_one['id'];
	$w[total]=$row_one['subtotal'];
	$w[duedate]=$row_one['duedate'];
	$w[email]=$usr_id['email'];
	$w[payment_link]="http://localbulls.com/account/billing";
	$email= $usr_id['email'];
	$emailPrepared = prepareEmail("$em_result[email_name]",$w);  /// Send Welcome email based on account type
   sendEmailTemplate($w[website_email],$email,$emailPrepared[subject],$emailPrepared[html],$emailPrepared[text],$emailPrepared[priority],$w);
mysql($db,"UPDATE users_data SET `invoice_sent`=4 WHERE user_id=$usr_id[user_id]"); 
}
} 
	} else if($inv_row['schedule']=='onlead'){
$result_one=mysql($db,"SELECT * FROM `invoice_data` WHERE datepaid > date_sub(now(), interval 5 minute) AND status='Paid' AND userid=$usr_cl[value]");
		//echo "SELECT * FROM `invoice_data` WHERE datepaid > date_sub(now(), interval 5 minute) AND status='Paid' AND userid=$usr_cl[value]"."<br>";
$row_one=mysql_fetch_assoc($result_one);
$row_count=mysql_num_rows($result_one);

if($row_count!=0 && $usr_id['invoice_sent']!=5){
$result_item=mysql($db,"SELECT * FROM `invoice_data_items` WHERE invoiceid='$row_one[id]'");
	//echo "SELECT * FROM `invoice_data_items` WHERE invoiceid='$row_one[id]'"."<br>";
$row_item=mysql_fetch_assoc($result_item);
if($row_item['relid']==0 && $row_item['type']==""){
$em_template=mysql($db,"SELECT * FROM `email_templates` WHERE email_id='$inv_row[email_template]'");
$em_result=mysql_fetch_assoc($em_template);
//echo $row_one['userid']."id".$usr_id['user_id']."email".$em_result['email_name'];
	$w[user_id]=$usr_id['user_id'];
	$w[first_name]=$usr_id['first_name'];
	$w[last_name]=$usr_id['last_name'];
	$w[company]=$usr_id['company'];
	$w[invoiceid]=$row_one['id'];
	$w[total]=$row_one['subtotal'];
	$w[duedate]=$row_one['duedate'];
	$w[email]=$usr_id['email'];
	$w[payment_link]="http://localbulls.com/account/billing";
	$email= $usr_id['email'];
	$emailPrepared = prepareEmail("$em_result[email_name]",$w);  /// Send Welcome email based on account type
   sendEmailTemplate($w[website_email],$email,$emailPrepared[subject],$emailPrepared[html],$emailPrepared[text],$emailPrepared[priority],$w);
mysql($db,"UPDATE users_data SET `invoice_sent`=5 WHERE user_id=$usr_id[user_id]"); 
}
} 
			}

		}
	}
}

?><?php */?>