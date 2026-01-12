<script>
    $('.view_phone_number_header').click(function(event){
        event.preventDefault();
        $(this).hide();
        $('.view_phone_number').hide();
        $('.phone_number_header').css("display","block");

    })
</script>
<?php
if(addonController::isAddonActive('user_statistics_addon') && $subscription['profile_statistics'] > 0){
    addonController::showWidget('user_statistics_addon',"9af3ef4d205f5d61a1e7861e47f38603");
} ?>
