<style>
    .img-row{
        border: 2px solid #e0e0e0;
        margin: 0px auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .imgsize{
        height: 300px;
        width: 300px;
    }
</style>
<?php

$user_id = (int)$user['user_id'];

$sql = "
    SELECT file 
    FROM users_photo 
    WHERE user_id = ".$user_id." 
    ORDER BY 
        CASE 
            WHEN type = 'logo' THEN 1
            WHEN type = 'photo' THEN 2
            ELSE 3
        END
    LIMIT 1
";

$res = mysql_query($sql);
$row = mysql_fetch_assoc($res);

if (!empty($row['file'])) {
    ?>
    <div class="row img-row">
        <a href="/<?php echo $user['filename'] ?>" target="_blank">
        <img class='imgsize' src="/logos/profile/<?php echo $row['file']; ?>" 
            alt="Company Logo" 
            class="img-responsive">
        <div class="clearfix"></div>
        </a>
    </div>
    <?php
}

?>
