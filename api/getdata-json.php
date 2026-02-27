<?php
require_once 'setup-db.php';

$json_payload_str = isset($_GET['Password']) ? $_GET['Password'] : '{}';

// Parse the JSON Document
$json_obj = json_decode($json_payload_str, true);

if ($json_obj === null && json_last_error() !== JSON_ERROR_NONE) {
    if (isset($_GET['format']) && $_GET['format'] === 'json') {
        die(json_encode(["error" => "Invalid JSON provided in payload.", "sql" => "/* No execution, JSON parse failed */"]));
    }
    die("Invalid JSON.\n");
}

$user_id = isset($json_obj['user_id']) ? $json_obj['user_id'] : '';
$sql = "SELECT Name, Salary, SSN FROM employee WHERE pid= '$user_id'";

$data = [];
$error = "";

try {
    $stmt = $pdo->query($sql);
    if ($stmt) {
        $data = $stmt->fetchAll();
    }
}
catch (PDOException $e) {
    $error = $e->getMessage();
}

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode([
        "sql" => $sql,
        "data" => $data,
        "error" => $error
    ]);
    exit;
}

if (!empty($data)) {
    foreach ($data as $row) {
        printf("Name: %s -- Salary: %s -- SSN: %s\n", $row["Name"], $row["Salary"], $row['SSN']);
    }
}
?>
