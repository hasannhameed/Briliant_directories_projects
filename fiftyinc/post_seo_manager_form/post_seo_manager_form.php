<?php
// -------------------------------------------------------
//  1. Database Connection & Setup
// -------------------------------------------------------
// $db = brilliantDirectories::getDatabaseConfiguration("database");

if($_GET['page_context'] == "search_result_page"){

  $page_context = 'search_result_page';
  
}else if($_GET['page_context'] == 'detail_page'){

  $page_context = 'Details Page - Post Details';

}else{
  
}

//$page_context = isset($_GET['page_context']) ? $_GET['page_context'] : 'search_result_page';

$data_id = isset($_GET['data_id']) ? intval($_GET['data_id']) : 0;

// Get Post Type Info
$row = mysql_fetch_assoc(mysql_query("SELECT * FROM data_categories WHERE data_id = $data_id"));
$posttype_id = $data_id;

// -------------------------------------------------------
//  2. FETCH DATA FROM list_seo (Read Operation)
// -------------------------------------------------------
// We query the list_seo table using the Post Type ID and Page Context
$fetch_query = mysql_query("
    SELECT * FROM list_seo 
    WHERE database_id = $posttype_id 
    AND section = '" . mysql_real_escape_string($page_context) . "' 
    LIMIT 1
");
$db_data = mysql_fetch_assoc($fetch_query);

// Decode the JSON 'content_settings' column (where we store schema, ad settings, etc.)
$extras = array();
if (!empty($db_data['content_settings'])) {
    $extras = json_decode($db_data['content_settings'], true);
}

// -------------------------------------------------------
//  3. MAP DATA TO FORM VARIABLES (The Fix)
//  We map list_seo columns -> to the names your HTML form expects
// -------------------------------------------------------

$seo_row = array();

// Basic SEO
$seo_row['meta_title']       = isset($db_data['title']) ? $db_data['title'] : '';
$seo_row['meta_description'] = isset($db_data['meta_desc']) ? $db_data['meta_desc'] : '';
$seo_row['meta_keywords']    = isset($db_data['meta_keywords']) ? $db_data['meta_keywords'] : '';

// Open Graph
$seo_row['og_title']         = isset($db_data['facebook_title']) ? $db_data['facebook_title'] : '';
$seo_row['og_description']   = isset($db_data['facebook_desc']) ? $db_data['facebook_desc'] : '';
$seo_row['og_image']         = isset($db_data['facebook_image']) ? $db_data['facebook_image'] : '';

// Advanced Code
$seo_row['page_level_css_style']    = isset($db_data['content_css']) ? $db_data['content_css'] : '';
$seo_row['page_level_header_style'] = isset($db_data['content_head']) ? $db_data['content_head'] : '';
$seo_row['page_level_footer_style'] = isset($db_data['content_footer']) ? $db_data['content_footer'] : '';

// Page Options (Direct Columns)
$seo_row['hide_header']          = isset($db_data['hide_header']) ? $db_data['hide_header'] : 0;
$seo_row['hide_footer']          = isset($db_data['hide_footer']) ? $db_data['hide_footer'] : 0;
$seo_row['hide_top_header_menu'] = isset($db_data['hide_header_links']) ? $db_data['hide_header_links'] : 0;
$seo_row['hide_main_menu']       = isset($db_data['hide_from_menu']) ? $db_data['hide_from_menu'] : 0;
$seo_row['bread_crumbs']         = isset($db_data['breadcrumb']) ? $db_data['breadcrumb'] : '';

// Custom JSON Fields (From content_settings)
$seo_row['custom_post_schema'] = isset($extras['custom_post_schema']) ? $extras['custom_post_schema'] : '';
$seo_row['hide_banner_ad']     = isset($extras['hide_banner_ad']) ? $extras['hide_banner_ad'] : 0;
$seo_row['enlarge_image']      = isset($extras['enlarge_image']) ? $extras['enlarge_image'] : 0;
$seo_row['apply_noindex']      = isset($extras['apply_noindex']) ? $extras['apply_noindex'] : 0;


// Default image for preview
$default_image = 'https://fiftyinc.comimages/default-og-image.png';
$display_image = !empty($seo_row['og_image']) ? 'https://fiftyinc.com'.$seo_row['og_image'] : $default_image;


// -------------------------------------------------------
//  4. FORM SUBMISSION LOGIC (Write Operation)
// -------------------------------------------------------
if (isset($_POST['save_schema']) || isset($_POST['save_advanced']) || isset($_POST['save_page_options'])) {
    
    // Gather Basic Inputs
    $title          = addslashes($_POST['meta_title']);
    $meta_desc      = addslashes($_POST['meta_description']);
    $meta_keywords  = addslashes($_POST['meta_keywords']);
    $fb_title       = addslashes($_POST['og_title']);
    $fb_desc        = addslashes($_POST['og_description']);
    
    // Gather Advanced Inputs
    $content_css    = addslashes($_POST['page_level_css_style']);
    $content_head   = addslashes($_POST['page_level_header_style']);
    $content_footer = addslashes($_POST['page_level_footer_style']);

    // Gather Page Options
    $hide_header       = isset($_POST['hide_header']) ? 1 : 0;
    $hide_footer       = isset($_POST['hide_footer']) ? 1 : 0;
    $hide_header_links = isset($_POST['hide_top_header_menu']) ? 1 : 0; 
    $hide_from_menu    = isset($_POST['hide_main_menu']) ? 1 : 0;       
    $breadcrumb        = addslashes($_POST['bread_crumbs']);

    // Handle Image Upload
    $fb_image_path = !empty($seo_row['og_image']) ? $seo_row['og_image'] : '';
    
    if (isset($_FILES['og_image']) && is_uploaded_file($_FILES['og_image']['tmp_name'])) {
        $username = brilliantDirectories::getDatabaseConfiguration('website_user');
        $password = brilliantDirectories::getDatabaseConfiguration('website_pass');
        $host     = brilliantDirectories::getDatabaseConfiguration('ftp_server');

        $ftp = ftp_connect($host);
        if ($ftp && ftp_login($ftp, $username, $password)) {
            ftp_pasv($ftp, true);
            $filename = time() . '_og_' . basename($_FILES['og_image']['name']);
            $remote_path = "/public_html/images/custom_og_images/" . $filename;

            if (ftp_put($ftp, $remote_path, $_FILES['og_image']['tmp_name'], FTP_BINARY)) {
                $fb_image_path = "/images/custom_og_images/" . $filename;
            }
            ftp_close($ftp);
        }
    }

    // Pack Extra Settings
    $settings_array = array(
        'custom_post_schema' => $_POST['custom_post_schema'],
        'hide_banner_ad'     => isset($_POST['hide_banner_ad']) ? 1 : 0,
        'enlarge_image'      => isset($_POST['enlarge_image']) ? 1 : 0,
        'apply_noindex'      => isset($_POST['apply_noindex']) ? 1 : 0
    );
    $content_settings = addslashes(json_encode($settings_array));

    // Check if Row Exists
    $exists_query = mysql_query("SELECT seo_id FROM list_seo WHERE database_id = $posttype_id AND section = '$page_context'");
    $exists = mysql_num_rows($exists_query);

    if ($exists) {
        // UPDATE
        $query = "
          UPDATE list_seo SET
            title           = '$title',
            meta_desc       = '$meta_desc',
            meta_keywords   = '$meta_keywords',
            facebook_title  = '$fb_title',
            facebook_desc   = '$fb_desc',
            facebook_image  = '$fb_image_path',
            content_css     = '$content_css',
            content_head    = '$content_head',
            content_footer  = '$content_footer',
            hide_header     = '$hide_header',
            hide_footer     = '$hide_footer',
            hide_header_links = '$hide_header_links',
            hide_from_menu  = '$hide_from_menu',
            breadcrumb      = '$breadcrumb',
            content_settings = '$content_settings'
          WHERE database_id = $posttype_id AND section = '$page_context'
        ";
        
    } else {
        // INSERT
        $filename_slug = $row['data_filename'];
        
        $query = "
          INSERT INTO list_seo 
          (
            database_id, section, seo_type, `database`, filename,
            title, meta_desc, meta_keywords, 
            facebook_title, facebook_desc, facebook_image,
            content_css, content_head, content_footer,
            hide_header, hide_footer, hide_header_links, hide_from_menu, breadcrumb,
            content_settings
          )
          VALUES 
          (
            '$posttype_id', '$page_context', 'custom_manager', 'data_categories', '$filename_slug',
            '$title', '$meta_desc', '$meta_keywords', 
            '$fb_title', '$fb_desc', '$fb_image_path',
            '$content_css', '$content_head', '$content_footer',
            '$hide_header', '$hide_footer', '$hide_header_links', '$hide_from_menu', '$breadcrumb',
            '$content_settings'
          )
        ";
    }

    if (mysql_query($query)) {
        echo "<script>
            if (typeof swal === 'function') {
                swal({ title: 'Success!', text: 'Saved Successfully!', type: 'success' }, function(){ window.location = window.location.href; });
            } else {
                alert('Saved Successfully!'); window.location = window.location.href;
            }
        </script>";
        echo $query;
    } else {
        echo "<script>alert('Error Saving: " . mysql_error() . "');</script>";
    }
}
?>

<style>
/* ------------------ Layout & Styling ------------------ */
.schema-settings {
    width: 98%;
    margin: 20px auto 50px;
    background: #fff;
    border: 1px solid #cbd7e3;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    border-radius: 4px;
}
.schema-settings h3 {
    background: #253342;
    color: #fff;
    margin: 0;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 600;
}

/* Tab Navigation Styles */
.tab-navigation {
    display: flex;
    background: #f5f6fa;
    border-bottom: 2px solid #e2e8f0;
    padding: 0;
    margin: 15px 0 0 0;
    gap: 8px;
    padding: 8px 8px 0 8px;
}

.tab-btn {
    background: #e8ecf1;
    border: 1px solid #d1d5db;
    border-bottom: none;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    border-radius: 0;
}

.tab-btn:hover {
    color: #334155!important;
    background: #fff!important;
}

.tab-btn.active {
    color: black;
    background: #fff;
    border-top: 3px solid #2563eb;
    border-left: 1px solid #d1d5db;
    border-right: 1px solid #d1d5db;
    border-bottom: none;
    padding-top: 10px;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}

.schema-settings table.regtext {
    width: 100%;
    border-collapse: collapse;
}
.schema-settings td {
    vertical-align: top;
    padding: 15px;
    border-bottom: 1px solid #ebeff3;
}

.section-heading {
    background: #253342;
    color: #fff;
    font-weight: 600;
    padding: 10px 15px;
    font-size: 14px;
    margin: -15px -15px 15px -15px;
}

.seo-meta-header {
    background: #f5f6fa;
    color: #253342;
    font-weight: 700;
    padding: 10px 15px;
    font-size: 16px;
    border-bottom: 1px solid #ebeff3;
}
.schema-settings input[type="text"],
.schema-settings textarea {
    width: 98%;
    border: 1px ;
    border-radius: 4px;
    padding: 8px 10px;
    background: #fff;
    font-size: 13px;
    transition: all 0.2s ease;
    resize: vertical; 
}
.schema-settings input:focus,
.schema-settings textarea:focus {
    border: 2px;
    box-shadow: none;
    outline: none;
    padding: 7px 9px;
}

.meta-title-box {
    position: relative;
}
.char-count {
    font-size: 11px;
    color: #888;
    margin-top: 4px;
    text-align: right;
}

.schema-settings button[type="submit"] {
    background: #038a72;
    color: #fff;
    border: none;
    padding: 10px 20px;
		width: 100%;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s ease, transform 0.2s ease;
}
.schema-settings button:hover {
    background-color: #038a72;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2),
              0 0 0 .25rem #335a7a40;
}

.change-btn {
    background: #6366f1;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 13px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s ease;
}
.change-btn:hover {
    background: #4f46e5;
}

.photo-upload-area {
    border: 2px dashed #d1d5db;
    padding: 40px 20px;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    position: relative;
		
    background: #ebeff3;
    transition: all 0.3s ease;
}


.photo-upload-area:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
}
.photo-upload-SEOplaceholder {
    pointer-events: none;
}
.photo-upload-heading {
    font-size: 18px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 15px;
}
.photo-upload-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
}
.photo-upload-icon svg {
    width: 100%;
    height: 100%;
}
.photo-upload-button {
    background: #0891b2;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    pointer-events: none;
}
.photo-upload-text {
    font-size: 13px;
    color: #6b7280;
    margin-top: 10px;
}
.photo-upload-subtext {
    font-size: 12px;
    color: #9ca3af;
}

