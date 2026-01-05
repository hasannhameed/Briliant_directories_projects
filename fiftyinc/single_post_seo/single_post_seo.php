<?php
// -------------------------------------------------------------------------
// 1. UPDATE LOGIC (Writes to data_posts)
// -------------------------------------------------------------------------
$message = "";

// -------------------------------------------------------------------------
// 1. UPDATE LOGIC (Writes to data_posts AND users_meta)
// -------------------------------------------------------------------------
$message = "";

if (isset($_POST['save_post_seo'])) {
    
    $post_id    = intval($_POST['edit_post_id']);
    $meta_title = mysql_real_escape_string($_POST['meta_title']);
    $meta_desc  = mysql_real_escape_string($_POST['meta_description']);
    $post_tags  = mysql_real_escape_string($_POST['post_tags']); 
    
    // Image Handling
    // $image_path = mysql_real_escape_string($_POST['current_image_path']);

    // if (isset($_FILES['post_image']) && is_uploaded_file($_FILES['post_image']['tmp_name'])) {
    //     $username = brilliantDirectories::getDatabaseConfiguration('website_user');
    //     $password = brilliantDirectories::getDatabaseConfiguration('website_pass');
    //     $host     = brilliantDirectories::getDatabaseConfiguration('ftp_server');

    //     $ftp = ftp_connect($host);
    //     if ($ftp && ftp_login($ftp, $username, $password)) {
    //         ftp_pasv($ftp, true);
    //         $filename   = time() . "_" . basename($_FILES['post_image']['name']);
    //         $remote_path = "/public_html/uploads/news-pictures-thumbnails/" . $filename;

    //         if (ftp_put($ftp, $remote_path, $_FILES['post_image']['tmp_name'], FTP_BINARY)) {
    //             $fb_image_path = "/uploads/news-pictures-thumbnails/" . $filename;
    //         }
    //         ftp_close($ftp);
    //     }
    // }

    // A. UPDATE data_posts (for image and tags)
    $update_posts_sql = "
        UPDATE `data_posts` 
        SET 
            `post_tags` = '$post_tags',
        WHERE `post_id` = $post_id 
    ";

    mysql_query($update_posts_sql);

    // B. UPDATE users_meta (for SEO Title)
    $check_title = mysql_query("SELECT meta_id FROM users_meta WHERE database_id = $post_id AND `key` = 'post_meta_title'");
    if (mysql_num_rows($check_title) > 0) {
        $meta_sql_1 = "UPDATE users_meta SET `value` = '$meta_title' WHERE database_id = $post_id AND `key` = 'post_meta_title'";
    } else {
        // Added backticks around `database`, `key`, and `value`
        $meta_sql_1 = "INSERT INTO users_meta (`database`, `database_id`, `key`, `value`, `date_added`) 
                    VALUES ('data_posts', $post_id, 'post_meta_title', '$meta_title', NOW())";
    }
    mysql_query($meta_sql_1);

    // C. UPDATE users_meta (for SEO Description)
    $check_desc = mysql_query("SELECT meta_id FROM users_meta WHERE database_id = $post_id AND `key` = 'post_meta_description'");
    if (mysql_num_rows($check_desc) > 0) {
        $meta_sql_2 = "UPDATE users_meta SET `value` = '$meta_desc' WHERE database_id = $post_id AND `key` = 'post_meta_description'";
    } else {
        // Added backticks around `database`, `key`, and `value`
        $meta_sql_2 = "INSERT INTO users_meta (`database`, `database_id`, `key`, `value`, `date_added`) 
                    VALUES ('data_posts', $post_id, 'post_meta_description', '$meta_desc', NOW())";
    }
}

// -------------------------------------------------------------------------
// 2. FETCH DATA (Joined with users_meta)
// -------------------------------------------------------------------------
$_GET['data_id'] = isset($_GET['data_id']) ? intval($_GET['data_id']) : 0;
$data_id = $_GET['data_id'];

// We join data_posts with users_meta to get the SEO title and description directly
$query = "
    SELECT 
        p.*, 
        m1.value AS seo_title, 
        m2.value AS seo_description
    FROM `data_posts` p
    LEFT JOIN `users_meta` m1 ON (p.data_id = m1.database_id AND m1.`key` = 'post_meta_title')
    LEFT JOIN `users_meta` m2 ON (p.data_id = m2.database_id AND m2.`key` = 'post_meta_description')
    WHERE p.data_id = $data_id
    ORDER BY p.data_id DESC
";

$result = mysql_query($query);

?>

<?php

