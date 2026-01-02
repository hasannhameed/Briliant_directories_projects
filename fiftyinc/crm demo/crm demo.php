<div><button type
="button" class="btn btn-info" data-toggle="collapse" data-target="#demo1">Settings</button>        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo2">Members</button>
<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalForm">
    Open Contact Form
</button>
<?php $db=brilliantDirectories::getDatabaseConfiguration('database');
$result = mysql($db,"select * from users_data"); ?>
<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Contact Form</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg" id="statusMsg"></p>
                <form role="form">
                    <div class="form-group">
                        <label for="tagname">Tag Name</label>
                        <input type="text" class="form-control" id="tagname" placeholder="Enter Tag name"/>
                    </div>
                   
                    <div class="form-group">
                        <label for="tagdesc">Message</label>
                        <textarea class="form-control" id="tagdesc" placeholder="Enter Tag Description"></textarea>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submitBtn" onclick="submitContactForm()">SUBMIT</button>
            </div>
        </div>
    </div>
</div>
       
<div id="demo2" class="container collapse">

  <table class="table">
    <thead>
      <tr>
      <th>S.No</th>
        <th>Member Id</th>
        <th>Member Name</th>
       <th>Membership Level</th>
    <th>Tags</th>
   <th>Lead Status</th>
   <th>Relationship level</th>
    <th>Status</th>
      </tr>
    </thead>
    <tbody>
  <?php 
  $i=1;
  while($row=mysql_fetch_assoc($result)) { ?>
      <tr>
      <td><?=$i;?></td>
        <td><?php echo $row['user_id']; ?></td>
        <td><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
       <? //echo "select * from subscription_types where `subscription_id`='$row[subscription_id]'";
       $result1 = mysql($db,"select * from subscription_types where `subscription_id`='$row[subscription_id]'");
       $row2=mysql_fetch_assoc($result1); ?>
    <td><?php echo $row2['subscription_name']; ?></td>
    <td>       <? //echo "select * from subscription_types where `subscription_id`='$row[subscription_id]'";
       $result2 = mysql($db,"select * from tags");?>
       <select class="chosen-select" id="tag">
        <? while($row3=mysql_fetch_assoc($result2)){?>
          <option value="<?=$row3[tag_name]?>"><?=$row3[tag_name]?></option>
          <? } ?>
       </select>
     
</td>
    <td><?=$row['lead_status']?></td>
    <td><?=$row['relationship']?></td>
    <td><? if($row['active']==1) echo "Not Active";
    else if($row['active']==2) echo "Active";?></td>
      </tr>
    <?php $i++;} ?>
    </tbody>
  </table>
</div>


<select name="tags" required="" placeholder="Choose Your School" id="tag-chained" autocomplete="off" class="form-control" style="display: none;" data-fv-field="tags"><option value="" hidden="">Choose Your School</option>
  <?
$row = mysql($w[database],"SELECT * FROM `school_names`");
while($r1=mysql_fetch_assoc($row)){

?>
  <option value="<?=$r1[attended_university]?>" <? if($user[attended_university]==$r1[attended_university]) echo 'selected="selected"'; ?>>
  <?=$r1[attended_university]?></option>
  <?}

?>


</select>


