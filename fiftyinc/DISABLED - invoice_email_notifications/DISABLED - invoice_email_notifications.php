<?php

//Database connections
$con=mysqli_connect("localhost","wstan12631_billinguser",'EG8MUN3Say_U',"wstan12631_billing");
$con1=mysqli_connect("localhost","wstan12631_directoryuser",'!ShAI8[S]qWW',"wstan12631_directory");



$rs=mysqli_query($con1,"select * from invoice_mapping order by id");
while($map=mysqli_fetch_assoc($rs))
{
	//getting template details
	$rs1=mysqli_query($con1,"select * from email_templates where email_id=".$map['email_template']);
     $email_temp=mysqli_fetch_assoc($rs1);
     $template_name=$email_temp['email_name'];
     $date=date("Y-m-d");
	 $days=$map['days'];
  //getting invoice details based on mapping days
    if($map['schedule']=="after"){
				$sql="DATE_ADD(duedate, INTERVAL $days DAY)='$date' ";
	      }else if($map['schedule']=="before"){
				 $sql="DATE_ADD(duedate, INTERVAL -$days DAY)='$date'";
	      }else if($map['schedule']=="ontheday"){
			     $sql="DATE_ADD(duedate, INTERVAL -$days DAY)='$date'";
		  }else { $sql=1;
	  }
   $rs2=mysqli_query($con,"select * from tblinvoices where $sql and status!='Paid'");
	
	while($invoice=mysqli_fetch_assoc($rs2))
	{
		$rs3=mysqli_query($con,"select * from tblclients where id=".$invoice[userid]);
	      $data=mysqli_fetch_assoc($rs3);
            $id = substr(strrchr($data['notes'], "#"), 1);
		      $user=getUser($id,$w);
			    $to_email=$user[email];
				
		//sending value ato template 
		  foreach($user as $key=>$val) $w[$key]=$val;
		  $w['duedate']=$invoice['duedate'];
		  $w['total']=$invoice['total'];
		  $w['invoiceid']=$invoice['id'];
		//print_r($w);
       $email = prepareEmail($template_name,$w);  /// Prepare the email
       sendEmailTemplate($email[sender],$to_email,$email[subject],$email[html],$email[text],$email[priority],$w);
	  
	}
}
?>