#supplier-table .supplier-table-th{
	padding:0;
	border:none;
	height:0;
}
/*.action{
	display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
	height: 57px;
}*/
.select2-container{
	width:300px!important;
}
.table>tbody>tr.danger>td{
    border-bottom: 1px solid white !important;
}
.table>tbody>tr.success>td{
    border-bottom: 1px solid white !important;
}
@media only screen and (min-width: 600px) {
	.staff{
		width:40% !important;
	}
	.status{
		width:20% !important;
	}
	.registration{
		width:20% !important;	
	}
}
.select2-selection__placeholder{
    font-size:12px;
}
@media only screen and (min-width: 600px) and (max-width: 760px) {
	.cancel{
		width:20% !important;
	}
}

@media (max-width: 760px) {
    .custom-table-responsive {
        overflow-x: auto;
        display: block;
        white-space: nowrap;
    }

    .custom-table-responsive table {
        width: 100%;
        min-width: 600px; /* Decreased from 760px */
    }

    .custom-table-responsive th,
    .custom-table-responsive td {
        font-size: 12px; /* Reduce text size for better fit */
        padding: 5px; /* Reduce padding */
    }
	.status{
		width: 65px;
	}
}


.table_selected_staff,
.status,
.register,
.action {
    vertical-align: middle !important;
    text-align: center;
}


@media only screen and (max-width: 1199px) {
.edit-supplier-card{
	width: 120px;
}
}
@media only screen and (max-width: 991px) {
	.form-inline .form-group {
        margin-bottom: 0px;
    }
}
@media only screen and (min-width: 760px) {
    .status{
		width:15% !important;
    }
    .cancel{
		width:35% !important;
	}
}
@media only screen and (max-width: 767px) {
    #event-filter-form {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .form-group {
        margin-right: 10px; /* Optional: Adjust spacing between select and button */
    }

    .btn {
        margin-left: 0; /* Optional: Adjust button margin if needed */
    }
	.member_accounts .post-title {
        font-size: 14px;
        line-height: 1.2em !important;
        text-align: left;
    }
}

@media only screen and (max-width: 425px) {
    .table_selected_staff{
		<!-- width: 50px !important; -->
	}
}
.swal-text {
    font-size: 14px !important;
    position: relative;
    float: none;
    line-height: normal;
    vertical-align: top;
    text-align: left;
    display: inline-block;
    margin: 0;
    padding: 0 0px !important;
    font-weight: 400;
    color: rgba(0, 0, 0, .64);
    max-width: calc(100% - 20px);
    overflow-wrap: break-word;
    box-sizing: border-box;
    text-align: center;
}

