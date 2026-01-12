<script>document.addEventListener('DOMContentLoaded', function () {
  var headers = document.querySelectorAll('.gw-filter-header');

  headers.forEach(function (header) {
    header.addEventListener('click', function () {
      // find the section this header belongs to
      var section = header.closest('.gw-filter-section');
      if (!section) return;

      // find the body & icon INSIDE this section only
      var body = section.querySelector('.gw-filter-body');
      var icon = header.querySelector('.gw-filter-toggle-icon');
      if (!body) return;

      var isOpen = body.style.display === 'block';

      if (isOpen) {
        body.style.display = 'none';
        if (icon) {
          icon.classList.remove('fa-chevron-down');
          icon.classList.add('fa-chevron-right');
        }
      } else {
        body.style.display = 'block';
        if (icon) {
          icon.classList.remove('fa-chevron-right');
          icon.classList.add('fa-chevron-down');
        }
      }
    });
  });
});
</script>

