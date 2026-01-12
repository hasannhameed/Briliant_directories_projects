<?php if ($user['website'] != "") { ?><div class="module">


				<a itemprop="url" class="weblink" title="website" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> target="_blank" href="<?php echo $user['website']; ?>">
					<?php $user['website'];e ?>
<span class="btn btn-info btn-block font-sm-4 bold">Visit Website</span>

				</a>
		
</div><?php } ?> 