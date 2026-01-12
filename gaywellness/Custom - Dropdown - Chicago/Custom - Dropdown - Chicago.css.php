.link[data-v-06235859] {
    box-sizing: inherit;
    color: #477be4;
    cursor: pointer;
    display: block;
    font-family: Proxima Nova,Open Sans,Helvetica Neue,Arial,sans-serif;
    -webkit-text-decoration: none;
    text-decoration: none;
    touch-action: manipulation;
}

/* Style the element that is used to open and close the accordion class */
@media only screen and (min-width: 769px){
p.accordion {
    color: #444;
    cursor: pointer;
	
	font-size:16px;
    width: 26%;
    text-align: left;
    border: none;
    outline: none;
    transition: 0.4s;
    margin-bottom:10px;
	}

/* Unicode character for "plus" sign (+) */
p.accordion:after {
    content: '˅'; 
    font-size: 16px;
	
    color: #777;
    float: right;
    margin-left: 5px;
}
	
}
 @media only screen and (max-width: 768px) {
p.accordion {
    color: #444;
    cursor: pointer;
	
	font-size:16px;
    width: 100%;
    text-align: left;
    border: none;
    outline: none;
    transition: 0.4s;
    margin-bottom:10px;
	}
/* Unicode character for "plus" sign (+) */
p.accordion:after {
    content: '˅'; 
    font-size: 10px !important;
	
    color: #777;
    float: right;
    margin-left: 5px;
}
	
	.hero_section_container .hero_content {
    color: ;
    font-size: 10px;
}
.col-xs-3	
	{
    position: relative;
    min-height: 1px;
    padding-right: 5px;
    padding-left: 5px;
}

}

/* Add a background color to the accordion if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
p.accordion.active, p.accordion:hover {
    background-color: #ddd;
	
}



/* Unicode character for "minus" sign (-) */
p.accordion.active:after {
    content: "˄"; 
}

/* Style the element that is used for the panel class */

div.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: 0.4s ease-in-out;
    opacity: 0;
    margin-bottom:10px;
}

div.panel.show {
    opacity: 1;
	padding:15px; 
    max-height: 500px; /* Whatever you like, as long as its more than the height of the content (on all screen sizes) */
}

