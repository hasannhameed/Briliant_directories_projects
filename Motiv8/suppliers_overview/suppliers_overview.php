<?php
if($pars[0] === 'admin' && $pars[1] === 'go.php'){
  
global $sess; 
$loggedname = $sess['admin_name'];
$loggeduser = $sess['admin_user']; 
$complet = 'Published';
$inComplet = 'Incomplete';
$approve = 'Set to Publish';
$reject = 'Set as Incomplete';
$website_domain = "https://launch18186.directoryup.com";
$website_domain = "https://www.motiv8search.com";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $Id = $_POST['id'];
  $content = $_POST['content'];
  $type = $_POST['type'];
  $postId = $_POST['postId'];
  $currentDate = date('Y-m-d H:i:s');
  if (isset($Id) && isset($content) && isset($type)) {
    $content = mysql_real_escape_string($content);
    if ($type === 'comment') {
      // $sql = "UPDATE users_data SET comments = '$content' WHERE user_id  = $Id";
      $sql = "INSERT INTO supplier_comments (user_id, post_id, comments, comment_by, `date`) VALUES ('$Id', '$postId', '$content', '" . $sess['admin_name'] . "', '$currentDate') ";
    }
    if ($type === 'owner') {
      $sql = "UPDATE users_data SET owners = '$content' WHERE user_id  = $Id";
    }
    // echo $sql;
    $query = mysql_query($sql);
    if ($query) {
      echo "Comments updated successfully";
    } else {
      echo "Error updating comments: " . mysql_error();
    }
  }
}
if (isset($_POST["cid"])) {
  $cid = $_POST['cid'];

  $deleteQuery = "DELETE FROM supplier_comments WHERE id='$cid'";
  //echo "DELETE FROM create_application_pages WHERE id='$delete_btn'";
  $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                               VALUES ('$loggedname', '$loggeduser', 'comment_delete_booth', '$cid')");
  if (mysql_query($deleteQuery) === TRUE) {
    //echo "Delete Successful";
  } else {
    echo "Error deleting data" . mysql_error();
  }
}


// Database credentials
$whmcs_database_host = $w['whmcs_database_host'];
$whmcs_database_name = $w['whmcs_database_name'];
$whmcs_database_password = $w['whmcs_database_password'];
$whmcs_database_user = $w['whmcs_database_user'];

// Create a connection
$conn = new mysqli($whmcs_database_host, $whmcs_database_user, $whmcs_database_password, $whmcs_database_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$conditions = array();
$filterbyNews = false;
$filterbyEvents = false;
$creditUsers = array();
$eventsUsers = array();
$creditQuery = mysqli_query($conn, "SELECT notes as user_id FROM `tblpromotions` WHERE  notes != '' AND maxuses = 1 AND `code` != '' GROUP BY user_id ORDER BY `id` DESC");
while ($row = mysqli_fetch_assoc($creditQuery)) {
  $creditUsers[] = $row['user_id'];
}


// Handle Credits Search
$creditsFilter = '';
if (!empty($creditUsers) && isset($_GET['credits']) && !empty($_GET['credits'])) {
  $creditsFilter = "ud.user_id IN ('" . implode("','", $creditUsers) . "')";
  $conditions[] = $creditsFilter;
}

// Handle Keyword Search
if (isset($_GET['key']) && !empty($_GET['key'])) {
  $keyword = mysql_real_escape_string($_GET['key']);
  $conditions[] = "(
      ud.user_id LIKE '%$keyword%' OR 
      ud.first_name LIKE '%$keyword%' OR 
      ud.last_name LIKE '%$keyword%' OR 
      ud.company LIKE '%$keyword%' OR 
      ud.email LIKE '%$keyword%' OR
	  dp.post_title LIKE '%$keyword%' 
  )";
}

// Handle "Events" filters
if (isset($_GET['events']) && !empty($_GET['events'])) {
  foreach ($_GET['events'] as $filter) {
    switch ($filter) {
      case 'no_events':
        $conditions[] = "ud.user_id NOT IN (SELECT lep.user_id FROM `live_events_posts` AS lep)";
        $filterbyEvents = true;
        break;
      case 'no_events_last_week':
        $conditions[] = "ud.user_id NOT IN (SELECT lep.user_id FROM `live_events_posts` AS lep WHERE lep.start_date < NOW() - INTERVAL 7 DAY GROUP BY lep.user_id)";
        $filterbyEvents = true;
        break;
      case 'no_events_last_30_days':
        $conditions[] = "ud.user_id NOT IN (SELECT lep.user_id FROM `live_events_posts` AS lep WHERE lep.start_date < NOW() - INTERVAL 30 DAY GROUP BY lep.user_id)";
        $filterbyEvents = true;
        break;
      case 'no_events_last_90_days':
        $conditions[] = "ud.user_id NOT IN (SELECT lep.user_id FROM `live_events_posts` AS lep WHERE lep.start_date < NOW() - INTERVAL 90 DAY GROUP BY lep.user_id)";
        $filterbyEvents = true;
        break;
      case 'no_events_last_180_days':
        $conditions[] = "ud.user_id NOT IN (SELECT lep.user_id FROM `live_events_posts` AS lep WHERE lep.start_date < NOW() - INTERVAL 180 DAY GROUP BY lep.user_id)";
        $filterbyEvents = true;
        break;
    }
  }
}

// Handle "News Post" filters
if (isset($_GET['notnewsPost']) && !empty($_GET['notnewsPost'])) {
  foreach ($_GET['notnewsPost'] as $filter) {
    switch ($filter) {
      case 'no_news_last_week':
        $conditions[] = "ud.user_id NOT IN (SELECT dp.user_id FROM `data_posts` AS dp WHERE dp.data_id = 14 AND (dp.post_live_date IS NULL OR dp.post_live_date < NOW() - INTERVAL 7 DAY ) GROUP BY dp.user_id)";
        $filterbyNews = true;
        break;
      case 'no_news_last_30_days':
        $conditions[] = "ud.user_id NOT IN (SELECT dp.user_id FROM `data_posts` AS dp WHERE dp.data_id = 14 AND (dp.post_live_date IS NULL OR dp.post_live_date < NOW() - INTERVAL 30 DAY ) GROUP BY dp.user_id)";
        $filterbyNews = true;
        break;
      case 'no_news_last_90_days':
        $conditions[] = "ud.user_id NOT IN (SELECT dp.user_id FROM `data_posts` AS dp WHERE dp.data_id = 14 AND (dp.post_live_date IS NULL OR dp.post_live_date < NOW() - INTERVAL 90 DAY ) GROUP BY dp.user_id)";
        $filterbyNews = true;
        break;
      case 'no_news_last_180_days':
        $conditions[] = "ud.user_id NOT IN (SELECT dp.user_id FROM `data_posts` AS dp WHERE dp.data_id = 14 AND (dp.post_live_date IS NULL OR dp.post_live_date < NOW() - INTERVAL 180 DAY ) GROUP BY dp.user_id)";
        $filterbyNews = true;
        break;
    }
  }
}

if (isset($_GET['newsPost']) && !empty($_GET['newsPost'])) {
  foreach ($_GET['newsPost'] as $filter) {
    switch ($filter) {
      case 'news_last_week':
        $conditions[] = "(dp.post_live_date >= NOW() - INTERVAL 7 DAY)";
        $filterbyNews = true;
        break;
      case 'news_last_30_days':
        $conditions[] = "(dp.post_live_date >= NOW() - INTERVAL 30 DAY)";
        $filterbyNews = true;
        break;
      case 'news_last_90_days':
        $conditions[] = "(dp.post_live_date >= NOW() - INTERVAL 90 DAY)";
        $filterbyNews = true;
        break;
      case 'news_last_180_days':
        $conditions[] = "(dp.post_live_date >= NOW() - INTERVAL 180 DAY)";
        $filterbyNews = true;
        break;
    }
  }
}

// Handle "Account Owner" filters
//if (isset($_GET['accountOwner']) && !empty($_GET['accountOwner'])) {
  //$ownerConditions = array();
  //foreach ($_GET['accountOwner'] as $owner) {
   // $ownerConditions = "ud.owners = '" . mysql_real_escape_string($owner) . "'";
 // }
  if (isset($_GET['accountOwner'])) {
    //$conditions[] = "(" . implode(' OR ', $ownerConditions) . ")";
	 $conditions[] = "ud.owners = '" . mysql_real_escape_string($_GET['accountOwner']) . "'";
  }
