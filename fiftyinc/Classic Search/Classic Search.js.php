<script>
$(document).ready(function() {	
if ($(window).width() < 376) {
  $('.axn').css("display","none");
  $('.axno').css("display","block");
}
else {
 $('.axn').css("display","block");
  $('.axno').css("display","none");
}   
});
</script>
<script>
function onSave(tid,event){
event.preventDefault();
var form_name = '#' +tid;
    $.ajax({
         type: "POST",
		 data : $(form_name).serialize(),   
         url:  "/api/widget/html/get/Classic Editable",		 
         success: function(data){
			  $('.input-group-addon.rex.axn').hide();
			  swal({
                title: "Success",
                text: "Data Saved successfully.",
                type: "success"
            });	
	window.setTimeout(function(){location.reload()},2000); 	
			 } 
	});
    
}
function openPopmail(hid) {
   $('.popup_one').hide();
   $('#' + eid).fadeIn(200);   
}
function closePopup() {
    $('.popup_one').fadeOut(300);
}



</script>


<?php
// This code replaces the Form - Froala Editor Javascript Widget. Please verify that the HTML, CSS and JS Tabs of this same widget are empty before adding this code.
// This code goes in the Javascript section of the widget.
// Option to set the size of uploaded files in Megabytes (eg: 1, 2, 5). Default is 5.
$fileUploadSize = 5;
$froalaVersion = '2.7.3';
$codeMirrorVersion = '5.33.0';
?>
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/froala_editor.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/froala_style.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/char_counter.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/code_view.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/colors.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/image.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/line_breaker.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/table.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/fullscreen.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/video.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/css/plugins/draggable.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/codemirror/<?=$codeMirrorVersion?>/codemirror.min.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/froala_editor.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/code_view.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/table.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/colors.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/font_family.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/font_size.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/paragraph_style.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/image.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/video.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/image_manager.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/file.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/draggable.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/char_counter.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/fullscreen.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/align.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/link.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/lists.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/froala-editor/<?=$froalaVersion?>/js/plugins/paragraph_format.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/<?=$codeMirrorVersion?>/codemirror.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/<?=$codeMirrorVersion?>/mode/xml/xml.min.js"></script>
<script>
$('.froala-editor, .froala-editor-admin, .froala-editor-user-upload').froalaEditor({
charCounterMax: 200000,
placeholderText: 'You can type here.',	
minHeight: '350',
height: '350',
dragInline: true,
imageResize: true,
videoResize: true,
toolbarSticky: false,
imageMove: true,
videoMove: true,
videoDefaultAlign: 'center',
videoDefaultDisplay: 'inline',
videoInsertButtons: ['videoBack', '|', 'videoByURL', 'videoEmbed'],
videoTextNear: true,
imagePaste: true,
fileMaxSize: <?=$fileUploadSize?> * 1024 * 1024,
imageAllowedTypes: ['jpeg', 'jpg', 'png', 'pdf', 'gif'],
fileAllowedTypes: ['*'],
imageAltButtons: ["display", "align", "linkImage", "replaceImage", "removeImage"],
codeMirrorOptions: {
indentWithTabs: true,
lineNumbers: true,
lineWrapping: true,
mode: 'text/html',
tabMode: 'indent',
tabSize: 4
},
imageUploadURL: '/imageup.php',
fileUploadURL: '/imageup.php',
fileUploadParams: {
id: '<?=$siteName?>'
},
imageUploadParams: {
id: '<?=$siteName?>'
},
key: 'kKC1KXDF1INBh1KPe2TK==',
maxHeight: '350',
toolbarButtons:   ["bold", "italic", "underline", "fontSize", "fontFamily", "color", "paragraphFormat", "inlineStyle", "align", "outdent", "indent", "formatOL", "formatUL", "paragraphStyle", "createLink", "insertHR", "insertLink", "insertTable", "insertFile", "insertImage", "insertVideo", "undo", "html"],
toolbarButtonsMD: ["bold", "italic", "underline", "fontSize", "fontFamily", "color", "paragraphFormat", "inlineStyle", "align", "outdent", "indent", "formatOL", "formatUL", "paragraphStyle", "createLink", "insertHR", "insertLink", "insertTable", "insertFile", "insertImage", "insertVideo", "undo", "html"],
toolbarButtonsSM: ["bold", "italic", "underline", "fontSize", "fontFamily", "color", "paragraphFormat", "inlineStyle", "align", "outdent", "indent", "formatOL", "formatUL", "paragraphStyle", "createLink", "insertHR", "insertLink", "insertTable", "insertFile", "insertImage", "insertVideo", "undo", "html"],
toolbarButtonsXS: ["bold", "italic", "underline", "fontSize", "fontFamily", "color", "paragraphFormat", "inlineStyle", "align", "outdent", "indent", "formatOL", "formatUL", "paragraphStyle", "createLink", "insertHR", "insertLink", "insertTable", "insertFile", "insertImage", "insertVideo", "undo", "html"]
}).on('froalaEditor.drop', function (e, editor, dropEvent) {
// Focus at the current posisiton.
editor.markers.insertAtPoint(dropEvent.originalEvent);
var $marker = editor.$el.find('.fr-marker');
$marker.replaceWith($.FroalaEditor.MARKERS);
editor.selection.restore();

// Save into undo stack the current position.
if (!editor.undo.canDo()) editor.undo.saveStep();

// Insert HTML.

// Save into undo stack the changes.
editor.undo.saveStep();

// Stop event propagation.
dropEvent.preventDefault();
dropEvent.stopPropagation();
return false;
}).on('froalaEditor.image.error', function (e, editor, error, response) {
// Bad link.
if (error.code == 1) { console.log(error); console.log(response); }

// No link in upload response.
else if (error.code == 2) { console.log(error); console.log(response); }

// Error during image upload.
else if (error.code == 3) { console.log(error); console.log(response); }

// Parsing response failed.
else if (error.code == 4) { console.log(error); console.log(response); }

// Image too text-large.
else if (error.code == 5) { console.log(error); console.log(response); }

// Invalid image type.
else if (error.code == 6) { console.log(error); console.log(response); }

// Image can be uploaded only to same domain in IE 8 and IE 9.
else if (error.code == 7) { console.log(error); console.log(response); }

else { console.log(error.code); }
});
</script>
