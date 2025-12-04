<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['selectedEvent']) && !empty($_POST['selectedEvent'])) {
        $selectedEvent = $_POST['selectedEvent'];
        // Sanitize the input to prevent SQL injection
        //$selectedEvent = intval($selectedEvent);

        // Construct the SQL query
        if ($selectedEvent > 0) {
            $sql = "SELECT * FROM `supplier_registration_form` WHERE `event_id` = $selectedEvent ORDER BY `supplier_registration_form`.`id` DESC";
			echo $sql;
        } else {
            echo "";
            exit;
        }

        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            $filter_data = "";
            // Loop through the result set and build the HTML table rows
            $serialNumber = 1;
            while ($row = mysql_fetch_assoc($result)) {
                $filter_data .= "<tr>";
                $filter_data .= "<td>$serialNumber</td>";
                $filter_data .= "<td>" . $row['job_title'] . "</td>";
                $filter_data .= "<td>" . $row['contact_person'] . "</td>";
                $filter_data .= "<td>" . $row['email_address'] . "</td>";
                $filter_data .= "<td>" . $row['phone_number'] . "</td>";
                $filter_data .= '<td><a href="https://ww2.managemydirectory.com/admin/go.php?widget=application_module_more&id=' . $row['id'] . '" class="btn btn-primary btn_more" target="/">View Details</a></td>';
                $filter_data .= "</tr>";

                $serialNumber++;
            }

            // Output the filter_data
            echo $filter_data;
        } else {
            // If no rows are returned, return an empty response
            echo '<tr><td colspan="6">No record Found.....</td></tr>';
        }
    } else {
        // If selectedEvent data is not sent via POST, return an empty response
        echo '<tr><td colspan="6">No record Found.....</td></tr>';
    }
} else {
    // If the request method is not POST, return an empty response
    echo "post not found";
}
