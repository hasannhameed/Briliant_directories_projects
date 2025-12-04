


<style>
    /* -----------------------------------------------------------
       1. LAYOUT RESET
    ----------------------------------------------------------- */
    .parent .pricing_menu {
        display: flex !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        gap: 40px !important;
        padding: 0 !important;
        list-style: none !important;
        margin-top: 40px !important;
    }
    .parent .pricing_menu::before, .parent .pricing_menu::after { display: none !important; }

    /* -----------------------------------------------------------
       2. CARD DESIGN
    ----------------------------------------------------------- */
    .parent .pricing_menu > li.col-sm-4 {
        float: none !important;
        width: 100% !important;
        flex: 1 !important;
        min-width: 320px !important;
        max-width: 450px !important;
        background: #ffffff !important;
        border: 3px solid #e2e8f0 !important; /* Thicker border */
        border-radius: 16px !important;
        padding: 40px 30px !important;
        position: relative !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important; /* Smooth pop effect */
        text-align: center !important;
        z-index: 1 !important; /* Default stack order */
    }

    /* -----------------------------------------------------------
       3. TOP ICONS (Logos)
    ----------------------------------------------------------- */
    .pricing_menu li span{
        background: #ffffff;
        border: 0;
        color: #666;
        display: block;
        font-size: 26px;
        font-weight: 600;
        line-height: 1.5em;
        padding: 5px 0 0;
    }

    .parent .pricing_menu > li.col-sm-4::before {
        content: '' !important;
        display: block !important;
        width: 64px !important;
        height: 64px !important;
        margin: 0 auto 20px auto !important;
        border-radius: 12px !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: 32px !important;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important; 
    }

    /* Blue Icon */
    .parent .pricing_menu > li.col-sm-4:first-child::before {
        background-color: #2563eb !important; 
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0v14h-2V3h2zm-4 4h-2v2h2V7zm0 4h-2v2h2v-2zm0 4h-2v2h2v-2zm8-8h-2v2h2V7zm0 4h-2v2h2v-2zm0 4h-2v2h2v-2z'/%3E%3C/svg%3E") !important;
    }

    /* Purple Icon */
    .parent .pricing_menu > li.col-sm-4:last-child::before {
        background-color: #9333ea !important; 
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M20 7.5l-8-4.5-8 4.5v9l8 4.5 8-4.5z'/%3E%3C/svg%3E") !important;
    }

    /* -----------------------------------------------------------
       4. TYPOGRAPHY
    ----------------------------------------------------------- */
    .parent .pricing_menu .title {
        display: block !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 10px !important;
        background: rgb(255 255 255);
    }

    .pricing_menu li .title {
        border-radius: 8px 8px 0 0;
        color: #fff;
        display: block;
        font-size: 34px;
        line-height: 1em;
        padding: 0px 15px;
    }

    .parent .pricing_menu .price {
        display: block !important;
        font-size: 16px !important;
        color: #64748b !important;
        font-weight: normal !important;
        margin-bottom: 5px !important;
    }

    /* Inner List */
    .parent .pricing_menu > li > ul {
        list-style: none !important;
        padding: 0 !important;
        margin: 20px 0 0 0 !important;
        text-align: left !important;
        box-shadow: 0 0 0rem rgba(0, 0, 0, .15);
    }

    .parent .pricing_menu > li > ul > li {
        position: relative !important;
        padding-left: 30px !important;
        margin-bottom: 12px !important;
        font-size: 15px !important;
        color: #334155 !important;
        border: none !important;
    }

    .parent .pricing_menu > li > ul > li::before {
        content: '✓' !important;
        position: absolute !important;
        left: 0 !important;
        color: #16a34a !important; 
        font-weight: 900 !important;
        font-size: 16px !important;
    }

    /* Hiding checkmarks for non-feature items */
    .parent .pricing_menu > li > ul > li:nth-child(1),
    .parent .pricing_menu > li > ul > li:nth-child(2),
    .parent .pricing_menu > li > ul > li:nth-child(3),
    .parent .pricing_menu > li > ul > li:nth-child(4),
    .parent .pricing_menu > li > ul > li:last-child {
        padding-left: 0 !important;
        text-align: center !important;
    }
    .parent .pricing_menu > li > ul > li:nth-child(1)::before,
    .parent .pricing_menu > li > ul > li:nth-child(2)::before,
    .parent .pricing_menu > li > ul > li:nth-child(3)::before,
    .parent .pricing_menu > li > ul > li:nth-child(4)::before,
    .parent .pricing_menu > li > ul > li:last-child::before {
        content: none !important;
    }

    /* -----------------------------------------------------------
       5. BUTTONS & HOVER EFFECTS (UPDATED)
    ----------------------------------------------------------- */
    .parent .btn {
        background-color: #0f172a !important; 
        color: #fff !important;
        border: none !important;
        width: 100% !important;
        padding: 15px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        margin-top: 20px !important;
        text-transform: none !important;
        transition: all 0.3s ease !important;
    }

    /* --- HOVER EFFECTS --- */
    
    /* 1. Expand Card (Scale), Lift, and Red Border */
    .parent .pricing_menu > li.col-sm-4:hover {
        border-color: #dc2626 !important;
        /* THIS IS THE KEY CHANGE: scale(1.05) expands it */
        transform: translateY(-10px) scale(1.05) !important; 
        box-shadow: 0 30px 40px -10px rgba(220, 38, 38, 0.2) !important;
        z-index: 10 !important; /* Keeps it on top of others */
    }

    /* 2. Scale Logo (The Icon grows even bigger) */
    .parent .pricing_menu > li.col-sm-4:hover::before {
        transform: scale(1.2) !important; 
    }

    /* 3. Button turns Red */
    .parent .pricing_menu > li.col-sm-4:hover .btn {
        background-color: #dc2626 !important;
    }
