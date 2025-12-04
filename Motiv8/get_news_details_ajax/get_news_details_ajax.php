<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = isset($_POST['uid']) ? intval($_POST['uid']) : null;

    if (empty($uid)) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
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

    // Query to fetch news posts for the given user ID
    $query = "SELECT dp.post_id, dp.data_id, dp.user_id, dp.post_status, dp.post_filename, dp.post_title, dp.post_live_date, dp.revision_timestamp, dp.post_start_date, dp.post_expire_date FROM `data_posts` AS dp WHERE dp.user_id = " . $uid . " AND dp.data_id = 14 ORDER BY dp.post_live_date DESC";

    $result = mysql_query($query);

    if ($result && mysql_num_rows($result) > 0) {
		
        $news = [];

        while ($row = mysql_fetch_assoc($result)) {
			$post_startDate = DateTime::createFromFormat('YmdHis', $row['post_live_date'])->format('d/m/Y');
			$post_editDate  = date('d/m/Y', strtotime($row['revision_timestamp']));
			
            $news[] = [
                'post_title' => $row['post_title'],
                'post_status' => $row['post_status'],
                'start_date' => $post_startDate,
                'last_edit' => $post_editDate,
                'link' => 'https://www.motiv8search.com/' . $row['post_filename']
            ];
        }
		
		$response = [
        'success' => true,
        'supplier' => [
            'name' => htmlspecialchars(trim($supplierData['first_name'] . ' ' . $supplierData['last_name'])),
            'company' => htmlspecialchars($supplierData['company']),
            'email' => htmlspecialchars($supplierData['email']),
            'user_id' => $supplierData['user_id']
        ],
        'data' => $news
    ];

		echo json_encode($response);

        //echo json_encode(['success' => true, 'data' => $news]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No news found.'.mysql_error()]);
    }
}
?>