.search-snippet-preview-box {
    border: 1px solid #cbd7e3;
    border-radius: 4px;
    padding: 15px;
    background-color: #f7f7f7;
    margin-top: 15px;
}
.search-snippet-preview-box h4 {
    margin: 0 0 10px 0;
    font-size: 14px;
    color: #253342;
}
.search-snippet-title {
    color: #1a0dab;
    font-size: 18px;
    font-weight: 400;
    margin-bottom: 2px;
}
.search-snippet-url {
    color: #006621;
    font-size: 13px;
    margin-bottom: 4px;
}
.search-snippet-description {
    color: #545454;
    font-size: 13px;
    line-height: 1.4;
}

/* ===== Advanced Tab - Page-Level Boxes ===== */
.page-section {
    background: #f5f8fb;
    border: 1px solid #d1dbe5;
    border-radius: 6px;
    margin: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    padding-bottom: 15px;
}
.page-section h3 {
    background: #1f4e6b;
    color: #fff;
    padding: 12px 18px;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
}
.page-warning {
    background: #fff8e5;
    border-left: 4px solid #f0ad4e;
    color: #555;
    font-size: 14px;
    padding: 10px 15px;
    margin: 15px;
    border-radius: 4px;
}
.page-warning b {
    color: #c77d15;
}
/* Advanced Page Textarea */
.page-textarea {
    background: #fff;
    border: 1px solid #d9e1ea;
    border-radius: 4px;
    width: 97%;
    margin: 0 15px 20px 15px;
    padding: 10px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 14px;
    min-height: 180px;
    resize: vertical;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.page-textarea:focus {
    border: 2px solid #3b82f6;
    box-shadow: none;
    outline: none;
    padding: 9px;
}
.advanced-save-btn {
    text-align: center;
    padding: 20px;
    margin: 0 20px;
};
</style>

	<div class="container"> <!-- Back Button Section -->
  <div class="top-toolbar">
  <div class="left-side">
    <a  class="back-btn" href='https://ww2.managemydirectory.com/admin/go.php?widget=seo_tool'>
        <i class="fa fa-reply"></i> Back
    </a>
  </div>
    <div class="right-side">
        <?php 
        // 1. Get the Data ID from the URL
        $post_data_id = intval($_GET['data_id']);

        // 2. Query the DB to get the filename (URL Slug) for this post type
        $sql_link = mysql_query("SELECT data_filename FROM data_categories WHERE data_id = '$post_data_id' LIMIT 1");
        $link_row = mysql_fetch_assoc($sql_link);
        
        // 3. Define your Domain
        $website_domain = "https://fiftyinc.com";
        
        // 4. Build the final URL (e.g., https://site.com/events)
        $live_url = $website_domain . "/" . $link_row['data_filename'];
        ?>
        <?php  
         if( $_GET['page_context'] != 'detail_page' ) {
        ?>

        <a href="<?php echo $live_url; ?>" target="_blank" class="save-btn-top" style="background-color: #253342; border: 1px solid #fff; margin-right: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa fa-external-link"></i> View Live Page
        </a>

        <?php } ?>

            <!-- <form method="post" enctype="multipart/form-data" style="display:inline;">
                <input type="hidden" name="post_data_id" value="<?php echo intval($row['data_id']); ?>">
                <button type="submit" name="save_schema" class="save-btn-top">
                    <i class="fa fa-save"></i> Save
                </button>
            </form> -->
        </div>
    </div>
		</div>
	
	
   <div class="col-md-9">
      <div class="schema-settings">
      <h3>
  <i class="fa fa-gear"></i>
  Manage <?php echo htmlspecialchars($row['data_name']); ?> 
  <?php echo ($_GET['page_context'] == 'search_result_page') ? 'Search Results Page' : (($_GET['page_context'] == 'detail_page') ? 'Detail Page' : 'Unknown Page'); ?> 
  SEO Settings
</h3>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
      <button type="button" class="tab-btn active" data-tab="seo">SEO Settings</button>
      <button type="button" class="tab-btn" data-tab="advanced">Advanced</button>
    </div>
<?php //echo $row['data_type'];
if ($row['data_type'] == 20) { 
  $SEOtitleplaceholder = htmlspecialchars($row['data_name'])." {search_term} in {website_name} {city}"; 
	$SEOkeyword = htmlspecialchars($row['data_name'])."Technology Hiring,Job Seekers,Job Seekers,Technology Hiring Events";
	$base_text = "Post";
} elseif($row['data_type'] == 4){
  $SEOtitleplaceholder = htmlspecialchars($row['data_name'])." {post_title} in {website_name} {city}";
	$SEOkeyword = htmlspecialchars($row['data_name'])."Technology Hiring,Job Seekers,Job Seekers,Technology Hiring Events";
	$base_text = "Group";
} 

?>
    <!-- SEO Settings Tab -->
    <div id="seoTab" class="tab-content active">
      <form method="post" enctype="multipart/form-data">
        <table class="regtext"> 
          <tr>
            <td colspan="2">
              <div class="section-heading">SEO Meta Tags</div>
            </td>
          </tr>
          <tr>
            <td>
               <label class="control-label">Meta Title <span style="color:red;">*</span></label>
    <div class="meta-title-box">
      <textarea name="meta_title" id="meta_title" rows="3" maxlength="160" placeholder="<?= $SEOtitleplaceholder ?>"><?php echo htmlspecialchars($seo_row['meta_title']); ?></textarea>
      <div class="char-count"><span id="metaTitleCount">0</span> characters</div>
    </div>
            </td>
            <td>
              <label class="control-label">Meta Description</label>
              <div class="meta-title-box">
                <textarea name="meta_description" id="meta_description" rows="3" maxlength="300" placeholder="<?= $SEOtitleplaceholder ?>"><?php echo htmlspecialchars($seo_row['meta_description']); ?></textarea>
                <div class="char-count"><span id="metaDescCount">0</span> characters</div>
              </div>
            </td>
          </tr>
			<tr>
  <td colspan="2">
    <label class="control-label">Meta Keywords</label>
    <div class="meta-title-box">
      <textarea name="meta_keywords" id="meta_keywords" rows="3" maxlength="250" placeholder="<?= $SEOkeyword ?>"><?php echo htmlspecialchars($seo_row['meta_keywords']); ?></textarea>
      <div class="char-count"><span id="metaKeywordsCount">0</span> characters</div>
    </div>
  </td>
</tr>
          <tr>
            <td colspan="2">
              <div class="section-heading">Open Graph Tags</div>
            </td>
          </tr>
          <tr>
            <td>
              <label class="control-label">OG Title</label>
              <div class="meta-title-box">
                <textarea name="og_title" id="og_title" rows="2" placeholder="<?= $SEOtitleplaceholder ?>"><?php echo htmlspecialchars($seo_row['og_title']); ?></textarea>
                <div class="char-count"><span id="ogtitleCount">0</span> characters</div>
              </div>
            </td>
            <td>
              <label class="control-label">OG Description</label>
              <div class="meta-title-box">
                <textarea name="og_description" id="og_description" rows="2" placeholder="<?= $SEOtitleplaceholder ?>"><?php echo htmlspecialchars($seo_row['og_description']); ?></textarea>
                <div class="char-count"><span id="ogdescriptionCount">0</span> characters</div>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <!-- <label style="font-weight:600; color:#444;">Upload OG Image</label> -->
              <label class="control-label">Upload OG Image</label>
              <div class="photo-upload-area" id="ogImageUploadArea">
                <div class="photo-upload-SEOplaceholder" id="ogImageSEOPlaceholder"
                  style="<?php echo !empty($seo_row['og_image']) ? 'display:none;' : ''; ?>">
                  <!-- <div class="photo-upload-heading">Upload Image</div> -->
                  <!-- <div class="photo-upload-icon">
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M40 10C50 10 60 15 65 25C68 32 68 40 65 47C60 57 50 62 40 62C30 62 20 57 15 47C12 40 12 32 15 25C20 15 30 10 40 10Z" fill="#93C5FD"/>
                      <path d="M40 25V47M40 25L33 32M40 25L47 32" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M28 50H52" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                  </div> -->
                  <img style="width: 100%; max-height: 200px;max-width: 370px;box-sizing:border-box;" id="image_preview_facebook_image" src="/images/upload_a_photo.jpg">
                  <!-- <div class="photo-upload-button">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8 3V13M8 3L5 6M8 3L11 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M3 11V12C3 12.5523 3.44772 13 4 13H12C12.5523 13 13 12.5523 13 12V11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    Click to Add Image
                  </div> -->
                  <a style="display: block;box-sizing: border-box;" href="https://fiftyinc.comadminfm?rdtoken=20251011080803&amp;fmtoken=e3b5bd4484c9c5cdddfa4174d2e4c2957bf5ca35202a927a678ace570b09bfe2&amp;field_id=facebook_image" class="popup sbuttonwiz7">
                                                                    <i class="fa fa-cloud-upload"></i> Click to Add Image                                                                </a>
                  <!-- <div class="photo-upload-subtext">At Least 200px x 200px</div> -->
                  <span style="font-size:11px;color:#666;display:inline-block;margin-top:5px; width:100%;">At Least 200px x 200px</span>
                </div>

                <img id="ogImagePreview" src="<?php echo htmlspecialchars($display_image); ?>" style="<?php echo empty($seo_row['og_image']) ? 'display:none;' : ''; ?>">

                <input type="file" name="og_image" accept="image/*" id="ogImageInput"
                       style="opacity:0; position:absolute; top:0; left:0; width:100%; height:100%; cursor:pointer;">
              </div>
              <div id="changeImageContainer"
                   style="margin-top:10px; text-align:center; <?php echo empty($seo_row['og_image']) ? 'display:none;' : ''; ?>">
                <button type="button" id="changeImageBtn" class="change-btn">
                  <i class="fa fa-refresh"></i> Change Image
                </button>
              </div>
            </td>
            <!-- <td style="padding-left: 10px;text-align:center;max-width: 187px;">
                                                        <label class="control-label">Social Media Shared Image</label>
                                                        <div class="controls">
                                                            <div class="social_image_container">
                                                                <div style="display:block;text-align:center;">
                                                                    <img style="width: 100%; max-height: 200px;max-width: 370px;box-sizing:border-box;" id="image_preview_facebook_image" src="/images/upload_a_photo.jpg" title="http://everythingblabs.directoryup.com/images/1757583931_post_cropped_post.jpg"><br>
                                                                </div>
                                                                <input type="hidden" name="facebook_image" value="" id="facebook_image">
                                                                <a style="display: block;box-sizing: border-box;" href="https://everythingblabs.directoryup.com/adminfm?rdtoken=20251011074306&amp;fmtoken=e3b5bd4484c9c5cdddfa4174d2e4c2957bf5ca35202a927a678ace570b09bfe2&amp;field_id=facebook_image" class="popup sbuttonwiz7"><i class="fa fa-cloud-upload"></i> Upload Image</a>
                                                                <a href="#" style="font-weight: bold; float: left; margin-top: 7px; color: rgb(225, 105, 92); display: none;" class="delete_image" id="delete_image_facebook_image">Remove Image</a>
                                                                <span style="font-size:11px;color:#666;display:inline-block;margin-top:5px; width:100%;">At Least 200px x 200px</span>
                                                            </div>
                                                        </div>
                                                    </td> -->
            <td>
              <label class="control-label">Custom Post Schema (JSON)</label>
              <div class="meta-title-box">
                <textarea name="custom_post_schema" id="custom_post_schema" rows="12"><?php echo htmlspecialchars($seo_row['custom_post_schema']); ?></textarea>
                <div class="char-count"><span id="custompostschemaCount">0</span> characters</div>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:center; padding:20px;">
              <button type="submit" name="save_schema"><i class="fa fa-save"></i> Save</button>
            </td>
          </tr>
        </table>
        <input type="hidden" name="post_data_id" value="<?php echo intval($row['data_id']); ?>">
      </form>
    </div>

    <!-- Advanced Tab -->
    <div id="advancedTab" class="tab-content">
      <form method="post">
        <div class="page-section">
          <h3>Page-Level CSS Styles</h3>
          <div class="page-warning">
            ⚠️ <b>WARNING</b> - DO NOT include &lt;style&gt; tags. Directly input additional CSS rules that will be used on this page.
          </div>
          <textarea class="page-textarea" name="page_level_css_style" SEOplaceholder="Type CSS here..."><?php echo htmlspecialchars($seo_row['page_level_css_style']); ?></textarea>
        </div>

        <div class="page-section">
          <h3>Page-Level HEAD Tags</h3>
          <div class="page-warning">
            ⚠️ <b>WARNING</b> - DO NOT include &lt;head&gt; tags. Directly input additional code that will render in the HEAD of this page.
          </div>
          <textarea class="page-textarea" name="page_level_header_style" SEOplaceholder="Type HEAD code here..."><?php echo htmlspecialchars($seo_row['page_level_header_style']); ?></textarea>
        </div>

        <div class="page-section">
          <h3>Page-Level Footer Javascript</h3>
          <div class="page-warning">
            ⚠️ <b>WARNING</b> - Directly input additional Javascript Code that will render in the Footer of this page.
          </div>
          <textarea class="page-textarea" name="page_level_footer_style" SEOplaceholder="Type JS code here..."><?php echo htmlspecialchars($seo_row['page_level_footer_style']); ?></textarea>
        </div>

        <div class="advanced-save-btn">
          <button type="submit" name="save_advanced"><i class="fa fa-save"></i> Save Advanced Settings</button>
        </div>
        <input type="hidden" name="post_data_id" value="<?php echo intval($row['data_id']); ?>">
      </form>
    </div>
  </div>	
</div>
<?php
	if (isset($_POST['save_page_options'])) {
    $post_type_id = intval($_POST['post_data_id']);
    $hide_banner_ad       = isset($_POST['hide_banner_ad']) ? 1 : 0;
    $hide_header          = isset($_POST['hide_header']) ? 1 : 0;
    $hide_footer          = isset($_POST['hide_footer']) ? 1 : 0;
    $hide_top_header_menu = isset($_POST['hide_top_header_menu']) ? 1 : 0;
    $hide_main_menu       = isset($_POST['hide_main_menu']) ? 1 : 0;
    $enlarge_image        = isset($_POST['enlarge_image']) ? 1 : 0;
    $apply_noindex        = isset($_POST['apply_noindex']) ? 1 : 0;
    $bread_crumbs         = addslashes(trim($_POST['bread_crumbs']));

    $exists = mysql_num_rows(mysql_query("
        SELECT * FROM custom_seo 
        WHERE post_type_id = $post_type_id 
        AND page_type = '" . addslashes($_GET['page_context']) . "'
    "));
    if ($exists) {
        $query = "
          UPDATE custom_seo SET
            hide_banner_ad = '$hide_banner_ad',
            hide_header = '$hide_header',
            hide_footer = '$hide_footer',
            hide_top_header_menu = '$hide_top_header_menu',
            hide_main_menu = '$hide_main_menu',
            enlarge_image = '$enlarge_image',
            apply_noindex = '$apply_noindex',
            bread_crumbs = '$bread_crumbs'
          WHERE post_type_id = $post_type_id 
          AND page_type = '$page_context'
        ";
    } else {
        $query = "
          INSERT INTO custom_seo 
          (post_type_id, page_type, hide_banner_ad, hide_header, hide_footer, hide_top_header_menu, hide_main_menu, enlarge_image, apply_noindex, bread_crumbs)
          VALUES 
          ('$post_type_id', '$page_context', '$hide_banner_ad', '$hide_header', '$hide_footer', '$hide_top_header_menu', '$hide_main_menu', '$enlarge_image', '$apply_noindex', '$bread_crumbs')
        ";
    }

    $result = mysql_query($query);
    if ($result) {
        echo "<script>
            if (typeof swal === 'function') {
                swal({ title: 'Success!', text: 'Page Options Saved!', type: 'success' }, function(){
                    window.location = window.location.href;
                });
            } else {
                alert('Page Options Saved!');
                window.location = window.location.href;
            }
        </script>";
    } else {
        echo "<script>alert('Failed to Save Page Options.');</script>";
    }
}
?>

	<div class="col-md-3">
  <div class="sidebar-box">
    <!-- Sidebar Tab Navigation -->
    <div class="sidebar-tabs">
      <button class="sidebar-tab active" data-tab="pageDetails">Page Details</button>
      <button class="sidebar-tab" data-tab="pageOptions">Page Options</button>
    </div>

    <!-- PAGE DETAILS TAB -->
    <div id="pageDetails" class="sidebar-content active">
      <div class="sidebar-section">
        <div class="info-card">
          <p><span class="label">SEO Template:</span> #<?php echo intval($row['data_id']); ?></p>
        </div>
		   <div class="info-card">
          <p><span class="label">Type:</span> <?= $base_text ?></p>
        </div>
        <div class="info-card">
          <p>
  <span class="label">Details Page:</span>
  <?php echo ($_GET['page_context'] == 'search_result_page') ? 'Search Results Page' : (($_GET['page_context'] == 'detail_page') ? 'Detail Page' : 'Unknown Page'); ?>
</p>

        </div>
        <?/*<p class="last-updated">Last Update: <?php echo date('m/d/Y h:i a', strtotime($seo_row['date_updated'] ?? 'now')); ?></p>*/?>
      </div>
    </div>

    <!-- PAGE OPTIONS TAB -->
    <div id="pageOptions" class="sidebar-content">
      <form method="post">
        <div class="sidebar-section">
          <h5>Display Settings</h5>
          <label><input type="checkbox" name="hide_banner_ad" value="1" <?php if(!empty($seo_row['hide_banner_ad'])) echo 'checked'; ?>> Hide Banner Ad Modules</label>
          <label><input type="checkbox" name="hide_header" value="1" <?php if(!empty($seo_row['hide_header'])) echo 'checked'; ?>> Hide Header</label>
          <label><input type="checkbox" name="hide_footer" value="1" <?php if(!empty($seo_row['hide_footer'])) echo 'checked'; ?>> Hide Footer</label>
          <label><input type="checkbox" name="hide_top_header_menu" value="1" <?php if(!empty($seo_row['hide_top_header_menu'])) echo 'checked'; ?>> Hide Top Header Menu</label>
          <label><input type="checkbox" name="hide_main_menu" value="1" <?php if(!empty($seo_row['hide_main_menu'])) echo 'checked'; ?>> Hide Main Menu</label>
          <label><input type="checkbox" name="enlarge_image" value="1" <?php if(!empty($seo_row['enlarge_image'])) echo 'checked'; ?>> Click-Enlarge Images</label>

          <h5 class="mt-3">Advanced Options</h5>
          <label><input type="checkbox" name="apply_noindex" value="1" <?php if(!empty($seo_row['apply_noindex'])) echo 'checked'; ?>> Apply NoIndex, NoFollow</label>

          <h5 class="mt-3">Breadcrumbs <span>(optional)</span></h5>
          <div class="textarea-wrapper">
            <textarea name="bread_crumbs" rows="3" SEOplaceholder="%Data_filename% > %%%Post_title%%%"><?php echo htmlspecialchars($seo_row['bread_crumbs']); ?></textarea>
            <span class="edit-icon">✎</span>
          </div>

          <div style="text-align:center; margin-top:15px;">
            <button type="submit" name="save_page_options" class="btn btn-primary" style="width:100%;">
              <i class="fa fa-save"></i> Save Page Options
            </button>
          </div>
          <input type="hidden" name="post_data_id" value="<?php echo intval($row['data_id']); ?>">
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
  const tabButtons = document.querySelectorAll('.tab-btn');
  
  tabButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetTab = this.getAttribute('data-tab');
      
      // Remove active class from all buttons
      tabButtons.forEach(btn => btn.classList.remove('active'));
      
      // Add active class to clicked button
      this.classList.add('active');
      
      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      
      // Show target tab content
      if (targetTab === 'advanced') {
        document.getElementById('advancedTab').classList.add('active');
      } else {
        document.getElementById('seoTab').classList.add('active');
      }
    });
  });

