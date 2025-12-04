<?php
if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    die("Invalid request: post_id is missing.");
}

$post_id = intval($_GET['post_id']); 

$user_ids = [];

if (!empty($_GET['user_ids'])) {
    $ids = preg_replace('/[^0-9,]/', '', $_GET['user_ids']); // sanitize input
    $userIds = array_filter(array_map('intval', explode(',', $ids))); // convert to integers
    if ($userIds) {
        $user_ids = implode(',', $userIds);
    }
}

if (!empty($user_ids)) {
    $query = "SELECT r.*
    FROM attending_supplier_staff_registration r
    JOIN attending_staff_attendance a
        ON r.user_id = a.staff_id
        AND a.supplier_id IN ($user_ids)
        AND a.staff_id != 0
        AND a.post_id = $post_id
        AND r.supplier_id != 0
    WHERE a.staff_id IS NOT NULL GROUP BY user_id;";
	
} else {
    $query = "SELECT *
              FROM attending_staff_attendance a";
}




 //echo $query ;

// // print_r($userIds);
  //exit;

$get_post_details = mysql_fetch_assoc(mysql_query("SELECT * FROM data_posts WHERE post_id = '" . $post_id . "'"));
$result = mysql_query($query);

if (!$result) {
    die("SQL Error: " . mysql_error());
}

// Optional: filter by user_ids
// if (!empty($_GET['user_ids'])) {
//     $ids = preg_replace('/[^0-9,]/', '', $_GET['user_ids']);
//     $userIds = array_filter(array_map('intval', explode(',', $ids)));
//     if ($userIds) {
//         $query .= " AND user_id IN (" . implode(',', $userIds) . ")";
//     }
// }

// echo $query ;

//  print_r($userIds);
//  exit;

if (mysql_num_rows($result) == 0) {
    die("No data found for post_id = $post_id");
}

// Collect rows
$rows = [];
$filename = strtolower(preg_replace('/[^A-Za-z0-9 ]/', '', $get_post_details['post_title']));
$filename = str_replace(' ', '-', $filename) . '-attendees.csv';

while ($row = mysql_fetch_assoc($result)) {
    $rows[] = $row;
}

if (empty($rows)) {
    die("Error: Data exists but not being fetched properly!");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Define headers
$headers = [
    'Full Name',
    'Job Title',
    'Email',
    'Mobile Phone Number',
    'Company Name',
    'Company registered for this event',
    'Attended Supplier Engagement Day before',
    'Event Space Requirements',
    'Equipment Requirements'
];

if ($post_id == 979 || $post_id == 984) {
    array_unshift($headers, 'Photo id');
}
if ($post_id == 984) {
    $headers[] = 'Travel Method';
    $headers[] = 'Travel Details';
}

fputcsv($output, $headers);
$company_name='';
$reg_company = '';
// Data rows
foreach ($rows as $row) {
    $company_name='';

    if(empty($row['company_name'])){
        $comp_string   = "SELECT company FROM users_data WHERE user_id = ".$row['supplier_id'];
        $comp_query    = mysql_query($comp_string);
        $comp_data     = mysql_fetch_assoc($comp_query);
        $company_name  = $comp_data['company'];
    }else{
        $company_name = $row['company_name'];
    }

    if(empty($row['registered_company'])){
        $reg_string  = "SELECT company FROM users_data WHERE user_id = ".$row['supplier_id'];
        $reg_query   = mysql_query($reg_string);
        $reg_data    = mysql_fetch_assoc($reg_query);
        $reg_company = $reg_data['company'];
    }else{
        $reg_company = $row['registered_company'];
    }

    $user_string = mysql_query('SELECT * FROM users_data WHERE user_id ='.$row['user_id']);
    $user_data = mysql_fetch_assoc($user_string);


    $rowData = [
        $user_data['first_name'].' '.$user_data['last_name'],
        $row['job_title'],
        $user_data['email'],
        $row['phone_number'],
        $company_name?$company_name:$comp_string,
        $reg_company?$reg_company:$reg_string,
        $row['attended_before'],
        $row['event_space'],
        $row['other_equipment']
    ];

    if ($post_id == 979 || $post_id == 984) {
        array_unshift($rowData, $row['customphotoid']);
    }

    if ($post_id == 984) {
        $rowData[] = $row['travel_method'];
        $rowData[] = $row['travel_details'];
    }

    fputcsv($output, $rowData);

}

fclose($output);
exit;
?>
