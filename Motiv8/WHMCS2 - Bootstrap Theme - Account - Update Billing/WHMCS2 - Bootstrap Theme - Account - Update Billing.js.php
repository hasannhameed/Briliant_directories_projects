<?
/*
<script type="text/javascript" src="https://cdn.datatables.net/s/bs/dt-1.10.10,cr-1.3.0,fh-3.1.0,kt-2.1.0,r-2.0.0,rr-1.1.0/datatables.min.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs/dt-1.10.10,cr-1.3.0,fh-3.1.0,kt-2.1.0,r-2.0.0,rr-1.1.0/datatables.min.css"/>
*/
require_once "$w[flocation]/functionsWhmcs.php";
$stripe = stripeClient::billingGatewaySettings($w);
?>
<link rel="stylesheet" type="text/css" href="/directory/cdn/bootstrap/flippant.js-master/flippant.min.css">
<script src="https://js.stripe.com/v3/"></script>
<script src="/directory/cdn/bootstrap/flippant.js-master/flippant.min.js"></script>
<script type="text/javascript" src="/directory/cdn/bootstrap/bootbox/bootbox.min.js"></script>
<script src="/directory/cdn/bootstrap/colorbox/jquery.colorbox.min.js"></script>
<script>
    $('.colorbox').click(function(){
        function openColorBox(a){
            $.colorbox({iframe:true, width:"320px", height:"180px", href: a});
        }
        setTimeout(openColorBox(this.href), 50);
    });
</script>


<style>

    .flippant-back { width: auto !important;margin:20px 12% !important; }
    #flipper-content { margin: 0px auto; }
    .flippant-modal-dark { background: rgba(0,0,0,1.7) !important; }
    .sweet-alert.stripe_authorization{ padding: 0px !important; }

</style>
<script>


    var flip = flippant.flip
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnflip')) {
            e.preventDefault();

            var flipper = e.target.parentNode.parentNode;
            var href=$(e.target).attr("href");
            if (!$(e.target).attr("element")) { var element; } else { var element=$(e.target).attr("element"); }
            if (!$(e.target).attr("href")) { var href; } else { var href=$(e.target).attr("href"); }
            if (!$(e.target).html()) { var title='Member Quick View'; } else { var title=$(e.target).html(); }

            var back;
            var getdata;
            getdata='<div class="modal-lg"><div class="modal-header"><button aria-label="Close" data-dismiss="modal" type="button" class="btn btn-danger pull-right">x</button><h3 class="modal-title">'+title+'</h3></div><div id="flipper-content"></div></div>';

            if (e.target.classList.contains('card')) {
                var back = flip(flipper, getdata, 'card')
            } else {
                var back = flip(flipper, getdata, 'modal')
            }

            $('<iframe />').attr('src', href+"&noheader=1&print=yes").attr('name', 'iframe').prependTo('#flipper-content');

            back.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-danger')) {
                    back.close();
                }
            })

        }
    })
</script>

<script>
    $(document).ready(function() {
        $('#example').dataTable({
            paging: false,
            lengthChange: false,
			"language": {
				search: "_INPUT_",
				searchPlaceholder: `<?php echo $label['search_invoices']?>`,
			},			
            order: [[0,'desc']]
        });
        setTimeout(function() {
            $('.dataTables_filter input').addClass("form-control");
        }, 1000);
    });
</script>

<link rel="stylesheet" type="text/css" href="/directory/cdn/bootstrap/sweetalert/sweetalert2/dist/sweetalert2.css">
<script src="/directory/cdn/bootstrap/sweetalert/sweetalert2/dist/sweetalert2.min.js"></script>
<style>

