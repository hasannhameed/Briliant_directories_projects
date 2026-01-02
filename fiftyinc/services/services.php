<?php $service_area_schema_SQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT * FROM `service_areas` WHERE user_id ='$user[user_id]'");
//$service_areas= mysql_fetch_assoc($reviewschema_SQL);
//echo "SELECT * FROM `service_areas` WHERE user_id ='$user[user_id]'";
?>

<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Service",
        "provider": {
            "@type": "LocalBusiness"
            <?php if ($user['company'] != '') { ?>,
                "name": "<?php echo $user['company'] ?>"
            <?php } ?>
        },
        "hasOfferCatalog": [
            <?php
            $get_professions = mysql_query("SELECT * FROM list_professions where `profession_id`IN($user[profession_id])");
            $index_top = 0;
            $top_count = mysql_num_rows($get_professions);
            while ($top_services = mysql_fetch_assoc($get_professions)) { ?>

                {
                    "@type": "OfferCatalog",
                    "name": "<?php echo $top_services['name'] ?>",
                    <?php
                    $rel_sub_services = mysql_query("SELECT * FROM `rel_services`, list_services where rel_services.service_id = list_services.service_id AND user_id ='$user[user_id]'  AND list_services.master_id = 0");

                    $index_sub = 0;
                    $rel_sub_services_count = mysql_num_rows($rel_sub_services);
                    if ($rel_sub_services_count > 0) { ?>

                        "itemListElement": [
                            <?php

                            $index_sub = 0;
                            $rel_sub_services_count = mysql_num_rows($rel_sub_services);

                            while ($sub_services = mysql_fetch_assoc($rel_sub_services)) {
                                $rel_sub_services_info = mysql_query("SELECT * FROM `list_services` where `service_id` ='$sub_services[service_id]' AND master_id = 0");
                                //$index_sub=0;
                                //$rel_sub_services_count= mysql_num_rows($rel_sub_services_info);
                                // echo "SELECT * FROM `list_services` where `service_id` ='$sub_services[service_id]'";
                                while ($rel_sub_services_name = mysql_fetch_assoc($rel_sub_services_info)) { ?> 
                                    {
                                        "@type": "OfferCatalog",
                                        "name": "<?php echo $rel_sub_services_name[name]; ?>",
                                        "itemListElement": [
                                        <?php
                                            $rel_sub_sub_services_info = mysql_query("SELECT * FROM `list_services` where `master_id` ='$rel_sub_services_name[service_id]' ");
                                            $index_sub_sub = 0;
                                            $rel_sub_sub_services_count = mysql_num_rows($rel_sub_sub_services_info);
                                            while ($rel_sub_sub_services_name = mysql_fetch_assoc($rel_sub_sub_services_info)) { ?> 
                                                {
                                                    "@type": "Offer",
                                                    "itemOffered": {
                                                        "@type": "Service",
                                                        "name": "<?php echo $rel_sub_sub_services_name[name]; ?>"
                                                    }
                                                }

                                                <?php

                                                $index_sub_sub++;
                                                echo ($rel_sub_sub_services_count != $index_sub_sub) ? "," : "";

                                                ?>

                                            <?php } ?>
                                        ]
                                    }


                                <?php } ?>
                                <?php

                                /*$rel_sub_services_count."__".*/ $index_sub++;
                                echo ($rel_sub_services_count != $index_sub) ? "," : "";

                                ?>
                            <?php } ?>
                        ] <? } ?>
                }
                <?php $index_top++;
                echo ($top_count != $index_top) ? "," : "";

                ?>
            <?php } ?>

        ]
    }
</script>