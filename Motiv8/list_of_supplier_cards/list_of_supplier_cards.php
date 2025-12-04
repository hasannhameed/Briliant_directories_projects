<?/* <a href="/account/<?php echo $pars[1]; ?>/add" class="test274 btn btn-success publish-post-button">
  %%%add_a_new_label%%% <?php echo $dc['data_name'].$extraLabelTextForAddButton; ?>
</a> */?>
 <a  class="btn btn-success publish-post-button">
  Currently you don't have Supplier Card
</a> 
<?php
$complete = 'Published';
$incomplete = 'Incomplete';
$delete_label = 'Delete Supplier Card?';

//$sql = "SELECT lep.*, dp.* FROM live_events_posts AS lep JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE lep.user_id = '" . $_COOKIE[userid] . "'";
//$sql = "SELECT lep.*, dp.post_title,dp.post_id FROM live_events_posts AS lep JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE lep.user_id = '" . $_COOKIE[userid] . "'  ORDER BY CASE WHEN (SELECT COUNT(*) FROM `users_meta` WHERE `key` = 'additional_info_link' AND `database_id` = dp.post_id AND `database` = 'data_posts') > 0 THEN 1 ELSE 0 END ASC, `lep`.`id` DESC, `lep`.`start_date` ASC";
$sql ="SELECT lep.*, dp.post_title,dp.post_id, dp.post_start_date, dp.post_live_date, dp.post_expire_date FROM live_events_posts AS lep JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE lep.user_id = '" . $_COOKIE[userid] . "'  ORDER BY  ABS(dp.post_start_date - DATE_FORMAT(NOW(), '%Y%m%d')) ASC";
//echo $sql;
//post_live_date is changed to post_start_date for order by on 01-22-25
//,`lep`.`id` DESC
$postresults = mysql_query($sql);
$mysqlnumrows = mysql_num_rows($postresults);

if($mysqlnumrows > 0){
 
  $j = 0;

  while ($row = mysql_fetch_assoc($postresults)) {
    $add_info_link_sql = "SELECT `value` FROM `users_meta` WHERE `key` = 'additional_info_link' AND `database_id` = '".$row['post_id']."' AND `database` = 'data_posts'";
    $add_info_link_results = mysql_query($add_info_link_sql);
    $add_info_link = mysql_fetch_assoc($add_info_link_results);

    //---------------------------------------
    //1
    //---------------------------------------
    $td1 = "<div style='display:none';>".$row['id']."</div><div class='clearfix'></div>";

    if ($row[staus] == 1 || $row[staus] == 0) {
      $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-bottom label-danger'>".$incomplete;
    }
    if ($row[staus] == 2) {
      $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-bottom label-success'>".$complete;
    }

    $td1 .= "<div class='clearfix'></div></div>";

    if ($row[thumb_booth] != '') {
      //Open div that holds image
      $td1 .= "<div class='alert-default btn-block text-center the-post-image'>";
      $td1 .= "<img src='https://www.motiv8search.com/images/events/" . $row['thumb_booth'] . "'>";
      //Close div that holds image
      $td1 .= "<div class='clearfix'></div></div>";
    }
    //---------------------------------------
    //2
    //---------------------------------------
      $td2 = "<h4 class='line-height-lg bold xs-nomargin post-title'><a href='/account/edit-supplier-card/edit?id=" . $row['id'] . "'>" . $row['post_title'] . "</a></h4>";
      $td2 .= "<div class='small bmargin hidden-xs'>";
      if ($row['video_option'] == 'link') {
        $td2 .= "<p class='the-post-description'>" . limitWords(trim(strip_tags(str_replace('src="http://', 'src="//', $row['event_name']))), 200) . "</p>";
      }
      if ($row['video_option'] == 'none') {
        $td2 .= "<p class='the-post-description'>" . limitWords(trim(strip_tags(str_replace('src="http://', 'src="//', $row['event_description']))), 200) . "</p>";
      }
      if ($row['video_option'] == 'link') {
        $td2 .= "<span class='the-post-location small'><b>Presentation Slot:</b> ";
        $td2 .= $row['start_time']. ' - ' .$row['end_time'];
        $td2 .= "</span>";
      }

    //---------------------------------------
    //3
    //---------------------------------------
    //1 = Draft
    //2 = Published
    $td3 = "<div class='dropdown center-block'>";
    $td3 .= "<a class='btn btn-primary bmargin btn-sm bold btn-block' href='/account/edit-supplier-card/edit?id=" . $row['id'] . "'>Edit Supplier Card</a>";
    if(isset($add_info_link['value']) && !empty($add_info_link['value'])){
      $td3 .= "<a class='btn btn-primary bmargin btn-sm bold btn-block' href='" . $add_info_link['value'] . "' target='_blank'>Event Info</a>";
    }

        /*
        <i class='fa fa-angle-down pull-right' style='margin-top: 4px;'></i>
        $td3 .= "<ul class='dropdown-menu pull-right font-sm'>";

        $td3 .= "<li>";

        $td3 .= "<a class='action-post-link-view' href='/account/add-supplier-card/edit/" . $row['id'] . "' target='_blank'>Edit Supplier Card</a></li>";

        $td3 .= "<li>";

        //$td3 .= "<a href='javascript:decisionDelete(" .$delete_label . '", "/account/add-supplier-card/delete/' . $row['id'] . '"' . ");' class='text-danger action-post-link-delete'>";
        $td3 .= "%%%delete_label%%%";

        $td3 .= "</a>";

        $td3 .= "</li>";

        $td3 .= "</ul>"; */

    $td3 .= "</div>";
    $data[$j] = array(
      "picture" => $td1,
      "description" => $td2,
      "actions" => $td3
    );
    $j++;
  }
  // <img src="https://www.motiv8search.com/images/events/642159fotor_ai (1).png" alt="...">
 } 
 $json_data = json_encode(array("data" => $data));
 ?>
 <?php if ($mysqlnumrows > 0) { ?>
     <script>
        $(document).ready(function() {
            $('.publish-post-button').remove();
        });
     </script>
 <? } ?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/s/bs/dt-1.10.10,cr-1.3.0,fh-3.1.0,kt-2.1.0,r-2.0.0,rr-1.1.0/datatables.min.js"type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function($) {
    var vkdata = <?php echo json_encode($data); ?>;
    $( ".first-post" ).after( "<div class='new-element'><table id='supplier-datatable' class='table table-striped'><!-- datatables --></table></div>" );
    <?php
    if ($mysqlnumrows > 0) { ?>
      console.log("Data for DataTable:", vkdata);
      $('#supplier-datatable').DataTable({
        data: vkdata,
        columns: [
          { data: 'picture','searchable': true },
          { data: 'description', 'searchable' : true },
          { data: 'actions', 'searchable' : false }
        ],
        ordering: false
        //"lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
        //order: [[2, 'asc']]
      });
    <? } ?>
  });
</script>
<style>
  .label-success {
    background-color: #5cb85c;
  }
</style>