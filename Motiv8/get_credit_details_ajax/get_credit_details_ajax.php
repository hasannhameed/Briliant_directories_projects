<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');

// Database credentials
$whmcs_database_host = $w['whmcs_database_host'];
$whmcs_database_name = $w['whmcs_database_name'];
$whmcs_database_password = $w['whmcs_database_password'];
$whmcs_database_user = $w['whmcs_database_user'];

// Create connection for `tblpromotions`
$conn = new mysqli($whmcs_database_host, $whmcs_database_user, $whmcs_database_password, $whmcs_database_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'];

    if (empty($uid)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
        exit;
    }
	// Fetch supplier information
    $supplierQuery = "SELECT first_name, last_name, company, email, user_id FROM users_data WHERE user_id = '" . mysql_real_escape_string($uid) . "'";

    $supplierResult = mysql_query($supplierQuery);

    if (!$supplierResult) {
        echo json_encode(['success' => false, 'message' => 'Error fetching supplier info: ' . mysql_error()]);
        exit;
    }

    $supplierData = mysql_fetch_assoc($supplierResult);

    if (!$supplierData) {
        echo json_encode(['success' => false, 'message' => 'No supplier info found']);
        exit;
    }
	
    $credits = []; // Final data array

    $promoQuery = "SELECT code, maxuses, uses, startdate, expirationdate FROM tblpromotions WHERE notes = '$uid'";
    $promoResult = mysqli_query($conn, $promoQuery);

    if (!$promoResult) {
        echo json_encode(['success' => false, 'message' => 'Error fetching promotions: ' . mysqli_error($conn)]);
        exit;
    }

    while ($promo = mysqli_fetch_assoc($promoResult)) {
        $promoCode = $promo['code'];
        $isUsed = $promo['uses'] >= $promo['maxuses']; 

        $promotionDetails = [
            'code' => $promoCode,
            'is_used' => $isUsed,
            'start_date' => $promo['startdate'],
            'expiration_date' => $promo['expirationdate']
        ];

        if ($isUsed) {
            $postIdQuery = "SELECT event_id AS post_id FROM supplier_registration_form WHERE promo_code_section = '$promoCode'";
            $postIdResult = mysql_query($postIdQuery);

            if ($postIdResult) {
                while ($post = mysql_fetch_assoc($postIdResult)) {
                    $postId = $post['post_id'];

                    $postDetailsQuery = "SELECT post_title, post_filename FROM data_posts WHERE post_id = '$postId'";
                    $postDetailsResult = mysql_query($postDetailsQuery);

                    if ($postDetailsResult) {
                        while ($postDetails = mysql_fetch_assoc($postDetailsResult)) {
                            $credits[] = array_merge($promotionDetails, [
                                'post_id' => $postId,
                                'post_title' => $postDetails['post_title'],
                                'post_link' => $postDetails['post_filename']
                            ]);
                        }
                    }
                }
            }
        } else {
            // If the credit is not used, add only promotion details
            $credits[] = $promotionDetails;
        }
    }
	$response = [
        'success' => true,
        'supplier' => [
            'name' => htmlspecialchars(trim($supplierData['first_name'] . ' ' . $supplierData['last_name'])),
            'company' => htmlspecialchars($supplierData['company']),
            'email' => htmlspecialchars($supplierData['email']),
            'user_id' => $supplierData['user_id']
        ],
        'data' => $credits,
		'promotionDetails' => $promotionDetails
    ];

    // Return the response
    if (!empty($credits) || !empty($supplierData)) {
        //echo json_encode(['success' => true, 'data' => $credits]);
		echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found']);
    }

    // Close connection for `tblpromotions`
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
