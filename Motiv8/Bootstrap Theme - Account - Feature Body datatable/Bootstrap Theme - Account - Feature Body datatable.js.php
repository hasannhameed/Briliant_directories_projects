<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/s/bs/dt-1.10.10,cr-1.3.0,fh-3.1.0,kt-2.1.0,r-2.0.0,rr-1.1.0/datatables.min.js"
        type="text/javascript"></script>
<!-- <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script> -->
<?php if ($pars[0] == "videos") { ?>
    <script>
        // Find all the YouTube video embedded on a page
        var videos = document.getElementsByClassName("youtube");

        for (var i = 0; i < videos.length; i++) {

            var youtube = videos[i];

            // Based on the YouTube ID, we can easily find the thumbnail image
            var img = document.createElement("img");
            img.setAttribute("src", "http://i.ytimg.com/vi/" + youtube.id + "/hqdefault.jpg");
            img.setAttribute("class", "thumb");

            // Overlay the Play icon to make it look like a video player
            var circle = document.createElement("div");
            circle.setAttribute("class", "circle");
            youtube.appendChild(img);
            youtube.appendChild(circle);

            // Attach an onclick event to the YouTube Thumbnail
            youtube.onclick = function () {

                // Create an iFrame with autoplay set to true
                var iframe = document.createElement("iframe");
                iframe.setAttribute("src", "https://www.youtube.com/embed/" + this.id + "?autoplay=1&autohide=1&border=0&wmode=opaque&enablejsapi=1");

                // The height and width of the iFrame should be the same as parent
                iframe.style.width = this.style.width;
                iframe.style.height = this.style.height;

                // Replace the YouTube thumbnail with YouTube HTML5 Player
                this.parentNode.replaceChild(iframe, this);
            };
        }
    </script>
<? } ?>

