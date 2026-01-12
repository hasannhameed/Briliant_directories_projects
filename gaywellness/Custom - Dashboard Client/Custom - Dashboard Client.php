<?php
$user_data = getUser($_COOKIE['userid'],$w);
$sub = getSubscription($user_data['subscription_id'],$w);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<a href="/account/upgrade" class="action-button">
				<div class="dashboard-card blue-overide">

					<h3 class="card-title-blue">Your current plan: <span class="small member-level-name">
            <?php echo $sub['subscription_name']; ?> 
            </span> </h3>

					<p class="card-subtitle-blue">Click to Change Your Plan</p>
				</div>
			</a></div>
</div></div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4 col-xs-6">
			<a href="/account/contact" class="action-button">
				<div class="dashboard-card"><i class="fa fa-user-circle fa-2x card-icon" aria-hidden="true"></i>

					<h3 class="card-title">Manage Listing</h3>

					<p class="card-subtitle">Control your property details</p>
				</div>
			</a></div>
		<div class="col-md-4 col-xs-6">
			<a href="/account/chat_messages" class="action-button">
				<div class="dashboard-card"><i class="fa fa-comments-o fa-2x card-icon" aria-hidden="true"></i>

					<h3 class="card-title">Private<span class="desktop-space">&nbsp;</span>
						<br class="mobile-only">Chats</h3>

					<p class="card-subtitle">Secure messaging center</p>
				</div>
			</a></div>
		<div class="col-md-4 col-xs-6">
			<a href="/account/travel-scheduler" class="action-button">
				<div class="dashboard-card"><i class="fa fa-plane fa-2x card-icon" aria-hidden="true"></i>

					<h3 class="card-title">Travel Schedule</h3>

					<p class="card-subtitle">Plan your journeys</p>
				</div>
			</a></div>
		<div class="col-md-4 col-xs-6">
			<a href="/account/profile-booster" class="action-button">
				<div class="dashboard-card"><i class="fa fa-rocket fa-2x card-icon" aria-hidden="true"></i>

					<h3 class="card-title">Profile Booster</h3>

					<p class="card-subtitle">Enhance your visibility</p>
				</div>
			</a></div>
		<div class="col-md-4 col-xs-6">
			<a href="/account/resume" class="action-button">
				<div class="dashboard-card"><i class="fa fa-calendar-check-o fa-2x card-icon" aria-hidden="true"></i>

					<h3 class="card-title">Set Availability</h3>

					<p class="card-subtitle">Manage your schedule</p>
				</div>
			</a></div>
		<div class="col-md-4 col-xs-6">
			<a href="/gw-videos" class="action-button">
				<div class="dashboard-card"><i class="fa fa-certificate fa-2x card-icon" aria-hidden="true"></i>

					<h3 class="card-title">Tutorial Videos</h3>

					<p class="card-subtitle">Learn platform features</p>
				</div>
			</a></div></div></div>
