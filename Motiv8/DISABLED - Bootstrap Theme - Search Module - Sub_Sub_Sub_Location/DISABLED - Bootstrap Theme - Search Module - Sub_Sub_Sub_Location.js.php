<script>
$( document ).ready(function() {
    <?php
    if ($_GET['tid']) { ?>

        setTimeout(() => {
            $("#bd-chained").select2('val', '<?php echo $_GET['tid']; ?>');
            $('#bd-chained').trigger('change');
            setTimeout(() => {
                <?php
                if ($_GET['ttid']) { ?>
                    $("#tid").select2('val', '<?php echo $_GET['ttid']; ?>');
                <?php } ?>
            }, 500)
        }, 500);
    <?php } ?>
});
</script>