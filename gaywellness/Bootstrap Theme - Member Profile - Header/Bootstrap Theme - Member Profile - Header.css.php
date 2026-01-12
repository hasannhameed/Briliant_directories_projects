/* Hide Top Menu  List Your Services Option */
#link405,body > div.header > div.container > div > div.col-md-9.text-right.sm-text-center.header-right-container.nolpad.xs-hpad > div.hidden-xs > div > ul > li.header-join.hpad {
	display:none;
}
	

#link1596 {
	margin-left: 15px;
}

.gc-center-widget{
  display: flex;
  align-items: center;      /* vertical center */
  justify-content: center;  /* horizontal center */
  text-align: center;
}


/* Resize & align icon inside pill */
.multi-pill i {
    font-size: 22px;
    margin-right: 8px;
    transform: translateY(1px);
}


/* Flash / Pulse animation for chat icon */
.chat-icon {
    animation: chatPulse 1.4s infinite ease-in-out;
}

@keyframes chatPulse {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.25); /* grows slightly */
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@media only screen and (min-width: 769px) {
/* LIKE BUTTON – same structure as Chat */
.like-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;

    width: 75%;
    max-width: 3200px;
    margin: 0 auto;

    padding: 12px 22px;
    border-radius: 9999px;

    background-color: #ADC6C5;
    border: 1px solid #17718D;
    color: #0D3F4F;

    font-family: 'Nunito', sans-serif;
    font-weight: 600;
    font-size: 28px !important;

    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.25s ease;
}
}

/* ICON – visually matches text height */
.like-icon {
    font-size: 28px !important;
    line-height: 1;
    transform: translateY(2px);
    color: inherit;
}

/* HOVER */
.like-btn:hover {
    background-color: #9BB5B4;
    color: #0A3440;
}



.chat-icon {
    font-size: 32px !important;   /* larger than text */
    line-height: 1;
    transform: translateY(2px);   /* aligns baseline */
}



/* Chat Button – Primary Action */
.chat-btn {
    display: flex;
    justify-content: center;   /* centers text + icon horizontally */
    align-items: center;       /* centers vertically */
    gap: 10px;                 /* spacing between icon and text */

    width: 100%;               /* FULL WIDTH BUTTON */
    max-width: 3200px;          /* optional: prevent it from being too wide */
    margin: 0 auto;            /* centers the button itself */

    background-color: #17718D;
    color: #FFFFFF;

    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    font-size: 28px;

    padding: 12px 22px;
    border-radius: 9999px;

    text-decoration: none !important;
    border: none;
    cursor: pointer;

    transition: all 0.25s ease;
}

/* Icon same size as text */
.chat-btn i {
    font-size: 20px;     /* MATCHING the text size exactly */
    line-height: 1;
    color: inherit;      /* makes icon follow text color */
}

/* Hover */
.chat-btn:hover {
    background-color: #136276;
    color: #E6E6E6;      /* soft grey */
}


h2 {
    background-color: #aae2d3;
    border: none;
    color: #0D3F4F !important;
    padding: 10px;
    border-radius: 6px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 24px;
    margin: 4px 2px;
}

@media only screen and (max-width: 767px) {
	
	/* Hide mobile top menu on mobile */
	body > div.hidden-sm.hidden-md.hidden-lg.hidden-xl {display:none;}
	
.chat-btn {
    
    padding:0px !important;
	font-size: 22px !important;
	}
	/* LIKE BUTTON – same structure as Chat */
.like-btn {
    
    padding:0px !important;
	font-size: 22px !important;
		
	display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;

    width: 75%;
    max-width: 3200px;
    margin: 0 auto;

    padding: 12px 22px;
    border-radius: 9999px;

    background-color: #ADC6C5;
    border: 1px solid #17718D;
    color: #0D3F4F;

    font-family: 'Nunito', sans-serif;
    font-weight: 600;

    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.25s ease;
}

	.open {
		font-size: 19px !important;
		margin-bottom:5px;
	}
	
	.open-lg {
		font-size: 25px !important;
	}
	
	.multi-pill {
		font-size: 22px !important;
	}
	
	.profile-header-write-review {
		margin-top: 10px !important;
	}
}

.btn-sms {
	background-color: #FA5C2B!important;
	color: #FFF;
}

.col-sm-5  {
    padding-right: 0px
;
}

.favorite, .fa.favorite {
    background: rgb(255, 255, 255)!important;
   
}

.profile-header-write-review a.btn {
    cursor: pointer !important;
}

.author-phone-pointer > span {
    cursor: pointer !important;
}



.favorite span, .fa.favorite span {
    font-size: 20px!important;
}


.list-subs-profile a {
    margin-bottom: 5px!important;
   
}