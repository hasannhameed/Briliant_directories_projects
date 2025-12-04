<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
<?php 
    $sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT lep.*, srf.packages_section AS packages 
    FROM live_events_posts lep
    LEFT JOIN supplier_registration_form srf 
    ON lep.user_id = srf.user_id AND lep.post_id = srf.event_id
    WHERE (thumb_booth IS NOT NULL OR thumb_booth <> '')
    AND (replay_embed IS NOT NULL OR replay_embed <> '')
    AND post_id = ".$post['post_id']." AND staus = '2'
    AND booth_priority <> 999
    GROUP BY lep.user_id
    ORDER BY FIELD(video_option, 'other', 'superbooth', 'none', 'link', 'embed'), start_time, updated_at;");
    /*echo "
      SELECT
              *
          FROM
              `live_events_posts`
          WHERE
              (
                  thumb_booth IS NOT NULL OR thumb_booth <> ''
              ) AND (
                  replay_embed IS NOT NULL OR replay_embed <> ''
              ) AND post_id = ".$post['post_id']." AND staus = '2'
              AND booth_priority <> 999
          GROUP BY
              user_id
          ORDER BY
              FIELD(video_option, 'other', 'supperbooth', 'none', 'link', 'embed'),
              start_time,
              updated_at
    "; */
    ?>
<?php if (mysql_num_rows($sql)): ?>
  
  <!-- <div style="display: flex; justify-content: space-between;">
    <div><h2 class="up-next-title" style="font-weight: bold;"><?php echo $post['webinars_title'] ?></h2></div>
    <div class="up-next-title" >
      <form>
        <input  type="text" name="search" placeholder="Search by keyword">
        <div class="input-group">
          <span class="input-group-addon">Search</span>
          <input id="msg" type="text" class="form-control" name="msg" placeholder="Additional Info">
        </div>
      </form>
    </div>
  </div> -->
<div class="hidden">
<?php 
		echo "SELECT lep.*, srf.packages_section AS packages 
    FROM live_events_posts lep
    LEFT JOIN supplier_registration_form srf 
    ON lep.user_id = srf.user_id AND lep.post_id = srf.event_id
    WHERE (thumb_booth IS NOT NULL OR thumb_booth <> '')
    AND (replay_embed IS NOT NULL OR replay_embed <> '')
    AND post_id = ".$post['post_id']." AND staus = '2'
    AND booth_priority <> 999
    GROUP BY lep.user_id
    ORDER BY FIELD(video_option, 'other', 'superbooth', 'none', 'link', 'embed'), start_time, updated_at;"; 
?>
</div>
  <div class="row">
    <div class="col-md-6 supplier-webinars">
      <h2 class="up-next-title" style="font-weight: bold;"><?php echo $post['webinars_title'] ?></h2>
    </div>
    <div class="col-md-6 search-form up-next-title">
      <form>
        <div class="input-group">
          <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
          <input type="text" name="search" id="search-input" placeholder="Search by keyword" class="form-control">
        </div>
      </form>
    </div>
</div>

