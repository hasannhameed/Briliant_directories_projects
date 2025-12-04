<section class="branding-banner-section">
    <div class="banner-content-wrapper">
        <div class="branding-icon-box">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3.5" y="6.5" width="13" height="11" rx="2" stroke="white" stroke-width="2"/>
                <path d="M16.5 9L20 7V17L16.5 15V9Z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="branding-text-container">
            <h1 class="branding-title">La Chaîne Quirenov'</h1>
            <p class="branding-subtitle">Nos productions pour sensibiliser et vulgariser la rénovation énergétique</p>
        </div>
    </div>
</section>
	

<style>
.branding-banner-section {
    
    background-color: #fef5ef; 
    padding: 30px 20px;
    font-family: Arial, sans-serif;
}

.banner-content-wrapper {
    display: flex; 
    align-items: center; 
    max-width: 1200px;
    
}



.branding-icon-box {
    background-color: #e53935; 
    border-radius: 8px; 
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0; 
	border-radius: 20px;
}

.branding-icon-box svg {
    
    width: 50px;
    height: 50px;
}
	
	


.branding-text-container {
    margin-left: 15px; 
    color: #1a2b47; 
}

.branding-title {
    font-size: 1.8em;
    font-weight: bold;
    margin: 0; 
    line-height: 1.2;
}

.branding-subtitle {
    font-size: 1em;
    margin: 3px 0 0 0; 
    opacity: 0.85; 
}


@media (max-width: 600px) {
    .branding-banner-section {
        padding: 20px 15px;
    }
    
    .banner-content-wrapper {
        flex-direction: column; 
        text-align: center;
    }

    .branding-icon-box {
        margin-bottom: 15px; 
    }

    .branding-text-container {
        margin-left: 0;
    }

    .branding-title {
        font-size: 1.5em;
    }

    .branding-subtitle {
        font-size: 0.9em;
    }
}
</style>