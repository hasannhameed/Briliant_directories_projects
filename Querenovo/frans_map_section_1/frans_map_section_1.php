    <style>
        .how-to-section {
            margin-top: -10px;
            margin-bottom: -15px;
            background-color: #434244;
            color: #f4f4f4;
            padding: 60px 20px;
            text-align: center;
        }

        .how-to-section .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        
        .how-to-section .accent-line {
            width: 100%;
            max-width: 900px;
            height: 3px;
            background-color: #b2d234;
            margin: 0 auto 50px auto;
        }
        .how-to-section .accent-line:first-child {
            margin-bottom: 60px;
        }

        
        .how-to-section h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 50px;
        }
        .how-to-section h1 {
           
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 50px;
        }
        
        .steps-grid {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px 20px;
            margin-bottom: 60px;
        }

        .step-item {
            flex: 0 0 200px;
            text-align: center;
        }

       
        .step-image-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px auto;
            border: 2px solid #b2d234;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .step-image-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

       
        .step-item h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #b2d234;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

       
        .step-item p {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #dddddd;
            margin: 0;
        }

        
        @media (max-width: 768px) {
            .how-to-section h2 {
                font-size: 2rem;
            }
            .step-item {
                flex: 0 0 45%;
            }
        }

        @media (max-width: 480px) {
            .how-to-section h2 {
                font-size: 1.8rem;
            }
            .step-item {
                flex: 0 0 90%;
            }
        }
    </style>


    <section class="how-to-section">
        <div class="row">

            <!-- <div class="accent-line"></div> -->
            
            <h1>Comment Trouver Votre Expert par Département</h1>
            
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-image-circle">
                        <img src="https://cdn-icons-png.flaticon.com/512/9852/9852500.png" alt="Image pour Étape 1">
                        </div>
                    <h4>EXPLORER</h4>
                    <p>Naviguez facilement par département ou par catégorie de service.</p>
                </div>
                
                <div class="step-item">
                    <div class="step-image-circle">
                        <img src="https://img.freepik.com/free-vector/illustration-check-arrow-icon_53876-35887.jpg?semt=ais_hybrid&w=740&q=80" alt="Image pour Étape 2">
                    </div>
                    <h4>SÉLECTIONNER</h4>
                    <p>Affinez votre recherche avec des filtres pour vos besoins spécifiques.</p>
                </div>
                
                <div class="step-item">
                    <div class="step-image-circle">
                        <img src="https://e7.pngegg.com/pngimages/223/22/png-clipart-telephone-call-computer-icons-ringing-contact-centre-icon-telephone-call-trademark-thumbnail.png" alt="Image pour Étape 3">
                    </div>
                    <h4>CONTACTER</h4>
                    <p>Échangez directement avec les professionnels locaux vérifiés.</p>
                </div>
                
                <div class="step-item">
                    <div class="step-image-circle">
                        <img src="https://cdn-icons-png.flaticon.com/512/4698/4698094.png" alt="Image pour Étape 4">
                    </div>
                    <h4>FINALISER</h4>
                    <p>Menez à bien votre projet avec l'expert idéal en toute confiance.</p>
                </div>
            </div>

            <div class="accent-line"></div>

        </div>
    </section>

