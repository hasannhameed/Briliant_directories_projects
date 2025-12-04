<div class="form-group">
    <label for="event">Choose Event</label>
    <select name="event_id" class="event_id form-control" id="event_names_select">
        <option value="" disabled selected>Select Event</option>
        <?php
        $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
        while ($row = mysql_fetch_assoc($selectquery)) {
            echo '<option value="' . $row['post_id'] . '">' . $row['post_title'] . '</option>';
        }
        ?>
    </select>
</div>