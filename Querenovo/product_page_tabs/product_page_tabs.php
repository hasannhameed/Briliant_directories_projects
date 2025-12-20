
<?php
/* ===================================================
   1. CONTROLLER LOGIC (Prepare Data First)
   =================================================== */

// Sanitize inputs for Security
$current_group_id = (int)$group['group_id'];
$data_type        = (int)$group['data_type'];
$user_id          = (int)$group['user_id'];

// Fetch Main Group Data
$qr = mysql_query("
    SELECT group_desc, video_link, user_id
    FROM users_portfolio_groups 
    WHERE group_id = '$current_group_id'
    LIMIT 1
");
$data = mysql_fetch_assoc($qr);

// Define Flags
$hasDescription = !empty($data['group_desc']);
$hasVideo       = !empty($data['video_link']);
$hasUser        = ($user_id > 0);

// Fetch User/Manufacturer Data (If User Exists)
$manufacturerData = [];

if ($hasUser) {
    // Get User Photo/Logo
    $sql_photo = "
        SELECT file FROM users_photo 
        WHERE user_id = '$user_id' 
        ORDER BY CASE WHEN type = 'logo' THEN 1 WHEN type = 'photo' THEN 2 ELSE 3 END
        LIMIT 1
    ";
    $res_photo = mysql_query($sql_photo);
    $row_photo = mysql_fetch_assoc($res_photo);
    
    $manufacturerData['logo'] = !empty($row_photo['file']) ? "/logos/profile/" . $row_photo['file'] : "";
    $manufacturerData['name'] = $user['company'] ?: $user['full_name'];
    $manufacturerData['link'] = "/" . $user['filename'];
    $manufacturerData['desc'] = $user['search_description'];

     $manufacturerData['tags'] = $group['post_tags']; 
}


?>
<style>
    /* Tag Container */
    .manufacturer-tags {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px; /* Space between tags */
    }

    /* Individual Tag Style (Matches your theme) */
    .bd-tag-badge {
        display: inline-block;
        background-color: #f1f5f9; /* Light Gray Background */
        color: #475569;            /* Slate Gray Text */
        font-size: 12px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 6px;        /* Rounded corners */
        border: 1px solid #e2e8f0; /* Subtle border */
        white-space: nowrap;
    }

    .bd-tag-badge:hover {
        background-color: #e2e8f0;
        color: #0f2133;
    }
</style>
<style>
    /* --- Tabs Navigation --- */
    .bd-tabs {
        margin-top: 100px;
        border-bottom: 2px solid #e0e0e0;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .bd-tabs > li > a {
        border: none;
        background: transparent;
        color: #555;
        font-weight: 600;
        padding: 12px 20px;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px; /* Overlap border */
    }
    .bd-tabs > li > a:hover {
        background: transparent;
        color: #000;
    }
    .bd-tabs > li.active > a {
        border: none;
        border-bottom: 3px solid #8b3a4f; /* Brand Color */
        color: #000;
        background: transparent;
    }

    /* --- Sections --- */
    .bd-section {
        padding: 30px 0;
        border-bottom: 1px solid #f0f0f0;
        animation: fadeIn 0.5s ease;
    }
    .bd-section h3 {
        font-size: 22px;
        font-weight: 600;
        color: #222;
        margin-top: 0;
        margin-bottom: 20px;
    }

    /* --- Manufacturer Card (Refined) --- */
    .manufacturer-card {
        display: flex;
        gap: 25px;
        align-items: flex-start;
        background: #fff;
    }
    .manufacturer-logo-box {
        width: 160px;
        height: 160px;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        background: #fff;
        flex-shrink: 0;
    }
    .manufacturer-logo-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .manufacturer-name-link {
        font-size: 20px;
        font-weight: 600;
        color: #8b3a4f;
        text-decoration: none;
        display: block;
        margin-bottom: 10px;
    }
    .manufacturer-name-link:hover { text-decoration: underline; color: #6d2d3d; }
    .manufacturer-description { font-size: 14px; line-height: 1.6; color: #555; }

    /* --- Product Grid (Other Products) --- */
    #bd-other { margin-top: 20px; }
    
    .product-grid-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }
    
    .bd-product-col {
        padding: 0 10px;
        margin-bottom: 20px;
        display: flex; /* Ensures columns stretch */
    }

    .bd-product-card {
        background: #fff;
        border: 1px solid #eee;
        padding: 15px;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .bd-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        border-color: #ddd;
    }

    .prod-img-box {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        overflow: hidden;
    }
    .prod-img-box img { max-height: 100%; max-width: 100%; object-fit: contain; }

    .prod-title {
        color: #8b3a4f;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
        display: block;
        height: 38px;
        overflow: hidden;
        text-decoration: none;
        line-height: 1.3;
    }
    .prod-desc {
        font-size: 12px;
        color: #777;
        margin-bottom: 15px;
        height: 34px;
        overflow: hidden;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .btn-documentation {
        background-color: #ff8c00;
        color: #fff !important;
        display: block;
        width: 100%;
        padding: 10px 0;
        text-align: center;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        border-radius: 4px;
        text-decoration: none;
        margin-top: auto;
        transition: background 0.2s;
    }
    .btn-documentation:hover { background-color: #e67e00; color: #fff; }

    /* Responsive */
    @media (max-width: 768px) {
        .manufacturer-card { flex-direction: column; align-items: center; text-align: center; }
        .manufacturer-content { width: 100%; }
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>


<ul class="nav nav-tabs bd-tabs">
    <?php if ($hasDescription) { ?> <li><a href="#bd-description" data-toggle="tab">Description</a></li> <?php } ?>
    <?php if ($hasVideo) { ?> <li><a href="#bd-video" data-toggle="tab">Video</a></li> <?php } ?>
    <?php if ($hasUser) { ?> <li><a href="#bd-user" data-toggle="tab">Manufacturer</a></li> <?php } ?>
    <?php if ($hasUser) { ?> <li><a href="#bd-other" data-toggle="tab">Other products</a></li> <?php } ?>
    <li><a href="#bd-qna" data-toggle="tab">Any questions?</a></li>
</ul>


<div class="tab-content">

    <?php if ($hasDescription) { ?>
    <div id="bd-description" class="bd-section tab-pane fade in active">
        <h3>Description</h3>
        <div class="content-body">
            <?php echo nl2br($data['group_desc']); ?>
        </div>

        <?php if (!empty($manufacturerData['tags'])) { ?>
        <div class="tags" style="margin-top: 15px;">
            <?php 
            // Split comma-separated string into array
            $tags_array = explode(',', $manufacturerData['tags']);
            
            foreach ($tags_array as $tag) { 
                $tag = trim($tag);
                if (!empty($tag)) { 
                    $tag_safe = htmlspecialchars($tag);
                    $tag_url  = urlencode($tag);
                    ?>
                    <a class="btn btn-default" href="/products?q=<?php echo $tag_url; ?>" title="<?php echo $tag_safe; ?> Produit" style="margin-right: 5px; margin-bottom: 5px;">
                        <?php echo $tag_safe; ?>
                    </a>
                <?php } 
            } ?>
        </div>
        <?php } ?>


    </div>
    <?php } ?>

<?php if ($hasVideo) { ?>
<div id="bd-video" class="bd-section tab-pane fade">
    <h3>Video</h3>

    <div class="embed-responsive embed-responsive-16by9"
         style="border-radius:8px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1);">

        <?php
        $video = trim($data['video_link']);
        $youtube_id = '';

        // =========================
        // YOUTUBE HANDLING
        // =========================
        if (!empty($video)) {

            // Remove @ if user pasted it
            $video = ltrim($video, '@');

            // Extract YouTube ID from all formats
            if (preg_match(
                '~(?:youtube\.com/(?:watch\?.*v=|embed/|shorts/)|youtu\.be/)([a-zA-Z0-9_-]{11})~',
                $video,
                $matches
            )) {
                $youtube_id = $matches[1];
            }
            // If only ID stored
            elseif (preg_match('/^[a-zA-Z0-9_-]{11}$/', $video)) {
                $youtube_id = $video;
            }
        }

        // =========================
        // OUTPUT
        // =========================
        if ($youtube_id) {
            ?>
            <iframe
                width="100%"
                height="315"
                src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtube_id); ?>?rel=0"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
            <?php
        } elseif (!empty($video)) {
            // Self-hosted MP4 ONLY
            ?>
            <video controls style="width:100%;">
                <source src="<?php echo htmlspecialchars($video); ?>" type="video/mp4">
            </video>
            <?php
        } else {
            echo '<p class="video-error">No video available.</p>';
        }
        ?>

    </div>
</div>
<?php } ?>

    

    <?php if ($hasUser && !empty($manufacturerData['logo'])) { ?>
    <div id="bd-user" class="bd-section tab-pane fade">
        <h3>Manufacturer</h3>
        <div class="manufacturer-card">
            <div class="manufacturer-logo-box">
                <a href="<?php echo $manufacturerData['link']; ?>" target="_blank">
                    <img src="<?php echo $manufacturerData['logo']; ?>" alt="<?php echo htmlspecialchars($manufacturerData['name']); ?>">
                </a>
            </div>
            <div class="manufacturer-content">
                <a href="<?php echo $manufacturerData['link']; ?>" target="_blank" class="manufacturer-name-link">
                    <?php echo $manufacturerData['name']; ?>
                </a>
                <div class="manufacturer-description">
                    <?php echo nl2br(htmlspecialchars($manufacturerData['desc'])); ?>
                    
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if ($hasUser) { ?>
    <div id="bd-other" class="bd-section tab-pane fade">
        <h3>Other products from this manufacturer</h3>
        
        <div class="product-grid-row">
            <?php 
            // Query for other products (Optimized)
            $sql_prods = "
                SELECT group_id, group_name, group_desc, group_filename 
                FROM users_portfolio_groups 
                WHERE user_id = '$user_id' 
                AND data_type = '$data_type' 
                AND group_id != '$current_group_id'
                ORDER BY group_order ASC 
                LIMIT 4
            ";
            $prod_query = mysql_query($sql_prods);

            if (mysql_num_rows($prod_query) > 0) {
                while ($row = mysql_fetch_assoc($prod_query)) {
                    // Fetch Image for this specific product
                    $gid = (int)$row['group_id'];
                    $img_sql = mysql_query("SELECT file FROM users_portfolio WHERE group_id = '$gid' ORDER BY `order` ASC LIMIT 1");
                    $img_row = mysql_fetch_assoc($img_sql);
                    
                    // Fallback image if empty
                    $img_src = !empty($img_row['file']) 
                        ? "/photos/main/" . $img_row['file'] // CHECK YOUR PATH (might be /logos/portfolio/ or /images/)
                        : "https://via.placeholder.com/300x200?text=No+Image";
                    ?>
                    
                    <div class="col-sm-3 col-xs-6 bd-product-col">
                        <div class="bd-product-card">
                            <a href="/<?php echo $row['group_filename']; ?>" class="prod-img-box">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($row['group_name']); ?>">
                            </a>
                            
                            <div class="prod-text">
                                <a href="/<?php echo $row['group_filename']; ?>" class="prod-title">
                                    <?php echo $row['group_name']; ?>
                                </a>
                                <div class="prod-desc">
                                    <?php echo strip_tags($row['group_desc']); ?>
                                </div>
                            </div>

                            <a href="/<?php echo $row['group_filename']; ?>" class="btn-documentation">
                                Documentation
                            </a>
                        </div>
                    </div>

                <?php 
                } 
            } else {
                echo "<p class='col-sm-12 text-muted'>No other products found.</p>";
            }
            ?>
        </div>
    </div>
    <?php } ?>
    
    <div id="bd-qna" class="bd-section tab-pane fade">
        <h3>Have Questions?</h3>
        <div class='row'>
            <div class="col-sm-10 ">
                <textarea name="qna" id="qna" class="form-control qnatext" placeholder="Please enter your question here"></textarea>
            </div>
            <div class="col-sm-2 nopad">
                <button class='btn btn-primary btn-block custom-send'>Send</button>
            </div>
        </div>
        </div>

</div>

<script>

let customSend = document.querySelector('.custom-send');

if (customSend) {
    customSend.addEventListener('click', function () {

        let customContact = document.querySelector('.custom-contact');
        let lead_message  = document.querySelector('[name="lead_message"]');
        let qnatext       = document.querySelector('.qnatext');

        if (customContact && lead_message && qnatext) {
            lead_message.value = qnatext.value;
            customContact.click();
        }
    });
}


jQuery(function($){
    // Handle Tab Clicks
    // $('.bd-tabs a').click(function (e) {
    //     e.preventDefault();
    //     $(this).tab('show'); // Bootstrap Tab Switch
        
    //     // Optional: Smooth scroll to the tab content
    //     var target = $(this).attr('href');
    //     $('html, body').animate({
    //         scrollTop: $(target).offset().top - 150
    //     }, 300);
    // });

    // Auto-active first tab if none selected (Bootstrap usually handles this, but safety check)
    if(!$('.bd-tabs li.active').length){
        $('.bd-tabs li:first').addClass('active');
        $('.tab-content .tab-pane:first').addClass('active in');
    }
});
</script>