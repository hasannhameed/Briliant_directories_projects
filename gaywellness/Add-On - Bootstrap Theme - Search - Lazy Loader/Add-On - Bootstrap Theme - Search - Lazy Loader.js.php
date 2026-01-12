<script>
    const listView = document.getElementsByClassName('listView')[0];
    const gridView = document.getElementsByClassName('gridView')[0];
    const queryString = window.location.search;
    const profId = `<?php echo $profession['profession_id']; ?>`;
    const servId =<?php echo json_encode($service); ?>;
    const countryId = `<?php echo $country['country_code']; ?>`;
    const stateId = `<?php echo $state['state_sn']; ?>`;
    const cityId = `<?php echo $city['city_ln']; ?>`;
    const profs = <?php echo json_encode($profs);?>;
    const btnPositionInside = document.querySelector('.grid-container .clickToLoadMoreContainer');
    const btnPositionOutside = document.querySelector('.grid-container ~ .clickToLoadMoreContainer');
    const amountToSum = <?php echo str_replace(array('.', ','), '' , $_ENV['end']);?>;
    let currentAmountText = document.querySelector('.current__amount__js');
    let totalAmount = document.querySelector('.total__js');
    let btnClickMore = document.getElementsByClassName('clickToLoadMoreBtn')[0];
    let btnClickMoreContainer = document.getElementsByClassName('clickToLoadMoreContainer')[0];
    let flagFirstTime = true;
    let autoLoadResults = true;
    let lastElementPost ;
    if(typeof totalAmount !== "undefined" && totalAmount !== null){
        let removeCommasAndDots = /[.,\s]/g;
        totalAmount = totalAmount.innerHTML.replace(removeCommasAndDots, '');
    }
    /*event listener*/
    if (typeof btnClickMore !== "undefined") {
        btnClickMore.addEventListener('click',  function (event) {
            let viewIcons = document.querySelectorAll('.views i.gridView.activeView')[0];
            let featureDCId = this.dataset.dc;
            let currentPage = this.dataset.page;
            let dataType = this.dataset.type;
            let levId = this.dataset.lvl;
            this.dataset.page = parseInt(currentPage) + 1;
            let url = `/wapi/widget`;
            let currentBTN = this;
            let currentHTML = this.innerHTML;
            lastElementPost = document.querySelectorAll('.search_result');
            this.classList.add('loadingMore');
            this.innerHTML = `<i class="fa fa-spinner fa-spin"></i> <?php echo $label['lazy_load_loading']?>`;
            let formData = new FormData();
            autoLoadResults=false;
            setClassLoaded();

            formData.append('dc_id', featureDCId);
            formData.append('header_type', 'html');
            formData.append('request_type', 'POST');
            formData.append('currentPage', currentPage);
            formData.append('dataType', dataType);
            formData.append('queryString', queryString);
            formData.append('profId', profId);
            formData.append('servId', JSON.stringify(servId));
            formData.append('countryId', countryId);
            formData.append('stateId', stateId);
            formData.append('cityId', cityId);
            formData.append('levId', levId);
            formData.append('profsPost', JSON.stringify(profs));
            formData.append('widget_name', 'Add-On - Bootstrap Theme - Search - Lazy Loader');
            let myInit = {
                method: 'POST',
                body: formData
            };
            fetch(url, myInit)
                .then(function (response) {
                    return response.text();
                }).then(function (html) {
                let parser = new DOMParser();

                // Parse the text
                let result = parser.parseFromString(html, "text/html");
                let newResultsHTML = result.getElementById('grabHTML__js').innerHTML;
                autoLoadResults =true;
                if (typeof viewIcons !== 'undefined') {
                    newResultsHTML = newResultsHTML.replaceAll("grid_element", "grid_element hide");
                }
                let amountOfClicks = result.getElementById('grabHTML__js').dataset.pages;

                if (typeof btnPositionOutside !== "undefined" && btnPositionOutside !== null) {
                    $('.grid-container').append(newResultsHTML);
                }
                if (typeof btnPositionInside !== "undefined" && btnPositionInside !== null || btnPositionOutside === null && dataType == 13) {
                    $('.clickToLoadMoreContainer').before(newResultsHTML);
                }

                currentBTN.innerHTML = currentHTML; // default html
                currentBTN.classList.remove('loadingMore');

                if (typeof viewIcons !== 'undefined') {

                    listView.click();
                    gridView.click();
                    $(".grid_element.hide").removeClass("hide");
                    <?php if($wa['grid_view_layout'] != "1"){ ?>
                    
                    <?php } ?>
                    checkAllImagesLoaded();

                }
                if (typeof favoriteOn == 'function') {
                    favoriteOn();
                }
                if (typeof runRoyalSlider == 'function') {
                    runRoyalSlider();
                }

                setAmountPages(currentBTN, amountOfClicks);
                if (typeof currentAmountText !== "undefined" && currentAmountText !== null) {
                    let newAmount = parseInt(currentAmountText.innerHTML) + parseInt(amountToSum);
                    if (newAmount >= parseInt(totalAmount)) {
                        newAmount = document.querySelector('.total__js').innerHTML;
                    }
                    currentAmountText.innerHTML = newAmount.toString()
                }
                if (typeof google != "undefined" && google.hasOwnProperty('maps')){
                    if (typeof initializeMapSR == 'function') {
                        initializeMapSR();
                    }else{
                        $('.mapView.activeView').click();
                    }
                }
            }).catch((error) => {
                console.error('Error:', error);
            });
        });
        // Check if insta_load_btn_behavior is set
        <?php if($w['insta_load_btn_behavior'] == 1 || $w['insta_load_btn_behavior'] == 2 && checkIfMobile(true)){ ?>
        // Add a scroll event listener to the window
        window.addEventListener('scroll', function() {
            userStartedScrolling = true;  // Mark that user has started scrolling
            listenToScroll();
        });
        <?php }?>

    }
    if (typeof gridView !== "undefined" && typeof btnClickMoreContainer !== "undefined") {
        gridView.addEventListener('click', function () {
            gridView.classList.add('activeView');
            if (gridView.classList.contains('activeView')) {
                btnClickMoreContainer.classList.add('gridBtnLazyLoad');
            } else {
                btnClickMoreContainer.classList.remove('gridBtnLazyLoad');
            }
        });
    }
    if (typeof listView !== "undefined" && typeof btnClickMoreContainer !== "undefined") {

        listView.addEventListener('click', function () {
            listView.classList.add('activeView');
            if (listView.classList.contains('activeView')) {
                btnClickMoreContainer.classList.remove('gridBtnLazyLoad');
            } else {
                btnClickMoreContainer.classList.add('gridBtnLazyLoad');
            }
        });
    }

    function setAmountPages(btn, amount) {
        if (!("pages" in btn.dataset)) {
            btn.dataset.pages = parseInt(amount) - 1; // we do -1 because the one just clicked
        } else {
            btn.dataset.pages = parseInt(btn.dataset.pages) - 1;
        }
        if (btn.dataset.pages <= 0) {
            removeListenToScroll();
            btn.remove();
        }
    }

    function setClassLoaded() {
        if (flagFirstTime) {
            flagFirstTime = false;
            let images = document.querySelectorAll('.grid-container img:not(.loaded)');
            images.forEach(image => {
                image.classList.add("loaded");
            });
        }
    }
    function refreshUI(){

        $('.grid-container').masonry("layout");
        if (typeof lastElementPost !== 'undefined' && lastElementPost != null) {
            let pos = lastElementPost.length - 1;
            <?php if($w['insta_load_btn_behavior']!=1 || $dc["data_type"] == 10){ ?>
            setTimeout(()=>{
					console.log(lastElementPost[pos]);
					
                lastElementPost[pos].scrollIntoView();
            },20)
            <?php } ?>

        }
    }
    function checkAllImagesLoaded() {
        let images = document.querySelectorAll('.grid-container img:not(.loaded)');
        let countImg = 0;

        images.forEach(image => {
            image.classList.add("loaded");

            image.addEventListener('load', () => {
                countImg++;
                if (countImg === images.length) {
                    refreshUI()
                }
            });
            image.addEventListener('error', () => {
                countImg++;
                if (countImg === images.length) {
                    refreshUI()
                }
            });
        });

    }

    // Variables
    let userStartedScrolling = false;  // Flag to check if the user has started scrolling
    let wasPreviouslyInViewport = false;  // To track if the button was in the viewport previously


    // Function to remove the scroll event listener
    function removeListenToScroll(){
        window.removeEventListener('scroll', listenToScroll);
    }

    // Function to check button visibility and load content
    function listenToScroll() {
        const isInViewport = isElementInViewport(btnClickMore);

        // Check if the button has just entered the viewport
        const justEnteredViewport = !wasPreviouslyInViewport && isInViewport;

        // If button just entered the viewport, user has started scrolling, and autoLoadResults is true
        if (userStartedScrolling && justEnteredViewport && autoLoadResults) {
            // Add a delay (2 seconds) before automatically clicking the button to load content
            setTimeout(function() {
                btnClickMore.click();
                autoLoadResults = false; // Set the flag too false to avoid subsequent auto-clicks
            }, 2000);
        }

        // If the button is out of the viewport, reset autoLoadResults
        if (!isInViewport) {
            autoLoadResults = true;
        }

        // Update the previous viewport status for the next check
        wasPreviouslyInViewport = isInViewport;
    }

    // Function to determine if a DOM element is in the viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

</script>