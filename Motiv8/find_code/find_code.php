<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);


        $code = $data['code']?$data['code']: 0;
        $id = isset($data['id']) ? intval($data['id']) : 0;

        $whmcs_database_host = $w['whmcs_database_host'];
        $whmcs_database_name = $w['whmcs_database_name'];
        $whmcs_database_password = $w['whmcs_database_password'];
        $whmcs_database_user = $w['whmcs_database_user'];

        $conn = new mysqli($whmcs_database_host, $whmcs_database_user, $whmcs_database_password, $whmcs_database_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($data['uses']) && isset($data['code'])) {
            $code = mysqli_real_escape_string($conn, $data['code']);
            $count = intval($data['uses']) + 1;
            $query = "UPDATE tblpromotions SET uses = $count WHERE code = '$code'";
            $usesResult = mysqli_query($conn, $query);
            return;
        }

        
        $str = "SELECT code,value, maxuses, uses, startdate, expirationdate, type FROM tblpromotions WHERE code = '$code'";
        $promoResult = mysqli_query($conn, $str);

        if ($promoResult) {

            $row = mysqli_fetch_assoc($promoResult);

            echo json_encode([
                "code" => $row['code'],
                "maxuses" => $row['maxuses'],
                "uses" => $row['uses'],
                "startdate" => $row['startdate'],
                "expirationdate" => $row['expirationdate'],
                "type" => $row['type'],
                "value" => $row['value'],
                "input" => ["id" => $id, "code" => $code]
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Promotion code not found or invalid",
                "input" => ["id" => $id, "code" => $code],
                "query" => $str,
                "mysql_error" => mysqli_error($conn)
            ]);

        }

    } else {
        echo json_encode(["error" => "Invalid request method"]);
    }


?>
