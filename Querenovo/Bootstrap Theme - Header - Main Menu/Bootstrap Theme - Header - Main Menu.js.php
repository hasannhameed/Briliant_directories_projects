<script>
	$(document).ready(function(){
		$('.navbar-header .navbar-toggle.main_menu').click(function(){
			$('.mobile-main-menu').toggleClass('opened');
		});
		$('.mobile-main-menu .sidebar-nav').find('li').each(function(){
			$(this).addClass('hasChildren');
			if ($(this).children('ul').length > 0){
				$(this).prepend('<i class="fa fa-plus" aria-hidden="true"></i>');
				$(this).find('a').after('<div class="clearfix"></div>');
				$(this).find('span').after('<div class="clearfix"></div>');
			}
			$(this).append('<div class="clearfix"></div>');
		});

		$('.mobile-main-menu .sidebar-nav li i').click(function(){
			if ($(this).parent().children('ul').length > 0){
				$(this).parent().toggleClass('sub_open');
			}
			if ($(this).hasClass('fa-plus')){
				$(this).switchClass('fa-plus','fa-minus');
			} else {
				$(this).switchClass('fa-minus','fa-plus');
			}
		});
	})

	if ($(window).width() > 740 && $(window).width() < 1100) {

		$(document).ready(function(){
			$('.tablet-menu .tablet-menu-ul').find('li').each(function(){

				if ($(this).children('ul').length > 0){
					$(this).prepend('<i class="fa fa-plus tablet-fa hidden-sm hidden-md hidden-lg" aria-hidden="true"></i>');
					$(this).find('a').after('<div class="clearfix"></div>');
					var this_link = $(this).children('a').text().replace(/[^\x00-\x7F]/g, "");;
					$(this).children('a').html(this_link);
					$(this).find('span').after('<div class="clearfix"></div>');
				}
				$(this).append('<div class="clearfix"></div>');
			});

			$('.tablet-menu .tablet-menu-ul li i').click(function(){

				if ($(this).parent().children('ul').length > 0){
					$(this).parent().toggleClass('sub_open');

					if ($(this).siblings( "ul" ).hasClass('tablet-block')){
						$(this).siblings( "ul" ).switchClass('tablet-block', 'tablet-none');
					} else {
						$(this).siblings( "ul" ).addClass( "tablet-block" );

						if ($(this).siblings( "ul" ).hasClass('tablet-none')){
							$(this).siblings( "ul" ).removeClass('tablet-none')
						}

						if ($(this).parent().siblings().children('ul').hasClass('tablet-block')) {
							$(this).parent().siblings().children('ul').switchClass('tablet-block', 'tablet-none');
							$(this).parent().siblings().children('i').switchClass('fa-minus','fa-plus');
						}

						if ($(this).parent().siblings().children('ul').children().children('ul').hasClass('tablet-block')) {
							$(this).parent().siblings().children('ul').children().children('ul').switchClass('tablet-block', 'tablet-none');
							$(this).parent().siblings().children('ul').children().children('i').switchClass('fa-minus','fa-plus');
						}

						if ($(this).siblings('ul').children('ul').children().children('ul').hasClass('tablet-block')) {
							$(this).siblings('ul').children('ul').children().children('ul').switchClass('tablet-block', 'tablet-none');
							$(this).siblings('ul').children('ul').children().children('i').switchClass('fa-minus','fa-plus');
						}

					}

				}

				if ($(this).hasClass('fa-plus')){
					$(this).switchClass('fa-plus','fa-minus');
				} else {
					$(this).switchClass('fa-minus','fa-plus');
				}
			});
		})
	}
	// Append unique ID attribute for mobile main menu links
	$('.mobile-main-menu a,.mobile-main-menu span').attr("id", function() { return $(this).attr("id") + "-mobile" });
</script>

<?php if ($wa['custom_297'] == "2") { ?>
	<script>
		$(document).ready(function() {
			$('#compact-mobile-search').on('click', function() {
				$('.header .website-search').stop(true, true).slideToggle(250);
			});
		});
	</script>
<?php } ?>

<script>
// Enterprise-mode navigation highlight logic
document.querySelectorAll('.example a').forEach(link => {

    // Check Active State Match via URL
    if (link.href === window.location.href) {
        link.classList.add('active-nav');
    }

    // Hover Event → highlight
    link.addEventListener('mouseenter', () => {
        link.classList.add('hover-nav');
    });

    // Leave Event → remove hover highlight unless active
    link.addEventListener('mouseleave', () => {
        link.classList.remove('hover-nav');
    });

});
</script>