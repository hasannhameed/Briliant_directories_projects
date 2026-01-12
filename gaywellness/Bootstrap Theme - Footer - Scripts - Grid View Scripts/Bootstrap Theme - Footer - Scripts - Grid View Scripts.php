<?php
//custom variables for grid view on member search and features
if (addonController::isAddonActive("grid_view_search_reults") && ($page['seo_type'] == "profile_search_results" || $page['seo_type'] == "data_category") && ($wa["custom_160"] == "1" || $wa["custom_198"] == "1")) { ?>
    <script src="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/js/imagesloaded.pkgd.min.js"></script>
<?php if ($wa["custom_160"] == "1") { ?>
        <script>
            setTimeout(function(){
                $('.grid-container').imagesLoaded( function() {
                    if ($(window).width() > 960) {
                        $( ".member_results .views > i.gridView" ).trigger( "click" );
                    }
                    $('.grid-container').delay(10000).addClass('visible');
                });
            }, 250);
        </script>
    <?php }

    if ($wa["custom_198"] == "1") { ?>
        <script>
			$(document).ready(function(){
				$('.grid-container').imagesLoaded( function() {
					if ($(window).width() > 960) {
						$( ".feature-search .js-click" ).trigger( "click" );
					}
					$('.grid-container').delay(850).addClass('visible');
				})
			 });
        </script>
    <?php }
}
//custom variable for google map view on member search
if ($wa["custom_160"] == "2") { ?>
    <script>
        $(document).ready(function(){
            if(document.getElementById("google-pin") !== null){
                $( ".member_results .views > i.mapView" ).trigger( "click" );
            } else {
                $('.grid-container').delay(850).addClass('visible');
            }
        });
    </script>
<?php } if ($wa["custom_198"] == "2") { ?>
    <script>
        $(document).ready(function(){
            if(document.getElementById("google-pin") !== null){
                $( ".views > i.mapView" ).trigger( "click" );
            } else {
                $('.grid-container').delay(850).addClass('visible');
            }
        });
    </script>
<?php } ?>
