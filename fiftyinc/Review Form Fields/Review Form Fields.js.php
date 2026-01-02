<? if($_GET['ptoken']!=""){
 $res_data=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `users_portfolio_groups` WHERE group_token='$_GET[ptoken]'");
		   $re_row=mysql_fetch_assoc($res_data); 
$url_one='https://'.$w['website_url'].'/'.$re_row['group_filename']; ?>						   
<script>
$(document).ready(function(e) {
$(".app-submit").on("click",function(e) {
e.preventDefault();
//alert("Applcation");
var form = $("#member_review").serialize();	
 $.ajax({
		 type: "POST",
		 url: "/api/widget/html/get/ad-save-portfolio-review",
         data: form,
       success: function(result){
           if(result=="success"){
		  swal("Success!", "Your Review Submitted Successfully!", "success").then(function(){ 
			  window.location="<?=$url_one?>";		  
		  });
		   }else{
		   swal("Failure!", "Please Fill All The Fields!", "error");		   
		   }
       }

     });
});
});
</script>
<? } ?>