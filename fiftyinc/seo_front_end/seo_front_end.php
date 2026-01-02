<?php
// 1. Get the Data ID & context (Ensure $dc, $w, and $pars are available)
$data_id = $dc['data_id'];
//echo $page['nickname'];
// 2. Optimized Database Fetch

$section_name = $page['nickname'] == "search_result_page" ? "search_result_page" : "Details Page - Post Details";

$seo_string = "SELECT * FROM `list_seo` WHERE section = '".$section_name."' AND database_id = '" . mysql_real_escape_string($data_id) . "' LIMIT 1";
//echo $seo_string;
$post_sql_query = mysql_query($seo_string);
$seo_data = ($post_sql_query && mysql_num_rows($post_sql_query) > 0) ? mysql_fetch_assoc($post_sql_query) : false;

// 3. Expanded Replacements (Adding Location and Category)
$replacements = array(
    '%%%website_name%%%' => $w['website_name'],
    '%website_name%'     => $w['website_name'],
    '%%%profession%%%'   => $w['profession'],
    '%profession%'       => $w['profession'],
    '%%%industry%%%'     => $w['industry'],
    '%industry%'         => $w['industry'],
    // New Additions:
    '%%%location%%%'     => $pars[1] ? str_replace('-', ' ', $pars[1]) : 'Worldwide',
    '%%%category%%%'     => $pars[0] ? str_replace('-', ' ', $pars[0]) : $w['profession']
);

function parse_seo_tags($text, $replacements) {
    if (empty($text)) return "";
    return str_replace(array_keys($replacements), array_values($replacements), $text);
}

// 4. Enhanced Data Processing
if ($seo_data) {
    // Basic Meta
    $meta_title    = parse_seo_tags($seo_data['title'], $replacements);
    $meta_desc     = parse_seo_tags($seo_data['meta_desc'], $replacements);
    $meta_keywords = parse_seo_tags($seo_data['meta_keywords'], $replacements);
    $canonical_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Image logic
    $og_image = $seo_data['facebook_image'];
    if (!empty($og_image) && strpos($og_image, 'http') === false) {
        $og_image = 'https://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($og_image, '/');
    }

    // On-page content
    $page_h1      = parse_seo_tags($seo_data['h1'], $replacements);
    $page_h2      = parse_seo_tags($seo_data['h2'], $replacements);
    $seo_content  = parse_seo_tags($seo_data['seo_text'], $replacements);
}
?>

<?php if ($seo_data): ?>
    <title><?php echo $meta_title; ?></title>
    <meta name="description" content="<?php echo $meta_desc; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:title" content="<?php echo !empty($seo_data['facebook_title']) ? parse_seo_tags($seo_data['facebook_title'], $replacements) : $meta_title; ?>">
    <meta property="og:description" content="<?php echo !empty($seo_data['facebook_desc']) ? parse_seo_tags($seo_data['facebook_desc'], $replacements) : $meta_desc; ?>">
    <?php if ($og_image): ?><meta property="og:image" content="<?php echo $og_image; ?>"><?php endif; ?>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $meta_title; ?>">
    <meta name="twitter:description" content="<?php echo $meta_desc; ?>">
    <?php if ($og_image): ?><meta name="twitter:image" content="<?php echo $og_image; ?>"><?php endif; ?>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo $meta_title; ?>",
      "description": "<?php echo $meta_desc; ?>",
      "url": "<?php echo $canonical_url; ?>"
    }
    </script>

    <div class="homepage-hero-text">
        <h1><?php echo $page_h1; ?></h1>
        <?php if ($page_h2): ?><h2><?php echo $page_h2; ?></h2><?php endif; ?>
        
        <div class="seo-content-text">
            <?php echo $seo_content; ?>
        </div>
    </div>
<?php endif; ?>