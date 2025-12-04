<?php 
        $redirectUrl =  $_GET['redirect']; 
        if(!empty($redirectUrl) && strpos($redirectUrl, 'supplier-registration') !== false) {
        ?>
<script>
        $(document).ready(function(){
            // Change the text of h2 element
            $('#member_login .nomargin.member-login-h2-form-title').text('Please sign in to continue with the registration.');
        });
    </script>

<?php } ?>