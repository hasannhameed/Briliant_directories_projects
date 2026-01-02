@font-face {
  font-family: Avenir_Book;
  src: url('../fonts/Avenir_Book.otf');
}

@font-face {
  font-family: Avenir_Heavy;
  src: url('../fonts/Avenir_Heavy.ttf');
}

@font-face {
  font-family: Avenir_Medium;
  src: url('../fonts/Avenir_Medium.otf');
}

body{
	margin:0; 
	padding:0; 
	font-size:13px; 
	font-family: 'Avenir_Book';
	color:#000; 
	background-color:#fff;
	min-width:371px;
}

h1,h2,h3,h4,h5,h6 {
	margin:0;
	padding:10px 15px;
	font-weight:normal;
}

a {
	color:#000;
	text-decoration:none;
	cursor:pointer;
}

a:hover {
    text-decoration:none;
}

p {
	font-size:13px;
	text-align: justify;
	margin:0;
	padding:5px 15px;
}

.margin_90 {
	margin:0 90px;
}

.row {
	margin: 0;
}








/***********   big image   ************/

.big_image {
	background: #fff url('../img/background.jpg');
	background-repeat: no-repeat;
	background-size: auto 100%;
}
.image_overlay {
	background: rgba(255,255,255,0.8);
}

.big_image h2{
	margin-top: 93px;
    padding: 0;
    font-family: Avenir_Heavy;
    font-size: 34px;
    letter-spacing: 1.8px;
    line-height: 42px;
}

.big_image p{
	padding: 0;
	margin: 40px 0 0 0;
}

.big_image p a, .join_free{
	background: #1bb4bd;
	color: #fff;
	padding: 13px 60px;
	font-size: 14px;
	letter-spacing: 3px;
	margin-top: 10px;
	border-radius: 25px;
}

.big_image h3{
	margin: 100px 0 15px;
	text-align: center;
	letter-spacing: 1.5px;
	line-height: 35px;
	font-style: italic;
}


/**********   How we can help   ***********/

.block_title {
	text-align: center;
	margin-top: 35px;
	font-family: Avenir_Heavy;
	font-size: 35px;
	letter-spacing: 4px;
}

.block_title:after {
	content: '';
	height:5px;
	width:200px;
	position: relative;
	bottom: -20px;
	margin: 0 auto;
	background: #1bb4bd;
	display: block;
}
.help_icons {
	width: 120px;
	height: auto;
	margin: 40px auto 15px auto;
	display: block;
}
.how_help h3 {
	padding: 0;
	margin: 0;
	text-align: center;
	font-size: 21px;
	letter-spacing: 2.5px;
	line-height: 29px;
	color: #444;
}


/**********   How it works   ***********/

.margin_120 {
	margin:0 120px;
}

.how_works h4{

	text-align: center;
	font-family: Avenir_Heavy;
	font-size: 20px;
	margin-top: 30px;
	letter-spacing: 2px;
	font-style: italic;
	margin-bottom: 50px;
	padding-right: 0;
	padding-left: 0;
}

.how_works h3 {

	padding: 0;
	margin: 0;
	font-size: 24px;
	font-family: Avenir_Medium;
	letter-spacing: 2px;
	font-weight: 800;

}
.how_works h3 span {

	border-bottom: 2px solid #000;

}
.how_works p {

	margin: 13px 0 0 20px;
	font-family: Avenir_Medium;
	font-size: 15px;
	letter-spacing: 2px;
	font-weight: bold;
	padding: 0;
	color: #333;
}

.browse {
	font-size: 20px;
	font-style: italic;
	letter-spacing: 2px;
	color: #112aff;
}

.list_block {
	height: 390px;
}
.membership {
	color: #1bb4bd;
}

.join_free2 {
	margin: 0 auto;
	display: block;
	width: 340px;
}

.join_free2:hover {
	color: #fff;
}

.abs_free {
	text-align: center;
	font-family: Avenir_Heavy;
	font-size: 60px;
	letter-spacing: 2px;
	padding-right: 0;
	margin-right: 0;
	padding-left: 0;
	margin-left: 0;
}


/**********   Footer   ***********/

.footer {
	background: #fafafa;
	padding:30px 100px 80px 100px;
	color: #333;
}

.footer h2 {
	font-family: Avenir_Heavy;
	font-size: 35px;
	text-align: center;
	letter-spacing: 2px;
	padding-left: 0;
	padding-right: 0;
}

.footer h3 {
	text-align: center;
	letter-spacing: 3px;
	font-size: 23px;
	padding-left: 0;
	padding-right: 0;
}

.footer .client_title {
	font-size:32px;
}

.brand_icons {
	height: 65px;
	width: auto;
	margin: 0 auto;
	display: block;
	margin-bottom: 30px;
}



/**********   Class Helpers   ***********/


.white{
	color:#fff;
}
.red {
	color:red;
}
.no_border {
	border:none;
}
.border{
	border:1px solid #ff9900;
}
.no_margin {
	margin:0;
}
.left {
	float:left;
}
.right {
	float:right;
}
.clear {
	clear:left;
	clear:right;
}
.no_padding {
	padding:0;
}
.center {
	text-align:center;
}








/***********  Media Query Responsive  ***************/
@media screen and (max-width: 1299px){	
	.brand_icons {
		height: 50px;
	}
}

@media screen and (max-width: 1199px){	
	.margin_90, .header_container, .margin_120{
		margin: 0;
		width: 100%;
	}
	.how_works {
    	padding-left: 80px;
		padding-right: 80px;
	}
	.footer {
		padding:30px 15px;
	}
}

@media screen and (max-width: 991px){	
	.navbar-nav > li > a {
		padding: 10px;
		font-size: 16px;
	}
	
	.how_works {
    	padding-left: 100px;
		padding-right: 100px;
	}
}
@media screen and (max-width: 767px){
	.how_works {
    	padding-left: 15px;
		padding-right: 15px;
	}
	.nav  {
		width: 100%;
		margin-top: 30px;
	}	
	#myNavbar{
		width: 100%;
		text-align: right;
	}
	.navbar-nav > li > a {
		padding-right: 0;
	}
	.navbar-toggle {
		margin-top: 5px;
		margin-bottom: 0;
	}
	.big_image h2{
		font-size: 27px;
	}

	.big_image h3{
		font-size: 17px;
	}
	.list_block h3, .list_block p, .list_block h6 {
		text-align: center;
		padding-left:0;
		padding-right:0;
		margin-left:0;
		margin-right:0;
	}

}
	
@media screen and (max-width: 700px){
	
	
}
@media screen and (max-width: 600px){
	
	.big_image h2{
		font-size: 22px;
		margin-top:40px;
		line-height: 35px;
	}

	.big_image h3{
		font-size: 15px;
		margin-top: 60px;
		line-height: 25px;
	}

	.big_image p a{
		padding: 10px 25px;
	}
	
}
@media screen and (max-width: 450px){	

	.big_image h2{
		font-size: 17px;
		line-height:27px;
	}

	.big_image h3{
		font-size: 13px;
		margin-top: 60px;
		line-height: 22px;
		padding: 0;
		margin-bottom: 30px;
	}

	.big_image p a{
		padding: 10px 25px;
		font-size: 11px;
	}

	.join_free2 {
		margin: 0 auto;
		display: block;
		width: 300px;
		padding: 10px 40px;
	}
	
}
.web-page-content p:first-child {
    display: none;
}