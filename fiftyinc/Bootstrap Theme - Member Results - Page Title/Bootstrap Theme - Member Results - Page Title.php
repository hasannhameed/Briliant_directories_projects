<?php
if ($page[h1] == "" && $_ENV[error] == "") {
    if (($_REQUEST[location_value] != "" && !strstr($_REQUEST[location_value], '|')) || $_REQUEST[q] != "") {
        if ($_REQUEST[location_value] != "" && $_REQUEST[q] != "") {
            $page[h1] .= "$_GET[q] $label[search_results_location_operand] $bsearch[location_display_value]";
            $page[title] = ucwords($page[h1]);
        } else if ($_GET[location_display_value] != "") {
            $page[h1] = "$label[default_search_listings_text] in $_GET[location_display_value]";
            $page[title] = ucwords($page[h1]);
        } else if ($_GET[location_value] != "") {
            $page[h1] = "$label[default_search_listings_text] in $_GET[location_value]";
            $page[title] = ucwords($page[h1]);
        } else if ($_GET[q] != "" && $_GET[location_value] == "") {
            $page[h1] = "Results for: $_GET[q]";
            $page[title] = $page[h1];
        } else {
            $page[h1] = "Results ";
        }
    }
}
?>

<?php if ($searcherror != "") { ?>	
    <h1>Your search returned 0 results</h1>
<?php } else { ?>
    <div class="member_results_header">
 
        <h1 class="bold nomargin"><?php echo stripslashes($page[h1]); ?></h1>
        <?php if ($page[h2] != "") { ?>
            <h2 class="tmargin"><?php echo stripslashes($page[h2]); ?></h2>
        <?php } ?>
    </div>
<?php } ?>
<?php echo widget("list_members_schema"); ?>
