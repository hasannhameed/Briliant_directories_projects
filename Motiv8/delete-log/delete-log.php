<div class="module nobmargin well"> 
    <h1 class="tab-head">Activity Logs</h1>

<?php
/* ======================= FILTERS ======================= */
$keyword  = isset($_GET['search']) ? mysql_real_escape_string($_GET['search']) : '';
$userType = isset($_GET['type'])   ? mysql_real_escape_string($_GET['type'])   : '';

$where = "WHERE 1=1";
if ($keyword != '') {
    $where .= " AND (
        action LIKE '%$keyword%' OR
        reference_table LIKE '%$keyword%' OR
        user_name LIKE '%$keyword%' OR
        description LIKE '%$keyword%'
    )";
}
if ($userType != '') {
    $where .= " AND user_type = '$userType'";
}

/* ======================= PAGINATION CONFIG ======================= */
$limit = 20; // 20 rows per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;

$totalResult = mysql_query("SELECT COUNT(*) AS total FROM activity_log $where");
$totalRow    = mysql_fetch_assoc($totalResult);
$total       = $totalRow['total'];
$totalPages  = ceil($total / $limit);

$start = ($page - 1) * $limit;

/* ======================= FETCH PAGINATED LOGS ======================= */
$result = mysql_query("SELECT * FROM activity_log $where ORDER BY id DESC LIMIT $start, $limit");

// Keep filters for future pagination URLs
$queryString = "&search=$keyword&type=$userType";
?>

<!-- ======================= FILTER HTML ======================= -->
<div class="row" style="margin-bottom:15px;">

    <div class="col-md-4">
        <input type="text" id="filterSearch" class="form-control" placeholder="Search logs..." value="<?php echo $keyword; ?>">
    </div>

    <div class="col-md-3">
        <select id="filterType" class="form-control">
            <option value="">All User Types</option>
            <option value="Admin" <?php if($userType=='Admin') echo 'selected'; ?>>Admin</option>
            <option value="User"  <?php if($userType=='User')  echo 'selected'; ?>>User</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class='btn btn-block btn-primary search'>Search</button>
    </div>
</div>

<!-- ======================= TABLE ======================= -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Action</th>
            <th>Table</th>
            <th>Ref ID</th>
            <th>Description</th>
            <th>IP</th>
            <th>User Agent</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <?php while ($row = mysql_fetch_assoc($result)) { 
    $user_data = getUser($row['user_id'], $w);
    $user_full_name = $user_data['first_name'] . ' ' . $user_data['last_name'];
?>
<tr>
    <!-- ID -->
    <td class="text-center">
        <span class="label label-item label-default" style="font-size:12px;"><?php echo $row['id']; ?></span>
    </td>

    <!-- USER -->
    <td class="text-center">
        <strong class="label label-item label-item"><?php echo ($row['user_type'] == 'admin') ? ($row['user_name'] ?: 'Admin') : $user_full_name; ?></strong>
        <br>
        <br>
        <span class="label label-item label-info">ID: <?php echo $row['user_id']; ?></span>
    </td>

    <!-- TYPE -->
    <td class="text-center">
        <?php echo ($row['user_type'] == 'admin') 
            ? "<span class='label label-item label-danger' style='font-size:12px;'>ADMIN</span>"
            : "<span class='label label-item label-success' style='font-size:12px;'>USER</span>"; ?>
    </td>

    <!-- ACTION -->
    <td class="text-center">
        <span class="label label-item label-primary" style="font-size:12px;">
            <?php echo htmlspecialchars($row['action']); ?>
        </span>
    </td>

    <!-- TABLE -->
    <td class="text-center">
        <span class="label label-item label-warning" style="font-size:12px;">
            <?php echo htmlspecialchars($row['reference_table']); ?>
        </span>
    </td>

    <!-- REF ID -->
    <td class="text-center">
        <span class="label label-item label-default" style="font-size:12px;">
            <?php echo $row['reference_id']; ?>
        </span>
    </td>

    <!-- DESCRIPTION -->
    <td class="text-center">
        <span class="label label-item label-info" title="<?php echo htmlspecialchars($row['description']); ?>" style="font-size:12px; display:inline-block; max-width:120px; overflow:hidden; text-overflow:ellipsis;">
            <?php echo htmlspecialchars(substr($row['description'], 0, 30)); ?>...
        </span>
    </td>

    <!-- IP -->
    <td class="text-center">
        <span class="label label-item label-default" style="font-size:12px;">
            <?php echo $row['ip_address']; ?>
        </span>
    </td>

    <!-- USER AGENT -->
    <td class="text-center">
        <span class="label label-item label-default" title="<?php echo htmlspecialchars($row['user_agent']); ?>" style="font-size:12px;">
            <?php echo substr($row['user_agent'],0,15).'...'; ?>
        </span>
    </td>

    <!-- TIME -->
    <td class="text-center">
        <span class="label label-success label-item" style="font-size:12px;">
            <?php echo date('H:i:s', strtotime($row['created_at'])) . '<br>' . date('d-M-Y', strtotime($row['created_at'])); ?>
        </span>
    </td>
</tr>
<?php } ?>

    </tbody>
</table>

<!-- ======================= PAGINATION ======================= -->
<?php if ($totalPages > 1): ?>
<nav aria-label="Page navigation" style="text-align:center;">
    <ul class="pagination">
        <li class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a href="?widget=delete-log&page=<?php echo $page-1 . $queryString; ?>">Prev</a>
        </li>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <a href="?widget=delete-log&page=<?php echo $i . $queryString; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <li class="<?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a href="?widget=delete-log&page=<?php echo $page+1 . $queryString; ?>">Next</a>
        </li>
    </ul>
</nav>
<?php endif; ?>

</div>

<!-- ======================= JS FILTER TRIGGER ======================= -->
<script>
$('.search').on('click', function() {
    var search = $('#filterSearch').val();
    var type   = $('#filterType').val();
    window.location.href = '?widget=delete-log&search=' + encodeURIComponent(search) + '&type=' + encodeURIComponent(type);
});
</script>


<style>
.description-box { 
    position: relative; 
    display: inline-block; 
    cursor: pointer;
 }
.description-box .tooltip-content {
    visibility: hidden;
    opacity: 0; 
    width: 300px; 
    background-color: #333; 
    color: #fff;
    text-align: left; 
    border-radius: 5px; 
    padding: 10px; 
    position: absolute; 
    z-index: 100;
    bottom: 125%; 
    left: 50%; 
    transform: translateX(-50%); 
    transition: opacity 0.3s; 
    white-space: normal;
}
.description-box:hover .tooltip-content { 
    visibility: visible; 
    opacity: 1;
 }
 .pagination>li{
    display: flex;
    width: 40px;
 }
 .pagination>li>a, .pagination>li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    line-height: 1.42857143;
    text-decoration: none;
    color: #428bca;
    background-color: #fff;
    border: 1px solid #ddd;
    margin-left: -1px;
    width: 100%;
}
.pagination {
    display: flex;
    flex-wrap: wrap;
    padding-left: 0;
    margin: 20px 0;
    border-radius: 4px;
}
</style>
<style>
    
</style>