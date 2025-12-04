<div class='almight-parent'>
<div class="container">
<div class="row">
    <div class="col-sm-12 nopad">
        <section class="hero-section text-center py-5">
            <div class="container row parent-rw1">
                <div class="row absolute-div">
                    <div class="col-sm-4 col-xs-4 bg-blog left"></div>
                    <div class="col-sm-4 col-xs-4 bg-blog"></div>
                    <div class="col-sm-4 col-xs-4 bg-blog right"></div>

                    <div class="col-sm-4 col-xs-4 bg-blog c_left"></div>
                    <div class="col-sm-4 col-xs-4 bg-blog c_center"></div>
                    <div class="col-sm-4 col-xs-4 bg-blog c_right"></div>

                    <div class="col-sm-4 col-xs-4 bg-blog b_left"></div>
                    <div class="col-sm-4 col-xs-4 bg-blog b_center"></div>
                    <div class="col-sm-4 col-xs-4 bg-blog b_right"></div>

                </div>
                

                <div class="card p-4 p-md-5 hero-card relative-div">
                    <h1 class="text-white mb-4 display-4 fw-bold">PRODUITS & SERVICES</h1>

                    <p class="text-white mb-4 fs-5">
                        Découvrez une sélection innovante de produits et services dédiés à l'efficacité énergétique, au confort et à la durabilité de votre habitat.
                    </p>

                    <span class="text-white mb-4 span">Que vous soyez un professionnel à la recherche de matériaux performants ou un particulier soucieux de son empreinte environnementale, trouvez ici les solutions adaptées à vos besoins.</span>

                    <div class="d-flex btn-parent justify-content-center flex-wrap gap-3">
                        <a href="#" class="btn hero-btn">Isolation</a>
                        <a href="#" class="btn hero-btn">Chauffage</a>
                        <a href="#" class="btn hero-btn">Ventilation</a>
                        <a href="#" class="btn hero-btn">Menuiserie</a>
                        <a href="#" class="btn hero-btn">Énergies</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
</div>


<style>
    /* Custom Styles for the Hero Section */

/* 1. The main orange background for the card */
/* .absolute-div{
   position: relative;
}
.relative-div{
    
     position: absolute;
} */
.b_left,.b_center,.b_right{
    display: none !important;
}

.hero-card {
    top: 60px;
    left: 35px;
    position: absolute;
    
    border-radius: 15px;
    /* padding: 70px 250px; */
    line-height: 3rem;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.bg-blog {
    height: 370px;
    background-color: #4276e9;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 20px solid #2c68e7;

}
.left{
     border-right: none;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
}
.right{
    border-left: none;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
}
.btn-parent{
    padding: 20px;
}

/* 2. Styling for the action buttons */
.hero-btn {
    /* Background color slightly darker/different than the main card */
    background-color: #517de6; 
    border: 1px solid #517de6;
    color: white;
    font-weight: 500;
    padding: 8px 20px;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
    min-width: 120px; /* Ensure buttons have a minimum width */
    padding: 10px;
    margin: 5px;
}

/* Hover effect for the buttons */
.hero-btn:hover {
	color: white;
    /* background-color: #2c68e7c7; *?
   
    /* transform: translateY(-2px);  */
}
.hero-btn:focus {
		 color: white;
    /* background-color: #2c68e7c7; *?
   
    /* transform: translateY(-2px);  */
}
.parent-rw1 {
    max-width: 100%;
    margin: 0 auto;
}
/* Ensuring text within the section is white */
.hero-card h1, .hero-card p,.hero-card span {
    color: white !important;
}

.hero-card p {
    font-size: 23px;
    width: 70%;
}
.hero-card span,h1 {
   
    width: 60%;
}

.btn-parent{
    width: 70%;
}
.hero-card h1 {
    font-weight: bold;
    font-size: 40px;
}

.hero-card .span {
    margin: 10px 0px;
}
.absolute-div{
    width: 100%;
    margin: 0 auto;
}
.c_left,.c_center,.c_right{
    display: none;
}
.c_left,.c_center{
    border-right: none;
     border-top: none;
}

/* Adjusting the hero section padding */
.hero-section {
    background-color: #f8f9fa;
    border-radius: 20px;
}
.almight-parent {
    padding: 50px 0px;
    background-color: #192a560a;
}
@media screen and (max-width: 1200px) {
    .hero-card {
        top: 75px;
        left: 0px;
        position: absolute;
    }
     .bg-blog {
       height:520px ;
    }
}

@media screen and (max-width: 900px) {
    .hero-card {
        top: 50px;
        left: 0px;
        position: absolute;
        border-radius: 15px;
        line-height: 3rem;
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .bg-blog {
       height:260px ;
    }
    
    .absolute-div{
        background-color: #2c68e7;
        border-radius: 20px;
    }
    .b_left,.b_center,.b_right{
        display: block !important;
    }
    
    .b_left,.b_center{
        border-right: none !important;
        border-top: none !important;
    }
    .b_right{
        border-top: none !important;
        border-bottom-right-radius: 20px;
    }
    .b_left{
        border-bottom-left-radius: 20px;
    }
}

@media screen and (max-width: 600px) {
    .bg-blog {
         height: 220px !important;
    }
    .c_left,.c_center,.c_right{
        display: block !important;
    }
    .btn-parent {
        width: 90%;
    }
}
@media screen and (max-width: 570px) {
    .btn-parent {
        width: 100%;
    }
    .bg-blog {
         height: 240px !important;
    }
}

@media screen and (max-width: 500px) {
    .btn-parent {
        width: 100%;
    }
    .bg-blog {
         height: 290px !important;
    }
    .absolute-div {
        width: 100%;
        margin: 0 auto;
    }
}

</style>