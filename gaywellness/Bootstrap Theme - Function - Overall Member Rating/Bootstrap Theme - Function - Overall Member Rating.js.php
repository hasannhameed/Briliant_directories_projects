<script>
                $('#btnReviews').on('click', function() {
                    //switch to ticket tab
                    $('.member-profile-tabs a[href="#div3"]').tab('show');
                    //scroll to tab section
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(".member-profile-tabs").offset().top
                    }, 2000);
                });
            </script>
