<script>
	
        $(document).ready(function() { 
			$("#tag").select2(); 
		});
			    
	function submitContactForm(){
	
    var tagname = $('#tagname').val();
    var tagdesc = $('#tagdesc').val();
   
    if(tagname.trim() == '' ){
        alert('Please enter tag name.');
        $('#tagname').focus();
        return false;
    }else if(tagdesc.trim() == '' ){
        alert('Please enter Tag Description.');
        $('#tagdesc').focus();
        return false;
    }else{
		
		$.ajax({
            type:'GET',
            url:'https://bestwpdevelopers.com/submit_form.php',
            data:'contactFrmSubmit=1&tagname='+tagname+'&tagdesc='+tagdesc,
            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
				
				   
            },
            success:function(msg){
			
               alert("Record removed successfully"); 
                
              // $('.submitBtn').removeAttr("disabled");
               // $('.modal-body').css('opacity', '');
            }
        });
    

	
	}}

    </script>
