<script>
$(".member-profile-tabs ul li a").click(function() {
    tabsTop = $(".member-profile-tabs").offset().top;
    positionTop = $(window).scrollTop();
    if(tabsTop < positionTop){
        $('html, body').animate({
        scrollTop: $(".member-profile-tabs").offset().top
    }, 300);
    }
});
</script>