//}

// Handle "Profile Completion" filters
if (isset($_GET['profileCompletion']) && !empty($_GET['profileCompletion'])) {
  foreach ($_GET['profileCompletion'] as $filter) {
    switch ($filter) {
      case 'ContactDetails':
        $conditions[] = "us.complete_contact = 0";
        break;
      case 'PageInfo':
        $conditions[] = "us.complete_resume = 0";
        break;
      case 'Logo':
        $conditions[] = "us.show_complete = 0";
        break;
      case 'PageOverview':
        $conditions[] = "us.complete_about = 0";
        break;
    }
  }
}

// Handle Sorting
$orderBy = "ud.signup_date DESC"; // Default sorting: Newest - Oldest

if (isset($_GET['sortBy']) && !empty($_GET['sortBy'])) {
    switch ($_GET['sortBy']) {
        case 'oldest':
            $orderBy = "ud.signup_date ASC";
            break;
        case 'az':
            $orderBy = "ud.company ASC"; 
            break;
        case 'za':
            $orderBy = "ud.company DESC";
            break;
        case 'recent_activity':
            $orderBy = "ud.last_login DESC"; 
            break;
        default:
        $orderBy = "ud.signup_date DESC";
    }
}
// Build the WHERE clause
$whereClause = !empty($conditions) ? 'AND ' . implode(' AND ', $conditions) : '';