</style>


<?php  if($pars[0]=='join'){ 
   
    ?>
<style>
    .parent{
        margin: 0 auto;
    }
   
    .show {
        display: block !important;
    }
    .plan-selector h2 {
        cursor: pointer;
       
        display: inline-block;
        margin-right: 20px;
        padding-bottom: 5px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease-in-out;
        m
    }
    .plan-selector h2.active {
        color: #333;
        border-bottom-color: #333;
    }
    .plan-selector a{
        color: #192a56;
    }
</style>
<style>
    /* -----------------------------------------------------------
       HEADER TYPOGRAPHY DESIGN
    ----------------------------------------------------------- */
    
    /* 1. Main Title (Our Membership plans) */
    .parent h1 {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        font-size: 32px !important;
        font-weight: 800 !important; /* Extra Bold */
        color: #0f172a !important;   /* Dark Navy */
        margin-bottom: 12px !important;
        margin-top: 20px !important;
        text-transform: none !important; /* Prevents all-caps if your theme forces it */
        letter-spacing: -0.5px !important; /* Tighten letters slightly for modern look */
    }

    /* 2. Subtitle (Membership plan benefits...) */
    .parent h2 {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        font-size: 18px !important;
        font-weight: 400 !important; /* Regular/Light weight */
        color: #64748b !important;   /* Soft Gray-Blue */
        margin-top: 0 !important;
        margin-bottom: 50px !important; /* Adds space between text and the cards */
        line-height: 1.5 !important;
        max-width: 600px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
</style>

<?php } ?>

<div class="col-sm-12 parent text-center">
    <h1>Quel type de profil souhaitez-vous créer ?</h1>
    
    <div class="company-content text-center col-sm-12 tmargin" id="company"> 
		
        <h2>Choisissez le type de profil qui correspond le mieux à votre activité</h2>
		<div class='tmargin' style='margin-top:30px;'>
        	[menu=pricing_menu_company]
		</div>
    </div>
</div>
