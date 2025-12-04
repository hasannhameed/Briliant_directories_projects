#first_container {
    background-color: <?php echo $wa['custom_79']?>;
}
body{
    z-index: 0;
}
.vegas-slide-inner {background-position:center top!important;}
.previous {
    left: 10px;
    right: auto;
    background-image: url('//vegas.jaysalvat.com/img/icon-previous.svg') !important;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
.vegas-wrapper .previous, .vegas-wrapper .next {
    opacity: .8;
    visibility: hidden;
    display: block;
    position: absolute;
    width: 32px;
    height: 32px;
    margin: 0;
    padding: 0;
    background: center center no-repeat;
    background-size: cover;
    top: 50%;
}
.vegas-wrapper .next {
    left: auto;
    right: 10px;
    background-image: url('//vegas.jaysalvat.com/img/icon-next.svg') !important;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}

.vegas-transition-zoomIn2-out,
.vegas-transition-zoomOut,
.vegas-transition-zoomOut2{
    webkit-transform: scale(1) !important;
    transform: scale(1) !important;
    opacity: 0;
}

body #first_container{
    overflow:visible !important
}
<?php if($page['seo_type'] == "home" && $wa['hide_hero_on_mobile'] == "1"){ ?>
    @media only screen and (max-width: 767px) {
        body .vegas-slide {display: none !important}
		body #first_container {height: 0 !important;overflow: hidden!important;
}
    }
<?php }?>