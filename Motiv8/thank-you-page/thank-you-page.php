<div style="margin-top:4rem;">
<?php
        if (isset($_GET['cu']) && $_GET['cu'] == 'active') {
            $conf_text = "Head into your account to view your Promo Codes.";
            $redirect_link = "https://www.motiv8search.com/account/my-promo";
            $conf_btn_text = "Go to Account";
        } else {
            $conf_text = "Head into your account and add content to appear on the event advertisment. ";
            $redirect_link = "https://www.motiv8search.com/account/add-supplier-card/view";
            $conf_btn_text = "Go to Account";
        }
        

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      title: 'Registration Complete!',
      text: ' <?php echo $conf_text; ?> ',
      icon : 'success',
      showCancelButton: false,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: ' <?php echo $conf_btn_text; ?> '
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = ' <?php echo $redirect_link; ?> '; 
      }
    })
  });
</script>
<?php 
/*
<!--<p style="text-align: center;"><img src="https://bdgrowthsuite.com/images/0289ec7e62855bbcabcf1bef89377172ce14e6c0.jpg"
        style="width: 300px;" class="fr-dib" width="300"></p>-->

        <p style="text-align: center;"><span style="font-size: 72px;"><strong><span style='font-family: "Roboto";'>Registration Complete!</span></strong></span></p>

<p dir="ltr"
    style="color: rgb(80, 0, 80); font-family: Roboto; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; line-height: 1.38; text-align: center; margin-top: 0pt; margin-bottom: 50pt;"
    id="isPasted"><span
        style="font-size: 72px; font-family: Calibri; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;"><?php if (isset($_GET['cu']) && $_GET['cu']=='active') { echo "Head into your account to view your Promo Codes."; }else { echo "Head into your account and add content to appear on the event advertisment. ";} ?></span></p>



<p dir="ltr"
    style="color: rgb(80, 0, 80); font-family: Roboto, font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; line-height: 1.38; text-align: center; margin-top: 0pt; margin-bottom: 8pt;">

    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "ID parameter not found in the URL.";
    } ?>
    

    <span style="font-size: 30px; font-family: Roboto; color: rgb(0, 0, 0); background-color: transparent; font-weight: 400; font-style: normal; font-variant: normal; text-decoration: none; vertical-align: baseline; white-space: pre-wrap;">
     <? if (isset($_GET['cu']) && $_GET['cu']=='active') { ?>
        <a href="https://www.motiv8search.com/account/my-promo" target="_blank" class="btn-lg btn btn-success booth_btn" style="color: #ffffff;font-size:20px;" rel="noopener noreferrer">
        Go to Account
        </a>
        <? }else{ ?>
        <!--<a href="https://www.motiv8search.com/account/edit-supplier-card/edit?id=<?php echo $id; ?>" target="_blank" class="btn-lg btn btn-success booth_btn" style="color: #ffffff;font-size:20px;" rel="noopener noreferrer">
        Continue to edit your supplier card
        </a>-->
		<a href="https://www.motiv8search.com/account/add-supplier-card/view" target="_blank" class="btn-lg btn btn-success booth_btn" style="color: #ffffff;font-size:20px;" rel="noopener noreferrer">
        Go to Account
        </a>
        <? } ?>
    </span>
</p> 
*/
?>
</div>