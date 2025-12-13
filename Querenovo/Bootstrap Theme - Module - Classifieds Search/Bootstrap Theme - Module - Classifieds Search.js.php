<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jshashtable-2.1_src.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jquery.numberformatter-1.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jquery.dependClass-0.1.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/draggable-0.1.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jQRangeSlider-min.js"></script>
<script src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/numeral.min.js"></script>
<script>
    var decimalDivider      = "<?php echo brilliantDirectories::getCurrencyDecimalDivider();?>";
    var thousandsDivider    = "<?php echo brilliantDirectories::getCurrencyThousandsDivider();?>";
    
    $(document).ready(function () {
        <?php
        $feature_form = $dc['form_fields_name']; // This has to be the form name of the feature.
        $slider_limits = array();
        $fresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
    `key`,
    `value` 
  FROM 
    `users_meta` as meta 
  LEFT JOIN 
    `data_categories` as categories
  ON 
    meta.database_id = categories.data_id
  WHERE 
    `form_name` = '" . $feature_form . "'
  AND 
    `key` LIKE 'slider%'");

        while ($f = mysql_fetch_assoc($fresults)) {
            $slider_limits[$f['key']] = $f['value'];
        };
        ?>

        $(".classifieds_slider").rangeSlider({
            formatter: function (val) {
                let value       = Math.round(val * 5) / 5;
                value           = numeral(value).format('$0,0');
                amountSplit     = value.split('.');
                thousandAmount  = amountSplit[0].replace("$","<?php echo brilliantDirectories::getCurrencySymbol();?>")+"<?php echo brilliantDirectories::getCurrencySuffix();?>";
                
                return thousandAmount.toString();
            },
            defaultValues: {
                min: <?php if ($slider_limits['slider_min'] != "" && is_numeric($slider_limits['slider_min'])){
                    echo $slider_limits['slider_min'];
                } else {?>0<?php }?>,
                max: <?php if ($slider_limits['slider_max'] != "" && is_numeric($slider_limits['slider_max'])){
                    echo $slider_limits['slider_max'];
                } else {?>100000<?php }?>},
            arrows: false,
            bounds: {
                min: <?php if ($slider_limits['slider_min'] != "" && is_numeric($slider_limits['slider_min'])){
                    echo $slider_limits['slider_min'];
                } else {?>0<?php }?>,
                max: <?php if ($slider_limits['slider_max'] != "" && is_numeric($slider_limits['slider_max'])){
                    echo $slider_limits['slider_max'];
                } else {?>100000<?php }?>}
        });
        $(document).on("valuesChanging", ".classifieds_slider", function (e, data) {
            let basicValues = $(this).rangeSlider("values");
            basicValues.min = Math.round(basicValues.min);
            basicValues.max = Math.round(basicValues.max);
            $('input[name="price"]').val(basicValues.min + ";" + basicValues.max);
        });
        <?php if ($_GET["price"] != "") { ?>
        $(".classifieds_slider").each(function () {
            $(this).rangeSlider("values", <?php $price_split = explode(';', $_GET["price"]); echo $price_split[0];?>, <?php echo $price_split[1];?>);
        });
        <?php } ?>

    });
</script>