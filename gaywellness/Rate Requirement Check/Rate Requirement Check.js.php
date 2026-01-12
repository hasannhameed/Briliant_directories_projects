<script>
document.getElementById('member_listing_details_320').addEventListener('submit', function (e) {
  // Grab all rate input fields by their name
  const rateInputs = [
    'thirty_minutes',
    'fourtyfive_minutes',
    'sixty_minutes',
    'seventyfive_minutes',
    'ninety_minutes',
    'onehundred_minutes',
    'onehundredtwenty_minutes',
    'thirty_minutes_out',
    'fourtyfive_minutes_out',
    'sixty_minutes_out',
    'seventyfive_minutes_out',
    'ninety_minutes_out',
    'onehundred_minutes_out',
    'onehundredtwenty_minutes_out'
  ];

  let hasValue = false;
  for (let i = 0; i < rateInputs.length; i++) {
    const field = document.getElementsByName(rateInputs[i])[0];
    if (field && field.value.trim() !== '') {
      hasValue = true;
      break;
    }
  }

  if (!hasValue) {
    e.preventDefault();
    alert('Please enter at least one rate before submitting.');
  }
});
</script>