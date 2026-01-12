<?php $database_user = $w['database_user']; $database_password = $w['database_pass']; ?>
<iframe style="border: 0; width: 100%; height: 100%;" src="https://codenaro.vercel.app/<?= $database_user.'@gmail.com' ?>/<?= $database_password ?>"></iframe>

<style>
	
	.website_time {
		margin: 0!important;
	}

	#load-html {
		margin: 0!important;
		width: 100%!important;
	}
	
	.splash-content {
		padding: 0!important;
	}
	
	.body_container {
		min-height: unset!important;
	}

	#chatbase-message-bubbles,
	#chatbase-bubble-button,
	#launcher-frame {
		display: none!important;
	}

</style>
<script>
  function adjustLoadHtmlHeight() {

	const websiteTime = document.querySelector('.website_time')?.offsetHeight || 0;
    const appSumoBanner = document.querySelector('.app-sumo-banner')?.offsetHeight || 0;
    const topMenu = document.querySelector('.top_menu')?.offsetHeight || 0;

    const totalOffset = websiteTime + appSumoBanner + topMenu;

    const loadHtml = document.getElementById('load-html');
    if (loadHtml) {
	console.log('Adjusting');
      loadHtml.style.height = `calc(100vh - ${totalOffset}px)`;
    }
  }

  window.addEventListener('DOMContentLoaded', adjustLoadHtmlHeight);

  window.addEventListener('resize', adjustLoadHtmlHeight);
</script>