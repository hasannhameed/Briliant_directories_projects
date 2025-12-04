<?php if ($wa['custom_298'] == "0" && $wa['display_tab_behavior'] == "scroll") { ?>
    <script>
        $(document).ready(function () {
            var $tabs = $('.member-profile-tabs .profile-tabs-nav');
            // Wrap in a flex container
            var $container = $('<div class="tabs-container"></div>').insertBefore($tabs);
            $tabs.appendTo($container);

            // Dynamically add arrows
            var $leftArrow = $('<i class="fa fa-angle-left scroll-arrow left-arrow" aria-hidden="true"></i>').prependTo($container);
            var $rightArrow = $('<i class="fa fa-angle-right scroll-arrow right-arrow" aria-hidden="true"></i>').appendTo($container);

            function checkScroll() {
                // If there's horizontal overflow
                if ($tabs[0].scrollWidth > $tabs[0].clientWidth) {
                    $leftArrow.show();
                    $rightArrow.show();
                } else {
                    $leftArrow.hide();
                    $rightArrow.hide();
                }
            }

            // Initial check
            checkScroll();

            // Check on scroll
            $tabs.on('scroll', checkScroll);

            // Scroll when clicking the arrows

            $leftArrow.click(function () {
                $tabs.animate({scrollLeft: $tabs.scrollLeft() - 200}, 300);
            });

            $rightArrow.click(function () {
                $tabs.animate({scrollLeft: $tabs.scrollLeft() + 200}, 300);
            });
        });
    </script>
<?php } ?>