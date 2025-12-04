<?php
if ($pars[0] === 'admin' && $pars[1] === 'go.php') {
?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-5">
                            <h2 class="float-left">Upcoming Events</h2>
                        </div>
                        <div class="col-md-7">
                            <form class="form-inline float-right" action="" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="<?php echo $_POST['event_name'] ?>" placeholder="Search by event name" name="event_name" id="myInput">
                                </div>
                                <div class="form-group">
                                    <select class="select" name="event_type">
                                        <option value="0" <?php echo (!isset($_POST['event_type']) || $_POST['event_type'] === '0' ? 'selected' : ''); ?>>Upcoming Events</option>
                                        <option value="1" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] === '1' ? 'selected' : ''); ?>>Past Events</option>
                                    </select>
                                </div>
                                <button type="submit" name="event_search" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                        <?/*<div class="col-md-2">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">
                            Add Booth
                        </button>
                    </div> */ ?>
                    </div>


                    <div class="clearfix"></div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Event Date</th>
                                <th style="display:none">Start Time</th>
                                <th style="display:none">End Time</th>
                                <th class="text-center">Total Registrants</th>
                                <th class="manage-head">Manage</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            // function to extract name string 
                            function extract_event_name($event_title)
                            {
                                // Regex to match the event name before the first number
                                preg_match('/^(.*?)(?=\s*-*\s*\d|$)/', $event_title, $matches);
                                return trim($matches[1]); // Returns the event name without the date/number
                            }
                            ?>
                            <?php
                            if (isset($_GET)) {
                                if (isset($_GET['delete_post'])) {

                                    $delete_post = mysql_query("DELETE FROM `live_events_posts` WHERE `live_events_posts`.`post_id` = " . $_GET['delete_post']);
                                    $delete_post_data = mysql_query("DELETE FROM `data_posts` WHERE `data_posts`.`data_id`='73' AND `data_posts`.`post_id` = " . $_GET['delete_post']);
                                    //echo "<script>location = location</script>";
                                    //echo "DELETE FROM `data_posts` WHERE `data_posts`.`data_id`='73' AND `data_posts`.`post_id` = " . $_GET['delete_post'];
                                }
                            }

                            $where_str = "AND dp.post_start_date >= CURDATE()";
                            if (isset($_POST['event_search'])) {
                                if ($_POST['event_name'] != "") {
                                    $where_str = " AND dp.post_title LIKE '%" . $_POST['event_name'] . "%' ";
                                } else if ($_POST['event_type'] == "0") {
                                    // $where_str = " AND dp.post_start_date >= CURDATE() ";
                                    $where_str = " AND dp.post_start_date >= CURDATE()";
                                } else if ($_POST['event_type'] == "1") {
                                    // $where_str = " AND dp.post_expire_date < CURDATE() ";
                                    $where_str = " AND dp.post_expire_date < CURDATE() ";
                                }
                            }
                            if (isset($_POST['event_search']) && isset($_POST['event_type'])) {
                                if ($_POST['event_name'] != "" && $_POST['event_type'] == "0") {
                                    $where_str = " AND dp.post_start_date >= CURDATE() AND dp.post_title LIKE '%" . $_POST['event_name'] . "%'  ";
                                } else if ($_POST['event_name'] != "" && $_POST['event_type'] == "1") {
                                    $where_str = " AND dp.post_expire_date < CURDATE() AND dp.post_title LIKE '%" . $_POST['event_name'] . "%'  ";
                                }
                            }


                            $get_videos_sql = mysql_query("
							
							SELECT
                               dp.*, ( SELECT COUNT(lep.user_id) FROM live_events_posts AS lep WHERE lep.post_id = dp.post_id ) AS count_suppliers
                            FROM
                            data_posts AS dp WHERE dp.data_id = 73 " . $where_str . " ORDER BY dp.post_start_date ASC, count_suppliers ASC


                        ");
                            //echo "SELECT dp.*, ( SELECT COUNT(lep.user_id) FROM live_events_posts AS lep WHERE lep.post_id = dp.post_id ) AS count_suppliers FROM data_posts AS dp WHERE dp.data_id = 73 " . $where_str . " ORDER BY dp.post_start_date ASC ";

                            while ($videos = mysql_fetch_assoc($get_videos_sql)) {
                                $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $videos['post_id'] . " AND `key` = 'start_time'");
                                $get_start_time = mysql_fetch_assoc($get_start_time_sql);
                                $start_time = $get_start_time['value'];

                                $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $videos['post_id'] . " AND `key` = 'end_time' ");
                                $get_end_time = mysql_fetch_assoc($get_end_time_sql);
                                $end_time = $get_end_time['value'];
                                $post_id = $videos['post_id'];
                                $title = $videos['post_title'];
                                $title2 = extract_event_name($title);

                            ?>
                                <tr>
                                    <td>
                                        <p class="event_name"><?= $title2 ?></p>
                                    </td>
                                    <td>
                                        <?php echo date('M j, Y', strtotime($videos['post_start_date'])) ?>
                                    </td>
                                    <td style="display:none">
                                        <?php echo date('H-i-A', strtotime($start_time)) ?>
                                    </td>
                                    <td style="display:none">
                                        <?php echo date('H-i-A', strtotime($end_time)) ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=<?php echo $videos['post_id']; ?>" target="_blank">
                                            <?php echo $videos['count_suppliers']; ?> </a>
                                    </td>
                                    <td class="d-flex">
                                        <a style="width:100%;" href="https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=<?php echo $videos['post_id']; ?>" class="btn btn-primary">View Event</a>

                                        <!-- <form action="https://ww2.managemydirectory.com/admin/go.php?widget=video-publishing-plugin" method="post" class="delete_post">
                                        <input type="hidden" name="delete_post" value="<?php echo $videos['post_id'] ?>">
                                        <button type="submit" class="btn btn-danger delete_post_btn">Delete</button>
                                    </form> -->
                                        <a href="javascript:void(0);" style="display: none;" onclick="decision('Are you sure you want to delete <?= $title ?>','/admin/go.php?widget=video-publishing-plugin&delete_post=<?= $post_id ?>');" class="btn btn-danger" hidden>Delete</a>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>






<?php } else { ?>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Access Denied</h1>
                    <p>You do not have the necessary permissions to view this page.</p>
                </div>
            </div>
        </div>
    </section>
<?php } ?>