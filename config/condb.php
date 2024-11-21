<?php
$host = 'tcp:samsantech.database.windows.net,1433';
$dbName = 'db_website';
$username = 'fiw_cnw';
$password = 'ChinnawatAoumkaew01';

try {
    $conn = new PDO("sqlsrv:server=$host;Database=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
