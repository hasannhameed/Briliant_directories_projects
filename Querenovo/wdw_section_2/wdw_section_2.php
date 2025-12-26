<?php

$subBtn_sql    =    'SELECT * FROM `list_services` where profession_id = 4 AND master_id != 0';
$subcat_sql2   =    'SELECT * FROM `list_services` where profession_id = 4 AND master_id != 0 LIMIT 9';
$brands_sql3   =    'SELECT * FROM `users_data` where profession_id = 4 LIMIT 20'; 
$data          =    mysql_query($subBtn_sql);
$data2         =    mysql_query($subcat_sql2);
$brands_data   =    mysql_query($brands_sql3);
?>


<div class="container-fluid ta-section-container">
    <div class="container">

        <section class="ta-section">
            <div class="ta-section-header row">
                <h2>Expériences Populaires </h2>
                <p>Découvrez ce que d'autres naviguent comme vous acheter lors du tri des notes des équipements et leur nombre d'abonnements</p>
                <!-- <button class="btn ta-btn-green pull-right">Tout afficher</button> -->
            </div>
            <div class="ta-filter-chips row">
            <?php while($result = mysql_fetch_assoc($data2)) {?>
                <button class="btnn ta-chip-btn <?php echo $result['service_id']; ?>"> <?php echo $result['name']; ?></button>
            <?php } ?>
             <button class="btnn ta-chip-btn view_all"> View All</button>
            </div>
        </section>

       <section class='scroll-section'>
            <p class='text-center header-brand'>MARQUES EN VEDETTE</p>

            <div class="marquee">
                <div class="marquee-content" id="brandsContainer">
                    <?php 
                    while ($brands_fetch = mysql_fetch_assoc($brands_data)) {?>
                    <span class='brand-item'>
                        <div class="brand-item-inner"> 
                            <div class='brand-icon' bis_skin_checked='1'>
                                <span class='text-xl'>📢</span>
                            </div> 
                            <div>
                                <?php echo $brands_fetch['company']; ?>
                            </div>
                        </div>
                    </span>
                   <?php } ?>
                </div>
            </div>

        </section>


    
    <?php 
    $count = 0; 
    while ($category = mysql_fetch_assoc($data)) { 
        ?>
            <section class="ta-section" id="<?php echo htmlspecialchars($category['service_id']); ?>">
                <div>
                <div class="ta-section-header row">     
                    <div class="col-sm-6 col-xs-6 header-div"><h2><?php echo htmlspecialchars($category['name']); ?></h2> &nbsp;<a href="<?php echo htmlspecialchars($category['filename']); ?>"><i class="fa fa-external-link"></i></a></div>
                    <div class="col-sm-6 col-xs-6">
                        <!-- <a class="btn ta-btn-green pull-right" href="<?php echo htmlspecialchars($category['filename']); ?>" target="_blank">Tout afficher</a> -->
                        <div class='scroll-btn'>
                            <p class='left-btn'><i class="fa fa-angle-left" style="font-size:24px"></i></p>
                            <p class='right-btn'><i class="fa fa-angle-right" style="font-size:24px"></i></p>
                        </div>
                    </div>             
                </div>

                <div class="marquee-wrapper">
                    <div class="marquee-track">
                        <div class="row ta-card-grid">
                            <?php
                                $service_id = (int)$category['service_id'];

                                $sql_string = mysql_query(
                                    "SELECT 
                                        up.file,
                                        up.type,
                                        ud.user_id,
                                        ud.profession_id,
                                        ud.company,
                                        ud.first_name,
                                        ud.last_name,
                                        ud.filename,
                                        ls.service_id
                                    FROM users_data AS ud
                                    JOIN list_services AS ls
                                        ON ud.profession_id = ls.profession_id
                                    JOIN users_photo AS up
                                        ON ud.user_id = up.user_id
                                    WHERE ls.service_id = $service_id
                                    LIMIT 20;
                                    "
                                );
                                if (!$sql_string) {
                                    $fallback_sql = "SELECT 
                                        up.file,
                                        up.type,
                                        ud.user_id,
                                        ud.profession_id,
                                        ud.company,
                                        ud.first_name,
                                        ud.last_name,
                                        ud.filename,
                                        ls.service_id
                                    FROM users_data AS ud
                                    JOIN list_services AS ls
                                        ON ud.profession_id = ls.profession_id
                                    JOIN users_photo AS up
                                        ON ud.user_id = up.user_id
                                    WHERE up.type != 'photo' AND ls.service_id = $service_id
                                    LIMIT 20";
                                    
                                    $sql_string = mysql_query($fallback_sql);
                                }
                                while ($listingData = mysql_fetch_assoc($sql_string)) {
                                ?>
                                <div class="col-xs-12 col-sm-3 col-md-3 card" id='<?php echo $listingData['user_id']; ?>'>
                                    <div class="ta-card ta-listing-card">
                                        <img src="https://www.quirenov.fr/logos/profile/<?php echo $listingData['file']; ?>" alt="<?php echo $listingData['company'];?>">
                                        <div class="ta-card-content">
                                            <span class="ta-sponsored-tag"><?php echo $listingData['service_id']?'':''; ?></span>
                                            <h4><a href="#"><?php echo $listingData['company']; ?></a></h4>
                                            <div class="rating-bubbles">
                                                <div class='text-center rating-bubbles-inner'>
                                                    <span class='star'> ★★★★★ </span>
                                                    <span class="review-count">205 avis</span>
                                                </div>
                                            </div>
                                            
                                            <a href='<?php  echo $listingData['filename']; ?>'class="btn ta-btn-green">Voir le profil</a>
                                        </div>
                                    </div>
                                </div>
                            <?php   } ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php $count=$count+1; }  ?>

    </div>