</style>
<script>

    var that = {};

    function getSweetStateList(id)
    {
        
        $('#state').html('');
        $.ajax({
            url : '/ajaxsearch/get-states',
            type : "GET",
            data : {
                'country' : id
            },
            dataType: "json",
            success : function(data) {
                var options = '';

                for (var i = 0; i < data.length; i++) {

                    $('#state')
                        .append($("<option></option>")
                            .attr("value",data[i].value)
                            .text(data[i].title));
                }
            }
        });
    }

    $( document ).on( "click", ".deletecreditcard", function(e) {
        e.preventDefault();
        if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
        swal({
                title: `<?php echo $label['clear_credit_card']?>`,
                html: `<?php echo $label['clear_credit_card_description']?>`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: `<?php echo $label['yes_clear_credit_card']?>`,
                cancelButtonText: `<?php echo $label['no_clear_credit_card']?>`,
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm){
                    swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: `<?php echo $label["lead_processing_order"]?>`,   html:     `<?php echo $label["please_wait_processing"]?>` }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
                    $.ajax({
                        url: href,
                        type: "POST",
                        dataType: "json",
                        success: function(data){
                            var message=data['message'];
                            var message_title=data['message_title'];
                            var result=data['result'];
                            if (result=="success") {
                                swal({  type:"success",title: message_title,   html: decodeURIComponent(message),showCancelButton: false, showConfirmButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: `<?php echo $label['lead_close_window']?>`,
                                        closeOnConfirm: false,
                                        closeOnCancel: true,
                                        allowEscapeKey: false,
                                        allowOutsideClick: false
                                    },
                                    function(isConfirm){
                                        top.location.href=top.location.href;

                                    });


                            } else {
                                swal({  type:"error", title: message_title,   html: decodeURIComponent(message),   html: true });

                            }
                        },
                        error: function(e){
                            swal({  type:"error", title: '<?php echo $label["lead_js_error"]?>',   text: `<?php echo $label['form_connection_error']?>`,   html: true });
                        }
                    });

                } else {

                }
            })
    });


var cardElement         = {};
var stripeElement       = {};
var formStripeId        = "";
var isCardUpdate        = false; 
var currentInvoice      = 0;
var isRunning           = false;
var authorizedInvoice   = 0;

function setIsCardUpdate(){
    isCardUpdate = true;
}

function isStripeNewCard(){
    if($("#card-element").length > 0){
        return true;
    }else{
        return false;
    }
}

function mountCard(){
    stripeElement = Stripe("<?php echo trim($stripe['publishableKey']); ?>");
    var elements = stripeElement.elements();

    cardElement = elements.create('card', {
      hidePostalCode: true,
      style: {
          base: {
              iconColor: '#F99A52',
              color: '#32315E',
              lineHeight: '48px',
              fontWeight: 400,
              fontFamily: '"Helvetica Neue", "Helvetica", sans-serif',
              fontSize: '15px',

              '::placeholder': {
                  color: '#CFD7DF'
              }
          },
      }
    });

    cardElement.mount('#card-element');

    $( ".sweet-alert .confirm" ).removeClass("submitted");
    $( ".sweet-alert .confirm" ).prop("disabled","disabled");

    cardElement.addEventListener("change", function(event) {
        $( ".sweet-alert .confirm" ).removeClass("submitted");
        if (event.error || event.complete !== true) {
            $( ".sweet-alert .confirm" ).prop("disabled","disabled");
            if(event.error){
                $("#stripe_card_error_message").css('display','block');
            }
        } else {
            $( ".sweet-alert .confirm" ).prop("disabled","");
            $("#stripe_card_error_message").css('display','none');
        }
    });
}

function sendForm(invoiceId){
    var ccform = $(".sweet-alert form:eq(0)").serialize();

    $("#stripe_card_error_message").css('display','none');
    $( ".sweet-alert .confirm" ).addClass("submitted");

    if(invoiceId > 0){
        $("#collect"+invoiceId).attr("extra",ccform);
        $("#collect"+invoiceId).click();
    }else{
        $( formStripeId ).attr("extra",ccform);
        $( formStripeId ).click();
    }
    
}

function setOutcomeFunction(result) {
    var successElement  = document.querySelector('.success');
    var errorElement    = document.querySelector('.error');
    successElement.classList.remove('visible');
    errorElement.classList.remove('visible');
    $('.confirm').prop('disabled','');

    if(isCardUpdate === false){
        if (result.paymentIntent) {
            var form = $(".sweet-alert form:eq(0)");
            $( form ).append($('<input type="hidden" name="stripeToken" />').val(result.paymentIntent.payment_method));
            sendForm(authorizedInvoice);
        } else if (result.error) {
          //TODO: parse the stripe error and show it 
			$("#stripe_error").html('<?php echo $label["error_check_cc_info"]; ?><p><small><b>'+result.error.code+'<b/></small></p>').show();
        }
    }else{

        if (result.token) {
            var form = $(".sweet-alert form:eq(0)");
            $( form ).append($('<input type="hidden" name="stripeToken" />').val(result.token.id));
            $( form ).append($('<input type="hidden" name="cc_num" />').val(result.token.card.last4));
            $( form ).append($('<input type="hidden" name="cc_type" />').val(result.token.card.brand));
            $( form ).append($('<input type="hidden" name="cc_cvv" />').val(result.token.card.last4));
            $( form ).append($('<input type="hidden" name="month" />').val(result.token.card.exp_month));
            $( form ).append($('<input type="hidden" name="year" />').val(result.token.card.exp_year));
            sendForm(authorizedInvoice);
        } else if (result.error) {
            $("#stripe_error").html('<?php echo $label["error_check_cc_info"]; ?><p><small><b>'+result.error.code+'<b/></small></p>').show();
        }
    }
    
} 

