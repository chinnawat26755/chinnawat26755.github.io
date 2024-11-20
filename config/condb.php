<?php
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:inventoryfanilpro.database.windows.net,1433; Database = inventory123", "projectsamsan", "SamsanTech00");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "projectsamsan", "pwd" => "SamsanTech00", "Database" => "inventory123", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:inventoryfanilpro.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
?>
