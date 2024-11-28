<?php 
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';

// เชื่อมต่อฐานข้อมูล
include '../config/condb.php';

$act = (isset($_GET['act']) ? $_GET['act'] : '');

// ลบข้อมูลจากตาราง emails
if ($act == 'delete_email' && isset($_GET['id'])) {
    $deleteId = intval($_GET['id']);
    ?>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script>
        swal({
            title: "Do you want to delete this email?",
            text: "Deleting mail is irreversible!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it.",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm) {
                window.location.href = "member.php?act=confirm_delete&id=" + <?= $deleteId; ?>;
            } else {
                swal("Cancel", "Email deletion is cancelled.", "error");
            }
        });
    </script>
    <?php
    exit();
}

// ยืนยันการลบจริง
if ($act == 'confirm_delete' && isset($_GET['id'])) {
    $deleteId = intval($_GET['id']);
    try {
        $stmt = $condb->prepare("DELETE FROM emails WHERE id = :id");
        $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $stmt->execute();
        echo '
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
        <script>
            swal({
                title: "Email deleted successfully",
                type: "success"
            }, function() {
                window.location.href = "/admin/member.php";
            });
        </script>';
    } catch (PDOException $e) {
        echo '
        <script>
            swal({
                title: "An error occurred.: ' . $e->getMessage() . '",
                type: "error"
            }, function() {
                window.location.href = "member_list.php";
            });
        </script>';
    }
    exit();
}

// ดูรายละเอียดอีเมล
if ($act == 'view_email' && isset($_GET['id'])) {
    $viewId = intval($_GET['id']);
    try {
        $stmt = $condb->prepare("SELECT e.id, e.subject, e.body, e.date_sent, 
                                 u.firstname AS sender_firstname, u.lastname AS sender_lastname,
                                 r.firstname AS receiver_firstname, r.lastname AS receiver_lastname
                                 FROM emails e
                                 INNER JOIN users u ON e.user_id = u.id
                                 INNER JOIN users r ON e.receiver_id = r.id
                                 WHERE e.id = :id");
        $stmt->bindParam(':id', $viewId, PDO::PARAM_INT);
        $stmt->execute();
        $email = $stmt->fetch();

        if ($email) {
            ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>View Email</h1>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Email Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Sender:</strong> <?= htmlspecialchars($email['sender_firstname'] . ' ' . $email['sender_lastname']); ?></p>
                                        <p><strong>To:</strong> <?= htmlspecialchars($email['receiver_firstname'] . ' ' . $email['receiver_lastname']); ?></p>
                                        <p><strong>Subject:</strong> <?= htmlspecialchars($email['subject']); ?></p>
                                        <p><strong>Body:</strong> <?= nl2br(htmlspecialchars($email['body'])); ?></p>
                                        <p><strong>Date Sent:</strong> <?= htmlspecialchars($email['date_sent']); ?></p>
                                        <a href="member_list.php" class="btn btn-secondary btn-sm mt-3">
                                            <i class="fas fa-arrow-left"></i> Back to inbox
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php
        } else {
            echo '<p>ไม่พบข้อมูลอีเมลที่คุณต้องการดู</p>';
        }
    } catch (PDOException $e) {
        echo '<p>เกิดข้อผิดพลาด: ' . $e->getMessage() . '</p>';
    }
}

if ($act == 'add') {
    include 'member_form_add.php';
} else {
    include 'member_list.php';
}

include 'footer.php';
?> 

<!-- เพิ่ม CSS สำหรับจัดข้อความในตารางให้อยู่ตรงกลาง -->
<style>
    .table td, .table th {
        text-align: center; /* จัดข้อความในแนวนอน */
        vertical-align: middle; /* จัดข้อความในแนวตั้ง */
    }
</style>
