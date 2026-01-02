<script>
    $('#captchaContainer').removeClass('form-control ');
    $(".website_url_field").keyup(function(){
        var fieldName = $(this).attr('name');
        $(this).val($(this).val().replace(/\s+/g, ''));
        $('#myform').formValidation('revalidateField', fieldName);
    });
</script>
