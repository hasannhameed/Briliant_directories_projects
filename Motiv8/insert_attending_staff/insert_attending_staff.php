<?php
//This is not Using
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    if (isset($_POST['staff_id'], $_POST['staff_name'], $_POST['post_title'], $_POST['post_id'], $_POST['supplier_id'], $_POST['post_date'], $_POST['supplier_name'], $_POST['lep_id'])) {
        
        $supplier   = mysql_real_escape_string($_POST['supplier_name']);
        $supplierId = mysql_real_escape_string($_POST['supplier_id']);
        $staffId    = mysql_real_escape_string($_POST['staff_id']);
        $staffName  = mysql_real_escape_string($_POST['staff_name']);
        $staffEmail = mysql_real_escape_string($_POST['staff_email']);
        $postTitle  = mysql_real_escape_string($_POST['post_title']);
        $postId     = mysql_real_escape_string($_POST['post_id']);
		$lepId     = mysql_real_escape_string($_POST['lep_id']);
        $postDate   = mysql_real_escape_string($_POST['post_date']);

        $token = bin2hex(random_bytes(16)); 
        $expires = date('Y-m-d H:i:s', strtotime($postDate));
		if (isset($_POST['staff_email'])) {
            $selectSql = "SELECT staff_id FROM attending_staff_attendance WHERE post_id = '$postId' AND supplier_id = '$supplierId' AND staff_id = '$staffId'";
            $insertSql = "INSERT INTO attending_staff_attendance (supplier_id, post_id, lep_id, staff_id, `status`, token, expries) VALUES ('$supplierId', '$postId', '$lepId', '$staffId', 'Invited', '$token', '$expires')";
            echo $insertSql;
        } else {
            $selectSql = "SELECT staff_email, `status` FROM attending_staff_attendance WHERE post_id = '$postId' AND supplier_id = '$supplierId' AND staff_email = '$staffId'";
            $insertSql = "INSERT INTO attending_staff_attendance (supplier_id, post_id, lep_id, staff_email, `status`, token, expries) VALUES ('$supplierId', '$postId', '$lepId', '$staffId', 'Invited', '$token', '$expires')";
            echo $selectSql;
            $staffEmail = $staffId;
            $staffName = '';
        }
        
        $query = mysql_query($selectSql);
        $result = mysql_num_rows($query);
        
        if ($result == 0) {
            // Staff email does not exist in the table, insert new record
            mysql_query($insertSql);
        } else {
            // Staff email exists, check if status is "Not Required - We don&#39;t need this member"
            $row = mysql_fetch_assoc($query);
            if ($row['staff_email'] == 'Not Required - We do not need this member') {
                // Allow saving since the staff has the "Not Required" status
                mysql_query($insertSql);
            } else {
                // Staff email exists and doesn't have the "Not Required" status, do not save
                echo "Staff email already exists with a different status.";
            }
        }
        
        $applicationTokenQuery = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '$postId' AND `type` = 'registration form'");
        $applicationTokenResult = mysql_fetch_assoc($applicationTokenQuery);
        $applicationtoken = $applicationTokenResult['token'];
	    $attendingUrl = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$token;
        // Prepare email data
        $w['eventname']   		= $postTitle;
		$w['staffname']   		= $staffName;
		$w['suppliercompany']	= $supplier;
	    $w['supplier_token']    = $attendingUrl;
		$w['event_date']  		= date("d-M-Y H:i:s", strtotime($postDate));
		
        $emailPrepareOne  = prepareEmail('attending_staff_invitation', $w);

        
        // Send email
        sendEmailTemplate($w['website_email'], $staffEmail, $emailPrepareOne['subject'], $emailPrepareOne['html'], $emailPrepareOne['text'], $emailPrepareOne['priority'], $w);

        echo json_encode(['success' => true, 'message' => 'Invitation sent successfully']);
    } else {
        
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
    }
}

?>
