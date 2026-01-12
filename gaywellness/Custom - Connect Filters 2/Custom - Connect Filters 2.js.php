<script>
(function () {
  // ===== MAIN TOGGLE(S) – "Their Interests" =====
  var mainHeaders = document.querySelectorAll('.gw-interests-header');

  mainHeaders.forEach(function (header) {
    // Find the container for THIS instance
    var container = header.closest('.gw-interests');
    if (!container) return;

    var mainBody  = container.querySelector('.gw-interests-body');
    var arrowWrap = container.querySelector('.gw-interests-arrow i');

    // Start CLOSED
    if (mainBody) {
      mainBody.style.display = 'none';
    }
    if (arrowWrap) {
      arrowWrap.className = 'fa fa-chevron-right';
    }

    header.addEventListener('click', function () {
      if (!mainBody) return;
      var hidden = (mainBody.style.display === 'none');
      mainBody.style.display = hidden ? 'block' : 'none';

      if (arrowWrap) {
        arrowWrap.className = hidden
          ? 'fa fa-chevron-down'
          : 'fa fa-chevron-right';
      }
    });
  });

  // ===== SUB-GROUP TOGGLES (Arts & Culture, Career, etc.) =====
  var groups = document.querySelectorAll('.gw-interests .gw-interest-group');

  groups.forEach(function (group) {
    var header = group.querySelector('.gw-interest-group-header');
    var body   = group.querySelector('.gw-interest-group-body');
    var icon   = group.querySelector('.gw-interest-group-toggle i');

    if (!header || !body || !icon) return;

    // Default CLOSED
    body.style.display = 'none';
    icon.className = 'fa fa-plus';

    header.addEventListener('click', function (e) {
      // Don’t trigger the main "Their Interests" toggle
      e.stopPropagation();

      var hidden = (body.style.display === 'none');
      body.style.display = hidden ? 'block' : 'none';
      icon.className = hidden ? 'fa fa-minus' : 'fa fa-plus';
    });
  });

})();
</script>
