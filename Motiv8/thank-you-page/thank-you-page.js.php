<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.6.0/clipboard.min.js"></script>
<script>
                var clipboard = new Clipboard('.copy_code');
                clipboard.on('success', function (e) {
                    alert('Copied to Clipboard!');
                    return false;
                });
                clipboard.on('error', function (e) {
                    showTooltip(e.trigger, fallbackMessage(e.action));
                });
        </script>