<div class="clearfix"></div>

<?php
// Map each choice key to its icon + sentence-case label
$_gw_fav_config = array(
    'my_favorite_gym_or_yoga_studio' => array(
        'icon'  => 'https://gaywellness.com/images/icons/dumbbell-solid-full.svg',
        'label' => 'My favorite gym or yoga studio',
    ),
    'my_favorite_park_or_hiking_trail' => array(
        'icon'  => 'https://gaywellness.com/images/icons/bench-tree-solid-full.svg',
        'label' => 'My favorite park or hiking trail',
    ),
    'my_favorite_spa_or_wellness_spot' => array(
        'icon'  => 'https://gaywellness.com/images/icons/spa-solid-full.svg',
        'label' => 'My favorite spa or wellness spot',
    ),
    'my_favorite_restaurant_or_caf' => array(
        'icon'  => 'https://gaywellness.com/images/icons/pot-food-solid-full.svg',
        'label' => 'My favorite restaurant or café',
    ),
    'my_favorite_bar' => array(
        'icon'  => 'https://gaywellness.com/images/icons/martini-glass-solid-full.svg',
        'label' => 'My favorite bar',
    ),
    'my_favorite_weekend_getaway' => array(
        'icon'  => 'https://gaywellness.com/images/icons/car-building-solid-full.svg',
        'label' => 'My favorite weekend getaway',
    ),
);

// Loop through fav_choice_1, fav_choice_2, fav_choice_3
for ($_gw_i = 1; $_gw_i <= 3; $_gw_i++) {

    $_gw_choice_key = 'fav_choice_' . $_gw_i;
    $_gw_item_key   = 'fav_item_'   . $_gw_i;

    // Skip if no choice selected
    if (empty($user[$_gw_choice_key])) {
        continue;
    }

    $_gw_choice = $user[$_gw_choice_key];

    // Skip if choice not in our config map
    if (!isset($_gw_fav_config[$_gw_choice])) {
        continue;
    }

    $_gw_cfg   = $_gw_fav_config[$_gw_choice];
    $_gw_icon  = $_gw_cfg['icon'];
    $_gw_label = $$_gw_label = $$_gw_label = htmlspecialchars($_gw_cfg['label'], ENT_QUOTES, 'UTF-8');
    ?>

    <div class="module spacing gw-fav-block">
        <div class="col-md-12">
            <img src="<?php echo $_gw_icon; ?>"
                 style="width:25px; display:inline !important;"
                 width="25"
                 alt="">
            <strong class="fav-heading"><?php echo $_gw_label; ?></strong>
        </div>

        <div class="col-md-12 fav-item">
            <?php echo $user[$_gw_item_key]; ?>
        </div>
    </div>

    <?php
}
?>
