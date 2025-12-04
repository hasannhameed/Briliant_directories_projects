<?php
/* ============================================
   Fetch French departments from your table
   ============================================ */

$departments = [];
$q = mysql_query("SELECT dep_id, dep_name FROM departments ORDER BY dep_id ASC");

if ($q) {
    while ($row = mysql_fetch_assoc($q)) {
        $departments[] = $row;
    }
}

// Load saved value when editing profile
$saved_department = isset($user['department_code']) ? $user['department_code'] : '';
?>
<p class="account-menu-title"><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Sélectionner un département</font></font></p>
<div class="form-group">
    <div class='col-sm-3 nopad text-right'>
    <label for="fr_department pull-right">Département français</label></div>
    <div class="col-sm-9">
    <select name="department_code" id="fr_department" class="form-control">
        <option value="">-- Select Department --</option>

        <?php foreach ($departments as $d): ?>
            <option value="<?php echo $d['dep_id']; ?>"
                <?php echo ($saved_department == $d['dep_id']) ? 'selected' : ''; ?>>
                <?php echo $d['dep_id'] . ' - ' . htmlspecialchars($d['dep_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    </div>
</div>