// print_r($conditions);
//  echo $whereClause;
?>
<section>
  <!-- <a href="/admin/go.php?widget=video-publishing-plugin" class="back-button"><i class="fa fa-reply" aria-hidden="true"></i> Back</a> -->
  <!-- <a href="https://www.motiv8search.com/<?= $get_post_details['post_filename'] ?>" class="view-post-button" target="_blank"><i class="fa fa-external-link"></i> Go to event page</a> -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12 col-lg-3">
            <h2 class="float-left">Suppliers Overview</h2>
          </div>
          <div class="col-md-12 col-lg-9">
            <div class="search-filters" style="display: flex;grid-gap: 10px;align-items: center;justify-content: flex-end;">
              <form id="filterForm" method="get" action="https://ww2.managemydirectory.com/admin/go.php?widget=suppliers_overview" style="display: flex;align-items: center;grid-gap: 10px;" class="rmargin">
                <input type="hidden" name="widget" value="suppliers_overview">
                <!-- Suppliers with Credits -->
                <label><input type="checkbox" name="credits" id="creditsCheckbox" value="credits" <?php echo (isset($_GET['credits']) && $_GET['credits'] == 'credits') ? 'checked' : ''; ?>> Suppliers with Credits</label>

                <!-- Keyword Search -->
                <label class="float-right nomargin">
                  <input type="search" name="key" id="event_searchInput" class="form-control" placeholder="Search by Keyword" value="<?php echo isset($_GET['key']) ? htmlspecialchars($_GET['key']) : ''; ?>" aria-controls="irow">
                </label>

                <!-- Events Dropdown -->
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownEvents" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span id="selectedEventText">Events</span> <span id="selectedEvent" class="text-info"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownEvents">
                    <!-- <li><label><input type="checkbox" name="events[]" value="no_events_last_week"> Nothing booked in last week</label></li> -->
                    <li><label><input type="checkbox" name="events[]" value="no_events_last_30_days"> Nothing booked in last 30 days</label></li>
                    <li><label><input type="checkbox" name="events[]" value="no_events_last_90_days"> Nothing booked in last 90 days</label></li>
                    <li><label><input type="checkbox" name="events[]" value="no_events_last_180_days"> Nothing booked in last 180 days</label></li>
                    <li><label><input type="checkbox" name="events[]" value="no_events">Nothing Booked</label></li>
                  </ul>
                </div>

                <!-- News Post Dropdown -->
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownNewsPost" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span id="selectedNewsPostText">News</span> <span id="selectedNewsPost" class="text-info"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownNewsPost">
                    <!-- <li><label><input type="checkbox" name="newsPost[]" value="news_last_week" <?php echo (isset($_GET['newsPost']) && in_array('news_last_week', $_GET['newsPost'])) ? 'checked' : ''; ?>> Posted in last week</label></li>
                    <li><label><input type="checkbox" name="newsPost[]" value="news_last_30_days" <?php echo (isset($_GET['newsPost']) && in_array('news_last_30_days', $_GET['newsPost'])) ? 'checked' : ''; ?>> Posted in last 30 days</label></li>
                    <li><label><input type="checkbox" name="newsPost[]" value="news_last_90_days" <?php echo (isset($_GET['newsPost']) && in_array('news_last_90_days', $_GET['newsPost'])) ? 'checked' : ''; ?>> Posted in last 90 days</label></li>
                    <li><label><input type="checkbox" name="newsPost[]" value="news_last_180_days" <?php echo (isset($_GET['newsPost']) && in_array('news_last_180_days', $_GET['newsPost'])) ? 'checked' : ''; ?>> Posted in last 180 days</label></li>
                    <hr> -->
                    <li><label><input type="checkbox" name="notnewsPost[]" value="no_news_last_week"> Nothing posted in last week</label></li>
                    <li><label><input type="checkbox" name="notnewsPost[]" value="no_news_last_30_days"> Nothing posted in last 30 days</label></li>
                    <li><label><input type="checkbox" name="notnewsPost[]" value="no_news_last_90_days"> Nothing posted in last 90 days</label></li>
                    <li><label><input type="checkbox" name="notnewsPost[]" value="no_news_last_180_days"> Nothing posted in last 180 days</label></li>
                  </ul>
                </div>

                <!-- Account Owner Dropdown -->
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownAccountOwner" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span id="selectedAccountOwnerText">Account Owner</span> <span id="selectedAccountOwner" class="text-info"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownAccountOwner">
					  <li><label><input type='checkbox' name='accountOwner' value=''> Unallocated</label></li>
                    <?php
                    $ownerSql = "SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4";
                    $query = mysql_query($ownerSql);
                    while ($owner = mysql_fetch_assoc($query)) {
                      //$checked = (isset($_GET['accountOwner']) && in_array($owner['user_id'], $_GET['accountOwner'])) ? 'checked' : '';
                      echo "<li><label><input type='checkbox' name='accountOwner' value='{$owner['user_id']}'> {$owner['first_name']} {$owner['last_name']}</label></li>";
                    }
                    ?>
                  </ul>
                </div>

                <!-- Profile Completion Dropdown -->
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownProfileCompletion" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span id="selectedProfileCompletionText">Profile Completion</span> <span id="selectedProfileCompletion" class="text-info"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownProfileCompletion">
                    <li><label><input type="checkbox" name="profileCompletion[]" value="ContactDetails">No Contact Details</label></li>
                    <li><label><input type="checkbox" name="profileCompletion[]" value="PageInfo">No Page Info</label></li>
                    <li><label><input type="checkbox" name="profileCompletion[]" value="Logo">No Logo</label></li>
                    <li><label><input type="checkbox" name="profileCompletion[]" value="PageOverview">No Page Overview</label></li>
                  </ul>
                </div>
                <!-- Sort By Dropdown -->
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span id="selectedSortByText">Sort By</span> <span id="selectedSortBy" class="text-info"></span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownSortBy">
                    <li><label><input type="radio" name="sortBy" value="newest" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'newest') ? 'checked' : ''; ?>> Newest - Oldest</label></li>
                    <li><label><input type="radio" name="sortBy" value="oldest" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'oldest') ? 'checked' : ''; ?>> Oldest - Newest</label></li>
                    <li><label><input type="radio" name="sortBy" value="az" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'az') ? 'checked' : ''; ?>> A - Z</label></li>
                    <li><label><input type="radio" name="sortBy" value="za" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'za') ? 'checked' : ''; ?>> Z - A</label></li>
                    <li><label><input type="radio" name="sortBy" value="recent_activity" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'recent_activity') ? 'checked' : ''; ?>> Most Recent Activity</label></li>
                  </ul>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-info">Apply</button>
              </form>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <!-- <div class="table-responsive"> -->
        <table class="table" id="eventTable">
          <thead>
            <tr>
              <!-- <th>Event Detail</th> -->
              <th style="width: 250px;">Supplier</th>
              <th style="width: 280px;">Events</th>
              <th style="width: 256px;">News</th>
              <th style="width: 180px;">Credits Info.</th>
              <th style="width: 220px;">Account Info.</th>
              <th style="width: 355px;">Comments</th>
            </tr>
          </thead>
          <tbody id="eventTbody">
            <?php
            // Pagination variables
            $limit = 190; // Number of items per page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max($page, 1); // Ensure page is at least 1
            $start = ($page - 1) * $limit;
            $Select_data = 'lep.start_date, lep.post_id,';
            $Join_data = 'LEFT JOIN live_events_posts AS lep ON ud.user_id = lep.user_id';
            // $order_By = 'lep.`id` DESC';
            $order_By = 'ud.signup_date DESC'; 
            
            // If profileCompletion filter is applied, adjust JOIN and SELECT
            if (isset($_GET['profileCompletion']) && !empty($_GET['profileCompletion'])) {
                $Select_data = 'us.complete_contact, us.complete_resume, us.complete_photo, us.complete_about, us.show_complete,';
                $Join_data = 'LEFT JOIN users_setting AS us ON ud.user_id = us.user_id';
                $order_By = 'ud.user_id DESC'; 
            } else {
                // Only join `data_posts` if `lep` is present
                $Join_data .= ' LEFT JOIN data_posts AS dp ON lep.post_id = dp.post_id';
            }
            
            // If sorting is applied, override orderBy
            if (isset($_GET['sortBy']) && !empty($_GET['sortBy'])) {
                $order_By = $orderBy;
            }
            
            $userSQL = "SELECT 
                          ud.user_id, 
                          ud.first_name, 
                          ud.last_name, 
                          ud.company, 
                          ud.email, 
                          ud.token,
                          ud.filename,
                          ud.subscription_id, 
                          ud.owners,
                          $Select_data 
                          ud.comments
                        FROM 
                          `users_data` AS ud
                        $Join_data
                        WHERE 
                          ud.subscription_id IN (30,33)
                          $whereClause
                        GROUP BY ud.user_id
                        ORDER BY $order_By";
            
            /*LIMIT $start, $limit;*/
            //ud.user_id DESC
            // (lep.start_date < NOW()) ASC, 
            // ABS(DATEDIFF(lep.start_date, NOW())) ASC
            //echo $userSQL;
            $result = mysql_query($userSQL) or die("Error: " . mysql_error());
            while ($udRows = mysql_fetch_assoc($result)) {

            ?>
              <tr style="display: table-row;">
                <td>
                  <div class="video_detail">

                    <?php if ($udRows['company'] != ""): ?>
                      <p><strong><?php echo $udRows['company'] ?></strong></p>
                    <?php endif ?>
					          <?php if ($udRows['first_name'] != ""): ?>
                    <p>Name: <?php echo $udRows['first_name'] . " " . $udRows['last_name'] ?></p>
					          <?php endif ?>
                    <p>Email: <a href="mailto:<?= $udRows['email'] ?>"><?= $udRows['email'] ?></a></p>
                    <p>Member ID : <?php echo $udRows['user_id'] ?></p>
					 

                    <?/*<a href="https://www.motiv8search.com/login/token/<?= $udRows['token'] ?>/home" target="_blank"><i class="fa fa-external-link fa-fw"></i> Login as Member</a>*/?>
                    

                    <?php
                    $user_logo_view = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $udRows['user_id'] . "' AND type = 'logo' LIMIT 1"));
                    if ($user_logo_view != '') { ?>
                      <div class="member-image">
                        <img src="<?php echo 'https://www.motiv8search.com/logos/profile/' . $user_logo_view['file']; ?>">
                      </div>
                      <a href="<?php echo 'https://www.motiv8search.com/logos/profile/' . $user_logo_view['file']; ?>" class="" target='_blank'><i class="fa fa-eye" aria-hidden="true"></i>View Logo</a>
                    <? } else {
                      echo 'Logo N/A';
                    }
                    ?>
                    <br>
                    <br>
                    <button class="btn btn-info" data-user-id="<?php echo $udRows['user_id']; ?>">
                      Manage Account <i class="fa fa-caret-down fa-fw"></i>
                      </button>
                      <div class="action-links" id="action-links-<?php echo $udRows['user_id']; ?>" style="display: none;">
                        <a href="https://www.motiv8search.com/login/token/<?= $udRows['token'] ?>/home" target="_blank">Login as Member</a>
                        <a href="https://www.motiv8search.com/<?= $udRows['filename'] ?>?bdt=<?= $udRows['token'] ?>" target="_blank">View Live Profile</a>
                        <a href="/admin/viewMembers.php?faction=view&userid=<?= $udRows['token'] ?>&newsite=19348" target="_blank">Account Details</a>
                        <a href="/admin/viewMembers.php?faction=edit&userid=<?= $udRows['token'] ?>&newsite=19348&noheader=1" class="popup">Quick Edit</a>
                        <a href="/admin/emailCompose.php?noheader=1&form_name=contact&table_index=user_id&email_state=full&email_from=george%40motiv8connect.com&email_recipients=<?= $udRows['email'] ?>&email_template=&user_id=<?= $udRows['user_id'] ?>&select2=0&name_from=Motiv8+Search&subscription_name=Free+Membership&invoiceid=" class="popup"> Compose Email</a>
                        <a href="/admin/createReminder.php?faction=orders&userid=<?= $udRows['token'] ?>&method=view&newsite=19348" target="_blank"> Payment History</a>
                        <a href="https://ww2.managemydirectory.com/admin/go.php?widget=Admin%20-%20Module%20-%20Change%20Password%20Tool&user=<?= $udRows['token'] ?>&noheader=1" class="popup">Change Password</a>
                        <a href="#" class="action-link-delete delete_member_plugin" attr-name="<?= $udRows['first_name'] . " " . $udRows['last_name'] ?>" attr-id="<?= $udRows['user_id'] ?>">Delete Account</a>
                      </div>
                  </div>
                </td>
                <td>
                  <?php
                  $dpSql = "SELECT 
                          lep.id,
                          lep.user_id,
                          lep.post_id AS lep_post_id,
                          lep.staus,
                          lep.start_date,
                          lep.end_date,
                          lep.start_time,
                          lep.end_time,
                          lep.video_option,
                          dp.post_id,
                          dp.data_id,
                          dp.user_id AS dp_user_id,
                          dp.post_status,
                          dp.post_filename,
                          dp.post_title,
                          dp.post_live_date,
                          dp.revision_timestamp,
                          dp.post_start_date,
                          dp.post_expire_date
                      FROM 
                          live_events_posts AS lep
                      LEFT JOIN 
                          data_posts AS dp 
                      ON 
                          lep.post_id = dp.post_id
                      WHERE 
                          lep.user_id = " . intval($udRows['user_id']) . " 
                          AND dp.data_id = 73
                          ORDER BY 
                          (lep.start_date < NOW()) ASC, 
                          ABS(DATEDIFF(lep.start_date, NOW())) ASC
                          LIMIT 2
                          ;
                    "; //lep.start_date DESC
                  // dp.post_live_date DESC,`lep`.`id` DESC
                  $result2 = mysql_query($dpSql) or die("Error: " . mysql_error());
                  $dprowCount = mysql_num_rows($result2);
                  $dpcurrentRow = 0;
                  while ($dpRows = mysql_fetch_assoc($result2)) {
                    $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $dpRows['post_id'] . " AND `key` = 'start_time'");
                    $get_start_time = mysql_fetch_assoc($get_start_time_sql);
                    $start_time = $get_start_time['value'];

                    $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $dpRows['post_id'] . " AND `key` = 'end_time'");
                    $get_end_time = mysql_fetch_assoc($get_end_time_sql);
                    $end_time = $get_end_time['value'];
                    $packages = mysql_fetch_array(mysql_query(" SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $dpRows['user_id'] . " AND event_id = " . $dpRows['post_id']));
                    $packages = $packages['packages_section'];
                    $postUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=' . $dpRows['post_id'];
                    $dpcurrentRow++;
                    $start_datetime = strtotime($dpRows['start_date'] . ' ' . $dpRows['start_time']);
                    $end_datetime = strtotime($dpRows['end_date'] . ' ' . $dpRows['end_time']);
                    $current_time = time();
                    //$hidePastEvent = ($current_time > $end_datetime) ? 'hidden' : '';
                  ?>
                    <div class="video_detail  <?= $hidePastEvent ?>">
                      <p>
                        <a href="<?= $postUrl ?>" target="_blank" class="btn-link nopad">
                          <strong><?php echo $dpRows['post_title'] ?></strong>
                        </a>
                      </p>
                      <?php if ($dpRows['video_option'] == 'link') { ?>
                        <!-- <p>Start time : <?php echo date('h:i a', strtotime($start_time)) ?></p>
                        <p>End time : <?php echo date('h:i a', strtotime($end_time)) ?></p> -->
                        <p>Start: <?php echo date('d/m/Y h:i a', $start_datetime); ?></p>
                        <p>End: <?php echo date('d/m/Y h:i a', $end_datetime); ?></p>
                      <? } ?>
                      <?php
                      if ($dpRows['staus'] == '1') {
                        // Draft
                        echo '<span style="font-size: 100%;" class="label label-warning">' . $inComplet . '</span>';
                      } elseif ($dpRows['staus'] == '2') {
                        // Published
                        echo '<span style="font-size: 100%;" class="label label-success">' . $complet . '</span>';
                      }

                      echo '<span style="margin: 0 2px;"></span>';

                      if ($dpRows['video_option'] == 'link' && $packages != 'SuperBooth Package') {
                        // Draft
                        echo '<span style="font-size: 100%;" class="label label-primary">Presentation</span>';
                      } elseif ($dpRows['video_option'] == 'none' && $packages != 'SuperBooth Package') {
                        // Published
                        echo '<span style="font-size: 100%;" class="label label-info">Desktop</span>';
                      } elseif ($packages != '' && $packages == 'SuperBooth Package') {
                        echo '<span style="font-size: 100%;" class="label label-purple">SuperBooth</span>';
                      }
                      echo '<span style="margin: 0 2px;"></span>';
                      // Add Upcoming/Past labels based on start_date and end_date
                      if ($current_time < $start_datetime) {
                        echo '<span style="font-size: 100%;" class="label label-default">Upcoming</span>';
                      } elseif ($current_time > $end_datetime) {
                        echo '<span style="font-size: 100%;" class="label label-danger">Past</span>';
                      } else {
                        //echo '<span style="font-size: 100%;" class="label label-success">Ongoing</span>';
                      }
                      
                      $labelSql = "
                      (
                          SELECT 
                              srf.user_id, 
                              srf.add_on, 
                              srf.event_id, 
                              sl.id, 
                              sl.text_label, 
                              sl.color_code, 
                              sl.type
                          FROM supplier_registration_form srf
                          LEFT JOIN supplier_labels sl 
                              ON srf.user_id = sl.user_id 
                              AND srf.event_id = sl.post_id
                          WHERE srf.user_id = " . (int)$dpRows['user_id'] . "
                            AND srf.event_id = " . (int)$dpRows['post_id'] . "
                      )
                      UNION ALL
                      (
                          SELECT 
                              sl.user_id, 
                              NULL AS add_on, 
                              NULL AS event_id, 
                              sl.id, 
                              sl.text_label, 
                              sl.color_code, 
                              sl.type
                          FROM supplier_labels sl
                          LEFT JOIN supplier_registration_form srf 
                              ON sl.user_id = srf.user_id 
                              AND sl.post_id = srf.event_id
                          WHERE (srf.user_id IS NULL OR srf.event_id IS NULL)
                            AND sl.user_id = " . (int)$dpRows['user_id'] . "
                            AND sl.post_id = " . (int)$dpRows['post_id'] . "
                      )
                      ORDER BY id ASC
                      ";


                      $labelRes = mysql_query($labelSql) or die("Label Query Error: " . mysql_error());

                     while ($labelRow = mysql_fetch_assoc($labelRes)) {
                          if (!empty($labelRow['text_label'])) {
                              $color = !empty($labelRow['color_code']) ? $labelRow['color_code'] : '#999';
                              echo '<span class="label" style="display: inline-block; margin-top: 10px;font-size: 100%; background-color: ' . htmlspecialchars($color) . '; color: #fff; padding: 3px 6px; border-radius: 3px; margin-left: 2px;">'
                                  . htmlspecialchars($labelRow['text_label']) .
                              '</span>';
                          }
                      }

                      echo "<br> <br>";
                      ?>
                      <?php if ($dpcurrentRow === $dprowCount && $dpcurrentRow == 2) { ?>
                        <button type="button" class="btn btn-link nolpad more-events" data-uid="<?= $udRows['user_id'] ?>" data-pid="<?= $dpRows['post_id'] ?>" data-toggle="tooltip" title="View More">
                          Click to view more
                          <i class="fa fa-info-circle" style="color:#006fbb;cursor: pointer;"></i>
                        </button>
                      <?php } ?>
                    </div>
                  <?php } ?>
                </td>
                <td>
                  <div class="video_detail">
                    <?php
                    $NewsSql = "SELECT dp.post_id, dp.data_id, dp.user_id, dp.post_status, dp.post_filename, dp.post_title, dp.post_live_date, dp.revision_timestamp, dp.post_start_date, dp.post_expire_date FROM `data_posts` AS dp WHERE dp.user_id = " . $udRows['user_id'] . " AND dp.data_id = 14 ORDER BY dp.post_live_date DESC  LIMIT 1";
                    $CountNewsSql = "SELECT dp.post_id, dp.user_id FROM `data_posts` AS dp WHERE dp.user_id = " . $udRows['user_id'] . " AND dp.data_id = 14";

                    $result3 = mysql_query($NewsSql) or die("Error: " . mysql_error());
                    $result4 = mysql_query($CountNewsSql) or die("Error: " . mysql_error());
                    $nsrowCount = mysql_num_rows($result4);
                    while ($NewsRows = mysql_fetch_assoc($result3)) {
                      $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $NewsRows['post_id'] . " AND `key` = 'start_time'");
                      $get_start_time = mysql_fetch_assoc($get_start_time_sql);
                      $start_time = $get_start_time['value'];

                      $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $NewsRows['post_id'] . " AND `key` = 'end_time'");
                      $get_end_time = mysql_fetch_assoc($get_end_time_sql);
                      $end_time = $get_end_time['value'];

                      $postUrl = 'https://www.motiv8search.com/' . $NewsRows['post_filename'];

                    ?>
                      <p>
                        <a href="<?= $postUrl ?>" target="_blank" class="btn-link nopad">
                          <strong><?php echo $NewsRows['post_title'] ?></strong>
                        </a>
                      </p>
                      <?php if (!empty($NewsRows['post_live_date']) && !empty($NewsRows['revision_timestamp'])) { ?>
                        <p>Start/Publish : <?php echo DateTime::createFromFormat('YmdHis', $NewsRows['post_live_date'])->format('d/m/Y'); ?></p>
                        <p>Last Edit : <?php echo date('d/m/Y', strtotime($NewsRows['revision_timestamp'])); ?></p>
                      <?php } ?>

                    <?php
                      if ($NewsRows['post_status'] == '0') {
                        // Draft
                        echo '<span style="font-size: 100%;" class="label label-warning">' . $inComplet . '</span>';
                      } elseif ($NewsRows['post_status'] == '1') {
                        // Published
                        echo '<span style="font-size: 100%;" class="label label-success">' . $complet . '</span>';
                      }
                      echo '<span style="margin: 0 2px;"></span>';
                      echo "<br> <br>";
                    }
                    ?>
                    <?php if ($nsrowCount >= 2) { ?>
                      <button type="button" class="btn btn-link nolpad more-news" data-uid="<?= $udRows['user_id'] ?>" data-pid="<?= $dpRows['post_id'] ?>" data-toggle="tooltip" title="View More">
                        Click to view more
                        <i class="fa fa-info-circle" style="color:#006fbb;cursor: pointer;"></i>
                      </button>
                    <?php } ?>
                  </div>
                </td>
                <td>
                  <?php
                  $token = $udRows['user_id'];
                  $tblpromoquery = mysqli_query($conn, "SELECT * FROM `tblpromotions` WHERE tblpromotions.notes='$token' ORDER BY `id` DESC");
                  $usedCount = 0;
                  $unusedCount = 0;
                  $totalCount = 0;
                  while ($coupon_data = mysqli_fetch_assoc($tblpromoquery)) {
                    $CUstartDate = $coupon_data['startdate'];
                    $CUexpDate = $coupon_data['expirationdate'];
                    $start = date("M d, Y", strtotime($CUstartDate));
                    $exp = date("M d, Y", strtotime($CUexpDate));
                    $isUsed = $coupon_data['maxuses'] == $coupon_data['uses'];

                    if ($isUsed) {
                      $usedCount++;
                      $usedClass = 'usedcu';
                    } else {
                      $unusedCount++;
                      $usedClass = '';
                    }
                    $totalCount = $usedCount + $unusedCount;
                  }
                  if ($totalCount > 0) {
                  ?>

                    <div>
                      <p>Total credit: <strong><?= $totalCount ?></strong></p>
                      <p>Used: <strong><?= $usedCount ?></strong></p>
                      <p>Unused: <strong><?= $unusedCount ?></strong></p>

                      <button type="button" class="btn btn-link nolpad view-details" data-uid="<?= $udRows['user_id'] ?>" data-pid="<?= $udRows['post_id'] ?>" data-toggle="tooltip" title="View Details">
                        Click to view details
                        <i class="fa fa-info-circle" style="color:#006fbb;cursor: pointer;"></i>
                      </button>

                      <!-- <span class="view-details" data-uid="<?= $udRows['user_id'] ?>" data-pid="<?= $udRows['post_id'] ?>" data-toggle="tooltip" title="View Details">
                        
                      </span> -->
                    </div>
                  <?php  } ?>
                </td>
                <td>
                  <div class="incomplete_fields_detail">
                    <?php
                    $subscription = getSubscription($user['subscription_id'], $w);
                    $contact_details_form = $listing_details_form = $about_form = $complete_resume = $complete_about = $complete_contact = false;
                    if (form::isFormComplete($subscription['contact_details_form'], $udRows['user_id'])) {
                      $contact_details_form = true;
                    }
                    if (form::isFormComplete($subscription['listing_details_form'], $udRows['user_id'])) {
                      $listing_details_form = true;
                    }
                    if (form::isFormComplete($subscription['about_form'], $udRows['user_id'])) {
                      $about_form = true;
                    }
                    $users_settingSQL = mysql_query(
                      "SELECT 
                            complete_contact, 
                            complete_resume, 
                            complete_photo, 
                            complete_about, 
                            complete_portfolio,
                            show_complete 
                          FROM 
                            users_setting 
                          WHERE 
                            user_id = " . $udRows['user_id']
                    );
                    while ($user = mysql_fetch_array($users_settingSQL)) {
                      $complete_contact = ($user['complete_contact']) ? true : false;
                      $complete_resume = ($user['complete_resume']) ? true : false;
                      $complete_about = ($user['complete_about']) ? true : false;
                    }
                    ?>
                    <!-- Contact Details -->
                    <span style="color: <?php echo ($complete_contact || $contact_details_form) ? 'green' : 'red'; ?>;" class="glyphicon glyphicon-<?php echo ($complete_contact || $contact_details_form) ? 'ok' : 'remove'; ?>"></span>
                    <span><?php echo ($complete_contact || $contact_details_form) ? 'Contact Details - Completed' : 'Contact Form - Not Completed'; ?></span><br>
                    <!-- Page Info -->
                    <span style="color: <?php echo ($complete_resume || $listing_details_form) ? 'green' : 'red'; ?>;" class="glyphicon glyphicon-<?php echo ($complete_resume || $listing_details_form) ? 'ok' : 'remove'; ?>"></span>
                    <span><?php echo ($complete_resume || $listing_details_form) ? 'Page Info - Completed' : 'Page Info - Not Completed'; ?></span><br>
                    <!-- Logo -->
                    <span style="color: <?php echo ($user['show_complete'] || $user_logo_view) ? 'green' : 'red'; ?>;" class="glyphicon glyphicon-<?php echo ($user['show_complete'] || $user_logo_view) ? 'ok' : 'remove'; ?>"></span>
                    <span><?php echo ($user['show_complete'] || $user_logo_view) ? 'Logo - Completed' : 'Logo - Not Completed'; ?></span><br>
                    <!-- Page Overview -->
                    <span style="color: <?php echo ($complete_about || $about_form) ? 'green' : 'red'; ?>;" class="glyphicon glyphicon-<?php echo ($complete_about || $about_form) ? 'ok' : 'remove'; ?>"></span>
                    <span><?php echo ($complete_about || $about_form) ? 'Page Overview - Completed' : 'Page Overview - Not Completed'; ?></span><br>
                    <hr>
                    <br>
                    <!-- Owner's Name Section -->
                    <div class="owners_name" data-id="<?php echo $udRows['user_id']; ?>" data-type="owner" data-toggle="tooltip" title="Click to edit">
                      <span class="badge badge-primary p-2 owners-badge d-none">
                        <?php
                        $owner = $udRows['owners'] ? $udRows['owners'] : 'George Osborn';
                        echo ucwords(str_replace('_', ' ', $owner));
                        ?>
                      </span>
                      <select name="owners_name_<?php echo $udRows['user_id']; ?>" id="inputowners_name_<?php echo $udRows['user_id']; ?>" class="form-control owners-select d-none">
                        <option value="">Select Owner</option>
                        <?php
                        $ownerSql = "SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4";
                        $query = mysql_query($ownerSql);
                        while ($owner = mysql_fetch_assoc($query)) {
                          //echo "SELECT * FROM `users_photo` WHERE user_id = '" . $owner['user_id'] . "' AND type = 'photo' LIMIT 1";
                          $user_logo_view = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $owner['user_id'] . "' AND type = 'photo' LIMIT 1"));
                          $imgSrc = $user_logo_view
                            ? "https://www.motiv8search.com/pictures/profile/" . $user_logo_view['file']
                            : "https://www.motiv8search.com/images/profile-profile-holder.png";
                          $selected = $owner['user_id'] == $udRows['owners'] ? "selected" : "";
                          echo "<option data-thumbnail='{$imgSrc}' value='{$owner['user_id']}' $selected>
                    {$owner['first_name']} {$owner['last_name']}
                    </option>";
                        }
                        ?>
                      </select>
                      <input type="hidden" id="inputowners_name_hidden_<?php echo $udRows['user_id']; ?>" value="<?php echo $udRows['owners']; ?>">
                      <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownOwners_<?php echo $udRows['user_id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          <span id="selectedOwnerText_<?php echo $udRows['user_id']; ?>">Select Owner</span>
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu ownersdropdown" aria-labelledby="dropdownOwners_<?php echo $udRows['user_id']; ?>">
                          <?php
                          $ownerSql = "SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4 AND `active` = 2";
                          $query = mysql_query($ownerSql);
                          while ($owner = mysql_fetch_assoc($query)) {
                            $user_logo_view = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $owner['user_id'] . "' AND type = 'photo' LIMIT 1"));
                            $imgSrc = $user_logo_view
                              ? "https://www.motiv8search.com/pictures/profile/" . $user_logo_view['file']
                              : "https://www.motiv8search.com/images/profile-profile-holder.png";
                            echo "<li data-value='{$owner['user_id']}' data-thumbnail='{$imgSrc}'>
                              <label><img src='{$imgSrc}' alt='Profile' style='width: 20px; height: 20px; border-radius: 50%; margin-right: 5px;'>{$owner['first_name']} {$owner['last_name']}</label>
                              </li>";
                          }
                          ?>
                        </ul>
                      </div>
                    </div>

                  </div>
                </td>
                <td class="comments-td" style="position: relative;">
                  <?php
                  //$commentSql = "SELECT id, user_id, comments AS comment_body, comment_by AS user_name, date AS comment_date FROM supplier_comments WHERE user_id = '" . $udRows['user_id'] . "'ORDER BY `supplier_comments`.`id` DESC LIMIT 2";
                    $commentSql = "SELECT id, user_id, comments AS comment_body, comment_by AS user_name, date AS comment_date FROM supplier_comments WHERE user_id = '" . $videos['user_id'] . "' AND post_id = '" . $videos['post_id'] . "' ORDER BY `supplier_comments`.`id` DESC LIMIT 2";
                  // echo $commentSql;
                  $commentResult = mysql_query($commentSql);
                  $rowCount = mysql_num_rows($commentResult);
                  $currentRow = 0;
                  while ($commentRow = mysql_fetch_assoc($commentResult)) {
                    $currentRow++; ?>

                    <span>
                      <b><?php echo $commentRow['user_name']; ?>: </b>
                      <span class="<?php echo ($currentRow === 1) ? 'updated-comment-text' : ''; ?>">
                        <?php echo htmlspecialchars(substr($commentRow['comment_body'], 0, 45)); ?>
                      </span>
                      <form action="" method="post" class="delete_form" onclick="return confirmDelete(event, '<?php echo $commentRow['id']; ?>');">
                        <input type="hidden" name="cid" value="<?php echo $commentRow['id']; ?>">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </form>
                      <br>
                      <div class="d-flex">
                        <?php if ($currentRow === $rowCount && $currentRow == 2) { ?>
                          <span>
                            <small class="btn-link more-comments" data-uid="<?= $udRows['user_id'] ?>" data-pid="<?= $udRows['post_id'] ?>">
                              view more comments
                            </small>
                          </span>
                        <?php } ?>
                        <span class="float-right"><b><?php echo !empty($commentRow['comment_date']) ? date("d-M-Y", strtotime($commentRow['comment_date'])) : 'Unknown Date'; ?></b></span>
                      </div>
                      <hr>
                    </span>

                  <?php } ?>
                  <textarea data-id="<?php echo $udRows['user_id']; ?>" data-type="comment" data-pid="<?= $udRows['post_id'] ?>" class="comments form-control" rows="3" placeholder="Write a comment..." style="position: absolute;bottom: 5px;width: calc(100% - 15px);"></textarea>
                </td>
              </tr>
            <?php }
            ?>
          </tbody>
        </table>
        <!-- </div> -->
        <!-- Pagination -->
        <?php
        $total_items_query = "SELECT COUNT(*) AS total FROM `users_data` AS ud WHERE ud.subscription_id IN (30, 33) $whereClause ";
        //echo $total_items_query;
        $row = mysql_fetch_assoc(mysql_query($total_items_query));
        $total_records = $row['total'];
        $total_pages = ceil($total_records / $limit);
        ?>
        <nav style="float: right;" class="hide">
          <ul class="pagination">
            <?php
            // Build query string for filters
            $queryParams = $_GET; // Capture existing query parameters
            unset($queryParams['page']); // Exclude 'page' as we'll set it dynamically

            $baseUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=suppliers_overview';
            $queryString = http_build_query($queryParams); // Convert filters into query string

            // Generate URLs with filters
            $generatePageUrl = function ($page) use ($baseUrl, $queryString) {
              return $baseUrl . '&' . $queryString . '&page=' . $page;
            };

            if ($page > 1): ?>
              <li><a href="<?php echo $generatePageUrl($page - 1); ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="<?php echo $i == $page ? 'active' : ''; ?>">
                <a href="<?php echo $generatePageUrl($i); ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
              <li><a href="<?php echo $generatePageUrl($page + 1); ?>">Next</a></li>
            <?php endif; ?>
          </ul>
        </nav>

        <!-- Modal -->
        <div id="credit_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Credit History</h4>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Credits Code</th>
                        <th>Events</th>
                      </tr>
                    </thead>
                    <tbody id="credit_details">
                      <tr>
                        <td>
                          <p class="<?= $usedClass ?>">
                            8ROSQWV9T
                          </p>
                        </td>
                        <td>
                          Harley-Davidson Event - 19 March 2025
                          <a href="http://" target="_blank" rel="noopener noreferrer">
                            &nbsp;<i class="fa fa-external-link fa-fw"></i> View Event
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div id="comments_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="commentsModalLabel">Suppliers Comments</h4>
              </div>
              <div class="modal-body">
                <div class="comment-box">
                  <img src="https://www.motiv8search.com/images/profile-profile-holder.png" alt="User Profile">
                  <div class="comment-content">
                    <div class="comment-header">
                      <h5>Ralph Edwards</h5>
                      <small>August 19, 2021</small>
                    </div>
                    <div class="comment-body">
                      <p>In mauris porttitor tincidunt mauris massa sit lorem sed scelerisque. Fringilla pharetra vel massa enim sollicitudin cras. At pulvinar eget sociis adipiscing eget donec ultricies nibh tristique.</p>
                    </div>
                    <div class="comment-footer d-none">
                      <span><i class="glyphicon glyphicon-thumbs-up"></i>5 Likes</span>
                      <span><i class="glyphicon glyphicon-comment"></i>3 Replies</span>
                      <a href="#">Reply</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div id="events_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Event Details</h4>
              </div>
              <div class="modal-body">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 30%;">Event Title</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Status</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  <tbody id="events_table">
                    <!-- Rows will be populated dynamically -->
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div id="news_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">News Details</h4>
              </div>
              <div class="modal-body">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 40%;">News Title</th>
                      <th>Start/Publish Date</th>
                      <th>Last Edit</th>
                      <th>Status</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  <tbody id="news_table">
                    <!-- Rows will be populated dynamically -->
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
// Close the connection
$conn->close();
?>
<script>
  function populateSupplierInfo(modalId, supplierInfo) {
    const supplierHtml = `
            <div class="supplier-info">
                <p><strong>Name:</strong> ${supplierInfo.name || 'N/A'}</p>
                <p><strong>Member ID:</strong> ${supplierInfo.user_id || 'N/A'}</p>
                <p><strong>Company:</strong> ${supplierInfo.company || 'N/A'}</p>
                <p><strong>Email:</strong> <a href="mailto:${supplierInfo.email || ''}">${supplierInfo.email || 'N/A'}</a></p>
            </div>
        `;
    $(`#${modalId} .modal-body`).prepend(supplierHtml);
  }

  function showLoadingSpinner(modalId, tableId) {
    const loadingHtml = `
    <tr><td colspan="5">
    <div class="loading-spinner text-center">
      <img src="/images/bars-loading.gif" alt="Loading..." style="width: 50px;">
      <p>Loading, please wait...</p>
    </div></td></tr>
    `;
    $(`#${modalId} #${tableId}`).html(loadingHtml);
  }
  $(document).ready(function() {
    $('#credit_modal').modal('hide');
    $('#comments_modal').modal('hide');
    $('#events_modal').modal('hide');
    $('#news_modal').modal('hide');

  });
  $(document).on('click', '.view-details', function() {
    let $element = $(this);
    let uid = $element.data('uid');
    let pid = $element.data('pid');
    $('#credit_modal').modal('show');
    showLoadingSpinner('credit_modal', 'credit_details');
    $.ajax({
      type: "POST",
      url: "<?php echo $website_domain ?>/api/widget/json/get/get_credit_details_ajax",
      data: {
        uid: uid,
        pid: pid
      },
      dataType: "json",
      success: function(response) {
        if (response.success) {
          const details = response.data;
          const supplier = response.supplier;
          console.log(details);
          let rows = '';

          details.forEach(detail => {
            const isUsed = detail.is_used; // Check if the code is used
            const usedClass = isUsed ? 'usedcu' : ''; // Add class if used
            const eventTitle = isUsed ? detail.post_title : ''; // Show title only if used
            const eventLink = isUsed ? `
              <a href="<?php echo $website_domain ?>/${detail.post_link}" target="_blank" rel="noopener noreferrer">
                &nbsp;<i class="fa fa-external-link fa-fw"></i> View Event
              </a>
            ` : ''; // Show link only if used

            rows += `
              <tr>
                <td class="${usedClass}">${detail.code}</td>
                <td>
                  ${eventTitle}
                  ${eventLink}
                </td>
              </tr>
            `;
          });

          $('#credit_details').html(rows);
          $('#credit_modal .modal-body').find('.supplier-info').remove();
          populateSupplierInfo('credit_modal', supplier);
          $('#credit_modal').modal('show');
        } else {
          //alert(response.message);
          $('#credit_modal').modal('hide');
          swal("Action Warning!", response.message, "warning");
        }
      },
      error: function() {
        $('#credit_modal').modal('hide');
        swal("Action Failed!", "Failed to fetch details. Please try again.", "error");
      }
    });
  });
  $(document).on('click', '.more-comments', function() {
    let $element = $(this);
    let uid = $element.data('uid');
	let PID = $element.data('pid');
    const loadHtml = `
    <div class="loading-spinner text-center">
      <img src="/images/bars-loading.gif" alt="Loading..." style="width: 50px;">
      <p>Loading, please wait...</p>
    </div>`;
    $('#comments_modal').modal('show');
    $(`#comments_modal .modal-body`).html(loadHtml);
    $.ajax({
      type: "POST",
      url: "<?php echo $website_domain ?>/api/widget/json/get/get_suppliers_comments_ajax",
      data: {
        uid: uid,
	    pid: PID
      },
      dataType: "json",
      success: function(response) {
        if (response.success) {
          const details = response.data;
          console.log(details);
          let commentsHtml = '';

          details.forEach(detail => {
            // const userProfile = detail.user_image ?
            //   detail.user_image :
            //   "https://www.motiv8search.com/images/default_profile_photo.jpg";
            const userProfile = "https://www.motiv8search.com/images/default_profile_photo.jpg" ;
            const commentDate = detail.comment_date || "Unknown Date";
            const userName = detail.user_name || "Anonymous User";
            const commentBody = detail.comment_body || "No content available.";
            const userId = detail.id || "0";

            commentsHtml += `
                      <div class="comment-box">
                          <img src="https://www.motiv8search.com/logos/profile/${userProfile}" alt="User Profile">
                          <div class="comment-content">
                              <div class="comment-header">
                                  <h5>${userName}</h5>
                                  <small>${commentDate}</small>
                                  <form action="" method="post" class="delete_form pull-right" onclick="return confirmDelete(event, '${userId}');">
                                    <input type="hidden" name="cid" value="${userId}">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                  </form>
                              </div>
                              <div class="comment-body">
                                  <p>${commentBody}</p>
                              </div>
                              <div class="comment-footer d-none">
                                  <span><i class="glyphicon glyphicon-thumbs-up"></i> 0 Likes</span>
                                  <span><i class="glyphicon glyphicon-comment"></i> 0 Replies</span>
                                  <a href="#">Reply</a>
                              </div>
                          </div>
                      </div>
                    `;
          });

          // Update the modal's body
          $('#comments_modal .modal-body').html(commentsHtml);

          // Show the modal
          $('#comments_modal').modal('show');
        } else {
          // Handle failure response
          $('#comments_modal').modal('hide');
          swal("Action Warning!", response.message, "warning");
        }
      },
      error: function() {
        $('#comments_modal').modal('hide');
        // Handle AJAX error
        swal("Action Failed!", "Failed to fetch details. Please try again.", "error");
      }
    });
  });
	  
  function decodeHTML(html) {
	const txt = document.createElement('textarea');
	txt.innerHTML = html;
	return txt.value;
  }

  $(document).on('click', '.more-events', function() {
    let $element = $(this);
    let uid = $element.data('uid');
    $('#events_modal').modal('show');
    showLoadingSpinner('events_modal', 'events_table');
    $.ajax({
      type: "POST",
      url: "<?php echo $website_domain ?>/api/widget/json/get/get_events_details_ajax",
      data: {
        uid: uid
      },
      dataType: "json",
      success: function(response) {
        if (response.success) {
          const events = response.data;
          const supplier = response.supplier;
          let rows = '';

          events.forEach(event => {
            const statusLabel = event.status === '1' ?
              '<span style="font-size: 100%;" class="label label-warning">Incomplete</span>' :
              '<span style="font-size: 100%;" class="label label-success">Published</span>';
            const eventType = event.event_type === 'Upcoming' ?
              '<span style="font-size: 100%;" class="label label-default">Upcoming</span>' :
              '<span style="font-size: 100%;" class="label label-danger">Past</span>';
            let videoOption = '';
            switch (event.video_option) {
              case 'superbooth':
                videoOption = '<span style="font-size: 100%;" class="label label-purple">SuperBooth</span>';
                break;
              case 'link':
                videoOption = '<span style="font-size: 100%;" class="label label-primary">Presentation</span>';
                break;
              case 'none':
                videoOption = '<span style="font-size: 100%;" class="label label-info">Desktop</span>';
                break;
              default:
                videoOption = '<span class="badge badge-secondary">Unknown</span>';
            }
						 
			let supplierLabels = '';
						 
			if (event.labels && event.labels.length > 0) {
				supplierLabels = event.labels
					.filter(l => l.text_label && l.text_label.trim() !== '')
					.map(l => `<span style="
						font-size:100%;
						background-color:${l.color_code || '#999'};
						color:#fff;
						padding:3px 6px;
						border-radius:3px;
						margin:2px 2px 0 3px;
						white-space:nowrap;
						max-width:150px;
						overflow:hidden;
						text-overflow:ellipsis;
					">${decodeHTML(l.text_label)}</span>`)
					.join('');
			}

            rows += `
                        <tr>
                            <td>${event.title}</td>
                            <td>${event.start_time}</td>
                            <td>${event.end_time}</td>
                            <td>${statusLabel} ${videoOption} ${eventType}${supplierLabels}</td>
                            <td>
                                <a href="${event.link}" target="_blank" rel="noopener noreferrer">
                                  &nbsp;<i class="fa fa-external-link fa-fw"></i>
                                  Go to Events
                                </a>
                            </td>
                        </tr>
                    `;
          });

          $('#events_modal .modal-body').find('.supplier-info').remove();
          $('#events_modal .modal-body').find('.loading-spinner').remove();
          $('#events_table').html(rows); // Populate table
          populateSupplierInfo('events_modal', supplier);
          $('#events_modal').modal('show'); // Show modal
        } else {
          $('#events_modal').modal('hide');
          swal("Action Warning!", response.message, "warning");
        }
      },
      error: function() {
        $('#events_modal').modal('hide');
        swal("Action Failed!", "Failed to fetch events. Please try again.", "error");
      }
    });
  });
  $(document).on('click', '.more-news', function() {
    let $element = $(this);
    let uid = $element.data('uid');
    $('#news_modal').modal('show');
    showLoadingSpinner('news_modal', 'news_table');
    $.ajax({
      type: "POST",
      url: "<?php echo $website_domain ?>/api/widget/json/get/get_news_details_ajax",
      data: {
        uid: uid
      },
      dataType: "json",
      success: function(response) {
        if (response.success) {
          const news = response.data;
          const supplier = response.supplier;
          let rows = '';

          news.forEach(item => {
            let statusLabel = '';
            switch (item.post_status) {
              case '1':
                statusLabel = '<span style="font-size: 100%;" class="label label-success">Published</span>';
                break;
              case '0':
                statusLabel = '<span style="font-size: 100%;" class="label label-warning">Incomplete</span>';
                break;
              default:
                statusLabel = '<span class="badge badge-secondary">Unknown</span>';
            }
            // const statusLabel = item.post_status === '1'
            //     ? '<span class="badge badge-success">Published</span>'
            //     : '<span class="badge badge-warning">Draft</span>';

            rows += `
                        <tr>
                            <td>${item.post_title}</td>
                            <td>${item.start_date || 'N/A'}</td>
                            <td>${item.last_edit || 'N/A'}</td>
                            <td>${statusLabel}</td>
                            <td>
                                <a href="${item.link}" target="_blank" rel="noopener noreferrer">
                                  &nbsp;<i class="fa fa-external-link fa-fw"></i>
                                  Go to News Post
                                </a>
                            </td>
                        </tr>
                    `;
          });

          $('#news_modal .modal-body').find('.supplier-info').remove();
          $('#news_modal .modal-body').find('.loading-spinner').remove();
          $('#news_table').html(rows); // Populate the table
          populateSupplierInfo('news_modal', supplier);
          $('#news_modal').modal('show'); // Show the modal
        } else {
          $('#news_modal').modal('hide');
          swal("Action Warning!", response.message, "warning");
        }
      },
      error: function() {
        $('#news_modal').modal('hide');
        swal("Action Failed!", "Failed to fetch news. Please try again.", "error");
      }
    });
  });

  $(document).ready(function() {
    // Set default selected values
    $('.owners_name').each(function() {
      const $parentDiv = $(this);
      const preselectedValue = $parentDiv.find('input[type="hidden"]').val();
      const $dropdownItems = $parentDiv.find('.ownersdropdown li');

      $dropdownItems.each(function() {
        if ($(this).data('value') == preselectedValue) {
          $(this).addClass('selected');
          const selectedText = $(this).text();
          const selectedImage = $(this).data('thumbnail');

          $parentDiv.find('#selectedOwnerText_' + $parentDiv.data('id')).html(
            '<img src="' +
            selectedImage +
            '" alt="Profile" style="height: 20px; width: 20px; border-radius: 50%; margin-right: 10px;">' +
            selectedText
          );
        }
      });
    });

    // Handle dropdown item clicks
    $(document).on('click', '.ownersdropdown li', function() {
      const $this = $(this);
      const $parentDiv = $this.closest('.owners_name');
      const selectedValue = $this.data('value');
      const selectedText = $this.text();
      const selectedImage = $this.data('thumbnail');

      // Update selected class
      $parentDiv.find('.ownersdropdown li').removeClass('selected');
      $this.addClass('selected');

      // Update displayed text and image
      $parentDiv.find('#selectedOwnerText_' + $parentDiv.data('id')).html(
        '<img src="' +
        selectedImage +
        '" alt="Profile" style="height: 20px; width: 20px; border-radius: 50%; margin-right: 10px;">' +
        selectedText
      );

      // Update hidden input
      $parentDiv.find('input[type="hidden"]').val(selectedValue);

      // Trigger change event for the select input
      $parentDiv.find('select').val(selectedValue).trigger('change');
    });

    // Handle select change (e.g., form submission)
    $(document).on('change', '.owners_name select', function() {
      const $select = $(this);
      const selectedValue = $select.val();
      const $parentDiv = $select.closest('.owners_name');
      const id = $parentDiv.data('id');
      const type = $parentDiv.data('type');

      if (selectedValue) {
        swal({
          imageUrl: '//www.securemypayment.com/directory/cdn/images/bars-loading.gif',
          title: 'Saving Changes',
          text: 'Please wait...',
          showConfirmButton: false,
          allowOutsideClick: false,
        });

        $.post(window.location.href, {
            id: id,
            content: selectedValue,
            type: type
          })
          .done(function(response) {
            swal({
              type: 'success',
              title: 'Success!',
              text: "Owner's name updated successfully.",
            });
            setTimeout(function() {
              swal.closeModal();
            }, 750);
          })
          .fail(function() {
            swal('Action Failed!', "Failed to update owner's name. Please try again.", 'error');
          });
      }
    });
  });
