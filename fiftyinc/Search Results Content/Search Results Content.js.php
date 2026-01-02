<style>
i.fa.fa-trash.fr-delete-img {
    display: none!important;
}
body .fr-modal .fr-modal-wrapper div.fr-scroller div.fr-image-list div.fr-image-container .fr-insert-img {
    left: 70%;
}</style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_editor.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/css/froala_editor.pkgd.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.3.4/js/froala_editor.pkgd.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

    <script>
var max_text_editor_size = '<?php echo $w[max_text_editor_size];?>';

if (!max_text_editor_size) {
   max_text_editor_size = 10;
}

var imageMaxSizeSetting = 1024 * 1024 * Number(max_text_editor_size);
        
       var imageMaxSizeSetting = 1024 * 1024 * max_text_editor_size;
        var widgetName = "Bootstrap Theme - Form - Froala Editor Actions";
        $(document).ready(function() {
            $('.froala').froalaEditor({
                placeholderText: '<?php echo $label['froala_type_something']?>',
                toolbarInline: false,
                minHeight: '350',
                height: '350',
                codeMirrorOptions: {
                    indentWithTabs: true,
                    lineNumbers: true,
                    lineWrapping: true,
                    mode: 'text/html',
                    tabMode: 'indent',
                    tabSize: 4
                },
                key: 'kKC1KXDF1INBh1KPe2TK==',
                maxHeight: '800',
                buttons: ["bold", "italic", "underline", "fontSize", "color", "paragraphFormat", "inlineStyle", "align", "outdent", "indent", "insertLink", "insertTable", "undo", "redo", "html", "fullscreen"]
            });
            //default froala class backwards compatibility limited features
            $('.froala-editor').froalaEditor({
                placeholderText: '<?php echo $label['froala_type_something']?>',
                minHeight: '350',
                height: '350',
                key: 'kKC1KXDF1INBh1KPe2TK==',
                quickInsertButtons: ['table', 'ul', 'ol', 'hr'],
                maxHeight: '600',
                toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-', 'insertLink', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
            });
            //froala editor code for the admin blog features, gives the functionality of upload images, browse the /images folder and add videos
            $('.froala-editor-admin').froalaEditor({
                minHeight: '350',
                height: '350',
                key: 'kKC1KXDF1INBh1KPe2TK==',
                maxHeight: '800',
                imageUploadParam: 'blog_image',
                imageUploadURL: '/wapi/widget?widget_name=' + widgetName + '&header_type=json&request_type=GET',
                imageUploadMethod: 'POST',
                imageUploadParams: {
                    upload_scope: 'admin'
                },
                imageManagerPageSize: 20,
                imageAllowedTypes: ['jpeg', 'jpg', 'png'],
                toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
                imageManagerLoadURL: '/wapi/widget?widget_name=' + widgetName + '&header_type=json&request_type=GET&module_action=browse_images',
                imageManagerLoadMethod: "GET",
                imageManagerDeleteURL: false,
                imageMaxSize: imageMaxSizeSetting
            }).on('froalaEditor.image.removed', function (e, editor, $img) {
                $.ajax({
                    method: "POST",
                    url: '/wapi/widget',
                    data: {
                        widget_name: widgetName,
                        header_type: 'json',
                        request_type: 'POST',
                        module_action: 'delete_image',
                        src_path: $img.attr('src')
                    }
                })
                .done (function (data) {
                    console.log(data);
                    console.log ('image was deleted');
                })
                .fail (function (data) {
                    console.log(data);
                    console.log ('image delete problem');
                });
            });
            //froala editor code for the members that includes a feature to upload images to /images/member-uploads where the member can upload but can not browse the folder
            $('.froala-editor-user-upload').froalaEditor({
                minHeight: '350',
                height: '350',
                key: 'kKC1KXDF1INBh1KPe2TK==',
                maxHeight: '800',
                imageUploadParam: 'blog_image',
                imageUploadURL: '/wapi/widget?widget_name=' + widgetName + '&header_type=json&request_type=GET',
                imageUploadMethod: 'POST',
                imageUploadParams: {
                    upload_scope: 'member'
                },
                imageAllowedTypes: ['jpeg', 'jpg', 'png'],
                imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL'],
                toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
                imageMaxSize: imageMaxSizeSetting

            }).on('froalaEditor.image.removed', function (e, editor, $img) {
                $.ajax({
                    method: "POST",
                    url: '/wapi/widget',
                    data: {
                        widget_name: widgetName,
                        header_type: 'json',
                        request_type: 'POST',
                        module_action: 'delete_image',
                        src_path: $img.attr('src')
                    }
                })
                .done (function (data) {
                    console.log(data);
                    console.log ('image was deleted');
                })
                .fail (function (data) {
                    console.log(data);
                    console.log ('image delete problem');
                });
            });
        });
   $(window).load(function () {
    $(".trigger_popup_fricc").click(function(){
       $('.hover_bkgr_fricc').show();
    });
    $('.hover_bkgr_fricc').click(function(){
        $('.hover_bkgr_fricc').hide();
    });
    $('.popupCloseButton').click(function(){
        $('.hover_bkgr_fricc').hide();
    });
});
 </script>