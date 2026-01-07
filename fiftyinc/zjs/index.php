<div class="module custom-sidebar-search-filters1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <form action="?" method="get" id="gw-filter-form" class='gw-filter-form'>

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
                        <input type="checkbox" name="open_to[]" value="friends" <?php if (in_array('friends', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Friends
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="dates" <?php if (in_array('dates', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Dates
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="network_social" <?php if (in_array('network_social', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Network / Social
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="open_to[]" value="fun_more" <?php if (in_array('fun_more', $open)) echo 'checked'; ?>>
                        &nbsp;&nbsp;Fun &amp; More
                    </label>
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
                        <input type="checkbox" name="like_to_meet[]" value="for_a_walk_or_hike" <?php if (in_array('for_a_walk_or_hike', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For a walk or hike
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_coffee_or_drinks" <?php if (in_array('for_coffee_or_drinks', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For coffee or drinks
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_exercisesports" <?php if (in_array('for_exercisesports', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For exercise/sports
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_a_massage_exchange" <?php if (in_array('for_a_massage_exchange', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For a massage exchange
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_a_hike_or_bike_ride" <?php if (in_array('for_a_hike_or_bike_ride', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For a hike or bike ride
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_a_classevent" <?php if (in_array('for_a_classevent', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For a class/event
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_networking" <?php if (in_array('for_networking', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For networking
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_support_or_mentorship" <?php if (in_array('for_support_or_mentorship', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For support or mentorship
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_exploring_the_city" <?php if (in_array('for_exploring_the_city', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For exploring the city
                    </label>
                    <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                        <input type="checkbox" name="like_to_meet[]" value="for_something_else" <?php if (in_array('for_something_else', $meet)) echo 'checked'; ?>>
                        &nbsp;&nbsp;For something else...
                    </label>
                </div>
            </div>

        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    function buildUrlAndRedirect() {
        
        const baseUrl = '?';
        
        const params = new URLSearchParams();

        const sidInput = document.querySelector('input[name="sid"]');
        if (sidInput) {
            params.append('sid', sidInput.value);
        }

        const qInput = document.querySelector('input[name="q"]');
        if (qInput && qInput.value) params.append('q', qInput.value);


        const targetArrays = ['open_to[]', 'like_to_meet[]', 'tid[]'];

        targetArrays.forEach(name => {
            
            const checkedBoxes = document.querySelectorAll(`input[name="${name}"]:checked`);
            
            checkedBoxes.forEach(box => {
                params.append(name, box.value);
            });
        });
        
        const finalUrl = baseUrl + '?' + params.toString();
        
        console.log("Redirecting to:", finalUrl);
        
        window.location.href = finalUrl;
    }

    
    document.addEventListener('change', function(e) {
        const name = e.target.name;

        if (name === 'open_to[]' || name === 'like_to_meet[]' || name === 'tid[]') {
            buildUrlAndRedirect();
        }
    });
});
</script>

<script>

    (function() {

        var mainHeaders = document.querySelectorAll('.gw-interests-header');

        mainHeaders.forEach(function(header) {

            var container = header.closest('.gw-interests');
            if (!container) return;

            var mainBody = container.querySelector('.gw-interests-body');
            var arrowWrap = container.querySelector('.gw-interests-arrow i');


            if (mainBody) {
                mainBody.style.display = 'none';
            }
            if (arrowWrap) {
                arrowWrap.className = 'fa fa-chevron-right';
            }

            header.addEventListener('click', function() {
                if (!mainBody) return;
                var hidden = (mainBody.style.display === 'none');
                mainBody.style.display = hidden ? 'block' : 'none';

                if (arrowWrap) {
                    arrowWrap.className = hidden ?
                        'fa fa-chevron-down' :
                        'fa fa-chevron-right';
                }
            });
        });


        var groups = document.querySelectorAll('.gw-interests .gw-interest-group');

        groups.forEach(function(group) {
            var header = group.querySelector('.gw-interest-group-header');
            var body = group.querySelector('.gw-interest-group-body');
            var icon = group.querySelector('.gw-interest-group-toggle i');

            if (!header || !body || !icon) return;


            body.style.display = 'none';
            icon.className = 'fa fa-plus';

            header.addEventListener('click', function(e) {

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


        const urlParams = new URLSearchParams(window.location.search);


        const checkboxes = document.querySelectorAll('input[type="checkbox"]');

        checkboxes.forEach(box => {


            const paramName = box.name;
            const paramValues = urlParams.getAll(paramName);

            if (paramValues.includes(box.value)) {


                box.checked = true;



                const subGroupBody = box.closest('.gw-interest-group-body');
                if (subGroupBody) {
                    subGroupBody.style.display = 'block';


                    const subGroup = box.closest('.gw-interest-group');
                    const subIcon = subGroup.querySelector('.gw-interest-group-toggle i');
                    if (subIcon) {
                        subIcon.className = 'fa fa-minus';
                    }
                }



                const mainBody = box.closest('.gw-filter-body, .gw-interests-body');
                if (mainBody) {
                    mainBody.style.display = 'block';




                    let mainHeader;
                    if (mainBody.id === 'gw-interests-body') {
                        mainHeader = document.getElementById('gw-interests-header');
                    } else {

                        mainHeader = document.querySelector(`h3[data-target="#${mainBody.id}"]`);
                    }

                    if (mainHeader) {
                        const mainIcon = mainHeader.querySelector('.fa-chevron-right, .gw-interests-arrow i');
                        if (mainIcon) {

                            if (mainIcon.id === 'gw-interests-arrow-icon') {
                                mainIcon.className = 'fa fa-chevron-down';
                            } else {

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
