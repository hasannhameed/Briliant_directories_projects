<?php if ($user['faq_pricing'] != "" || $user['faq_process'] != "" || $user['faq_started'] != ""|| $user['faq_types'] != ""|| $user['faq_questions'] != "") { ?>
<div class=col-md-10 col-md-odffset-1>

<?php if ($user['faq_pricing'] != "") { ?>
<button class="accordion">What should the customer know about your pricing (e.g., discounts, fees)?</button>
<div class="panel">
  <p><?php echo $user['faq_pricing']; ?></p>
</div>
<?php } ?>
<?php if ($user['faq_process'] != "") { ?>
<button class="accordion">What is your typical process for working with a new customer?</button>
<div class="panel">
  <p><?php echo $user['faq_process']; ?></p>
</div>
<?php } ?>
<?php if ($user['faq_started'] != "") { ?>
<button class="accordion">How did you get started doing this type of work?</button>
<div class="panel">
  <p><?php echo $user['faq_started']; ?></p>
</div>
<?php } ?>
<?php if ($user['faq_types'] != "") { ?>
<button class="accordion">What types of customers have you worked with?</button>
<div class="panel">
  <p><?php echo $user['faq_types']; ?></p>
</div>
<?php } ?>
<?php if ($user['faq_questions'] != "") { ?>
<button class="accordion">What questions should customers think through before talking to you?</button>
<div class="panel">
  <p><?php echo $user['faq_questions']; ?></p>
</div>
<?php } ?>
</div><?php } ?>