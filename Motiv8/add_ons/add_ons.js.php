<script>
    $(document).ready(function () {
        $(".delete-btn").click(function () {
            var id = $(this).data("id");

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
                    $.ajax({
                        type: "post",
                        url: "https://ww2.managemydirectory.com/admin/go.php?widget=add_ons",
                        data: { id: id },
                        success: function (response) {
                            console.log(response);
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                            window.location.reload();
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Event handler for form submission
        $(document).on('submit', '.editAddonForm', function (event) {
            event.preventDefault();
            var $form = $(this);
            // Extract data from form fields
            var id = $form.closest('.modal-body').find("#edit_id").val();
            var name = $form.closest('.modal-body').find("#edit_name").val();
            var price = $form.closest('.modal-body').find("#edit_price").val();
            var stock = $form.closest('.modal-body').find("#edit_stock").val();
			var label = $form.closest('.modal-body').find("#edit_label").val();
			var color_code = $form.closest('.modal-body').find("#edit_color_code").val();
			
           var ckeditorId = '#edit_description' + id;
			var description = $form.closest('.modal-body').find(ckeditorId).val();
            /* var editor = CKEDITOR.instances[ckeditorId];
            if (editor) {
                var description = editor.getData();
            } else {
                var description = $form.closest('.modal-body').find(ckeditorId).text();
            }*/
			
            var formData = {
                'update_id': id,
                'update_name': name,
                'update_price': price,
                'update_stock': stock,
				'update_label': label,
				'update_color_code': color_code,
                'update_description': description
            };
            
            // Log form data for debugging
            console.log("Form data:", formData);
            $.ajax({
                type: "POST",
                url: "https://www.motiv8search.com/api/widget/html/get/add_ons",
                data: formData,
                success: function (response) {
                    if (response) {
                        // Update UI or handle success response
                        $("#editModal").modal("hide");
                        Swal.fire(
                            'Add-On Saved!',
                            ' Add-On saved successfully!',
                            'success'
                        );
                        window.location.reload();
                    } else {
                        // Handle error response if needed
                    }
                },
                error: function () {
                    console.log("Update fail");
                }
            });
        });
    });
</script>
