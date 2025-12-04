    <?php 
        $user_query = "SELECT * FROM `users_data` WHERE active = 2 AND subscription_id = 4";
        $user_data = mysql_query($user_query);
    ?>
    <?php
    $query = "SELECT * FROM `data_posts` WHERE data_id = 73 AND post_start_date >= CURDATE() ORDER BY post_start_date ASC";
    $data = mysql_query($query);
    ?>

    <?php
        $eventData = array();
        $event_query = mysql_query("SELECT * FROM `event_schedule` ORDER BY priority ASC");
        while ($row = mysql_fetch_assoc($event_query)) {

            $event_id = $row['event_id'];
            $display_query = mysql_query("SELECT first_name,last_name,company,email FROM users_data WHERE user_id =".$row['action_by']);
            $display_name_data = mysql_fetch_assoc($display_query);

            $first_name_data = trim($display_name_data['first_name']);
            $last_name_data = trim($display_name_data['last_name']);
            $company_data = trim($display_name_data['company']);
            $email = trim($display_name_data['email']);

            if (!empty($first_name_data) || !empty($last_name_data)) {
                $display_name = htmlspecialchars($first_name_data . ' ' . $last_name_data);
            } else {
                $display_name = htmlspecialchars($company_data);
            }

            $action_by_id = $row['action_by'];
            $photo_query3 = mysql_query("SELECT file FROM users_photo WHERE user_id = " . $action_by_id . " AND type = 'photo' LIMIT 1");
            $photo_data3 = mysql_fetch_assoc($photo_query3);

            if (!empty($photo_data3['file'])) {
                $img_file3 = $photo_data3['file'];
                $img_url3 = "https://www.motiv8search.com/pictures/profile/" . $img_file3;
            } else {
                $logo_query3 = mysql_query("SELECT file FROM users_photo WHERE user_id = " . $action_by_id . " AND type = 'logo' LIMIT 1");
                $logo_data3 = mysql_fetch_assoc($logo_query3);

                if (!empty($logo_data3['file'])) {
                    $img_file3 = $logo_data3['file'];
                    $img_url3 = "https://www.motiv8search.com/logos/profile/" . $img_file3;
                } else {
                    $img_url3 = "https://www.motiv8search.com/images/profile-profile-holder.png";
                }
            }

            $eventData[$event_id][] = array(
                'id' => (int)$row['id'],
                'event_name' => $row['event_name'],
                'priority' => (int)$row['priority'],
                'action_no' => (int)$row['action_no'],
                'action_by' => $row['action_by'],
                'assigned_to' => $row['assigned_to'],
                'action_title' => $row['action_title'],
                'status' => isset($row['status']) ? $row['status'] : null,
                'due_date' => $row['due_date'],
                'reminder' => $row['reminder'],
                'content' => $row['content'],
                'manage' => $row['manage'],
                'display_name' => $display_name,
                'url'=>$img_url3,
                'email'=>$email
            );
        }
        //print_r($eventData);
    ?>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        /* ===== Modal-only: make .m8-* look like .custom-* (no class conflicts) ===== */
        #myModal .m8-user-dropdown { position: relative; }

        /* Toggle (mirror .custom-dropdown-toggle) */
        #myModal .m8-user-toggle {
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 6px 12px;
        width: 100%;
        }
        #myModal .m8-user-toggle img {
        width: 20px; height: 20px; border-radius: 50%;
        margin-right: 8px;
        }
        .nopad{
            padding: 0px !important;
        }

        /* Menu (mirror .custom-dropdown-menu) */
        #myModal .m8-user-menu {
        display: none;               /* stays JS-controlled */
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1010;
        padding: 5px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        }

        /* Search input (mirror .custom-dropdown-search-input) */
        #myModal .m8-search {
        width: calc(100% - 10px);
        margin: 5px;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        }
        .bmargin{
            margin-bottom: 5px;
        }

        /* Items (mirror .custom-dropdown-item) */
        #myModal .m8-user-item {
        display: flex;
        align-items: center;
        padding: 8px;
        cursor: pointer;
        border-radius: 3px;
        }
        #myModal .m8-user-item:hover { background-color: #f1f1f1; }

        /* Item avatar (mirror .custom img sizing) */
        #myModal .m8-user-item .m8-avatar,
        #myModal .m8-user-item img {
        width: 20px; height: 20px; border-radius: 50%;
        margin-right: 8px; object-fit: cover;
        }

        /* Optional utility to hide items (mirror .custom .hidden) */
        #myModal .m8-hidden { display: none !important; }

        /* ========== Modal-only styles (no conflicts) ========== */
            #myModal .m8-table-wrap { width: 100%; overflow-x: auto; min-height: 200px; }

            /* Head cells (mirror your <th> look) */
            #myModal thead.m8-thead th {
            text-align: left;
            white-space: nowrap;
            background-color: #e8edf2 !important;
            border-bottom: 1px solid #eee;
            border-right: 1px solid #ddd;
            color: #253342;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 10px;
            vertical-align: middle !important;
            }

            /* Body cells (mirror your <td> look) */
            #myModal tbody.m8-task-body td {
            border: 1px solid #ddd;
            padding: 5px 5px;
            vertical-align: middle !important;
            white-space: nowrap;
            }

            /* Column widths to echo the old table */
            #myModal .m8-index       { width: 50px; text-align: center; }
            #myModal .m8-drag-cell   { width: 40px; text-align: center; }
            #myModal .m8-action-by   { min-width: 200px; }
            #myModal .m8-actions     { min-width: 300px; }

            /* Row status backgrounds (scoped to modal only) */
            #myModal .status-complete     { background-color: #dff0d8 !important; }
            #myModal .status-pending      { background-color: #fcf8e3; }
            #myModal .status-overdue      { background-color: #e4b9b9; }
            #myModal .status-not-started  { background-color: #f5f5f5 !important; }
            #myModal .status-na           { background-color: #ffffff !important; }

            /* Drag handle + sortable placeholder (modal scope) */
            #myModal .m8-drag { cursor: move; color: #aaa; }
            #myModal .ui-sortable-placeholder {
            background: #f0f8ff;
            border: 2px dashed #ccc;
            height: 50px;
            visibility: visible !important;
            }
            #myModal .ui-sortable-helper { box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

            /* -------- Modal-only dropdown (no shared classes) -------- */
            #myModal .m8-dropdown { position: relative; }

            #myModal .m8-toggle {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 6px 12px;
            width: 100%;
            }
            #myModal .m8-toggle img {
            width: 20px; height: 20px; border-radius: 50%; margin-right: 8px;
            }
            #myModal .m8-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1010;
            padding: 5px;
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
            }
            #myModal .m8-search {
            width: calc(100% - 10px);
            margin: 5px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            }
            #myModal .m8-dropdown-item {
            display: flex;
            align-items: center;
            padding: 8px;
            cursor: pointer;
            border-radius: 3px;
            }
            #myModal .m8-dropdown-item:hover { background-color: #f1f1f1; }
            #myModal .m8-hidden { display: none !important; }

            /* Optional: keep modal toast position consistent */
            #myModal .swal2-popup { top: 50px; }
    </style>


        <style>
            body {
                background-color: #f4f7f6;
                padding: 20px;
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }
            .swal2-popup{
                top: 50px;
            }
            .plugin-container {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                padding: 20px;
            }

            th {
                text-align: left;
                padding: 0;
                vertical-align: middle !important;
                white-space: nowrap;
                background-color: #e8edf2 !important;
                border-bottom: 1px solid #eee;
                border-right: 1px solid #ddd;
                color: #253342;
                font-size: 14px;
                font-weight: 600;
                border-bottom-width: 2px;
                padding: 10px 10px;
                vertical-align: middle !important;
                align-items: center;
            }
            td{
                border: 1px solid #ddd;
                padding: 5px 5px;
            }
            

            .plugin-header h3 {
                margin: 0;
                font-weight: bold;
                color: #333;
                padding-bottom: 15px;
                border-bottom: 1px solid #e9ecef;
            }
            
            .custom-dropdown-container {
                position: relative;
            }
            .custom-dropdown-toggle {
                background: #fff;
                border: 1px solid #ccc;
                border-radius: 4px;
                cursor: pointer;
                display: flex;
                align-items: center;
                padding: 6px 12px;
                width: 100%;
            }
            .custom-dropdown-toggle img {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                margin-right: 8px;
            }
            .custom-dropdown-menu {
                display: none;
                position: absolute;
                background-color: white;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 100%;
                max-height: 250px;
                overflow-y: auto;
                z-index: 1010;
                padding: 5px;
                box-shadow: 0 6px 12px rgba(0,0,0,.175);
            }

            .custom-dropdown-search-input {
                width: calc(100% - 10px);
                margin: 5px;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 3px;
            }

            .custom-dropdown-item {
                display: flex;
                align-items: center;
                padding: 8px;
                cursor: pointer;
                border-radius: 3px;
            }
            .updating{
                color: green;
            }

            .custom-dropdown-item:hover { background-color: #f1f1f1; }
            .custom-dropdown-item.hidden { display: none; }

            .table-container { width: 100%; overflow-x: auto;min-height: 200px; }
            .table td, .table th { vertical-align: middle !important; white-space: nowrap; }
            .table th.action-by-col, .table td.action-by-col { min-width: 200px; }
            .table th.actions-col, .table td.actions-col { min-width: 300px; }
            .table th.status-col, .table td.status-col { min-width: 140px; }
            .table th.due-date-col, .table td.due-date-col { min-width: 150px; }
            .table th.reminder-col, .table td.reminder-col { min-width: 90px; }
            .table th.content-col, .table td.content-col { min-width: 300px; }
            .editable-text { cursor: pointer; }
            
            .status-complete { background-color: #dff0d8 !important; }
            .status-pending { background-color: #fcf8e3; }
            .status-overdue { background-color: #e4b9b9 }
            .status-not-started { background-color: #f5f5f5 !important; }
            .status-na { background-color: #ffffff !important; }
            
            .drag-handle { cursor: move; color: #aaa; }
            .ui-sortable-placeholder { background: #f0f8ff; border: 2px dashed #ccc; height: 50px; visibility: visible !important; }
            .ui-sortable-helper { box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        </style>
    </head>


    <!-- Modal -->
    <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Event Template</h4>
                
        </div>
        <div class="modal-body">
            <div class="" >
                <table class="table-bordered table-hover" style='width: 100%;'>
                    <thead>
                        <tr>
                            <th ></th>
                            <th >No.</th>
                            <th >Action By</th>
                            <th >Actions</th>
                            <th >Manage</th>
                        </tr>
                    </thead>
                    <tbody id="" class="">
                        <tr>
                             <div class="alert alert-success btn btn-block alertsuccess">Fetching data<div class="loader"></div></div>
                        </tr>
                    </tbody> 
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-block save_data">Save All Changes</button>
            
            <p></p>
            <div class='col-sm-12'>
                <div id="event-selector-wrapper2" class="text-left col-sm-8 nopad">
                  <!-- dropdown will be injected here by JS -->
                </div>
                <div class='col-sm-4' style='display: flex;flex-direction: column;'>
                    <label for="" style='color:transparent'>text</label>
                    <button class='btn btn-sm btn-primary' id='assign'>Assign Template</button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>

    </div>
    </div>


    <div class="">
        <div class="plugin-container">
            <div class="plugin-header">
                <h3>Event Action Schedule</h3> 
                <div class="col-sm-6">
                    <div class="col-sm-7 nopad form-inline text-right">
                        <input class='form-control' type="text" id="search" placeholder="search by key word">
                        &nbsp;
                        <button type="button" class="btn btn-sm btn-primary " id="searchBtn">Search</button>
                    </div>
                    
                    <div class="col-sm-5 nopad form-inline text-right">
                        <button type="button" class="btn btn-primary editTemplate" data-toggle="modal" data-target="#myModal" >Event Template</button>
                        <a class="btn btn-primary btn-sm linkClass" href=''>View Event</a>
                    </div>
                </div>
                
            </div>

            <div id="event-selector-wrapper">
            </div>


            <div class="table-container">
                
                <table class="table-bordered col-sm-12">
                    <thead>
                        <tr>
                            <th style="width: 40px;"></th>
                            <th style="width: 50px;">No.</th>
                            <th class="action-by-col" style="width: 15%;">Action By</th>
                            <th class="actions-col">Actions</th>
                            <th class="status-col">Status</th>
                            <th class="due-date-col">Due Date</th>
                            <th class="reminder-col text-center">Reminder</th>
                            <th class="content-col">Content</th>
                            <th style="width: 100px;">Manage</th>
                        </tr>
                    </thead>
                    <tbody id="task-table-body">
                    </tbody>
                    <tfoot id="task-table-footer"></tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- External Libraries JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        
        // --- MOCK DATA ---
        const eventData = JSON.parse(`<?php echo json_encode($eventData);?>`);

        const statuses = { 
            'Not Started': 'status-not-started', 'Pending': 'status-pending',
            'Overdue': 'status-overdue', 'Complete': 'status-complete', 'NA': 'status-na' 
        };
        let event_id = '<?php echo $_GET['eventId']; ?>';
        let eventIdNumber = parseInt(event_id);
        console.log('eventIdNumber',eventIdNumber);

        let currentEventKey = Object.keys(eventData)[0];
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

        function createEventDropdown(selectedEventKey) {

            let itemsHtml = `<?php

            function extract_event_name($event_title) {
                preg_match('/^(.*?)(?=\s*-*\s*\d|$)/', $event_title, $matches);
                return trim($matches[1]);
            }

            while($results = mysql_fetch_assoc($data)) {
                $clean_event_name = extract_event_name($results['post_title']);
                ?>
                <div class="custom-dropdown-item event-item" data-url='<?php echo $results['post_filename']; ?>' data-event-key="<?php echo extract_event_name($results['post_title']); ?>" id="<?php echo $results['post_id']; ?>">
                    <i class="fa fa-calendar-o" style="margin-right: 8px; color: #888;"></i>
                    <span><?php echo htmlspecialchars($clean_event_name); ?></span>
                </div>
            <?php } ?> `;

            const tempContainer = $('<div>').html(itemsHtml);
            const eventText = tempContainer.find(`#${selectedEventKey} span`).text();
            window.eventName = eventText;

            const dropdownHtml = `
                <div class="form-group custom-dropdown-container" id="event-dropdown">
                    <label>Select Event:</label> 
                    <div class="custom-dropdown-toggle event-toggle">
                        <i class="fa fa-calendar" style="margin-right: 8px; color: #555;"></i>
                        <span class="selected-event-name" id='${selectedEventKey?selectedEventKey:0}'></span>
                    </div>
                    <div class="custom-dropdown-menu">
                        <input type="text" class="form-control custom-dropdown-search-input" placeholder="Search events...">
                        <div class="event-list">${itemsHtml}</div>
                    </div>
                </div>
            `;
            $('#event-selector-wrapper').html(dropdownHtml);
        }

        $('#editEventModal').on('shown.bs.modal', function () {
            createEventDropdown(/* pass selectedEventKey if needed */);
        });

        function createUserDropdown(selectedUserId) {
            let itemsHtml = `
                <div class="custom-dropdown-item user-item" data-user-id="0">
                    <img src="https://www.motiv8search.com/images/profile-profile-holder.png" class='toggleImage'>
                    <span>Select a user</span>
                </div>
                <?php while($user_details = mysql_fetch_assoc($user_data)){
                    $user_id = (int)$user_details['user_id'];
                    
                    // Try to get 'photo' first
                    $photo_query = mysql_query("SELECT file FROM users_photo WHERE user_id = $user_id AND type = 'photo' LIMIT 1");
                    $photo_data  = mysql_fetch_assoc($photo_query);

                    if (!empty($photo_data['file'])) {
                        $img_file = $photo_data['file'];
                        $img_url = "https://www.motiv8search.com/pictures/profile/" . $img_file;
                    } else {
                        // Fallback to 'logo'
                        $logo_query = mysql_query("SELECT file FROM users_photo WHERE user_id = $user_id AND type = 'logo' LIMIT 1");
                        $logo_data  = mysql_fetch_assoc($logo_query);

                        if (!empty($logo_data['file'])) {
                            $img_file = $logo_data['file'];
                            $img_url = "https://www.motiv8search.com/logos/profile/" . $img_file;
                        } else {
                            $img_url = "https://www.motiv8search.com/images/profile-profile-holder.png";
                        }
                    }

                    $first_name = trim($user_details['first_name']);
                    $last_name = trim($user_details['last_name']);
                    $company = trim($user_details['company']);

                    if (!empty($first_name) || !empty($last_name)) {
                        $display_name = htmlspecialchars($first_name . ' ' . $last_name);
                    } else {
                        $display_name = htmlspecialchars($company);
                    }
                ?>
                    <div class="custom-dropdown-item user-item" data-user-id="<?php echo $user_details['user_id']; ?>">
                        <img src="<?php echo $img_url; ?>" alt="<?php echo $display_name; ?>" class='toggleImage'>
                        <span><?php echo $display_name; ?></span>
                    </div>
                <?php } ?>
                `;

            return `
                    <div class="custom-dropdown-menu">
                        <input type="text" class="form-control custom-dropdown-search-input" placeholder="Search users...">
                        <div class="user-list">${itemsHtml}</div>
                    </div>
            `;
        }

        function renderTable(id = null) {
            const tableBody = $('#task-table-body');
            const tasks = id ? (eventData?.[id] || []) : (eventData?.[currentEventKey] || []);
            console.log('id',id);
            console.log('table');
            console.table(tasks);

            const hasNonNullItems = tasks.some(item => item !== undefined);
            if (tasks.length > 0 && !hasNonNullItems) {
                //renderTable(id);
                    //location.reload();
            }
            console.log('hasNonNullItems',hasNonNullItems);

            tableBody.empty();
            const originalArray = tasks;

            const transformedArray = originalArray.map(item => {
                return {
                    id: item.id,
                    action_title: item.action_title
                };
            });
            console.log('transformedArray');
            console.table(transformedArray);
            // ${createUserDropdown(task.action_by)}
            console.log('tasks.length ',tasks.length);
            tasks.forEach((task, index) => {
                    const statusClass = statuses[task.status] || 'status-na';
                    const row = `
                        <tr class="task-row ${statusClass}" data-id="${task.id}" data-priority="${task.priority}">
                            <td class="text-center"><i class="fa fa-bars drag-handle" title="Drag to reorder"></i></td>
                            <td class="text-center index">${index + 1}</td>
                            <td class="action-by-col">
                                <div class="custom-dropdown-container user-dropdown" data-selected-user-id="${task.action_by}" bis_skin_checked="1">
                                    <div class="custom-dropdown-toggle user-toggle" bis_skin_checked="1">
                                        <img src="${task.url}" alt="${task.display_name}">
                                        <span>${task.display_name?task.display_name:"Select a user"}</span>
                                    </div>
                                    ${createUserDropdown()}
                                </div>
                                
                            </td>
                            <td class="actions-col"><span class="editable-text action-text">${task.action_title?task.action_title:""}</span></td>
                            <td class="status-col">
                                <select class="form-control status-select">
                                    ${Object.keys(statuses).map(s => `
                                        <option value="${s}" ${task.status == s ? 'selected' : ''}>${s}</option>
                                    `).join('')}
                                </select>
                            </td>
                            <td class="due-date-col">
                                <input type="date" class="form-control due-date-input" value="${task.due_date}">
                            </td>
                            <td class="text-center reminder-col">
                                <a href="#" class="google-cal-btn" title="Add to Google Calendar">
                                    <i class="fa fa-calendar-plus-o fa-2x"></i>
                                </a>
                                <input type="hidden" class="email-input" data-email="${task.email}">
                            </td>
                            <td class="content-col">
                                <span class="editable-text content-text write_content form-control">${task.content?task.content:""}</span>
                            </td>
                            <td class="action-buttons">
                                <button class="btn btn-danger btn-sm delete-task-btn">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;

                    tableBody.append(row);
                });

                if (tasks.length < 1) {
                    tableBody.html(`
                        <tr>
                            <td colspan="12">
                                <div class='alert alertsuccess col-sm-12 text-center'>
                                    Please assign a template
                                </div>
                            </td>
                        </tr>
                    `);
                }

                renderFooter();
                findUser();
        }

        function findUser(){
                const urlParams = new URLSearchParams(window.location.search);
                const actionIdFromUrl = urlParams.get('actionId');
                if (actionIdFromUrl) {
                    const targetRow = document.querySelector(`tr[data-id="${actionIdFromUrl}"]`);
                    if (targetRow) {
                        targetRow.classList.add('highlight-row');
                    }
                }
        }
        
        function renderFooter() {
            const tableFooter = $('#task-table-footer');
            tableFooter.empty();
            const newRow = `
                <tr id="add-new-row">
                    <td></td><td></td>
                    <td class="action-by-col">
                    <div class="custom-dropdown-container user-dropdown" data-selected-user-id="0" bis_skin_checked="1">
                        <div class="custom-dropdown-toggle user-toggle" bis_skin_checked="1">
                            <span>Select Member</span>
                        </div>
                        ${createUserDropdown()}
                    </div>
                    </td>
                    <td class="actions-col"><input type="text" id="new-action" class="form-control" placeholder="Add new action..."></td>
                    <td class="status-col"><select class="form-control" id="new-status">${Object.keys(statuses).map(s => `<option value="${s}" ${s === 'Not Started' ? 'selected' : ''}>${s}</option>`).join('')}</select></td>
                    <td class="due-date-col"><input type="date" id="new-due-date" class="form-control"></td>
                    <td></td>
                    <td class="content-col"><input type="text" id="new-content" class="form-control" placeholder="Add content..."></td>
                    <td><button class="btn btn-success btn-sm add-new-task-btn"><i class="fa fa-plus"></i> Add</button></td>
                </tr>`;
            tableFooter.append(newRow);
        }

        // --- CORE LOGIC & EVENT HANDLERS ---

        function makeSortable() {
            $('#task-table-body').sortable({
                handle: '.drag-handle',
                placeholder: 'ui-sortable-placeholder',
                helper: 'clone',
                axis: 'y',
                stop: function(event, ui) {
                    const newOrder = {};

                    $('#task-table-body tr.task-row').each(function(index) {
                        const taskId = $(this).data('id');
                        const rowIndex = index + 1;
                        newOrder[taskId] = rowIndex;
                    });
                    newOrder['action'] = 'order';

                    saveToServer(newOrder);
                    
                    //const newOrderIds = $('#task-table-body').sortable('toArray', { attribute: 'data-id' });
                    //eventData[currentEventKey] = newOrderIds.map(id => eventData[currentEventKey].find(task => task.id == id));
                    //renderTable();
                    
                }
            }).disableSelection();
        }

        async function saveToServer(data){
            let url = 'https://www.motiv8search.com/api/widget/json/get/addEvent';
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    if (!res.ok) {
                        throw new Error(`HTTP error! Status: ${res.status}`);
                    }
                    const result = await res.json();
                    if(result.status='Success'){
                        Toast.fire({ icon: 'success', title: 'Order updated!' });
                        location.reload();
                    }else{
                        Toast.fire({icon: 'error',title: 'Task updated failed!'});
                    }
                } catch (e) {
                    console.error('add_event_schedule failed:', e);
                    Swal.fire('Error!', 'Please try again.', 'error');
                    throw e; 
                }
        }
        
        function updateTaskData(taskId, field, value) {
            const task = eventData[currentEventKey].find(t => t.id == taskId);
            if (task) task[field] = value;
        }

        // Global click to close dropdowns
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-dropdown-container').length) {
                $('.custom-dropdown-menu').hide();
            }
        });
        
        // Delegated handlers for dynamic content
        const pluginContainer = $('.plugin-container');

        //Custom Dropdown Search
        pluginContainer.on('keyup', '.custom-dropdown-search-input', function() {
            const searchTerm = $(this).val().toLowerCase();
            const items = $(this).next('div').find('.custom-dropdown-item');
            items.each(function() {
                const text = $(this).find('span').text().toLowerCase();
                $(this).toggleClass('hidden', text.indexOf(searchTerm) === -1);
            });
        });

        // Custom Dropdown Toggle
        pluginContainer.on('click', '.custom-dropdown-toggle', function(e) {
            e.stopPropagation();
            const menu = $(this).next('.custom-dropdown-menu');
            $('.custom-dropdown-menu').not(menu).hide();
            menu.toggle();
        });


        pluginContainer.on('click', '.event-item', function() {
            const currentEventKey = $(this).data('event-key');
            const currentEventUrl = $(this).data('url');
            const id = $(this).attr('id');
            const selectedEventElement = $('#event-dropdown').find('.selected-event-name');
            const linkButton = $('.plugin-header').find('.linkClass');
            linkButton.attr('href', 'https://www.motiv8search.com/'+currentEventUrl);
            selectedEventElement.text(currentEventKey);
            selectedEventElement.attr('id', id);

            $(this).closest('.custom-dropdown-menu').hide();
            renderTable();
        });

        // User Selection
        pluginContainer.on('click', '.user-item', function () {
            const dropdown = $(this).closest('.user-dropdown');
            const userId = $(this).data('user-id');

            const userImg = $(this).find('img').attr('src');
            const userName = $(this).find('span').text().trim();

            dropdown.data('selected-user-id', userId);

            const toggle = dropdown.find('.user-toggle');
            toggle.find('img').attr('src', userImg).attr('alt', userName);
            toggle.find('span').text(userName);

            $(this).closest('.custom-dropdown-menu').hide();


            const row = $(this).closest('tr');
            if ($row.closest('tfoot').length) return;
            const selectedEventId = $('.selected-event-name').attr('id');

            if (!selectedEventId || selectedEventId == 0) {
                alert('Please Select an Event');
                return;
            }

            // Extract values from this row
            const id  = row.data('id');  // from <tr data-id="">
            const userId2 = row.find('.user-dropdown').data('selected-user-id');
            const status = row.find('.status-select').val();
            const dueDate = row.find('.due-date-input').val();
            const content = row.find('.content-text').text() || '';
            const actionData = row.find('.action-text').text() || '';

            const newTask = {
                id,
                userId2,
                userId,
                actionData,
                event_name: $('.selected-event-name').text(),
                eventId: selectedEventId,
                status,
                dueDate,
                content,
                action: 'edit'
            };

            console.log('edit1',newTask);

            // can safely call updateTaskData
            add_event_schedule(newTask);

            if (row.hasClass('task-row')) {
                updateTaskData(row.data('id'), 'userId', userId);
                Toast.fire({ icon: 'success', title: 'User updated!' });
            }
        });

        // Add New Task
        $('#task-table-footer').on('click', '.add-new-task-btn', function() {
            const actionData = $('#new-action').val();
            if (!actionData) {
                Swal.fire('Action Required', 'The "Actions" field cannot be empty.', 'error');
                return;
            }
            const selectedEventId = $('.selected-event-name').attr('id');
            if(selectedEventId == 0){
                alert('Please Select an Event');
                return;
            }
            const newTask = {
                userId: parseInt($('#add-new-row .user-dropdown').data('selected-user-id')),
                actionData: actionData,
                event_name: $('.selected-event-name').text(),             
                eventId: selectedEventId,           
                status: $('#new-status').val(),
                dueDate: $('#new-due-date').val(),
                content: $('#new-content').val(),
                action: 'add',
            };

            //alert(JSON.stringify(newTask, null, 2));
            add_event_schedule(newTask);
        });

        let url = 'https://www.motiv8search.com/api/widget/json/get/addEvent'
        async function add_event_schedule(data) {
            let action = data.action;
            let dueDate = data.dueDate;
        
            if (dueDate && action != 'edit') {
                const selectedDate = new Date(dueDate);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    Toast.fire({icon: 'error',title: 'Task updated failed!'});
                    Swal.fire('Invalid Date', 'Due date cannot be in the past.', 'warning');
                    return;
                }
            }

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                if (!res.ok) {
                    throw new Error(`HTTP error! Status: ${res.status}`);
                }
                const result = await res.json();
                //renderTable(result);
                if(action=='add'){
                    Swal.fire('Task Added!', 'The new task has been added.', 'success');
                    location.reload();
                }else{
                    Toast.fire({ icon: 'success', title: 'Task updated!' });
                    location.reload();
                }
            } catch (e) {
                console.error('add_event_schedule failed:', e);
                Swal.fire('Error!', 'Please try again.', 'error');
                throw e; 
            }
        }

        // Other table inputs
        // A separate function to handle the task update logic
        function updateTaskFromRow(row) {
            const selectedEventId = $('.selected-event-name').attr('id');

            if (!selectedEventId || selectedEventId == 0) {
                alert('Please Select an Event');
                return;
            }

            // Extract values from the row passed to the function
            const id = row.data('id');
            const userId = row.find('.user-dropdown').data('selected-user-id');
            const status = row.find('.status-select').val();
            const dueDate = row.find('.due-date-input').val();

            const content = row.find('.content-text').text() || row.find('.content-text input').val();
            const actionData = row.find('.action-data-input').val() || row.find('.action-text').text();

            const newTask = {
                id,
                userId,
                actionData,
                event_name: $('.selected-event-name').text(),
                eventId: selectedEventId,
                status,
                dueDate,
                content,
                action: 'edit'
            };

            console.log('edit2',newTask);

            add_event_schedule(newTask);
        }

        $('#task-table-body').on('change', '.status-select, .due-date-input', function() {
            const row = $(this).closest('tr');
            if ($(this).hasClass('status-select')) {
                Object.keys(statuses).forEach(key => row.removeClass(statuses[key]));
                row.addClass(statuses[$(this).val()]);
            }
            
            updateTaskFromRow(row);
        });


        
        $('#task-table-body').on('click', '.delete-task-btn', function() {
            const row = $(this).closest('tr');
            const taskId = row.data('id');

            const taskData = {
                id:taskId,
                action:'delete'
            }
            
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const taskIndex = eventData[currentEventKey].findIndex(t => t.id === taskId);
                    if (taskIndex > -1) eventData[currentEventKey].splice(taskIndex, 1);
                    renderTable();
                    row.remove();
                    add_event_schedule(taskData);
                    Swal.fire('Deleted!', 'The task has been deleted.', 'success');
                }
            });
        });

        //mac code

    $('#task-table-body').on('click', '.google-cal-btn', function(e) {
        e.preventDefault();
        let row = $(this).closest('tr');
        let taskId = row.data('id');

        // Get all necessary data from the row
        let userName = row.find('.custom-dropdown-container.user-dropdown .custom-dropdown-toggle span').text();
        let userEmail = row.find('.email-input').data('email');
        let dueDate = row.find('.due-date-input').val();
        let action = row.find('.editable-text.action-text').text();
        let content = row.find('.editable-text.content-text').text();
        let eventName = $('.selected-event-name').text();
        let eventId = $('.selected-event-name').attr('id');

        if (!dueDate) {
            Swal.fire('Missing Date', 'Please set a due date for this task to create a calendar reminder.', 'warning');
            return;
        }

        // --- Helper functions ---
        function formatDateToICS(date) {
            return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
        }

        function icsEscape(text) {
            return (text || '')
                .replace(/\\/g, "")  // escape backslashes
                .replace(/\n/g, "\\n")   // line breaks
                .replace(/,/g, "\\,")    // commas
                .replace(/;/g, "\\;")    // semicolons
                .trim();
        }

        try {
            action = icsEscape(action);
            content = icsEscape(content);
            userName = icsEscape(userName);
            eventName = icsEscape(eventName);
        } catch (error) {
            console.error("An error occurred while escaping text for .ics file:", error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong with the calendar export. Please try again.',
            });
            return;
        }

        // --- Dates ---
        const startDate = formatDateToICS(new Date(dueDate + "T09:00:00"));
        const endDate = formatDateToICS(new Date(dueDate + "T10:00:00"));
        const dtstamp = formatDateToICS(new Date());

        // --- Create the .ics content (NO indentation) ---
        let icsContent = `BEGIN:VCALENDAR
    VERSION:2.0
    PRODID:-//motiv8search.com//NONSGML v1.0//EN
    BEGIN:VEVENT
    UID:${taskId}-${Date.now()}@motiv8search.com
    DTSTAMP:${dtstamp}
    DTSTART:${startDate}
    DTEND:${endDate}
    SUMMARY:${action}
    DESCRIPTION:Event: ${eventName}\\nContent: ${content}\\nAssigned to: ${userName}
    END:VEVENT
    END:VCALENDAR`;

        // Apple Calendar requires CRLF line endings
        icsContent = icsContent.replace(/\n/g, "\r\n");

        // --- Create a Blob from the .ics content and trigger a download ---
        const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${action.substring(0, 20)}.ics`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // --- Success message ---
        Swal.fire('Success', 'Calendar file downloaded!', 'success');
    });


        $('#task-table-body').on('click', '.editable-text', function() {
            const span = $(this);
            span.removeClass('form-control');
            if (span.find('input').length) { return;};
            let currentText = span.text();
            if(currentText == ''){
                currentText = '';
            }
            const input = $('<input type="text" class="form-control">').val(currentText);
            span.html(input);
            input.focus();

            input.on('blur keyup', function(e) {
                if (e.type === 'blur' || e.key === 'Enter') {
                    const newText = $(this).val()?$(this).val():'';
                    const row = $(this).closest('tr');
                    const taskId = row.data('id');
                    const fieldClass = span.hasClass('action-text') ? 'action' : 'content';
                    updateTaskData(taskId, fieldClass, newText);
                    span.text(newText);
                    span.addClass('form-control');
                    updateTaskFromRow(row);
                }
            });
        });
        
        // --- INITIALIZATION ---

        let lastEventKeyFromBrowser = JSON.parse(localStorage.getItem('ID'));
        let lastEventUrlFromBrowser = JSON.parse(localStorage.getItem('url'));

        const urlParams = new URLSearchParams(window.location.search);
        const eventIdFromUrl = urlParams.get('eventId');

        const finalEventKey = eventIdFromUrl || lastEventKeyFromBrowser || currentEventKey;

    

        const initLinkButton = $('.plugin-header').find('.linkClass');
        initLinkButton.attr('href', lastEventUrlFromBrowser);

        createEventDropdown(finalEventKey);
        renderTable(finalEventKey);

        makeSortable();
        $('.selected-event-name').text(window.eventName?window.eventName:"Select an Event");

    document.addEventListener('click', function (e) {
        const item = e.target.closest('.event-item'); 
        if (!item) return;
        const newAction = document.getElementById('new-action');
        const eventId   = item.id || '';
        const eventKey  = item.dataset.eventKey || '';
        const eventURL  = item.dataset.url || '';
        const eventName = (item.querySelector('span')?.textContent || '').trim();

        const hiddenInput = document.createElement('input');
        hiddenInput.type  = "hidden";
        hiddenInput.id    = eventId;
        hiddenInput.value = eventName;
        hiddenInput.name  = 'eventId';

        if (newAction) {
            newAction.insertAdjacentElement('afterend', hiddenInput);
        }

        //alert(`ID: ${eventId}\nKey: ${eventKey}\nName: ${eventName}`);

        localStorage.setItem('ID',JSON.stringify(eventId));
        localStorage.setItem('Key',JSON.stringify(eventKey));
        localStorage.setItem('Name',JSON.stringify(eventName));
        localStorage.setItem('url',JSON.stringify('https://www.motiv8search.com/'+eventURL));

        let currentData = JSON.stringify(eventData[`${eventId}`]);
        console.log('currentData',currentData);
        
        // let url = new URL(window.location.href);
        // url.searchParams.set('eventId', eventId);
        // window.location.href = url.toString();

        renderTable(eventId);
    });

    document.querySelector(".add-new-task-btn").addEventListener("click", function () {
        const userDropdown = document.querySelector(".user-dropdown");
        const userId   = userDropdown.getAttribute("data-selected-user-id");
        const userName = userDropdown.querySelector(".custom-dropdown-toggle span").textContent.trim();

        const action   = document.getElementById("new-action").value.trim();
        const status   = document.getElementById("new-status").value;
        const dueDate  = document.getElementById("new-due-date").value;
        const content  = document.getElementById("new-content").value.trim();

        if (!userId || userId === "0" || !action || !status || status === "NA" || !dueDate || !content) {
        alert("Please fill all fields before adding the task.");
        } else {
        //alert('working');
        }
    });

    const thDueDate = document.querySelector("th.due-date-col");
    const tbody = document.getElementById("task-table-body");
    if (!thDueDate || !tbody) return;

    let sortState = 0; 

    const getRows = () => Array.from(tbody.querySelectorAll("tr"));
    const originalOrder = getRows(); 
    const originalIds = originalOrder.map(r => String(r.dataset.id || "").trim());

    function parseTime(val) {
        if (!val || val === "0000-00-00") return null;
        const t = Date.parse(val);
        return isNaN(t) ? null : t;
    }

    function sortRows(direction) {
        const rows = getRows();
        if (!direction) {
    
            const presentOriginal = originalOrder.filter(r => rows.includes(r));
            const presentOriginalIds = new Set(presentOriginal.map(r => String(r.dataset.id || "").trim()));
            const extras = rows.filter(r => !presentOriginalIds.has(String(r.dataset.id || "").trim()));
            const finalRows = presentOriginal.concat(extras);
            tbody.innerHTML = "";
            finalRows.forEach(r => tbody.appendChild(r));
            return;
        }

        rows.sort((a, b) => {
            const tA = parseTime(a.querySelector(".due-date-input")?.value);
            const tB = parseTime(b.querySelector(".due-date-input")?.value);


            if (tA === null && tB === null) return 0;
        
            if (tA === null) return 1;
            if (tB === null) return -1;

            return direction === "asc" ? tA - tB : tB - tA;
        });

        tbody.innerHTML = "";
        rows.forEach(r => tbody.appendChild(r));
    }

    thDueDate.style.cursor = "pointer";
    thDueDate.addEventListener("click", function () {
        sortState = (sortState + 1) % 3;
        if (sortState === 0) renderTable(JSON.parse(localStorage.getItem('ID')));         
        else if (sortState === 1) sortRows("asc");   
        else sortRows("desc");                       
    });


});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ====== CONSTANTS / ELEMENTS ======
    const API       = 'https://www.motiv8search.com/api/widget/json/get/edit_template';
    const btn       = document.querySelector('.editTemplate');
    const modal     = document.getElementById('myModal');
    const modalBody = modal?.querySelector('.modal-body');
    const headerWrap= document.getElementById('event-selector-wrapper2');

    if (!btn || !modal || !modalBody || !headerWrap) return;

    // ====== LOCAL STATE ======
    const state = {
        eventId: 0,       // selected event
        templateId: 0,    // selected template
        users: [],
        edits: new Map(),
        order: [],
        dirty: false
    };

    window.url=API;


    function markDirty(on = true) {
        state.dirty = !!on;
        const saveBtn = modal.querySelector('.save_data');
        if (!saveBtn) return;
        saveBtn.classList.toggle('btn-warning', state.dirty);
        saveBtn.textContent = state.dirty ? 'Save all the Changes (unsaved)' : 'Save all the Changes';
    }

    let saveBound = false;
    function bindSaveButtonOnce() {
        if (saveBound) return;
        const saveBtn = modal.querySelector('.save_data');
        if (!saveBtn) return;
        saveBtn.addEventListener('click', onSaveAll);
        saveBound = true;
    }

    // ====== INIT ON FIRST OPEN ONLY ======
    $('#myModal').one('shown.bs.modal', initModal);
    window.countRequests = 0;
    async function initModal() {
        window.countRequests++;
        // Clear previous body rows (if any)
        const m8taskbody = modal.querySelector('.m8-task-body');
        if (m8taskbody) m8taskbody.innerHTML = '';

        const loading = modal.querySelector('.alert-success');
        if (loading) loading.style.display = 'block';

        try {
            // fetch events + users in parallel
            const [eventsRes, usersRes, templatesRes] = await Promise.all([
                fetch(API, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'events' })}),
                fetch(API, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'users' })}),
                fetch(API, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'templates' })})
            ]);

            if (!eventsRes.ok || !usersRes.ok) throw new Error('Network error');

            if (loading) loading.style.display = 'none';

            const eventsJson    = await eventsRes.json();
            const usersJson     = await usersRes.json();
            const templatesJson = await templatesRes.json();

            const events        = Array.isArray(eventsJson.events) ? eventsJson.events : [];
            const users         = Array.isArray(usersJson.users)  ? usersJson.users  : [];
            const templates     = Array.isArray(templatesJson.templates) ? templatesJson.templates : [];
            state.users  = users;

            // Preselect from page if present, else first event
            // const pageSel = document.querySelector('#event-selector-wrapper .selected-event-name');
            // const preId   = pageSel?.id ? parseInt(pageSel.id, 10) : (events[0]?.post_id || 0);
            // const preName = pageSel?.textContent?.trim() || (events[0]?.eventName || 'Select Event');
            // const preHref = (document.querySelector('.plugin-header .linkClass')?.getAttribute('href')) || '';

            const preId   = 0;
            const preName = 'Select Event';
            const preHref = '';
            // Build event dropdown
            headerWrap.innerHTML = 
                buildEventDropdownHTML(events, preId, preName, preHref) //+
                //buildTemplateDropdownHTML(templates, templates[0]?.template_id || 0, templates[0]?.template_name || 'Select Template');


            // Ensure table skeleton and loader row
            ensureModalTableSkeleton();

            // Initialize state for selected event
            state.eventId = preId || 0;
            state.edits.clear();
            state.order = [];
            markDirty(false);

            // Load rows for preselected event
            //if (preId) await loadAndRenderEventRows(preId, users);

            if (templates.length) {
                state.templateId = templates[0].template_id;
                await loadAndRenderTemplateRows(state.templateId);
            }

            // Wire modal-only handlers ONCE
            wireModalOnlyHandlers(users);

            // Bind save button ONCE
            bindSaveButtonOnce();

        } catch (err) {
            // do the destructive/confirm action
            if(window.countRequests != 10){
                initModal();
                return;
            }

           console.error(err);
           Swal.fire({
                icon: 'warning',
                title: 'Failed to load the template data!',
                text: 'Please confirm to reload the page and try again.',
                showCancelButton: true,
                confirmButtonText: 'Reload',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#DD6B55',
                allowOutsideClick: false,
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // do the destructive/confirm action
                    window.location.reload();
                } else {
                    // optional: follow-up notice
                    Swal.fire({
                    icon: 'info',
                    title: 'Cancelled',
                    text: 'No changes were made.',
                    timer: 1800,
                    showConfirmButton: false,
                    heightAuto: false
                    });
                }
            });
        if (typeof swal === 'function') {
            swal("Cancelled", "Your  file is safe :)", "error");
        }
        }
    }

    async function loadAndRenderTemplateRows(templateId) {
        //alert('working');
        const loading = modal.querySelector('.alert-success');
        if (loading) loading.style.display = 'block';
        let tbody  = document.querySelector('.m8-task-body');
        tbody.innerHTML = '';
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type':'application/json' },
                body: JSON.stringify({ action: 'template_data', template_id: templateId })
            });
            if (!res.ok) throw new Error('template_data failed');
            if (loading) loading.style.display = 'none';

            const json = await res.json();
            const rows = Array.isArray(json.data) ? json.data : [];
            renderModalRows(rows, state.users, templateId);
        } catch (e) {
            console.error(e);
            if (loading) loading.style.display = 'none';
            renderModalRows([], state.users, templateId);
        }
    }


    // ====== UI BUILDERS ======
    function buildEventDropdownHTML(events, selectedId, selectedName, href) {
        const items = events.map(e => (`
        <div class="m8-dropdown-item m8-event-item"
            data-url="${e.post_filename}"
            data-event-key="${cleanEventName(e.eventName)}"
            id="${e.post_id}">
            <i class="fa fa-calendar-o" style="margin-right:8px;color:#888;"></i>
            <span>${cleanEventName(e.eventName)}</span>
        </div>
        `)).join('');

        return `
        <div class="m8-dropdown m8-event-dropdown" id="m8-event-dropdown">
            <label>Please select an event to assign a template.</label>
            <div class="m8-toggle m8-event-toggle">
            <i class="fa fa-calendar" style="margin-right:8px;color:#555;"></i>
            <span class="m8-selected-event-name" id="${selectedId}">${cleanEventName(selectedName)}</span>
            </div>
            <div class="m8-menu m8-event-menu" style="display:none;">
            <input type="text" class="form-control m8-search" placeholder="Search events...">
            <div class="m8-event-list">${items}</div>
            </div>
        </div>`;
    }

    function buildTemplateDropdownHTML(templates, selectedId, selectedName) {
        const items = templates.map(t => (`
            <div class="m8-dropdown-item m8-template-item"
                data-template-id="${t.template_id}">
                <i class="fa fa-clone" style="margin-right:8px;color:#888;"></i>
                <span>${escapeHtml(t.template_name)}</span>
                 <i class="fa fa-trash m8-template-delete" style="color:red;margin-left:auto;"></i>
            </div>
        `)).join('');

        // return `
        // <div class="m8-dropdown m8-template-dropdown" id="m8-template-dropdown">
        //     <label>Select Template:</label>
        //     <div class="m8-toggle m8-template-toggle">
        //         <i class="fa fa-clone" style="margin-right:8px;color:#555;"></i>
        //         <span class="m8-selected-template-name" id="${selectedId}">${escapeHtml(selectedName)}</span>
        //     </div>
        //     <div class="m8-menu m8-template-menu" style="display:none;">
        //         <input type="text" class="form-control m8-search" placeholder="Search templates...">
        //         <div class="m8-template-list">${items}</div>
        //         <div class="m8-dropdown-item m8-template-create">
        //             <i class="fa fa-plus" style="margin-right:8px;color:#28a745;"></i>
        //             <span>Create New Template</span>
        //         </div>
        //     </div>
        // </div>`;
        return '';
    }



   function ensureModalTableSkeleton() {
        const table = modalBody.querySelector('table');
        if (!table) return;

        // remove existing body + footers
        table.querySelectorAll('tbody, tfoot').forEach(el => el.remove());

        // header stays as is...
        let thead = table.querySelector('thead');
        if (!thead) {
            table.insertAdjacentHTML('afterbegin', `
            <thead class="m8-thead">
                <tr>
                <th style="width:40px;"></th>
                <th style="width:50px;">No.</th>
                <th style="min-width:200px;">Action By</th>
                <th style="min-width:300px;">Actions</th>
                </tr>
            </thead>
            `);
        }

        table.insertAdjacentHTML('beforeend', `
            <tbody class="m8-task-body">
            <tr>
                <td colspan="4">
                <div class="alert alert-success alertsuccess" style="display:none;">
                    Fetching latest data <div class="loader"></div>
                </div>
                </td>
            </tr>
            </tbody>
            <tfoot class="m8-task-footer">
            <tr>
                <td></td>
                <td></td>
                <td>
                <div class="m8-dropdown m8-user-dropdown" data-selected-user-id="0">
                    <div class="m8-toggle m8-user-toggle">
                    <img src="https://www.motiv8search.com/images/profile-profile-holder.png" alt="Select User">
                    <span>Select User</span>
                    </div>
                    ${buildUserMenuHTML(state.users || [])}
                </div>
                </td>
                <td>
                <input type="text" class="form-control m8-new-action" placeholder="Add new action...">
                </td>
                <td>
                <button class="btn btn-success btn-sm m8-add-row">
                    <i class="fa fa-plus"></i> Add
                </button>
                </td>
            </tr>
            </tfoot>
        `);
    }


    // ====== DATA LOADING / RENDERING ======
    async function loadAndRenderEventRows(eventId, users) {
        const loading = modal.querySelector('.alert-success');
        if (loading) loading.style.display = 'block';
        let tbody  = document.querySelector('.m8-task-body');
        tbody.innerHTML = '';
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ action: 'event_data', event_id: eventId })
            });
            if (!res.ok) throw new Error('event_data failed');
            if (loading) loading.style.display = 'none';

            const json = await res.json();
            const rows = Array.isArray(json.data) ? json.data : [];
            renderModalRows(rows, users, eventId);
        } catch (e) {
            console.error(e);
            if (loading) loading.style.display = 'none';
            renderModalRows([], users, eventId);
        }
    }

    function renderModalRows(rows, users, eventId) {
        //alert(JSON.stringify(users.url));
        const tbody = modalBody.querySelector('tbody.m8-task-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        tbody.setAttribute('data-event-id', String(eventId));

        rows.forEach((r, i) => {
        const user    = users.find(u => u.user_id === Number(r.action_by));
        const name    = user?.name || r.display_name || 'Select a user';
        const img     = user?.url || 'https://www.motiv8search.com/images/profile-profile-holder.png';
        const tr      = document.createElement('tr');

        tr.className        = `m8-task-row ${statusClass(r.status)}`;
        tr.dataset.id       = r.id;
        tr.dataset.priority = r.priority ?? (i + 1);

        tr.innerHTML = `
            <td class="text-center"><i class="fa fa-bars m8-drag" title="Drag to reorder"></i></td>
            <td class="text-center m8-index">${i + 1}</td>
            <td class="m8-action-by">
            <div class="m8-dropdown m8-user-dropdown" data-selected-user-id="${r.action_by || 0}">
                <div class="m8-toggle m8-user-toggle">
                <img src="${img}" alt="${escapeHtml(name)}">
                <span>${escapeHtml(name)}</span>
                </div>
                ${buildUserMenuHTML(users)}
            </div>
            </td>
            <td class="m8-actions">
            <span class="m8-editable m8-action-text form-control">${escapeHtml(r.action_title || '')}</span>
            </td>
            <td class="text-center">
            <button class="btn btn-danger btn-sm m8-delete-task" data-id="${r.id}">
                <i class="fa fa-trash"></i>
            </button>
            </td>
        `;
        tbody.appendChild(tr);
        });

        // Reset order cache to current render (fresh)
        const list = [...tbody.querySelectorAll('tr.m8-task-row')];
        state.order = list.map((tr, i) => ({ id: Number(tr.dataset.id), priority: i + 1 }));

        makeModalSortable();
    }

    // ====== MODAL HANDLERS (no auto-save; only cache) ======
    function wireModalOnlyHandlers(users) {
        // Close menus (modal-scope)
        document.addEventListener('click', (e) => {
        if (!e.target.closest('#myModal .m8-dropdown')) {
            modal.querySelectorAll('.m8-menu').forEach(m => m.style.display = 'none');
        }
        });

        modal.addEventListener('click', async (e) => {
            const delBtn = e.target.closest('.m8-delete-task');
            if (!delBtn) return;

            const taskId = delBtn.dataset.id;

            const confirm = await Swal.fire({
                icon: 'warning',
                title: 'Delete this action?',
                text: 'This will permanently remove it from the template.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            });

            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id: taskId, template_id: state.templateId })
                });

                const json = await res.json();
                if (json.status === 'success') {
                    Swal.fire('Deleted!', 'Action removed successfully.', 'success');
                    await loadAndRenderTemplateRows(state.templateId); // refresh table
                } else {
                    Swal.fire('Error', json.message || 'Delete failed', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Network issue deleting row', 'error');
            }
        });


        modal.addEventListener('click', async (e) => {
            const addBtn = e.target.closest('.m8-add-row');
            if (!addBtn) return;

            const tfoot   = addBtn.closest('tfoot');
            const userId  = Number(tfoot.querySelector('.m8-user-dropdown')?.dataset.selectedUserId || 0);
            const action  = tfoot.querySelector('.m8-new-action')?.value.trim();

            if (!action) {
                Swal.fire('Missing Data', 'Please enter an action.', 'warning');
                return;
            }

            if (!state.templateId) {
                Swal.fire('Missing Template', 'Please select a template first.', 'warning');
                return;
            }

            const payload = {
                action: 'add',
                template_id: state.templateId,
                userId,
                actionData: action
            };

            try {
                const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type':'application/json' },
                body: JSON.stringify(payload)
                });
                const json = await res.json();
                if (json.status === 'success') {
                Swal.fire('Added!', 'New action added to template.', 'success');
                await loadAndRenderTemplateRows(state.templateId);
                } else {
                Swal.fire('Error', json.message || 'Failed to add action', 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Network error adding new row.', 'error');
            }
        });


        // Toggle menus
        modal.addEventListener('click', (e) => {
            const toggle = e.target.closest('#myModal .m8-toggle');
            if (!toggle) return;
            e.stopPropagation();
            const menu = toggle.nextElementSibling;
            modal.querySelectorAll('.m8-menu').forEach(m => { if (m !== menu) m.style.display = 'none'; });
            if (menu) menu.style.display = (menu.style.display === 'none' || !menu.style.display) ? 'block' : 'none';
        });

        // Search filter (events/users)
        modal.addEventListener('keyup', (e) => {
            if (!e.target.matches('#myModal .m8-search')) return;
            const term = e.target.value.toLowerCase();
            const list = e.target.parentElement.querySelector('.m8-event-list, .m8-user-list');
            if (!list) return;
            list.querySelectorAll('.m8-dropdown-item').forEach(item => {
                const text = (item.querySelector('span')?.textContent || '').toLowerCase();
                item.classList.toggle('hidden', text.indexOf(term) === -1);
            });
        });

        // Select event in modal
        modal.addEventListener('click', async (e) => {
            const item = e.target.closest('#myModal .m8-event-item');
            if (!item) return;

            const id = item.id || '0';
            const name = item.dataset.eventKey || '';

            const selSpan = modal.querySelector('#m8-event-dropdown .m8-selected-event-name');
            if (selSpan) { selSpan.textContent = name; selSpan.id = id; }

            item.closest('.m8-menu')?.style?.setProperty('display', 'none');

            // Only set eventId, don't reload table
            state.eventId = Number(id) || 0;
            markDirty(false);
        });


        modal.addEventListener('click', async (e) => {
            const item = e.target.closest('#myModal .m8-template-item');
            if (!item) return;

            const tid = Number(item.dataset.templateId || 0);
            const tname = item.querySelector('span')?.textContent || 'Template';

            const selSpan = modal.querySelector('#m8-template-dropdown .m8-selected-template-name');
            if (selSpan) { selSpan.textContent = tname; selSpan.id = tid; }

            item.closest('.m8-menu')?.style?.setProperty('display', 'none');

            state.templateId = tid;
            await loadAndRenderTemplateRows(tid);
        });

        // Handle "Create Template" click
        modal.addEventListener('click', (e) => {
            const createBtn = e.target.closest('#myModal .m8-template-create');
            if (!createBtn) return;

            // Close menu
            createBtn.closest('.m8-menu')?.style?.setProperty('display', 'none');

            // Replace table with form
            const tbody = modalBody.querySelector('tbody.m8-task-body');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr><td colspan="4">
                    <form id="create-template-form" class="p-3">
                        <div class="form-group">
                            <label>Template Name</label>
                            <input type="text" name="template_name" class="form-control" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Save Template</button>
                        <button type="button" class="btn btn-secondary mt-3 cancel-create">Cancel</button>
                    </form>
                </td></tr>
            `;

            // Handle submit
            const form = tbody.querySelector('#create-template-form');
            form.addEventListener('submit', async (ev) => {
                ev.preventDefault();
                const name = form.template_name.value.trim();
                const desc = form.description.value.trim();

                if (!name) {
                    alert("Template Name is required");
                    return;
                }

                try {
                    const res = await fetch(API, {
                        method: 'POST',
                        headers: { 'Content-Type':'application/json' },
                        body: JSON.stringify({
                            action: 'create_template',
                            template_name: name,
                            description: desc
                            // `created_by` should be filled server-side from session/admin
                        })
                    });
                    const json = await res.json();
                    if (json.status === 'success') {
                        Swal.fire('Created!', 'Template created successfully', 'success')
                        .then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', json.message || 'Failed to create template', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('Error', 'Network error', 'error');
                }
            });

            // Cancel button → reload current template table
            const cancelBtn = tbody.querySelector('.cancel-create');
            cancelBtn.addEventListener('click', () => {
                loadAndRenderTemplateRows(state.templateId);
            });
        });

        modal.addEventListener('click', async (e) => {
            const delBtn = e.target.closest('.m8-template-delete');
            if (!delBtn) return;

            const templateItem = delBtn.closest('.m8-template-item');
            const tid = templateItem.dataset.templateId;

            const confirm = await Swal.fire({
                icon: 'warning',
                title: 'Delete this template?',
                text: 'This will also delete all related actions.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            });

            if (!confirm.isConfirmed) return;

            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type':'application/json' },
                body: JSON.stringify({ action: 'delete_template', template_id: tid })
            });
            const json = await res.json();

            if (json.status === 'success') {
                Swal.fire('Deleted!', 'Template removed successfully.', 'success');
                // reload template dropdown
                window.location.reload();
            } else {
                Swal.fire('Error', json.message || 'Failed to delete template', 'error');
            }
        });


        // Select user in modal (cache only)
        modal.addEventListener('click', (e) => {
            const item = e.target.closest('#myModal .m8-user-item');
            if (!item) return;

            const userId = Number(item.dataset.userId || 0);
            const name   = (item.querySelector('span')?.textContent || '').trim();
            const img    = item.querySelector('img')?.getAttribute('src') || 'https://www.motiv8search.com/images/profile-profile-holder.png';

            const dropdown = item.closest('.m8-user-dropdown');
            dropdown?.setAttribute('data-selected-user-id', String(userId));

            const toggle = dropdown?.querySelector('.m8-user-toggle');
            if (toggle) {
                toggle.querySelector('img')?.setAttribute('src', img);
                const s = toggle.querySelector('span'); if (s) s.textContent = name;
            }
            item.closest('.m8-menu')?.style?.setProperty('display', 'none');

            const row = item.closest('tr.m8-task-row');
            if (row) upsertEditFromRow(row);
        });

        // Inline edit (Actions) in modal (cache only)
        modal.addEventListener('click', (e) => {
            const span = e.target.closest('#myModal .m8-action-text');
            if (!span || span.querySelector('input')) return;

            const val = span.textContent.trim();
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = val;
            span.classList.remove('form-control');
            span.textContent = '';
            span.appendChild(input);
            input.focus();

            const commit = () => {
                span.textContent = input.value.trim();
                span.classList.add('form-control');
                const row = span.closest('tr.m8-task-row');
                if (row) upsertEditFromRow(row);
            };
            input.addEventListener('blur', commit);
            input.addEventListener('keyup', (k) => { if (k.key === 'Enter') commit(); });
        });
    }

    // ====== CACHE HELPERS (no network) ======
    function upsertEditFromRow(rowEl) {
        const id        = Number(rowEl.dataset.id);
        if (!id) return;
        const userId    = Number(rowEl.querySelector('.m8-user-dropdown')?.getAttribute('data-selected-user-id') || 0);
        const actionTxt = rowEl.querySelector('.m8-action-text')?.textContent?.trim() || '';

        const payload = state.edits.get(id) || { id, eventId: state.eventId };
        payload.userId     = userId;
        payload.actionData = actionTxt;

        state.edits.set(id, payload);
        markDirty(true);
    }

    function makeModalSortable() {
        const $tbody = $(modal).find('tbody.m8-task-body');
        if (!$tbody.length || typeof $tbody.sortable !== 'function') return;
        $tbody.sortable({
        handle: '.m8-drag',
        placeholder: 'ui-sortable-placeholder',
        helper: 'clone',
        axis: 'y',
        stop: function () {
            // reindex UI
            $tbody.find('tr .m8-index').each(function (i) { $(this).text(i + 1); });
            // capture order in state
            state.order = [];
            $tbody.find('tr.m8-task-row').each(function(i, tr){
            state.order.push({ id: Number(tr.dataset.id), priority: i + 1 });
            });
            markDirty(true);
        }
        }).disableSelection && $tbody.disableSelection();
    }

    // ====== SAVE ALL (single click -> minimal network) ======
    async function onSaveAll() {
        // Swal.fire('This feature is currently under development and has been disabled by the developers.Please wait for updates..', ' We are currently working on this', 'info');
        // return;
        //if (!state.eventId) { alert('Please select an Event first'); return; }
        if (!state.dirty) {
        if (window.Swal) Swal.fire('Nothing to save', 'No changes detected.', 'info');
        return;
        }
        
        

        const loading = modal.querySelector('.alert-success');
        if (loading) loading.style.display = 'block';

        try {
        // 1) Push order if captured
        if (state.order.length) {
            const orderBody = state.order.reduce((acc, r) => (acc[r.id] = r.priority, acc), { 
                action: 'order', 
                event_id: state.eventId,
                template_id: state.templateId
            });
            await fetch(API, { method: 'POST', headers: { 'Content-Type':'application/json' }, body: JSON.stringify(orderBody) });
        }

        // 2) Push edits
        const updates = Array.from(state.edits.values());
        if (updates.length) {
            await Promise.all(updates.map(p =>
            fetch(API, {
                method: 'POST',
                headers: { 'Content-Type':'application/json' },
                body: JSON.stringify({
                    action     : 'update',
                    id         : p.id,
                    eventId    : state.eventId,    // which event is using it
                    templateId : state.templateId, // which template it belongs to
                    userId     : p.userId ?? 0,
                    actionData : p.actionData ?? ''
                })

            })
            ));
        }

        

        markDirty(false);
        if (window.Swal && typeof Swal.fire === 'function') {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'All changes have been saved.',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                heightAuto: false
            }).then((res) => {
                if (res.isConfirmed) {
                //location.reload();
                }
            });
        } else {
            alert('Saved! All changes have been saved.');
            location.reload();
        }

        await loadAndRenderEventRows(state.eventId, state.users);

        } catch (e) {
        console.error(e);
        if (window.Swal) Swal.fire('Error', 'Failed to save changes. Please try again.', 'error');
        } finally {
        const loading = modal.querySelector('.alert-success');
        if (loading) loading.style.display = 'none';
        }
    }

    // ====== UTILS ======
    function buildUserMenuHTML(users) {
        const items = users.map(u => (`
        <div class="m8-dropdown-item m8-user-item" data-user-id="${u.user_id}">
            <img src="https://www.motiv8search.com/images/profile-profile-holder.png" class="m8-avatar" alt="">
            <span>${escapeHtml(u.name)}</span>
        </div>
        `)).join('');

        return `
        <div class="m8-menu m8-user-menu">
            <input type="text" class="form-control m8-search" placeholder="Search users...">
            <div class="m8-user-list">
            <div class="m8-dropdown-item m8-user-item" data-user-id="0">
                <img src="https://www.motiv8search.com/images/profile-profile-holder.png" class="m8-avatar" alt="">
                <span>Select a user</span>
            </div>
            ${items}
            </div>
        </div>
        `;
    }

    function statusClass(s) {
        const map = { 'Not Started':'status-not-started','Pending':'status-pending','Overdue':'status-overdue','Complete':'status-complete','NA':'status-na' };
        return map[s] || 'status-na';
    }

    function escapeHtml(s='') {
        return s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }

    function cleanEventName(raw = '') {
        let t = String(raw).trim();
        const months = '(January|February|March|April|May|June|July|August|September|October|November|December|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Sept|Oct|Nov|Dec)';

        t = t.replace(new RegExp(`\\s*-\\s*\\d{1,2}(?:st|nd|rd|th)?\\s+${months}\\s+\\d{4}\\b.*$`, 'i'), '');
        t = t.replace(new RegExp(`\\s*-\\s*${months}\\s+\\d{1,2}(?:st|nd|rd|th)?(?:,)?\\s+\\d{4}\\b.*$`, 'i'), '');
        t = t.replace(/\s*-\s*\d{4}-\d{2}-\d{2}\b.*$/i, '');

        t = t.trim();
        return t || String(raw).trim();
    }

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    let keyword = '<?php echo $_GET['keyword'] ?>';
    if(keyword != ''){
        let value = document.getElementById("search").value = keyword;
        if(value!= ''){
            setTimeout(()=>{
                 filterTable();
            },100)
           
        }
        
    }

    function filterTable() {
        let input = document.getElementById("search").value.toLowerCase().trim();
        let rows = document.querySelectorAll(".table-container tbody tr");
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            
        });

        
        const url = new URL(window.location.href);
        url.searchParams.set("keyword", input);
        window.history.replaceState({}, '', url);
        

    }
    document.getElementById("searchBtn").addEventListener("click", filterTable);
})
</script>



<script>
    const assign      = document.querySelector('#assign');
    const initialTxt  =  assign.textContent;
    const awaitTxt    = 'Assigning Template';

    assign.addEventListener('click', confirmAssign);

    async function confirmAssign() {
        const eventId = document.querySelector('.m8-selected-event-name').id;

        if (eventId == 0) {
            Swal.fire('Notice', "No Event is Selected", 'info');
            return;
        }

        // Confirmation dialog
        const result = await Swal.fire({
            title: 'Assign Template?',
            text: "Are you sure you want to assign this template to the selected event?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, Assign',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            await assignTemplate(eventId);
        }
    }

    async function assignTemplate() {

    const eventId      = document.querySelector('.m8-selected-event-name').id;
    if(eventId==0){
        Swal.fire('Notice', "No Event is Selected", 'info');
        return;
    }
    assign.textContent = awaitTxt;
    assign.disabled = true;

    try {
        let res = await fetch(window.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ eventId, action: 'assignTemplate' })
        });

        let data = await res.json(); 
        console.table(data); 
        if (data.status == 'error') {
            Swal.fire('Error!', data.message, 'error');
        } else {
            Swal.fire('Success!', data.message, 'success');
            location.reload();
        }
        assign.textContent = initialTxt;
    } catch (error) {
        console.error('Error assigning template:', error);
        assign.textContent = 'Server error try again';
    }

    assign.disabled = false;
}

</script>