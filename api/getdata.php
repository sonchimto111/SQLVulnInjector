<?php
require_once 'setup-db.php';

$pid = isset($_GET['PID']) ? $_GET['PID'] : '';
$pwd = isset($_GET['Password']) ? $_GET['Password'] : '';

// Level 1: Raw concatenation directly into SQLite query
$sql = "SELECT Name, Salary, SSN
            FROM employee
            WHERE pid= '$pid' and password='$pwd'";

$data = [];
$error = "";

try {
    $stmt = $pdo->query($sql); // Vulnerable execution
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
else if ($error) {
    echo "Error: $error\n";
}
?>