</script>
<script>
  $(document).on("blur", ".comments", function() {
    const $el = $(this);
    let updatedText = $el.val().trim();
    const type = $el.data("type");
    const id = $el.data("id");
    const postId = $el.data("pid");

    if (!id || !type) {
      swal("Action Failed!", "Invalid data. Please try again.", "error");
      return;
    }

    if (!updatedText) {
      swal("Warning!", "Comment cannot be empty or null.", "warning");
      return;
    }

    $el.prop("disabled", true);
    $.post(window.location.href, {
        id: id,
        content: updatedText,
        type: type,
        postId: postId
      })
      .done(function() {
        $el.val('');
        $el.prop("disabled", false);
        swal("Action Successful!", "Comment updated successfully.", "success")
  .then(() => {
    // Reload the page after the Swal notification is closed
    window.location.reload();
  });

        updatedText = updatedText.substring(0, 45);
        const $commentSpan = $el.closest('td').find('.updated-comment-text');
        if ($commentSpan.length > 0) {
          $commentSpan.text(updatedText);
        } else {
          const adminName = "<?php echo $sess['admin_name']; ?>";
          const commentDate = new Date().toLocaleDateString("en-GB", {
            day: "2-digit",
            month: "short",
            year: "numeric"
          });
          $el.closest('td').append(`
            <span>
              <b>${adminName}: </b><span class="updated-comment-text">${updatedText}</span>
              <br>
              <div class="d-flex">
                <span class="float-right"><b>${commentDate}</b></span>
              </div>
              <hr>
            </span>
          `);
        }
      })
      .fail(function() {
        $el.prop("disabled", false);
        swal("Action Failed!", "Failed to update the comment. Please try again.", "error");
      });
  });
  $(document).ready(function() {
    // When hovering over the "Manage Account" button
   /* $('button[data-user-id],[id^="action-links-"]').hover(
        function() {
            var userId = $(this).data('user-id'); // Get user_id from data attribute
            $('#action-links-' + userId).css('display', 'block'); // Show the corresponding action links
        },
        function() {
            var userId = $(this).data('user-id'); // Get user_id from data attribute
            $('#action-links-' + userId).css('display', 'none'); // Hide the corresponding action links
        }
    );*/
    $('button[data-user-id]').click(function(event) {
        event.preventDefault(); 
        var userId = $(this).data('user-id'); 
        var target = $('#action-links-' + userId);

        // If the clicked element is already visible, just hide it
        if (target.is(':visible')) {
            target.hide();
        } else {
            $('[id^="action-links-"]').hide(); // Hide all others
            target.show(); // Show only the clicked one
        }
    });


  });

  $(document).on("click", ".delete_member_plugin", function (e) {
      e.preventDefault(); 
      var member_id  = $(this).attr('attr-id');
   var member_name = "<?php echo $loggedname; ?>";
   var member_email = "<?php echo $loggeduser; ?>";	  
      swal({
          title: "Delete Member",
          text: "Deleting member can delete entire images and posts", 
          icon: "warning", 
          buttons: ["No, cancel", "Yes, continue"], 
          dangerMode: true, 
      }).then(function(isConfirm) {
          if (isConfirm) {
              $.ajax({
                url: '<?php echo $website_domain ?>/api/widget/html/get/ajax-delete-member-suppliers-event',
                data: {id: member_id,name:member_name, useremail:member_email},
          type: 'GET',
              })
              .done(function() {
                swal({
                  title: "Final Confirmation", 
                  text: "Permanently delete this member? This action cannot be reversed.",
                  icon: "warning", 
                  buttons: ["No, cancel", "Yes, continue"], 
                  dangerMode: true,
              }).then(function(finalResult) {
                  if (finalResult) {
                    location.reload();
                  } else {
                      swal.close();
                  }
              });
          
              });
        
          } else {
              swal.close();
          }
      });
  });


  $(".popup, .popup-websites, .popup-large, .iframe-link").fancybox({
    type: "iframe",
    afterClose: function () { 
        var loc = parent.location.href;
        if (loc.search('billingPromotions.php') >= 0) {
            parent.location.reload(true);
        }
    },
    autoDimensions: false,
    iframe : {
        css : {
            width : '100%',
            height: '100%',
            'border-radius': '3px 3px 4px 4px'
        }
    },
    buttons: [
        'fullScreen',
        'close'
    ]
  });

  $().fancybox({
      selector : '.popup',
      loop     : true,
      type: "iframe",
      autoDimensions: false,
      idleTime: 99999,
      afterClose: function () { 
          var loc = parent.location.href;
          if (loc.search('billingPromotions.php') >= 0) {
              parent.location.reload(true);
          }
      },
      iframe : {
          css : {
              width : '100%',
              height: '100%',
              padding: '20px 0 0 0',
              'border-radius': '3px 3px 4px 4px'
          }
      },
      buttons: [
          'fullScreen',
          'close'
      ]
  });
	  $().fancybox({
      selector : '.popup-email',
      loop     : true,
      type: "iframe",
      autoDimensions: false,
      idleTime: 99999,
      iframe : {
          css : {
              width : '100%',
              height: '100%',
              padding: '20px 0 0 0',
              'border-radius': '3px 3px 4px 4px',
              'max-width': '940px'
          }
      },
      buttons: [
          'fullScreen',
          'close'
      ]
    });
  $(".popup-wizard").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      width: 800,
      height: 500,
      autoScale: true
  });
  $("#accountchange").fancybox({
      overlayOpacity: "0.7",
      width: "820",
      height: "500",
      autoScale: "true"
  });
  $(".cropme").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      width: "900",
      height: "500",
      autoScale: "true"
  });
  $("#wizard").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      width: "820",
      height: "500",
      autoScale: "true"
  });
  $("#signupbox").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      autoScale: "false",
      width: "820",
      height: "500"
  });
  $("#newsletterbox").fancybox({
      overlayOpacity: "0.7",
      width: "820",
      height: "500"
  })
</script>
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