function createStripeToken(invoiceId){
    $('.confirm').prop('disabled','disabled');

    //we removed the error message and added rotating animation to the save button
    $("#stripe_card_error_message").css('display','none');
    $( ".sweet-alert .confirm" ).addClass("submitted");
    $("#stripe_error").css('display','none');


    authorizedInvoice = invoiceId;
    if(isCardUpdate === false){
        $.ajax({
            url: "/wapi/widget",
            type: 'POST',
            data: {
                action:'get_secret',
                widget_name : 'WHMCS2 - Stripe Payment Intent',
                header_type : 'json',
                request_type : 'POST',
                payment_intent_bd_id:invoiceId
            },
            dataType: "json",
            success: function(response){
                var extraDetailsSignUp = 
                {
                    payment_method_data: 
                    {
                        billing_details: 
                        {
                            name: document.querySelector('#payment-form').querySelector("input[name='firstname']").value
                        }
                    }
                }
                stripeElement.handleCardPayment(response.client_secret,cardElement,extraDetailsSignUp).then(setOutcomeFunction);
            }
        });
    }else{
        var extraDetails        = {
            name: document.querySelector('#payment-form').querySelector("input[name='firstname']").value
        };
        stripeElement.createToken(cardElement,extraDetails).then(setOutcomeFunction);
    }
}

    $(".swal").on("click",function(e) {
        e.preventDefault();
        swal({
            showCancelButton: false, 
            showConfirmButton: false,
            closeOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false, 
            imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', 
            title: '<?php echo $label["lead_processing_order"]?>',   
            html:     '<?php echo $label["please_wait_processing"]?>' 
        }, 
            function() {   
                swal.disableButtons();   
                setTimeout(function() {    

                }, 3000); 
            }
        );

        that = $(this);
        formStripeId = '.swal';
        var message_title = '<?php echo $label["lead_processing_error"]?>';
        var message = '<?php echo $label["lead_processing_error_text"]?>';
        var result = "error";
        var extra = '';

        if(!$(this).attr("extra")) { 
            var extra = ''; 
        } else { 
            var extra = $(this).attr("extra"); 
            $(this).attr("extra","");  
        }

        $.ajax({
            url: $(this).attr('href')+"&"+extra,
            type: "POST",
            dataType: "json",
            success: function(data){
                var message = data['message'];
                var message_title = data['message_title'];
                var result = data['result'];
                var error = false;

                try {
                    message = decodeURIComponent(data['message']);
                } catch (err) {
                    error = true;
                    console.log('Error', err.message);
                }

                if (error) {
                    message = data['message'];
                }

                if (result=="success") {
                    swal({  
                            type:"success",
                            title: message_title, 
                            html: message,
                            showCancelButton: false, 
                            showConfirmButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: `<?php echo $label['lead_close_window']?>`,
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        },
                        function(isConfirm){
                            top.location.href=top.location.href;

                        });
                } else {
                    swal({  
                            title: message_title,   
                            html: message,
                            showCancelButton: true, 
                            showConfirmButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: `<?php echo $label["lead_save_cc"]?>`,
                            cancelButtonText: `<?php echo $label["cancel_message"]?>`,
                            closeOnConfirm: false,
                            closeOnCancel: true,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        },
                        function(isConfirm){
                            if (isConfirm){
                                if(isStripeNewCard() === true){
                                  createStripeToken(data['invoice_id']);
                                }else{
                                  sendForm(data['invoice_id']);
                                }
                            }
                        });

                    if(isStripeNewCard() === true){
                        mountCard();
                    }
                }
            }
        });
    });


    var url="";
    $(".bootbox").on( "click", function(e) {
        e.preventDefault();


        $.ajax({
            url: $(this).attr("href"),
            type: "get",
            dataType: "html",
            success: function(json){
                bootbox.dialog({
                    message: json,
                    closeButton: false,
                    show: false // We will show it manually later
                })
                    .on('shown.bs.modal', function() {

                        var validatorCode="";


                        $('.modal-body form:eq(0)').formValidation().on('success.form.fv', function(e,fvdata) {
                            // Prevent form submission
                            e.preventDefault();
                            $form     = $(e.target),
                                fv = $form.data('formValidation');
                            var values = $(this).serialize();

                            if (!$(this).attr("action")) { var action=''; } else { var action=$(this).attr("action"); }
                            if (!$(this).attr("method")) { var method='post'; } else { var method=$(this).attr("method"); }
                            if (!$(this).attr("id")) { var form_id=$(this).attr("id"); } else { var form_id=$(this).attr("id"); }
                            if (!$(this).attr("name")) { var newid=''; } else { var newid=$(this).attr("name"); }
                            if (!$(this).attr("form_action_type")) { var form_action_type='modal'; } else { var form_action_type=$(this).attr("form_action_type"); }
                            if (!$(this).attr("form_action_div")) { var form_action_div=$(this).parents(".content-container:eq(0)"); } else { var form_action_div=$(this).attr("form_action_div"); }
                            if (!$(this).attr("return_data_type")) { var return_data_type='json'; } else { var return_data_type=$(this).attr("return_data_type"); }

                            if ($("#"+form_id+"-notification").html()!="") { $("#"+form_id+"-notification").remove(); }
                            $(this).prepend('<div id="'+form_id+'-notification" class="alert"></div>');

                            var notification=$("#"+form_id+"-notification");

                            if (form_action_type=="" || form_action_type=="default") {
                                notification.html('<?php echo $label["lead_processing_order"]?>').addClass("alert-warning");
                                fv.defaultSubmit();
                                return true;
                            } else if (form_action_type=="modal") {
                                notification.html('<?php echo $label["saving_data_label"]?>').addClass("alert-warning");
                                $.ajax({
                                    url: action,
                                    type: method,
                                    data: values,
                                    dataType: "json",
                                    success: function(json){


                                        setTimeout(function(){
                                            $(".modal-header .close").click();
                                            bootbox.alert('<div id="billing_add_credit_card-notification" class="alert alert-success"><?php echo $label["changes_saved_successfully"]?></div>');
                                            setTimeout(function(){
                                                $(".modal-content .close").click();
                                            }, 2000);
                                        }, 500);


                                    },error: function(e){
                                        notification.html('<?php echo $label["form_connection_error"]?>');
                                        notification.addClass("alert-warning").removeClass("alert-success").removeClass("alert-warning").fadeIn();
                                    }
                                });
                                return true;
                            }
                        });



                    }).on('hide.bs.modal', function(e) {
                    // Bootbox will remove the modal (including the body which contains the login form)


                })
                    .modal('show');


            }
        });
    });


