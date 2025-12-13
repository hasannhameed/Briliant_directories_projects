    <section class="filter-bar-sticky">
        <div class="container-fluid container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="filter-inner-wrapper">
                        
                        <div class="sort-section btndiv1 ">
                            <span class="text-muted sort-label">Trier par :</span>
                            
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle custom-select" type="button" id="sort-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Plus récentes &nbsp;&nbsp;&nbsp;
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" id="sort-dropdown-menu">
                                    <li><a  data-sort="date-desc">Plus récentes</a></li>
                                    <li><a  data-sort="date-asc">Plus anciennes</a></li>
                                    <li><a  data-sort="title-asc">Titre (A-Z)</a></li>
                                    <li><a  data-sort="urgent-desc">Urgentes d'abord</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="search-filter-section">
                            
                            <div class="input-group search-input-group">
                                <span class="input-group-addon search-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                </span>
                                <input type="text" class="form-control" placeholder="Rechercher dans les annonces..." value="">
                            </div>

                            <button class="btn btn-default custom-filter-btn " style='color:#fff'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter filter-icon-margin"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                                Filtres
                            </button>
                            <button class="btn btn-sm btn-default dropdown-toggle custom-select custom-reset-btn" style="display:none;">
                                Reset
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
       
    .filter-bar-sticky {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        border-top: 1px solid #eee;
        padding: 15px 0;
       
        position: sticky;
        top: 0;
        z-index: 30;
        box-shadow: 0 1px 3px rgba(0,0,0,.05); 
    }
    .custom-reset-btn{
            WIDTH: FIT-CONTENT;
    }
   
    .filter-inner-wrapper {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

   
    .sort-section {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sort-label {
        font-size: 14px;
        font-weight: 600;
        color: #555;
    }

   
    .custom-select {
        width: 150px;
        text-align: left;
        background-color: white !important;
    }

   
    .search-filter-section {
        display: flex;
        flex-grow: 1;
        align-items: center;
        justify-content: end;
        gap: 10px;
    }

   
    .search-input-group {
        max-width: 320px;
        flex-grow: 1; 
    }
    .dropdown-menu li a {
        cursor: pointer;
    }
   
    .search-icon-addon {
        background-color: #fff;
        border: 1px solid #ccc;
        border-right: none;
        padding-right: 0;
        color: #999;
    }

    .search-icon-addon svg {
        margin-top: 2px;
    }

   
    .search-input-group .form-control {
        border-left: none; 
        box-shadow: none;
        height: 38px;
    }

   
    .custom-filter-btn {
        border: 1px solid #ccc;
        background-color: #f8f8f8;
    }
    .dropdown-toggle{
        display: flex;
        justify-content: space-around;
        align-items: center;

    }
    .filter-icon-margin {
        margin-right: 5px;
    }
    .custom_active{
        background-color: black !important;
        color: white !important;
    }
  
    .row.filter-active .sidebar-to-hide {
        display: none;
    }
   .col-md-3:has(.website-search) {
        display: none;
    }
    .col-md-9:has(.job-parent) {
        width: 100%;
        left: 0px;
    }
    .col-sm-9-ultimate{
        width: 75% !important;
        left: 25% !important;
    } 

    @media (max-width: 990px) {
        .col-sm-9-ultimate{
            width: 100% !important;
            left: 0% !important;
        } 
    }

    @media (max-width: 767px) {
        .filter-inner-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }
        .sort-section {
            margin-bottom: 10px;
        }
        .search-filter-section {
            width: 100%;
            flex-wrap: wrap; 
        }
        .search-input-group {
            width: 100%;
            max-width: 100%;
        }
        .custom-filter-btn {
            flex-grow: 1;
        }
        
    }
    </style>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const params = new URLSearchParams(window.location.search);
    const resetBtn = document.querySelector(".custom-reset-btn");

    if (!resetBtn) return;

   
    if ([...params.keys()].length > 0) {
        resetBtn.style.display = "inline-block";
    } else {
        resetBtn.style.display = "none";
    }
    resetBtn.addEventListener("click", function () {
        window.location.href = window.location.pathname;
    })
});
</script>

<script>
    document.addEventListener('DOMContentLoaded',function(){
        const state = localStorage.getItem('state'); 

        const btnbtn            = document.querySelector('.custom-filter-btn');
        const website_search = document.querySelector('.website-search');
        const first          = document.querySelector('.job-parent');
        const firstParent    = first.parentElement;
        const parent         = website_search.parentElement.parentElement;

        
        if(state === 'active'){
            btnbtn.classList.add('custom_active');
            parent.classList.remove('hide');
            parent.classList.add('show');
            firstParent.classList.add('col-sm-9-ultimate');
        }

        
        btnbtn.addEventListener('click', function(e){
            if(btnbtn.classList.contains('custom_active')){
                
                parent.classList.add('hide');
                parent.classList.remove('show');
                btnbtn.classList.remove('custom_active');
                firstParent.classList.remove('col-sm-9-ultimate');
                localStorage.setItem('state', 'notactive');
            } else {
                
                btnbtn.classList.add('custom_active');
                parent.classList.remove('hide');
                parent.classList.add('show');
                firstParent.classList.add('col-sm-9-ultimate');
                localStorage.setItem('state', 'active');
            }
        });

        $(document).ready(function () {
            $('.search-input-group input').on('keyup', function () {
                let value = $(this).val().toLowerCase().trim();

                $('.grid-container .search_result').filter(function () {
                    let text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(value) > -1);
                });
            });
        });

    })
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const dropdown_menu = document.querySelector('.dropdown-menu');
    const gridContainer = document.querySelector('.grid-container');

    dropdown_menu.addEventListener('click', function(e) {
        if (e.target.tagName !== "A") return;

        let selectedSort = e.target.dataset.sort.trim().toLowerCase();

        let cards = Array.from(document.querySelectorAll(".search_result"));

        
        function getTitle(card) {
            return card.querySelector(".mid_section a.h3, .mid_section a.h4")?.textContent.trim().toLowerCase() || "";
        }

        function getDate(card) {
            const dateText = card.querySelector(".contact-member span")?.textContent.trim() || "";
            return new Date(dateText.split("/").reverse().join("-")); 
        }

        function isUrgent(card) {
            return card.querySelector(".custom_badge_urgent") ? 1 : 0;
        }

       
        cards.sort((a, b) => {
            switch(selectedSort) {

                case "date-desc":
                    return getDate(b) - getDate(a);

                case "date-asc":
                    return getDate(a) - getDate(b);

                case "title-asc":
                    return getTitle(a).localeCompare(getTitle(b));

                case "urgent-desc":
                    return isUrgent(b) - isUrgent(a);

                default:
                    return 0;
            }
        });

      
        cards.forEach(card => gridContainer.appendChild(card));
    });

});
</script>



