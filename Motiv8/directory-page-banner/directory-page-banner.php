<link rel="stylesheet" href="/directory/cdn/assets/bootstrap/css/vegas.min.css">
<script defer src="/directory/cdn/assets/bootstrap/js/vegas.min.js"></script>
<script defer>
	$(function () {
		"use strict";
		$('#first_container').vegas({
			color: 'rgb(18, 18, 18)',
			delay: 4000,
			transitionDuration: 3000,
			timer: false,
			transition: null,
			slides: [
				{ src: '/images/shutterstock_1679641663-(2).jpg' },
				{ src: '/images/shutterstock_519461983-(7).jpg' },
				{ src: '/images/shutterstock_1095301820-(1).jpg' },
				{ src: '/images/shutterstock_733812400-(1).jpg' },
				{ src: '/images/shutterstock_682503058-(1).jpg' },
			],
				init: function () {
				setTimeout(function () {
				$('#first_container').vegas('options', 'transition', 'fade');
				}, 1000);
				}
				});

				$('a.previous').on('click', function () {
				$('#first_container').vegas('options', 'transition', 'fade').vegas('previous');
				return false;
				});

				$('a.next').on('click', function () {
				$('#first_container').vegas('options', 'transition', 'fade').vegas('next');
				return false;
				});
				});
</script>