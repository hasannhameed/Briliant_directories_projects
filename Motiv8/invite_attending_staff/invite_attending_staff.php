<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['staff_id'], $_POST['staff_name'], $_POST['post_id'], $_POST['supplier_id'], $_POST['lep_id'], $_POST['post_title'], $_POST['post_date'], $_POST['mail_date'], $_POST['supplier_name'])) {
        // Sanitize input data using mysql_real_escape_string
        $id = mysql_real_escape_string(trim($_POST['id']));
        $supplierId = mysql_real_escape_string(trim($_POST['supplier_id']));
        $supplier = mysql_real_escape_string(trim($_POST['supplier_name']));
        $staffId = mysql_real_escape_string(trim($_POST['staff_id']));
        $staffName = mysql_real_escape_string(trim($_POST['staff_name']));
        $staffEmail = mysql_real_escape_string(trim($_POST['staff_email']));
        $postId = mysql_real_escape_string(trim($_POST['post_id']));
        $lepId = mysql_real_escape_string(trim($_POST['lep_id']));
        $postTitle = mysql_real_escape_string(trim($_POST['post_title']));
        $action = mysql_real_escape_string(trim($_POST['action']));
        $postDate   = mysql_real_escape_string($_POST['post_date']);
        $mailDate   = mysql_real_escape_string($_POST['mail_date']);
		
        // Validate supplier_id
        if (empty($supplierId) || $supplierId == "0" || strtolower($supplierId) === "null") {
            echo json_encode(['success' => false, 'message' => 'Invalid supplier_id. Cannot be 0 or null.']);
            exit;
        }
        $user_data = getUser($_COOKIE['userid'], $w); // Get Current User Data
        $user_full_name = $user_data['first_name'] . ' ' . $user_data['last_name'];
        function activity_log($action, $reference_table, $reference_id, $description = '') {
            $user_id = isset($_COOKIE['userid']) ? intval($_COOKIE['userid']) : 0;
            $user_name = isset($user_full_name) ? mysql_real_escape_string($user_full_name) : (isset($user_data['company']) ? $user_data['company'] : $user_data['email']);

            $action            = mysql_real_escape_string($action);
            $reference_table   = mysql_real_escape_string($reference_table);
            $reference_id      = intval($reference_id);
            $description       = mysql_real_escape_string($description);
            $ip                = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
            $agent             = isset($_SERVER['HTTP_USER_AGENT']) ? mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']) : 'unknown';
            $now               = date('Y-m-d H:i:s');
            $sql = "INSERT INTO activity_log 
                    (user_id, user_name, user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
                    VALUES 
                    ('$user_id', '$user_full_name', 'member', '$action', '$reference_table', '$reference_id', '$description', '$ip', '$agent', '$now')
                    ";

            mysql_query($sql);
        }


        // Generate token and expiration date
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime($postDate));

        // Check if a staff is already assigned to this post
        $checkSql = "SELECT * FROM attending_staff_attendance 
                     WHERE post_id = '$postId' 
                     AND supplier_id = '$supplierId' 
                     AND lep_id = '$lepId'
                     AND id = '$id'";
        $checkResult = mysql_query($checkSql);
        $existingRow = mysql_fetch_assoc($checkResult);
        
        if ($existingRow) {
            // If a staff is already assigned and it's different → Update
            if ($existingRow['staff_id'] !== $staffId) {
                if (empty($staffEmail)) {
                    $staffEmail = "Not Required - We do not need this member";
                }
                 $updateSql = "UPDATE attending_staff_attendance 
                SET staff_id = '$staffId', staff_email = '$staffEmail', token = '$token', `status` = 'TBC'
                WHERE post_id = '$postId' 
                AND supplier_id = '$supplierId' 
                AND lep_id = '$lepId' 
                AND id = '$id'";

                // echo $deleteSql = "DELETE * FROM attending_supplier_staff_registration WHERE supplier_id = '$supplierId' AND post_id = '$postId' AND user_id = '$staffId'";

                if (mysql_query($deleteSql) === TRUE) {
                    echo "Deleted successfully in database";
                } else {
                  echo "Error deleting data: " . mysql_error();
                }
                
                if (mysql_query($updateSql)) {
                    // Log the activity
                    activity_log('updated', 'attending_staff_attendance', $id, "Attending staff updated: $staffName ($staffId) for Supplier ID: $supplierId and Post ID: $postId by $user_full_name");
                    //echo json_encode(['success' => true, 'message' => 'Attending staff updated successfully']);
                } else {
                    //echo json_encode(['success' => false, 'message' => 'Failed to update staff']);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'No changes made. Staff is already assigned.']);
            }
        } else {
            // Check if no record exists, then insert
            $insertCheckSql = "SELECT * FROM attending_staff_attendance 
                               WHERE post_id = '$postId' 
                               AND supplier_id = '$supplierId' 
                               AND lep_id = '$lepId' 
                               AND staff_id = '$staffId'";
            $insertCheckResult = mysql_query($insertCheckSql);
            $insertExistingRow = mysql_fetch_assoc($insertCheckResult);
            
            if ((!$insertExistingRow || $staffId == 0) && $supplierId != "0" && strtolower($supplierId) !== "null") {
                // Insert new record
                if (empty($staffEmail)) {
                    $staffEmail = "Not Required - We do not need this member";
                }
                $insertSql = "INSERT INTO attending_staff_attendance 
                              (supplier_id, post_id, lep_id, staff_id, staff_email, `status`, token, expries) 
                              VALUES ('$supplierId', '$postId', '$lepId', '$staffId', '$staffEmail', 'TBC', '$token', '$expires')";

                if (mysql_query($insertSql)) {
                    // Log the activity
                    activity_log('Add Attending Staff', 'attending_staff_attendance', mysql_insert_id(), 'Attending staff added by ' . $user_full_name . ' for Supplier ID: ' . $supplierId . ' and Staff ID: ' . $staffId . ' for Post ID: ' . $postId);

                    //echo json_encode(['success' => true, 'message' => 'Attending staff added successfully']);
                } else {
                   // echo json_encode(['success' => false, 'message' => 'Failed to add attending staff']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Staff already assigned to this post']);
            }
        }

        $applicationTokenQuery = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '$postId' AND `type` = 'registration form'");
        $applicationTokenResult = mysql_fetch_assoc($applicationTokenQuery);
        $applicationtoken = $applicationTokenResult['token'];

        $supplier_token = mysql_query("SELECT token FROM users_data WHERE email = '".$staffEmail."' ");
        $supplier_token_row = mysql_fetch_assoc($supplier_token);
        $supplier_token_val = $supplier_token_row['token'] ?? '';

        $newUser = false;

        if (mysql_num_rows($supplier_token) < 1 ) {
            $newUser = true;
            $newToken   = bin2hex(random_bytes(16));
            $apiUrl = 'https://www.motiv8search.com/api/widget/json/get/code_block_test_byHasan?email=' 
                    . urlencode($staffEmail) 
                    . '&newToken=' 
                    . urlencode($newToken);

            $response = file_get_contents($apiUrl);

            if ($response !== false) {
                $data = json_decode($response, true);

                if ($data && isset($data['status']) && $data['status'] === 'ok') {
                    $supplier_token_val = $data['token'];
                } else {
                    $supplier_token_val = '';
                    $message = $data['message'] ?? 'Unknown error from API';
                }
            } else {
                $supplier_token_val = '';
                $message = 'Failed to call API';
            }
        }

        
        if($_SERVER['REMOTE_ADDR'] == '49.205.169.64'){
            //$attendingUrl = "https://www.motiv8search.com/login/token/".$supplier_token_val."/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$token;

            $baseUrl                = brilliantDirectories::getWebsiteUrl();
            $encryptedToken         = brilliantDirectories::getEncryptedToken('encrypt', $supplier_token_val);
            $encrypApplicationtoken = $applicationtoken;
            $encrypToken            = $token;
            $redirectPath = "/attending-supplier-staff-registration?ref=" . $encrypApplicationtoken
                . "&token=" . $encrypToken
                . (!empty($newUser) ? '&newUser=' . urlencode($newUser) : '');

            $attendingUrl = $baseUrl . "/login/token/" . urlencode($supplier_token_val)
                . "?login_direct_url=" . urlencode($redirectPath);

            

            //$attendingUrl = $baseUrl . "/login/fromsignup/" . $encryptedToken . "?login_direct_url=" . urlencode($redirectPath);

        }else {
            $attendingUrl = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$token;
        }

        echo '<style>
        a::first-letter {
            text-transform: capitalize;
        }
        </style>';

        $amcherURL = '<a  href="' . $attendingUrl . '" style="text-transform: capitalize">Click link</a>';

        $w['eventname']   		= $postTitle;
		$w['staffname']   		= $staffName;
		$w['suppliercompany']	= $supplier;
	    $w['supplier_token']    = $amcherURL;
		$w['eventstartdate']  	= $postDate;
		$w['deadlinedate']  	= $mailDate;
		
        $emailPrepareOne  = prepareEmail('attending_staff_invitation', $w);

        
        // Send email
        sendEmailTemplate($w['website_email'], $staffEmail, $emailPrepareOne['subject'], $emailPrepareOne['html'], $emailPrepareOne['text'], $emailPrepareOne['priority'], $w);
        // Log the activity for sending email
        activity_log('Send Email', 'attending_staff_attendance', $id, "Email sent to $staffName ($staffEmail) for Supplier ID: $supplierId and Post ID: $postId by $user_full_name");

        // echo json_encode(["success" => true, "message" => "Registration Form Sent!"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
    }
}

// Close the database connection
mysql_close();
?>
