<?php
require_once 'setup-db.php';

$pid = isset($_GET['PID']) ? $_GET['PID'] : '';
$pwd = isset($_GET['Password']) ? $_GET['Password'] : '';

$sql = "SELECT Name, Salary, SSN
            FROM employee
            WHERE pid= ? AND password= ?";

$error = "";
$data = [];

try {
    // Level 3: Secure Prepared Statements
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pid, $pwd]);
    $data = $stmt->fetchAll();
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

foreach ($data as $row) {
    printf("%s %s %s\n", $row['Name'], $row['Salary'], $row['SSN']);
}
?>