// Character counter functionality
function updateCharCount(textareaId, countId) {
  const textarea = document.getElementById(textareaId);
  const counter = document.getElementById(countId);
  if (textarea && counter) {
    // Initial update (for pre-filled data)
    counter.textContent = textarea.value.length;
    // Update on user input
    textarea.addEventListener('input', function() {
      counter.textContent = this.value.length;
    });
  }
}

  document.addEventListener("DOMContentLoaded", function() {
  updateCharCount('meta_title', 'metaTitleCount');
  updateCharCount('meta_description', 'metaDescCount');

  updateCharCount('og_title', 'ogtitleCount');
  updateCharCount('og_description', 'ogdescriptionCount');
  updateCharCount('custom_post_schema', 'custompostschemaCount');

  // Image upload functionality
  const ogImageInput = document.getElementById('ogImageInput');
  const changeImageBtn = document.getElementById('changeImageBtn');
  
  if (ogImageInput) {
    ogImageInput.addEventListener('change', function(e) {
      if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('ogImagePreview').src = e.target.result;
          document.getElementById('ogImagePreview').style.display = 'block';
          document.getElementById('ogImageSEOPlaceholder').style.display = 'none';
          document.getElementById('changeImageContainer').style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
      }
    });
  }

  if (changeImageBtn) {
    changeImageBtn.addEventListener('click', function() {
      ogImageInput.click();
    });
  }
});
	   });
