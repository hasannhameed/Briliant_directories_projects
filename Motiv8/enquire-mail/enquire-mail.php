<?php
$user_id = $_POST['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$notify_text = $_POST['notify_text'];


$w['send_full_name'] = $first_name . ' ' . $last_name;
$w['post_text'] = $notify_text;
$emailPrepareone = prepareEmail('Admin-Pre-Register', $w);
sendEmailTemplate(
    $w['website_email'],
    $email, $emailPrepareone['subject'], $emailPrepareone['html'],
    $emailPrepareone['text'], $emailPrepareone['priority'],
    $w
);

?>