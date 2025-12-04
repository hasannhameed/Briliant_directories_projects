<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).ready(function(){
  $("#event_searchInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#eventTbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}); 
</script>
<script>
$(document).ready(function() {
	<?/*$('#event_searchInput').on('blur', function(){
		
		$('#filterForm').submit();
	})*/?>
	
    $('input[type="checkbox"]').on('change', function() {
        var checkboxGroupName = $(this).attr('name');
       	//$('input[name="' + checkboxGroupName + '"]').not(this).prop('checked', false);
		//$('input[type="checkbox"]').not(this).prop('checked', false);
		
		if(checkboxGroupName === 'credits'){
			
			$('#filterForm').submit();
		}
		
		if (checkboxGroupName !== 'profileCompletion[]') { 
			$('input[type="checkbox"]').not(this).prop('checked', false);
			$('input[name="' + checkboxGroupName + '"]').not(this).prop('checked', false);
			$('input[type="checkbox"]').not(this).prop('checked', false);
        }
		
		if (checkboxGroupName === 'newsPost[]') {
            $('input[name="notnewsPost[]"]').prop('checked', false); 
        } else if (checkboxGroupName === 'notnewsPost[]') {
            $('input[name="newsPost[]"]').prop('checked', false); 
        }
		
		
		
		var selectedProfileCompletionCount = $('input[name="profileCompletion[]"]:checked').length;
		if (selectedProfileCompletionCount > 0) {
            $('#selectedProfileCompletionText').text(selectedProfileCompletionCount + ' Selected');
        } else {
            $('#selectedProfileCompletionText').text('Profile Completion');
        }
		
        var selectedEvents = $('input[name="events[]"]:checked').parent().text();
        var selectedNewsPost = $('input[name="newsPost[]"]:checked').parent().text();
        var selectedNotNewsPost = $('input[name="notnewsPost[]"]:checked').parent().text();
        var selectedAccountOwner = $('input[name="accountOwner[]"]:checked').parent().text();
       // var selectedProfileCompletion = $('input[name="profileCompletion[]"]:checked').parent().text();
		
        $('#selectedEventText').text(selectedEvents ? selectedEvents : 'Events');
        $('#selectedNewsPostText').text((selectedNewsPost || selectedNotNewsPost) ? (selectedNewsPost || selectedNotNewsPost) : 'News');
        $('#selectedAccountOwnerText').text(selectedAccountOwner ? selectedAccountOwner : 'Account Owner');
        //$('#selectedProfileCompletionText').text(selectedProfileCompletion ? selectedProfileCompletion : 'Profile Completion');
    });
});

</script>
<script>
    function confirmDelete(event, eventId) {
        event.preventDefault(); // Prevent default behavior

        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: {
                cancel: "Cancel",
                confirm: "Yes, delete it!",
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                swal({
                    title: "Processing...",
                    text: "Please wait while your request is being processed.",
                    icon: "/images/bars-loading.gif", // Update the image path
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                });

                $.ajax({
                    type: "POST",
                    url: "https://ww2.managemydirectory.com/admin/go.php?widget=suppliers_overview", // Update with the correct endpoint
                    data: { cid: eventId },
                    success: function () {
                        swal("Deleted!", "The event has been deleted successfully.", {
                            icon: "success",
                        });
						$('#comments_modal').modal('hide');
						setTimeout(function() {
						  swal.close();
							location.reload();
						}, 750);
                    },
                    error: function () {
                        swal("Error!", "An error occurred while deleting the event. Please try again later.", {
                            icon: "error",
                        });
                    },
                });
            } else {
               /* swal("Cancelled", "Your event is safe!", {
                    icon: "info",
                }); */
            }
        });
    }
</script>


