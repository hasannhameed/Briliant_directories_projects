.cms-content h1 {
    margin-bottom: 24px !important;
	font-family: Roboto!important;
    font-size: 48px !important;
    line-height: 72px !important;
   font-weight: 600 !important;
}


.cms-content p {
    font-size: 16px !important;
	font-family: Roboto!important;
    line-height: 24px !important;
    margin-bottom: 24px !important;
}


/* Style the element that is used to open and close the accordion class */
p.accordion {
    background-color: #f9f9f9;
    color: #444;
    cursor: pointer;
    padding-bottom: 8px;
    width: 100%;
    text-align: left;
    border-bottom: 1px solid #ccc; 
    outline: none;
    transition: 0.4s;
    margin-bottom:0px;
}

/* Add a background color to the accordion if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
p.accordion.active, p.accordion:hover {
    background-color: #fff;
	border-bottom: 1px solid #fff; 
	
}

.top {
	/*border-top: 1px solid #ccc;*/
	padding: 15px;
}

.top-right {
	/*border-top: 1px solid #ccc;
	border-right: 1px solid #ccc;*/
	padding: 15px;
}

.title{
	color: #000;
	font-size: 35px;
	font-weight: 700;
}

.intro{
	color: #000;
	font-size: 18px;
	font-weight: 500;
}

.question-title{
	color: #000;
	font-size: 25px;
	font-weight: 700;
}

.answer{
	color: #000;
	font-size: 18px;
	font-weight: 500;
}

.answer-small {
	color: #000;
	font-size: 14px;
	font-weight: 250;
}

.number{
	color: #ff0000;
	font-size: 25px;
	font-weight: 700;
	padding-bottom: 20px;
}

/* Unicode character for "plus" sign (+) */
p.accordion:after {
    content: '▼'; 
	font-weight: 500;
	font-size:15px;
	font-stretch: expanded;
	border-bottom: none; 
    color: #000;
    float: right;
    margin-left: 5px;
}

/* Unicode character for "minus" sign (-) */
p.accordion.active:after {
    content: "▲"; 
}

/* Style the element that is used for the panel class */

div.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: 0.4s ease-in-out;
    opacity: 0;
    margin-bottom:0px;
	padding-bottom: 10px;
	border-bottom: 1px solid #ccc; 
}

div.panel.show {
    opacity: 1;
    max-height: 600px; /* Whatever you like, as long as its more than the height of the content (on all screen sizes) */
}

/* Media Query for Mobile Devices */
@media screen and (max-width: 768px) {
    .cms-content h1 {
        margin-bottom: 18px !important;
        font-size: 36px !important;
        line-height: 54px !important;
    }

    .cms-content p {
        font-size: 15px !important;
        line-height: 18px !important;
        margin-bottom: 18px !important;
    }

    p.accordion {
        padding-bottom: 6px;
    }

    .top {
        padding: 11px;
    }

    .top-right {
        padding: 11px;
    }

    .title {
        font-size: 26px;
    }

    .intro {
        font-size: 15px;
    }

    .question-title {
        font-size: 18.5px;
    }

    .answer {
        font-size: 15px;
    }

    .answer-small {
        font-size: 14px;
    }

    .number {
        font-size: 18.5px;
        padding-bottom: 12px;
    }

    p.accordion:after {
        font-size: 15px;
    }

    div.panel {
        padding: 0 13px;
        padding-bottom: 7.5px;
    }
}


.orange-button {
	display: block;
	background-color: rgba(247, 144, 30,0.95);
	border-color: rgb(247, 144, 30);
	color: white;
	padding: 10px 16px;
	text-decoration: none;
	border-radius: 5px;
	font-family: Quicksand;
	font-size: 18px;
	font-weight: 600;
	border: none;
	width: 100%;
	vertical-align: middle;
	cursor: pointer;
	text-align:center;
}

    .orange-button:hover {
      background-color: rgba(247, 144, 30,0.95);
	color: white;
    }
