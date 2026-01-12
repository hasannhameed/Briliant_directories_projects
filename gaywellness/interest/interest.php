
<?php if(false){ ?>

<style>
    /* Container Styling */
    .gw-interests-body {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        background: #fff;
        font-family: Arial, sans-serif;
    }

    /* Header (The Clickable Category) */
    .gw-interest-group-header {
        padding: 15px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    .gw-interest-group-header:hover {
        background-color: #f1f1f1;
    }
    .gw-interest-group-title {
        font-weight: 600;
        color: #333;
    }
    .no-copy {
        -webkit-user-select: none; /* Safari */
        -ms-user-select: none; /* IE 10 and IE 11 */
        user-select: none; /* Standard syntax */
    }

    /* The Dropdown Body */
    .gw-interest-group-body {
        padding: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        background: #fff;
        border-bottom: 1px solid #eee;
    }
    .gw-interest-group-body span{
        padding-left: 10px;
        font-weight: 100;
    }

    /* --- CHECKBOX PILL STYLING --- */
    .gw-pill-checkbox {
        cursor: pointer;
        user-select: none;
    }

    /* Hide the actual browser checkbox input */
    .gw-pill-checkbox input[type="checkbox"] {
        display: none;
    }

    /* The visual part of the button */
    .gw-pill-checkbox span {
        display: inline-block;
        padding: 8px 16px;
        border: 1px solid #ccc;
        border-radius: 20px; /* Pill shape */
        color: #555;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #fff;
    }

    /* Hover State */
    .gw-pill-checkbox span:hover {
        border-color: #888;
        background: #fdfdfd;
    }

    /* CHECKED STATE (Active) - This is the "Selected" look */
    .gw-pill-checkbox input[type="checkbox"]:checked + span {
        background-color: #007bff; /* Brilliant Directories Blue */
        color: white;
        border-color: #007bff;
        box-shadow: 0 2px 5px rgba(0,123,255,0.2);
    }
</style>


<div id="gw-interests-body" class="gw-interests-body row">
    
    <h4 class="h4 line-height-lg bold alert bg-secondary vpad img-rounded nomargin no-radius-bottom select-sub-categories-title">
        Their Interests <input class="form-control category_keyword_search" placeholder="Search By Keyword">
    </h4>

    <?php
    $sql = "SELECT * FROM `list_interests` ORDER BY `top_category` ASC, `name` ASC";
    $query = mysql_query($sql);

    $grouped_interests = array();
    
    while ($row = mysql_fetch_assoc($query)) {
        $cat = $row['top_category'];
        // Initialize the array key if it doesn't exist yet
        if (!isset($grouped_interests[$cat])) {
            $grouped_interests[$cat] = array();
        }
        $grouped_interests[$cat][] = $row;
    }

    // 3. LOOP THROUGH CATEGORIES AND RENDER HTML
    foreach ($grouped_interests as $category_name => $sub_items) { ?>
        <div class="gw-interest-group col-sm-4">
            <div class="gw-interest-group-header no-copy">
                <span class="gw-interest-group-title"><?php echo $category_name; ?></span>
                <span class="gw-interest-group-toggle"><i class="fa fa-plus"></i></span>
            </div>

            <div class="gw-interest-group-body" style="display: none;">
                <?php 
                foreach ($sub_items as $item) { 
                    $val = $item['value'];
                    $name = $item['name'];
                ?>
                    <label class="gw-list-checkbox col-sm-12 nopad">
                        <input type="checkbox" name="interests[]" value="<?php echo $val; ?>">
                        <span><?php echo $name; ?></span>
                    </label>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="gw-interest-group col-sm-12">
        <p id="bottomText" class="bg-default fpad img-rounded vmargin bold">
            You Are Listed In <span class="counter">0</span> Interests
        </p>
        <div class="checked_subs_container2">
            </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Select all headers
        let headers = document.querySelectorAll('.gw-interest-group-header');

        headers.forEach(header => {
            header.addEventListener('click', function() {
                // Find the body associated with this header
                let body = this.nextElementSibling;
                let icon = this.querySelector('.fa');

                // Toggle visibility
                if (body.style.display === "none") {
                    body.style.display = "flex"; // Show it
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus'); // Change icon to minus
                } else {
                    body.style.display = "none"; // Hide it
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus'); // Change icon to plus
                }
            });
        });
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    
document.addEventListener("DOMContentLoaded", function() {
    
    const API_URL = 'https://gaywellness.com/api/widget/json/post/interest_backend';
    
    let container    = document.getElementById('gw-interests-body');
    let checked_subs = document.querySelector('.checked_subs_container2');
    let debounceTimer; 

    loadCheckedValues();

    if (container) container.addEventListener('change', counterCheck);
    if (checked_subs) checked_subs.addEventListener('click', counterCheck);

    function counterCheck(event) {
        if (event.target.type === 'checkbox' && event.target.name === 'interests[]') {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                syncChanges();
            }, 500);

        } else if (event.target.closest('.remove-sub')) {
            let valueToRemove = event.target.closest('.remove-sub').getAttribute('data-value');
            let checkbox = document.querySelector(`input[name="interests[]"][value="${valueToRemove}"]`);
            
            if (checkbox) {
                checkbox.checked = false;
                
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    syncChanges();
                }, 500);
            }
        }
    }

    function syncChanges() {
        let allChecked = document.querySelectorAll('input[name="interests[]"]:checked');
        let selectedValues = Array.from(allChecked).map(checkbox => checkbox.value);

        console.clear(); 
        console.log("Total Selected:", selectedValues.length);
        console.log("Selected Values:", selectedValues);

        saveSelectedValues(selectedValues);
        updateLabels(selectedValues);
        updateCounter(selectedValues.length);
    }

    async function loadCheckedValues() {
        try {
            const userId = '<?php echo isset($_COOKIE["userid"]) ? $_COOKIE["userid"] : ""; ?>';
            if (!userId) {
                console.log("No User ID found in cookies.");
                return;
            }

            const response = await axios.get(API_URL + '?user_id=' + userId);
            let savedItems = response.data.items || response.data || [];

            if (typeof savedItems === 'string') {
                savedItems = JSON.parse(savedItems);
            }

            let allcheckboxes = document.querySelectorAll("input[type='checkbox']");
            allcheckboxes.forEach(box => {
                if (savedItems.includes(box.value)) {
                    box.checked = true;
                }
            });

            updateCounter(savedItems.length);
            updateLabels(savedItems);

        } catch (error) {
            console.error("Error loading data from server:", error);
        }
    }

    async function saveSelectedValues(values) {
        try {
            await axios.post(API_URL, { 
                items: values,
                user_id : '<?php echo $_COOKIE['userid']; ?>'
            });
            console.log("Saved successfully to server.");
        } catch (error) {
            console.error("Error saving data to server:", error);
        }
    }

    function updateLabels(savedItems) {
        let html = '';
        savedItems.forEach(value => {
            html += `<span class="btn btn-default btn-xs bold rmargin bmargin checkedSub">
                        ${value} 
                        <i class="fa fa-times text-danger remove-sub" data-value="${value}" style="cursor:pointer; margin-left:5px; color:white;" aria-hidden="true"></i>
                    </span>`;
        });
        
        if (checked_subs) {
            checked_subs.innerHTML = html;
        }
    }

    function updateCounter(count) {
        let counter = document.querySelector(".counter");
        if (counter) {
            counter.innerText = `${count}`;
        }
    }
});

</script>
<?php } ?>