<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title">Contactez un conseiller Quirenov'</h2>
        </div>
        <div class="modal-body">
            [form=copy_of_contact_form]
        </div>
        <div class="modal-footer">
         
        </div>
      </div>
    </div>
  </div>
<style>
    .form-group:has(.custom_feild) {
      width: 50%;
      float: left;
      padding: 0px 10px 0px 0px;
    }
    .form-group {
        position: static;
    }
    .modal-content{
        width: 80%;
    }
    .modal-body {
        width: 100%;
        height: 600px;
        
        overflow: scroll;
        padding: 0px 25px;
        background-color: white !important;
    }
	.modal-header{
		background-color: white !important;
	}
</style>


<? if($pars[0]=='aidandfunding') { ?>
<style>
    .container{
        /* width: 100% !important; */
    }
</style>
<section class="financing-banner-section">
    
    <div class="main-header-banner">
        <div class="header-content-inner">
            <div class="header-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-heart w-10 h-10"><path d="M11 14h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 16"></path><path d="m7 20 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path><path d="m2 15 6 6"></path><path d="M19.5 8.5c.7-.7 1.5-1.6 1.5-2.7A2.73 2.73 0 0 0 16 4a2.78 2.78 0 0 0-5 1.8c0 1.2.8 2 1.5 2.8L16 12Z"></path></svg>
                    <path d="M12 2L3 8V20H21V8L12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 13H15M9 17H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h1 class="main-banner-title">Aides et financement</h1>
                <p class="main-banner-subtitle">Tous les dispositifs pour financer votre projet de rénovation énergétique</p>
            </div>
        </div>
    </div>

    <div class="contact-cta-footer">
        <div class="contact-content-inner">
            
            <div class="contact-text-group">
                <div class="contact-icon-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 16.92V20.92C22 21.46 21.46 22 20.92 22C17.65 22 14.47 21.21 11.47 19.64C8.47 18.08 5.8 15.41 4.24 12.41C2.67 9.41 1.89 6.23 2 2.96C2 2.42 2.54 1.88 3.08 1.88H7.08C7.62 1.88 8.04 2.29 8.1 2.83C8.21 3.73 8.44 4.62 8.78 5.48C8.97 5.96 8.87 6.49 8.54 6.82L7.38 7.98C8.65 10.43 10.73 12.51 13.18 13.78L14.34 12.62C14.67 12.29 15.2 12.19 15.68 12.38C16.54 12.72 17.43 12.95 18.33 13.06C18.87 13.12 19.28 13.54 19.28 14.08V18.08C19.28 18.62 18.86 19.04 18.33 19.1C16.89 19.27 15.47 19.36 14.08 19.36C9.28 19.36 5.12 17.56 2.06 14.5C-1.01 11.44 -1.01 6.31 2.06 3.25C5.12 0.19 9.28 1.88 14.08 1.88" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h3 class="contact-title">Besoin d'aide pour votre projet ?</h3>
                    <p class="contact-subtitle">Nos conseillers Quirenov vous accompagnent gratuitement</p>
                </div>
            </div>

            <div class="contact-buttons-group">
                <a href="tel:0100000000" class="btn-phone-number">
                    01 00 00 00 00
                </a>
                 <a  class="btn-contact-action btn-email" data-toggle="modal" data-target="#myModal"> <!-- href="mailto:contact@example.com" -->
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="#1a2b47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 6L12 13L2 6" stroke="#1a2b47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Email
                </a>
                <a href="#" class="btn-contact-action btn-rappel" data-toggle="modal" data-target="#myModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">                        <path d="M22 16.92V20.92C22 21.46 21.46 22 20.92 22C17.65 22 14.47 21.21 11.47 19.64C8.47 18.08 5.8 15.41 4.24 12.41C2.67 9.41 1.89 6.23 2 2.96C2 2.42 2.54 1.88 3.08 1.88H7.08C7.62 1.88 8.04 2.29 8.1 2.83C8.21 3.73 8.44 4.62 8.78 5.48C8.97 5.96 8.87 6.49 8.54 6.82L7.38 7.98C8.65 10.43 10.73 12.51 13.18 13.78L14.34 12.62C14.67 12.29 15.2 12.19 15.68 12.38C16.54 12.72 17.43 12.95 18.33 13.06C18.87 13.12 19.28 13.54 19.28 14.08V18.08C19.28 18.62 18.86 19.04 18.33 19.1C16.89 19.27 15.47 19.36 14.08 19.36C9.28 19.36 5.12 17.56 2.06 14.5C-1.01 11.44 -1.01 6.31 2.06 3.25C5.12 0.19 9.28 1.88 14.08 1.88" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>                    </svg>
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" stroke="#1a2b47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 6V12L16 14" stroke="#1a2b47" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Rappeler
                </a>
            </div>

        </div>
    </div>
</section>
<style>
    
    :root {
    --color-dark-navy: #0e1d40; /* Darker blue for the header background */
    --color-red-cta: #dc2626; /* Bright red */
    --color-contact-bg: #fffbf9; /* Light beige/pink background for contact footer */
    --color-primary-blue: #1a2b47;
    --color-text-dark: #333;
    --color-text-light: #666;
    --color-border: #e0e0e0;
}

/* --- Main Header Banner (Aides et financement) --- */
.main-header-banner {
    background-color: #192a56;
    color: white;
    padding: 60px 20px;
    /* Optional: Adding a small red line at the top/bottom if desired, though not fully visible in the crop */
}

.header-content-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon-box {
    width: 60px;
    height: 60px;
    background-color: var(--color-red-cta);
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
}

.main-banner-title {
    font-size: 3em;
    font-weight: bold;
    margin: 0;
}

 
.main-banner-subtitle {
    font-size: 1.4em;
    margin: 5px 0 0 0;
	font-weight:200;
    opacity: 0.9;
}

/* --- Contact Footer CTA (Besoin d'aide) --- */
.contact-cta-footer {
        background-color: #fef4f0;
    padding: 30px 20px;
    border-bottom: 5px solid var(--color-red-cta); /* Red accent line at the bottom */
}

.contact-content-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
}

