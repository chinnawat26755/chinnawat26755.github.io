<?php
session_start();
require_once 'config/condb.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_POST['signin'])) {
    $firstname = trim($_POST['firstname']); // ตัดช่องว่างที่ไม่จำเป็น
    $password = $_POST['password'];

    // ตรวจสอบข้อมูลที่กรอกมา
    if (empty($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("location: register.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("location: register.php");
        exit();
    } else if (strlen($password) > 20 || strlen($password) < 5) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
        header("location: register.php");
        exit();
    } else {
        try {
            // ตรวจสอบการมีอยู่ของชื่อจริงในฐานข้อมูล
            $check_data = $condb->prepare("SELECT * FROM users WHERE firstname = :firstname");
            $check_data->bindParam(":firstname", $firstname, PDO::PARAM_STR);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($row) { // ถ้าพบชื่อในฐานข้อมูล
                // ตรวจสอบรหัสผ่าน
                if (password_verify($password, $row['password'])) {
                    // เก็บข้อมูลผู้ใช้ลง session
                    $_SESSION['user_login'] = $row['id'];
                    header("location: /admin/index.php"); // เปลี่ยนไปหน้า admin
                    exit();
                } else {
                    $_SESSION['error'] = 'รหัสผ่านผิด';
                    header("location: register.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                header("location: register.php");
                exit();
            }
        } catch (PDOException $e) {
            // แสดงข้อความ error หากเกิดปัญหากับฐานข้อมูล
            $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
            header("location: register.php");
            exit();
        }
    }
}
?>
