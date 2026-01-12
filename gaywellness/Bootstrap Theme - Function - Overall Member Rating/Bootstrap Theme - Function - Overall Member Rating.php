<?php
$results = $_ENV['results'];
if ($results['total']) {
    if (stristr($results['rating'], ".")) {
        $dec = explode(".", $results['rating']);
    }

    if ($dec[1] > 0) {
        $emptystars = 5 - $dec[0] - 1;
    } else {
        $emptystars = 5 - $results['rating'];
    }
    $amountStars = $results['rating'];

    if ($dec[1] > 7) {
        $amountStars = $results['ratingint'];
    }
    if ($dec[1] < 3) {
        $emptystars = 5 - $results['ratingint'];
    }
    ?>

    <div class="star_rating">
		<span class="the-star-icons">
			<?php for ($i = 1; $i <= $amountStars; $i++) { ?>
                <i class="fa fa-star"></i>
            <?php } ?>
            <?php if ($dec[1] > 2 && $dec[1] < 8) { ?>
                <i class="fa fa-star-half-o"></i>
            <?php } ?>
            <?php for ($i = 1; $i <= $emptystars; $i++) { ?>
                <i class="fa fa-star-o"></i>
            <?php }

            ?>
		</span>
        <span class="small inline-block the-rating-text">
			<span class="the-average-rating inline-block">
				%%%profile_rated%%% <?php echo $results['rating'] ?>/5
			</span>
			<span class="the-review-count inline-block">
				<a id="btnReviews"><u><strong>(<?php echo $results['total'] ?><?php if ($results['total'] != 1) { ?><?php } else { ?><?php } ?>)</strong></u></a>
			</span>
		</span>
    </div>
<?php } ?>