</div> 

<script>
    const marquee = document.querySelector('#brandsContainer');
    marquee.innerHTML += marquee.innerHTML; 

</script>
<style>
.scroll-section{
    background-color: #152d390d;
    border-radius: 10px;
    padding: 20px;
    
}
.brand-icon {
    height: 40px;
    width: 40px;
    background-color: #eeeff0;
    border-radius: 100%;
    font-size: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
   
}
	.active2:hover{
		color:white !important;
	}
.marquee {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
}

.header-brand{
    text-align: center;
    font-size: 12px;
    font-weight: bold;
    color: #0000009e;
}
.brand-item {
    font-size: 10px;
    font-weight: 100;
    padding: 0 5px;
    overflow: hidden;
    width: 150px;
    height: 100px;
    
}

.brand-item-inner:hover{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    background-color: #fff;
    height: 100%;
    width: 100%;
    border: 1px dashed black;
    text-align: center;
    border: 2px dashed #00000029;
    border-radius: 5px;
}

.brand-item-inner{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    background-color: #fff;
    height: 100%;
    width: 100%;
    border: 1px dashed black;
    text-align: center;
    border: 2px dashed #00000029;
    border-radius: 5px;
}


@keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.marquee-content {
    display: inline-flex;
    animation: scroll-left 200s linear infinite;
}


.marquee-content {
    display: inline-flex;
    animation: scroll-left 60s linear infinite; 
}


.ta-section-container {
    padding: 30px 0; 
    
     
    width: 90%;
    margin: 0 auto;
}
.ta-section {
    margin: 80px 0px; 
}

.ta-section-header {
    margin-bottom: 20px;
    
}
.header-div{
    flex-direction: row;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}