</script>




<!-- css -->

<style>
    /* Style for dynamic character counter */
    .char-counter {
        font-size: 11px;
        color: #888;
        margin-top: 5px;
        text-align: right;
    }
    .char-count {
        font-weight: bold;
        color: #253342;
    }



    .meta-title-box {
    background: #f7f9fb;
    border: 1px solid #d8e2ef;
    border-radius: 6px;
    padding: 10px 10px 6px 17px;
    position: relative;
    transition: all 0.3s ease;
    }

    .meta-title-box:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
    }

    .meta-title-box textarea {
    width: 100%;
    border: none;
    background: transparent;
    resize: none;
    font-size: 14px;
    color: #333;
    line-height: 1.5;
    outline: none;
    }

    .meta-title-box textarea::placeholder {
    color: #9aa0a6;
    }

    .meta-title-box .char-count {
    font-size: 12px;
    color: #666;
    margin-top: 6px;
    text-align: left;
    }

    .section-heading {
        background-color: #253342;  /* same dark blue as form header */
        color: #fff;
        font-weight: 600;
        padding: 8px 12px;
        margin: 20px 0 10px 0;
        border-radius: 4px;
        font-size: 14px;
    }

    .seo-tab-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 15px 0 5px 10px;
    }

    .seo-tab {
        font-weight: 600;
        background: #dde5ec;
        box-shadow: 0 0px 2px rgba(100, 136, 177, .4);
        color: #636f7c;
        font-size: 15px;
        padding: 3px 20px 0;
        height: 36px;
        border: none;
        border-top: 3px solid transparent;
        border-radius: 4px 4px 0 0;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .seo-tab:hover {
        background: #fff;
        border-top: 3px solid #007bff;
        color: #000;
    }

    .seo-tab.active {
        background: #fff;
        border-top: 3px solid #007bff;
        color: #000;
    }

    /* Tab Navigation Styles */
            .tab-navigation {
                display: flex;
                background: #f5f6fa;
                border-bottom: 1px solid #cbd7e3;
                padding: 0;
                margin: 0;
            }
            
            .tab-btn {
                background: transparent;
                border: none;
                padding: 12px 24px;
                font-size: 14px;
                font-weight: 500;
                color: #64748b;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                transition: all 0.3s ease;
                position: relative;
            }
            
            .tab-btn:hover {
                color: #334155;
                background: #e2e8f0;
            }
            
            .tab-btn.active {
                color: #2563eb;
                border-bottom-color: #2563eb;
                background: #fff;
            }

    .container {
        width: 100%;
        max-width: 1200px; /* optional, adjust as needed */
        margin: 0;
        padding-left: 20px;
    }

    /* ---------- ADVANCED SECTION DESIGN ---------- */
    .page-section {
    background: #f5f8fb;
    border: 1px solid #d1dbe5;
    border-radius: 6px;
    margin-bottom: 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-section h3 {
    background: #1f4e6b;
    color: #fff;
    padding: 12px 18px;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    }

    .page-warning {
    background: #fff !important;
    border-left: 4px solid #f0ad4e;
        display: inline-block;
    color: #555;
    font-size: 14px;
    padding: 10px 15px;
    margin: 15px;
    border-radius: 4px;
    }

    .page-warning b {
    color: black !important;
    }

    .page-textarea {
    background: #fff;
    border: 1px solid #d9e1ea;
    border-radius: 4px;
    width: calc(100% - 30px);
    margin: 0 15px 20px 15px;
    padding: 10px;
    font-family: monospace;
    font-size: 14px;
    min-height: 180px;
    resize: vertical;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }




    .top-toolbar {
        width: 98%;
        margin: 20px auto 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .back-btn {
        background: #eff6ff;
        color: #253342;
        border: 1px solid #cbd7e3;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .back-btn:hover {
        background: #dbeafe;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }

    /* Right-Side Save Button */
    .save-btn-top {
        background: #038a72;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .save-btn-top:hover {
      color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2),
                    0 0 0 .25rem #335a7a40;
    }
    .save-btn-top:focus {
      color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2),
                    0 0 0 .25rem #335a7a40;
    }

    /* === Sidebar Styling === */
    .sidebar-box {
    background:#f7f4f4 !important;
        
    border: 1px solid #cbd7e3;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    margin-top: 20px;
    }

    .sidebar-box .tab-navigation {
    display: flex;
    justify-content: space-between;
    padding: 8px;
    background: #f5f6fa;
    border-bottom: 2px solid #e2e8f0;
    }

    .sidebar-box .tab-btn {
    flex: 1;
    text-align: center;
    background: #e8ecf1;
    border: 1px solid #d1d5db;
    border-bottom: none;
    padding: 10px 0;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    }

    .sidebar-box .tab-btn.active {
    background: #fff;
    color: black;
    border-top: 3px solid #2563eb;
    border-left: 1px solid #d1d5db;
    border-right: 1px solid #d1d5db;
    }

    .sidebar-content {
    padding: 15px;
    }

    .sidebar-section p {
    font-size: 12px;
    color: #444;
    margin-bottom: 8px;
    }

    .last-updated {
    font-size: 12px;
    color: #888;
    border-top: 1px solid #eee;
    padding-top: 8px;
    margin-top: 10px;
    }



    /* Sidebar Container */
    .sidebar-box {
    background: #fff;
    border: 1px solid #d0dae3;
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    font-family: "Poppins", sans-serif;
    }

    /* Tabs */
    .sidebar-tabs {
    display: flex;
    border-bottom: 1px solid #cfd8e3;
    }

    .sidebar-tab {
    flex: 1;
    background: #e5ecf4;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 10px 0;
    text-align: center;
    font-weight: 600;
    color: #475569;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.25s ease;
    }

    .sidebar-tab.active {
    background: #f5f1f1;
    color: #0f172a;
    border-top: 2px solid #60a5fa;
    }

    .sidebar-tab:hover {
    background: #f8fafc;
    }

    /* Content Areas */
    .sidebar-content {
    display: none;
    padding: 15px;
    }

    .sidebar-content.active {
    display: block;
    }

    .sidebar-section h5 {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 10px;
    margin-top: 10px;
    }

    .sidebar-section label {
    font-size: 13px;
    color: #374151;
    display: block;
    margin-bottom: 6px;
    cursor: pointer;
    }

    .sidebar-section input[type="checkbox"] {
    margin-right: 6px;
    }

    .sidebar-section textarea {
    width: 100%;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    padding: 8px;
    font-size: 13px;
    resize: vertical;
    background: #f9fafb;
    }

    .sidebar-section textarea:focus {
    outline: none;
    border-color: #3b82f6;
    background: #fff;
    }

    .sidebar-section span {
    font-size: 12px;
    color: #64748b;
    }

    .last-updated {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 10px;
    border-top: 1px solid #e2e8f0;
    padding-top: 8px;
    }

    /* Optional spacing helper */
    .mt-3 {
    margin-top: 15px;
    }



    .tabs {
        display: flex;
        background: #e8e8e8;
        }

        .tab {
        flex: 1;
        padding: 12px 20px;
        text-align: center;
        font-size: 14px;
        color: #666;
        background: #e8e8e8;
        border: none;
        cursor: pointer;
        font-weight: 500;
        }

        .tab.active {
        background: white;
        color: #333;
        border-bottom: 3px solid #4A90E2;
        }

        .sidebar-content {
        display: none;
        padding: 20px;
        background: #fafafa;
        }

        .sidebar-content.active {
        display: block;
        }

        .sidebar-section h5 {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        }

        .sidebar-section h5.mt-3 {
        margin-top: 24px;
        }

        .sidebar-section h5 span {
        color: #999;
        font-weight: 400;
        font-size: 13px;
        }

        .sidebar-section label {
        display: block;
        font-size: 13px;
        font-weight: normal;
        color: #555;
        margin-bottom: 8px;
        cursor: pointer;
        line-height: 1.4;
        }

        .sidebar-section input[type="checkbox"] {
        margin-right: 8px;
        cursor: pointer;
        width: 15px;
        height: 15px;
        vertical-align: middle;
        }

        .textarea-wrapper {
        position: relative;
        margin-top: 8px;
        }

        .sidebar-section textarea {
        width: 100%;
        padding: 10px;
        font-size: 13px;
        border: 1px solid #ddd;
        border-radius: 3px;
        resize: vertical;
        font-family: 'Courier New', monospace;
        color: #666;
        line-height: 1.5;
        }

        .sidebar-section textarea:focus {
        outline: none;
        border-color: #4A90E2;
        }

        .edit-icon {
        position: absolute;
        right: 8px;
        bottom: 8px;
        color: #999;
        font-size: 14px;
        pointer-events: none;
        }

        .info-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 9px;
        padding: 4px 3px 4px 3px;
        margin-bottom: 12px;
        font-size: 13px;
        color: #333;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .info-card p {
        margin: 0;
        line-height: 1.5;
        }

        .info-card .label {
        font-weight: 600;
        color: #333;
        }

        .last-updated {
        margin-top: 8px;
        font-size: 12px;
        color: #999;
        padding-left: 2px;
        }

        .regtext td {
        width: 50% !important;
    }

    #ogImagePreview {
    border: 1px solid rgb(221, 221, 221);
    border-radius: 4px;
    margin: 0 auto; /* Center the image horizontally */
    max-width: 100%; /* Make sure it doesn't exceed parent width */
    }

    .control-label {
        color: #636f7c;
        font-weight: 700;
        display: inline-block;
        font-size: 14px;
        text-align: left;
        width: 100%;
        margin-bottom: 10px;
    }

