<?php if ($pars[0] == 'videos') { 

// Fetch latest 4 videos (change LIMIT as needed)
$videos_query = mysql_query("SELECT * FROM `data_posts` WHERE data_id = 13  ORDER BY post_id DESC LIMIT 4");

?>

<section class="video-grid-section">
    <div class="video-grid-header">
        <h2 class="section-title">Toutes les vidéos</h2>
        <span class="video-count">
            <?php echo mysql_num_rows($videos_query); ?> vidéos
        </span>
    </div>

    <div class="video-grid-container">
        <?php while($video = mysql_fetch_assoc($videos_query)) { ?>

        <a href="<?php echo $video['post_filename']; ?>" class=" video-card" >

            <div class="video-thumbnail-placeholder">
                <?php if(!empty($video['post_image'])) { ?>
                    <img src="<?php echo $video['post_image']; ?>" style="width:100%; height:100%; object-fit:cover;">
                <?php } ?>
                <div class="video-overlay"></div>
                <div class="video-play-btn"></div>
                <div class="video-duration"> <span class='triangle'>▷</span></div>
            </div>

            <div class="video-info">
                <h3 class="video-title">
                    <?php echo $video['post_title']; ?>
                </h3>

                <p class="video-channel">
                    <?php echo $video['post_author']; ?>
                </p>

                <p class="video-description">
                    <?php echo mb_strimwidth(strip_tags($video['post_content']), 0, 120, "..."); ?>
                </p>

                <div class="video-meta">
                    

                    <div class="video-stats">
                        <span class="video-tag"><?php echo $video['post_category']; ?></span>
                        <span><i class="far fa-calendar-alt"></i>
                            <?php echo date("d/m/Y", strtotime($video['post_live_date'])); ?>
                        </span>
                    </div>
                </div>
            </div>

        </a>

        <?php } ?>
    </div>
</section>

<style>
/* Remove old triangle / duration icon */
.video-duration,
.video-duration .triangle {
    display: none !important;
}

.video-thumbnail-placeholder {
    position: relative;
    overflow: hidden;
}

/* Dark overlay */
.video-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    opacity: 0;
    transition: opacity 0.35s ease;
    z-index: 2;
}

/* Red circular button */
.video-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 70px;
    height: 70px;
    background: #e53935;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0.85);
    opacity: 0;
    transition: all 0.35s ease;
    z-index: 3;
}

/* White play triangle */
.video-play-btn::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-35%, -50%);
    width: 0;
    height: 0;
    border-left: 20px solid #fff;
    border-top: 14px solid transparent;
    border-bottom: 14px solid transparent;
}

/* Hover states */
.video-card:hover .video-overlay {
    opacity: 1;
}

.video-card:hover .video-play-btn {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

/* Image zoom */
.video-thumbnail-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.video-card:hover img {
    transform: scale(1.05);
}


/*---*/
.video-grid-section {
    padding: 40px 20px;
    background-color: #f8f8f8; 
    font-family: Arial, sans-serif;
}


.video-grid-header {
    max-width: 1370px;
    margin: 0 auto 30px auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px; 
}

.video-grid-header .section-title {
    font-size: 1.8em;
    font-weight: bold;
    color: #1a2b47; 
    margin: 0;
}

.video-grid-header .video-count {
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 0.9em;
    color: #555;
}


.video-grid-container {
    max-width: 1370px;
    margin: 0 auto;
    display: grid;
    
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 30px; 
}


.video-card {
    display: flex;
    flex-direction: column;
    border-radius: 8px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); 
    text-decoration: none; 
    color: inherit;
    transition: box-shadow 0.3s ease;
}

.video-card:hover {
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}


.video-thumbnail-placeholder {
    position: relative;
   max-height: 150px;
    background-color: #1a2b47; 
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-thumbnail-placeholder .camera-icon {
    top: 80px;
    position: absolute;
    width: 60px; 
    height: 60px;
    opacity: 0.8;
}

/* .video-duration {
    position: absolute;
    bottom: 65px;
    right: 144px;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 0.8em;
    z-index: 10;
    width: 60px;
    height: 60px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 50px;
    background-image:url('data:image/svg+xml;utf8,<svg width='32' height='32' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><rect x='3.5' y='6.5' width='13' height='11' rx='2' stroke='white' stroke-width='2'/><path d='M16.5 9L20 7V17L16.5 15V9Z' stroke='white' stroke-width='2' stroke-linejoin='round'/></svg>');
} */

.video-duration {
    position: absolute;
    bottom: 60px;
    right: 135px;
    font-size: 0px;
    background-color: #28282800;
    border-radius: 3px;
    padding: 3px;
    width: 50px;
    height: 50px;
    z-index: 10;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 50px;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB4PSIzLjUiIHk9IjYuNSIgd2lkdGg9IjEzIiBoZWlnaHQ9IjExIiByeD0iMiIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTE2LjUgOUwyMCA3VjE3TDE2LjUgMTVWOVoiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==");
}

/* .video-thumbnail-placeholder:hover .video-duration:hover{
    background-color: red;
    background-image: none;
    border-radius: 100%;
    color: #fff;
    font-weight: bolder;
    font-size: 45px;
    display: flex;
    padding: 0px 8px;
    align-items: center;
} */

.video-thumbnail-placeholder:hover .video-duration {
    background-color: #e53935;
    background-image: none;
    border-radius: 100%;
    color: #fff;
    font-weight: bolder;
    font-size: 45px;
    display: flex;
    padding: 0px 10px;
    align-items: center;
    
}



.video-info {
    padding: 15px;
    flex-grow: 1; 
    display: flex;
    flex-direction: column;
}

.video-title {
    font-size: 1.15em;
    font-weight: bold;
    color: #1a2b47;
    margin-top: 0;
    margin-bottom: 5px;
}

.video-channel {
    font-size: 0.9em;
    color: #555;
    margin-bottom: 10px;
}

.video-description {
    font-size: 0.9em;
    color: #777;
    margin-bottom: 15px;
    
}


.video-meta {
    margin-top: auto; 
    display: flex;
    flex-direction: column; 
    padding-top: 10px;
    border-top: 1px solid #eee;
}

.video-tag {
    display: inline-block;
    background-color: #0e1d40; 
    color: white;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 0.75em;
    font-weight: bold;
    align-self: flex-start; 
    margin-bottom: 10px;
}

.video-tag-reportage {
    background-color: #e53935; 
}

.video-stats {
    display: flex;
    justify-content: space-between;
    font-size: 0.85em;
    color: #555;
}

.video-stats span {
    display: flex;
    align-items: center;
    gap: 4px;
}

.video-stats i {
    color: #1a2b47; 
}


@media (max-width: 650px) {
    .video-grid-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .video-grid-header .video-count {
        margin-top: 10px;
    }

    .video-grid-container {
        
        grid-template-columns: 1fr;
    }
}
</style>
<script>
    document.addEventListener('click', function(e) {
        
        
        const clickedCard = e.target.closest('.video-card');

        if (clickedCard) {
           
        }
    });
</script>
<?php } ?>