<?php
?>
<?php endif ?>
<div class="row grid-row">
<?php
    $all_events = array();
    while($row = mysql_fetch_assoc($sql)) {
      $all_events[] = $row;
    } 
    function getPriority($event) {
      if ($event['video_option'] == 'other') return 0;
      if ($event['video_option'] == 'superbooth' || $event['packages'] == 'SuperBooth Package') return 1;
      if ($event['video_option'] == 'link') return 2;
      return 3; // Desktop
    }
    usort($all_events, function($a, $b) {
      return getPriority($a) - getPriority($b);
    });
    foreach ($all_events as $event) {
    
      $company_logo = "https://".$_SERVER['HTTP_HOST']."/images/profile-profile-holder.png";
    
      $get_user = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT * FROM `users_data` WHERE `user_id`= ".$event['user_id']); 
      $user_details = mysql_fetch_assoc($get_user);
    
      $get_user_logo = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT * FROM `users_photo` WHERE `type`='logo' and `user_id`= ".$event['user_id']); 
      $user_logo_details = mysql_fetch_assoc($get_user_logo);
    
      if(!empty($user_logo_details['file'])) 
      {        
        $company_logo = "https://".$_SERVER['HTTP_HOST']."/logos/profile/".$user_logo_details['file']; 
      } 
      else 
      {        
        $get_user_photo = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT * FROM `users_photo` WHERE `type`='photo' and `user_id`= ".$event['user_id']); 
        $user_photo_details = mysql_fetch_assoc($get_user_photo);
    
        if(!empty($user_photo_details['file'])) 
        {          
          $company_logo = "https://".$_SERVER['HTTP_HOST']."/pictures/profile/".$user_photo_details['file'];
        }
      }
    
      $current_date = date('Y-m-d H:i:s');
      $current_date_ymd = date('Y-m-d');
      $one_hour_letter = date("Y-m-d H:i:s", strtotime("+45 minutes"));
      $one_hour_before = date("Y-m-d H:i:s", strtotime("-45 minutes"));
      
      $is_live = false;
      $is_before_live = false;
      $is_after_live = false;
      $is_before_day = false;
      $presentation_available = false;
      $videocall_available = false;
      $btn_color = "live-color";
      if (strtotime($event['start_date']." ".$event['start_time']) < strtotime($current_date) AND strtotime($event['end_date']." ".$event['end_time']) > strtotime($current_date)) {
        $is_live = true;
      }
      if (strtotime($event['start_date']." ".$event['start_time']) < strtotime($current_date) AND strtotime($event['end_date']." ".$event['end_time']) > strtotime($current_date)) {
          $presentation_available = true;
      }

      if ($event['video_option'] == 'none' || $event['video_option'] == 'other' ) {
          $is_live = false;
      }
      // echo $event['start_date'];
      // echo "<br>";
      // echo $current_date_ymd;
      if ($event['start_date'] ==  $current_date_ymd) {
          $videocall_available = true;
      }

      if ($event['start_date'] >  $current_date_ymd) {
          $is_before_day = true;
      }

      if (strtotime($event['start_date']." ".$event['start_time']) < strtotime($current_date)) {
        $is_before_live = true;
      }
      if (strtotime($event['start_date']." ".$event['end_time']) < strtotime($current_date)) {
        // echo $event['start_date'] . " < " . $current_date;
        $is_after_live = true;
      }
      
      // if (strtotime($event['start_date']." ".$event['start_time']) > strtotime($one_hour_before) AND  strtotime($event['start_date']." ".$event['start_time']) < $current_date) {
      //   $is_before_live = true;
      //   $btn_color = "live-soon-color";
      // }
      // $tmp_date = strtotime($event['start_date']." ".$event['start_time']);


	  $packages = mysql_fetch_array(mysql_query(" SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $event['user_id'] . " AND event_id = " . $post['post_id'] ));
    $packages = $packages['packages_section'];

    if ($event['replay_embed'] != "") {
      $btn_color = 'rewatch-color';
    }

		$priority = 3; // Default
    if ($event['video_option'] == 'other') {
        $priority = 0;
    } elseif ($event['video_option'] == 'superbooth' || $packages == 'SuperBooth Package') {
        $priority = 1;
    } elseif ($event['video_option'] == 'link') {
        $priority = 2;
    } elseif ($event['video_option'] == 'none' || $packages == 'Desktop Package') {
        $priority = 3;
    }
	  // Default Border & Ribbon Logic
    $default_border_color = "#999"; 
    $default_ribbon_text = "Desktop";
    if ($event['video_option'] == 'superbooth' || $packages == 'SuperBooth Package') {
        $default_border_color = "#333"; 
        $default_ribbon_text = "SuperBooth";
    } elseif ($event['video_option'] == 'other') {
        $default_border_color = "#007BFF"; 
        $default_ribbon_text = "Other";
    } elseif ($event['video_option'] == 'link') {
        $default_ribbon_text = "Presentation";
    }
    usort($all_events, function($a, $b) {
      $getPriority = function($event) {
          if ($event['video_option'] == 'other') return 0;
          if ($event['video_option'] == 'superbooth' || $event['packages'] == 'SuperBooth Package') return 1;
          if ($event['video_option'] == 'link') return 2;
          return 3; // Desktop
      };
      return $getPriority($a) - $getPriority($b);
  });
    $final_border_color = !empty($event['border_color']) ? $event['border_color'] : $default_border_color;
    $final_ribbon_text = !empty($event['border_text']) ? $event['border_text'] : $default_ribbon_text;
    
		echo '<p class="hidden">'.$event['video_option'].'</p>';
    echo '<p class="hidden">'.$final_border_color.'</p>';
    echo '<p class="hidden">'.$final_ribbon_text.'</p>';  
    ?>
<div class="col-sm-6 col-md-4" >
    <div class="company-thum thumbnail search_supplier" <?php echo "style='border-color: $final_border_color'" ?> >
        <span class="presenting-ribbon "<?php echo " style='background: $final_border_color'" ?> >
          <?php  echo $final_ribbon_text;?>
        </span>
        <div>
            <img class="company_logo" style="height: 60px;" src="<?php echo $company_logo; ?>" alt="...">
            <?php if ($is_live): ?>
            <a style="color: #fff;" href="javascript:void(0)" class="live_tag <?php echo $btn_color ?>">
            <?php 
                if ($is_live) {
                    echo "Live";
                }
                ?>
            </a>
            <?php endif ?>
            <div class="company-thumb-img">
                <?php if ($event['replay_embed'] == ""): ?>
                <img src="https://<?php echo $_SERVER['HTTP_HOST'] ?>/images/events/<?php echo $event['thumb_booth'] ?>" alt="...">
                <?php else: ?>
                <?php echo $event['replay_embed'] ?>
                <?php endif ?>
            </div>
            <div class="caption">
                <p <?php echo ($event['video_option'] == 'none' || $event['video_option'] == 'superbooth' && !empty($event['start_time'])) ? "style='visibility:hidden; margin-bottom:5px;'" : "" ?> style="margin-bottom:5px;">
                  <?php
                        $start = date('h:i a', strtotime($event['start_time']));
                        $end = date('h:i a', strtotime($event['end_time']));
                  
                        if ($start !== $end) {
                            echo "<strong>Time:</strong> $start - $end";
                        }
                  ?>
                </p>
                <!-- <p <?php echo ($event['video_option'] =='link') ? "style='display: flex;'" : "style='visibility:hidden;'"; ?> ><strong style=" margin-right: 3px;">Location:</strong> <span><?php echo $event['event_location'] ?></span></p> -->
                <p <?php echo (($event['video_option'] == 'link' || $event['video_option'] == 'other' ) && !empty($event['event_location'])) ? "style='display: flex;'" : "style='visibility:hidden;'"; ?>>
                    <strong style="margin-right: 3px;">Location:</strong> 
                    <span><?php echo $event['event_location']; ?></span>
                </p>

                <?php if ($event['id'] > 799) {
                    if ($event['video_option'] == 'link') { ?>
                      <div>
                        <h4 class="company_desc" style="text-align: center;">
                            <?php echo $event['event_name'] ?>
                        </h4>
                      </div>
                        
                    <? }
                    if ($event['video_option'] == 'none' || $event['video_option'] == 'other' || $event['video_option'] == 'superbooth') {  ?>
                        <h4 class="company_desc" style="text-align: center;">
                            <?php echo $event['event_description'] ?>
                        </h4>
                    <?  } 
                }else{ ?>

                    <h4 class="company_desc" style="text-align: center;">
                        <?php echo $event['event_name'] ?>
                    </h4>

                <? } ?>
                <div class="company_name"><?=$user_details['company']?></div>
                <div class="top_category_name">Automotive</div>
                <?php
                $sub_category_names = mysql_fetch_assoc(mysql_query("SELECT GROUP_CONCAT(list_services.name) as category_name FROM list_services INNER JOIN rel_services ON list_services.service_id = rel_services.service_id AND rel_services.user_id = '".$user_details['user_id']."'")); 
                ?>
                <div class="sub_category_name"><?=$sub_category_names['category_name']?></div>
            </div>
        </div>
            <?php if ($event['video_option'] != 'none' && $event['video_option'] != 'other' && $event['video_option'] != 'superbooth'): ?>
              <div class="buttons-container">
                  <?php 
                      if ($event['video_link'] != "" && $presentation_available) {
                        $view_webinar = $event['video_link'];
                        $webinar_available = true;
                      } else {
                        $view_webinar = "";
                        $webinar_available = false;
                      }
                  ?>
                  <div class="row">
                      <div class="col-xs-6">
                          <a href="https://www.motiv8search.com/<?php echo urldecode($user_details['filename']); ?>" style="display: block; padding-left: 7px; padding-right: 7.5px;" target="_blank" class="btn btn-primary " role="button">View Company</a>
                      </div>
                      <div class="col-xs-6">
                          <?php 
                              if ($event['video_link'] != "" && $presentation_available) {
                                $view_webinar = $event['video_link'];
                                $webinar_available = true;
                              } else {
                                $view_webinar = "";
                                $webinar_available = false;
                              }
                          ?>
                          <a target="_blank" class="btn <?php echo ($webinar_available) ? 'btn-info' : 'btn-secondary disabled ' ?>" style="display: block; padding-left: 7px;" role="button" href="<?php echo $view_webinar ?>">Join Online</a>
                      </div>
                  </div>
                  <div class="row" style="margin-top: 15px;">
                      <div class="col-xs-6">
                      <?php if ($is_before_day): ?>
                            <?php // echo $event['add_to_calendar_embed_code'] ?>
                            <div title="Add to Calendar" class="addeventatc" style="display: block;">
                                Add to Calendar
                                <span class="start"><?php echo date('m/d/Y h:i A', strtotime($event['start_date']." ".$event['start_time'])) ?></span>
                                <span class="end"><?php echo date('m/d/Y h:i A', strtotime($event['start_date']." ".$event['end_time'])) ?></span>
                                <span class="timezone"><?php echo $post['addevent_timezone'] ?></span>
                                <span class="title"><?php echo $user_details['company'] ?> Webinar  - Motiv8 vForum</span>
                                <span class="location"><?php echo $event['event_location'] ?></span>
                                <span class="description">
                                  <?php
                                  echo $event['event_name'] ?> - <?php echo $user_details['company'];
                                  echo "<br> <br> Event Url: "; 
                                  $event_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                  echo urlencode($event_url);


                                   ?>
                                    
                                  </span>
                                <!-- <span class="location">Location of the event</span> -->
                            </div>
                      <?php elseif((!$is_after_live || $is_live) && $presentation_available): ?>
                        <a target="_blank" class="btn btn-secondary disabled" style="display: block; padding-right: 7px; padding-left: 7px;" role="button" >Presentation</a>
                      <?php elseif($is_after_live): ?>
                        <a target="_blank" class="btn btn-info" style="display: block; padding-left: 7px; padding-right: 7px;" role="button" href="<?php echo $event['presentation_pdf_link']; ?>">Presentation PDF</a>
                      <?php else: ?>
                          <a target="_blank" class="btn btn-secondary disabled" style="display: block; padding-right: 7px; padding-left: 7px;" role="button" >Presentation</a>
                      <?php endif ?>
                      </div>
                      

                      <div class="col-xs-6">
                          <a target="_blank" class="btn btn-info" style="display: block; padding-left: 7px;" role="button" href="https://www.motiv8search.com/<?php echo urldecode($user_details['filename']); ?>/connect">Message</a>
                      </div>
                      
                  </div>
              </div>
            <?php else: ?>
              <div class="buttons-container">
                  <div class="row single-row-button">
                      <?php if ($event['video_option'] == 'other' && !empty($event['button_text']) && !empty($event['button_url'])): ?>
                        <div class="col-xs-6 col-xs-offset-3" style="margin-bottom: 10px;">
                            <a target="_blank" class="btn btn-primary" style="display: block; padding-left: 7px;" 
                              role="button" href="<?php echo $event['button_url']; ?>">
                                <?php echo $event['button_text']; ?>
                            </a>
                        </div>
                      <?php endif; ?>
                      <div class="col-xs-6">
                          <a href="https://www.motiv8search.com/<?php echo urldecode($user_details['filename']); ?>" style="display: block; padding-left: 7px; padding-right: 7.5px;" target="_blank" class="btn btn-primary " role="button">View Company</a>
                      </div>
                      <div class="col-xs-6">
                          <a target="_blank" class="btn btn-info" style="display: block; padding-left: 7px;" role="button" href="https://www.motiv8search.com/<?php echo urldecode($user_details['filename']); ?>/connect">Message</a>
                      </div>
                  </div>
                  
              </div>
            <?php endif ?>
        
    </div>
</div>
<?php $i++; } ?>  
<style>
    a.disabled {
    pointer-events: none;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
  // Get the presentations and search input elements
  //var $presentations = $('.company-thum').parent();
  var $presentations = $('.search_supplier').parent();
  
  var $searchInput = $('#search-input');

  // Attach a keyup event handler to the search input
  $searchInput.keyup(function() {
    search();
  });

  // Search function to filter presentations based on user input
  function search() {
    // Get the user's search query
    var query = $searchInput.val().toLowerCase();

    // Iterate over the presentations and filter based on search query
    $presentations.each(function() {
      var $presentation = $(this);
      var title = $presentation.find('.company_desc').text().toLowerCase();
      var categories = $presentation.find('.sub_category_name').text().toLowerCase();
      var company = $presentation.find('.company_name').text().toLowerCase();

      // title.indexOf(query) > -1 || description.indexOf(query) > -1 || company.indexOf(query) > -1

      if (title.indexOf(query) > -1 || company.indexOf(query) > -1 || categories.indexOf(query) > -1) {
        $presentation.show();
      } else {
        $presentation.hide();
      }
    });
  }
});
</script>

