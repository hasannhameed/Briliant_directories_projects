<?php 
require_once $w['flocation']."/functionsWhmcs.php";
$stripe = stripeClient::billingGatewaySettings($w);
?>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<!-- Include Bootstrap Datepicker -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js"></script>

<link rel="stylesheet" href="/bootstrap/formvalidation/current/dist/css/formValidation.min.css"/>
<script src="/bootstrap/formvalidation/current/dist/js/formValidation.min.js"></script>
<script src="/bootstrap/formvalidation/current/dist/js/framework/bootstrap.min.js"></script> 

<script src="/bootstrap/flippant.js-master/flippant.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript">
    var isHttpProtocol  = 1;
    var cardElement     = {};
    var stripeElement   = {};
    var formStripeId    = "";
    var that            = {};
    var currentToken    = "";

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
	
	$(document).ready(function() {
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});

function sendForm(){
    var ccform = $(".sweet-alert form:eq(0)").serialize();
    $( that ).attr("extra",ccform);
    $( that ).click();
}

function setOutcomeFunction(result) {
    var successElement  = document.querySelector('.success');
    var errorElement    = document.querySelector('.error');
    successElement.classList.remove('visible');
    errorElement.classList.remove('visible');
    
    $('.confirm').prop('disabled','');

    if (result.token) {
        var form = $(".sweet-alert form:eq(0)");
        $( form ).append($('<input type="hidden" name="stripeToken" />').val(result.token.id));
        $( form ).append($('<input type="hidden" name="cc_num" />').val(result.token.card.last4));
        $( form ).append($('<input type="hidden" name="cc_type" />').val(result.token.card.brand));
        $( form ).append($('<input type="hidden" name="cc_cvv" />').val(result.token.card.last4));
        $( form ).append($('<input type="hidden" name="month" />').val(result.token.card.exp_month));
        $( form ).append($('<input type="hidden" name="year" />').val(result.token.card.exp_year));

        sendForm();

    } else if (result.error) {
      //TODO: parse the stripe error and show it 
    }
} 

function createStripeToken(){
    $('.confirm').prop('disabled','disabled');

    //we removed the error message and added rotating animation to the save button
    $("#stripe_card_error_message").css('display','none');
    $( ".sweet-alert .confirm" ).addClass("submitted");
    $("#stripe_error").css('display','none');


    var paymentFunctionForm = document.querySelector('#payment-form');
    var extraDetails = {
        name: paymentFunctionForm.querySelector("input[name='firstname']").value
    };

    stripeElement.createToken(cardElement,extraDetails).then(setOutcomeFunction);
}
  
function decodeHtml(html) {
  var txt = document.createElement("textarea");
  txt.innerHTML = html;
  return txt.value;
}

	var waitForEl = function(selector, callback, count) {
  if (jQuery(selector).children('option').length) {
    callback();
  } else {
    setTimeout(function() {
      if(!count) {
        count=0;
      }
      count++;
      
      if(count<30) {
        waitForEl(selector,callback,count);
      } else {return;}
    }, 100);
  }
};

var selector = $("#paymentype");
  
  
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
  
    
  
  function updatesummary() {
    $("#buttonrow").hide();
    
    
    $('#migrate_legacy_subscription').formValidation('revalidateField', 'pid');
    
    $("#ordersumm").html("<h2 style='color:#ccc;'>Updating Order Summary...</h2>").fadeIn();
    
	<?php if ($_ENV['clientid'] > 0) { ?>
    	var userid=<?php echo $_ENV['clientid']; ?>;
	  <?php } else { ?>
	  	var userid="1";
	  <?php } ?>
    var pid=$("#pid").val();
    if (!$("#paymentype").val()) { var billingcycle=''; } else { var billingcycle=$("#paymentype").val(); }
    if (!$("#promocode").val()) { var promocode=''; } else { var promocode=$("#promocode").val(); }
    if (!$("#priceoverride").val()) { var priceoverride=''; } else { var priceoverride=$("#priceoverride").val(); }
    var varsw="userid="+userid+"&pid[]="+pid+"&billingcycle[]="+billingcycle+"&qty[]=1&domain[]=&priceoverride[]="+priceoverride+"&adminauth=addorder&widget=WHMCS%20-%20AdminAuth&apitype=html";
    
    $.get("/admin/go.php", varsw,function(data){
        $("#ordersumm").html(data).fadeIn("fast");
    $("#buttonrow").show().fadeIn();
    });
}	
	
$(document).ready(function(){
  updatesummary();
});
	var hasOptions = 0;
			
