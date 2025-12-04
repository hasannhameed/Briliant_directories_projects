<style>
    .why-register-section {
        font-family: 'Segoe UI', sans-serif;
        max-width: 1100px;
        margin: 60px auto;
        padding: 0 20px;
        text-align: center;
    }

    .why-register-title {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 50px;
    }

    .benefits-container {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .benefit-item {
        flex: 1;
        min-width: 250px;
        max-width: 350px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Icon Circles */
    .benefit-icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .bg-blue-light { background-color: #e0f2fe; }
    .bg-green-light { background-color: #dcfce7; }
    .bg-orange-light { background-color: #ffedd5; }

    .benefit-icon-circle svg {
        width: 32px;
        height: 32px;
    }

    .icon-blue { stroke: #0284c7; fill: none; stroke-width: 2; }
    .icon-green { stroke: #16a34a; fill: none; stroke-width: 2; }
    .icon-orange { stroke: #ea580c; fill: none; stroke-width: 2; }

    /* Text Styling */
    .benefit-item h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .benefit-item p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
        max-width: 300px; /* Keep text nice and wrapped */
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .benefits-container {
            flex-direction: column;
            align-items: center;
        }
        .benefit-item {
            margin-bottom: 40px;
        }
    }
</style>

<div class="why-register-section">
    <h2 class="why-register-title">Pourquoi s'inscrire sur Quirenov.fr ?</h2>

    <div class="benefits-container">
        <div class="benefit-item">
            <div class="benefit-icon-circle bg-blue-light">
                <svg class="icon-blue" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <h3>Visibilité accrue</h3>
            <p>Soyez visible auprès de tous les maîtres d'ouvrage : particuliers, entreprises, collectivités, bailleurs sociaux...</p>
        </div>

        <div class="benefit-item">
            <div class="benefit-icon-circle bg-green-light">
                <svg class="icon-green" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.77 2 2 0 0 1 0-2.83z"></path>
                   <path d="M12 8v8"></path>
                   <path d="M8 12h8"></path>
                </svg>
            </div>
            <h3>Crédibilité renforcée</h3>
            <p>Mettez en avant vos labels, certifications et réalisations</p>
        </div>

        <div class="benefit-item">
            <div class="benefit-icon-circle bg-orange-light">
                <svg class="icon-orange" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <h3>Simple et rapide</h3>
            <p>Inscription en quelques minutes, gestion facile de votre profil</p>
        </div>
    </div>
</div>