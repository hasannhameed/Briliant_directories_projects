<script>
    let minusIcon = $('.fa-minus');
    minusIcon.hide();
    $(document).on('click', '.copy_btn', function(e) {
        e.stopPropagation(); 
    });
	
    $(document).on('click', '.alert_custom', function() {
        const clickedAlert = $(this);
        const plusIcon = clickedAlert.find('.fa-plus');
        const minusIcon = clickedAlert.find('.fa-minus');
        
        if (plusIcon.is(':visible')) {
            plusIcon.hide();
            minusIcon.show();
        } else {
            plusIcon.show();
            minusIcon.hide();
        }
    });
</script>