<?php 
    session_start();
    require_once 'config/condb.php';

    if (isset($_POST['signup'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $c_password = $_POST['c_password'];
        $urole = 'user';

        if (empty($firstname)) {
            $_SESSION['error'] = 'กรุณากรอกชื่อ';
            header("location: index.php");
        } else if (empty($lastname)) {
            $_SESSION['error'] = 'กรุณากรอกนามสกุล';
            header("location: index.php");
        } else if (empty($email)) {
            $_SESSION['error'] = 'กรุณากรอกอีเมล';
            header("location: index.php");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
            header("location: index.php");
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: index.php");
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
            header("location: index.php");
        } else if ($password != $c_password) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header("location: index.php");
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $check_data = $condb->prepare("SELECT * FROM users WHERE email = :email");
                $check_data->bindParam(":email", $email);
                $check_data->execute();
                if ($check_data->rowCount() > 0) {
                    $_SESSION['error'] = 'อีเมลนี้ถูกใช้งานไปแล้ว';
                    header("location: index.php");
                } else {
                    $stmt = $condb->prepare("INSERT INTO users (firstname, lastname, email, password, urole) VALUES (:firstname, :lastname, :email, :password, :urole)");
                    $stmt->bindParam(":firstname", $firstname);
                    $stmt->bindParam(":lastname", $lastname);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $password_hash);
                    $stmt->bindParam(":urole", $urole);
                    $stmt->execute();

                    $_SESSION['success'] = 'สมัครสมาชิกสำเร็จ';
                    header("location: signin.php");
                }
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
?>
