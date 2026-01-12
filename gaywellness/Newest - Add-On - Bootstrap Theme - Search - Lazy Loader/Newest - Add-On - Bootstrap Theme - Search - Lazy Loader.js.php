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
            this.classList.add('loadingMore');
            this.innerHTML = `<i class="fa fa-spinner fa-spin"></i> <?php echo $label['lazy_load_loading']?>`;
            let formData = new FormData();
            let lastElementPost = document.querySelectorAll('.search_result');

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
                    setTimeout(function () {
                        listView.click();
                        gridView.click();
                        $(".grid_element.hide").removeClass("hide");
                        <?php if($wa['grid_view_layout'] != "1"){ ?>
                        $('.grid-container').masonry({
                            // options
                            itemSelector: '.search_result',
                            columnWidth: '.search_result'
                        });
                        <?php } ?>
                    }, 800)
                }
                if (typeof favoriteOn == 'function') {
                    favoriteOn();
                }
                if (typeof runRoyalSlider == 'function') {
                    runRoyalSlider();
                }
                setTimeout(function (){

                    if (typeof lastElementPost !== 'undefined' && lastElementPost != null) {
                        let pos = lastElementPost.length - 1;

                        lastElementPost[pos].scrollIntoView({block: "center", behavior: "auto"});
                    }
                    setAmountPages(currentBTN, amountOfClicks);
                    if (typeof currentAmountText !== "undefined" && currentAmountText !== null) {
                        let newAmount = parseInt(currentAmountText.innerHTML) + parseInt(amountToSum);
                        if (newAmount >= parseInt(totalAmount)) {
                            newAmount = document.querySelector('.total__js').innerHTML;
                        }
                        currentAmountText.innerHTML = newAmount.toString()
                    }
                }, 1000);
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
            btn.dataset.pages = parseInt(amount) - 1; // we do -1 because the just clicked
        } else {
            btn.dataset.pages = parseInt(btn.dataset.pages) - 1;
        }
        if (btn.dataset.pages <= 0) {
            btn.remove();
        }
    }
</script>