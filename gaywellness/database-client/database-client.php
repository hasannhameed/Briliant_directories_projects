<?php
// query_api.php - Handle requests from the BDClient

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   http_response_code(405); // Method Not Allowed
   echo json_encode(['status' => 'error', 'error' => 'Invalid request method']);
   exit;
}

// Get the JSON payload from the request
$requestPayload = json_decode(file_get_contents('php://input'), true);

// Validate the payload
if (!isset($requestPayload['api_key']) || !isset($requestPayload['action'])) {
   http_response_code(400); // Bad Request
   echo json_encode(['status' => 'error', 'error' => 'Missing required fields']);
   exit;
}

// Validate the API key
$isValidToken = bdapi_controller::validateApiKey($requestPayload['api_key']);

if (!$isValidToken) {
   http_response_code(403); // Forbidden
   echo json_encode(['status' => 'error', 'error' => 'Invalid API key']);
   exit;
}

// Database connection details
$database_host = $w['database_host'];
$database_name = $w['database'];
$database_user = $w['database_user'];
$database_password = $w['database_pass'];

try {
   // Detect WHMCS table usage in raw queries
   if ($requestPayload['action'] === 'raw' && isset($requestPayload['query'])) {
      $rawQuery = strtolower($requestPayload['query']);

      // Basic check for WHMCS table usage
      if (strpos($rawQuery, 'tblinvoices') !== false) {
         $database_host = $w['whmcs_database_host'];
         $database_name = $w['whmcs_database_name'];
         $database_user = $w['whmcs_database_user'];
         $database_password = $w['whmcs_database_password'];
      }
   }

   // Establish PDO connection
   $pdo = new PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   $action = $requestPayload['action'];

   if ($action === 'select') {
      $table = isset($requestPayload['table']) ? $requestPayload['table'] : '';
      $columns = isset($requestPayload['columns']) ? $requestPayload['columns'] : '*';
      $where = isset($requestPayload['where']) ? $requestPayload['where'] : '1=1';
      $query = "SELECT $columns FROM $table WHERE $where";

      $stmt = $pdo->query($query);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      echo json_encode([
         'status' => 'success',
         'data' => $data,
      ]);
   } else if ($action === 'insert') {

      $table = isset($requestPayload['table']) ? $requestPayload['table'] : '';
      $data = isset($requestPayload['data']) ? $requestPayload['data'] : [];
      if (empty($data)) {
         throw new Exception('No data provided for insertion');
      }

      $columns = implode(',', array_keys($data));
      $placeholders = implode(',', array_fill(0, count($data), '?'));
      $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";

      $stmt = $pdo->prepare($query);
      $stmt->execute(array_values($data));

      echo json_encode([
         'status' => 'success',
         'data' => ['insert_id' => $pdo->lastInsertId()],
      ]);

   } else if ($action === 'update') {
      $table = isset($requestPayload['table']) ? $requestPayload['table'] : '';
      $data = isset($requestPayload['data']) ? $requestPayload['data'] : [];
      $where = isset($requestPayload['where']) ? $requestPayload['where'] : '1=1';

      if (empty($data)) {
         throw new Exception('No data provided for update');
      }

      $set = implode(',', array_map(function ($column) {
         return "$column = ?";
      }, array_keys($data)));

      $query = "UPDATE $table SET $set WHERE $where";

      $stmt = $pdo->prepare($query);
      $stmt->execute(array_values($data));

      echo json_encode([
         'status' => 'success',
         'data' => ['affected_rows' => $stmt->rowCount()],
      ]);
   } else if ($action === 'delete') {
      $table = isset($requestPayload['table']) ? $requestPayload['table'] : '';
      $where = isset($requestPayload['where']) ? $requestPayload['where'] : '1=1';
      $query = "DELETE FROM $table WHERE $where";

      $stmt = $pdo->prepare($query);
      $stmt->execute();

      echo json_encode([
         'status' => 'success',
         'data' => ['affected_rows' => $stmt->rowCount()],
      ]);
   } else if ($action === 'raw') {

      $query = isset($requestPayload['query']) ? $requestPayload['query'] : '';
      if (empty($query)) {
         throw new Exception('No SQL query provided for raw action');
      }

      // Execute raw query
      $stmt = $pdo->query($query);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Convert all numeric strings to numbers
      foreach ($data as &$row) {
         foreach ($row as $key => $value) {
            if (is_numeric($value)) {
               $row[$key] = $value + 0;
            }
         }
      }

      echo json_encode([
         'status' => 'success',
         'data' => $data,
      ]);
   } else {
      echo json_encode([
         'status' => 'error',
         'error' => 'Invalid action',
      ]);
   }
} catch (Exception $e) {
   http_response_code(500); // Internal Server Error
   echo json_encode([
      'status' => 'error',
      'error' => $e->getMessage(),
   ]);
}

?>
