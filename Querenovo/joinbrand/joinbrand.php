<?php  if($pars[0]=='join'){ 
   
    ?>
<style>
    .parent{
        margin: 0 auto;
    }
    .company-content, .brand-content {
        display: none;
    }
    .show {
        display: block !important;
    }
    .plan-selector h2 {
        cursor: pointer;
        color: #007bff;
        display: inline-block;
        margin-right: 20px;
        padding-bottom: 5px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease-in-out;
    }
    .plan-selector h2.active {
        color: #333;
        border-bottom-color: #333;
    }
     .plan-selector a{
        color: #192a56;
    }
</style>

<?php } ?>

<div class="col-sm-12 parent text-center">
    <h1>Our Membership plans</h1>
    
    <div class="plan-selector text-center">
        <h2 class="company-toggle active">Are you a company ? <a href="/join">click here</a></h2>
    </div>

    <div class="brand-content text-center col-sm-12" id="brand"> 
        <h2>Membership plan benefits for the brands</h2>
        [menu=pricing_menu_brands]
    </div>
</div>
