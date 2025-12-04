<?php
$full_name = $_POST['full_name'];
$email = $_POST['email_id'];
/*$notify_text = $_POST['notify_text'];*/
$W['main_body'] = $_POST['notify_text'];


$w['send_first_name'] = $full_name;
$w['post_text'] = $notify_text;
$emailPrepareone = prepareEmail('admin-enquire', $w);
sendEmailTemplate(
    $w['website_email'],
    $email, $emailPrepareone['subject'], $_POST['notify_text'],
    $emailPrepareone['text'], $emailPrepareone['priority'],
    $w
);

?>