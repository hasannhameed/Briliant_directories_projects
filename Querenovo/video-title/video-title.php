<?php  if( $pars[0]=='videos' ) { ?>

<div class="energy-renovation-banner">
    <div class="banner-content container modal-dialog">
        <div class="banner-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3.5" y="6.5" width="13" height="11" rx="2" stroke="white" stroke-width="2"/>
                <path d="M16.5 9L20 7V17L16.5 15V9Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="banner-text">
            <h1 class="banner-title">Vidéothèque Rénovation Énergétique</h1>
            <p class="banner-subtitle">Tutoriels, témoignages, innovations et actualités de la rénovation</p>
        </div>
    </div>
</div>
<style>

.energy-renovation-banner {
    /* background-color: #0e1d40; */
        background: linear-gradient(135deg, #0e1d40 0%, #102c5b 100%);
    padding: 50px 30px;
    display: flex;
    align-items: center;
    color: white;
    font-family: Arial, sans-serif;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
}
.feature-videos{
    margin-top: -50px;
}
.banner-content {
    display: flex;
    align-items: center;
}

.banner-icon {
    background-color: #e53935;
    border-radius: 20px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.banner-icon svg {
   
       width: 55px;
    height: 50px;
   
}

.banner-text {
    flex-grow: 1;
}

.banner-title {
    font-size: 2.8em;
    font-weight: bold;
    margin: 0;
    line-height: 1.2;
}

.banner-subtitle {
    font-size: 1.3em;
    margin: 5px 0 0 0;
    opacity: 0.8;
}

.input-lg {
    border-radius: 5px;
    height: 46px;
    border: 1px solid black;
}
.btn{
    border-radius: 10px;
    padding: 10px 16px;
}
@media (max-width: 768px) {
    .energy-renovation-banner {
        padding: 15px 20px;
    }

    .banner-icon {
        padding: 6px;
        margin-right: 10px;
    }

    .banner-icon svg {
        width: 32px;
        height: 32px;
    }

    .banner-title {
        font-size: 1.5em;
    }

    .banner-subtitle {
        font-size: 0.8em;
    }
}

@media (max-width: 480px) {
    .energy-renovation-banner {
        padding: 10px 15px;
    }

    .banner-content {
        flex-direction: column;
        text-align: center;
    }

    .banner-icon {
        margin-right: 0;
        margin-bottom: 10px;
        padding: 5px;
    }
    
    .banner-icon svg {
        width: 28px;
        height: 28px;
    }

    .banner-title {
        font-size: 1.3em;
    }

    .banner-subtitle {
        font-size: 0.75em;
    }
}
</style>

<?  } ?>