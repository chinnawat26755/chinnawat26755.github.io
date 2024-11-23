<?php
    session_start();
    require_once 'config/condb.php';

    if (isset($_POST['signin'])) {
        $firstname = $_POST['firstname'];  // เปลี่ยนเป็น firstname แทน email
        $password = $_POST['password'];

        if (empty($firstname)) {
            $_SESSION['error'] = 'กรุณากรอกชื่อ';
            header("location: signin.php");
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: signin.php");
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
            header("location: signin.php");
        } else {
            try {
                // ตรวจสอบการมีอยู่ของชื่อจริง
                $check_data = $condb->prepare("SELECT * FROM users WHERE firstname = :firstname");
                $check_data->bindParam(":firstname", $firstname);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);

                if ($check_data->rowCount() > 0) {
                    // ถ้ามีชื่อจริงตรงกัน
                    if ($firstname == $row['firstname']) {
                        if (password_verify($password, $row['password'])) {  // ถ้ารหัสผ่านถูกต้อง
                            if ($row['urole'] == 'admin') {
                                $_SESSION['admin_login'] = $row['id'];
                                header("location: admin.php");
                            } else {
                                $_SESSION['user_login'] = $row['id'];
                                header("location: /mywebsite/admin/index.php");
                            }
                        } else {
                            $_SESSION['error'] = 'รหัสผ่านผิด';
                            header("location: signin.php");
                        }
                    } else {
                        $_SESSION['error'] = 'ชื่อจริงผิด';
                        header("location: signin.php");
                    }
                } else {
                    $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                    header("location: signin.php");
                }
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
?>
