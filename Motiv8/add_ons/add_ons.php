<!-- Add Ons -->
<?php
if($pars[0] === 'admin' && $pars[1] === 'go.php'){
    global $sess; 
    $loggedname = $sess['admin_name'];
    $loggeduser = $sess['admin_user']; 
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["name"]!="" && isset($_POST["name"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $label = $_POST["label"];
    $color_code = $_POST["color_code"];

    $insert_sql = "INSERT INTO add_ons (`name`, `price`, `stock`, `label`, `color_code`, `description`) VALUES ('$name', '$price', '$stock', '$label', '$color_code', '$description')";

    if (mysql_query($insert_sql) === TRUE) {
        //echo "Data inserted successfully.";
    } else {
        echo "Error";
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_id"]) && $_POST["update_id"]!="") {

    $id = $_POST["update_id"];
    $name = $_POST["update_name"];
    $price = $_POST["update_price"];
    $stock = $_POST["update_stock"];
    $description = $_POST["update_description"];
    $label = $_POST["update_label"];
    $color_code = $_POST["update_color_code"];

    $update_query = "UPDATE add_ons SET `name` = '$name', `price` = '$price', `stock` = '$stock', `label` = '$label', `color_code` = '$color_code', `description` = '$description' WHERE `id` = '$id'";
	echo $update_query;

    if (mysql_query($update_query)) {
        $response = array("success" => true);
        echo json_encode($response);
    } else {
        echo "Not Updated";
    }

} else {
    if (isset($_POST["id"])) {

        $id = $_POST["id"];

        $delete_sql = "DELETE FROM add_ons WHERE id = '$id'";
        $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                               VALUES ('$loggedname', '$loggeduser', 'Add_On', '$id')");
        if (mysql_query($delete_sql)) {
            echo "Row deleted successfully.";
        } else {
            echo "Error deleting row";
        }
    }
}


$select_sql = "SELECT * FROM `add_ons` ORDER BY `add_ons`.`id` ASC";
$result = mysql_query($select_sql);

$data = array();

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $data[] = $row;
    }
}

?>

<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="add_section">
                    <h2> Manage Add Ons </h2>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#flipFlop">
                        Add New Add On
                    </button>
                </div>

                <!-- The modal -->
                <div class="modal modal_content" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                    aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="modalLabel">Create New Add On</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addonForm" method="post" action="">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="form-control" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" id="price" name="price" required>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="stock">Stock Limit</label>
                                        <input type="number" class="form-control" id="stock" name="stock" required>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="additional-add-ons">Choose Add-On Label</label>
                                            <select class="form-control" id="additional-add-ons" name="label">
                                                <option value=""></option>
                                                <option value="1"><span class="badge">Presentation Add-On</span></option>
                                                <option value="2"><span class="badge">Additional Add-Ons</span></option>
                                            </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="color-code">Choose Add-On Label Color</label>
                                        <input type="color" name="color_code" class="form-control-color" value="#0000" title="Choose Color code">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" class="form-control" name="description"
                                            required ></textarea>
                                            
                                    </div>

                                    <div class="submit_btns text-right">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <table style="width:100%" class="table">
                    <thead>
                        <tr>
                            <th class="number_cls">#</th>
                            <th class="name_cls">Name</th>
                            <th class="price_cls">Price</th>
                            <!-- <th class="stock_cls">Stock Limit</th>
                            <th class="balance_stock_cls">Used Stock</th> -->
                            <th class="price_cls">Label</th>
                            <th class="desc_cls">Description</th>
                            <th class="dlt_cls">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $index => $row) {?>
                            <tr>
                                <td class="number_cls">
                                    <?php echo $index + 1; ?>
                                </td>
                                <td class="name_cls edit_data">
                                    <?php echo $row['name']; ?>
                                </td>
                                <td class="price_cls edit_data" style="text-align: right;">£
                                    <?php echo $row['price']; ?>
                                </td>



								<!-- <td style="text-align: right;"
                                    <?php if ($row['stock'] == 0) { ?>
                                        title="Here '∞' means unlimited Add-On(s)" data-toggle="tooltip"
                                    <?php } ?>>
                                    <?php echo ($row['stock'] == 0) ? '∞' : $row['stock']; ?>
                                </td> -->

                                
								<!-- <td style="text-align: right;"><?php echo $row['balance_stock']; ?></td> -->
								<!-- <td style="text-align: right;"><?php echo $row['stock'] - $row['balance_stock']; ?></td> -->
                                <td class="label_cls edit_data">
                                    <?php
                                    if($row['label'] == 1){
                                        echo "<span class='badge'>Presentation Add-On</span>";
                                    }
                                    if($row['label'] == 2){
                                        echo "<span class='badge'>Additional Add-Ons</span>";
                                    } ?>
                                </td>
                                <td class="desc_cls edit_data">
                                    <?php echo $row['description']; ?>
                                </td>
                                <td class="dlt_cls">
                                    <button class="edit-btn btn-pr" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#editModal<?= $row['id']?>"><i
                                            class="fa fa-pencil" aria-hidden="true"></i>Edit</button>
                                    <button class="delete-btn <?php echo ($row['id'] == 91) ? 'hide' : ''; ?>" data-id="<?php echo $row['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i>Delete</button>
                                </td>
                            </tr>

                            <!-- The Edit modal -->
                            <div class="modal modal_content" id="editModal<?= $row['id']?>" tabindex="-1" role="dialog"
                                aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="modalLabel">Edit Add On</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editAddonForm" class="editAddonForm" method="post" action="">
                                                <input type="hidden" id="edit_id" name="edit_id" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="name">Name</label> <input type="text" id="edit_name"
                                                        class="form-control" name="edit_name" value="<?php echo $row['name']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type="number" class="form-control" id="edit_price" name="edit_price" value="<?php echo $row['price']; ?>">
                                                </div>
                                                <!-- <div class="form-group">
                                                    <label for="stock">Stock Limit</label>
                                                    <input type="number" class="form-control" id="edit_stock" name="edit_stock" value="<?php echo $row['stock']; ?>"  min="<?php echo $row['balance_stock']; ?>">
                                                    <small>Here '0' means unlimited Add-On(s)</small>
                                                </div> -->
                                                <div class="form-group">
                                                    <label for="additional-add-ons">Choose Add-On Label</label>
                                                    <select class="form-control" id="edit_label" name="edit_label" <?php echo ($row['id'] == 91) ? 'disabled' : ''; ?>>
                                                        <option value=""></option>
                                                        <option value="1" <?php echo ($row['label'] == 1) ? 'selected' : ''; ?>><span class="badge">Presentation Add-On</span></option>
                                                        <option value="2" <?php echo ($row['label'] == 2) ? 'selected' : ''; ?>><span class="badge">Additional Add-Ons</span></option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="color-code">Choose Add-On Label Color</label>
                                                    <input type="color" name="edit_color_code" id="edit_color_code" class="form-control-color" value="<?php echo $row['color_code']; ?>" title="Choose Color">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea id="edit_description<?= $row['id'] ?>" class="form-control edit_description"
                                                    rows="12" cols="50" name="edit_description" ><?php echo $row['description']; ?></textarea>
                                                    
                                                </div>
                                                <input type="hidden" name="update_btn">
                                                <div class="btn_last text-right">
                                                    <button type="submit" class="btn btn-primary updateAddon">Save
                                                        Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<script>
							/*$(document).ready(function () {
								CKEDITOR.replace("edit_description<?= $row['id'] ?>");
							});*/
						</script>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>-->
<script>
    /*$(document).ready(function() {
		CKEDITOR.replace("description");
    });*/
</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>




<?php } else { ?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Access Denied</h1>
        <p>You do not have the necessary permissions to view this page.</p>
      </div>
    </div>
  </div>
</section>
<?php } ?>