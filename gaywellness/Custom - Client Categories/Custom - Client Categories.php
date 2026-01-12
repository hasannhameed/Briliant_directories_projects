<?php
if (!defined('GW_SUBICONS_DONE')) {
    define('GW_SUBICONS_DONE', true);
	
// Toggle: white background behind icons
$useIconBackground = false; // set to false to turn OFF white bg + border

// Always-on styles (size, alignment, spacing)
$iconBaseStyle = '
    height:18px !important;
    width:18px !important;
    vertical-align:middle;
    margin-right:6px;
    display:inline-block !important;
    border-radius:6px;
    padding:1px;
    box-sizing:content-box;
';

// Optional white background + border (only this is toggled)
if ($useIconBackground) {
    $iconBaseStyle .= '
        background:#ffffff;
        border:2px solid #ffffff;
    ';
}


    // Get member subs as array
    $subs = getMemberSubCategory($user['user_id'], 'sub-sub', 'first', 'all', 'array');
    if (empty($subs)) {
        $subs = getMemberSubCategory($user['user_id'], 'all', 'first', 'all', 'array');
    }

    // Collect all possible names/values
    $candidates = [];
    foreach ((array)$subs as $k => $v) {
        if (is_string($k) && $k !== '') $candidates[] = $k;
        if (is_string($v) && $v !== '') $candidates[] = $v;
        if (is_array($v)) {
            if (!empty($v['name']))     $candidates[] = $v['name'];
            if (!empty($v['filename'])) $candidates[] = $v['filename'];
            if (!empty($v[0]) && is_string($v[0])) $candidates[] = $v[0];
        }
    }

    // Canonical versions
    $canon = array_map(function ($s) {
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return strtolower(trim($s));
    }, $candidates);

    // FULL CATEGORY LIST — EACH WITH A BLANK "icon" FIELD
    $subSubIcons = [

        // Arts & Culture
        ['label' => 'Dance', 'href' => '/dance-1', 'icon' => 'sneaker-running-duotone-solid-full.svg'],
        ['label' => 'Fashion', 'href' => '/fashion', 'icon' => 'shirt-sharp-solid-full-1.svg'],
        ['label' => 'Museums', 'href' => '/museums', 'icon' => 'building-columns-solid-full.svg'],
        ['label' => 'Music', 'href' => '/music', 'icon' => 'music-solid-full_1.svg'],
        ['label' => 'Painting', 'href' => '/painting', 'icon' => 'paintbrush-solid-full.svg'],
        ['label' => 'Photography', 'href' => '/photography', 'icon' => 'camera-polaroid-solid-full.svg'],
        ['label' => 'Theatre', 'href' => '/theatre', 'icon' => 'masks-theater-sharp-duotone-solid-full.svg'],

        // Career & Education
        ['label' => 'Education', 'href' => '/education', 'icon' => 'diploma-duotone-light-full-1.svg'],
        ['label' => 'Entrepreneurship', 'href' => '/entrepreneurship', 'icon' => 'briefcase-solid-full.svg'],
        ['label' => 'Finance', 'href' => '/finance', 'icon' => 'money-bill-light-full.svg'],
        ['label' => 'Language Learning', 'href' => '/language-learning', 'icon' => 'language-solid-full-1.svg'],
        ['label' => 'Marketing', 'href' => '/marketing', 'icon' => 'globe-sharp-regular-full.svg'],
        ['label' => 'Networking', 'href' => '/networking', 'icon' => 'handshake-solid-full.svg'],
        ['label' => 'Public Speaking', 'href' => '/public-speaking', 'icon' => 'comment-regular-full.svg'],
        ['label' => 'Tech', 'href' => '/tech', 'icon' => 'laptop-mobile-solid-full_1.svg'],
        ['label' => 'Volunteering', 'href' => '/volunteering', 'icon' => 'handshake-angle-regular-full.svg'],
        ['label' => 'Writing', 'href' => '/writing', 'icon' => 'calendar-lines-pen-regular-full.svg'],

        // Entertainment & Media
        ['label' => 'Comics', 'href' => '/comics', 'icon' => 'book-open-lines-regular-full.svg'],
        ['label' => 'Movies', 'href' => '/movies', 'icon' => 'camera-movie-solid-full.svg'],
        ['label' => 'Music Festivals', 'href' => '/music-festivals', 'icon' => 'party-bell-solid-full_1.svg'],
        ['label' => 'Podcasts', 'href' => '/podcasts', 'icon' => 'microphone-sharp-regular-full.svg'],
        ['label' => 'Pop Culture', 'href' => '/pop-culture', 'icon' => 'popcorn-light-full.svg'],
        ['label' => 'Social Media', 'href' => '/social-media', 'icon' => 'instagram-brands-solid-full.svg'],
        ['label' => 'TV & Streaming', 'href' => '/tv-streaming', 'icon' => 'tv-solid-full.svg'],
        ['label' => 'Video Games', 'href' => '/video-games', 'icon' => 'game-console-handheld-regular-full.svg'],

        // Health & Wellness
        ['label' => 'Biking', 'href' => '/biking', 'icon' => 'person-biking-solid-full.svg'],
	    ['label' => 'Diet', 'href' => '/diet', 'icon' => 'pot-food-solid-full-1.svg'],
        ['label' => 'Fitness', 'href' => '/fitness', 'icon' => 'watch-fitness-solid-full_1.svg'],
        ['label' => 'Holistic Health', 'href' => '/holistic-health', 'icon' => 'book-medical-light-full.svg'],
        ['label' => 'Meditation', 'href' => '/meditation', 'icon' => 'person-meditating-solid-full_1.svg'],
        ['label' => 'Mental Health', 'href' => '/mental-health', 'icon' => 'brain-solid-full.svg'],
        ['label' => 'Nutrition', 'href' => '/nutrition-2', 'icon' => 'nutritionix-brands-solid-full_1.svg'],
        ['label' => 'Running', 'href' => '/running', 'icon' => 'person-running-solid-full_1.svg'],
        ['label' => 'Swimming', 'href' => '/swimming', 'icon' => 'person-swimming-solid-full_1.svg'],
        ['label' => 'Wellness Retreats', 'href' => '/wellness-retreats', 'icon' => 'umbrella-beach-duotone-solid-full.svg'],
        ['label' => 'Yoga', 'href' => '/yoga', 'icon' => 'person-meditating-regular-full.svg'],

        // Lifestyle & Hobbies
        ['label' => 'Beauty', 'href' => '/beauty', 'icon' => 'user-hair-sharp-light-full.svg'],
        ['label' => 'Cooking', 'href' => '/cooking', 'icon' => 'bowl-rice-solid-full.svg'],
        ['label' => 'Fashion', 'href' => '/fashion-1', 'icon' => 'shirt-sharp-duotone-solid-full.svg'],
        ['label' => 'Gardening', 'href' => '/gardening', 'icon' => 'bag-seedling-solid-full_1.svg'],
        ['label' => 'Interior Design', 'href' => '/interior-design', 'icon' => 'couch-solid-full_1.svg'],
        ['label' => 'Pets', 'href' => '/pets', 'icon' => 'dog-solid-full_1.svg'],
        ['label' => 'Photography', 'href' => '/photography-1', 'icon' => 'photo-film-regular-full.svg'],
        ['label' => 'Tech Gadgets', 'href' => '/tech-gadgets', 'icon' => 'laptop-mobile-solid-full.svg'],

        // Mind & Growth
        ['label' => 'Astrology', 'href' => '/astrology', 'icon' => 'alien-light-full.svg'],
        ['label' => 'Coaching', 'href' => '/coaching', 'icon' => 'hand-heart-solid-full.svg'],
        ['label' => 'Reading', 'href' => '/reading', 'icon' => 'book-open-solid-full.svg'],
        ['label' => 'Spirituality', 'href' => '/spirituality', 'icon' => 'cloud-regular-full-1.svg'],

        // Outdoors & Adventure
        ['label' => 'Camping', 'href' => '/camping', 'icon' => 'campground-solid-full_1.svg'],
        ['label' => 'Climbing', 'href' => '/climbing', 'icon' => 'person-hiking-solid-full_2.svg'],
        ['label' => 'Hiking', 'href' => '/hiking', 'icon' => 'person-hiking-solid-full_1.svg'],
        ['label' => 'National Parks', 'href' => '/national-parks', 'icon' => 'house-sharp-solid-full.svg'],
        ['label' => 'Skiing & Snowboarding', 'href' => '/skiing-snowboarding', 'icon' => 'person-skiing-regular-full.svg'],
        ['label' => 'Surfing', 'href' => '/surfing', 'icon' => 'hand-shaka-solid-full_1.svg'],

        // Relationships & Community
        ['label' => 'Allyship', 'href' => '/allyship', 'icon' => 'user-group-regular-full.svg'],
		['label' => 'Casual', 'href' => '/casual', 'icon' => 'bed-empty-sharp-duotone-solid-full.svg'],
        ['label' => 'Dating', 'href' => '/dating', 'icon' => 'heart-solid-full.svg'],
        ['label' => 'Family', 'href' => '/family', 'icon' => 'family-solid-full_1.svg'],
        ['label' => 'Friendship', 'href' => '/friendship', 'icon' => 'user-group-sharp-solid-full.svg'],
        ['label' => 'Households', 'href' => '/households', 'icon' => 'house-solid-full.svg'],
        ['label' => 'LGBTQ+ Rights', 'href' => '/lgbtq-rights', 'icon' => 'hand-fist-light-full.svg'],
        ['label' => 'Local Meetups', 'href' => '/local-meetups', 'icon' => 'calendar-regular-full.svg'],
        ['label' => 'Mentorship', 'href' => '/mentorship', 'icon' => 'kakao-talk-brands-solid-full.svg'],
        ['label' => 'Travel', 'href' => '/travel', 'icon' => 'backpack-solid-full_2.svg'],
        ['label' => 'Support Groups', 'href' => '/support-groups', 'icon' => 'group-arrows-rotate-solid-full_1.svg'],

        // Social & Nightlife
        ['label' => 'Bars', 'href' => '/bars', 'icon' => 'wine-bottle-solid-full.svg'],
        ['label' => 'Clubs', 'href' => '/clubs', 'icon' => 'compact-disc-regular-full.svg'],
        ['label' => 'Dinner Parties', 'href' => '/dinner-parties', 'icon' => 'plate-utensils-sharp-solid-full.svg'],
        ['label' => 'Drag Shows', 'href' => '/drag-shows', 'icon' => 'lips-solid-full_1.svg'],
        ['label' => 'Festivals', 'href' => '/festivals', 'icon' => 'tent-circus-duotone-regular-full.svg'],
        ['label' => 'Game Nights', 'href' => '/game-nights', 'icon' => 'game-board-solid-full_1.svg'],
        ['label' => 'Karaoke', 'href' => '/karaoke', 'icon' => 'head-side-speak-sharp-solid-full.svg'],
        ['label' => 'Live Music', 'href' => '/live-music', 'icon' => 'music-solid-full.svg'],
        ['label' => 'Pride Events', 'href' => '/pride-events', 'icon' => 'rainbow-solid-full_1.svg'],
        ['label' => 'Speed Dating', 'href' => '/speed-dating', 'icon' => 'handshake-angle-light-full.svg'],

        // Travel & Exploration
        ['label' => 'Adventure Travel', 'href' => '/adventure-travel', 'icon' => 'backpack-solid-full_1.svg'],
        ['label' => 'City Exploration', 'href' => '/city-exploration', 'icon' => 'city-solid-full_1.svg'],
        ['label' => 'Cruise Travel', 'href' => '/cruise-travel', 'icon' => 'ship-large-solid-full.svg'],
        ['label' => 'Food Tours', 'href' => '/food-tours', 'icon' => 'pot-food-sharp-solid-full.svg'],
        ['label' => 'International Travel', 'href' => '/international-travel', 'icon' => 'ticket-airline-regular-full.svg'],
        ['label' => 'LGBTQ+ Destinations', 'href' => '/lgbtq-destinations', 'icon' => 'rainbow-half-light-full.svg'],
        ['label' => 'Road Trips', 'href' => '/road-trips', 'icon' => 'road-solid-full_1.svg'],
    ];

    echo '<div class="list-subs-profile">';

    $rendered = [];

    foreach ($subSubIcons as $cfg) {

        $labelLower = strtolower($cfg['label']);
        if (isset($rendered[$labelLower])) continue;

        // IF A CUSTOM ICON IS PROVIDED, USE IT
        if (!empty($cfg['icon'])) {
            $iconFile = $cfg['icon'];
        } else {
            // OTHERWISE AUTO-GENERATE THE FILENAME
            $imgSlug = html_entity_decode($cfg['label'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $imgSlug = str_replace(['&', '+'], ' ', $imgSlug);
            $imgSlug = preg_replace('~[^a-z0-9]+~', '-', strtolower($imgSlug));
            $imgSlug = trim($imgSlug, '-');
            $iconFile = $imgSlug . '.png';
        }

        // Match against known subs
        $path = parse_url($cfg['href'], PHP_URL_PATH);
        $slug = strtolower(ltrim($path, '/'));
        $slugNoNum = preg_replace('~-\d+$~', '', $slug);

        $matchKeys = [$labelLower, $slug, $slugNoNum];

        $matched = false;
        foreach ($matchKeys as $k) {
            if (in_array($k, $canon, true)) {
                $matched = true;
                break;
            }
        }

        if (!$matched) continue;

        $href = htmlspecialchars($cfg['href'], ENT_QUOTES);
        $title = htmlspecialchars($cfg['label'], ENT_QUOTES);

       echo '<a class="subcat-icon" href="' . $href . '" title="' . $title . '">
        <img src="/images/icons/' . $iconFile . '"
             style="' . $iconBaseStyle . '"
             width="20" alt="' . $title . ' icon">
        ' . $title . '
      </a> ';



        $rendered[$labelLower] = true;
    }

    echo '</div>';
}
?>