.ta-section-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #000;
    margin-top: 0;
    margin-bottom: 5px;
}
.ta-section-header p {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}
.ta-btn-green {
    background-color: #0d1b3e;
    color: #fff;
    border: none;
    padding: 8px 15px;
    font-weight: 600;
    transition: background-color 0.2s ease;
}
.scroll-btn{
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 16px;
    float: right;
}
.scroll-btn p {
    background-color: #e5e7eb;
    border-radius: 100%;
    padding: 7.5px 15px;
    margin: 0px 5px;
}
.scroll-btn p:hover {
    background-color: #d1d5db;
    transform :color 0.8s ease;
}
.ta-card:hover {
    border: 3px solid #e9321a;
    box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.25);
    transition: box-shadow 0.3s ease, border 0.3s ease;
}

.scroll-btn p {
    transition: background-color 0.2s ease;
}

.scroll-btn p:hover {
    background-color: #d1d5db;
}

.ta-section-header .pull-right {
    
    top: 0;
    right: 0;
}

.ta-section-header p + .pull-right {
    top: 30px; 
}
.ta-filter-chips {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    align-items: baseline;
    justify-content: flex-start;
    padding: 30px 0px;
    border-bottom: 1px solid #00000042;
    overflow: scroll;
}
.ta-chip-btn {
    background-color: #fff;
    border: 1px solid #28282826;
    margin-right: 10px;
    margin-bottom: 10px;
    color:  #000000ff;
    font-weight: 500;
}
.ta-chip-btn i {
    margin-right: 5px;
    color: #666; 
}

.ta-card-grid {
    margin-left: -10px; 
    margin-right: -10px;
    display: flex; 
    
}
.ta-card-grid > [class*="col-"] {
    padding-left: 10px; 
    padding-right: 10px;
    margin-bottom: 20px; 
}


.ta-card {
    background-color: #fff;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 3px solid #0000001a;
    transition: border 0.8s ease; 
    width: 270px;
}


.ta-card:hover {
    border: 3px solid #e9321a;
}

.ta-card:hover {
    color: #e9321a;
}

.ta-card img {
    width: 100%;
    height: 180px; 
    object-fit: cover;
    display: block;
    padding: 15px;
    padding-bottom: 0px;
    border-radius: 20px;
}

.ta-card-content {
    padding: 15px;
    flex-grow: 1; 
    display: flex;
    flex-direction: column;
	justify-content: end;
    text-align: center;
}

.heart-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: rgba(0,0,0,0.4);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    z-index: 10;
}
.heart-icon:hover {
    background-color: rgba(0,0,0,0.6);
}



.ta-city-card {
    position: relative; 
}
.ta-city-card h4 {
    font-size: 18px;
    font-weight: 600;
    margin-top: 5px;
    margin-bottom: 2px;
    color: #000;
}
.ta-city-card p {
    font-size: 13px;
    color: #666;
    margin-bottom: 0;
}

.ta-listing-card img {
    height: 150px; 
}

.ta-listing-card h4 {
    font-size: 16px;
    font-weight: 600;
    margin-top: 5px;
    margin-bottom: 5px;
    color: #000;
    line-height: 1.3;
}
.ta-listing-card h4 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.5s ease;
}


.rating-bubbles {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap; 
}
.rating-bubbles-inner{
    display: flex;
    width: 100%;
    justify-content: center;
    align-items: center;
    padding-top: 10px;
}
.star{
    color: #22c55e;
    font-size: 24px;
}
.rating-bubble {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color:#22c55e; 
    margin-right: 3px;
    flex-shrink: 0; 
}
.rating-bubble.empty {
    background-color: #e0e0e0;
}
.review-count {
    margin-left: 8px;
    font-size: 13px;
    color: #555;
    flex-shrink: 0;
}

.ta-sponsored-tag {
    font-size: 12px;
    color: #888;
    margin-bottom: 5px;
    display: block;
}

.ta-price {
    font-size: 15px;
    color: #555;
    margin-top: auto; 
    margin-bottom: 15px;
}
.ta-price span {
    font-weight: bold;
    color: #000;
    font-size: 1.2em; 
}
.active2{
    background-color: black;
    color: white;
}
.ta-listing-card .btn {
    width: 100%; 
    margin-top: 10px; 
}


