.code {
	background: #fbfafa;
	padding: 6px 38px;
	border-radius: 4px;
	border: 1px solid #ccc;
}
input[type="date"], input[type="time"], input[type="datetime-local"], input[type="month"] {
	line-height: 10px;
}
.switch {
	position: relative;
	display: inline-block;
	width: 48px;
	height: 24px;
}
.tablehead th, #irow th, table.dataTable thead .sorting, .table th {
	background-color: #ebeff3 !important;
	border-bottom: 1px solid #e6e6e6;
	border-right: 1px solid #d7d7d7;
	color: #20364e;
	font-size: 14px;
	font-weight: 600;
	padding: 6px 15px;
	text-align: left;
}
.tablerow td, #irow td {
	background: transparent;
	word-break: break-word;
	white-space: normal;
	color: #444;
	font-size: 13px;
	line-height: 1.5em;
	padding: 4px 15px;
	border-top: none !important;
	border-bottom: 1px solid #e5e5e5;
	border-right: 1px solid #e5e5e5;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider::before {
	position: absolute;
	content: "";
	height: 19px;
	width: 19px;
	left: 0px;
	bottom: 3px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
}
input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.website_time {
   margin-bottom: 15px !important;
}
h3{
    margin: 0;
    margin-bottom: 5PX;
}
.tablerow td, #irow td
{
	padding: 4px 15px;
}