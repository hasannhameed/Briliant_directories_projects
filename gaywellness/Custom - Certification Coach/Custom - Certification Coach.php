  <div class="clearfix"></div>
<?php if ($user['course_name'] != "") { ?>
<div style="padding-bottom:20px; margin-top:20px;"><span class="button-header"><strong>Training &#47; Certification</strong></span><br></div>
<div style="padding-left: 0px;">


<?php if ($user['training_start_date'] != "") { ?>
<?php echo date("M Y",strtotime(transformDate($user['training_start_date'],"QB")));  ?> - <?php echo date("M Y",strtotime(transformDate($user['training_finish_date'],"QB")));  ?><br><?php } ?>
<span class="course-label" ><?php echo $user['course_name']; ?></span><br>
<i><?php echo $user['institute_location']; ?></i></div>
  <div class="clearfix"></div>
<?php } ?>