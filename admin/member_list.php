<?php 
include 'header.php';
include 'sidebar_menu.php';

// เชื่อมต่อฐานข้อมูล
include '../config/condb.php';

$queryEmails = $condb->prepare("SELECT e.id, e.subject, e.body, e.date_sent, 
                               u.firstname AS sender_firstname, u.lastname AS sender_lastname,
                               r.firstname AS receiver_firstname, r.lastname AS receiver_lastname
                               FROM emails e
                               INNER JOIN users u ON e.user_id = u.id
                               INNER JOIN users r ON e.receiver_id = r.id
                               ORDER BY e.date_sent DESC");
$queryEmails->execute();
$emails = $queryEmails->fetchAll();
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Inbox</h1>
                </div>
                <div class="col-sm-6">
                    <a href="member_form_add.php" class="btn btn-primary btn-sm float-right mt-2">
                        <i class="fas fa-envelope"></i> Compose Email
                    </a>
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
                            <h3 class="card-title">Email List</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th>Sender</th>
                                        <th>To</th>
                                        <th>Subject</th>
                                        <th>Date Sent</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($emails)) { ?>
                                        <?php foreach ($emails as $email) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($email['sender_firstname'] . ' ' . $email['sender_lastname']); ?></td>
                                                <td><?= htmlspecialchars($email['receiver_firstname'] . ' ' . $email['receiver_lastname']); ?></td>
                                                <td><?= htmlspecialchars($email['subject']); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($email['date_sent']); ?></td>
                                                <td class="text-center">
                                                    <a href="member.php?act=delete_email&id=<?= htmlspecialchars($email['id']); ?>" 
                                                       class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </a>
                                                    <a href="member.php?act=view_email&id=<?= htmlspecialchars($email['id']); ?>" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No emails found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'footer.php'; ?>
