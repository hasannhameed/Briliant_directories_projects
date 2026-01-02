<?php
if(isset($_POST['submit']))
{
    $suc=mysql($w['database'],"insert into car_coupon_codes set code ='".$_POST['coupon_code']."', from_date='".$_POST['from_date']."', to_date='".$_POST['to_date']."', percentage='".$_POST['percentage']."', status='".$_POST['status']."' ");
    if($suc)
    {
    //$msg="Coupon Code Added Successfully";
      
           echo "<script>
                 swal({
                    title: 'Success',
                    text: 'Coupon Code Added Successfully',
                    type: 'success',
                    html: true,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                },
                function(isConfirm){

                    if (isConfirm){
                        window.location='https://www.managemydirectory.com/admin/go.php?widget=ecommerce-coupon-codes'

                    } 
                });</script>";
    }
}
if(isset($_POST['update']))
{
    $suc=mysql($w['database'],"update  car_coupon_codes set code ='".$_POST['coupon_code']."', from_date='".$_POST['from_date']."', to_date='".$_POST['to_date']."', percentage='".$_POST['percentage']."', status='".$_POST['status']."' where id=$_POST[coupon_id] ");
    if($suc){ //$msg="Coupon Code Updated Successfully";
                 echo "<script>
                 swal({
                    title: 'Success',
                    text: 'Coupon Code Updated Successfully',
                    type: 'success',
                    html: true,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                },
                function(isConfirm){

                    if (isConfirm){
                        window.location = 'https://www.managemydirectory.com/admin/go.php?widget=ecommerce-coupon-codes';

                    } 
                });</script>";
   }
}
if($_GET['delete']!="")
{
    $suc=mysql($w['database'],"delete from car_coupon_codes where id=$_GET[delete]");
    if($suc){ 
   
       echo "<script>
                 swal({
                    title: 'Success',
                    text: 'Deleted Successfully',
                    type: 'Warning',
                    html: true,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                },
                function(isConfirm){

                    if (isConfirm){
                       window.location='https://www.managemydirectory.com/admin/go.php?widget=ecommerce-coupon-codes'

                    } 
                });</script>";
        }
 }
if($_POST['cid']!="")
{
    $suc=mysql($w['database'],"update car_coupon_codes set status=$_POST[status] where id=$_POST[cid]");

    die();
}
/*if($msg!="")
{?>
     <div class="alert alert-success fade in alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>

              <?=$msg ?>

            </div>
<? }*/

if($_GET['edit']!="")
{
    $rs=mysql($w['database'],"select * from car_coupon_codes where id=$_GET[edit]");
    $row=mysql_fetch_assoc($rs);
    
}
   if($_GET['edit']!=""){ 
         
         echo "<h3>Update Coupon Code</h3>"; 
    } 
    else{ 
        echo "<h3>Add Coupon Code</h3>"; 
    }

 ?>
<div class="code"> 
<form action="" method="post">
    <div class="row">
        <div class="col-md-4">
          <div class="form-group">
          <label>Coupon Code</label>
          <input type="hidden" name="coupon_id" value="<?=$_GET['edit']?>"/>
          <input type="text" class="form-control" name="coupon_code" placeholder="Enter Code" autocomplete="off"  value="<?=$row['code']?>" required />
          </div>
        </div>
        <div class="col-md-4">
        <div class="form-group">
        <label>Valid From</label>
        <input type="date" class="form-control" name="from_date" value="<? if(isset($_GET['edit'])) { echo date("Y-m-d",strtotime($row['from_date'])); } ?>" required />
        </div>
        </div>
        <div class="col-md-4">
        <div class="form-group">
        <label>Valid To</label>
        <input type="date" class="form-control" name="to_date" value="<?  if(isset($_GET['edit'])) {  echo date("Y-m-d",strtotime($row['to_date'])); }?>" required/>
        </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-4">
        <div class="form-group">
        <label>Percentage</label>
        <input type="text" class="form-control" name="percentage" autocomplete="off" onkeypress="return isNumber(event)" placeholder="%" value="<?=$row['percentage']?>"  required/>
        </div>
        </div>
    <div class="col-md-4">
    <div class="form-group">
    <label>Status</label>
    <select class="form-control" name="status">
    <option value="1" <? if($row['status']==1) echo "selected";?>>Enable</option>
    <option value="0" <? if($row['status']==0) echo "selected";?>>Disable</option>
    </select>
    </div>
    </div>
    <div class="col-md-4">
    <input type="submit" value="<? if($_GET['edit']!="") echo "Update"; else echo "Save";?>" style="margin-top: 26px;
    margin-left: 16px;" name="<? if($_GET['edit']!="") echo "update"; else echo "submit";?>" class="btn btn-primary btn-sm"/>
    <? if($_GET['edit']!="") {?>
    <a href="/admin/go.php?widget=ecommerce-coupon-codes" class="btn btn-danger btn-sm" style="margin-top: 26px;
    margin-left: 16px;">Back</a>
    <? }?>
    </div>
    </div>
