<script>
$(document).ready(function(){
	$(".searching").click(function(){
		$(".vis_search").toggleClass("vis_show");
		 if ($(".vis_show").hasClass('large')) {
        $(".vis_show").height();
	    $(".vis_show").removeClass('large');
		} else {
        $(".vis_show").height(''); 
        $(".vis_show").addClass('large');
    }
		

});
					 
		});			 
					 
</script>