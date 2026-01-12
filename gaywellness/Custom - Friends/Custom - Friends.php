<?php
// Normalize friends_dates into an array of labels
$_gw_selected_options = array();

if (isset($user['friends_dates'])) {

    if (is_array($user['friends_dates'])) {
        $_gw_selected_options = $user['friends_dates'];
    } else {
        $_gw_raw = trim((string)$user['friends_dates']);
        if ($_gw_raw !== '') {
            $_gw_selected_options = explode(',', $_gw_raw);
        }
    }
}

if (!empty($_gw_selected_options)) {

    $_gw_friends_dates_map = array(
        'Friends' => array(
			'type'  => 'svg-inline',
            'icon'  => '/images/icons/face-sunglasses-sharp-regular-full--white.svg',
            'label' => 'Friends',
            'query' => 'friends'
        ),
        'Dates' => array(
            'type'  => 'fa',
            'icon'  => 'fa-heart',
            'label' => 'Dates',
            'query' => 'dates'
        ),
        'Social' => array(
            'type'  => 'fa',
            'icon'  => 'fa-comments',
            'label' => 'Social',
            'query' => 'network social'
        ),
        'Fun' => array(
            'type'  => 'svg-inline',
            'icon'  => '/images/icons/party-horn-solid-full-white.svg',
            'label' => 'Fun',
            'query' => 'fun'
        ),
    );
    ?>

    <div class="multi-pill-row" role="list" aria-label="Open To">
        <?php foreach ($_gw_selected_options as $_gw_raw_option):

            $_gw_key = trim((string)$_gw_raw_option);
            if (!isset($_gw_friends_dates_map[$_gw_key])) continue;

            $_gw_item = $_gw_friends_dates_map[$_gw_key];
        ?>
            <a class="multi-pill"
               role="listitem"
               href="/gay-connect?q=<?php echo urlencode($_gw_item['query']); ?>">

                <?php if ($_gw_item['type'] === 'fa'): ?>
                    <i class="fa <?php echo $_gw_item['icon']; ?>" aria-hidden="true"></i>
                <?php else:
                    // Inline SVG so global img rules can't blow it up
                    $_gw_svg_path = $_SERVER['DOCUMENT_ROOT'] . $_gw_item['icon'];
                    $_gw_svg_markup = '';
                    if (file_exists($_gw_svg_path)) {
                        $_gw_svg_markup = file_get_contents($_gw_svg_path);
                    }
                ?>
                    <span class="multi-pill-icon multi-pill-icon--svg" aria-hidden="true">
                        <?php echo $_gw_svg_markup; ?>
                    </span>
                <?php endif; ?>

                <span class="multi-pill-text"><?php echo $_gw_item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>

<?php } ?>
