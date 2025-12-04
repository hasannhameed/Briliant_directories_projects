<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cropper/4.0.0/cropper.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script type="text/javascript">
    const fillColor = '<?php $rgbarr = explode(",", cleanRGBA($wa["custom_1"]), 3);
        echo sprintf("#%02x%02x%02x", $rgbarr[0], $rgbarr[1], $rgbarr[2]); ?>';
    var photoRatio = "";
    var logoRatio = "";

    var photoBigWidth = "";
    var photoBigHeight = "";

    var photoSocialWidth = "";
    var photoSocialHeight = "";

    var logoBigWidth = "";
    var logoBigHeight = "";

    var logoSocialWidth = "";
    var logoSocialHeight = "";

    var coverMaxWidth = "";
    var coverMaxHeight = "";
    var coverMinWidth = '';
    var coverMinHeight = '';
    var coverRatio = "";

    var mainStatCheck = 0;
    var mainStatCheckLogo = 0;
    var gisResponseArray = [];

    var profileCropMinWidth = "";
    var profileCropMinHeight = "";
    var dominatedARatio = "";
    var imageDominatedSize = "";
    var imageDominantSize = "";

    var file = {};
    var ajaxUrl = '/wapi/widget';
    var filePath = 'Bootstrap Theme - Account - Profile Image Upload';
    var admin_id = '<?php echo $_COOKIE['userid'];?>';
    var fileSizeBase   = '<?php echo websiteSettingsController::getFileUploadSizeByType(websiteSettingsController::UPLOAD_FILE_TYPE)?>';
    var fileSize       = fileSizeBase * 1000000;

    $.ajax('/wapi/widget/print', {
        type: "POST",
        data: {
            request_type: 'POST',
            widget_name: 'Bootstrap Theme - Account - Profile Image Upload',
            header_type: 'json',
            module_action: 'get_image_settings'
        },
        success: function (data) {
            gisResponseArray.push(data);
        },
        error: function (data) {
            console.log(data);
            console.log('Upload error');
        }
    });
    var gisInter = setInterval(function () {

        if (gisResponseArray.length == 1) {
            clearInterval(gisInter);

            if (gisResponseArray[0]['profile_photo']['js_ratio'] != "free") {
                photoRatio = gisResponseArray[0]['profile_photo']['js_ratio'].split("/");

            } else {
                photoRatio = parseInt(NaN);
            }
            if (gisResponseArray[0]['profile_logo']['js_ratio'] != "free") {
                logoRatio = gisResponseArray[0]['profile_logo']['js_ratio'].split("/");

            } else {
                logoRatio = parseInt(NaN);
            }
            photoBigWidth = parseInt(gisResponseArray[0]['profile_photo']['width']);
            photoBigHeight = parseInt(gisResponseArray[0]['profile_photo']['height']);

            photoSocialWidth = parseInt(gisResponseArray[0]['profile_photo_social']['width']);
            photoSocialHeight = parseInt(gisResponseArray[0]['profile_photo_social']['height']);

            logoBigWidth = parseInt(gisResponseArray[0]['profile_logo']['width']);
            logoBigHeight = parseInt(gisResponseArray[0]['profile_logo']['height']);

            logoSocialWidth = parseInt(gisResponseArray[0]['profile_logo_social']['width']);
            logoSocialHeight = parseInt(gisResponseArray[0]['profile_logo_social']['height']);

            coverMaxWidth = parseInt(gisResponseArray[0]['profile_cover']['width']);
            coverMinHeight = parseInt(gisResponseArray[0]['profile_cover']['height']);
            imageAnalysis("initInfo", "");
        }
    }, 400);

    function readURL(input, method) {
        $('.preview-container').show();
        $('.image-controls').show();

        $('.profile-photo-confirm').show();
        $('.modal-title').hide();

        $('.image-preview-input').hide();
        $('.interactive-container').removeClass('hidden-interactive-container');
        $(".croper-action").attr("disabled", false);
        var reader = new FileReader();

        reader.onload = function (e) {

            if (mainStatCheck == 0) {

                $('.preview-image-img-tag').attr('src', e.target.result)
                    .cropper({
                        aspectRatio: parseInt(photoRatio[0]) / parseInt(photoRatio[1]),
                        viewMode: 1,
                        dragMode: 'move',
                        responsive:false,
                        scalable:true,
                        crop: function (e) {
                            imageAnalysis("main_photo_cropping", e);
                        }
                    });

                mainStatCheck = 1;
                imageAnalysis('main_photo', $('.preview-image-img-tag').cropper('getImageData'));

                $('.preview-image-img-tag').cropper('reset');

            } else {
                //selecting an image after the first init
                $('.preview-image-img-tag').cropper('replace', e.target.result);
                imageAnalysis('main_photo', $('.preview-image-img-tag').cropper('getImageData'));
            }
        };
        file = input.files[0];
        reader.readAsDataURL(input.files[0]);
    }

    function imageAnalysis(type, imgData) {
        switch (type) {
            case "initInfo":
                if ($.isArray(photoRatio)) {
                    //get the non predominant meassure of the choosen aspect ratio
                    if (parseInt(photoRatio[0]) > parseInt(photoRatio[1])) {
                        dominatedARatio = 'height';
                        var dominatedARatioVal = photoRatio[1];
                        var dominantARatioVal = photoRatio[0];
                        imageDominatedSize = parseInt(200);

                    } else {
                        dominatedARatio = 'width';
                        var dominatedARatioVal = photoRatio[0];
                        var dominantARatioVal = photoRatio[1];
                        imageDominatedSize = parseInt(200);
                    }
                    //get the dominant minumum size
                    imageDominantSize = Math.ceil(parseInt(imageDominatedSize) / (parseInt(dominatedARatioVal) / parseInt(dominantARatioVal)));

                    if (dominatedARatio == "height") {
                        profileCropMinWidth = imageDominantSize;
                        profileCropMinHeight = imageDominatedSize;

                    } else {
                        profileCropMinWidth = imageDominatedSize;
                        profileCropMinHeight = imageDominantSize;
                    }
                }
                break;
            case "main_photo":
            <?php if($w['skip_photo_upload_warning_size'] != 1 ) { ?>
                if ($.isArray(photoRatio)) {

                    if (dominatedARatio == "height") {

                        if (imgData['naturalHeight'] < imageDominatedSize || imgData['naturalWidth'] < imageDominantSize) {
                            swal(`<?php echo $label['swal_warning']?>`, `<?php echo $label['photo_upload_warning_swal_text']?>`, "warning");
                        }

                    } else {

                        if (imgData['naturalHeight'] < imageDominantSize || imgData['naturalWidth'] < imageDominatedSize) {
                            swal(`<?php echo $label['swal_warning']?>`, `<?php echo $label['photo_upload_warning_swal_text']?>`, "warning");
                        }
                    }

                    //END if ($.isArray(photoRatio))
                } else {

                    //the aspect ratio is free so just check that the image is at least 200 by 200
                    if (imgData['naturalHeight'] < 200 || imgData['naturalWidth'] < 200) {
                        swal(`<?php echo $label['swal_warning']?>`, `<?php echo $label['photo_upload_warning_swal_text']?>`, "warning");
                    }
                }
            <?php } ?>
                break;
            case "main_photo_cropping":
                if (imgData['detail']['width'] < profileCropMinWidth || imgData.detail.height > coverMinHeight) {
                    $('.cropper-line, .cropper-point').css("background-color", "#DA5B57");
                    $('.cropper-view-box').css("outline", "#DA5B57").css("outline-color", "#DA5B57");

                } else {
                    $('.cropper-line, .cropper-point').css("background-color", "#39f");
                    $('.cropper-view-box').css("outline", "#39F").css("outline-color", "#3399FF");
                }
                break;

            case "cover_photo_cropping":
                if (imgData['detail']['width'] < coverMaxWidth || imgData['detail']['height'] > coverMinHeight) {
                    $('.cropper-line, .cropper-point').css("background-color", "#DA5B57");
                    $('.cropper-view-box').css("outline", "#DA5B57").css("outline-color", "#DA5B57");
                } else {
                    $('.cropper-line, .cropper-point').css("background-color", "#39f");
                    $('.cropper-view-box').css("outline", "#39F").css("outline-color", "#3399FF");
                }
                break;
            case "main_photo_uploading":
                var responseVar = "";

                if (imgData['width'] < profileCropMinWidth || imgData['height'] < profileCropMinHeight) {
                    responseVar = "cancel";

                } else {
                    responseVar = "accept";
                }
                return responseVar;
        }

    }//END function imageAnalysis(type,imgData)
    //upload profile image function
    function uploadImage(imageInfo, imageType, secondImage = null, secondType = null) {
        switch (imageType) {
            case 'photo_main':
                var moduleAction = "upload_image_main";
                break;
            case 'logo_main':
                var moduleAction = "upload_logo_main";
                break;
            case 'cover_main':
                var moduleAction = "upload_cover_main";
                break;
        }
        switch (secondType) {
            case 'photo_social':
                var secondModuleAction = "upload_image_social";
                break;
            case 'logo_social':
                var secondModuleAction = "upload_logo_social";
                break;
        }
        var uploadResponse = [];

        var extension = file.name.split('.').pop();
        extension = extension.toLowerCase();

        $.ajax('/wapi/widget/print', {
            type: "POST",
            contentType: "application/x-www-form-urlencoded",
            data: {
                request_type: 'POST',
                widget_name: 'Bootstrap Theme - Account - Profile Image Upload',
                header_type: 'json',
                image_info: imageInfo,
                image_extension: extension,
                module_action: moduleAction
            },
            success: function (data) {
                // once the uploding cover is done, we exit the function
                if (imageType == "cover_main" && secondImage == null) {
                    swal({
                        title: `<?php echo $label['system_success_label']?>`,
                        text: `<?php echo $label['photo_upload_image_uploaded']?>`,
                        type: "success",
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        <?php if ($_GET['redirect']): ?>
                            window.location.href = '<?php echo $_GET['redirect'] ?>';
                        <?php else: ?>
                            window.location.href = '/account/profile';
                        <?php endif ?>
                    }, 1000);
                }
                uploadResponse.push(data);
            },
            error: function (data) {
                console.log(data);
                console.log('Upload error');
            }
        });

        var upInter = setInterval(function () {

            if (uploadResponse.length == 1) {
                clearInterval(upInter);

                if (uploadResponse[0][0] != null && uploadResponse[0][0] != "") {
                    var siResponseArray = [];
                    //now upload the second image
                    $.ajax('/wapi/widget/print', {
                        type: "POST",
                        contentType: "application/x-www-form-urlencoded",
                        data: {
                            request_type: 'POST',
                            widget_name: 'Bootstrap Theme - Account - Profile Image Upload',
                            header_type: 'json',
                            image_info: secondImage,
                            module_action: secondModuleAction
                        },
                        success: function (data) {
                            siResponseArray.push(data);
                        },
                        error: function (data) {
                            console.log(data);
                            console.log('Upload error');
                        }
                    });
                    var siInter = setInterval(function () {

                        if (siResponseArray.length == 1) {
                            clearInterval(siInter);

                            if (siResponseArray[0][0] != null && siResponseArray[0][0] != "") {

                                switch (imageType) {
                                    case 'photo_main':
                                        var moduleAction = "upload_image_main";
                                        var pictureType = "Photo";
                                        break;
                                    case 'logo_main':
                                        var moduleAction = "upload_image_thumb";
                                        var pictureType = "Logo";
                                        break;
                                }
                                if (imageType == "photo_main" || imageType == "logo_main" || imageType == "cover_main") {
                                    swal({
                                        title: `<?php echo $label['system_success_label']?>`,
                                        text: `<?php echo $label['photo_upload_image_uploaded']?>`,
                                        type: "success",
                                        showConfirmButton: false
                                    });
                                    setTimeout(function () {
                                        <?php if ($_GET['redirect']): ?>
                                            window.location.href = '<?php echo $_GET['redirect'] ?>';
                                        <?php else: ?>
                                            window.location.href = '/account/profile';
                                        <?php endif ?>
                                    }, 1000);
                                }
                            }//END if (siResponseArray[0][0] != null && siResponseArray[0][0] != "")
                        }//END if (siResponseArray.length == 1)
                    }, 400);//END siInter interval

                    //END if (uploadResponse[0][0] != null && uploadResponse[0][0] != "")
                } else {
                    swal({
                            title: `<?php echo $label['error_label']?>`,
                            text: `<?php echo $label['photo_upload_error']?>`,
                            type: "error"
                        },
                        function () {
                            //location.reload();
                        }
                    );
                }
            }//END if (uploadResponse.length == 1)
        }, 400);//END upInter interval

    }//END function uploadImage(imageInfo,imageType,secondImage,secondType)
    //selecting an image to upload
    $("#profile-img-upload-input").change(async function () {
        var imageName = $(this).val().substr(12);
        $('.image-preview-filename').val(imageName);

        var that = this;
        var file = that.files[0];
        await loadImageValidator();
        const imageValidatorObject = new imageValidator(
            {
                mimeTypes: [
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                    'image/svg+xml',
                    'image/webp'
                ],
                sizeAllowed:fileSize,
                extensions: ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'],
                image: file,
                ajaxUrl: ajaxUrl,
                success: function () {
                    readURL(that, 'input');
                },
                error: function () {
                    setTimeout(function(){
                        swal({
                            title: `<?php echo $label['error_label']?>`,
                            text: `<?php echo $label['photo_upload_error']?>`,
                            type: "error"
                            });

                        $('.image-preview-filename').val('');
                    },200);
                },
                extraParams: {},
                swalValidationTitle: `<?php echo $label['file_validation_title']?>`,
                swalValidationMessage: `<?php echo $label['file_validation_message']?>`
            }
        );

        var extension = file.name.split('.').pop();
        extension = extension.toLowerCase();

        extraParams = {};
        extraParams.imageName = file.name;
        extraParams.imageExtension = extension;
        extraParams.imageSize = file.size;
        extraParams.imageMimeType = file.type;
        extraParams.isFromFileManager = false;
        extraParams.pathFile = filePath;
        extraParams.userId = admin_id;
        extraParams.validationType = "<?php echo imageValidator::IMAGE;?>";

        imageValidatorObject.setExtraParams(extraParams);

        imageValidatorObject.run();

    });
    $('#coverPhotoUpload').on('hidden.bs.modal', function (e) {
        $('.interactive-container-cover').addClass('hidden-interactive-container');
        $('.cover-input-button-container').show();
        $('.cover-confirm').hide();
        $('.modal-title').show();
        $('.cover-preview-filename').val('');
        $(".cover-confirm").attr("disabled", true);
    });
    $('#profilePhotoUpload').on('hidden.bs.modal', function (e) {
        $('.interactive-container').addClass('hidden-interactive-container');
        $('.image-preview-input').show();

        $('.modal-title').show();
        $('.profile-photo-confirm').hide();

        $('.image-preview-filename').val('');
        $('#profile-img-upload-input').val('');
    });
    $('#logoUpload').on('hidden.bs.modal', function (e) {
        $('#logo-preview-img-tag').addClass('hide');
        $('.logo-preview-input').removeClass('hide');
        $('.upload-logo').hide();
        $('.modal-title').show();
        $('.logo-preview-filename').val('');
        $(".upload-logo").attr("disabled", true);
        $('#logo-img-upload-input').val('');
    })
    //profile image controls
    $(document).on('click', '.croper-action', function () {
        var currentButton = $(this);
        var dataMethod = currentButton.data('method');
        var dataOrigin = currentButton.data('origin');
        switch (dataOrigin) {
            case 'cover':
                // fixme: change to grab the one from canvas
                var currentCrop = $('#cover-preview-img-tag');
                break;
            case 'photo':
            default:
                var currentCrop = $('.preview-image-img-tag');
                break;
        }

        switch (dataMethod) {
            case 'zoom':
                currentCrop.cropper(dataMethod, currentButton.data('option'));
                break;
            case 'crop':
                swal({
                    title: '<i class="fa fa-refresh fa-spin fa-3x"></i>',
                    text: `<?php echo $label['photo_upload_processing']?>`,
                    html: true,
                    showConfirmButton: false
                });
                var userToken = $('#usertokenspan').data('usertoken');
                //big profile image
                var profileImgCroppedData = currentCrop.cropper('getCroppedCanvas', {
                    "imageSmoothingEnabled": true,
                    "imageSmoothingQuality": "high",
                    fillColor: fillColor,
                });
                var croppedImgWidth = parseInt(profileImgCroppedData.width);
                var croppedImgHeight = parseInt(profileImgCroppedData.height);

                var imgData = {
                    width: croppedImgWidth,
                    height: croppedImgHeight
                };


                if (dataOrigin == 'cover') {

                    let fileMimeType = document.getElementById('cover-preview-img-tag').src;
                    fileMimeType = fileMimeType.split(';');
                    fileMimeType = fileMimeType[0].split(':');
                    fileMimeType = fileMimeType[1];
                    let profileBigImg = profileImgCroppedData.toDataURL(fileMimeType, 0.99);
                    let fileCover = dataURItoBlob(profileBigImg);
                    uploadCoverImage(fileCover);
                    return;// we exit the current function
                }
                var uploadAnalysisResponse = imageAnalysis("main_photo_uploading", imgData);
                var doUpload = 0;

                if (uploadAnalysisResponse == "cancel") {
                    waitDeffer = 1;
                    <?php if($w['skip_photo_upload_warning_size'] != 1 ) { ?>
                    swal({
                        title: `<?php echo $label['swal_warning']?>`,
                        text: `<?php echo $label['photo_upload_warning_swal_text']?>`,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: `<?php echo $label['photo_upload_stretch']?>`,
                        cancelButtonText: `<?php echo $label['cancel_message']?>`,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function (isConfirm) {

                        if (isConfirm) {
                            doUpload = 1;
                            swal({
                                title: '<i class="fa fa-refresh fa-spin fa-3x"></i>',
                                text: `<?php echo $label['photo_upload_processing']?>`,
                                html: true,
                                showConfirmButton: false
                            });

                        } else {
                            doUpload = 2;
                            swal.close();
                        }

                    });
                    <?php } else {echo "doUpload = 1;";}?>
                } else {
                    doUpload = 1;
                }
                var wuInter = setInterval(function () {

                    if (doUpload != 0) {
                        clearInterval(wuInter);

                        if (doUpload == 1) {
                            //If cropped area is bigger than setting will resize image
                            if (croppedImgHeight > photoBigHeight && $.isArray(photoRatio)) {

                                let newProfileBigImgHeight = parseInt(photoBigHeight);
                                let ratio = parseInt(croppedImgWidth) / parseInt(croppedImgHeight);
                                let newProfileBigImgWidth = parseInt(Math.ceil(newProfileBigImgHeight * ratio));

                                let imgBigCanvas = document.getElementById("profile_img_canvas");
                                let ctx = imgBigCanvas.getContext('2d', {alpha: false});
                                imgBigCanvas.height = newProfileBigImgHeight;
                                imgBigCanvas.width = newProfileBigImgWidth;

                                ctx.imageSmoothingEnabled = true;
                                ctx.imageSmoothingQuality = 'high';

                                ctx.drawImage(profileImgCroppedData, 0, 0, newProfileBigImgWidth, newProfileBigImgHeight);
                                var profileBigImg = document.getElementById("profile_img_canvas").toDataURL(file.type, 0.99);

                            } else {
                                var profileBigImg = profileImgCroppedData.toDataURL(file.type, 0.99);
                            }
                            var parsedBigImage = profileBigImg.split(",");

                            //social media profile image
                            var profileSocialImg = currentCrop.cropper('getCroppedCanvas', {fillColor: fillColor});
                            var profileSocialImgWidth = parseInt(profileSocialImg.width);
                            var profileSocialImgHeight = parseInt(profileSocialImg.height);

                            //checked that the height of the thumbnail image is inside the settings parameters otherwise resize it again (mainly used when the ratio is free)
                            //scale using the biggest measure
                            if (profileSocialImgWidth > profileSocialImgHeight) {
                                //with is the dominan measure
                                var finalPhotoSocialWidth = parseInt(photoSocialWidth);
                                var finalPhotoSocialHeight = parseInt(Math.ceil(finalPhotoSocialWidth / (parseInt(profileSocialImgWidth) / parseInt(profileSocialImgHeight))));

                                if (finalPhotoSocialHeight < 200) {
                                    //After scaling to dominant width the height is not low limit compliant
                                    //if the height of the scale endup being smaller than the minimum 200 then scale it from the minimum measure
                                    var finalPhotoSocialHeight = parseInt(200);
                                    var finalPhotoSocialWidth = parseInt(Math.ceil(finalPhotoSocialHeight / (parseInt(profileSocialImgWidth) / parseInt(profileSocialImgHeight))));
                                }

                            } else {
                                //height is the dominan measure
                                var finalPhotoSocialHeight = parseInt(photoSocialHeight);
                                var finalPhotoSocialWidth = parseInt(Math.ceil(finalPhotoSocialHeight / (parseInt(profileSocialImgHeight) / parseInt(profileSocialImgWidth))));

                                if (finalPhotoSocialWidth < 200) {
                                    //After scaling to dominant height the width is not low limit compliant
                                    //if the width of the scale endup being smaller than the minimum 200 then scale it from the minumum measure
                                    var finalPhotoSocialWidth = parseInt(200);
                                    var finalPhotoSocialHeight = parseInt(Math.ceil(finalPhotoSocialWidth / (parseInt(profileSocialImgWidth) / parseInt(profileSocialImgHeight))));
                                }
                            }

                            var imgSocialCanvas = document.getElementById("profile_social_canvas");
                            $('#profile_social_canvas').attr("height", finalPhotoSocialHeight);
                            $('#profile_social_canvas').attr("width", finalPhotoSocialWidth);
                            var imgSocialCanvasVar = imgSocialCanvas.getContext("2d");
                            var profileSocialResizedCanvasVar = imgSocialCanvasVar.drawImage(profileSocialImg, 0, 0, profileSocialImgWidth, profileSocialImgHeight, 0, 0, finalPhotoSocialWidth, finalPhotoSocialHeight);
                            var finalProfileSocialImg = document.getElementById("profile_social_canvas").toDataURL(file.type, 0.99);
                            var parsedProfileSocialImg = finalProfileSocialImg.split(",");

                            uploadImage(parsedBigImage[1], 'photo_main', parsedProfileSocialImg[1], 'photo_social');
                        }
                    }
                }, 400);
                break;
            case 'move':
                currentCrop.cropper(dataMethod, currentButton.data('option'), currentButton.data('second-option'));
                break;
            case 'rotate':
                currentCrop.cropper(dataMethod, currentButton.data('option'));
                break;
            case 'scaleX':
                currentCrop.cropper(dataMethod, currentButton.data('option'));
                var currentValue = currentButton.data("option");

                if (currentValue == 1) {
                    currentButton.data("option", "-1");

                } else {
                    currentButton.data("option", "1");
                }
                break;
            case 'scaleY':
                currentCrop.cropper(dataMethod, currentButton.data('option'));
                var currentValue = currentButton.data("option");

                if (currentValue == 1) {
                    currentButton.data("option", "-1");

                } else {
                    currentButton.data("option", "1");
                }
                break;
        }
    });
    //code to show the layout to replace the image
    $('.change-current-image').click(function () {
        $('#profilePhotoUpload').modal({
            backdrop: 'static'
        });
        $('.image-preview').show();
        $('.interactive-container').show();

        if ($(window).width() < 1030) {
            $('html,body').animate({
                scrollTop: $('.hidden-uploader').offset().top - 70
            }, 500);
        }
    });

    //All the code related to the logo uploads
    //selecting an image to preview for the logo upload
    var mainLogoStatCheck = 0;

    function readURLLogo(input) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#logo-preview-img-tag').removeClass('hide');
            $('.logo-preview-input').addClass('hide');
            $('.upload-logo').show();
            $('.modal-title').hide();
            $('#logo-preview-img-tag').attr('src', e.target.result);
            $('.upload-logo').attr('disabled', false);
            $('.upload-logo').attr('disabled', false);
        };
        file = input.files[0];
        reader.readAsDataURL(input.files[0]);
        $('.logo-controls').show();
        $('.logo-controls-btn').show();
    }

    $("#logo-img-upload-input").change(async function () {
        var logoName = $(this).val().substr(12);
        $('.logo-preview-filename').val(logoName);

        var that = this;
        var file = that.files[0];
        await loadImageValidator();
        const imageValidatorObject = new imageValidator(
            {
                mimeTypes: [
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                    'image/svg+xml',
                    'image/webp'
                ],
                sizeAllowed:fileSize,
                extensions: ['png', 'jpg', 'jpeg', 'gif', 'svg','webp'],
                image: file,
                ajaxUrl: ajaxUrl,
                success: function () {
                    readURLLogo(that);
                },
                error: function () {
                    setTimeout(function(){
                        swal({
                            title: `<?php echo $label['error_label']?>`,
                            text: `<?php echo $label['photo_upload_error']?>`,
                            type: "error"
                            });

                        $('.logo-preview-filename').val('');
                    },200);
                },
                extraParams: {},
                swalValidationTitle: `<?php echo $label['file_validation_title']?>`,
                swalValidationMessage: `<?php echo $label['file_validation_message']?>`
            }
        );

        var extension = file.name.split('.').pop();
        extension = extension.toLowerCase();

        extraParams = {};
        extraParams.imageName = file.name;
        extraParams.imageExtension = extension;
        extraParams.imageSize = file.size;
        extraParams.imageMimeType = file.type;
        extraParams.isFromFileManager = false;
        extraParams.pathFile = filePath;
        extraParams.userId = admin_id;
        extraParams.validationType = "<?php echo imageValidator::IMAGE;?>"

        imageValidatorObject.setExtraParams(extraParams);

        imageValidatorObject.run();


    });
    $('.change-current-logo').click(function () {
        $('#logoUpload').modal({
            backdrop: 'static'
        });
        $('.input-group.logo-preview').show();

        if ($(window).width() < 1030) {
            $('html,body').animate({
                scrollTop: $('.logo-preview').offset().top - 70
            }, 500);
        }
    });
    //Code to update logo image
    $(document).on('click', '.upload-logo', function () {
        swal({
            title: `<i class="fa fa-refresh fa-spin fa-3x"></i>`,
            text: `<?php echo $label['photo_upload_processing'];?>`,
            html: true,
            showConfirmButton: false
        });
        //get the current image natural dimensions
        var currentLogoImg = $('#logo-preview-img-tag');
        var theLogoImage = new Image();
        theLogoImage.src = currentLogoImg.attr("src");
        var logoImageWidth = parseInt(theLogoImage.width);
        var logoImageHeight = parseInt(theLogoImage.height);


        //scale profile logo main image
        if (logoImageWidth > logoBigWidth || logoImageHeight > logoBigHeight) {
            //Scale first to match the width and then if the hieght is missing scale the height too
            var newProfileBigLogoWidth = parseInt(logoBigWidth);
            var newProfileBigLogoHeight = parseInt(Math.ceil(newProfileBigLogoWidth / (parseInt(logoImageWidth) / parseInt(logoImageHeight))));
            var finalLogoBigWidth = newProfileBigLogoWidth;
            var finalLogoBigHeight = newProfileBigLogoHeight;

            if (newProfileBigLogoHeight > logoBigHeight) {
                var scaledBigLogoHeight = parseInt(logoBigHeight);
                var scaledBigLogoWidth = parseInt(Math.ceil(scaledBigLogoHeight / (parseInt(newProfileBigLogoHeight) / parseInt(newProfileBigLogoWidth))));
                finalLogoBigWidth = parseInt(scaledBigLogoWidth);
                finalLogoBigHeight = parseInt(scaledBigLogoHeight);
            }

        } else {
            var finalLogoBigWidth = logoImageWidth;
            var finalLogoBigHeight = logoImageHeight;
        }
        var currentLogoElement = document.getElementById('logo-preview-img-tag');
        var profileLogoCanvas = document.getElementById("profile_logo_canvas");
        $('#profile_logo_canvas').attr("height", finalLogoBigHeight);
        $('#profile_logo_canvas').attr("width", finalLogoBigWidth);
        var profileLogoCanvasImg = profileLogoCanvas.getContext("2d");
        var profileLogoCanvasMain = profileLogoCanvasImg.drawImage(currentLogoElement, 0, 0, logoImageWidth, logoImageHeight, 0, 0, finalLogoBigWidth, finalLogoBigHeight);
        var logoMainImgInfo = document.getElementById("profile_logo_canvas").toDataURL(file.type, 0.99);
        var logoMainImgInfo = logoMainImgInfo.split(",");


        //scale profile logo social media image
        if (logoImageWidth < logoSocialWidth && logoImageHeight < logoSocialHeight && logoImageWidth > 200 && logoImageHeight > 200) {
            //Both measures are bigger than low limits
            //this image is ready to be uploaded
            var finalLogoSocialWidth = logoImageWidth;
            var finalLogoSocialHeight = logoImageHeight;

        } else {
            //both measures are bigger than high limits
            //the image has one or both meassures bigger than the limits so they will be scaled to comply
            //scale using the biggest measure
            if (logoImageWidth > logoImageHeight) {
                //with is the dominan measure
                var finalLogoSocialWidth = parseInt(logoSocialWidth);
                var finalLogoSocialHeight = parseInt(Math.ceil(finalLogoSocialWidth / (parseInt(logoImageWidth) / parseInt(logoImageHeight))));

                if (finalLogoSocialHeight < 200) {
                    //After scaling to dominant width the height is not low limit compliant
                    //if the height of the scale endup being smaller than the minimum 200 then scale it from the minimum measure
                    var finalLogoSocialHeight = parseInt(200);
                    var finalLogoSocialWidth = parseInt(Math.ceil(finalLogoSocialHeight / (parseInt(logoImageHeight) / parseInt(logoImageWidth))));
                }

            } else {
                //height is the dominan measure
                var finalLogoSocialHeight = parseInt(logoSocialHeight);
                var finalLogoSocialWidth = parseInt(Math.ceil(finalLogoSocialHeight / (parseInt(logoImageHeight) / parseInt(logoImageWidth))));

                if (finalLogoSocialWidth < 200) {
                    //After scaling to dominant height the width is not low limit compliant
                    //if the width of the scale endup being smaller than the minimum 200 then scale it from the minumum measure
                    var finalLogoSocialWidth = parseInt(200);
                    var finalLogoSocialHeight = parseInt(Math.ceil(finalLogoSocialWidth / (parseInt(logoImageWidth) / parseInt(logoImageHeight))));
                }
            }
        }
        var doUpload = 1;

        if (finalLogoSocialWidth * finalLogoSocialHeight < 240000) {
            //scale the logo versions for the image
            //first the main version
            var mainLogoImage = document.getElementById("logo-preview-img-tag");
            var imgLogoSocialCanvas = document.getElementById("profile_logo_social_canvas");
            $('#profile_logo_social_canvas').attr("height", finalLogoSocialHeight);
            $('#profile_logo_social_canvas').attr("width", finalLogoSocialWidth);
            var imgLogoSocialCanvasVar = imgLogoSocialCanvas.getContext("2d");
            var imgLogoSocialResizeCanvas = imgLogoSocialCanvasVar.drawImage(mainLogoImage, 0, 0, logoImageWidth, logoImageHeight, 0, 0, finalLogoSocialWidth, finalLogoSocialHeight);
            var logoSocialImgInfo = document.getElementById("profile_logo_social_canvas").toDataURL(file.type, 0.99);
            var logoSocialImgInfo = logoSocialImgInfo.split(",");

        } else {
            doUpload = 0;
            swal(`<?php echo $label['error_label']?>`, `<?php echo $label['photo_upload_ratio_error']?>`, "error");
        }
        if (doUpload == 1) {
            //check if the social media version will be stretched

            if ((logoImageWidth * logoImageHeight) < (finalLogoSocialWidth * finalLogoSocialHeight)) {
                <?php if($w['skip_photo_upload_warning_size'] != 1 ) { ?>
                swal({
                    title: `<?php echo $label['swal_warning']?>`,
                    text: `<?php echo $label['photo_upload_warning_swal_text']?>`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#5CB85C",
                    confirmButtonText: `<?php echo $label['photo_upload_current']?>`,
                    cancelButtonText: `<?php echo $label['cancel_message']?>`,
                    closeOnConfirm: false
                }, function () {
                    swal({
                        title: `<i class="fa fa-refresh fa-spin fa-3x"></i>`,
                        text: `<?php echo $label['photo_upload_processing']?>`,
                        html: true,
                        showConfirmButton: false
                    });
                    uploadImage(logoMainImgInfo[1], 'logo_main', logoSocialImgInfo[1], 'logo_social');
                });
                <?php }else{ echo "uploadImage(logoMainImgInfo[1], 'logo_main', logoSocialImgInfo[1], 'logo_social');";}?>
            } else {
                uploadImage(logoMainImgInfo[1], 'logo_main', logoSocialImgInfo[1], 'logo_social');
            }
        }
    });
    //delete photo and logo code
    $(document).on('click', '.delete-current-image', function (e) {
        e.preventDefault();
        swal({
            title: `<i class="fa fa-refresh fa-spin fa-3x"></i>`,
            text: `<?php echo $label['form_processing_request']?>`,
            html: true,
            showConfirmButton: false
        });
        var button = $(this);
        var userToken = $('#usertokenspan').data('usertoken');
        var imageType = button.data('imgtype');
        var diResponseArray = [];

        $.ajax({
            url: '/wapi/widget/print',
            type: "POST",
            dataType: "json",
            data: {
                request_type: 'POST',
                widget_name: 'Bootstrap Theme - Account - Profile Image Upload',
                header_type: 'json',
                user_token: userToken,
                module_action: 'delete_image',
                image_type: imageType
            },
            success: function (data) {
                diResponseArray.push(data);
            },
            error: function (data) {
                console.log(data);
                console.log('delete error');
            }
        });
        var diInter = setInterval(function () {

            if (diResponseArray.length == 1) {
                clearInterval(diInter);
                swal({
                    title: diResponseArray[0]['title'],
                    text: diResponseArray[0]['text'],
                    type: diResponseArray[0]['status'],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    <?php if ($_GET['redirect']): ?>
                        window.location.href = '<?php echo $_GET['redirect'] ?>';
                    <?php else: ?>
                        window.location.href = '/account/profile';
                    <?php endif ?>
                }, 1000);
            }
        }, 400);
    });

    /**********Cover Photo***************/

