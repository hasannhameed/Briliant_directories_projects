#map-canvas{
    display: block;
    width: 91%;
    height: 250px;
    float: right;
}
body input#pac-input[type=text] {
  width: 97.5%;
  border-radius: 0px;
  height: 27px;
  margin-bottom: 5px;
  color:#333;
}
<?php 
if ($w[software_version] >= 4){ ?>
#map-canvas {
    width: 100%;
}
body .fv-form input#pac-input[type=text] {
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    background-color: #fff;
    border: 1px solid #ccc;
	color:#333;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
<? } ?>