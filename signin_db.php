<?php
session_start();
include('db_connection.php');  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์มหรือไม่
if (isset($_POST['email']) && isset($_POST['password'])) {
    // รับข้อมูลจากฟอร์ม
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // คำสั่ง SQL เพื่อดึงข้อมูลผู้ใช้ที่มีอีเมลตรงกัน
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $condb->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ตรวจสอบว่ามีผู้ใช้หรือไม่ และตรวจสอบรหัสผ่าน
        if ($user && password_verify($password, $user['password'])) {
            // ถ้าผ่านการตรวจสอบเข้าสู่ระบบสำเร็จ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");  // เปลี่ยนไปยังหน้าหลังเข้าสู่ระบบ
            exit;
        } else {
            // ถ้ารหัสผ่านไม่ถูกต้อง
            echo "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
        }
    } catch (PDOException $e) {
        // ถ้ามีข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
        die("Error: " . $e->getMessage());
    }
} else {
    // ถ้าไม่มีการส่งข้อมูลมาจากฟอร์ม
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
}
?>
