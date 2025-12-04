<?php


// mysql_query('UPDATE users_data SET email = "hasan.businesslabs@gmail.com" WHERE user_id = 4865');

//$query = mysql_query("SELECT user_id, email FROM users_data WHERE user_id = 4865");

$str = "SELECT
            es.*,
            ud.email,
            dp.data_id,
            dp.post_start_date
        FROM
            event_schedule AS es
        LEFT JOIN users_data AS ud
            ON es.action_by = ud.user_id
        INNER JOIN data_posts AS dp
            ON dp.post_id = es.event_id

        WHERE
            es.due_date != '0000-00-00'
            AND es.due_date < NOW()
            AND dp.data_id = 73
            AND dp.post_start_date >= CURDATE();
    ";

$query = mysql_query($str);

if (!$query) {
    die("MySQL Query Failed: " . mysql_error());
}

if (mysql_num_rows($query) == 0) {
    die("No user found with this email.");
}

while ($user = mysql_fetch_assoc($query)) {

    if (empty($user['email'])) {
        echo "New user detected (No email sent before) - user_id {$user['user_id']}<br><br>";
    }

    $user_email       = $user['email'];
    $user_id          = $user['user_id'];
    $event_id          = $user['event_id'];
    $actionId          = $user['id'];

        $w['id'] = $event_id;
        $w['ok'] = $actionId;
    

        $email_type     = 'event_action_overdue';
        $emailPrepareone = prepareEmail($email_type, $w);

        $sendemail = sendEmailTemplate(
            $w['website_email'],
            $user_email,
            $emailPrepareone['subject'],
            $emailPrepareone['html'],
            $emailPrepareone['text'],
            $emailPrepareone['priority'],
            $w
        );

        mysql_query("UPDATE event_schedule SET status = 'Overdue' WHERE id =".$actionId);

    echo "Email sent to $user_email <br> EventIs $actionId ";
}

echo 'working';
echo mysql_error();

?>