</form>
</div>
<? if(!isset($_GET['edit'])){
?>
<div id="home" style="padding:0;margin-top: 15px;">
  <div class="inputbox">
    <div id="table-header-module">
      <?php 
        $sql = "SELECT * from car_coupon_codes order by id desc";
        $limit = 50;
        $currentpage = ($_GET['page'] > 0)?$_GET['page']:1;
        $start = ($currentpage -1)*$limit;
        $sqlquery .= " LIMIT $start,$limit ";
        $sql = preg_replace('/SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql, 1);
        $sqlquery1 = $sql.$sqlquery;
          $start +=1;
        $presults = mysql($w['database'],$sqlquery1);
        $totalr = mysql($w['database'],"SELECT FOUND_ROWS() AS `found_rows`");
        $totalr = mysql_fetch_assoc($totalr);
        $total = $totalr['found_rows'];
        $totalpages = ceil($total / $limit);
        $end = ($start - 1) + $limit;
        if ($end > $total) {
            $end = $total;
        }
        if ($total < $end) {
            $end = $total;
          }
        $page_row['meta_desc'] =($total==0)?$total." results": "Showing ".$start."-".$end." (out of ".$total.") ";
        $rowcount = mysql_num_rows($presults);
?>
<span class="cnt_msg">
      <?= $page_row['meta_desc']?>
      </span> </div>
    <table id="irow" class="regtext" border="0">
      <tbody>
        <tr class="tablehead">
         <th>Code</th>
         <th>Valid From</th>
         <th>Valid To</th>
         <th>Percentage</th>
         <th>Status</th>
         <th></th>
        </tr>
        <?php
if ($totalpages > 1) {

    
    $url ="/admin/go.php";
   

    if ($QUERY_STRING != "") {
        $QUERY_STRING = "&".str_replace("page=$currentpage","",$QUERY_STRING);
    }
    $QUERY_STRING = str_replace("&&","&",$QUERY_STRING);

    if ($currentpage > 1) {
        $prev = $currentpage - 1;
        $pagination .= "<li><a href='$url?page=".$prev.$QUERY_STRING."' rel='prev'><span aria-hidden='true'>&laquo;</span></a></li>";
        $page_before['title'] = " Page ".$currentpage." of ".$totalpages."";
       
    }
    for($i = 1; $i <= $totalpages; $i++) {

        if ($totalpages > 5) {

            if ($i >= ($currentpage - 5) && $i <= ($currentpage + 5)) {
                $show = 1;

            } else {
                $show = 0;
            }

        } else {
            $show = 1;
        }
        if ($show == 1) {

            if ($currentpage != $i) {
                $pagination .= "<li><a href='$url?page=".$i.$QUERY_STRING."'>".$i."</a></li> ";

            } else {
                $pagination .= "<li class='active'><a href='#'>".$i."</a></li></b>";
            }
        }
    }
    if ($currentpage < $totalpages) {
        $next = $currentpage + 1;
        $pagination .= "<li><a href='$url?page=".$next.$QUERY_STRING."' rel='next'><span aria-hidden='true'>&raquo;</span></a></li>"; 
        
    }

} else {
    $pagination_mini .= " Page 1 of $totalpages";
}

                while ($codes = mysql_fetch_assoc($presults)){                  
                ?>
        <tr class="locationrow tablerow " >
          <td><?=$codes['code']?></td>
            <td><?=date("Y M d",strtotime($codes['from_date']))?></td>
          <td ><?=date("Y M d",strtotime($codes['to_date']))?></td>
          <td><?=$codes['percentage']."%"?></td>
           <td>
           <label class="switch">
              <input type="checkbox" class="status_toggle" data-code="<?=$codes['id']?>" <? if($codes['status']==1) echo "checked";?> data-status="<?=$codes['status']?>">
              <span class="slider round"></span>
            </label></td><td>
            <a href="/admin/go.php?widget=ecommerce-coupon-codes&edit=<?=$codes['id']?>" class="btn btn-primary btn-xs" style="margin-right:10px">Edit</a><a href="/admin/go.php?widget=ecommerce-coupon-codes&delete=<?=$codes['id']?>" class="btn btn-danger btn-xs">Delete</a></td>
                </tr>
       
        <?php }?>
        <? if($rowcount==0){ echo "<tr><td colspan='6'>No Records Found</td></tr>";}?>
      </tbody>
    </table>
    <div class="clearfix tmargin"></div>
    <ul class="pagination pagination-md pull-right">
      <?=$pagination?>
    </ul>
    <div class="clearfix clearfix-md"></div>
  </div>
</div>
<? } ?>
<script>
$(document).ready(function(e) {
    $(".status_toggle").change(function(e){
        var st=0;
        var el=$(this);
        if(el.data('status')==1)  st==0;
        else st=1;
        el.attr("data-status",st);
                  $.ajax({
                            url: "/admin/go.php?widget=ecommerce-coupon-codes",
                            type: "POST",
                            data:{'cid':el.data('code'),'status':st},
                            success: function(data){
                            }
                  });
        
        });
});
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
