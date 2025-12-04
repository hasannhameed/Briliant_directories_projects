/*.badge-select {
    display: inline-block;
    min-width: 10px;
	min-height: 10px;
    height: 20px;
    padding: 3px 7px;
    font-size: 10px;
    font-weight: 600;
    color: #fff;
    line-height: 10px;
    vertical-align: text-bottom;
    white-space: nowrap;
    text-align: center;
    background: #ff7a59;
    border-radius: 10px;
    margin-left: 2px;
	cursor:pointer;
}

.badge-select option {
    color: #000; 
}

.badge-select:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
}

*/
.fancybox-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: auto;
    outline: none;
    white-space: normal;
    box-sizing: border-box;
    text-align: center;
    z-index: 99994;
}
.fancybox-stage {
    overflow: hidden;
    direction: ltr; 
    z-index: 99994;
    -webkit-transform: translateZ(0);
	position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;

}
.fancybox-slide--iframe .fancybox-content {
    width: 1000px !important;
    max-width: calc(100% - 50px) !important;
    background: transparent !important;
}
.action-links .action-link-delete {
    color: #e1695c;
}
.action-links a:hover, .action-links label:hover,.action-links button:hover {
    background: #006fbb;
    color: #fff;
    text-decoration: none;
}
.action-links .action-link-delete:hover {
    background: #e1695c;
    color: #fff;
}
.action-links {
   box-sizing: border-box;
    line-height: 1.3em;
    cursor: text;
    box-shadow: -1px 2px 3px rgba(0, 0, 0, 0.05);
    background: #fafafa;
    border: 1px solid #ddd;
    border-radius: 5px 0 5px 5px;
    padding: 7px;
    width: 300px;
	height:120px!important;
 
    position: absolute;
}
 .action-links a,.action-links label, .action-links button {
    font-weight: 600;
    display: block;
    line-height: 1.4em;
    margin: 2px 0;
    font-size: 12px;
    padding: 3px 8px;
    width: calc(50% - 0px);
    box-sizing: border-box;
    border: none;
    float: left;
    vertical-align: top;
    border-radius: 3px;
}
.supplier-info {
    display: flex;
    justify-content: space-between;
}

.dropdown-menu img {
    height: 30px;
    width: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.dropdown-menu label {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 5px 5px;
    cursor: pointer;
    grid-gap: 5px;
}

.dropdown-menu li {
    display: flex;
    align-items: center;
    width: 100%;
}

.dropdown-menu li:hover {
    background-color: #f1f1f1;
}

.dropdown-menu .selected-owner {
    display: flex;
    align-items: center;
}

.dropdown-menu .selected-owner img {
    margin-right: 10px;
}

.ownersdropdown li.selected {
    background-color: #007bff;
    color: white;
}

.d-none {
    display: none;
}

.nopad {
    padding: 0;
}

.tmargin {
    margin-top: 10px;
}

.website_time {
    margin: 0 0 10px;
}

.table {
    margin-top: 12px;
    background: white;
}

.table th {
    background-color: #253342 !important;
    color: white;
}

.select2-container.form-control {
    background: 0 0;
    box-shadow: none;
    border: none;
    display: block;
    margin: 0;
    padding: 0;
}

.select2-drop-active {
    border: 1px solid #e6e6e6;
    border-top: none;
}

.member-links {
    display: none;
    float: right;
    margin-top: 5px;
}

.show_load {
    margin-top: 5px;
    float: right;
    display: none;
}
.label-purple {
    background-color: rgba(189, 55, 243, 255);
}
.label-default{
	background-color: #ff7a59;
}
#google-input-lead:placeholder-shown+label {
    opacity: 0;
    min-height: 19px;
}

#google-input-lead:placeholder-shown+label input,
.hideSubNames {
    display: none !important;
}

.view-post-button {
    font-size: 11px;
    right: 0;
    position: absolute;
    top: -32px;
    background: #264966;
    color: #fff !important;
    padding: 3px 10px;
    border-radius: 100px;
    font-weight: 600;
}

.video_detail {
    width: 240px;
}

