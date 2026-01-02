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
        //echo $query;
    } else {
        echo "<script>alert('Error Saving: " . mysql_error() . "');</script>";
    }
}
?>



<style>
    /* --- Modern SaaS SEO Manager Styling --- */
    :root {
        --primary: #4f46e5;
        --primary-soft: #eef2ff;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-700: #334155;
        --slate-900: #0f172a;
        --emerald: #10b981;
    }

    .schema-settings {
        width: 100%;
        max-width: 1200px;
        margin: 30px auto;
        background: #fff;
        border: 1px solid var(--slate-200);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        overflow: hidden;
        font-family: 'Inter', -apple-system, sans-serif;
    }

    /* --- Heading --- */
    .schema-settings h3 {
        background: white;
        color: var(--slate-900);
        margin: 0;
        padding: 24px 30px;
        font-size: 1.25rem;
        font-weight: 800;
        border-bottom: 1px solid var(--slate-100);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* --- Modern Tab Navigation --- */
    .tab-navigation {
        display: flex;
        background: var(--slate-50);
        padding: 8px 30px;
        gap: 12px;
        border-bottom: 1px solid var(--slate-200);
    }
    .sidebar-section{
          margin-top: 20px;
          display: flex;
          flex-direction: column;
    }

    .tab-btn {
        background: transparent;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        color: var(--slate-700);
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .tab-btn:hover {
        background: var(--slate-200);
        color: var(--slate-900);
    }

    .tab-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* --- Form Content --- */
    .tab-content { padding: 30px; display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.3s ease; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    .schema-settings table.regtext { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
    .schema-settings td { vertical-align: top; padding: 0 10px; border: none; }

    /* Section Headings (Meta vs Social) */
    .section-heading {
        background: var(--slate-100);
        color: var(--slate-700);
        font-weight: 700;
        padding: 8px 15px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    /* --- Inputs & Textareas --- */
    .control-label {
        color: var(--slate-700);
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 8px;
        display: block;
    }

    .meta-title-box {
        background: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-radius: 10px;
        padding: 12px;
        transition: all 0.2s;
    }

    .meta-title-box:focus-within {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    .meta-title-box textarea {
        width: 100%;
        border: none !important;
        background: transparent !important;
        font-size: 14px;
        color: var(--slate-900);
        line-height: 1.6;
        min-height: 60px;
    }

    .char-count { font-size: 11px; color: var(--slate-300); font-weight: 500; margin-top: 8px; }

    /* --- Advanced Tab (Code Editors) --- */
    .page-section {
        background: #fff;
        border: 1px solid var(--slate-200);
        border-radius: 12px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .page-section h3 {
        background: var(--slate-900);
        color: #fff;
        font-size: 14px;
        padding: 12px 20px;
    }

    .page-warning {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        margin: 15px;
        padding: 12px;
        font-size: 13px;
        border-radius: 4px;
        color: #92400e;
    }

    .page-textarea {
        font-family: 'JetBrains Mono', 'Fira Code', monospace !important;
        width: calc(100% - 30px);
        margin: 0 15px 15px;
        border-radius: 8px;
        padding: 15px;
        font-size: 13px;
        min-height: 150px;
    }

    /* --- Buttons --- */
    .schema-settings button[type="submit"] {
        background: var(--emerald);
        color: #fff;
        border: none;
        padding: 14px 24px;
        border-radius: 10px;
        font-weight: 700;
        transition: 0.2s;
        cursor: pointer;
        width: auto;
        min-width: 200px;
    }

    .schema-settings button[type="submit"]:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }

    /* --- Image Upload --- */
    .photo-upload-area {
        background: var(--slate-50);
        border: 2px dashed var(--slate-200);
        border-radius: 12px;
        padding: 40px;
        transition: 0.3s;
    }

    .photo-upload-area:hover {
        border-color: var(--primary);
        background: var(--primary-soft);
    }

    /* --- Sidebar Enhancements --- */
    .sidebar-box {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--slate-200);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 25px;
    }

    .sidebar-tabs { display: flex; background: var(--slate-50); border-bottom: 1px solid var(--slate-200); }
    
    .sidebar-tab {
        flex: 1; padding: 12px; font-size: 13px; font-weight: 700; border: none; background: transparent; cursor: pointer; color: var(--slate-700);
    }

    .sidebar-tab.active { color: var(--primary); border-bottom: 2px solid var(--primary); background: #fff; }

    .info-card {
        background: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
    }
    .label {
      display: inline;
      padding: .2em .6em .3em;
      font-size: 75%;
      font-weight: bold;
      line-height: 1;
      color: black;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: .25em;
  }


    /* Ensure hidden tabs don't take up space */
  .sidebar-content.hide {
      display: none !important;
  }

  /* Ensure active tabs are visible */
  .sidebar-content.active {
      display: block !important;
      animation: fadeInSidebar 0.3s ease;
  }

  /* Smooth fade-in effect */
  @keyframes fadeInSidebar {
      from { opacity: 0; transform: translateY(5px); }
      to { opacity: 1; transform: translateY(0); }
  }

  /* Styling for the tab buttons to show which is active */
  .sidebar-tab.active {
      background: #fff !important;
      color: #4f46e5 !important; /* Primary Indigo */
      border-bottom: 2px solid #4f46e5 !important;
      font-weight: 700;
  }
  
  .top-toolbar{
      display: flex;
      justify-content: space-between;
  }
    

</style>

  <div class="col-md-9">

    <div class="container"> 

      <div class="top-toolbar">

        <div class="left-side">
          <a  class="back-btn btn btn-primary" href='https://ww2.managemydirectory.com/admin/go.php?widget=seo_tool'>
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

            <a href="<?php echo $live_url; ?>" target="_blank" class="save-btn-top btn btn-warning" style="background-color: #253342; border: 1px solid #fff; margin-right: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
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
                       style="opacity:0; top:0; left:0; width:100%; height:100%; cursor:pointer;">
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
    <div id="pageOptions" class="sidebar-content hide">
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
            <textarea name="bread_crumbs" rows="3" style="width:100%" SEOplaceholder="%Data_filename% > %%%Post_title%%%"><?php echo htmlspecialchars($seo_row['bread_crumbs']); ?></textarea>
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

<script>
  document.addEventListener("DOMContentLoaded", function() {
   
    const tabButtons = document.querySelectorAll('.sidebar-tab');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
          
            const targetId = this.getAttribute('data-tab');

        
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

          
            document.querySelectorAll('.sidebar-content').forEach(content => {
                content.classList.remove('active');
                content.classList.add('hide');
            });

      
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hide');
                targetContent.classList.add('active');
            }
        });
    });
});
</script>