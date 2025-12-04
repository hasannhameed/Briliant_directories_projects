<script>
    $('.copy_btn').on('click', function () {
        var tokenToCopy = $(this).data('token');
        var fullLink = 'https://www.motiv8search.com/supplier-registration?ref=' + tokenToCopy;
        copyLink(fullLink);

        // Swal.fire(
		//   'Link Copied!',
		//   'Link Copied Successfully!',
		//   'success'
		// )
        swal("Link Copied!", "Link Copied Successfully!", "success");
    });

    function copyLink(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }
</script>

<script>
    $(document).ready(function () {
        $(document).on('submit', '#applicationForm', function (e) {
            e.preventDefault();

            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                type: 'POST',
                url: '/api/widget/json/get/enquire-mail',
                data: formData,
                success: function (response) {
                    console.log('AJAX Success:', response);
                    swal({
                        title: 'Success',
                        text: 'Notification sent successfully!',
                        icon: 'success'
                    }).then(function() {
                        form.closest('.modal').modal('hide');
                        location.reload();
                    });
                },
                error: function (error) {
                    console.log('AJAX Error:', error);
                    swal('Error', 'Failed to send notification.', 'error');
                }
            });
        });
    });
</script>
