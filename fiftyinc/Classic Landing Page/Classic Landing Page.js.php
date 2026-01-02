    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_editor.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_editor.pkgd.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.pkgd.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

<? 
$dbt=brilliantDirectories::getDatabaseConfiguration('database');
 $pageld=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classic_page_fields` WHERE id=39"); 
$pagesd=mysql_fetch_assoc($pageld);
if($pagesd['edit_token']==$_GET['edit_token']){	
 ?>



<script>
function onSubmit(fid,e){
e.preventDefault();
var form_name = '#' +fid;
    $.ajax({
         type: "POST",
		 data : $(form_name).serialize(),   
         url:  "/api/widget/html/get/classic_img_path_save",		 
			 success: function(data){
				 }
		
    });	
		  swal({
					title: "Success",
					text: "Image Uploaded successfully.",
					type: "success"
				});
	window.setTimeout(function(){location.reload()},3000);
}

function openPopup(cid) {
   $('.popup_one').hide();
   $('#' + cid).fadeIn(200);   
}
function closePopup() {
    $('.popup_one').fadeOut(300);
}	
 $(document).ready(function() {
<? for($i=1;$i<=25;$i++){ ?>	 
    $('#file-upload<?=$i?>').on('change', function () {
	 $("#msg<?=$i?>").text(this.files[0].name);
    var formData = new FormData();
	formData.append('file', $('#file-upload<?=$i?>')[0].files[0]); 
    $.ajax({
        url: '/api/widget/html/get/classic_imgupload', 
		 type: 'POST', 
        data: formData,                        
		cache: false,
        contentType: false,
        processData: false,
       success: function(data){
		   if(data!=""){
		   $('form input[type="submit"]'). prop("disabled", false);
		   }
		   $("#image_path<?=$i?>").val(data);
        }
     });
	 });
	 <? } ?>
});
	
	
	
	
</script>


<? }?>



<? 
$dbt=brilliantDirectories::getDatabaseConfiguration('database');
 $pageld=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `classic_page_fields` WHERE id=39"); 
$pagesd=mysql_fetch_assoc($pageld);
if($pagesd['edit_token']==$_GET['edit_token']){	
 ?>

<script>
$(document).ready(function() {	
//$('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });
	
  $('.editable').on('dblclick', function() {
    var value = $(this);
	var dataname= $(this).attr("data-value") ; 
    var data = value.find('textarea');
    if (data.length) {
      //value.text(data.val());
    } else {
	var inputs=$(".tnt").find('textarea').length;
		//alert(inputs);
if(inputs==1){	
var edata='"'+  dataname + '"';
      data = $("<textarea class='edib froala-editor' name='"+ dataname +"'></textarea><a href='javascript:void(0);' onclick='editData("+ edata +");' style='border-radius: 8px;background-color:#fff;color:#000;padding:8px;font-size: 20px;text-decoration:none;font-weight:bold;z-index: 1000;position: absolute;'><i class='fa fa-check-circle' style='font-size:23px;color:green !important;padding-left: 3px;padding-right: 3px; '></i>SAVE</a>").prop({
        'type': 'text',
        'value': value.html()
      });
		
      value.empty().append(data);
      data.focus();
 }
	}
var max_text_editor_size = '<?php echo $w[max_text_editor_size];?>';

if (!max_text_editor_size) {
   max_text_editor_size = 10;
}
        var widgetName = "Bootstrap Theme - Form - Froala Editor Actions";
         $(document).ready(function() {
            $('.froala').froalaEditor({
                placeholderText: '<?php echo $label['froala_type_something']?>',
                toolbarInline: false,
                minHeight: '250',
                height: '250',
                codeMirrorOptions: {
                    indentWithTabs: true,
                    lineNumbers: true,
                    lineWrapping: true,
                    mode: 'text/html',
                    tabMode: 'indent',
                    tabSize: 4
                },
                key: 'kKC1KXDF1INBh1KPe2TK==',
                maxHeight: '300',
                buttons: ["bold", "italic", "underline", "fontSize", "color", "paragraphFormat", "inlineStyle", "align", "outdent", "indent", "insertLink", "insertTable", "undo", "redo", "html", "fullscreen"]
            });
            //default froala class backwards compatibility limited features
            $('.froala-editor').froalaEditor({
               placeholderText: '<?php echo $label['froala_type_something']?>',
                minHeight: '250',
                height: '250',
                key: 'kKC1KXDF1INBh1KPe2TK==',
                quickInsertButtons: ['table', 'ul', 'ol', 'hr'],
                maxHeight: '300',
                toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-', 'insertLink', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
            });           
        });	  
  });
     }); 
  function editData(dbname){
	  var x = $('.fr-element').html();
	 var name = dbname;
	 var dataString = {edited_value:x,db_name:name}
	 
	  $.ajax({
		   type:'POST',
   		   data:dataString,
		   url: "/api/widget/html/get/Classic Editable",
	  success:function(data){
		
			    swal({
					title: "Success",
					text: "Content changed Successfully.",
					type: "success"
				});
	window.setTimeout(function(){location.reload()},2000); 
			  }
  });
  }	
	
</script>
<? } ?>


<?php $imgup=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `landingpage_images` where id=22");
	$imgdata22=mysql_fetch_assoc($imgup); ?>
<style>
 .layers-widget-column-134-186 { background-repeat: no-repeat;background-position: center;background-image: url('landingpageimages/<?=$imgdata22[file];?>');}
</style>






<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() { $(".lad").hide(); }, 1000);
	setTimeout(function() { $(".tnt").show(); }, 1000);
});
</script>
