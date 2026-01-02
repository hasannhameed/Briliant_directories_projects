<?php 
	if (isset($_GET)) {
		if (isset($_GET['dashboard'])) {
			$init_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SHOW COLUMNS FROM `users_data` LIKE 'dashboard_init'"); 
			$is_init = (mysql_num_rows($init_sql))?TRUE:FALSE;
			if (isset($_POST)) {
				if (isset($_POST['init'])) {
					if (!$is_init) {
						$make_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"ALTER TABLE `users_data` ADD `dashboard_init` VARCHAR(255) NOT NULL DEFAULT '1'");
						$create_tour_table_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
								CREATE TABLE `dashboard_tour` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `selector` VARCHAR(255) NOT NULL , `text` VARCHAR(255) NOT NULL , `priority` VARCHAR(255) NOT NULL , `active` VARCHAR(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`)) ENGINE = MyISAM");
						$jquery_selector1 = "document.querySelector('.member_sidebar')";
						$jquery_selector2 = "$('.dashboard-publish-content').siblings('.col-md-4')[0]";
						$jquery_selector3 = "$('.dashboard-publish-content').siblings('.col-md-4')[1]";
						$jquery_selector4 = "$('.dashboard-publish-content').siblings('.col-md-4')[2]";
						$create_tour_table_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
								INSERT INTO `dashboard_tour`(`selector`, `text`, `priority`)
								VALUES(
								    '".$jquery_selector1."',
								    'This is your dashboard navigation.',
								    '1'
								),(
								    '".$jquery_selector2."',
								    'Manage your account here.',
								    '2'
								),(
								    '".$jquery_selector3."',
								    'See your account details.',
								    '3'
								),(
								    '".$jquery_selector4."',
								    'Scan this QR code to share .',
								    '4'
								)
							");
						echo "INSERT INTO `dashboard_tour`(`selector`, `text`, `priority`)
								VALUES(
								    '".$jquery_selector1."',
								    'This is your dashboard navigation.',
								    '1'
								),(
								    '".$jquery_selector2."',
								    'Manage your account here.',
								    '2'
								),(
								    '".$jquery_selector3."',
								    'See your account details.',
								    '3'
								),(
								    '".$jquery_selector4."',
								    'Scan this QR code to share .',
								    '4'
								)";
					}
					?><script>//window.location = "/admin/go.php?widget=dashboard_initialization_plugin&dashboard";</script><?php
				}
			}
			$init_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SHOW COLUMNS FROM `users_data` LIKE 'dashboard_init'"); 
			$is_init = (mysql_num_rows($init_sql))?TRUE:FALSE;

			if (!$is_init) { ?>
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-center">The plugin is not istalled yet.</h2>
						<p class="text-center">Click the botton bellow to install</p>
						<form action="/admin/go.php?widget=dashboard_initialization_plugin&dashboard" method="post">
							<input type="hidden" name="init" value="1">
							<p class="text-center"><button type="submit" class="btn btn-primary">Install now</button></p>
						</form>
					</div>
				</div>
			<?php } else {
				echo "Installed";
			}
		}
	}
 ?>
<?php 
	$check_user_sql = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT dashboard_init FROM users_data WHERE user_id = '".$_COOKIE['userid']."'");
	$check_user = mysql_fetch_assoc($check_user_sql);

	$is_new_user = ($check_user['dashboard_init'] != '')?TRUE:FALSE;

 ?>
 <?php if ($is_new_user): ?>
 	<script>
 	    $(document).ready(function startIntro() {
 	        var intro = introJs('body');
 	        intro.setOptions({
 	            steps: [
 	                      {
 	                          element: document.querySelector('.member_sidebar'),
 	                          intro: 'This is a tooltip.'
 	                      },
 	                      {
 	                          element: $('.dashboard-publish-content').siblings('.col-md-4')[0],
 	                          intro: 'More features, more fun.'
 	                      },
 	                      {
 	                          element: $('.dashboard-publish-content').siblings('.col-md-4')[1],
 	                          intro: 'Another tooltip.'
 	                      },
 	                      {
 	                          element: $('.dashboard-publish-content').siblings('.col-md-4')[2],
 	                          intro: 'Another tooltip.'
 	                      }
 	                ]
 	        });
 	        intro.start();

 	    });
 	</script>
 <?php endif ?>



