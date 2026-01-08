<?php

// -------------------------------------------------------------------------
// 1. CONFIGURATION & DATA FETCH
// -------------------------------------------------------------------------

$data_id = isset($dc['data_id']) ? intval($dc['data_id']) : 0;

$seo_sql = "
    SELECT *
    FROM custom_seo
    WHERE post_type_id = $data_id LIMIT 1
";

echo $seo_sql;

$seo_query = mysql_query($seo_sql);
$seo_data  = ($seo_query && mysql_num_rows($seo_query) > 0)
    ? mysql_fetch_assoc($seo_query)
    : false;

// -------------------------------------------------------------------------
// 2. VARIABLE REPLACEMENTS
// -------------------------------------------------------------------------

$replacements = array();

if (!empty($w)) {
    $replacements['%%%website_name%%%'] = $w['website_name'];
    $replacements['%website_name%']     = $w['website_name'];
    $replacements['%%%profession%%%']   = $w['profession'];
    $replacements['%profession%']       = $w['profession'];
    $replacements['%%%industry%%%']     = $w['industry'];
    $replacements['%industry%']         = $w['industry'];
}

if (!empty($pars)) {
    $replacements['%%%location%%%'] = !empty($pars[1]) ? str_replace('-', ' ', $pars[1]) : 'Worldwide';
    $replacements['%%%category%%%'] = !empty($pars[0]) ? str_replace('-', ' ', $pars[0]) : '';
}

function parse_seo_tags_safe($text, $replacements) {
    if (empty($text)) return '';
    return str_replace(array_keys($replacements), array_values($replacements), $text);
}

// -------------------------------------------------------------------------
// 3. MAP DATABASE VALUES
// -------------------------------------------------------------------------

if ($seo_data) {

    $meta_title    = parse_seo_tags_safe($seo_data['meta_title'], $replacements);
    $meta_desc     = parse_seo_tags_safe($seo_data['meta_description'], $replacements);
    $meta_keywords = parse_seo_tags_safe($seo_data['meta_keywords'], $replacements);

    $canonical_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $og_title = !empty($seo_data['og_title'])
        ? parse_seo_tags_safe($seo_data['og_title'], $replacements)
        : $meta_title;

    $og_desc = !empty($seo_data['og_description'])
        ? parse_seo_tags_safe($seo_data['og_description'], $replacements)
        : $meta_desc;

    $og_image = $seo_data['og_image'];
    if (!empty($og_image) && strpos($og_image, 'http') === false) {
        $og_image = 'https://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($og_image, '/');
    }

    $page_css      = $seo_data['page_level_css_style'];
    $page_head     = $seo_data['page_level_header_style'];
    $page_js       = $seo_data['page_level_footer_style'];
    $custom_schema = $seo_data['custom_post_schema'];
}
?>

<?php if ($seo_data): ?>

<!-- ================= CUSTOM SCHEMA ================= -->
<?php if (!empty($custom_schema)): ?>
<script type="application/ld+json">
<?php echo stripslashes($custom_schema); ?>
</script>
<?php endif; ?>

<!-- ================= PAGE-LEVEL INJECTIONS ================= -->
<?php if (!empty($page_head)) echo stripslashes($page_head); ?>
<?php if (!empty($page_css)): ?>
<style><?php echo stripslashes($page_css); ?></style>
<?php endif; ?>
<?php if (!empty($page_js)) echo stripslashes($page_js); ?>

<!-- ================= SEO OVERRIDE (NO DUPLICATES) ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    var metaTitle       = <?php echo json_encode($meta_title); ?>;
    var metaDesc        = <?php echo json_encode($meta_desc); ?>;
    var metaKeywords    = <?php echo json_encode($meta_keywords); ?>;
    var canonicalUrl    = <?php echo json_encode($canonical_url); ?>;

    var ogTitle         = <?php echo json_encode($og_title); ?>;
    var ogDesc          = <?php echo json_encode($og_desc); ?>;
    var ogImage         = <?php echo json_encode($og_image); ?>;

    /* ---------- TITLE ---------- */
    if (metaTitle) {
        document.title = metaTitle;
    }

    /* ---------- BASIC META ---------- */
    var descTag = document.querySelector('meta[name="description"]');
    if (descTag && metaDesc) {
        descTag.setAttribute('content', metaDesc);
    }

    var keywordsTag = document.querySelector('meta[name="keywords"]');
    if (keywordsTag && metaKeywords) {
        keywordsTag.setAttribute('content', metaKeywords);
    }

    /* ---------- CANONICAL ---------- */
    var canonicalTag = document.querySelector('link[rel="canonical"]');
    if (canonicalTag && canonicalUrl) {
        canonicalTag.setAttribute('href', canonicalUrl);
    }

    /* ---------- OG TAGS ---------- */
    var ogTitleTag = document.querySelector('meta[property="og:title"]');
    if (ogTitleTag && ogTitle) {
        ogTitleTag.setAttribute('content', ogTitle);
    }

    var ogDescTag = document.querySelector('meta[property="og:description"]');
    if (ogDescTag && ogDesc) {
        ogDescTag.setAttribute('content', ogDesc);
    }

    var ogImageTag = document.querySelector('meta[property="og:image"]');
    if (ogImageTag && ogImage) {
        ogImageTag.setAttribute('content', ogImage);
    }

    var ogUrlTag = document.querySelector('meta[property="og:url"]');
    if (ogUrlTag && canonicalUrl) {
        ogUrlTag.setAttribute('content', canonicalUrl);
    }

});
</script>


<?php endif; ?>
