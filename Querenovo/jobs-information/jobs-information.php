<?php
$items = [0 => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"> <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect> <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path> </svg> ', 1 => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"> <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path> <circle cx="8.5" cy="7" r="4"></circle> <line x1="20" y1="8" x2="20" y2="14"></line> <line x1="23" y1="11" x2="17" y2="11"></line> </svg> ', 2 => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handshake w-6 h-6"> <path d="m11 17 2 2a1 1 0 1 0 3-3"></path> <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"></path> <path d="m21 3 1 11h-2"></path> <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"></path> <path d="M3 4h8"></path> </svg> ', 3 => ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"> <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path> <circle cx="9" cy="7" r="4"></circle> <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path> <path d="M16 3.13a4 4 0 0 1 0 5.74"></path> </svg> ',];
$results = [];

$row = mysql_fetch_assoc(mysql_query(" SELECT * FROM data_categories WHERE data_id = 9 "));

$categories = explode(",", $row['feature_categories']);

$colors = ["blue", "green", "purple", "orange"];

$results = [];

$i = 0;


foreach ($categories as $cat) {
    $safe = mysql_real_escape_string(trim($cat));
    $q = mysql_query(" SELECT * FROM data_posts WHERE data_type = {$row['data_type']} AND post_category = '$safe' AND post_status = 1 ");
    $results[] = ["label" => $safe, "color" => $colors[$i] ?? "gray", "svg" => $items[$i] ?? "", "count" => mysql_num_rows($q)];
    $i++;
} 

$category_name = $_GET['category'][0];


?>

<div class="metrics-dashboard container">
    <input type="hidden" name="category[]" id="cardCategory">
    <?php foreach ($results as $item): ?>
        <div class="col-sm-3 nopad category" id="<?php echo $item['label']; ?>" data-category="<?= htmlspecialchars($item['label']); ?>">
            <div class="metric-card">
                <div class="icon-container <?php echo $item['color']; ?>"> <?php echo $item['svg']; ?> </div>
                <div class="count"><?php echo $item['count']; ?></div>
                <div class="label"><?php echo $item['label']; ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<style>
    .metrics-dashboard {
        display: flex;
        justify-content: center;
        gap: 15px;
        padding: 40px 0px;
        /* flex-wrap: wrap; */
    }

    .views {
        display: none !important;
    }

    .metric-card {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 10px 15px;
        background-color: #ffffff;
        border-radius: 12px;
        border: 2px solid #0000001c;
        /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); */
        text-align: center;
        transition: transform 0.2s;
        transition: border-color 0.2s ease-in-out, transform 0.2s;
        min-width: 150px;
    }

    .metric-card:hover {
        border: 2px solid #00000040;
    }

    .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .icon-container svg {
        width: 30px;
        height: 30px;
    }

    .icon-container.blue {
        background-color: #e6f0ff;
    }

    .icon-container.blue svg {
        color: #4c8aff;
    }

    .icon-container.green {
        background-color: #ecfdeb;
    }

    .icon-container.green svg {
        color: #55c45e;
    }

    .icon-container.purple {
        background-color: #f4e9ff;
    }

    .icon-container.purple svg {
        color: #925ceb;
    }

    .icon-container.orange {
        background-color: #fff1e4;
    }

    .icon-container.orange svg {
        color: #ff914c;
    }

    .count {
        font-size: 24px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 5px;
    }

    .label {
        font-size: 14px;
        color: #6a737d;
    }

    @media (max-width:700px) {
        .metrics-dashboard {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 40px 0px;
            flex-wrap: wrap;
        }
    }

.label_active {
    border: 2px solid #dc2626 !important;
    border-radius: 12PX;
    
}
.label_active .metric-card{
    background-color: #e41d2317 !important;
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dashboard = document.querySelector(".metrics-dashboard");
        const form = document.querySelector("form.website-search");
        const catInput = document.getElementById("cardCategory");
        if (!dashboard || !form || !catInput) return;
        dashboard.addEventListener("click", function(e) {
            const card = e.target.closest(".category");
            if (!card) return;
            const category = card.dataset.category;
            if (!category) return;
            catInput.value = category;
            location.href=form.getAttribute("action") + "?category[]=" + encodeURIComponent(category);
        });
    });
</script>
<script>
    let category_name = "<?php echo $category_name; ?>";
    document.addEventListener("DOMContentLoaded", function() {
        if (category_name) {
            let activeCard = document.getElementById(category_name);
            if (activeCard) {
                activeCard.classList.add("label_active");
            }
        }
    });
</script>

[widget=videos-filter-section]