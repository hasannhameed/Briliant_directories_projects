<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.1.0/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.1.0/cropper.min.js"></script>


<script>
    $(document).ready(function () {
        $('.copy_btn').on('click', function () {
            var tokenToCopy = $(this).data('token');
            var cu = $(this).data('coupon');
            if(cu!='' && cu=='credit form'){
                var fullLink = 'https://www.motiv8search.com/supplier-registration-promo?ref=' + tokenToCopy;
			}else if(cu!='' && cu=='pre-registration'){
				var fullLink = 'https://www.motiv8search.com/account/events-announcements/provisional-events?' + tokenToCopy;
			}else{
                var fullLink = 'https://www.motiv8search.com/supplier-registration?ref=' + tokenToCopy;
            }
            
            copyLink(fullLink);
    
            Swal.fire(
              'Link Copied!',
              'Link Copied Successfully!',
              'success'
            )
        }); 
        
        function copyLink(text) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }
    });

</script>

<script>
    function confirmDelete(event, eventId) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteEvent(eventId);
            }
        });
    }

    function deleteEvent(eventId) {

        $.ajax({
            type: 'POST',
            url: 'https://ww2.managemydirectory.com/admin/go.php?widget=create-application-from',
            data: { delete_id: eventId },
            success: function (response) {
                console.log('Deletion successful:', response);
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error('Deletion error:', error);
				console.log(response);
            }
        });

    }
</script>

<!--Limit event-heading -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const subheadingInputs = document.querySelectorAll(".subheading-input");

        subheadingInputs.forEach(function (input) {
            const errorMessage = input.nextElementSibling;

            input.addEventListener("input", function () {
                if (input.value.length > 80) {
                    input.value = input.value.substring(0, 80);
                    errorMessage.textContent = "Maximum character limit is 80 characters";
                } else {
                    errorMessage.textContent = "";
                }
            });
        });
    });
</script>
