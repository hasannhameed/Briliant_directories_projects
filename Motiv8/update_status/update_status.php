<?php 
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST" || $method == "GET") {
    $data = ($method == "POST") ? $_POST : $_GET;

    if (isset($data['id']) && isset($data['status_type']) && isset($data['status_value'])) {
        function log_Activity($action, $reference_table, $reference_id, $description = '') {
            global $sess; 
            $user_id = isset($sess['admin_id']) ? $sess['admin_id'] : 0;
            $user_name = isset($sess['admin_name']) ? $sess['admin_name'] : $sess['admin_user'];
            $user_type = 'admin';
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
            $now = date('Y-m-d H:i:s');
            $descriptionEscaped = mysql_real_escape_string($description);
            $sql = "INSERT INTO activity_log (user_id, user_name, user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
                    VALUES ('$user_id', '$user_name' '$user_type', '$action', '$reference_table', '$reference_id', '$descriptionEscaped', '$ip', '$agent', '$now')";

            mysql_query($sql);
        }
        $id = intval($data['id']);
        $status_type = $data['status_type'];

        $valid_columns = [
            'invoice_status' => 'complete_status',
            'paid_status' => 'paid_status',
            'invoice_url' => 'invoice_url'
        ];

        if (!array_key_exists($status_type, $valid_columns)) {
            echo "Invalid status type";
            exit;
        }

        $column_name = $valid_columns[$status_type];

        // Database connection
        $conn = mysql_connect("host", "username", "password");
        mysql_select_db("database_name", $conn);

        // Sanitize value depending on type
        $status_value = ($status_type === 'invoice_url') 
            ? mysql_real_escape_string($data['status_value']) 
            : intval($data['status_value']);

        $value_for_query = ($status_type === 'invoice_url') 
            ? "'$status_value'" 
            : $status_value;

        $update_query = "UPDATE supplier_registration_form SET $column_name = $value_for_query WHERE id = $id";
        log_Activity('update', 'supplier_registration_form', $id, "Updated $status_type to $value_for_query");

        if (mysql_query($update_query)) {
            echo "updated";
        } else {
            echo "Error updating status: " . mysql_error();
        }

        mysql_close($conn);
    } else {
        echo "Bad request: required parameters are missing";
    }
} else {
    echo "Method not allowed. Use POST or GET";
}
?>