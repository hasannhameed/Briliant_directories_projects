<? 
$dbt=brilliantDirectories::getDatabaseConfiguration('database');
 $pageld=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classic_page_fields` WHERE id=39"); 
$pagesd=mysql_fetch_assoc($pageld);
if($pagesd['edit_token']==$_GET['edit_token']){	
 ?>



<script>
function onSubmitcode(pid,e){
e.preventDefault();
var form_name = '#' +pid;
    $.ajax({
         type: "POST",
		 data : $(form_name).serialize(),   
         url:  "/api/widget/html/get/classic_save",		 
			 success: function(data){
				 }
		
    });	
		  swal({
					title: "Success",
					text: "data saved successfully.",
					type: "success"
				});
window.setTimeout(function(){location.reload()},2000)
}

function openPopup(rid) {
   $('.popup_one').hide();
   $('#' + rid).fadeIn(200);   
}
function closePopup() {
    $('.popup_one').fadeOut(300);
}	

</script>
<? } ?>


