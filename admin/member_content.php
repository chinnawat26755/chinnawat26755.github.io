<?php
include '../config/condb.php';

// ดึงข้อมูลผู้ใช้จากฐานข้อมูลเพื่อแสดงใน dropdown
$queryMembers = $condb->prepare("SELECT id, firstname, lastname FROM users");
$queryMembers->execute();
$members = $queryMembers->fetchAll();

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $user_id = $_POST['user_id'];      // ผู้ส่ง
    $receiver_id = $_POST['receiver_id']; // ผู้รับ
    $subject = $_POST['subject'];      // หัวข้อ
    $body = $_POST['body'];            // เนื้อหาอีเมล

    // บันทึกข้อมูลลงในฐานข้อมูล
    try {
        // เปลี่ยน NOW() เป็น GETDATE() สำหรับ SQL Server
        $stmt = $condb->prepare("INSERT INTO emails (user_id, receiver_id, subject, body, date_sent) 
                                 VALUES (:user_id, :receiver_id, :subject, :body, GETDATE())");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        $stmt->execute();
        
        echo '
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
        <script>
            swal({
                title: "Email sent successfully",
                type: "success"
            }, function() {
                window.location.href = "member_list.php";
            });
        </script>';
    } catch (PDOException $e) {
        echo '<script>
                alert("An error occurred.: ' . $e->getMessage() . '");
              </script>';
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Compose Email</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <form action="member_form_add.php" method="post">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2">From</label>
                    <div class="col-sm-4">
                        <select name="user_id" class="form-control" required>
                            <option value="">Author</option>
                            <?php foreach ($members as $member) { ?>
                                <option value="<?= $member['id']; ?>">
                                    <?= $member['firstname'] . ' ' . $member['lastname']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2">To</label>
                    <div class="col-sm-4">
                        <select name="receiver_id" class="form-control" required>
                            <option value="">Recipient</option>
                            <?php foreach ($members as $member) { ?>
                                <option value="<?= $member['id']; ?>">
                                    <?= $member['firstname'] . ' ' . $member['lastname']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea name="body" class="form-control" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </div>
        </form>
    </section>
</div>
