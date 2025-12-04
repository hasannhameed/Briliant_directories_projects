<script>
$(document).ready(function(){
  $("#event_searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#app_tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>