function getProductCycles(id,start)
            { 
        $("#billingcyclediv").hide();
        
      if (id>0) {
        $("#pid").attr("size","");
        $('#paymentype').val('');
        $('#paymentype').html('');
                $.ajax({
                    url : 'go.php',
                    type : "GET",
                    data : {
                        'pid' : id,
            'widget' : 'WHMCS2 - Invoice Actions',
            'action' : 'getproducts',
            'apitype' : 'json'
                    },
                    dataType: "json",
                    success : function(data) {
                        var options = '';
            if (data.length>0) {
                        for (var i = 0; i < data.length; i++) {
              
						   $('#paymentype')
					 .append($("<option></option>")
					 .attr("value",data[i].value)
					 .text(data[i].title)); 
                        }
            
            $("#paymentype option:first").attr('selected','selected');
            $("#billingcyclediv").show();
            $('#migrate_legacy_subscription').formValidation('revalidateField', 'billingcycle');
			hasOptions = 1;
            }
          else {
          	  hasOptions = 0;
			  $('#paymentype')
			   .append($("<option></option>")
			   .attr("value",'')
			   .text('')); 
				$("#billingcyclediv").hide();
			  }
            
          }
                });
        
      } else {
        $("#pid").attr("size",$("#pid").attr("start_size"));
        $('#paymentype').val('');
        $('#paymentype').html('');
        $("#billingcyclediv").hide();
        $('#migrate_legacy_subscription').formValidation('revalidateField', 'billingcycle');
      }
  
		if (id > 0) {
        setTimeout(function() {  
			waitForEl(selector, function() {
				updatesummary(); 
			})
		}, 500);
		} else {
			updatesummary(); 
		}
        
            }
                  
  
   
  
  $( document ).on( "click", ".resetbutton", function(e) {
    
    $("#migrate_legacy_subscription").data('formValidation').resetForm();
    setTimeout(function() { 
      getProductCycles(0); 
      setTimeout(function() {  updatesummary(); }, 500);  
    }, 500); 
    
    
  });
  
  $(".updatecreditcard").on("click",function(e) { 
        e.preventDefault();
          swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:true,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
          that=$(this);
          var message_title='Order Error';
          var message='Please double check your billing information and try again.';
          var result="error";
          var extra='';
          
          if(!$(this).attr("extra")) { 
            var extra=''; 
          } else { 
            var extra=$(this).attr("extra"); 
            $(this).attr("extra","");  
          }

          $.ajax({
              url: $(this).attr('href')+"&"+extra,
              type: "POST",
              dataType: "json",
              success: function(data){
                      var message=data['message'];
                      var message_title=data['message_title'];
                      var result=data['result'];
                       if (result=="success") { 
                        swal({  type:"success",title: message_title,   html: decodeHtml(message),showCancelButton: false, showConfirmButton: true,
                            confirmButtonColor: '#3085d6',   
                            cancelButtonColor: '#d33',
                            confirmButtonText: "Close Window",
                            closeOnConfirm: false,
                            closeOnCancel: true,
                            allowEscapeKey: false,
                            allowOutsideClick: true
                          },
                          function(isConfirm){
                            window.location.href=window.location.href;
                          });
                          
                      } else {
                        swal({  title: message_title,   html: decodeHtml(message),showCancelButton: true, showConfirmButton: true,
                            confirmButtonColor: '#3085d6',   
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Save credit card & continue',
                            cancelButtonText: "Cancel",
                            closeOnConfirm: false,
                            closeOnCancel: true,
                            allowEscapeKey: false,
                            allowOutsideClick: true
                          },
                          function(isConfirm){
                            if (isConfirm){
                                
                                if(isStripeNewCard() === true){
                                  createStripeToken();
                                }else{
                                  sendForm();
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
  
  
   $( document ).on( "click", ".deletecreditcard", function(e) {
                    e.preventDefault();
                    if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
                    swal({
                    title: "Clear Credit Card?",
                    html: "This will clear the credit card on file.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, clear credit card',
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                  },
                  function(isConfirm){
                    if (isConfirm){
                      swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
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
                                      confirmButtonText: "Close Window",
                                      closeOnConfirm: false,
                                      closeOnCancel: true,
                                      allowEscapeKey: false,
                                      allowOutsideClick: false
                                    },
                                    function(isConfirm){
                                      window.location.href=window.location.href;
                                      
                                    });
                                        
                                    
                                } else {
                                  swal({  type:"error", title: message_title,   html: decodeURIComponent(message),   html: true });
                                   
                                }  
                              },
                              error: function(e){ 
                                  swal({  type:"error", title: 'Javascript Error!',   text: "A connection error occurred while saving. Please try to save again.",   html: true });
                                }
                          });
                        
                    } else {
                        
                    }
                  })
              }); 
    
    $('#migrate_legacy_subscription').formValidation({"framework":"bootstrap",
    "fields":{
      "priceoverride": {
                validators: {
                    numeric: {
                        message: 'The price must be a number',
                        transformer: function($field, validatorName, validator) {
                            var value = $field.val();
                            return value.replace(',', '');
                        }
                    },
          greaterThan: {
                        value: 0.01,
                        message: 'Enter a price greater than 0 without commas.'
                    }
                }
            },
      "nextduedate": {
            validators: {
              notEmpty: {
                message: 'A valid date is required'
              },
              date: {
                format: '<? if ($w[default_date_format]=="d/m/Y") { ?>DD/MM/YYYY<? } else { ?>MM/DD/YYYY<? } ?>',
                min: '01/01/2010',
                message: 'The date is not a valid or out of range'
              }
            }
           },
      "pid":{"validators":{"notEmpty":{"message":"Required"}}},"billingcycle":{"validators":{"notEmpty":{"message":"Required"}}}}}).on('success.form.fv', function(e,fvdata) {
		
				// Prevent form submission
                    e.preventDefault();
		            $form     = $(e.target),
                    fv = $form.data('formValidation');
                    var values = $(this).serialize();
		
                    if (!$(this).attr("action")) { var action=''; } else { var action=$(this).attr("action"); }
                    if (!$(this).attr("method")) { var method='post'; } else { var method=$(this).attr("method"); }
                    if (!$(this).attr("id")) { var form_id='migrate_legacy_subscription'; } else { var form_id=$(this).attr("id"); }
                    if (!$(this).attr("name")) { var newid=''; } else { var newid=$(this).attr("name"); }
                    if (!$(this).attr("form_action_type")) { var form_action_type='notification'; } else { var form_action_type=$(this).attr("form_action_type"); }
                    if (!$(this).attr("form_action_div")) { var form_action_div="#load-html"; } else { var form_action_div=$(this).attr("form_action_div"); }
                    if (!$(this).attr("return_data_type")) { var return_data_type='json'; } else { var return_data_type=$(this).attr("return_data_type"); }
		
		swal({  title: "Confirm",   html: 'Are you sure you want to process this order?'
				 ,showCancelButton: true, showConfirmButton: true,
				 confirmButtonColor: '#3085d6',   
				 cancelButtonColor: '#d33',
				 confirmButtonText: 'Submit Order',
				 cancelButtonText: "Cancel",
				 closeOnConfirm: false,
				 closeOnCancel: true,
				 allowEscapeKey: false,
				 allowOutsideClick: false
				},
				function(isConfirm){
					if (isConfirm){
						swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
						              
                   

                    if ($("#migrate_legacy_subscription-notification").html()!="") { $("#migrate_legacy_subscription-notification").remove(); }
                    $("#migrate_legacy_subscription").prepend('<div id="migrate_legacy_subscription-notification" class="alert"></div>');

                    var notification=$("#migrate_legacy_subscription-notification");

                    notification.html('Processing Request...').addClass("alert-warning");
                  $.ajax({
                      url: action,
                      type: method,
                      data: values,
                      dataType: return_data_type,
                      success: function(data){
              if (data['result']=="success") {
                var id=window.parent.document.getElementById("btn-danger");
                notification.html(data['message']);
                                notification.addClass("alert-success").removeClass("alert-warning").fadeIn(); 
                setTimeout(function() {  
                      
                    if (!data['redirect_url']) { var url=""; } else { var url=data['redirect_url']; window.location=url; }
                    }, 1000);
                          
                
              } else {
                  notification.html(data['message']);
                                notification.addClass("alert-warning").removeClass("alert-success").fadeIn(); 
              }                                        

                      },
                      error: function(e){
                              notification.html('A connection error occurred while saving. Please try to save again.');
                              notification.addClass("alert-warning").removeClass("alert-success").fadeIn(); 
                            }
                  });

                /// End Ajax If
					}
				});
		
            }).bind('keydown', function(event) {
          if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
              case 's':
              event.preventDefault();
              $(this).submit();
              break;
            }
          }
        });

                                  
$("#myform-html").submit(function(event) {
    event.preventDefault();
      var values = $(this).serialize();
      if(!$(this).attr("extra")) { } else { var values=values+"&"+$(this).attr("extra"); }
      swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing Order',   html:     'Please wait a moment while we process your order...' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
        var message_title='Order Error';
                var message='Please double check your billing information and try again.';
                var result="error";
                $.ajax({
                    url: $(this).attr('action'),
                    type: "get",
                    data: values,
                    dataType: "json",
                    success: function(data){
                            var message=data['message'];
                            var message_title=data['message_title'];
                            var result=data['result'];
                            if (result=="success") { 
                              swal({  type:result, title: message_title,   html: message,showConfirmButton: false,
                                  allowEscapeKey: false,
                  allowOutsideClick: false });
                            } else if (result=="prompt") { 
                                swal({ title: message_title, html: message,showCancelButton: true, showConfirmButton: true,
                                  confirmButtonColor: '#3085d6',   
                                  cancelButtonColor: '#d33',
                                  confirmButtonText: 'Save credit card & continue',
                                  cancelButtonText: "Cancel",
                                  closeOnConfirm: false,
                                  closeOnCancel: true,
                                  allowEscapeKey: false,
                  allowOutsideClick: false
                                },
                                function(isConfirm){
                                  if (isConfirm){
                                      var ccform=$( ".sweet-alert form:eq(0)" ).serialize();
                                      $('#myform-html').attr("extra",ccform);
                                      $('#myform-html').submit();
                                  } else {
                                    swal("Action Cancelled!", "No changes were saved", "success");
                                  }
                                });

                            } else if (result=="confirm") { 
                                swal({  title: message_title,   html: message,showCancelButton: true, showConfirmButton: true,
                                  confirmButtonColor: '#3085d6',   
                                  cancelButtonColor: '#d33',
                                  confirmButtonText: 'Yes, continue with purchase',
                                  cancelButtonText: "No, cancel",
                                  closeOnConfirm: false,
                                  closeOnCancel: true,
                                  allowEscapeKey: false,
                  allowOutsideClick: false
                                },
                                function(isConfirm){
                                  if (isConfirm){
                                     $('#myform-html').attr("extra","");
                                     $("#calconly").val('false');
                                      $('#myform-html').submit();
                                  } else {
                                    swal("Action Cancelled!", "No action taken :)", "success");
                                  }
                                });

                            } else {    
                            swal({  type:result, title: message_title,   html: decodeHtml(message),
                                  allowEscapeKey: false,
                  allowOutsideClick: false});
                            }    
                    },
                    error: function(e){
                        swal({  type:"error", title: 'Javascript Error',   text: 'A connection error occurred while submitting your request. Please try to save again.',   html: true });
                    }
                });
});

</script>


<?=$_SERVER[form_validator_scripts]?>    
    
       
<script>
   
   $(document).ready(function(){
	   
              
  $('#dateRangePicker')
        .datepicker({
            format: '<? if ($w[default_date_format]=="d/m/Y") { ?>dd/mm/yyyy<? } else { ?>mm/dd/yyyy<? } ?>',
      autoclose: true
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#dateRangeForm').formValidation('revalidateField', 'date');
        });

     
   var flip = flippant.flip
 document.addEventListener('click', function(e) {
  if (e.target.classList.contains('btnflip')) {
    e.preventDefault();

    var flipper = e.target.parentNode.parentNode;
    var href=$(e.target).attr("href");
    if (!$(e.target).attr("element")) { var element; } else { var element=$(e.target).attr("element"); }
    if (!$(e.target).attr("href")) { var href; } else { var href=$(e.target).attr("href"); }
  if (!$(e.target).attr("title")) { var title=$(e.target).html(); } else { var title=$(e.target).attr("title"); }

    next = $(e.target).parents("tr").next().attr('href');
    prev = $(e.target).parents("tr").prev().attr('href');
    
    var back;
    var getdata;
    getdata='<div class="dataTable-pagination" style="position:absolute;width:100%;margin-top:100px;"><a href="'+prev+'&noheader=1" target="iframe" class="flipper-prev btnflip btn btn-default pull-left" title="Previous"><span class="glyphicon glyphicon-chevron-left"></span></a><a href="'+next+'&noheader=1" target="iframe" title="Next" class="flipper-next btn btn-default pull-right"><span class="glyphicon glyphicon-chevron-right"></span></a></div><div class="modal-lg"><div class="modal-header"><button aria-label="Close" data-dismiss="modal" type="button" class="btn btn-danger pull-right">x</button><h3 class="modal-title">'+title+'</h3></div><div id="flipper-content"></div></div>';
    
    
    if (e.target.classList.contains('card')) {
      var back = flip(flipper, getdata, 'card')
    } else {
      var back = flip(flipper, getdata, 'modal')
    }
    
      $('<iframe />').attr('src', href+"&noheader=1&print=yes").attr('name', 'iframe').prependTo('#flipper-content');
   
    back.addEventListener('click', function(e) {      
      if (e.target.classList.contains('btn-danger')) {
          back.close();
      obj=back;
      }  
    })

  }
})
 
 
$(document).on('click','.flipper-next',function(){
    
    var prevOne = current.attr("href");
        newCurrent = current.parents("tr").next().find('.member_links a:first-child').attr('href');
    var nextOne = current.parents("tr").next().next().find('.member_links a:first-child').attr('href');
    current = current.parents("tr").next().find('.member_links a:first-child');
    $(this).attr("href",nextOne);
    $('.flipper-prev').attr("href",prevOne);
});

$(document).on('click','.flipper-prev',function(){
    
    var prevOne = current.parents("tr").prev().prev().find('.member_links a:first-child').attr('href');
        newCurrent = current.parents("tr").prev().find('.member_links a:first-child').attr('href');
    var nextOne = current.attr("href");
    current = current.parents("tr").prev().find('.member_links a:first-child');
    $(this).attr("href",prevOne);
    $('.flipper-next').attr("href",nextOne);
});

          
     
      var row=90000;
     
       
      startNested(row);            
            
     });
     
     
      function startNested(row) {
            var row=90000;
           
    $('.filterable .btn-filter').click(function(){
        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
        /* Ignore tab key */
        var code = e.keyCode || e.which;
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');
        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
            var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });

     
              $(".swal").on("click",function(e) { 
                  e.preventDefault();
                    swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
          var that=$(this);
                    var message_title='Order Error';
                    var message='Please double check your billing information and try again.';
                    var result="error";
                    var extra='';
                    if(!$(this).attr("extra")) { var extra=''; } else { var extra=$(this).attr("extra"); $(this).attr("extra","");  }
                    
                    $.ajax({
                        url: $(this).attr('href')+"&"+extra,
                        type: "POST",
                        dataType: "json",
                        success: function(data){
                                var message=data['message'];
                                var message_title=data['message_title'];
                                var result=data['result'];
                                 if (result=="success") { 
                                    swal({  type:"success",title: message_title,   html: decodeURIComponent(message),showCancelButton: true, showConfirmButton: false,
                                      confirmButtonColor: '#3085d6',   
                                      cancelButtonColor: '#d33',
                                      confirmButtonText: "Close Window",
                                      closeOnConfirm: false,
                                      closeOnCancel: true,
                                      allowEscapeKey: false,
                                      allowOutsideClick: false
                                    });
                   startNested(90000);
                                } else {
                                  swal({  title: message_title,   html: decodeURIComponent(message),showCancelButton: true, showConfirmButton: true,
                                      confirmButtonColor: '#3085d6',   
                                      cancelButtonColor: '#d33',
                                      confirmButtonText: 'Save credit card & continue',
                                      cancelButtonText: "Cancel",
                                      closeOnConfirm: false,
                                      closeOnCancel: true,
                                      allowEscapeKey: false,
                                      allowOutsideClick: false
                                    },
                                    function(isConfirm){
                                      if (isConfirm){
                                          var ccform=$( ".sweet-alert form:eq(0)" ).serialize();
                                          that.attr("extra",ccform);
                                          that.click();
                                      } else {
                                        $.ajax({url: '/admin/whmcs2.php',type: "GET",data: { apitype: "html", view: "gatewayssdfsdf<?=$_REQUEST[widget]?>"},dataType: "html",
                                   success: function(reloaddata){  
                                            $('#migrate_legacy_subscription').html(reloaddata);
                                            
                                     }});       
                  
                                      }
                                    });
                               }     
                             }
                        });
                });
            
           
                
  var url="";
        $(".bootbox").on( "click", function(e) {
            e.preventDefault();
          
                          var url=$(this).attr("href")+"&validate=formValidator";
          
          
          
          
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
                                console.log(url);
                                    $.ajax({url: url,type: "GET",dataType: "json",
                                   success: function(str){  
                     var validatorCode =  str;
                  
                    $('.modal-body form:eq(0)').formValidation(validatorCode).on('success.form.fv', function(e,fvdata) {
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
                    notification.html('Processing Request...').addClass("alert-warning");
                    fv.defaultSubmit();
                    return true;
                 } else if (form_action_type=="modal") { 
                        notification.html('Saving Data...').addClass("alert-warning");
                          $.ajax({
                              url: action,
                              type: method,
                              data: values,
                              dataType: "json",
                              success: function(json){  
                  notification.html('Changes Saved... Updating content.').removeClass("alert-warning").addClass("alert-success");
                                   setTimeout(function(){ 
                                    $.ajax({url: '/admin/go.php',type: "POST",data: { noheader: "1", view: "<?=$_REQUEST[view]?>", apitype: "widget", widget: "WHMCS2 - Admin Template" },dataType: "html",
                                   success: function(reloaddata){  
                                            $('#ajax-content').html(reloaddata);
                                            startNested(90000);
                                     }});       
                  }, 1000);                                       
                                   setTimeout(function(){  $(".modal-header .close").click(); }, 500);
                                     
                              },error: function(e){
                              notification.html('A connection error occurred while saving. Please try to save again.');
                              notification.addClass("alert-warning").removeClass("alert-success").removeClass("alert-warning").fadeIn(); 
                            }
             });
                         return true;
                 } 
        });
                     
                     }
                       });
                  
                  }).on('hide.bs.modal', function(e) {
                                      // Bootbox will remove the modal (including the body which contains the login form)
                                      
                                  })
                                  .modal('show');
                  
                                
            }
                           });
            });
          
                        
            <?=$_SERVER[form_validator_code]?>  
            
             $( document ).on( "click", ".deletenested", function(e) {
                    e.preventDefault();
                    if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
                    if (!$(this).attr("element")) { var element="tr"; } else { var element=$(this).attr("element"); }
                    var li = $(this).closest(element);
                        $.ajax({
                              url: href,
                              type: "get",
                              dataType: "json",
                              success: function(json){
                                var id=json['result_id'];
                                if (!json['result']) { json['message']="error"; json['result']="error"; }
                                if (!json['message']) { json['message']="Your requested processed successfully"; }
                if (json['result']=="success") {  
                                         swal({
                                            title: json['message_title'],
                                            text: json['message'],
                                            type: "warning",
                                                html: true,
                                            showCancelButton: true,
                                            confirmButtonColor: '#DD6B55',
                                            confirmButtonText: 'Yes, delete',
                                            cancelButtonText: "No, cancel",
                                            closeOnConfirm: false,
                                            closeOnCancel: true
                                          },
                                          function(isConfirm){
                                            if (isConfirm){
                                                
                                                $.ajax({
                                                      url: href+"&factionconfirm=1&factionconfirmsub=1",
                                                      type: "get",
                                                      dataType: "json",
                                                      success: function(json){
                                                        var id=json['result_id'];
                                                        if (!json['result']) { json['message']="error"; json['result']="error"; }
                                                        if (!json['message']) { json['message']="Your requested processed successfully"; }
                                                        if (json['result']=="success") {  
                                                          li.fadeOut(500, function(){ li.remove(); });                          
                                                          swal({  type:"success", title: json['message_title'],   text: json['message'],   html: true });
                                                            $.get('go.php',{ method: "<?=$_REQUEST[method]?>", apitype: "widget", widget: "Admin - Form Builder Nestable Container", is_master: "<?=$_REQUEST[is_master]?>", website_id: "<?=$_REQUEST[website_id]?>", formid: "<?=$_REQUEST[formid]?>" },function(data){ $('#ajax-content').html(data);
                                                                startNested(row);
                                                            }); 
                                                        }
                                                      
                                                      }
                                                 });
                                                
                                                }
                                             });      
                                } else if (json['result']=="confirm") {   
                  bootbox.dialog({
                                      message: json['message'],
                                      title: json['message_title'],
                                      buttons: {
                                        success: {
                                          label: "Proceed & Continue &raquo;",
                                          className: "btn-success",
                                          callback: function() {
                                             var form_values=$(".bootbox-body form").serialize();
                                             var hrefnew=href+"&factionconfirm=1&factionconfirmsub=1&"+form_values;                                             
                                             $.post(hrefnew, { matt:1 },function(confirmjson){ 
                                              swal({  type:"success", title: confirmjson['message_title'],   text: confirmjson['message'],   html: true });
      
                                            },"json");
                                            
                                              $.get('go.php',{ apitype: "widget", widget: "Admin - Form Builder Nestable Container", is_master: "<?=$_REQUEST[is_master]?>", website_id: "<?=$_REQUEST[website_id]?>", formid: "<?=$_REQUEST[formid]?>", method: "<?=$_REQUEST[method]?>" },function(data){ $('#ajax-content').html(data);
                                                  startNested(row);
                                              });
                                            
                                          }
                                        },
                                        danger: {
                                          label: "Cancel Action",
                                          className: "btn-danger",
                                          callback: function() {

                                          }
                                        }
                                        <? /*
                                        ,
                                        main: {
                                          label: "Delete Membership Level & Members",
                                          className: "btn-primary",
                                          callback: function() {
                                            var hrefnew=href+"&factionconfirm=1&factionconfirmsub=0";                                             
                                             $.get(hrefnew);
                                                 swal({  type:"success", title: "Delete Complete!",   text: "Selected items are now deleted.",   html: true });
                                                 $.get('go.php',{ apitype: "widget", widget: "Admin - Form Builder Nestable Container", is_master: "<?=$_REQUEST[is_master]?>", website_id: "<?=$_REQUEST[website_id]?>", form_name: id },function(data){ $('#ajax-content').html(data);
                                                    startNested(row);
                                                });
                                          }
                                         }
                                         */ ?>
                                         
                                       }
                                     });
                        } else {
                                  swal({  type:"error", title: "Error!",   text: json['message'],   html: true }); 
                                } 

                              },
                              error: function(e){ 
                                  swal({  type:"error", title: 'Javascript Error!',   text: "A connection error occurred while saving. Please try to save again.",   html: true });
                                }
                       });
              });
        $(document).on( "click", ".capturepayment", function(event) {
    event.preventDefault();
      var values = $(this).attr("extra");
          var form_id = $(this).attr("id");
      
      swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '/directory/cdn/images/bars-loading.gif', title: 'Processing Order',   html:     'Please wait a moment while we process your order...' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
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
                            if (result=="success") { 
                
                 setTimeout(function(){ 
                                    $.ajax({url: '/admin/go.php',type: "POST",data: { noheader: "1", view: "<?=$_REQUEST[view]?>", apitype: "widget", widget: "WHMCS2 - Admin Template" },dataType: "html",
                                   success: function(reloaddata){  
                                            $('#ajax-content').html(reloaddata);
                                            startNested(90000);
                                     }});       
                  }, 1000);
                
                              swal({  type:result, title: message_title,   html: message,showConfirmButton: false,
                                  allowEscapeKey: true,
                    cancelButtonColor: '#3085d6',   
                    cancelButtonText: "OK",
                    showCancelButton: true, 
                    closeOnCancel: true,
                  allowOutsideClick: true });
                  
                            } else if (result=="prompt") { 
                
                                swal({  title: message_title, html: message,showCancelButton: true, showConfirmButton: true,
                                  confirmButtonColor: '#3085d6',   
                                  cancelButtonColor: '#d33',
                                  confirmButtonText: 'Save credit card & continue',
                                  cancelButtonText: "Cancel",
                                  closeOnConfirm: false,
                                  closeOnCancel: true,
                                  allowEscapeKey: false,
                  allowOutsideClick: false
                                },
                                function(isConfirm){
                                  if (isConfirm){
                                      var ccform=$( ".sweet-alert form:eq(0)" ).serialize();
                                      $('#'+form_id).attr("extra",ccform);
                    
                                      $('#'+form_id).click();
                                  } else {
                                    swal("Action Cancelled!", "No changes were saved", "success");
                                  }
                                });

                            } else if (result=="confirm") { 
                
                                swal({  type:"warning", title: message_title,   html: message,showCancelButton: true, showConfirmButton: true,
                                  confirmButtonColor: '#3085d6',   
                                  cancelButtonColor: '#d33',
                                  confirmButtonText: 'Yes, continue with purchase',
                                  cancelButtonText: "No, cancel",
                                  closeOnConfirm: false,
                                  closeOnCancel: true,
                                  allowEscapeKey: false,
                  allowOutsideClick: false
                                },
                                function(isConfirm){
                                  if (isConfirm){
                                     $('#orderform').attr("extra","");
                                     $("#calconly").val('false');
                                      $('#orderform').submit();
                                  } else {
                                    swal("Action Cancelled!", "No action taken :)", "success");
                                  }
                                });

                            } else {    
                            $("#calconly").val('true');
                              swal({  type:result, title: message_title,   html: decodeHtml(message)});
                            }    
                    },
                    error: function(e){
                        swal({  type:"error", title: 'Javascript Error',   text: 'A connection error occurred while submitting your request. Please try to save again.',   html: true });
                    }
                });
        });         
                $(document).on( "click", ".reload-content", function(e) {
                    e.preventDefault();
          swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
                    $.get('go.php',{ view: "transactions", apitype: "html", widget: "WHMCS2 - Admin Template" },function(data){ 
                              $('#ajax-content').html(data);
                                        $('.data-content').html();
                                        startNested(90000);
                     swal({type:"success",timer:2000,showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, title: 'Content Updated', html:     '' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });                   
               <? if ($_REQUEST[view]=="transactions") { ?>      table.fnDraw(); <? } ?>
       
                                    }); 
              }); 
              $( document ).on( "click", ".deleteonlygateway", function(e) {
                    e.preventDefault();

                    if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
                    
                    if (!$(this).attr("element")) { var element=""; } else { var element=$(this).attr("element"); }
                    if (!$(this).attr("swal-text")) { var text="Are you sure you want to continue?"; } else { var text=$(this).attr("swal-text"); }
                    
                    
                    swal({  type:$(this).attr("type"), title: $(this).attr("message_title"),   html: $(this).attr("message"),
                    showCancelButton: true,
                    showConfirmButton: false,
                    cancelButtonText: "Close Window",
                    closeOnCancel: true
                  })
              }); 
            
           
                $( document ).on( "click", ".gatewayaction", function(e) {
                    e.preventDefault();

                    if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
                    
                    if (!$(this).attr("element")) { var element=""; } else { var element=$(this).attr("element"); }
                    if (!$(this).attr("swal-text")) { var text="Are you sure you want to continue?"; } else { var text=$(this).attr("swal-text"); }
                    
                    swal({
                    title: "Confirm",
                    text: text,
                    html:true,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, continue!',
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true
                  },
                  function(isConfirm){
                    if (isConfirm){
                      $.ajax({
                              url: href,
                              type: "get",
                              dataType: "json",
                              success: function(json){
                                
                               if (json['result']=="success") {   
                                                             
                                swal({  type:"success", title: json['message_title'],   text: json['message'],   html: true });
                                  
                                
                                     setTimeout(function(){ 
                                    $.ajax({url: '/admin/go.php',type: "POST",data: { noheader: "1", view: "gateways", apitype: "widget", widget: "WHMCS2 - Admin Template" },dataType: "html",
                                   success: function(reloaddata){  
                                            $('#ajax-content').html(reloaddata);
                                            startNested(90000);
                                     }});       
                  }, 1000);
                                         
                                } else {
                                  swal({  type:"error", title: json['message_title'],   text: json['message'],   html: true });
                                   
                                }  
                              },
                              error: function(e){ 
                                  swal({  type:"error", title: 'Javascript Error!',   text: "A connection error occurred while saving. Please try to save again.",   html: true });
                                }
                          });
                        
                    } else {
                        
                    }
                  })
              }); 
        
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
              swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
                      $.ajax({
                              url: href,
                              type: "GET",
                              dataType: "json",
                              success: function(json){
                                if (!json['result']) { json['result']="error"; }
                                if (!json['message']) { json['message']="Your requested processed successfully"; }
                                if (!json['message_title']) { json['message_title']="Action Incomplete"; }
                               if (json['result']=="success") {   
                               
                                
                                swal({  type:"success", title: json['message_title'],   text: json['message'],   html: true });
                                  
                                } else {
                                  swal({  type:"error", title: json['message_title'],   text: json['message'],   html: true });
                                   
                                }  
                              },
                              error: function(e){ 
                                  swal({  type:"error", title: 'Javascript Error!',   text: "A connection error occurred while saving. Please try to save again.",   html: true });
                                }
                          });
                        
                          
              
                            return false;
                        } else {    
                            return false;
                        }
                       });
                        return false;
                        
              }); 
              
              $( document ).on( "click", ".refundpayment", function(e) {
                    e.preventDefault();
                    if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
                                        
                    if (!$(this).attr("type")) { 
                    swal({
                    title: "Confirm Refund",
                    text: "Are you sure you want to refund this payment?",
                    type: "warning",
                        html: true,
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, refund!',
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                  },
                  function(isConfirm){
                    if (isConfirm){
                      swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
                      $.ajax({
                              url: href,
                              type: "POST",
                              dataType: "json",
                              success: function(json){
                                if (!json['result']) { json['result']="error"; }
                                if (!json['message']) { json['message']="Your requested processed successfully"; }
                                if (!json['message_title']) { json['message_title']="Action Incomplete"; }
                               if (json['result']=="success") {   
                               
                                
                                swal({  type:"success", title: json['message_title'],   text: json['message'],   html: true });
                                  $.post('/admin/go.php',{ view: "<?=$_REQUEST[view]?>", apitype: "widget", widget: "WHMCS2 - Admin Template",userid: "<?=$_REQUEST[userid]?>" },function(data){ 
                                        $('#ajax-content').html(data);
                                        startNested(90000);
                                        table.fnDraw();
                                        
                                    }); 
                                } else {
                                  swal({  type:"error", title: json['message_title'],   text: json['message'],   html: true });
                                   
                                }  
                              },
                              error: function(e){ 
                                  swal({  type:"error", title: 'Javascript Error!',   text: "A connection error occurred while saving. Please try to save again.",   html: true });
                                }
                          });
                        
                    } else {
                        
                    }
                  })
                  } else {
                  
                            swal({  type:"info", title: $(this).attr("message_title"),   html: $(this).attr("message"),
                        showCancelButton: true,
                        confirmButtonColor: 'rgb(48, 133, 214)',
                        confirmButtonText: 'Go to Payment Gateway',
                        cancelButtonText: "Cancel",
                        closeOnConfirm: false,
                        closeOnCancel: true
                      },
                      function(isConfirm){
                        if (isConfirm) {

                            return false;
                        } else {    
                            return false;
                        }
                       });
                        return false;
                  }      
              }); 
              
             $( document ).on( "click", ".stoporder", function(e) {
                    e.preventDefault();
                    if (!$(this).attr("href")) { var href='javascript:void(0);'; } else { var href=$(this).attr("href"); }
                    swal({
                    title: "Stop Order?",
                    html: "This will only cancel the order.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, stop order!',
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                  },
                  function(isConfirm){
                    if (isConfirm){
                      swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif', title: 'Processing...',   html:     'Please wait a moment while your request is processed.' }, function() {   swal.disableButtons();   setTimeout(function() {    }, 3000); });
                      $.ajax({
                              url: href,
                              type: "POST",
                              dataType: "json",
                              success: function(json){
                              if (!json['result']) { json['result']="error"; }
                                if (!json['message']) { json['message']="Your requested processed successfully"; }
                                if (!json['message_title']) { jjson['message_title']="Action Incomplete"; }
                               if (json['result']=="success") {   
                               
                                
                                
                                  $.post('/admin/go.php',{ view: "<?=$_REQUEST[view]?>", apitype: "widget", widget: "WHMCS2 - Admin Template",userid: "<?=$_REQUEST[userid]?>" },function(data){ 
                                      swal({  type:"success", title: json['message_title'],   text: json['message'],   html: true });
                                        $('#ajax-content').html(data);
                                        startNested(90000);
                                        <? if ($_REQUEST[view]=="transactions") { ?>      table.fnDraw(); <? } ?>
                                        
                                        
                                    }); 
                                } else {
                                  swal({  type:"error", title: json['message_title'],   text: json['message'],   html: true });
                                   
                                }  
                              },
                              error: function(e){ 
                                  swal({  type:"error", title: 'Javascript Error!',   text: "A connection error occurred while saving. Please try to save again.",   html: true });
                                }
                          });
                        
                    } else {
                        
                    }
                  })
              }); 
