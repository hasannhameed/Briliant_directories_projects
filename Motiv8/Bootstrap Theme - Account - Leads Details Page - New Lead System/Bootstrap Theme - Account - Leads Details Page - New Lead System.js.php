<script>
    $(".js-action").click(function () {
        $(".header, .member_sidebar, .newsletter_row, .footer, .js-action, h1, .btn-primary, .nav-tabs, .member_wizard, .insidedetails > .row, .body-content,hr,.member_wizard2").fadeOut();
        $(".member_accounts").removeClass( "col-md-10 col-md-offset-2 " ).addClass("col-md-12");
        $(".member_accounts").css("min-height","100%");
        setTimeout(function () {
            window.print();
            $(".btn.btn-primary.btn-sm").fadeIn();
        }, 1500);
    });

    $(".js-back").click(function () { 
        location.reload();
    });

    $(document).bind("keyup keydown", function(e){
        if(e.ctrlKey && e.keyCode == 80){
            $(".js-action").click();
        }
    });
</script>