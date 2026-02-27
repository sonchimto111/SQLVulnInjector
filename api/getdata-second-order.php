<?php
require_once 'setup-db.php';

// Step 1: The attacker "registers" their username with a payload.
// This part is completely safe against immediate SQLi because we use prepared statements.
$username_payload = isset($_GET['PID']) ? $_GET['PID'] : '';

// Let's pretend we insert this safely into a 'users' table, and now we are an admin retrieving it.
// For the sake of this lab without requiring a new DB table structure, we will just simulate 
// retrieving the variable and passing it to the secondary query unsafely.

$simulated_safe_storage = $username_payload;

// Step 2: The Second-Order Vulnerability
// Later on, a background process or an admin panel reads the stored username 
// and concatenates it directly into a new query.

$sql = "SELECT Name, Salary, SSN FROM employee WHERE Name = '$simulated_safe_storage'";
$data = [];
$error = "";

try {
    $stmt = $pdo->query($sql); // Unsafe execution of the safely stored payload
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
        "sql" => "-- Step 1: Insert safely (Prepared Statement)\nINSERT INTO users (username) VALUES (?);\n\n-- Step 2: Unsafe retrieval later (Second Order)\n" . $sql,
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
