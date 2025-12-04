<style>
    .testimonials-section {
        font-family: 'Segoe UI', sans-serif;
        max-width: 1100px;
        margin: 60px auto;
        padding: 0 20px;
        text-align: center;
    }

    .testimonials-title {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 40px;
    }

    .testimonials-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    /* Individual Testimonial Card */
    .testimonial-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 30px;
        flex: 1;
        min-width: 280px;
        max-width: 350px;
        text-align: left;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Stars */
    .testimonial-stars {
        color: #fbbf24; /* Yellow/Gold color */
        font-size: 18px;
        margin-bottom: 15px;
    }

    /* Testimonial Text */
    .testimonial-text {
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 20px;
        font-style: italic;
    }

    /* Author Info */
    .testimonial-author h4 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 5px 0;
    }

    .testimonial-author p {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }

    @media (max-width: 768px) {
        .testimonials-container {
            flex-direction: column;
            align-items: center;
        }
        .testimonial-card {
            width: 100%;
        }
    }
</style>

<div class="testimonials-section">
    <h2 class="testimonials-title">Ils nous font confiance</h2>

    <div class="testimonials-container">
        <div class="testimonial-card">
            <div class="testimonial-stars">★★★★★</div>
            <p class="testimonial-text">"Excellente plateforme pour développer notre visibilité. Nous recevons régulièrement des demandes de devis qualifiées."</p>
            <div class="testimonial-author">
                <h4>Entreprise 1</h4>
                <p>Inscrit depuis 2024</p>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-stars">★★★★★</div>
            <p class="testimonial-text">"Plateforme intuitive et efficace. Le support client est très réactif. Je recommande vivement pour les professionnels du bâtiment."</p>
            <div class="testimonial-author">
                <h4>Entreprise 2</h4>
                <p>Inscrit depuis 2024</p>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-stars">★★★★★</div>
            <p class="testimonial-text">"Grâce à Quirenov, nous avons pu toucher une nouvelle clientèle et augmenter notre chiffre d'affaires de manière significative."</p>
            <div class="testimonial-author">
                <h4>Entreprise 3</h4>
                <p>Inscrit depuis 2024</p>
            </div>
        </div>
    </div>
</div>
```