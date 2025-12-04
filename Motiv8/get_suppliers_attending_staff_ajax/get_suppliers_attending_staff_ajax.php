<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get search query from the request
    $search = isset($_GET['search']) ? mysql_real_escape_string($_GET['search']) : '';

    // Fetch staff data from the database based on search
    $query = "SELECT user_id, first_name, last_name, email, company FROM users_data WHERE subscription_id IN(30, 33, 36) AND
              (first_name LIKE '%$search%' 
              OR last_name LIKE '%$search%' 
              OR company LIKE '%$search%' 
              OR email LIKE '%$search%') 
              ORDER BY first_name ASC";

    $result = mysql_query($query);
    
    if (mysql_num_rows($result) > 0) {
        $staff = array();
        $attendingStaff = array();
        $attendingStaffQuery = mysql_query("SELECT `supplier_id`, `staff_ids` FROM `supplier_attendingstaffs`");

        if (mysql_num_rows($attendingStaffQuery) > 0) {
            while ($row = mysql_fetch_assoc($attendingStaffQuery)) {
                $staffIds = explode(',', $row['staff_ids']);
                foreach ($staffIds as $staffId) {
                    $attendingStaff[trim($staffId)] = $row['supplier_id']; // Map user_id to supplier_id
                }
            }
        }

        while ($row = mysql_fetch_assoc($result)) {
            $fullname = trim($row['first_name'] . " " . $row['last_name']);
            if (empty($fullname)) {
                $fullname = $row['company']; // Use company name if full name is empty
            }
            $selected = isset($attendingStaff[$row['user_id']]) ? 'checked' : '';
            
            $staff[] = [
                'fullname' => $fullname,
                'uid' => $row['user_id'],
                'email' => $row['email'],
                'selected' => $selected,
                'supplier_id' => isset($attendingStaff[$row['user_id']]) ? $attendingStaff[$row['user_id']] : null // Return supplier_id
            ];
        }

        echo json_encode($staff);
    } else {
        echo json_encode(['success' => false, 'message' => 'No results found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
