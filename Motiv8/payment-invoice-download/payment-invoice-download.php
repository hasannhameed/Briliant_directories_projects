<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM `supplier_registration_form` WHERE `id` = $id";
    $result = mysql_query($sql);

    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_assoc($result);

  
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="payment_summary.csv"');

        $output = fopen('php://output', 'w');

        $headers = array('Application Id', 'Payment Summary', 'Package Amount', 'Coupon Code', 'Discount');
        $data = array($row['id'], $row['payment_summary'], $row['package_amount'], $row['promo_code_section'], $row['discount']);

       
        for ($i = 0; $i < count($headers); $i++) {
            $line = array($headers[$i], mb_convert_encoding($data[$i], 'UTF-8', 'auto'));
            fputcsv($output, $line);
        }

        fclose($output);

        exit;
    } else {
        echo "Registration not found.";
    }
} else {
    echo "Invalid ID parameter.";
}
?>
