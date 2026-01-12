<?php if ($_GET['location_value']) { ?>
<?php
$pathurl = $_SERVER['REQUEST_URI'];
$path = explode("?",$pathurl);
?>
<aside>
<div class="module">
<h3>Are you interested in events near this location?</h3>
<br>
<a title="%%%get_listed_text%%%" href="/events?<?php echo $path[1]; ?>" class="btn btn-primary btn-block">View Events In This Location</a>
</div>
</aside>
<? } ?>