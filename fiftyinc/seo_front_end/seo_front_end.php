<?php
// -------------------------------------------------------------------------
// 1. CONFIGURATION & DATA FETCH
// -------------------------------------------------------------------------

// Ensure we have the ID. If $dc isn't set, default to 0 to prevent errors.
$data_id = isset($dc['data_id']) ? $dc['data_id'] : 0;

// Determine which section name to look for in the DB
$nickname = isset($page['nickname']) ? $page['nickname'] : '';
$section_name = ($nickname == "Search Results - Features - Post & Group") ? "search_result_page" : "Details Page - Post Details";

// QUERY: Fetch the specific row from 'list_seo' matching this page
$seo_string = "SELECT * FROM `list_seo` WHERE section = '".mysql_real_escape_string($section_name)."' AND database_id = '" . mysql_real_escape_string($data_id) . "' LIMIT 1";
$post_sql_query = mysql_query($seo_string);
$seo_data = ($post_sql_query && mysql_num_rows($post_sql_query) > 0) ? mysql_fetch_assoc($post_sql_query) : false;

// -------------------------------------------------------------------------
// 2. VARIABLE REPLACEMENTS (Swapping %Tags% for Real Data)
// -------------------------------------------------------------------------

$replacements = array();

// Check if $w (Website Settings) exists before using it
if(isset($w)) {
    $replacements['%%%website_name%%%'] = $w['website_name'];
    $replacements['%website_name%']     = $w['website_name'];
    $replacements['%%%profession%%%']   = $w['profession'];
    $replacements['%profession%']       = $w['profession'];
    $replacements['%%%industry%%%']     = $w['industry'];
    $replacements['%industry%']         = $w['industry'];
}

// Check if $pars (URL Parameters) exists
// IGNORED IF NOT AVAILABLE: We default to empty strings if $pars is missing
if(isset($pars)) {
    $replacements['%%%location%%%'] = isset($pars[1]) ? str_replace('-', ' ', $pars[1]) : 'Worldwide';
    $replacements['%%%category%%%'] = isset($pars[0]) ? str_replace('-', ' ', $pars[0]) : '';
}

// Helper function to process the text
function parse_seo_tags_safe($text, $replacements) {
    if (empty($text)) return "";
    return str_replace(array_keys($replacements), array_values($replacements), $text);
}

// -------------------------------------------------------------------------
// 3. MAP DATABASE COLUMNS TO VARIABLES
// -------------------------------------------------------------------------

if ($seo_data) {
    // Standard Meta Tags (Columns found in your table dump)
    $meta_title    = parse_seo_tags_safe($seo_data['title'], $replacements);
    $meta_desc     = parse_seo_tags_safe($seo_data['meta_desc'], $replacements);
    $meta_keywords = parse_seo_tags_safe($seo_data['meta_keywords'], $replacements);
    
    // Canonical URL
    $canonical_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Social / Open Graph Data
    $og_title = !empty($seo_data['facebook_title']) ? parse_seo_tags_safe($seo_data['facebook_title'], $replacements) : $meta_title;
    $og_desc  = !empty($seo_data['facebook_desc']) ? parse_seo_tags_safe($seo_data['facebook_desc'], $replacements) : $meta_desc;

    // Image Logic
    $og_image = $seo_data['facebook_image'];
    // Fix relative paths to absolute paths
    if (!empty($og_image) && strpos($og_image, 'http') === false) {
        $og_image = 'https://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($og_image, '/');
    }

    // Page Content (H1, H2, Main Text)
    $page_h1     = parse_seo_tags_safe($seo_data['h1'], $replacements);
    $page_h2     = parse_seo_tags_safe($seo_data['h2'], $replacements);
    $seo_content = parse_seo_tags_safe($seo_data['seo_text'], $replacements); // Table uses 'seo_text'

    /* IGNORED COLUMNS (Available in table but not used here):
    - $seo_data['content_css'] 
    - $seo_data['content_head'] 
    - $seo_data['content_footer']
    - $seo_data['breadcrumb']
    */
}
?>

<?php if ($seo_data): ?>

    <title><?php echo $meta_title; ?></title>
    <meta name="description" content="<?php echo $meta_desc; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    <meta name="robots" content="index, follow">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:site_name" content="<?php echo isset($w['website_name']) ? $w['website_name'] : ''; ?>">
    <meta property="og:title" content="<?php echo $og_title; ?>">
    <meta property="og:description" content="<?php echo $og_desc; ?>">
    <?php if ($og_image): ?>
    <meta property="og:image" content="<?php echo $og_image; ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $meta_title; ?>">
    <meta name="twitter:description" content="<?php echo $meta_desc; ?>">
    <?php if ($og_image): ?>
    <meta name="twitter:image" content="<?php echo $og_image; ?>">
    <?php endif; ?>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "<?php echo addslashes($meta_title); ?>",
      "description": "<?php echo addslashes($meta_desc); ?>",
      "url": "<?php echo $canonical_url; ?>"
    }
    </script>

    <div class="homepage-hero-text">
        <h1><?php echo $page_h1; ?></h1>
        
        <?php if ($page_h2): ?>
            <h2><?php echo $page_h2; ?></h2>
        <?php endif; ?>
        
        <div class="seo-content-text">
            <?php echo $seo_content; ?>
        </div>
    </div>

<?php endif; ?>