<? if ($_REQUEST[view]=="transactions" || $_REQUEST[view]=="invoices" || $_REQUEST[view]=="pastdueinvoices" || $_REQUEST[view]=="emailtemplates2" || $_REQUEST[view]=="currencycodes" || $_REQUEST[view]=="promotions" || $_REQUEST[view]=="paymentgatewaylog") { ?>
              var table=$('.table').DataTable({
        "scrollX": false,
        "scrollY": false,
        pageLength: 50,
        lengthChange: true,
        paging: true,
        order: ['0','desc'],
        dom: 'Bfrtip',
        buttons: ['csv']
      })
<? } ?>  
        <? if ($_REQUEST[view]=="migratereminders" || $_REQUEST[view]=="updatesales" || $_REQUEST[view]=="billingmoduleadmin") { ?>              
              var table=$('.table').DataTable({
        "scrollX": false,
    
        "scrollY": false,
        pageLength: 25,
        lengthChange: true,
        paging: true,
          <? if ($_REQUEST[view]=="migratereminders") { ?>              
        order: ['4','desc'],
          <? } else { ?>
          order: ['0','desc'],
          <? } ?>
        dom: 'Bfrtip',
        buttons: [
      'csv', 'excel', 'pdf', 'print'
    ]
      })
    
        var reminder_id=0;                                 
            var billable_amount=0;
          var billable=0;
          var timeout=100;
          var totalcount=0;
          var table_index=0;  
          var total_checked=0;
        $(".addmultiple").click(function(e) {
                                
          var tagName=$(this).attr("selector-class");
          var faction=$(this).attr("selector-action");    
          var reminder_id=0;                                 
            var billable_amount=0;
          var billable=0;
          <? if ($_REQUEST[view]=="updatesales") { ?>              
            var total_checked=$("."+tagName+":checked").length;
            swal({showCancelButton: false, showConfirmButton: false,closeOnConfirm: true,allowOutsideClick:false,allowEscapeKey:false, imageUrl: '/images/ring-loading.gif', title: 'Updating Sales',   html:     'Progress: <input type="text" name="totalcount" id="totalcount" value="0" style="border:0px;font-size:16px;font-weight:bold;min-width:10px;max-width:35px;border-radius: 0px;box-shadow: none;font-family: open sans,arial,san serif;padding: 0 4px 0 0;text-align:right;"> of '+total_checked+' complete' });
          <? } ?>
          $("."+tagName+":checked").each(function(index,value) {
              var table_index=index+1;
              var timeout=10*table_index;
            var reminder_id=$(this).val();
            if (!$("#billable"+reminder_id).is(':checked')) {
              var dataarray={
              apitype : "html",
              widget : "WHMCS - Migrate Order Actions",
              faction: faction,
              newsite: <?=$w[website_id]?>,
              select : reminder_id };
            } else {              
              var billable_amount=$("#billable_amount"+reminder_id).val();
              var dataarray={
              apitype : "html",
              billable_amount: billable_amount,
              widget : "WHMCS - Migrate Order Actions",
              faction: faction,
              newsite: <?=$w[website_id]?>,
              select : reminder_id };
            }   
              
            $("#tr"+reminder_id+" .status_column").delay(500).html('').addClass("cell-loading-background");
            

            $.ajax({
              url: "/admin/go.php",
              type: 'POST',
              dataType: "html",
              data: dataarray,
              success:function(jsondata){
                $("#tr"+reminder_id+" .status_column").delay(500).removeClass("cell-loading-background");
                $("#tr"+reminder_id+" .status_column").html(jsondata).fadeIn("fast");
                $("#tr"+reminder_id+" ."+tagName).attr("checked",false).remove();
                
              }
            }).done(function() {
            setTimeout(function() { 
              $("#totalcount").val(table_index);
              
                
              if (table_index>=total_checked) {
                setTimeout(function() { 
                  <? if ($_REQUEST[view]=="updatesales") { ?>              
                  top.location.href='whmcs2.php?view=migratereminders&subaction=ready+to+migrate';
                  <? } else { ?>
                    top.location.href='whmcs2.php?view=migratereminders&subaction=migrated';
                  <? } ?>
                }, 2500); 
                }
            }, timeout);  
            });
               
               
        });
      }); 
        <? if ($_REQUEST[view]=="updatesales") { ?>              
        $(".addmultiple").delay(1000).click();
        <? } ?>
<? } ?> 
      }   
</script>

<? if ($_REQUEST[view]=="emailtemplates2" || $_REQUEST[view]=="currencycodes" || $_REQUEST[view]=="promotions") {  ///will show extra datatable formatting for some datatables. ?>
<style>
.dataTables_filter { margin-top: 0px !important;margin-right:0px; !important; }
</style>
<? } ?>