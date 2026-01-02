<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/js/froala_editor.min.js"></script> 
              <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/js/froala_editor.pkgd.min.js"></script> 
              <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script> 
              <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
             
              <script>
jQuery(document).ready(function() {
    $('.froala-editor').froalaEditor({
        toolbarInline: false,
        iframe: true,
        minHeight: '350',
        height: '350',
        enter: $.FroalaEditor.ENTER_BR,
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
        toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'fontFamily', 'fontSize', 'specialCharacters', 'color', 'paragraphFormat', 'align', 'insertTable' , 'formatOL', 'formatUL', 'undo', 'redo', 'clearFormatting','insertLink','insertImage','html'],
		imageManagerLoadURL: '/admin/go.php?widget=System - Froala Upload Read Images&noheader=1&apitype=json'
    });
});
</script>

<script>
$('#schedule').on('change', function() {
var sear=this.value;
if(sear=="ontheday"){
 $("#days").attr("value", "14");
 $('#days').prop("disabled", true);
} else if(sear=="onpay" || sear=="onlead"){
 $("#days").attr("value", "On Pay");
 $('#days').prop("disabled", true);
} else{
	$('#days').prop("disabled", false);
	
	}
});

</script>
<script type="text/javascript">
    $(".map_delete").click(function(){
        var id = $(this).parents("tr").attr("id");
        if(confirm('Are you sure to remove this record ?'))
        {
            $.ajax({
               url: 'http://localbulls.com/delete.php',
               type: 'GET',
               data: {id: id},
               error: function() {
                  alert('Something is wrong');
               },
               success: function(data) {
                    $("#"+id).remove();
                    alert("Record removed successfully"); 
				   window.location="/admin/go.php?widget=Invoice_Notifications";
				   
               }
            });
        }
    });

</script>
 <script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script> 
            <script>
    $(document).ready(function() {
        $('#variable_map').dataTable({
            paging: false,
            lengthChange: false,
            bSort: false,
            "language": {
                "search": '',
                "searchPlaceholder": "Search Email Variables"
            }
        });
		$('.templates').dataTable();
		$('.delete_temp').click(function(e){
		  if(confirm("Are you sure you want to delete this?")){ return true; } else{ return false; }
		})
    });
</script>
