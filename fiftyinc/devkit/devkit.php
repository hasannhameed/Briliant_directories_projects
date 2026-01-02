<?php

header('Content-Type: application/json');

// Log all incoming requests (for debugging)
mysql_query("INSERT INTO test SET body = '".json_encode(array_merge($_REQUEST, (array)json_decode(file_get_contents('php://input'), true)))."'");

$action = $pars[5];

// **Fetch Widgets**
if ($action === "fetch") {
    $get_widgets = mysql_query("SELECT * FROM data_widgets");
    $widgets = [];

    while ($widget = mysql_fetch_assoc($get_widgets)) {
        $widgets[] = [
            'widget_name' => $widget['widget_name'],
            'widget_data' => $widget['widget_data'],
            'widget_javascript' => $widget['widget_javascript'],
            'widget_style' => $widget['widget_style']
        ];
    }

    echo json_encode(['widgets' => $widgets]);
}

// **Update Widget**
if ($action === "update") {
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);

    $widget_name = $decoded['widget_name'] ?? null;
    $content_type = $decoded['content_type'] ?? null;
    $widgetContent = $decoded['content'] ?? null;

    // Validate input
    if (!$widget_name || !$content_type || !$widgetContent) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing parameters. Expected widget_name, content_type, and content.'
        ]);
    }

    // Validate content type
    $validColumns = ['widget_data', 'widget_javascript', 'widget_style'];
    if (!in_array($content_type, $validColumns)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid content_type. Allowed values: widget_data, widget_javascript, widget_style.'
        ]);
    }

    // Update query
    $query = "UPDATE data_widgets SET `$content_type` = '" . mysql_real_escape_string($widgetContent) . "' WHERE widget_name = '" . mysql_real_escape_string($widget_name) . "'";
    $result = mysql_query($query);

    $get_widget = mysql_query("SELECT * FROM data_widgets WHERE widget_name = '". $widget_name ."' ");
    $update_cache = mysql_fetch_assoc($get_widget);


    $cache_payload = array(

        "widget_id" => $update_cache['widget_id'],
        "widget_name" => $update_cache['widget_name'],
        "widget_type" => $update_cache['widget_type'],
        "widget_data" => $update_cache['widget_data'],
        "widget_style" => $update_cache['widget_style'],
        "widget_class" => $update_cache['widget_class'],
        "widget_viewport" => $update_cache['widget_viewport'],
        "date_updated" => $update_cache['date_updated'],
        "updated_by" => $update_cache['updated_by'],
        "short_code" => $update_cache['short_code'],
        "div_id" => $update_cache['div_id'],
        "bootstrap_enabled" => $update_cache['bootstrap_enabled'],
        "ssl_enabled" => $update_cache['ssl_enabled'],
        "mobile_enabled" => $update_cache['mobile_enabled'],
        "revision_timestamp" => $update_cache['revision_timestamp'],
        "widget_html_element" => $update_cache['widget_html_element'],
        "widget_javascript" => $update_cache['widget_javascript'],
        "file_type" => $update_cache['file_type'],
        "widget_settings" => $update_cache['widget_settings'],
        "widget_values" => $update_cache['widget_values']

    );

    // print_r($cache_payload);


    // update cache does't work
    // file_put_contents("/home/launc18189/public_html/filedata/cache/widgets/" . $update_cache['short_code'], json_encode($cache_payload));



    $location = $_SERVER['DOCUMENT_ROOT'] . "/filedata/cache/widgets/";
    $filename = $location . $update_cache['short_code'];
    chmod($filename, 0777);
    $contents = json_encode($cache_payload);

    file_put_contents($filename, $contents);

    

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => "Widget '$widget_name' updated successfully for $content_type."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database update failed.'
        ]);
    }

    
}

?>
