<script>
$(document).ready(function () {

    
    $(document).on("click", "a.video-card", function (e) {
        e.preventDefault();

        var videoUrl = $(this).attr("href");

        $("#iframePreview").attr("src", videoUrl);
        $("#videoModal").modal("show");
    });

    // When modal closes, reset video
    $('#videoModal').on('hidden.bs.modal', function () {
        $("#iframePreview").attr("src", ""); // Stop video on close
    });

});
</script>