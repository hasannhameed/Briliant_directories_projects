.main_menu, .user_sidebar {
    padding: 5px 8px;
}
.main_menu i, .user_sidebar i {
    font-size: 16px;
}
.navbar-nav li {
    float: none!Important;
}
#member_sidebar_toggle {
    max-height: 34px;
}
.navbar-default{
    -webkit-transition: all .6s ease-in-out;
    -moz-transition: all .6s ease-in-out;
    -o-transition: all .6s ease-in-out;
    transition: all .6s ease-in-out;
}
.transparent_menu {
background-color: rgba(<?php $theme_color = trim(($wa[custom_16]), 'r,g,b,(,)'); echo $theme_color;?>,0.95)!important;
}
@media only screen and (max-width: 991px) {
#popover {display: none;}
}
@media only screen and (max-width: 1100px){
    .mobile-main-menu, #member_sidebar_toggle{
        display:block!important;
    }
    .navbar-toggle {
        display: block!important;
    }
    #bs-main_menu .nav {
        display: none!important;
    }
}
.mobile-main-menu {
    position: fixed;
    height: calc(100% - 50px)!important;
    width: 250px;
    background: <?=$wa[custom_16]?>;
    z-index: 99999;
    right: -250px;
    top: 50px;
    display: none;
    -webkit-transition: all .6s ease-in-out;
    -moz-transition: all .6s ease-in-out;
    -o-transition: all .6s ease-in-out;
    transition: all .6s ease-in-out;
    overflow-y: auto!important;
    overflow-x: hidden!important;
}
.mobile-main-menu ul li i {
    color: <?=$wa[custom_17]?>;
    cursor: pointer;
    float: right;
    padding: 13px;
}

.mobile-main-menu.opened{
    right: 0px!important;
}

.mobile-main-menu ul li a, .mobile-main-menu ul li span {
    display: inline-block;
    float: left;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    padding: 10px 0px;
    width: 80%;
    width: calc(100% - 40px);
}
.mobile-main-menu .sidebar-nav li ul {
    height: 0px;
    overflow: hidden;
    list-style: none;
    color: <?=$wa[custom_17]?>;
    padding-left: 10px;
}
.mobile-main-menu .sidebar-nav li.sub_open > ul{
    height: 100%;
}
.mobile-main-menu ul.sidebar-nav {
    position: absolute;
    width: 250px;
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 14px;
}
.mobile-main-menu .sidebar-nav > li {
    display: block;
    line-height: 20px;
    padding: 0 15px 0 20px;
}
.mobile-main-menu .sidebar-nav li a, .mobile-main-menu .sidebar-nav li span {
    text-decoration: none;
    color: <?=$wa[custom_17]?>;
}
.mobile-main-menu .sidebar-nav li a i, .mobile-main-menu .sidebar-nav li span i {
    display: none;
}