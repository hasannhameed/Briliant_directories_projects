<link rel="stylesheet" href="//cdn.jsdelivr.net/select2/4.0.0/css/select2.min.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.datatables/0.1/css/datatables.css">
<script defer src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script defer src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js"></script>

<script defer>
    $(document).ready(function(){
        $('#member_sidebar_toggle').click(function(){
            $('div.member_sidebar').toggleClass('open');
        })
        $('#accordion .collapse.in').prev('.panel-heading').addClass('active');
    });
    $('.resend_verification_email').click(function(event){
        event.preventDefault();
        $('.resend_verification_email').addClass('disabled');
        $.ajax({
            url: '/account/resendverification',  // The URL to send the request to
            type: 'POST',                        // The HTTP method to use
            dataType: 'json',
            data: { send: 1 },                   // The data to send in the request
            success: function(response) {

                if (response.result == "success") {
                    swal({
                        title: `%%%system_success_label%%%`,
                        text: `%%%resending_verification_email_to%%% <?php echo $user['email']; ?>`,
                        type: "success"
                    });
                } else {
                    swal({
                        title: `%%%swal_whoops%%%`,
                        text: `%%%try_again_error%%%`,
                        type: "error"
                    });
                }
                $('.resend_verification_email').removeClass('disabled');
            },
            error: function(xhr, status, error) {
                swal({
                        title: `%%%swal_whoops%%%`,
                        text: `%%%try_again_error%%%`,
                        type: "error"
                });
            }
        });
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
    <?php if(!is_null($_GET['favorites'])){ ?>
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        sendToEngine(urlParams.get('clickingUserId'), urlParams.get('selectedUser'), urlParams.get('selectedName'), urlParams.get('selectedId'), urlParams.get('selectedPostId'), urlParams.get('selectedType'), urlParams.get('action'));
    <?php } ?>

    $('.favoriteFeature').on('click', function () {
        var clickingUserId = $('.panel-collapse.collapse.in > div > span:nth-child(1) > a').data('favoriteuser');
        var selectedName = $(this).data('favoritefeature');
        var selectedUser = $(this).data('favoriteuser');
        var selectedId = $(this).data('favoritefeatureid');
        var selectedType = $(this).data('favoritefeaturetype');
        var selectedPostId = 0;
        var action = "show";
        <?php if($pars[0] == "account") { ?>
        $("#member_sidebar_toggle").trigger("click");
        sendToEngine(clickingUserId, selectedUser, selectedName, selectedId, selectedPostId, selectedType, action);
        <?php } else { ?>
            window.location = `/account?favorites&clickingUserId=`+clickingUserId+`&selectedName=`+selectedName+`&selectedUser=`+selectedUser+`&selectedId=`+selectedId+`&selectedType=`+selectedType+`&selectedPostId=`+selectedPostId+`&action=`+action;
        <?php } ?>
    });

    function sendToEngine(clickingUserId, selectedUser, selectedName, selectedId, selectedPostId, selectedType, action) {
        if(selectedType == null){
            selectedType = 0;
        }
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
                $('.member_accounts').empty().append(`<div role='tabpanel' class='manage-post-tabs'><ul role='tablist' class='nav nav-tabs'><li class='active'><a href='#'><i class="fa fa-heart"></i> `+featureTabName+`: `+selectedName+`</a></li></ul></div> ` + data);


                $('.dataTable').DataTable({
                    "dom":  "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    "ordering": false,
                    "language": {
                        "info": `<?php echo $label['locations_showing_page_of_label']?> _PAGE_ <?php echo $label['locations_of_label']?> _PAGES_`,
                        "infoEmpty": "<?php echo $label['locations_info_empty']?>",
                        "search": "",
                        "lengthMenu": `<?php echo $label['locations_show_label']?> _MENU_ <?php echo $label['locations_entries_label']?>`,
                        "zeroRecords": `<?php echo $label['locations_no_results']?>`,
                        "infoFiltered": `(<?php echo $label['locations_filtered_from']?> _MAX_ <?php echo $label['locations_filtered_total']?>)`,
                        "emptyTable": `<?php echo $label['locations_empty_table']?>`,
                        "loadingRecords": `<?php echo $label['locations_loading_table']?>`,
                        "processing": `<?php echo $label['locations_processing_table']?>`,
                        'lengthMenu': `<select class='form-control input-sm'><option value='5'>5 <?php echo $label['locations_entries_label']?></option><option value='10'>10 <?php echo $label['locations_entries_label']?></option><option value='25'>25 <?php echo $label['locations_entries_label']?></option><option value='50'>50 <?php echo $label['locations_entries_label']?></option></select>`,
                        "paginate": {
                            "first": `<?php echo $label['pagination_first_page']?>`,
                            "last": `<?php echo $label['pagination_last_page']?>`,
                            "next": `<?php echo $label['pagination_next_page_label']?>`,
                            "previous": `<?php echo $label['pagination_previous_page_label']?>`
                        }
                    },
                    responsive: true
                });
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_length select').addClass('form-control');
            }
            /*Add class to keyword search filter*/
            $('div.dataTables_filter input').addClass('form-control input-sm lmargin');
            $('.dataTables_filter input').attr('placeholder', `<?php echo $label["photo_albums_filter_placeholder"]?>`);
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