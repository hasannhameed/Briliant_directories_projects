<div class="module">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <form action="" method="get" id="gw-filter-form">

        <div class="gw-filter-sidebar">
            
            <div class="gw-filter-section">
                <h3 class="gw-filter-header" data-target="#gw-filter-open">
                    <span>
                        <i class="fa fa-angle-double-right gw-filter-prefix"></i>
                        Open To
                    </span>
                    <i class="fa fa-chevron-right gw-filter-toggle-icon"></i>
                </h3>

                <div id="gw-filter-open" class="gw-filter-body" style="display:none;">
                    <?php $open = isset($_GET['open_to']) ? $_GET['open_to'] : array(); ?>
                    
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="friends" <?php if(in_array('friends', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Friends
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="dates" <?php if(in_array('dates', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Dates
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="network_social" <?php if(in_array('network_social', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Network / Social
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="fun_more" <?php if(in_array('fun_more', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Fun &amp; More
                    </label>
                </div>
            </div>

            <div class="gw-filter-section">
                <div class="custom-sidebar-search-filters gw-interests">

                    <h3 id="gw-interests-header" class="gw-interests-header">
                        <span>
                            <i class="fa fa-heart-o" aria-hidden="true"></i>&nbsp;&nbsp;Their Interests
                        </span>
                        <span class="gw-interests-arrow">
                            <i id="gw-interests-arrow-icon" class="fa fa-chevron-down" aria-hidden="true"></i>
                        </span>
                    </h3>

                    <div id="gw-interests-body" class="gw-interests-body">
                        
                        <?php
                        // 1. QUERY DB
                        $sql = "SELECT * FROM `list_interests` ORDER BY `top_category` ASC, `name` ASC";
                        $query = mysql_query($sql);

                        // 2. GROUP DATA
                        $grouped_interests = array();
                        while ($row = mysql_fetch_assoc($query)) {
                            $cat = $row['top_category'];
                            if (!isset($grouped_interests[$cat])) {
                                $grouped_interests[$cat] = array();
                            }
                            $grouped_interests[$cat][] = $row;
                        }

                        // Get currently selected interests to keep boxes checked
                        $selected = isset($_GET['interests']) ? $_GET['interests'] : array();

                        // 3. RENDER HTML
                        foreach ($grouped_interests as $category_name => $items) {
                        ?>
                            <div class="gw-interest-group">
                                <div class="gw-interest-group-header">
                                    <span class="gw-interest-group-title"><?php echo $category_name; ?></span>
                                    <span class="gw-interest-group-toggle">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </span>
                                </div>

                                <div class="gw-interest-group-body" style="display: none;">
                                    <?php foreach ($items as $item) { 
                                        $isChecked = in_array($item['value'], $selected) ? 'checked' : '';
                                    ?>
                                        <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                                            <input type="checkbox" name="interests[]" value="<?php echo $item['value']; ?>" <?php echo $isChecked; ?>>
                                            &nbsp;&nbsp;<?php echo $item['name']; ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php 
                        } // End Foreach 
                        ?>

                    </div>
                </div>
            </div>

            <div class="gw-filter-section">
                <h3 class="gw-filter-header" data-target="#gw-filter-meet">
                    <span>
                        <i class="fa fa-comment-o gw-filter-prefix"></i>
                        How They Like to Meet
                    </span>
                    <i class="fa fa-chevron-right gw-filter-toggle-icon"></i>
                </h3>

                <div id="gw-filter-meet" class="gw-filter-body" style="display:none;">
                    <?php $meet = isset($_GET['like_to_meet']) ? $_GET['like_to_meet'] : array(); ?>

                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_a_walk" <?php if(in_array('for_a_walk', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For a walk or hike
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_drinks" <?php if(in_array('for_drinks', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For coffee or drinks
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_a_workout" <?php if(in_array('for_a_workout', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For exercise/sports
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_networking" <?php if(in_array('for_networking', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For networking
                    </label>
                </div>
            </div>

            <div style="padding: 15px 0;">
                <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
            </div>

        </div>
    </form>
</div>
<style>
    .fa, .fas, .far, .fal, .fab, .fad {
    color: #3F7880 !important;
}

/* ===== MAIN "Their Interests" header ===== */
.gw-interests-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    margin-bottom: 6px;
}

.gw-interests-header i {
    color: #0d3f4f;
}

.gw-interests-arrow i {
    color: #3F7880; /* arrow color */
}

/* ===== groups ===== */
.gw-interest-group {
    margin-top: 10px;
}

.gw-interest-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.gw-interest-group-title {
    font-weight: 600;
    color: #0d3f4f;
}

.gw-interest-group-toggle i {
    color: #3F7880;  /* plus / minus color */
}

/* body containing the buttons */
.gw-interest-group-body {
    margin-top: 6px;
}

/* ===== pill buttons (full width, hover, etc.) ===== */
.gw-pill {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    margin: 4px 0 !important;
    border-radius: 8px !important;
    border: 1px solid #0d3f4f;
    background-color: #ffffff;
    text-decoration: none;
    text-align: center;
    height: 36px !important;
    line-height: 1.2;
    padding: 0 12px;
    white-space: nowrap;
    color: #0d3f4f !important;
    font-weight: 400;
}

.gw-pill-full {
    width: 100% !important;
}

.gw-pill:hover {
    background-color: #0d3f4f;
    color: #ffffff !important;
    text-decoration: none;
}

/* Fix H3 so spans don’t shrink the text */
.gw-interests-header span {
    font-size: inherit !important;
    font-weight: inherit !important;
}

/* Default: everything collapsed */
.gw-interests-body {
    display: none;
}
.gw-interest-group-body {
    display: none;
}

</style>
<script>

    let form = document.querySelector('#gw-filter-form');
    if(form){
        form.addEventListener('change',function(){
            form.submit();
        });
    }

(function () {
  // ===== MAIN TOGGLE(S) – "Their Interests" =====
  var mainHeaders = document.querySelectorAll('.gw-interests-header');

  mainHeaders.forEach(function (header) {
    // Find the container for THIS instance
    var container = header.closest('.gw-interests');
    if (!container) return;

    var mainBody  = container.querySelector('.gw-interests-body');
    var arrowWrap = container.querySelector('.gw-interests-arrow i');

    // Start CLOSED
    if (mainBody) {
      mainBody.style.display = 'none';
    }
    if (arrowWrap) {
      arrowWrap.className = 'fa fa-chevron-right';
    }

    header.addEventListener('click', function () {
      if (!mainBody) return;
      var hidden = (mainBody.style.display === 'none');
      mainBody.style.display = hidden ? 'block' : 'none';

      if (arrowWrap) {
        arrowWrap.className = hidden
          ? 'fa fa-chevron-down'
          : 'fa fa-chevron-right';
      }
    });
  });

  // ===== SUB-GROUP TOGGLES (Arts & Culture, Career, etc.) =====
  var groups = document.querySelectorAll('.gw-interests .gw-interest-group');

  groups.forEach(function (group) {
    var header = group.querySelector('.gw-interest-group-header');
    var body   = group.querySelector('.gw-interest-group-body');
    var icon   = group.querySelector('.gw-interest-group-toggle i');

    if (!header || !body || !icon) return;

    // Default CLOSED
    body.style.display = 'none';
    icon.className = 'fa fa-plus';

    header.addEventListener('click', function (e) {
      // Don’t trigger the main "Their Interests" toggle
      e.stopPropagation();

      var hidden = (body.style.display === 'none');
      body.style.display = hidden ? 'block' : 'none';
      icon.className = hidden ? 'fa fa-minus' : 'fa fa-plus';
    });
  });

})();
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Parse URL Parameters
    const urlParams = new URLSearchParams(window.location.search);
    
    // 2. Loop through all checkboxes on the page
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(box => {
        // Check if this box's value exists in the URL
        // We look for the parameter name (e.g., 'interests[]') and the value
        const paramName = box.name; 
        const paramValues = urlParams.getAll(paramName);

        if (paramValues.includes(box.value)) {
            
            // A. Ensure the box is visually checked
            box.checked = true;

            // B. Open the Sub-Group (if it exists)
            // We look for the closest '.gw-interest-group-body' parent
            const subGroupBody = box.closest('.gw-interest-group-body');
            if (subGroupBody) {
                subGroupBody.style.display = 'block';
                
                // Update the Plus/Minus icon
                const subGroup = box.closest('.gw-interest-group');
                const subIcon = subGroup.querySelector('.gw-interest-group-toggle i');
                if (subIcon) {
                    subIcon.className = 'fa fa-minus';
                }
            }

            // C. Open the Main Container (Open To, Interests, Meeting)
            // We look for the closest '.gw-filter-body' or '.gw-interests-body'
            const mainBody = box.closest('.gw-filter-body, .gw-interests-body');
            if (mainBody) {
                mainBody.style.display = 'block';

                // Update the Chevron Icon
                // We need to find the header that controls this body.
                // Usually it's the previous sibling element, or we find it by ID logic.
                let mainHeader;
                if (mainBody.id === 'gw-interests-body') {
                    mainHeader = document.getElementById('gw-interests-header');
                } else {
                    // Find the header that points to this body ID
                    mainHeader = document.querySelector(`h3[data-target="#${mainBody.id}"]`);
                }

                if (mainHeader) {
                    const mainIcon = mainHeader.querySelector('.fa-chevron-right, .gw-interests-arrow i');
                    if (mainIcon) {
                        // If it's the specific arrow icon
                        if(mainIcon.id === 'gw-interests-arrow-icon') {
                            mainIcon.className = 'fa fa-chevron-down';
                        } else {
                            // Standard filter icon
                            mainIcon.classList.remove('fa-chevron-right');
                            mainIcon.classList.add('fa-chevron-down');
                        }
                    }
                }
            }
        }
    });
});
</script>