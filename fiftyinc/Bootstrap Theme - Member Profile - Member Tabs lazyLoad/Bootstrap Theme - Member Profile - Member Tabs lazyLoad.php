<div role="tabpanel" class="tmargin">
<?php if (is_array($tabs)) { ?>
    <ul class="nav nav-tabs" role="tablist">
        <?php 
        foreach ($tabs as $key => $tab) {
			
            if ($data_results[$key]['total'] > 0) {
                if ($data_results[$key]['data_filename'] == "specialities") { 
                    if ($subscription['hide_specialties'] == 0) { 
                        $tabsnum++;?>
                        <li <?php $ti++; if ($ti == 1) { ?> class="active hideMyTab"<?php } ?>>
                            <a href="#div<?php echo $key;?>"
                                            rel=" nofollow"
                                            aria-controls="t<?php echo $key;?>" role="tab"
                                            data-toggle="tab">
                                <?php echo eval("?>" . $tab . "<?");?>
                                <?php echo $dc['data_type'];?>
                                <?php if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) {
                                    echo " (<small>" . $data_results[$key]['total'] . "</small>)";
                                }?> 
                            </a>
                        </li>
                    <?php }
                    
                } else {
                    $tabsnum++; ?>
                    <li <?php $ti++; if ($ti == 1) { ?> class="active hideMyTab"<?php } ?>>
                        <a href="#div<?php echo $key;?>"
                                        rel=" nofollow"
                                        aria-controls="t<?php echo $key;?>" role="tab"
                                        data-toggle="tab">
                            <?php echo eval("?>" . $tab . "<?");?>
                            <?php echo $dc['data_type'];?>
                            <?php if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) {
                                echo " (<small>" . $data_results[$key]['total'] . "</small>)";
                            }?> 
                        </a>
                    </li>
                <?php 
				}
            }
        
        }

        if ($tabsnum == 1) {
            echo '<style> .hideMyTab{ display:none !important;}</style>';
        } ?>
    </ul>
    <div class="tab-content">
        <?php if (is_array($data_results)) {

            foreach ($data_results as $key => $value) {

                if ($data_results[$key]['total'] > 0 && array_key_exists($key, $tabs)) { ?>

                    <div id="div<?php echo $key;?>" role="tabpanel" class="tab-pane <?php if ($activediv < 1) { ?>active<?php $activediv++;}?>">
                        <?php 
                            echo eval("?>" . $value['profile_header'] . "<?");
                            echo replaceChars($w, $value['results']);
                            echo eval("?>" . $dcProfileFooter[$data_results[$key]['data_type']] . "<?");

                            if ($data_results[$key]['total'] > $data_results[$key]['end'] && $data_results[$key]['data_type'] != 10) { ?>
                                <div id="clickToLoadMore-<?php echo $data_results[$key]['data_filename']; ?>" class="text-center loadContainer">
                                    <button class="btn btn-default btn-block clickToLoadMoreBtn" href="#div<?php echo $key;?>" type="button"
                                            data-query="<?php echo $data_results[$key]['sqlquery'];?>"
                                            data-total="<?php echo $data_results[$key]['total'];?>"
                                            data-filename="<?php echo $data_results[$key]['data_filename'];?>"
                                            data-start="0"
                                            data-formname="<?php echo $data_results[$key]['form_name'];?>"
                                            data-finalDate="<?php echo $data_results[$key]['finalDate'];?>"
                                            data-finalDate2="<?php echo $data_results[$key]['finalDate2'];?>"
                                            data-end="<?php echo $data_results[$key]['end'];?>"
                                            data-type="<?php echo $data_results[$key]['data_type'];?>"
                                            rel=" nofollow"
                                            aria-controls="t<?php echo $key;?>" role="tab"
                                            data-toggle="tab">
                                        <?php echo $label['lazyLoadBtnText']; ?>
                                    </button>
                                </div>
                            <?php 
                            }
                        ?>
                    </div>
                <?php }
            }
        } ?>
    </div>
<?php } ?>
</div>
<?php echo widget("Bootstrap - Search - Lazy Loader tabs", "", $w['website_id'], $w); ?>