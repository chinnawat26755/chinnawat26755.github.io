<?php
$servername = "localhost";
$username = "root";
$password = ""; // ถ้าไม่ได้ตั้งรหัสผ่านให้ลบ yourpassword ออก

try {
  $condb = new PDO("mysql:host=$servername;dbname=db_website;charset=utf8", $username, $password);
  // กำหนดโหมดข้อผิดพลาดให้แสดงเป็น exception
  $condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