</script>




<script>
    $( document ).on( "click", ".paylink", function(e) {
        e.preventDefault();
        if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }



        swal({  type:"info", title: $(this).attr("message_title"),   html: $(this).attr("message"),
                showCancelButton: true,
                confirmButtonColor: 'rgb(48, 133, 214)',
                confirmButtonText: 'Email Payment Link',
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm) {
                    swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: '<?php echo $label["lead_processing_order"]?>',   html:     '<?php echo $label["please_wait_processing"]?>' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
                    $.ajax({
                        url: href,
                        type: "GET",
                        dataType: "json",
                        success: function(json){
                            if (!json['result']) { json['result']="error"; }
                            if (!json['message']) { json['message']="<?php echo $label['request_processed_successfully']?>"; }
                            if (!json['message_title']) { json['message_title']="<?php echo $label['action_incomplete']?>"; }
                            if (json['result']=="success") {


                                swal({  type:"success", title: json['message_title'],   text: json['message'],   html: true });

                            } else {
                                swal({  type:"error", title: json['message_title'],   text: json['message'],   html: true });

                            }
                        },
                        error: function(e){
                            swal({  type:"error", title: '<?php echo $label["lead_js_error"]?>',   text: "<?php echo $label['form_connection_error']?>",   html: true });
                        }
                    });



                    return false;
                } else {
                    return false;
                }
            });
        return false;

    });


    $(document).on( "click", ".invoicedetails", function(event) {
        event.preventDefault();
        var values = $(this).attr("extra");
        var form_id = $(this).attr("id");

        swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '/directory/cdn/images/bars-loading.gif', title: '<?php echo $label["lead_processing_order"]?>',   html:     '<?php echo $label["please_wait_processing"]?>' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
        var message_title='Order Error';
        var message='Please double check your billing information and try again.';
        var result="error";
        $.ajax({
            url: $(this).attr('href'),
            type: "get",
            data: values,
            dataType: "json",
            success: function(data){
                var message=data['message'];
                var message_title=data['message_title'];
                var result=data['result'];
                if (!data['redirect_url']) { var url=""; } else { var url=data['redirect_url']; }

                swal({  type:result, title: message_title,   html: message,showConfirmButton: false,
                    allowEscapeKey: true,
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: "Close Window",
                    showCancelButton: true,
                    closeOnCancel: true,
                    allowOutsideClick: true });
                var search_count=message.search("form");
                if(search_count>0) { $(".sweet-alert form:eq(0)").submit(); }
                else if (url!="") { setTimeout(function(){ window.location=url; }, 1500); }

            },
            error: function(e){
                swal({  type:"error", title: '<?php echo $label["lead_js_error"]?>',   text: '<?php echo $label["form_connection_error"]?>',   html: true });
            }
        });
    });

    $(document).on( "click", ".capturepayment", function(event) {
        event.preventDefault();
        
        if(isRunning === true){
            return false;
        }

        isRunning = true;

        var values = $(this).attr("extra");
        var calconly = $(this).attr("calconly");
        that = $(this);
        formStripeId = '.capturepayment';
        var form_id = $(this).attr("id");

        swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '/directory/cdn/images/bars-loading.gif', title: '<?php echo $label["lead_processing_order"]?>',   html:     '<?php echo $label["please_wait_processing"]?>' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
        var message_title='<?php echo $label["lead_processing_error"]?>';
        var message='<?php echo $label["lead_processing_error_text"]?>';
        var result="error";
        $.ajax({
            url: $(this).attr('href')+"&calconly="+calconly,
            type: "get",
            data: values,
            dataType: "json",
            success: function(data){
                
                var message         = data['message'];
                var message_title   = data['message_title'];
                var result          = data['result'];
                isRunning           = false;

                if (!data['redirect_url']) { var url=""; } else { var url=data['redirect_url']; }
                if (result=="success") {
                    swal({  type:result, title: message_title,   html: message,showConfirmButton: false,
                        allowEscapeKey: true,
                        cancelButtonColor: '#3085d6',
                        showCancelButton: false,
                        closeOnCancel: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false });
                    var search_count=message.search("form");
                    if(search_count>0) { $(".sweet-alert form:eq(0)").submit(); }
                    else if (url!="") { setTimeout(function(){ window.location=url; }, 1500); }
                } else if (result=="prompt") {

                    swal({  title: message_title, html: message,showCancelButton: true, showConfirmButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: `<?php echo $label["lead_save_cc"]?>`,
                            cancelButtonText: `<?php echo $label["cancel_message"]?>`,
                            closeOnConfirm: false,
                            closeOnCancel: true,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        },
                        function(isConfirm){
                            if (isConfirm){
                                if(isStripeNewCard() === true){
                                    currentInvoice = 0;
                                    createStripeToken(data['invoice_id']);
                                }else{
                                  sendForm(data['invoice_id']);
                                }
                            } else {
                                swal(`<?php echo $label['lead_action_cancelled']?>`, `<?php echo $label['lead_no_action_taken']?>`, "success");
                            }
                        });
                        
                        if(isStripeNewCard() === true){
                            mountCard();
                        }

                } else if (result=="confirm") {

                    let lead_continue_purchase = `<?php echo $label["lead_continue_purchase"]?>`;
                    let lead_cancel_purchase = `<?php echo $label['lead_cancel_purchase']?>`;                    

                    swal({  type:"warning", title: message_title,   html: message,showCancelButton: true, showConfirmButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: lead_continue_purchase,
                            cancelButtonText: lead_cancel_purchase,
                            closeOnConfirm: false,
                            closeOnCancel: true,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        },
                        function(isConfirm){
                            if (isConfirm){
                                $('#'+form_id).attr("calconly","false");
                                $('#'+form_id).click();
                            } else {
                                swal(`<?php echo $label['lead_action_cancelled']?>`, `<?php echo $label['lead_no_action_taken']?>`, "success");
                            }
                        });

                } else {
                    
                    
                    if(result == "stripe_authorization"){
                        currentInvoice  = data['invoice_id'];
                        var stripe = Stripe("<?php echo trim($stripe['publishableKey']); ?>");
                        stripe.handleCardPayment(
                          message,
                        ).then(function(result) {
                            var href        = $("#collect"+currentInvoice).attr('href');
                            var hrefNew     = href+'&stripe_authorize=true';

                            $("#collect"+currentInvoice).attr('href',hrefNew);
                            $("#collect"+currentInvoice).click();
                            setTimeout(function() {
                                $("#collect"+currentInvoice).attr('href',href);
                            },100);
                        });
                    }else {
                        $("#calconly").val('true');
                        swal({  type:result, title: message_title,   html: decodeHtml(message)});
                    }

                }
            },
            error: function(e){
                swal({  type:"error", title: '<?php echo $label["lead_js_error"]?>',   text: '<?php echo $label["form_connection_error"]?>',   html: true });
                isRunning = false;
            }
        });
    });
</script>