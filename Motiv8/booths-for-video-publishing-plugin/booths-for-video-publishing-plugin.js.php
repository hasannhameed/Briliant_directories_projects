 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 



<script>



document.addEventListener('click', function(e) {
    const target = e.target.closest('.url-copy');
    if (target) {
        e.preventDefault();
        const url = target.getAttribute('data-url');

        navigator.clipboard.writeText(url).then(() => {
            // Optional: give feedback
            swal("Copied!", "The URL has been copied to your clipboard.", "success");
            // Or use swal if you already load SweetAlert
            // swal("Copied!", "Invite link copied to clipboard.", "success");
        }).catch(err => {
            console.error("Clipboard copy failed:", err);
            swal("Copy Failed", "Could not copy the URL. Please copy it manually.", "error");
        });
    }
});


$(document).on('blur', '.packageLimit', function () {
  const val = ($(this).val() || '').trim(),
        userId = Number($(this).data('user-id') || 0),
        postId = Number($(this).data('post-id') || 0);

  if (!val || isNaN(val) || val < 0 || !userId || !postId) {
    return swal('Invalid input', 'Enter a non-negative limit & valid IDs.', 'warning');
  }

  $.ajax({
    url: "https://www.motiv8search.com/api/widget/html/get/increase_purchase_limit",
    method: "GET",
    dataType: "json",
    data: { package_limit: val, user_id: userId, post_id: postId },

    success: function (res) {
      if (res && res.ok) {
        swal("Success!", res.message || "Updated successfully.", "success");
      } else {
        swal("Not Done", res.message || "Update failed or no row found.", "info");
      }
    },

    error: function (xhr) {
      const msg = (xhr.responseJSON && xhr.responseJSON.error) || xhr.statusText || "Unknown error";
      swal("Error", msg, "error");
    }
  });
});

</script>


 
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
                    url: "https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin",
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
<script>
document.addEventListener("DOMContentLoaded", function () {
  // 1. Collect all user IDs from <tr data-userid="">
  const userIds = [];
  document.querySelectorAll("#eventTable tr[data-userid]").forEach(tr => {
    const id = tr.getAttribute("data-userid");
    if (id) userIds.push(id);
  });

  // 2. Update the download link
  const staffLink = document.getElementById("download_cssv");
  if (staffLink && userIds.length > 0) {
    const url = new URL(staffLink.href, window.location.origin);
    
    // Preserve existing post_id
    const postId = url.searchParams.get("post_id");

    // Append user_ids while keeping post_id
    url.searchParams.set("post_id", postId);
    url.searchParams.set("user_ids", userIds.join(","));

    staffLink.href = url.toString();
  }

  //console.log("Updated download link:", staffLink.href);
});
</script>
