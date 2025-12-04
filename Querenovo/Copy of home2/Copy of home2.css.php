.home-container {
    margin-top: -10px;
}
/* Red header bar */
.project-steps-header {
    background-color: #c62839;
    /* red shade */
    color: #fff;
    padding: 12px 0;
    border-radius: 25px;
    margin-bottom: 25px;

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
    background-color: #c62839;
    /* Same red as your previous section */
    color: #fff;
    border-radius: 20px;
    padding: 30px;
    line-height: 1.6;
    font-size: 16px;
    margin-bottom: 25px;
}

.project-red-box p {
    margin-bottom: 20px;
    flex-grow: 1;
    overflow: hidden;
    /* font-size: 20px; */
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
   
    display: inline-block;
    margin-bottom: 5px;
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
    border: 1px solid #ddd;
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
    /* deep navy blue */
    color: #fff;
    border-radius: 20px;
    padding: 35px 30px;
    font-size: 16px;
    line-height: 1.6;
    margin: 30px 0;
}

.faq-box p {
    margin-bottom: 25px;
}

.faq-box strong {
    display: inline-block;
    margin-bottom: 5px;
}

.faq-item {
    margin-bottom: 10px;
    padding: 10px;
    background: #4341753d;
     user-select: none;         /* Prevents text selection */
    -webkit-user-select: none; /* For Safari/Chrome */
    -moz-user-select: none;    /* For Firefox */
    -ms-user-select: none;     /* For IE/Edge */
}