</style>
<!--  End -->


<!-- Script -->

<script>
document.addEventListener("DOMContentLoaded", function() {
  const ogImageInput = document.getElementById("ogImageInput");
  const ogImagePreview = document.getElementById("ogImagePreview");
  const ogImagePlaceholder = document.getElementById("ogImagePlaceholder");
  const ogImageContainer = document.getElementById("ogImageContainer");
  const changeBtn = document.getElementById("changeImageBtn");

  // When the "Change Image" button is clicked
  changeBtn?.addEventListener("click", function() {
    ogImageInput.click(); // trigger file select
  });

  // When a new file is selected
  ogImageInput?.addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(evt) {
        ogImagePreview.src = evt.target.result;
        ogImagePlaceholder.style.display = "none";
        ogImageContainer.style.display = "block";
      };
      reader.readAsDataURL(file);
    }
  });
});
</script>


<script>
  // Character counter functionality
  function updateCharCount(textareaId, countId) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(countId);
    if (!textarea || !counter) return;

    // Initial count (for pre-filled data)
    counter.textContent = textarea.value.length;

    // Update as user types
    textarea.addEventListener('input', function() {
      counter.textContent = this.value.length;
    });
  }

  // Initialize all counters once DOM is ready
  document.addEventListener("DOMContentLoaded", function() {
    updateCharCount('meta_title', 'metaTitleCount');
    updateCharCount('meta_description', 'metaDescCount');
	  updateCharCount('meta_keywords', 'metaKeywordsCount');
    updateCharCount('og_title', 'ogtitleCount');
    updateCharCount('og_description', 'ogdescriptionCount');
    updateCharCount('custom_post_schema', 'custompostschemaCount');
  });
</script>


<script>
// document.getElementById('seoSettingsBtn').addEventListener('click', function() {
//   document.getElementById('seoSettingsSection').style.display = 'block';
//   document.getElementById('advancedSection').style.display = 'none';
//   this.classList.add('active');
//   document.getElementById('advancedBtn').classList.remove('active');
// });

// document.getElementById('advancedBtn').addEventListener('click', function() {
//   document.getElementById('seoSettingsSection').style.display = 'none';
//   document.getElementById('advancedSection').style.display = 'block';
//   this.classList.add('active');
//   document.getElementById('seoSettingsBtn').classList.remove('active');
// });
</script>


<script>
document.querySelectorAll('.sidebar-tab').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.sidebar-tab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.sidebar-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(btn.dataset.tab).classList.add('active');
  });
});
</script>

<!-- end -->

