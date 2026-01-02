<script>
$(document).ready(function(){
	$('input[name="listing_price"]').autoNumeric('init', {aForm: false, aSign:'$' });
	$('input[name="listing_price"]').keyup(function() {
		var listing_price = $(this).val().replace(/,/g , "" ).replace("$","");
	$("input[name='now_price']").val(listing_price);
	});
	
	
});

</script>