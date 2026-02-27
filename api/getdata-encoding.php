<?php
require_once 'setup-db.php';

$pid = isset($_GET['PID']) ? $_GET['PID'] : '';
$pwd = isset($_GET['Password']) ? $_GET['Password'] : '';

// PDO doesn't have an exact mysqli_real_escape_string equivalent unless we use quote(),
// but quote() actually surrounds the string with quotes. 
// To simulate the classic encoding bypass, we'll manually escape single quotes.
$escaped_pid = str_replace("'", "''", $pid);
$escaped_pwd = str_replace("'", "''", $pwd);

$sql = "SELECT Name, Salary, SSN
            FROM employee
            WHERE pid= '$escaped_pid' and password='$escaped_pwd'";

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
else if ($error) {
    echo "Error: $error\n";
}
?>
