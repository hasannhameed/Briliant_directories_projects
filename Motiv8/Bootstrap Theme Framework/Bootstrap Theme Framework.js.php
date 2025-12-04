<?php

$showFBChat = false;
$showFBByClick = false;

if ($w['fb_login_status'] == "1" && !empty($w['fb_app_id'])) {
    $facebookAppId = '&appId=' . $w['fb_app_id'] . '&status=true&cookie=true&';
    $showFBByClick = true;
}
if (addonController::isAddonActive('facebook_chat') && $w['facebook_page_id'] != "") {
    if ($w['facebook_messenger_status'] == "1") {
        $showFBChat = true;
    } else if ($w['facebook_messenger_status'] == "2" && !checkIfMobile()) {
        $showFBChat = true;
    } else if ($w['facebook_messenger_status'] == "3" && checkIfMobile()) {
        $showFBChat = true;
    }
}
if ($showFBChat || $showFBByClick) { ?>
    <!-- Facebook Javascript SDK -->
    <script>
        function loadFBjs(delay = 500) {
            return new Promise((resolve, reject) => {
                if (window["fbjs"]) {
                    resolve("already load FB");
                }
                let body = document.getElementsByTagName("body")[0]
                let divFbRoot = document.createElement("div");
                divFbRoot.id = 'fb-root';

                body.prepend(divFbRoot);
                setTimeout(function () {
                    (function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s);
                        js.id = id;
                        js.async = false;
                        js.src = "//connect.facebook.net/<?php echo str_replace('-', '_', $w['website_language']); ?>/sdk/xfbml.customerchat.js#version=v15.0&xfbml=1<?php echo $facebookAppId;?>";
                        fjs.parentNode.insertBefore(js, fjs);
                        js.onload = function () {
                            window["fbjs"] = true;
                            resolve("ok");
                        };
                    }(document, 'script', 'facebook-jssdk'));
                }, delay)
            });
        }
    </script>
    <?php
    if ($showFBChat) {
        addonController::showWidget('facebook_chat', 'f60ec467c22b9d4319900131f88b671b');
    }

} ?>