.contact-text-group {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-shrink: 1;
}

.contact-icon-box {
    width: 65px;
    height: 65px;
    background-color: var(--color-red-cta);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
}

.contact-title {
    font-size: 1.5em;
    color: var(--color-primary-blue);
    margin: 0;
    font-weight: bold;
}

.contact-subtitle {
    font-size: 0.9em;
    color: var(--color-text-light);
    margin: 3px 0 0 0;
}

.contact-buttons-group {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

/* Button Styling */
.btn-phone-number {
    background-color: var(--color-red-cta);
    color: white;
    text-decoration: none;
    padding: 15px 20px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 1em;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-phone-number:hover {
    background-color: #a81c1c;
}

.btn-contact-action {
    background-color: white;
    color: var(--color-text-dark);
    text-decoration: none;
    padding: 10px 15px;
    border: 1px solid var(--color-border);
    border-radius: 5px;
    font-size: 1em;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: background-color 0.2s;
	border: 1px solid #0000003d;
    cursor: pointer;
}

.btn-contact-action:hover {
    background-color: #f0f0f0;
}

.btn-contact-action svg path {
    stroke: var(--color-primary-blue);
}


/* --- Responsive Adjustments --- */
@media (max-width: 900px) {
    .contact-content-inner {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .contact-buttons-group {
        width: 100%;
        justify-content: stretch;
        margin-top: 15px;
    }

    .btn-phone-number, .btn-contact-action {
        flex: 1;
    }
}

@media (max-width: 500px) {
    .contact-buttons-group {
        flex-direction: column;
    }
	.main-banner-title {
		font-size: 2em;
		font-weight: bold;
		margin: 0;
	}
}
</style>
<? } ?>