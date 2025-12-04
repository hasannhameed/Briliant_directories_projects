<?php

$staffEmail = isset($_GET['email']) ? trim($_GET['email']) : '';
$newToken   = isset($_GET['newToken']) ? trim($_GET['newToken']) : '';
if ($staffEmail == '') {
    die(json_encode(['status' => 'error', 'message' => 'Missing email']));
}
$res = mysql_query("SELECT token FROM users_data WHERE email = '" . mysql_real_escape_string($staffEmail) . "'");

if (!$res) {
    die(json_encode(['status' => 'error', 'message' => mysql_error()]));
}

if (mysql_num_rows($res) < 1) {
    //$username   = preg_replace('/[^a-z0-9]/i', '', $staffEmail); // simple username
    $memberPlan = 36; // PU Membership Plan

    $insert = mysql_query("
        INSERT INTO users_data (email, subscription_id, token, active) 
        VALUES (
            '" . mysql_real_escape_string($staffEmail) . "',
            '" . (int)$memberPlan . "',
            '" . mysql_real_escape_string($newToken) . "',
            2
        )
    ");

    if ($insert) {
        echo json_encode(['status' => 'ok', 'token' => $newToken]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysql_error()]);
    }
} else {
    // Found existing
    $row = mysql_fetch_assoc($res);
    $token = $row['token'];
    echo json_encode(['status' => 'ok', 'token' => $token]);
}

?>

