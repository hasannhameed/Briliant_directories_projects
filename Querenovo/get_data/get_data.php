<?php

/**
 * PHP cURL Example for fetching structured image data from an external, 
 * non-Google API (demonstrated here using a placeholder).
 * This script showcases how to correctly set a User-Agent and handle HTTP requests.
 * * NOTE: For actual Google Image data, you MUST use the official Google Custom Search API.
 */

// 1. Configuration for the request
$search_query = "construction_tools"; 
$api_url = "https://jsonplaceholder.typicode.com/photos?albumId=1&_limit=10"; // Example public JSON endpoint for placeholder data
$user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

// 2. Initialize cURL session
$ch = curl_init();

// 3. Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the transfer as a string
curl_setopt($ch, CURLOPT_TIMEOUT, 30);      // Set a timeout of 30 seconds
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

// Set HTTP headers, including a realistic User-Agent (CRUCIAL for anti-bot evasion)
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: {$user_agent}",
    "Accept: application/json",
]);

// 4. Execute the request and fetch the response
$response = curl_exec($ch);

// 5. Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    exit;
}

// 6. Close cURL session
curl_close($ch);

// 7. Process the JSON response
$data = json_decode($response, true);

if (empty($data)) {
    echo "No data or failed to decode JSON.";
} else {
    echo "<h2>Successfully fetched data for: '{$search_query}'</h2>";
    echo "<table border='1' cellpadding='10' cellspacing='0' width='100%'>";
    echo "<thead><tr><th>Title</th><th>Thumbnail URL</th></tr></thead><tbody>";

    // Loop through results and display them
    foreach ($data as $item) {
        $title = htmlspecialchars($item['title'] ?? 'N/A');
        $thumbnail_url = htmlspecialchars($item['thumbnailUrl'] ?? 'N/A');

        echo "<tr>";
        echo "<td>{$title}</td>";
        echo "<td><a href='{$thumbnail_url}' target='_blank'>{$thumbnail_url}</a></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
}

?>
