<?php
/**
 * Widget: Click to Call Handler (separate from SMS widget)
 * Created: 2025-08-14
 * Description: Sends SMS + initiates voice call via Twilio when a tel: link is clicked
 */

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
?>
<script>
    function triggerCallNotify(element) {
        const rawHref = $(element).attr('href');
        let phone = rawHref.replace('tel:', '').replace(/[\s()-]/g, '');

        const params = new URLSearchParams({
            widget_name: 'Click to Call Widget',
            callnumber: phone,
            calltype: 'initiate',
            ajax: true
        });

        // Use Beacon to avoid blocking
        navigator.sendBeacon('/wapi/click-to-call', params);
    }

    $(document).ready(function () {
        $(document).on('click', 'a[href^="tel"]', function (e) {
            triggerCallNotify(this);
        });
    });
</script>
<?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    global $w;

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Content-Type: application/json");

    $recipient = $_POST['callnumber'] ?? null;
    $result = 'Not processed';
    $errorMessage = '';
    $curlResponse = '';

    if (!$recipient) {
        echo json_encode(['result' => 'Error', 'error' => 'No number provided']);
        exit;
    }

    // TWILIO AUTH
    $accountSid = 'ACf9a1c77c1d2ec40130e0c3feeebe8b56';
    $authToken = 'a372c10d9db7ea1d8cc1771a7b9b1bc9';

    $fromNumber = (substr($recipient, 0, 3) === '+44') ? '+447700152318' : '+12057725957';

    // Step 1: SMS Notification
    $smsPayload = [
        "From" => $fromNumber,
        "To" => $recipient,
        "Body" => "Your phone number was clicked on for a call via gaywellness.com. Expect a call shortly."
    ];

    $smsUrl = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";

    $smsCurl = curl_init();
    curl_setopt($smsCurl, CURLOPT_URL, $smsUrl);
    curl_setopt($smsCurl, CURLOPT_POST, true);
    curl_setopt($smsCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($smsCurl, CURLOPT_USERPWD, "{$accountSid}:{$authToken}");
    curl_setopt($smsCurl, CURLOPT_POSTFIELDS, http_build_query($smsPayload));

    $smsResponse = curl_exec($smsCurl);
    $smsError = curl_error($smsCurl);

    // Step 2: Initiate Call
    $callPayload = [
        "From" => $fromNumber,
        "To" => $recipient,
        "Url" => "https://gaywellness.com/wapi/call-bridge.xml"
    ];

    $callUrl = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Calls.json";

    $callCurl = curl_init();
    curl_setopt($callCurl, CURLOPT_URL, $callUrl);
    curl_setopt($callCurl, CURLOPT_POST, true);
    curl_setopt($callCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($callCurl, CURLOPT_USERPWD, "{$accountSid}:{$authToken}");
    curl_setopt($callCurl, CURLOPT_POSTFIELDS, http_build_query($callPayload));

    $callResponse = curl_exec($callCurl);
    $callError = curl_error($callCurl);
    $httpCode = curl_getinfo($callCurl, CURLINFO_HTTP_CODE);

    if ($callError || $httpCode < 200 || $httpCode > 299) {
        $result = 'Error';
        $errorMessage = "Twilio Call Error: HTTP $httpCode - $callError - $callResponse";
    } else {
        $result = 'Call Initiated';
    }

    // Log to MySQL
    $audit = [
        'result' => $result,
        'smsResponse' => $smsResponse,
        'callResponse' => $callResponse,
        'number' => $recipient,
        'payload' => ['sms' => $smsPayload, 'call' => $callPayload]
    ];

    $servername = brilliantDirectories::getDatabaseConfiguration('database_host');
    $username = brilliantDirectories::getDatabaseConfiguration('database_user');
    $password = brilliantDirectories::getDatabaseConfiguration('database_pass');
    $dbname = brilliantDirectories::getDatabaseConfiguration('database');

    $link = new mysqli($servername, $username, $password, $dbname);
    if (!$link->connect_error) {
        $link->set_charset('utf8');
        $auditText = $link->real_escape_string(json_encode($audit));
        $sql = "
            INSERT INTO gaywellness_auditLog 
                (category, event, auditData) 
            VALUES 
                ('Call', 'Click_To_Call', '{$auditText}')
        ";
        $link->query($sql);
    }

    echo json_encode([
        'result' => $result,
        'error' => $errorMessage,
        'number' => $recipient
    ]);
    exit;
}
?>