.incomplete_fields_detail {
    width: 200px;
}

.member-image {
    width: 26px !important;
    height: 26px !important;
    padding: 1px;
    margin-right: 3px !important;
}

.member-image {
    display: inline-block;
    margin-right: 10px;
    text-align: center;
    vertical-align: middle;
    width: 75px;
    height: 75px;
    padding: 2px;
    background: #fff;
    border-radius: 100px;
    box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .15);
}

.dropdown-menu {
    padding: 5px 5px;
    min-width: 170px;
}
#filterForm .dropdown-menu{
	min-width: 263px
}

.select2-container--open .select2-dropdown--below {
    width: 176.312px !important;
}

.select2-container--default .select2-selection--single {
    height: 34px !important;
}

.owners_name {
    padding: 10px 0px;
}

.usedcu {
    color: #ccc;
}

a.copy_code.disabled {
    visibility: collapse;
}

textarea {
    width: 100%;
    height: 150px;
    resize: none;
}

.comments,
.owners_name select,
.owners-badge {
    cursor: pointer;
}

.nolpad {
    padding-left: 0px;
}



.comment-box {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}

.comment-box img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}

.comment-content {
    background-color: #f5f5f5;
    padding: 15px;
    border-radius: 8px;
    width: 100%;
    position: relative;
}

.comment-content:before {
    content: "";
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 10px 10px 10px 0;
    border-color: transparent #f5f5f5 transparent transparent;
    position: absolute;
    left: -10px;
    top: 15px;
}
form.delete_form {
    color: red;
    float: right;
    padding: 0px 5px;
    cursor: pointer;
}

@media (min-width: 992px) {
    .modal-lg {
        width: 1200px;
    }
}

@media screen and (max-width: 1420px) {
    .search-filters {
        justify-content: space-between !important;
        margin: 10px 0px;
    }

    .table {
        margin-top: 0px;
    }
    .body_container.menu_open{
        width: calc(100% - -380px);
        overflow-y: scroll;
    }
    .body_container{
        width: calc(100% - -370px);
        overflow-y: scroll;
    }

    /*
    .table-responsive {
        width: 100%;
        margin-bottom: 15px;
        overflow-y: hidden;
        overflow-x: auto;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border: 1px solid #ddd;
        -webkit-overflow-scrolling: touch
    }

    .table-responsive>.table {
        margin-bottom: 0
    }

    .table-responsive>.table>thead>tr>th,
    .table-responsive>.table>tbody>tr>th,
    .table-responsive>.table>tfoot>tr>th,
    .table-responsive>.table>thead>tr>td,
    .table-responsive>.table>tbody>tr>td,
    .table-responsive>.table>tfoot>tr>td {
        white-space: nowrap
    }

    .table-responsive>.table-bordered {
        border: 0
    }

    .table-responsive>.table-bordered>thead>tr>th:first-child,
    .table-responsive>.table-bordered>tbody>tr>th:first-child,
    .table-responsive>.table-bordered>tfoot>tr>th:first-child,
    .table-responsive>.table-bordered>thead>tr>td:first-child,
    .table-responsive>.table-bordered>tbody>tr>td:first-child,
    .table-responsive>.table-bordered>tfoot>tr>td:first-child {
        border-left: 0
    }

    .table-responsive>.table-bordered>thead>tr>th:last-child,
    .table-responsive>.table-bordered>tbody>tr>th:last-child,
    .table-responsive>.table-bordered>tfoot>tr>th:last-child,
    .table-responsive>.table-bordered>thead>tr>td:last-child,
    .table-responsive>.table-bordered>tbody>tr>td:last-child,
    .table-responsive>.table-bordered>tfoot>tr>td:last-child {
        border-right: 0
    }

    .table-responsive>.table-bordered>tbody>tr:last-child>th,
    .table-responsive>.table-bordered>tfoot>tr:last-child>th,
    .table-responsive>.table-bordered>tbody>tr:last-child>td,
    .table-responsive>.table-bordered>tfoot>tr:last-child>td {
        border-bottom: 0
    }
     */
}