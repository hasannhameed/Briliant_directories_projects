<?php
/**
 * Created by Jonny 03/10/2022
 * This widget will identify whenever a <a> with href of scheme sms: is clicked and send a SMS to the practicioner
 * The widget is added to the footer of all pages so that it will automatically hook in to all SMS links
 */
?>

<?php
if($_SERVER['REQUEST_METHOD'] == "GET") {
    /************ Add javascript to hook onto any <a href> click wihere schema is sms:, tel: or whatsapp *****************/
    ?>
    <script>
        function triggerSmsNotify(element) {
            console.log(element);
            var originalHref = $(element).attr('href');
            let linkHref = $(element).attr('href');
            if(linkHref.indexOf('&') > 0) {
                linkHref = linkHref.substr(0, linkHref.indexOf('&')); //get only the number, remove rest of the link query string
            }
            
            //remove unwanted characters
            linkHref = linkHref.replace('(','');
            linkHref = linkHref.replace(')','');
            linkHref = linkHref.replace('-','');
            linkHref = linkHref.replace('-','');
            linkHref = linkHref.replace(' ','');
            linkHref = linkHref.replace('sms://','');
            linkHref = linkHref.replace('tel://','');
            linkHref = linkHref.replace('tel:','');
            linkHref = linkHref.replace('https://wa.me/','');
            linkHref = linkHref.replace('/?text=(from%20Gay%20Wellness)','');
            linkHref = linkHref.replace('/?text=from%20Gay%20Wellness','');

            console.log(linkHref);

            var params = new URLSearchParams({
                'widget_name': 'custom - SMS notify',
                'smsnumber': linkHref, 
                'request_type': 'POST',
                'header_type': 'json',
                'ajax': true
            });
            navigator.sendBeacon('/wapi/widget', params);
        }

        $(document).ready(function() {
            $(document).on('click','a[href^="sms"]', function(e) {
                triggerSmsNotify($(this));
            });
            $(document).on('click','a[href^="tel"]', function(e) {
                triggerSmsNotify($(this));
            });
            $(document).on('click','a[href^="https://wa.me"]', function(e) {
                triggerSmsNotify($(this));
            });
        })
    </script>
    <?php
} elseif($_SERVER['REQUEST_METHOD'] == "POST") {
    /************ Handle callback when SMS link is clicked ******************/

    global $w;

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    

    $recipient = $_POST['smsnumber'];
    $result = 'Ok';
    $accountSid = 'ACf9a1c77c1d2ec40130e0c3feeebe8b56';
    $authToken = 'a372c10d9db7ea1d8cc1771a7b9b1bc9';

    if(substr($recipient, 0, 3) == '+44') { //is a UK recipient
        $fromNumber = '+447700152318';
    } else {
        $fromNumber = '+12057725957';
    }

    $urlToCall = 'https://api.twilio.com/2010-04-01/Accounts/ACf9a1c77c1d2ec40130e0c3feeebe8b56/Messages.json';
    $headerArray = array();

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $urlToCall);
    curl_setopt($c, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($c, CURLOPT_USERPWD, $accountSid . ":" . $authToken);

    $payload = (array(
        "From" => $fromNumber,
        "To" => $recipient,
        "Body" => 'Your phone number was clicked on in gaywellness.com, you should receive a booking request soon'
    ));

    curl_setopt($c, CURLOPT_POSTFIELDS, $payload);
    
    curl_setopt($c, CURLOPT_HTTPHEADER, $headerArray);
    $curlResponse = curl_exec($c);

	if( curl_error($c) || ((curl_getinfo($c, CURLINFO_HTTP_CODE) < 200) || (curl_getinfo($c, CURLINFO_HTTP_CODE) > 299) ))
    {
        $errorMessage = 'Curl Error. Response Code: ' . curl_getinfo($c, CURLINFO_HTTP_CODE) . ' Error: ' . curl_error($c) . ' Response: ' . $curlResponse;
        $result = 'Error';
    }

    $originalHref = $_POST['originalHref'];

    /* Create audit log entry */
    $audit = array(
        'result' => $result,
        'error' => $errorMessage,
        'number' => $recipient,
        'curlResponse' => $curlResponse,
        'payload' => $payload
    );

    $servername = brilliantDirectories::getDatabaseConfiguration('database_host');
    $username = brilliantDirectories::getDatabaseConfiguration('database_user');
    $password = brilliantDirectories::getDatabaseConfiguration('database_pass');
    $dbname = brilliantDirectories::getDatabaseConfiguration('database');

    
    // Create connection
    $link = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $link->set_charset('utf8');


    $auditText = $link->real_escape_string(json_encode($audit));
    $sql = "
        INSERT INTO gaywellness_auditLog 
            (category,event,auditData) 
        VALUES 
            ('SMS','SMS_Sent','".$auditText."')
        ";
    $tresults = $link->query($sql);

       

    /* return */

    header('Content-Type: application/json');
    echo(json_encode(array(
        'result' => $result,
        'error' => $errorMessage,
        'number' => $recipient
    )));
    
}
?>