<style>
    .join-cta-section {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #0e1d40 0%, #102c5b 100%);
        color: #ffffff;
        text-align: center;
        padding: 60px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Red Icon Box */
    .join-icon-wrapper {
        background-color: #dc2626; /* Red color */
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
    }

    .join-icon-wrapper svg {
        width: 32px;
        height: 32px;
        fill: white;
    }

    /* Title styling */
    .join-cta-title {
        font-size: 45px;
        font-weight: 800;
        margin: 0 0 20px 0;
        letter-spacing: -0.5px;
    }

    /* Description paragraph styling */
    .join-cta-desc {
        font-size: 18px;
        font-weight: 400;
        line-height: 1.5;
        max-width: 700px;
        margin: 0 auto;
        opacity: 0.9; /* Slightly softer white */
    }

    @media (max-width: 768px) {
        .join-cta-title {
            font-size: 26px;
        }
        .join-cta-desc {
            font-size: 16px;
        }
    }
</style>

<div class="join-cta-section">
    <div class="join-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
        </svg>
    </div>
    
    <h2 class="join-cta-title">Rejoignez Quirenov.fr</h2>
    
    <p class="join-cta-desc">
        Développez votre visibilité auprès de particuliers, entreprises, collectivités et tous les maîtres d'ouvrage de la rénovation énergétique
    </p>
</div>