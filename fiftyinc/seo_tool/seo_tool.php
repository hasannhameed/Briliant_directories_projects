<?php
/**
 * HIGH-ATTRACTION TABBED SEO MANAGER
 */

// 1. DATABASE CONNECTIONS
$db = brilliantDirectories::getDatabaseConfiguration("database");

// Fetch POST SEO
$post_query = mysql_query("SELECT * FROM data_categories WHERE data_id NOT IN (1,2,3,4,13,71) ORDER BY data_name ASC");
$data_categories = array();
while ($row = mysql_fetch_assoc($post_query)) { $data_categories[] = $row; }

// Fetch STATIC PAGE SEO
$page_query = mysql_query("SELECT * FROM `list_seo` WHERE seo_type = 'content' ORDER BY section ASC");
$seo_pages = array();
while ($row = mysql_fetch_assoc($page_query)) { $seo_pages[] = $row; }

?>

<style>
    :root {
        --primary: #4f46e5;
        --primary-hover: #4338ca;
        --dark: #0f172a;
        --slate-50: #f8fafc;
        --slate-200: #e2e8f0;
        --slate-600: #475569;
        --emerald: #10b981;
        --indigo-soft: #eef2ff;
    }

    .seo-manager-container {
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        max-width: 1200px;
        color: var(--dark);
    }

    /* --- Modern Tab Navigation --- */
    .seo-tabs-nav {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        padding: 6px;
        background: var(--slate-200);
        border-radius: 12px;
        width: fit-content;
    }

    .seo-tab-btn {
        padding: 10px 24px;
        border: none;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        color: var(--slate-600);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .seo-tab-btn i { font-size: 16px; }

    .seo-tab-btn.active {
        background: white;
        color: var(--primary);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .seo-tab-btn:hover:not(.active) {
        background: rgba(255,255,255,0.5);
        color: var(--dark);
    }

    /* --- Tab Content Area --- */
    .seo-tab-content {
        display: none;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        border: 1px solid var(--slate-200);
        overflow: hidden;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .seo-tab-content.active { display: block; }

    /* --- Header Section --- */
    .table-header {
        padding: 24px 30px;
        background: white;
        border-bottom: 1px solid var(--slate-200);
    }
    
    .table-header h2 { 
        margin: 0; 
        font-size: 1.25rem; 
        font-weight: 800; 
        color: var(--dark);
        letter-spacing: -0.025em;
    }

    /* --- Table Styling --- */
    .table-seo { width: 100%; border-collapse: collapse; margin: 0; }
    
    .table-seo th {
        background-color: var(--slate-50);
        color: var(--slate-600);
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        padding: 14px 30px;
        text-align: left;
    }

    .table-seo td {
        padding: 18px 30px;
        border-bottom: 1px solid var(--slate-50);
        vertical-align: middle;
        font-size: 14px;
        transition: background 0.2s;
    }

    .table-seo tr:hover td {
        background-color: var(--indigo-soft);
    }

    /* --- Buttons & Actions --- */
    .action-btn {
        background-color: white;
        padding: 8px 16px;
        border: 1px solid var(--slate-200);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        color: var(--slate-600);
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--indigo-soft);
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background: white;
        min-width: 190px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 100;
        border-radius: 12px;
        margin-top: 8px;
        border: 1px solid var(--slate-200);
        padding: 6px;
    }

    .action-dropdown.active .dropdown-content { display: block; }

    .dropdown-content a {
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: var(--slate-600);
        border-radius: 6px;
        font-weight: 500;
        transition: 0.2s;
    }

    .dropdown-content a:hover { background-color: var(--indigo-soft); color: var(--primary); }

    /* --- Badges --- */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-blue { background: #e0e7ff; color: #4338ca; }
    .badge-green { background: #dcfce7; color: #15803d; }

    .page-name { font-weight: 700; color: var(--dark); font-size: 15px; margin-bottom: 2px; }
    .page-url { font-size: 12px; color: var(--slate-600); font-family: 'JetBrains Mono', monospace; opacity: 0.8; }
</style>

<div class="seo-manager-container">
    
    <div class="seo-tabs-nav">
        <button class="seo-tab-btn active" onclick="switchSeoTab(event, 'tab-posts')">
            <i class="fa fa-th-large"></i> Post Types
        </button>
        <button class="seo-tab-btn" onclick="switchSeoTab(event, 'tab-pages')">
            <i class="fa fa-file-text"></i> Static Pages
        </button>
    </div>

    <div id="tab-posts" class="seo-tab-content active">
    <div class="table-header">
        <h2>Post Type Settings</h2>
    </div>

    <table class="table-seo">
        <thead>
            <tr>
                <th width="120">Actions</th>
                <th>Post Type Name</th>
                <th>Classification</th>
                <th>Last Sync</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($data_categories as $row): 
                for ($i = 0; $i < 2; $i++):

                    // Context
                    $context = ($i == 0) ? "search_result_page" : "detail_page";

                    // Label for Classification column
                    $suffix  = ($i == 0) ? "Search Results" : "Detailed View";

                    // Widget
                    $widget = ($context == "search_result_page")
                        ? "post_seo_manager_form"
                        : "single_post_seo";

                    // URL
                    $edit_url = "/admin/go.php?widget=" . $widget .
                                "&data_id=" . $row['data_id'] .
                                "&page_context=" . $context;
            ?>

            <tr>
                <td>
                    <div class="action-dropdown">
                        <button type="button" class="action-btn">
                            Manage <i class="fa fa-chevron-down" style="font-size:10px"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="<?php echo $edit_url; ?>">
                                <i class="fa fa-sliders"></i> Edit SEO Rules
                            </a>
                        </div>
                    </div>
                </td>

                <td>
                    <span class="page-name">
                        <?php echo htmlspecialchars($row['data_name']); ?>
                    </span>
                </td>

                <td>
                    <span class="badge badge-blue">
                        <i class="fa fa-tag" style="margin-right:4px"></i>
                        <?php echo $suffix; ?>
                    </span>
                </td>

                <td>
                    <span style="color:var(--slate-600); font-size: 12px;">
                        <?php 
                            $query_str = "
                                SELECT revision_timestamp 
                                FROM list_seo 
                                WHERE database_id = '" . mysql_real_escape_string($row['data_id']) . "' 
                                LIMIT 1
                            ";

                            $result = mysql_query($query_str);

                            if ($result && mysql_num_rows($result) > 0) {
                                $seo_row = mysql_fetch_assoc($result);
                                echo date('M d, Y', strtotime($seo_row['revision_timestamp']));
                            } else {
                                echo "Never Updated";
                            }
                        ?>
                    </span>
                </td>
            </tr>

            <?php endfor; endforeach; ?>
        </tbody>
    </table>
</div>


    <div id="tab-pages" class="seo-tab-content">
        <div class="table-header">
            <h2>Single Page SEO</h2>
        </div>
        <table class="table-seo">
            <thead>
                <tr>
                    <th width="120">Actions</th>
                    <th>Identity</th>
                    <th>Last Sync</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seo_pages as $page): 
                    $edit_url = "/admin/go.php?widget=static_pages_seo&seo_type=content&seo_id=" . $page['seo_id'];
                ?>
                <tr>
                    <td>
                        <div class="action-dropdown">
                            <button type="button" class="action-btn">Manage <i class="fa fa-chevron-down" style="font-size:10px"></i></button>
                            <div class="dropdown-content">
                                <a href="<?php echo $edit_url; ?>"><i class="fa fa-pencil-square-o"></i> Edit Metadata</a>
                                <a href="https://fiftyinc.com/<?php echo $page['filename']; ?>" target="_blank"><i class="fa fa-external-link"></i> Live Preview</a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="page-name"><?php echo $page['nickname'] ? $page['nickname'] : "Unnamed Page"; ?></span>
                        <span class="page-url">/<?php echo $page['filename']; ?></span>
                    </td>
                    <!-- <td><span class="badge badge-green"><?php echo strtoupper($page['section']); ?></span></td> -->
                    <td><span style="color:var(--slate-600); font-size: 12px;"><?php echo date('M d, Y', strtotime($page['revision_timestamp'])); ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>



<script>
    function switchSeoTab(evt, tabId) {
        let tabcontent = document.getElementsByClassName("seo-tab-content");
        for (let i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        let tablinks = document.getElementsByClassName("seo-tab-btn");
        for (let i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabId).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    document.addEventListener('click', function(e) {
        const button = e.target.closest('.action-btn');
        if (button) {
            const currentDropdown = button.closest('.action-dropdown');
            document.querySelectorAll('.action-dropdown').forEach(d => {
                if (d !== currentDropdown) d.classList.remove('active');
            });
            currentDropdown.classList.toggle('active');
        } else {
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('active'));
        }
    });
</script>