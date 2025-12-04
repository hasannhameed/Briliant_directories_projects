<script>
    <?php
    if(checkIfMobile(true) && $dc['sidebar_position_mobile'] == 'top' && $page['seo_type'] != 'data_post'){?>
    let sidebarPositionTop = document.querySelectorAll('.content_w_sidebar > div');
    if (typeof sidebarPositionTop != 'undefined' && sidebarPositionTop.length > 0) {
        sidebarPositionTop[0].classList.remove('col-md-push-4', 'col-md-push-3', 'col-md-pull-8', 'col-md-pull-9');
        sidebarPositionTop[1].classList.remove('col-md-push-4', 'col-md-push-3', 'col-md-pull-8', 'col-md-pull-9');
        let sidebarHTML = sidebarPositionTop[1];
        sidebarPositionTop[1].remove();
        sidebarPositionTop[0].insertAdjacentElement('beforebegin', sidebarHTML);
    }
    <?php }?>
</script>