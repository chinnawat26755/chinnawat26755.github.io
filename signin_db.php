<?php
session_start();
require_once 'config/condb.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_POST['signin'])) {
    $firstname = $_POST['firstname'];  // เปลี่ยนเป็น firstname แทน email
    $password = $_POST['password'];

    // ตรวจสอบข้อมูลที่กรอกมา
    if (empty($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("location: signin.php");
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("location: signin.php");
    } else if (strlen($password) > 20 || strlen($password) < 5) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
        header("location: signin.php");
    } else {
        try {
            // ตรวจสอบการมีอยู่ของชื่อจริงในฐานข้อมูล
            $check_data = $condb->prepare("SELECT * FROM users WHERE firstname = :firstname");
            $check_data->bindParam(":firstname", $firstname);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            // ถ้ามีชื่อจริงตรงกัน
            if ($check_data->rowCount() > 0) {
                if ($firstname == $row['firstname']) {
                    // เปรียบเทียบรหัสผ่านที่กรอกกับรหัสผ่านที่เก็บในฐานข้อมูล
                    if (password_verify($password, $row['password'])) {
                        // เก็บข้อมูลผู้ใช้ลง session
                        $_SESSION['user_login'] = $row['id'];
                        header("location: /mywebsite/admin/index.php"); // ไปหน้า user หรือหน้าอื่นๆ ที่ต้องการ
                    } else {
                        $_SESSION['error'] = 'รหัสผ่านผิด';
                        header("location: signin.php"); // กลับไปหน้า signin
                    }
                } else {
                    $_SESSION['error'] = 'ชื่อจริงผิด';
                    header("location: signin.php"); // กลับไปหน้า signin
                }
            } else {
                $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                header("location: /admin/index.php"); // กลับไปหน้า signin
            }
        } catch(PDOException $e) {
            // แสดงข้อความ error หากเกิดปัญหากับฐานข้อมูล
            echo $e->getMessage();
        }
    }
}
?>
