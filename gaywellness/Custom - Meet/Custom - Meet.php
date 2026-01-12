<?php
// Get raw multi-select value for "meet"
$_gw_meet_raw = isset($user['meet']) ? (string)$user['meet'] : '';

if ($_gw_meet_raw !== '') {

    // Example: "For a walk or hike, For coffee or drinks"
    $_gw_selected_meet_options = explode(',', $_gw_meet_raw);

    // Map each LABEL to its display LABEL
    $_gw_meet_map = array(
        'a walk or hike'      => 'For a walk or hike',
        'coffee or drinks'    => 'For coffee or drinks',
        'exercise/sports'     => 'For exercise/sports',
        'a massage exchange'  => 'For a massage exchange',
        'a hike or bike ride' => 'For a hike or bike ride',
        'a class/event'       => 'For a class/event',
    );

    foreach ($_gw_selected_meet_options as $_gw_raw_option) {

        // Trim spaces around each label
        $_gw_key = trim($_gw_raw_option);
        if ($_gw_key === '') {
            continue;
        }

        // Skip any label we didn't define
        if (!isset($_gw_meet_map[$_gw_key])) {
            continue;
        }

        $_gw_label = $_gw_meet_map[$_gw_key];
        ?>

        <a class="btn bold meet-pill" href="#">
    <?php echo $_gw_label; ?>
</a>

        <?php
    }
}
?>
<div class="clearfix clearfix-lg"></div>
