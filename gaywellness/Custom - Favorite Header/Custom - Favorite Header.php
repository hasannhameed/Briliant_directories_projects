<?php
if (
    $user['fav_choice_1'] == "" &&
    $user['fav_choice_2'] == "" &&
    $user['fav_choice_3'] == ""
) {
    // No favorites selected — do nothing or add code here if needed
} else {
?><span class="button-header"><strong>
    
        My Top Picks in 
        <?php if ($user['city'] != "") { ?>
            <?php echo htmlspecialchars($user['city']); ?>
        <?php } else { ?>
            my city
        <?php } ?>
    </strong></span>
<?php
}
?>
