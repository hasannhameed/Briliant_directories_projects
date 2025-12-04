.table {
	margin-top: 12px;
    background: white;
}
.table th {
    background-color: #253342!important;
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
	float:right;
	margin-top:5px;
}
.show_load{
	margin-top:5px;
	float:right;
	display:none;
}
#google-input-lead:placeholder-shown + label {
        opacity: 0;
        min-height: 19px;
    }

    #google-input-lead:placeholder-shown + label input, .hideSubNames {
        display: none !important;
    }
.view-post-button{
	    font-size: 11px;
    right: 0;
    position: absolute;
    top: -32px;
    background: #264966;
    color: #fff!important;
    padding: 3px 10px;
    border-radius: 100px;
    font-weight: 600;
}
.video_detail{
	width:240px;
}
.incomplete_fields_detail{
	width:200px;
}
.member-image {
    width: 26px!important;
    height: 26px!important;
    padding: 1px;
    margin-right: 3px!important;
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
    box-shadow: 0 1px 4px 0 rgba(0,0,0,.15);
}
.btn-warning{
	margin-top:2px;
}
.status-labels{
	display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-direction: column;
    gap: 3px;
}
.label-purple {
    background-color: rgba(189, 55, 243, 255);
}
.label-default{
	background-color: #ff7a59;
}
.d-none {
    display: none;
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


.modal-body {
  max-height: 600px; /* Adjust this value as needed */
  overflow-y: auto;
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
i.fa.fa-download {
    float: right;
    margin-top: 5px;
    cursor: pointer;
}
i.fa.fa-download:hover{
	color:#31b0d5;
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
      /*.labels-container {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            max-width: 300px;
            background-color: #fff;
        }*/
div.labels-container {
    width: 100%;
}
.label-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%; 
}
.color-box.disabled {
    position: relative;
}
		.label {
    min-width: 100%;
    text-align: left;
    padding: 6px;
}
        .label-item {
           /* display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 10px;
            color: #fff;*/
            border-radius: 3px;
            margin-bottom: 5px;
			font-weight: bold;
            font-size: 12px;
			text-transform: capitalize;
			line-height:16px;
        }
        .label-item .label-title {
            flex: 1;
			text-wrap: wrap;
        }
        div.labels-container .edit-btn {
            background: none;
            border: none;
            cursor: pointer;
			padding: 0px 4px;
			float:right;
        }
        .color-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 5px;
        }
        .color-box {
            width: 30px;
            height: 30px;
            cursor: pointer;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .color-box.selected {
            border: 2px solid #000;
        }
		.color-overlay {
			position: absolute;
			font-size: 16px;
			font-weight: bold;
			color: red;
			transform: translate(-50%, -50%);
			top: 50%;
			left: 50%;
		}

		
    		
        @media (min-width: 768px) {
            
            .modal-dialog {
                
				margin-top: 150px;
            }
        }

/* @media (min-width: 992px) {
    .modal-lg {
        width: 1200px;
    }
} */
@media screen and (max-width: 1420px) {
    .search-filters {
        justify-content: space-between !important;
        margin: 10px 0px;
		
    }

    .table {
        margin-top: 0px;
    }
    .body_container.menu_open {
        	width: calc(100% - -380px);
        	overflow-y: scroll;
    		}
    .body_container{
        width: calc(100% - -470px);
        overflow-y: scroll;
    }
	
}