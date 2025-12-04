<link rel="stylesheet" href="//cdn.jsdelivr.net/select2/4.0.0/css/select2.min.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.datatables/0.1/css/datatables.css">
<script defer src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script defer src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>
<script defer>
    $(document).ready(function(){
        $('#member_sidebar_toggle').click(function(){
            $('div.member_sidebar').toggleClass('open');
        });
        $('#accordion .collapse.in').prev('.panel-heading').addClass('active');
    });

    $('.resend_verification_email').click(function(event){
        event.preventDefault();
        swal({
                title: `%%%system_success_label%%%`,
                text: `%%%resending_verification_email_to%%% <?php echo $user['email']; ?>`,
                type: "success"
            }
        );
        redirect_url = $(this).attr('href');
        setTimeout(function(){
            location.href = redirect_url;
        }, 3000);
    });

    $('#accordion')
        .on('show.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').addClass('active');
        })
        .on('hide.bs.collapse', function(e) {
            $(e.target).prev('.panel-heading').removeClass('active');
        });
</script>
<script defer>
    var apiEngine = '/api/data/html/get/data_widgets/widget_name?name=Bootstrap%20Theme%20-%20Function%20-%20Add%20to%20Favorites%20Button';

    var featureTabName = $('.fa-heart').parent('a').text().trim();

    $('.favoriteFeature').on('click', function () {
        $("#member_sidebar_toggle").trigger("click");
        var clickingUserId = $('.panel-collapse.collapse.in > div > span:nth-child(1) > a').data('favoriteuser');
        var selectedName = $(this).data('favoritefeature');
        var selectedUser = $(this).data('favoriteuser');
        var selectedId = $(this).data('favoritefeatureid');
        var selectedType = $(this).data('favoritefeaturetype');
        var selectedPostId = 0;
        var action = "show";
        sendToEngine(clickingUserId, selectedUser, selectedName, selectedId, selectedPostId, selectedType, action);
    });

    function sendToEngine(clickingUserId, selectedUser, selectedName, selectedId, selectedPostId, selectedType, action) {
        if (selectedId == 'deleteMember') {
            action = "delete";
            selectedUser = $('#favorites > div > span:nth-child(1) > a').data('favoriteuser');
            selectedPostId = 0;
        }

        $.post(apiEngine, {
            favoriteUserClickId: clickingUserId,
            favoriteUserId: selectedUser,
            favoriteDataName: selectedName,
            favoriteDataId: selectedId,
            favoritePostId: selectedPostId,
            favoriteDataType: selectedType,
            favoriteAction: action
        }, function (data) {
            var splitData = data.split('<split>');
            data = splitData[1];
            if (action == 'show') {
                $('.member_accounts').empty().append('<h1> ' + featureTabName + ': ' + selectedName + '</h1>' + data);
                $('.dataTable').DataTable({
                    "language": {
                        "info": "<?php echo $label['locations_showing_page_of_label']?> _PAGE_ <?php echo $label['locations_of_label']?> _PAGES_",
                        "infoEmpty": "<?php echo $label['locations_info_empty']?>",
                        "search": "<?php echo $label['search_label']?>:",
                        "lengthMenu": "<?php echo $label['locations_show_label']?> _MENU_ <?php echo $label['locations_entries_label']?>",
                        "zeroRecords": "<?php echo $label['locations_no_results']?>",
                        "infoFiltered": "(<?php echo $label['locations_filtered_from']?> _MAX_ <?php echo $label['locations_filtered_total']?>)",
                        "emptyTable": "<?php echo $label['locations_empty_table']?>",
                        "loadingRecords": "<?php echo $label['locations_loading_table']?>",
                        "processing": "<?php echo $label['locations_processing_table']?>",
                        "paginate": {
                            "first": "<?php echo $label['pagination_first_page']?>",
                            "last": "<?php echo $label['pagination_last_page']?>",
                            "next": "<?php echo $label['pagination_next_page_label']?>",
                            "previous": "<?php echo $label['pagination_previous_page_label']?>",
                        }
                    },
                    responsive: true
                });
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-control');
            }
            $('.actionDelete').on('click', function () {
                if ($(this).data('favoritefeaturetype') == 10) {
                    var selectedName = 0;
                    var selectedUser = $(this).data('favoriteuser');
                    var selectedId = 0;
                    var selectedType = $(this).data('favoritefeaturetype');
                    action = 'delete';
                    sendToEngine(clickingUserId, selectedUser, selectedName, selectedId, selectedPostId, selectedType, action);
                    $(this).parents('tr').remove();
                } else {
                    var selectedName = 0;
                    var selectedUser = $(this).data('favoriteuser');
                    var selectedPostId = $(this).data('favoritedataid');
                    var selectedId = $(this).data('favoritefeatureid');
                    var selectedType = $(this).data('favoritefeaturetype');
                    action = 'delete';
                    sendToEngine(clickingUserId, selectedUser, selectedName, selectedId, selectedPostId, selectedType, action);
                    $(this).parents('tr').remove();
                }
            });
        });
    }
</script>