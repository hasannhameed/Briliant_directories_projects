<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script><script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on('click', '.copy_btn', function() {
        var copyLink = $(this).data('copylink');
        
        // Create a temporary input
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(copyLink).select();

        // Copy the link
        document.execCommand('copy');
        
        // Remove the temporary input
        tempInput.remove();

        // Show feedback (optional)
        $(this).text('Copied!');

        // Optional: revert text after 2 seconds
        var button = $(this);
        setTimeout(function() {
            button.html('<i class="fa fa-files-o" aria-hidden="true"></i> Copy Link');
        }, 2000);
    });
});
</script>