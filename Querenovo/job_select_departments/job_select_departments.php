<?php
$dep_query = mysql_query("SELECT dep_id, dep_name FROM departments ORDER BY dep_name ASC");
$departments = [];
while ($r = mysql_fetch_assoc($dep_query)) {
    $departments[] = $r;
}
?>
<?php
$depValue = '';

if (!empty($post['department_code'])) {
    $dep_code = (int)$post['department_code'];

    $dep_str = mysql_fetch_assoc(
        mysql_query("SELECT dep_name FROM departments WHERE dep_id = {$dep_code} LIMIT 1")
    );

    if ($dep_str) {
        $depValue = $dep_code . ' - ' . $dep_str['dep_name'];
    }
}
?>

<div class="dep-pro-wrapper sub-cat-checkbox-container sub-sub">
    <label class="vertical-label">Département</label>

    <input
        type="text"
        id="depSearch"
        class="form-control dep-pro-input"
        placeholder="Ex: 75 ou Paris"
        autocomplete="off"
        value="<?= htmlspecialchars($depValue); ?>"
    >


    <div id="depResults" class="dep-pro-dropdown">
        <?php foreach ($departments as $d): ?>
            <div
                class="dep-pro-option"
                data-value="<?= $d['dep_id']; ?>"
                data-search="<?= strtolower($d['dep_id'].' '.$d['dep_name']); ?>"
            >
                <strong><?= $d['dep_id']; ?></strong><strong>-</strong>
                <span><?= $d['dep_name']; ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <input type="hidden" name="department_code[]" id="selectedDepartment" value='<?php echo $post['department_code'] ?$post['department_code'] :''; ?>'>
</div>
<style>
    .dep-pro-wrapper {
    position: relative;
}

.dep-pro-input {
    border-radius: 10px;
    padding: 10px 12px;
}

.dep-pro-dropdown {
    display: none;
    position: absolute;
    z-index: 999;
    width: 100%;
    max-height: 260px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 12px;
    margin-top: 6px;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

.dep-pro-option {
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    gap: 10px;
    align-items: center;
    font-size: 14px;
}

.dep-pro-option strong {
    min-width: 10px;
    text-align: right;
    color: #111;
}

.dep-pro-option span {
    color: #555;
}

.dep-pro-option:hover {
    background: #f5f7fa;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input    = document.getElementById("depSearch");
    const dropdown = document.getElementById("depResults");
    const options  = dropdown.querySelectorAll(".dep-pro-option");
    const hidden   = document.getElementById("selectedDepartment");

    // Restore from URL
    const params = new URLSearchParams(window.location.search);
    const selectedDep = params.getAll("department_code[]")[0];

    if (selectedDep) {
        options.forEach(opt => {
            if (opt.dataset.value === selectedDep) {
                input.value = opt.innerText.trim();
                hidden.value = selectedDep;
            }
        });
    }

    input.addEventListener("focus", () => dropdown.style.display = "block");

    input.addEventListener("keyup", function () {
        const val = this.value.toLowerCase();
        let visible = 0;

        options.forEach(opt => {
            if (opt.dataset.search.includes(val)) {
                opt.style.display = "flex";
                visible++;
            } else {
                opt.style.display = "none";
            }
        });

        dropdown.style.display = visible ? "block" : "none";
    });

    options.forEach(opt => {
        opt.addEventListener("mousedown", function () {
            input.value = this.innerText.trim();
            hidden.value = this.dataset.value;
            dropdown.style.display = "none";
        });
    });

    document.addEventListener("click", e => {
        if (!e.target.closest(".dep-pro-wrapper")) {
            dropdown.style.display = "none";
        }
    });
});
</script>

