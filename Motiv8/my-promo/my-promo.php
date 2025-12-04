<?php
// Set the token from cookies
$token = $_COOKIE['userid'];

// Database credentials
$whmcs_database_host = $w['whmcs_database_host'];
$whmcs_database_name = $w['whmcs_database_name'];
$whmcs_database_password = $w['whmcs_database_password'];
$whmcs_database_user = $w['whmcs_database_user'];

// Create connection for `tblpromotions`
$conn = new mysqli($whmcs_database_host, $whmcs_database_user, $whmcs_database_password, $whmcs_database_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Initialize the credits array
$credits = [];

// Check if user token is available
if (!empty($token)) {
    $promoQuery = "SELECT code, maxuses, uses, startdate, expirationdate FROM tblpromotions WHERE notes = '$token'";
    $promoResult = mysqli_query($conn, $promoQuery);

    if ($promoResult) {
        while ($promo = mysqli_fetch_assoc($promoResult)) {
            $promoCode = $promo['code'];
            $isUsed = $promo['uses'] == $promo['maxuses']; 
    
            $promotionDetails = [
                'code' => $promoCode,
                'is_used' => $isUsed,
                'start_date' => $promo['startdate'],
                'expiration_date' => $promo['expirationdate']
            ];
    
            if ($isUsed) {
                $postIdQuery = "SELECT event_id AS post_id FROM supplier_registration_form WHERE promo_code_section = '$promoCode'";
                $postIdResult = mysql_query($postIdQuery);
    
                if ($postIdResult) {
                    while ($post = mysql_fetch_assoc($postIdResult)) {
                        $postId = $post['post_id'];
    
                        $postDetailsQuery = "SELECT lep.id AS live_event_post_id, dp.post_title, dp.post_filename FROM data_posts dp LEFT JOIN live_events_posts lep ON dp.post_id = lep.post_id WHERE dp.post_id = '$postId' AND lep.user_id = '$token';";
						//echo $postDetailsQuery;
                        $postDetailsResult = mysql_query($postDetailsQuery);
    
                        if ($postDetailsResult) {
                            while ($postDetails = mysql_fetch_assoc($postDetailsResult)) {
                                $credits[] = array_merge($promotionDetails, [
                                    'live_event_post_id' => $postDetails['live_event_post_id'],
                                    'post_id' => $postId,
                                    'post_title' => $postDetails['post_title'],
                                    'post_link' => $postDetails['post_filename']
                                ]);
                            }
                        }
                    }
                }
            } else {
                // If the credit is not used, add only promotion details
                $credits[] = $promotionDetails;
            }
        }
    }
    // print_r($credits);
}


?>
<div class="col-md-9">
    <h1 class="main_title bold">Event Credits</h1>
    <h3>Use these to 0 the price when registering for events.</h3>
    <br>
    <table id="irow" class="active-coupons table" role="grid" style="width: 90%;">
        <thead>
            <tr role="row">
                <th class="sorting_disabled center" rowspan="1" colspan="1" style="width: 200px;">Event Credits</th>
                <th class="sorting_disabled center" rowspan="1" colspan="1">Event</th>
            </tr>
        </thead>
        <tbody id="credit_details">
            <?php if (!empty($credits)): ?>
                <?php foreach ($credits as $credit): ?>
                    <tr>
                        <td class="<?= $credit['is_used'] ? 'usedcu' : '' ?>">
                            <?= htmlspecialchars($credit['code']) ?>
                            <a href="javascript:void(0);" title="Copy Coupon Code" class="copy_code <?= $credit['is_used'] ? ' disabled' : '' ?>" data-clipboard-text="<?= htmlspecialchars($credit['code']) ?>">
                                <i class="fa fa-clipboard fa-fw"></i>
                            </a>
                        </td>
                        <td>
                            <?php if ($credit['is_used']): ?>
                                <?= htmlspecialchars($credit['post_title']) ?>
                                <a href="https://www.motiv8search.com/account/edit-supplier-card/edit?id=<?= $credit['live_event_post_id'] ?>" target="_blank" rel="noopener noreferrer">
                                    &nbsp;<i class="fa fa-external-link fa-fw"></i> View Event
                                </a>
                            <?php else: ?>
                                Not Used
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="center">No credits found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
// Close the database connection
$conn->close();
?>
