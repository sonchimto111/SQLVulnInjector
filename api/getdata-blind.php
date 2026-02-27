<?php
require_once 'setup-db.php';

$pid = isset($_GET['PID']) ? $_GET['PID'] : '';
$pwd = isset($_GET['Password']) ? $_GET['Password'] : '';

// Vulnerable query
$sql = "SELECT * FROM employee WHERE pid= '$pid' and password='$pwd'";

$exists = false;
$error = "";

try {
    $stmt = $pdo->query($sql);
    if ($stmt && $stmt->fetch()) {
        $exists = true;
    }
}
catch (PDOException $e) {
    // Blind SQLi hides errors!
    $error = "";
}

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');

    $response = [
        "sql" => $sql,
        "data" => [], // Look! No data returned to the attacker!
        "error" => $error // Look! No SQL errors returned! 
    ];

    if ($exists) {
        $response['message'] = "User exists.";
    }
    else {
        $response['message'] = "User does not exist.";
    }

    echo json_encode($response);
    exit;
}

if ($exists) {
    echo "User found.\n";
}
else {
    echo "User not found.\n";
}
?>