// ... [Keep your PHP logic exactly the same at the top] ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post SEO Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8f9fa; 
            color: #333;
        }
        .main-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: #fff;
            padding: 20px;
            margin-top: 30px;
        }
        .page-header {
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        .table thead {
            background-color: #f1f4f8;
        }
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border: none;
        }
        .img-preview-table { 
            width: 50px; 
            height: 50px; 
            object-fit: cover; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .img-preview-modal { 
            width: 100%; 
            border-radius: 8px; 
            border: 1px solid #ddd;
        }
        .btn-edit {
            background-color: #eef2ff;
            color: #4f46e5;
            border: none;
            transition: all 0.2s;
        }
        .btn-edit:hover {
            background-color: #4f46e5;
            color: #fff;
        }
        .badge-cat {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: 500;
        }
        /* SEO Character Counter Style */
        .char-counter {
            font-size: 0.75rem;
            float: right;
            margin-top: 5px;
            color: #666;
        }
    </style>

</head>
<body>

<div class="container pb-5">
    <div class="main-card">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="fw-bold m-0">Post SEO Manager</h3>
            <span class="text-muted small">Update metadata and thumbnails</span>
        </div>

        <?php echo $message; ?>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Post Details</th>
                        <th>SEO Title</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result && mysql_num_rows($result) > 0): ?>
                        <?php while ($row = mysql_fetch_assoc($result)): 
                            $id = $row['post_id'];
                            $img_src = !empty($row['post_image']) ? 'https://fiftyinc.com/'.$row['post_image'] : '';
                            $display_img = !empty($img_src) ? $img_src : 'https://placehold.jp/24/cccccc/ffffff/150x150.png?text=No%20Image';
                        ?>
                        <tr>
                            <td class="text-muted">#<?php echo $id; ?></td>
                            <td><img src="<?php echo $display_img; ?>" class="img-preview-table"></td>
                            <td>
                                <div class="fw-semibold"><?php echo $row['post_title']; ?></div>
                                <span class="badge badge-cat"><?php echo $row['category_name']; ?></span>
                            </td>

                            <td class="text-muted small">
                                <?php
                                    $post_id = (int)$row['post_id'];

                                    $meta = array(
                                        'title' => '',
                                        'description' => '',
                                        'tags' => '',
                                        'image' => ''
                                    );

                                    if ($post_id) {
                                        $qr = mysql_query("
                                            SELECT `key`, `value`
                                            FROM users_meta
                                            WHERE database_id = '{$post_id}'
                                            AND `key` IN (
                                                'post_meta_title',
                                                'post_meta_description',
                                                'post_meta_tags',
                                                'post_meta_image'
                                            )
                                        ");

                                        while ($r = mysql_fetch_assoc($qr)) {
                                            switch ($r['key']) {
                                                case 'post_meta_title':
                                                    $meta['title'] = $r['value'];
                                                    break;
                                                case 'post_meta_description':
                                                    $meta['description'] = $r['value'];
                                                    break;
                                                case 'post_meta_tags':
                                                    $meta['tags'] = $r['value'];
                                                    break;
                                                case 'post_meta_image':
                                                    $meta['image'] = $r['value'];
                                                    break;
                                            }
                                        }
                                    }
                                ?>
                                <?php echo !empty($meta['title']) 
                                ? htmlspecialchars($meta['title']) 
                                : '<em class="text-danger">Not Set</em>'; ?>
                            </td>

                            <td class="text-end">
                                <button class="btn btn-edit btn-sm px-3 btn-edit-post"
                                    data-bs-toggle="modal"
                                    data-bs-target="#postEditModal"
                                    data-id="<?php echo $post_id; ?>"
                                    data-mtitle="<?php echo htmlspecialchars($meta['title']); ?>"
                                    data-mdesc="<?php echo htmlspecialchars($meta['description']); ?>"
                                    data-tags="<?php echo htmlspecialchars($meta['tags']); ?>"
                                    data-img="<?php echo htmlspecialchars($meta['image']); ?>"
                                    data-imgsrc="<?php echo $meta['image']; ?>">
                                    Edit SEO
                                </button>
                            </td>


                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="py-4">
                                <div class="alert alert-info mb-0 d-flex align-items-center justify-content-center gap-2">
                                    <strong>No posts found.</strong>
                                    <span class="text-muted">
                                        Upload posts
                                    </span>
                                </div>
                            </td>
                        </tr>


                    <?php endif; ?>
                </tbody>
                
            </table>
        </div>
    </div>
</div>

<div class="modal" id="postEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Edit SEO & Assets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body px-4">
                    <input type="hidden" name="edit_post_id" id="modal_post_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" id="modal_meta_title" maxlength="70">
                        <div class="char-counter"><span id="title_count">0</span>/60 chars (Recommended)</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Meta Description</label>
                        <textarea class="form-control" name="meta_description" id="modal_meta_desc" rows="3" maxlength="160"></textarea>
                        <div class="char-counter"><span id="desc_count">0</span>/160 chars</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Keywords / Tags</label>
                        <input type="text" class="form-control" name="post_tags" id="modal_post_tags" placeholder="tech, marketing, design">
                    </div>

                    <div class="card bg-light border-0 p-3">
                        <!-- <div class="row align-items-center">
                            <div class="col-sm-3">
                                <label class="d-block small fw-bold mb-2 text-uppercase">Current Image</label>
                                <img src="" id="modal_img_preview" class="img-preview-modal">
                            </div>
                            <div class="col-sm-9">
                                <label class="form-label fw-bold small">Upload New Image</label>
                                <input type="hidden" name="current_image_path" id="modal_img_val">
                                <input type="file" class="form-control" name="post_image">
                                <div class="form-text text-muted mt-2">Preferred size: 1200x630px (OG Image)</div>
                            </div>
                        </div> -->
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_post_seo" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    // SEO Counter Logic
    function updateCounters() {
        $('#title_count').text($('#modal_meta_title').val().length);
        $('#desc_count').text($('#modal_meta_desc').val().length);
    }

    $('.btn-edit-post').on('click', function(){
        var id      = $(this).data('id');
        var mtitle  = $(this).data('mtitle');
        var mdesc   = $(this).data('mdesc');
        var tags    = $(this).data('tags');
        var imgVal  = $(this).data('img');
        var imgSrc  = $(this).data('imgsrc');

        $('#modal_post_id').val(id);
        $('#modal_meta_title').val(mtitle);
        $('#modal_meta_desc').val(mdesc);
        $('#modal_post_tags').val(tags);
        $('#modal_img_val').val(imgVal);
        $('#modal_img_preview').attr('src', imgSrc);
        
        updateCounters();
    });

    // Run counters on typing
    $('#modal_meta_title, #modal_meta_desc').on('keyup', updateCounters);

    // Live image preview on file select
    $('input[name="post_image"]').on('change', function () {
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#modal_img_preview')
                    .attr('src', e.target.result)
                    .hide()
                    .fadeIn(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

});
</script>

</body>
</html>