<div class="form-group">
  <label for="">Choose post option</label>
  <div class="clearfix"></div>
  <label class="radio-inline">
    <input type="radio" name="video_option" id="inlineRadio1" value="link" <?php echo ($post['video_option'] == 'link') ? "checked" : "" ?>> Link
  </label>
  <label class="radio-inline">
    <input type="radio" name="video_option" id="inlineRadio2" value="embed" <?php echo ($post['video_option'] == 'embed') ? "checked" : "" ?>> Embed
  </label>
</div>
<div class="form-group link-container">
  <label for="">Video link</label>
  <input type="url" name="video_link" class="form-control" value="<?php echo $post['video_link'] ?>">
</div>
<div class="form-group thumb-container">
  <label for="">Thumbnail</label>
  <input type="file" name="thumb" class="form-control thumbnail" >

<?php if ($post['thumbnail'] != ""): ?>
    <a target="_blank" href="https://www.motiv8search.com/images/events/<?php echo $post['thumbnail'] ?>" class="btn btn-primary">View image</a>
<?php endif ?>
</div>
<input type="hidden" name="thumbnail" value="" class="thumbnail-name">
<div class="form-group embed-container">
  <label for="">Embed Code</label>
  <textarea name="embed" id="" rows="5" class="form-control" ><?php echo $post['embed'] ?></textarea>
</div>
<script>
  <?php 
    if ($post['video_option'] == 'link') {
      ?>
        $('.embed-container').hide();
      <?php
    } elseif ($post['video_option'] == 'embed') {
      ?>
        $('.link-container').hide();
        $('.thumb-container').hide();
      <?php
    }
   ?>
  $('input[type=radio][name=video_option]').change(function() {
    console.log($(this).val())
    if (this.value == 'link') {
      $('.link-container').show();
      $('.thumb-container').show();
      $('.embed-container').hide();
    }
    else if (this.value == 'embed') {
      $('.link-container').hide();
      $('.thumb-container').hide();
      $('.embed-container').show();
    }
  });
  $('.thumbnail').change(function(event) {
    var formData = new FormData();
    formData.append('thumbnail', $('input[type=file]')[1].files[0]);
    $.ajax({
      url: 'https://www.motiv8search.com/upload_event_photo.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false, 
      success: function (data) {
        console.log(data);
        $('.thumbnail-name').val(data);
      }
    })
    
  });
</script>