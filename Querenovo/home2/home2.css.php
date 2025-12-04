.home-container {
    margin-top: -10px;
}
/* Red header bar */
.project-steps-header {
    background-color: white;
    /* red shade */
    color: black !important;
    padding: 12px 0;
    border-radius: 25px;
    margin-bottom: 25px;
    font-size: xx-large;
    flex-grow: 1;
    overflow: visible;
    text-transform: none;
    text-align: center;
    letter-spacing: 0px;
    line-height: 31px;
    white-space: nowrap;
    opacity: unset;
    visibility: visible;
    color: rgb(255, 255, 255);
}

/* Blue section container */
.project-step-box {
    background-color: #122955;
    /* deep navy */
    color: #fff;
    border-radius: 15px;
    padding: 10px 15px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
}

/* Image styling */
.step-image,.renovation-image {
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    width: 250px !important;
}

/* Text styling */
.step-content h3 {
    margin-bottom: 10px;

}

.step-content p {
    line-height: 1.6;
    height: 194px;
    flex-grow: 1;
    margin: 0px;
    overflow: hidden;
    /* font-size: 20px; */
    text-transform: none;
    text-align: center;
    letter-spacing: 0px;
    line-height: 31px;
    white-space: pre-line;
    word-break: break-word;
    opacity: 1;
    visibility: visible;
}

.project-red-box {
    background-color: #ffffff; /* White background */
    color: #333; /* Darker text for better contrast on white */
    border-radius: 12px; /* Slightly less rounded than 20px for a modern look */
    padding: 30px;
    
    /* ADDED: A subtle inner shadow/border for structure */
    border: 1px solid #e0e0e0;
    
    /* ADDED: Stronger box shadow for depth (subtle lift effect)
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1); */ 
    
    /* ADDED: Transition for hover effect */
    transition: all 0.3s ease-in-out;
    
    line-height: 1.6;
    font-size: 16px;
    margin-bottom: 25px;
}

/* ADDED: Hover effect for responsiveness 
.project-red-box:hover {
    /* Lighter lift and slightly more defined shadow */
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    
    /* Optional: Add an accent glow/border using a specific color */
    border-color: #c62839; /* Red border on hover for accent */
}

.project-red-box p {
   
    flex-grow: 1;
    overflow: hidden;
    /* Keeping text styles as requested */
    text-transform: none;
    text-align: left;
    letter-spacing: 0px;
    line-height: 31px; 
    white-space: pre-line;
    word-break: break-word;
    opacity: 1;
    visibility: visible;
}

.project-red-box strong {
    color: #c62839; /* Highlight strong text with your Red color */
    display: inline-block;
    margin-bottom: 8px !important;
    font-weight: 700; /* Ensure the strong text is bold */
}

.renovation-section {
    background-color: #122955;
    /* Deep navy blue */
    color: #fff;
    border-radius: 15px;
   padding: 10px 15px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
}

.renovation-image {
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}

.renovation-content h3 {
    margin-bottom: 10px;
}

.renovation-content p {
    font-size: 16px;
    line-height: 1.6;
}

/* Responsive: image on top for mobile */
@media (max-width: 767px) {
    .renovation-section,.project-step-box {
        text-align: center;
        display: block;
    }
    .renovation-image,.step-image {
        margin-bottom: 20px;
    }
}

.renovation-red-box {
    background-color: #c62839;
    /* Consistent red */
    color: #fff;
    border-radius: 20px;
    padding: 30px;
    line-height: 1.6;
    font-size: 16px;
}

.renovation-red-box p {
    margin-bottom: 18px;
}

.renovation-red-box strong {
    display: inline-block;
    margin-bottom: 5px;
}

.cta-section {
    margin-top: 30px;
    margin-bottom: 30px;
}

.cta-card {
    background-color: #fff;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 30px 25px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    /* min-height: 350px; */
}


.cta-card h3 {
    margin-bottom: 15px;
    font-size: 40px;;
    color: #2d2d2d;
}

.cta-card p {
    font-size: 15px;
    line-height: 1.6;
    color: #333;
    margin-bottom: 25px;
}
.cta-card hover{
	border: 5px solid red !important;
	
}

/* Blue button */
.btn-navy {
    background-color: #122955;
    color: #fff;
    border-radius: 6px;
    padding: 10px 25px;
    transition: background 0.3s ease;
}

.btn-navy:hover {
    background-color: #0d1e3f;
    color: #fff;
}

/* Red button */
.btn-red {
    background-color: #c62839;
    color: #fff;
    border-radius: 6px;
    padding: 10px 25px;
    transition: background 0.3s ease;
}

.btn-red:hover {
    background-color: #a71e2e;
    color: #fff;
}

/* Responsive */
@media (max-width: 767px) {
    .cta-card {
        margin-bottom: 20px;
    }
}

.faq-box {
    background-color: #122955;
    color: #fff !important;
    border-radius: 20px;
    padding: 35px 30px;
    font-size: 16px;
    line-height: 1.6;
    margin: 30px 0;
    max-width: 100%;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.faq-box p { margin-bottom: 25px; }
.faq-box strong { display: inline-block; margin-bottom: 0px; }
.faq-item { margin-bottom: 10px; border-radius: 0px; background: #4341753d; overflow: hidden; transition: background-color 0.3s ease; }
.faq-item:last-child { margin-bottom: 0; }
.faq-item strong { display: block; cursor: pointer; padding: 15px 20px; font-size: 16px; font-weight: 700; position: relative; user-select: none; transition: background-color 0.3s ease, color 0.3s ease; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.faq-item:hover strong { background-color: #1e3a6c; }
.faq-item.open strong, .faq-item strong.active { background-color: #c62839; border-color: #c62839; color: #fff !important; }
.faq-item strong::after { content: '+'; position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 24px; font-weight: 300; transition: transform 0.3s ease; }
.faq-item.open strong::after { content: '−'; transform: translateY(-50%) rotate(0deg); }
.faq-content { max-height: 0; padding: 0 20px; opacity: 0; transition: max-height 0.4s ease-in-out, padding 0.4s ease-in-out, opacity 0.4s ease-in-out; }
.faq-item.open .faq-content { max-height: 500px; padding: 15px 20px 20px 20px; opacity: 1; }
.faq-content p { margin: 0; padding: 0; }






.card-box {
    display: block;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 16px;
    padding: 20px;
    text-decoration: none !important;
    transition: all 0.25s ease;
    min-height: 220px;
}
.card-box:hover {
    border-color: red;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transform: translateY(-4px);
}
.card-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    background: #eef4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.card-icon i {
    font-size: 26px;
    color: #0d6efd;
}
.card-box h4 {
    font-weight: 600;
    margin-bottom: 10px;
    color: #0b1a33;
}
.card-box p {
    margin-bottom: 15px;
    color: #444;
}
.card-link {
    color: #d90429 !important;
    font-weight: 600;
}
.card-link i {
    margin-left: 5px;
}