.ta-card-grid-scroll {
    overflow-x: auto; 
    white-space: nowrap; 
    -webkit-overflow-scrolling: touch; 
    padding-bottom: 15px; 
    position: relative; 
    margin-left: 0; 
    margin-right: 0;
}
.ta-card-grid-scroll .col-xs-12 { 
    flex: 0 0 100%;
    max-width: 100%;
}
.ta-card-grid-scroll .col-sm-3 { 
    flex: 0 0 50%;
    max-width: 50%;
}
.ta-card-grid-scroll .col-md-3 { 
    flex: 0 0 25%;
    max-width: 25%;
}
.ta-card-grid-scroll .ta-scroll-item {
    display: inline-block; 
    float: none; 
    vertical-align: top; 
    white-space: normal; 
}


.ta-card-grid-scroll > [class*="col-"] {
    padding-left: 10px;
    padding-right: 10px;
}
.btnn {
    margin-right: 10px;
    margin-bottom: 10px;
    border: 1px solid black;
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid #28282826;
    border-radius: 5px;
}

.ta-scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #333;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 20;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}



.ta-scroll-left {
    left: -20px; 
}
.ta-scroll-right {
    right: -20px; 
}


.ta-card-grid-scroll::-webkit-scrollbar {
    display: none;
}
.ta-card-grid-scroll {
    -ms-overflow-style: none;  
    scrollbar-width: none;  
}


@media (max-width: 767px) { 
    .ta-section-header .pull-right {
       
        display: block;
        margin-top: 15px; */
       
    }
    .ta-chip-btn {
        
        
    }
    .active2{
        background-color: black;
        color: white;
        outline: 0;
      
    }
    .ta-card-grid-scroll {
        overflow-x: scroll; 
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        padding-bottom: 15px;
    }
    .ta-card-grid-scroll > [class*="col-"] {
        display: inline-block;
        float: none;
        vertical-align: top;
        white-space: normal;
        width: 80%; 
        max-width: 80%;
    }
    .ta-scroll-btn {
        display: none; 
    }
    .header-div{
        flex-direction: row;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        overflow: scroll;
    }
    .marquee-track {
        display: flex;
        width: max-content;
        animation: scrollCards 45s linear infinite;
        animation-play-state: paused; /* stop by default */
        overflow: scroll;
    }
}

@media (min-width: 768px) and (max-width: 991px) { 
    .ta-card-grid-scroll > [class*="col-"] {
        width: 100%;
        max-width: 50%;
    }
}
@media (min-width: 992px) { 
    .ta-card-grid-scroll > [class*="col-"] {
        width: 25%;
        max-width: 25%;
    }
}



/* Slower / Faster speed? adjust 45s above */
@keyframes scrollCards {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}

.marquee-wrapper {
    overflow-x: auto;
    white-space: nowrap;
    scroll-behavior: smooth;
}
.ta-card-grid{
    margin-right: 10px;
}
.marquee-wrapper::-webkit-scrollbar {
    display: none;
}

.marquee-track {
    display: flex;
    width: max-content;
    animation: scrollCards 45s linear infinite;
    animation-play-state: paused; /* stop by default */
}

.marquee-wrapper:hover .marquee-track {
    animation-play-state: running; /* start on hover */
}

@keyframes scrollCards {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}
</style>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const marquees = document.querySelectorAll('.marquee-wrapper');

    marquees.forEach(wrapper => {
        const track = wrapper.querySelector('.marquee-track');
        const leftBtn = wrapper.parentElement.querySelector('.left-btn');
        const rightBtn = wrapper.parentElement.querySelector('.right-btn');

        const step = 300;

        if (rightBtn) {
            rightBtn.addEventListener("click", () => {
                wrapper.scrollBy({ left: step, behavior: "smooth" });
            });
        }

        if (leftBtn) {
            leftBtn.addEventListener("click", () => {
                wrapper.scrollBy({ left: -step, behavior: "smooth" });
            });
        }
    });
});
</script>