<script>
    <?php
    $purchaseLimitAddOn = getAddOnInfo('purchase_limit');
    if (isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success') { ?>
    $(document).ready(function () {
        var head = document.getElementsByTagName("head")[0];
        var script = document.createElement("script");
        script.src = "https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.2/sweetalert2.min.js";
        head.appendChild(script);
    });
    <?php }?>



    $(document).ready(function () {
        $('.delete-sub-account').click(function (e) {
            e.preventDefault();

            const userToken = $(this).attr("data-token");

            swal({
                title: `<?php echo $label['sweet_alert_are_you_sure']; ?>`,
                text: `<?php echo $label['you_wont_be_able_to_revert']; ?>`,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `<?php echo $label['photo_albums_yes_delete'];?>`
            }).then((result) => {
                if (result) {
                    swal({
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif',
                        title: `<?php echo $label['form_processing_request']; ?>`,
                        html: `<?php echo $label['please_wait_processing']; ?>`
                    });
                    let url = `/wapi/widget?widget_name=Bootstrap Theme - Account - Feature Body datatable&header_type=json&apitype=json&noheader=1&request_type=GET&token=${userToken}&website_custom_action=sub_account_action&perform=delete_member`;
                    let myInit = {
                        method: 'GET',
                        credentials: 'same-origin',
                    };
                    fetch(url, myInit).then(function () {
                        location.reload();
                    }).catch(error => console.log('Error:', error));
                }
            }).catch(error => console.log(error));
        });


        var dataType = <?php echo $dc['data_type']; ?>;
        var dataId = <?php echo $dc['data_id']; ?>;
        var statusFilter = 0;
        var sortValue = 'newest_first';

        //datatables functionality
        var table = $("#feature-body-datatable").DataTable({
            "dom":  "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "ordering": false,
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                <?php if ($_COOKIE['editmode'] == 1 || $_COOKIE['editmode'] == 3) { ?>
                'url': "/wapi/widget?widget_name=Bootstrap%20Theme%20-%20Account%20-%20Feature%20Body%20datatable&request_type=GET&header_type=json&external_action=getresults&dataType=" + dataType + "&dataId=" + dataId + "&statusFilter=" + statusFilter + "&sortValue="+sortValue  + "&editmode=3",
                <?php } else { ?>
                'url': "/wapi/widget?widget_name=Bootstrap%20Theme%20-%20Account%20-%20Feature%20Body%20datatable&request_type=GET&header_type=json&external_action=getresults&dataType=" + dataType + "&dataId=" + dataId + "&statusFilter=" + statusFilter + "&sortValue="+sortValue,
                <?php } ?>
            },
            "drawCallback": function (settings) {

            },
            "columns": [
                {"data": "picture", "searchable": false},
                {"data": "description", "searchable": true},
                {"data": "actions", "searchable": false}
            ],

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

        });
        $('.dataTables_filter input').attr('id', 'DTsearchBox');

        $('.dataTables_filter input').attr('placeholder', `<?php echo $label["photo_albums_filter_placeholder"]?>`);
        /*Add class to keyword search filter*/
        $('div.dataTables_filter input').addClass('form-control input-sm lmargin');
        $('#DTsearchBox').on('keyup', function () {
            table.search(this.value, true, false).draw();
        });
        let pendingApprovalHTML = '';
        <?php
        if (addonController::isAddonActive('post_approval') && $dc['post_approval'] == 'admin_control') { ?>
        pendingApprovalHTML = `<option value='ppendingapproval'><?php echo $label['feature_datatable_pendingapproval'] ?></option>`
        <?php } ?>
        let statusHTML = `<div id='statusContainer' class='status-select'>
                            <select aria-controls='feature-body-datatable'
                                    class='form-control input-sm dropdown-search-status'>
                                <option value='0'><?php echo $label['photo_albums_status'] ?></option>
                                <option value='ppublished'><?php echo $label['feature_datatable_published'] ?></option>
                                <option value='pexpired'><?php echo $label['feature_datatable_expired'] ?></option>
                                <option value='pdraft'><?php echo $label['feature_datatable_draft'] ?></option>
                                ${pendingApprovalHTML}
                            </select>
                        </div>`;
        let sortHTML = `
        <div id='sortContainer' class='status-select rmargin'>
            <select aria-controls='feature-body-datatable' class='form-control input-sm dropdown-sort' name='sortFilter' id='sortFilter'>
                <option value='newest_first'><?php echo $label['datatable_newest_first'] ?></option>
                <option value='newest_last'><?php echo $label['datatable_newest_last'] ?></option>
                <option value='updated_first'><?php echo $label['datatable_updated_first'] ?></option>
                <option value='updated_last'><?php echo $label['datatable_updated_last'] ?></option>
                <?php 
                $postsDateCheckWhere = array(
                    array('value' => $dc['data_id'] , 'column' => 'data_id', 'logic' => '='),
                    array('value' => '' , 'column' => 'post_start_date', 'logic' => '!='),
                    array('value' => $user['user_id'] , 'column' => 'user_id', 'logic' => '=')
                );
                $postsDateCount = bd_controller::data_posts()->getCount('post_id', $postsDateCheckWhere);
                if(intval($postsDateCount) > 0){ ?>
                <option value='date_start_first'><?php echo $label['datatable_start_date_first'] ?></option>
                <option value='date_start_last'><?php echo $label['datatable_start_date_last'] ?></option>
                <?php } ?>
                <?php 
                $featuredWhere = array(
                    array('value' => $dc['data_id'] , 'column' => 'data_id', 'logic' => '='),
                    array('value' => 1 , 'column' => 'post_featured', 'logic' => '='),
                    array('value' => $user['user_id'] , 'column' => 'user_id', 'logic' => '=')
                );
                $featuredPostsCount = bd_controller::data_posts()->getCount('post_id', $featuredWhere);
                if(intval($featuredPostsCount) > 0){ ?>
                    <option value='featured'><?php echo $label['datatable_featured'] ?></option>
                <?php } ?>
            </select>
        </div>`;

        $('.dataTables_filter').append(statusHTML);
        $('.dataTables_filter').append(sortHTML);

        var sortValue = 'newest_first';
        var statusValue = '0';

        $('.dropdown-search-status').on('change', function () {
            /*table.search( this.value, true, false ).draw();
            $('#DTsearchBox').val("");*/
            var statusValue = $(this).val();
            let url = "/wapi/widget?widget_name=Bootstrap%20Theme%20-%20Account%20-%20Feature%20Body%20datatable&request_type=GET&header_type=json&external_action=getresults&dataType=" + dataType + "&dataId=" + dataId + "&statusFilter=" + statusValue + "&sortValue="+sortValue;
            table.ajax.url(url).load();
        });
        $('.dropdown-sort').on('change', function () {
            sortValue = $(this).val();
            let url = "/wapi/widget?widget_name=Bootstrap%20Theme%20-%20Account%20-%20Feature%20Body%20datatable&request_type=GET&header_type=json&external_action=getresults&dataType=" + dataType + "&dataId=" + dataId + "&statusFilter=" + statusValue + "&sortValue="+sortValue;
            table.ajax.url(url).load();
        });
    });

    //function to delete post
    function decisionDelete(message, url) {
        <?php
        $purchaseLimitAddOn = getAddOnInfo('purchase_limit');
        if (isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success') { ?>
        swal({
            type: "warning",
            title: `<?php echo $label['sweet_alert_are_you_sure']?>`,
            html: message,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: `<?php echo $label['photo_albums_yes_delete']?>`,
            cancelButtonText: `<?php echo $label['no_cancel_label']?>`
        }).then((result) => {
            if (result) {
                swal(`<?php echo $label['photo_albums_deleted']?>`, `<?php echo $label['photo_albums_deleted_text']?>`, "success");
                setTimeout(function () {
                    window.location = url;
                }, 1000);
            } else {
                swal(`<?php echo $label['photo_albums_cancelled']?>`, `<?php echo $label['photo_albums_post_safe']?>`, "error");
            }
        });
        <?php } else { ?>
        swal({
                title: `<?php echo $label['sweet_alert_are_you_sure']?>`,
                text: message,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `<?php echo $label['photo_albums_yes_delete']?>`,
                cancelButtonText: `<?php echo $label['no_cancel_label']?>`,
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    swal(`<?php echo $label['photo_albums_deleted']?>`, `<?php echo $label['photo_albums_deleted_text']?>`, "success");
                    setTimeout(function () {
                        window.location = url;
                    }, 1000);
                } else {
                    swal(`<?php echo $label['photo_albums_cancelled']?>`, `<?php echo $label['photo_albums_post_safe']?>`, "error");
                }
            });
        <?php } ?>

    }
</script>