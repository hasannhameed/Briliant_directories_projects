<script>
$( document ).ready(function() {
    $("#bd-chained").select2();
	$("#bd-chained-2").select2();
	
	
});
	
</script>
<script>
$(document).ready(function(){
  $("#event_searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#eventTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>