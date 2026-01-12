<?php
$currentURI .=$_SERVER['REQUEST_URI'];
?>
<script>
var mainWidget = 'Add-On - Bootstrap Theme - Direct Messages Flow Actions';
//code for the word counting
$(document).on('keyup', '#bd-chat-pmbrfc-message', function(){
    var input = $(this);
    var charNum = input.val().length;

    if (charNum > 2500) {
        input.val(input.val().substring(0, 2500));
        charNum = input.val().length;
    }
});
//code for expending the textarea
$('#bd-chat-pmbrfc-message').on('input', function() {
    $(this).outerHeight(38).outerHeight(this.scrollHeight);
});

//code to submit the init message thread
$(document).on('click','#bd-chat-pmb-sm-sm',function(e){
    e.preventDefault();
    var button = $(this);
    var responderToken = button.data('resptok');
    var message = $(this).parent().siblings(".bd-chat-pmb-rfc-fake-cont").find("#bd-chat-pmbrfc-message").val()
    
    if (message != "") {
        swal({
            html: `<i class='fa fa-refresh fa-spin fa-3x fa-fw'></i><br><br> <?php echo $label['chat_message_processing'] ?>`,
            showConfirmButton: false,
        });
        var gnaResponseArray = [];
        $.ajax({
            url: "/wapi/widget",
            type: "POST",
            dataType : "json",
            data : {
                "widget_name" : mainWidget,
                "header_type" : 'json',
                "request_type" : 'POST',
                "subaction" : 'init-pmb-thread',
                'responder' : responderToken,
                "origin_url":"<?php echo $currentURI;?>",
                'message' : message,
				'form_security_token' : '<?php echo generateCSRFToken(); ?>'
            },
            success: function(data){
                gnaResponseArray.push(data);
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
        var gnaInter = setInterval(function(){

            if (gnaResponseArray.length == 1) {
                clearInterval(gnaInter);

                if (gnaResponseArray[0]['result'] == "success") {

                    swal({
                        title: '',
                        text: `<?php echo $label['chat_message_sent_successfully']?>`,
                        type: 'success'
                    }).then((result) => {
                        window.location.replace(gnaResponseArray[0]['redirect_url']);
                    });

                } else {
                    swal("",``+gnaResponseArray[0]['message']+``,"warning");
                }
            }
        },400);

    } else {
        swal("",`<?php echo $label['chat_message_empty_error'] ?>`,"warning");
    }
});
</script>