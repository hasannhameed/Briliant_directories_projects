<?php /*?><? if($pars[0]!="sell-products"){ ?>

<div style="display:<? if($pars[1]=="car-products")echo "block";else echo "none"; ?>">
  <p class="account-menu-title">Disclaimer</p>
  <div class="form-group">
    <textarea class="froala-editor" name="post_disclaimer" ><?=$g['post_disclaimer']?></textarea>
  </div>
</div>
<? } else{
	?>

<h2 class="tmargin tpad xs-text-center xs-center-block clearfix">Disclaimer </h2>
<div class="table-view-group clearfix">
  <li class="col-sm-12">
    <p><?=$group['post_disclaimer']?></p>
  </li>
</div>
<? }?>
<?php */?>