//All the code related to the cover uploads
//selecting an image to preview for the cover upload
    var mainCoverStatCheck = true;
    var coverPhotoCropper;


    function readURLCover(coverFile) {
        $('.modal-title').hide();
        $('.cover-confirm').show();
        $('.image-controls-cover').show();
        $('.cover-input-button-container').hide();
        $('.interactive-container-cover').removeClass('hidden-interactive-container');
        $('.cover-confirm').attr('disabled', false);
        let reader = new FileReader();
        reader.onload = function (readerParam) {
            let defaultHeight = document.getElementById('cover-preview-img-tag').height;
            if (!mainCoverStatCheck) {
                $('#cover-preview-img-tag').cropper('destroy');
            }
            coverPhotoCropper = $('#cover-preview-img-tag').cropper({
                // aspectRatio: parseInt(photoRatio[0]) / parseInt(photoRatio[1]),
                viewMode: 1,
                fillColor: fillColor,
                dragMode: 'move',
                zoomOnWheel: false,
                cropBoxResizable: true,
                minCropBoxWidth: coverMaxWidth,
                data: {
                    width: coverMaxWidth,
                    height: coverMinHeight,
                },
                crop:function(e){
                    imageAnalysis('cover_photo_cropping',e);
                },
                cropend: function (e) {
                    if (coverPhotoCropper.cropper('getData').height > coverMinHeight) {
                        coverPhotoCropper.cropper('setData', {
                            x: Math.round(coverPhotoCropper.cropper('getData').x),
                            y: Math.round(coverPhotoCropper.cropper('getData').y),
                            width: Math.round(coverPhotoCropper.cropper('getData').width),
                            height: Math.round(coverMinHeight),
                        });

                    }
                }
            });


            mainCoverStatCheck = false;
            $('.image-controls-cover').show();

        };
        reader.readAsDataURL(coverFile);
    }
    <?php if ($subscription['coverPhoto'] == 1) { ?>
    const coverInput = document.getElementById('cover-img-upload-input');
    $("#cover-img-upload-input").click(function () {
        coverInput.value = "";
    });
    coverInput.addEventListener('change', validateImgFile, false);
    <?php } ?>

    async function validateImgFile(event) {
        let file = event.target.files[0];
        let coverName = file.name;
        $('.cover-preview-filename').val(coverName);
        await loadImageValidator();
        const imageValidatorObject = new imageValidator(
            {
                mimeTypes: [
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                    'image/svg+xml',
                    'image/webp'
                ],
                sizeAllowed:fileSize,
                extensions: ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'],
                image: file,
                ajaxUrl: ajaxUrl,
                success: function () {
                    imgToCanvas(file);// readjustment of size
                },
                error: function () {
                    setTimeout(function(){
                        swal({
                            title: `<?php echo $label['error_label']?>`,
                            text: `<?php echo $label['photo_upload_error']?>`,
                            type: "error"
                            });

                        $('.cover-preview-filename').val('');
                    },200);
                },
                extraParams: {},
                swalValidationTitle: `<?php echo $label['file_validation_title']?>`,
                swalValidationMessage: `<?php echo $label['file_validation_message']?>`
            }
        );

        var extension = file.name.split('.').pop();
        extension = extension.toLowerCase();

        extraParams = {};
        extraParams.imageName = file.name;
        extraParams.imageExtension = extension;
        extraParams.imageSize = file.size;
        extraParams.imageMimeType = file.type;
        extraParams.isFromFileManager = false;
        extraParams.pathFile = filePath;
        extraParams.userId = admin_id;
        extraParams.validationType = "<?php echo imageValidator::IMAGE;?>"

        imageValidatorObject.setExtraParams(extraParams);

        imageValidatorObject.run();


    }

    $('.change-current-cover').click(function () {
        $('#coverPhotoUpload').modal({
            backdrop: 'static'
        });

        $('.input-group.cover-preview').show();
        $('.interactive-container-cover').show();
        if ($(window).width() < 1030) {
            $('html,body').animate({
                scrollTop: $('.cover-preview').offset().top - 70
            }, 500);
        }
    });

    //Code to update cover image
    function uploadCoverImage(imgInfo) {
        let extension = imgInfo.type;
        extension = extension.split('/')[1].toLowerCase();
        let settingsData = new FormData();
        let imgData = {
            "widget_name": "Bootstrap Theme - Account - Profile Image Upload",
            "header_type": "html",
            "request_type": "POST",
            "imageAction": "upload_cover",
            "image_extension": extension,
        };
        for (let i in imgData) {
            settingsData.append(i, imgData[i]);
        }
        settingsData.append('coverFile', imgInfo);
        let url = `/wapi/widget`;
        let myInit = {
            method: 'POST',
            credentials: 'same-origin',
            body: settingsData
        };
        fetch(url, myInit)
            .then(function (response) {
                return response.json();
            }).then(function (data) {
            if (data.status === true) {
                swal({
                    title: `<?php echo $label['system_success_label']?>`,
                    text: `<?php echo $label['photo_upload_image_uploaded']?>`,
                    type: "success",
                    showConfirmButton: false
                });
                setTimeout(function () {
                    <?php if ($_GET['redirect']): ?>
                        window.location.href = '<?php echo $_GET['redirect'] ?>';
                    <?php else: ?>
                        window.location.href = '/account/profile';
                    <?php endif ?>
                }, 1000);
            } else {
                swal("Oops...", data.message, "error");
                return false
            }
        }).catch(err => console.log('ERROR NOT SAVE', err));
    }

    function imgToCanvas(file) {
        var fileType = file.type;

        let canvas = document.getElementById("profile_img_canvas");
        let ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        let urlImgObject = URL.createObjectURL(file);

        let img = new Image();
        img.onload = function () {

            ctx.drawImage(img, 20, 20);
            let width = img.width;
            let height = img.height;

            if (width > coverMaxWidth) {
                width = coverMaxWidth;
                height = coverMaxWidth * img.height / img.width;
            }
            if (img.height < coverMinHeight) {
                coverMinHeight = img.height;
            }
            canvas.width = width;
            canvas.height = height;
            ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, width, height);

            let coverImageResize = document.getElementById("profile_img_canvas").toDataURL(fileType, 0.99);
            let fileCover = dataURItoBlob(coverImageResize); // this will convert URL to Blob
            document.getElementById('cover-preview-img-tag').src = coverImageResize;
            readURLCover(fileCover);// this function is expecting a blob

        };
        img.src = urlImgObject;
    }

    function dataURItoBlob(dataURI) { // this function can converts canvas to blob
        // convert base64/URLEncoded data component to raw binary data held in a string
        let byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(dataURI.split(',')[1]);
        else
            byteString = unescape(dataURI.split(',')[1]);

        // separate out the mime component
        let mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to a typed array
        let ia = new Uint8Array(byteString.length);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], {type: mimeString, lastModified: Date.now()});
    }
    <?php if ($_SERVER['funcLoadImgVal'] !== true) {
    $_SERVER['funcLoadImgVal'] = true;?>

    function loadImageValidator(){
        let scriptLoaded = new Promise( (resolve,rejection) => {
            resolve('already loaded');
        });
        let mapUri ="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/image-validator/image-validator.min.js?v=2.3";
        let loadMapScriptAsync = function (uri) {
            return new Promise((resolve, reject) => {
                let script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = uri;
                script.async = true;
                script.onload = function () {
                    window['imageValidator'] = true;
                    resolve("ok");
                };
                let head = document.getElementsByTagName('head')[0];
                head.appendChild(script);
            });
        }
        if(window['imageValidator'] !== true){
            scriptLoaded = loadMapScriptAsync(mapUri);
        }
        return scriptLoaded;
    }
    <?php }?>
</script>