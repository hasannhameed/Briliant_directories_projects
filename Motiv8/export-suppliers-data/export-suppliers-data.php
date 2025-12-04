<?php
// Include DB connection and global settings
$website_domain = "https://www.motiv8search.com";

// Validate input
if (empty($_GET['post_id'])) {
  die('post_id is required.');
}

$postId = (int)$_GET['post_id'];

// Get post title BEFORE sending headers
$postData = mysql_fetch_assoc(mysql_query("SELECT post_title FROM data_posts WHERE post_id = '$postId'"));
$postTitle = !empty($postData['post_title']) ? $postData['post_title'] : 'event';

// Sanitize post title for filename (remove special characters)
$sanitizedTitle = preg_replace('/[^A-Za-z0-9\- ]/', '', $postTitle);
$sanitizedTitle = str_replace(' ', '-', $sanitizedTitle);
$filename = $sanitizedTitle . '-event-data.csv';

// Send CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Start output
$output = fopen('php://output', 'w');
fputcsv($output, array('Company Name', 'Full Name', 'Description', 'Labels', 'Equipment', 'Comments'));

// Fetch supplier data
$get_videos_sql = mysql_query("
  SELECT
      lep.*,
      ud.first_name,
      ud.last_name,
      ud.company,
      ud.user_id
  FROM `live_events_posts` AS lep
  LEFT JOIN users_data as ud ON lep.user_id = ud.user_id 
  WHERE lep.post_id = '$postId'
  ORDER BY lep.id DESC
");

while ($videos = mysql_fetch_assoc($get_videos_sql)) {
  $userId = (int)$videos['user_id'];

  $description = ($videos['video_option'] == 'link') ? $videos['event_name'] : $videos['event_description'];

    // Labels with status and video_option
    $labels = array();

    // Status label
    // if ($videos['staus'] == '1') {
    //   $labels[] = 'Incomplete';
    // } elseif ($videos['staus'] == '2') {
    //   $labels[] = 'Published';
    // }
  
    // Video option label
    switch ($videos['video_option']) {
      case 'link':
        $labels[] = 'Presentation';
        break;
      case 'none':
        $labels[] = 'Desktop';
        break;
      case 'superbooth':
        $labels[] = 'Superbooth';
        break;
      case 'other':
        $labels[] = 'Other';
        break;
    }
  
    // Existing labels from DB
    $labelSql = "
      (SELECT srf.user_id, srf.add_on, srf.event_id, sl.id, sl.text_label, sl.color_code, sl.type 
       FROM supplier_registration_form srf 
       LEFT JOIN supplier_labels sl ON srf.user_id = sl.user_id AND srf.event_id = sl.post_id 
       WHERE srf.user_id = $userId AND srf.event_id = $postId)
      UNION ALL
      (SELECT sl.user_id, NULL AS add_on, NULL AS event_id, sl.id, sl.text_label, sl.color_code, sl.type 
       FROM supplier_labels sl 
       LEFT JOIN supplier_registration_form srf ON sl.user_id = srf.user_id AND sl.post_id = srf.event_id 
       WHERE (srf.user_id IS NULL OR srf.event_id IS NULL) AND sl.user_id = $userId AND sl.post_id = $postId)
      ORDER BY id ASC";
    
    $labelQuery = mysql_query($labelSql);
    while ($row = mysql_fetch_assoc($labelQuery)) {
      if (!empty($row['text_label'])) {
        $labels[] = strtolower($row['text_label']);
      } elseif (!empty($row['add_on'])) {
        $addOns = array_map('trim', explode(',', $row['add_on']));
        $labels = array_merge($labels, $addOns);
      }
    }
  

  // Equipment
  $equipment = array();
  $equipment_sql = mysql_query("SELECT other_equipment FROM attending_supplier_staff_registration WHERE supplier_id = '$userId' AND post_id = '$postId' AND is_event_coordinator = '1'");
  while ($eq = mysql_fetch_assoc($equipment_sql)) {
    if (!empty($eq['other_equipment'])) {
      $equipment[] = $eq['other_equipment'];
    }
  }

  // Comments
  $comments = array();
  $commentSql = "SELECT comments, comment_by, date FROM supplier_comments WHERE user_id = '$userId' AND post_id = '$postId' ORDER BY id DESC LIMIT 2";
  $commentResult = mysql_query($commentSql);
  while ($commentRow = mysql_fetch_assoc($commentResult)) {
    $comments[] = $commentRow['comment_by'] . ': ' . $commentRow['comments'] . ' (' . date("d-M-Y", strtotime($commentRow['date'])) . ')';
  }

  // Write row
  fputcsv($output, array(
    $videos['company'],
    $videos['first_name'] . ' ' . $videos['last_name'],
    $description,
    implode(', ', $labels),
    implode(', ', $equipment),
    implode("\n", $comments)
  ));
}

fclose($output);
exit;
?>
