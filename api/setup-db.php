<?php
// setup-db.php
// This script creates a standalone SQLite database if it doesn't exist.
// This allows the lab to be 100% zero-configuration and run natively.

$db_file = __DIR__ . '/database.sqlite';
$is_new = !file_exists($db_file);

try {
    // Create (connect to) SQLite database in file
    $pdo = new PDO('sqlite:' . $db_file);
    // Set errormode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Return rows as associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if ($is_new) {
        // Build the Schema
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS employee (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                pid TEXT NOT NULL,
                Name TEXT NOT NULL,
                password TEXT NOT NULL,
                Salary INTEGER,
                SSN TEXT NOT NULL,
                role TEXT DEFAULT 'user'
            );
            
            CREATE TABLE IF NOT EXISTS secret_flags (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                flag_name TEXT NOT NULL,
                flag_value TEXT NOT NULL
            );
        ");

        // Insert Default Data
        $pdo->exec("
            INSERT INTO employee (pid, Name, password, Salary, SSN, role) VALUES 
            ('1', 'Alice Admin', 'admin123', 95000, '000-11-2222', 'admin'),
            ('2', 'Bob Builder', 'builder1', 65000, '000-33-4444', 'user'),
            ('3', 'Charlie Chaplin', 'comedy!', 45000, '000-55-6666', 'user');

            INSERT INTO secret_flags (flag_name, flag_value) VALUES 
            ('First Blood', 'FLAG{1nj3ct10n_1s_fun!}'),
            ('Master Hacker', 'FLAG{y0ur_sq1_1s_str0ng!}');
        ");
    }
}
catch (PDOException $e) {
    if (isset($_GET['format']) && $_GET['format'] === 'json') {
        die(json_encode(["error" => "Database Setup Failed: " . $e->getMessage()]));
    }
    else {
        die("Database Setup Failed: " . $e->getMessage() . "\n");
    }
}
?>
