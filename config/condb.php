<?php
// PHP Data Objects(PDO) Sample Code:
try {
    $condb = new PDO("sqlsrv:server = tcp:samsantech.database.windows.net,1433; Database = db_website", "fiw_cnw", "ChinnawatAoumkaew01");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "fiw_cnw", "pwd" => "ChinnawatAoumkaew01", "Database" => "db_website", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:samsantech.database.windows.net,1433";
$condb = sqlsrv_connect($serverName, $connectionInfo);
?>
