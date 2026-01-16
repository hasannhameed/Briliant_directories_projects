
    <?php

        $open_input = $_GET['open_to'] ?? [];
        $meet_input = $_GET['like_to_meet'] ?? [];

        if (is_string($open_input) && !empty($open_input)) {
            
            $open_raw = array_map('trim', explode(',', $open_input));
        } else {
            $open_raw = (array)$open_input;
        }

        if (is_string($meet_input) && !empty($meet_input)) {
            
            $meet_raw = array_map('trim', explode(',', $meet_input));
        } else {
            $meet_raw = (array)$meet_input;
        }

        $open_raw = array_values(array_unique($open_raw));
        $meet_raw = array_values(array_unique($meet_raw));

        
        

    ?>
    
    
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

                    <div id="gw-filter-open" class="gw-filter-body" 

                    <?php  
                       $open_style = (!empty($open_raw)) ? 'display:block;' : 'display:none;';
                    ?>
                    
                    >
                        <?php  $open = isset($open_raw) ? $open_raw : array(); ?>

                        <label class="gw-pill gw-pill-full" style="cursor:pointer; justify-content: flex-start;">
                            
                            <input type="checkbox" name="open_to[]" value="friends"  id='friends'
                            <?php if (in_array('friends', $open_raw )) { echo 'checked';} ?>>
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
                        <?php $meet = isset($meet_raw) ? $meet_raw : array(); ?>

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

    document.addEventListener('DOMContentLoaded', function () {

        document.addEventListener('change', function (e) {
            const name = e.target.name;
           
            if(e.target.checked == false){
               
                handleFilterValueRemoval(e);
            }
            if ( e.target.checked == true ){
             
                buildUrlAndRedirect();
            }
        });

        function handleFilterValueRemoval(e, valuesToRemove = null) {
            const url = new URL(window.location.href);
            const params = url.searchParams;

            const keyMap = {
                'open_to[]': 'open_to',
                'like_to_meet[]': 'like_to_meet'
            };

            let toRemove = [];
            if (Array.isArray(valuesToRemove)) {
                toRemove = valuesToRemove;
            } else if (e && e.target && e.target.value) {
                toRemove = [e.target.value];
            } else {
                return;
            }

            const name = e && e.target ? e.target.name : 'open_to[]'; 
            const urlKey = keyMap[name];

            if (!urlKey || !params.has(urlKey)) return;

            let currentValues = params.get(urlKey)
                .split(',')
                .map(v => decodeURIComponent(v.trim()))
                .filter(Boolean);

            currentValues = currentValues.filter(v => !toRemove.includes(v));

            if (currentValues.length > 0) {
                params.set(urlKey, currentValues.join(','));
            } else {
                params.delete(urlKey);
            }

            const newQuery = params.toString();
            const finalUrl = newQuery
                ? `${url.pathname}?${newQuery}`
                : url.pathname;

            window.location.href = finalUrl;
        }

        function buildUrlAndRedirect() {
            const params = new URLSearchParams(window.location.search);

            function updateFilter(urlKey, checkboxName) {
                const checkedBoxes = document.querySelectorAll(`input[name="${checkboxName}"]:checked`);
                
                const uniqueValues = new Set();
                checkedBoxes.forEach(cb => {
                    uniqueValues.add(cb.value);
                });

                const finalArray = Array.from(uniqueValues);

                if (finalArray.length > 0) {
                    params.set(urlKey, finalArray.join(','));
                } else {
                    params.delete(urlKey);
                }
            }

            updateFilter('open_to', 'open_to[]');
            updateFilter('like_to_meet', 'like_to_meet[]');
            updateFilter('tid', 'tid[]');

            const queryString = params.toString();
            const finalUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;

            window.location.href = finalUrl;
        }

    });

</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
            
        const checkClick = () => {
        
            var headers = document.querySelectorAll('.gw-filter-header');

            headers.forEach(function (header) {
                header.addEventListener('click', function () {
                    
                    var section = header.closest('.gw-filter-section');
                    if (!section) return;

                    
                    var body = section.querySelector('.gw-filter-body');
                    var icon = header.querySelector('.gw-filter-toggle-icon');
                    if (!body) return;

                    var isOpen = body.style.display === 'block';

                    if (isOpen) {
                        body.style.display = 'none';
                        if (icon) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-right');
                        }
                    } else {
                        body.style.display = 'block';
                        if (icon) {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });

        }

        checkClick();

        if (window.location.search.indexOf('??') === 0) {
            const clean = '?' + window.location.search.substring(2);
            window.history.replaceState({}, '', clean);
        }

        

    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.gw-filter-section').forEach(function (section) {

            const body  = section.querySelector('.gw-filter-body');
            const icon  = section.querySelector('.gw-filter-toggle-icon');
            const checked = body.querySelectorAll('input[type="checkbox"]:checked');

            if (checked.length > 0) {
                body.style.display = 'block';

                if (icon) {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-down');
                }
            }
        });

    });

</script>
