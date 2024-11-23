<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to SamsanTechSystem </title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css?v=3.2.0">
  <link rel="icon" href="/assets/dist/img/AdminLTELogo.png">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <b>SamsunTech</b>System
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">เข้าสู่ระบบเพื่อใช้งาน</p>
        <?php if(isset($_SESSION['error'])) { ?>
          <div class="alert alert-danger" role="alert">
            <?php 
              echo $_SESSION['error'];
              unset($_SESSION['error']);
            ?>
          </div>
        <?php } ?>
        <form action="signin_db.php" method="post">
          <div class="input-group mb-3">
            <!-- เปลี่ยนจาก email เป็น firstname -->
            <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="signin" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>
        <p class="mb-0">
          <a href="register.php" class="text-center">สมัครสมาชิก?</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
