<script>

$('.view_phone_number_header').click(function(event){
	event.preventDefault();
	$(this).hide();
	$('.view_phone_number').hide();
	$('.phone_number').css("display","block");
	$('.phone_number_header').css("display","block");
	
})

</script>