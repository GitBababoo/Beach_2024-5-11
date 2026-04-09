<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // เริ่ม session ถ้ายังไม่ได้เริ่ม
}
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}

include_once '../sidebar-navbar.php';
$host = 'localhost';
$db = 'ESSDUH_BNS_MEMBER';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['business_type_name'];
        $desc = $_POST['description'];

        // ตรวจสอบว่ามีชื่อประเภทธุรกิจนี้อยู่แล้วหรือไม่
        $checkQuery = $conn->prepare("SELECT business_type_id FROM business_types WHERE business_type_name = ?");
        $checkQuery->bind_param("s", $name);
        $checkQuery->execute();
        $result = $checkQuery->get_result();

        if ($result->num_rows > 0) {
            // แสดงข้อผิดพลาด แต่ไม่ใช้ AJAX
            $error_message = 'ชื่อประเภทธุรกิจนี้มีอยู่แล้ว!';
        } else {
            $stmt = $conn->prepare("INSERT INTO business_types (business_type_name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $desc);
            $stmt->execute();
            // redirect หลังจากเพิ่มข้อมูล
            header('Location: business_types.php');
            exit();
        }
        $checkQuery->close();
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['business_type_id'];
        $name = $_POST['business_type_name'];
        $desc = $_POST['description'];

        $stmt = $conn->prepare("UPDATE business_types SET business_type_name=?, description=? WHERE business_type_id=?");
        $stmt->bind_param("ssi", $name, $desc, $id);
        $stmt->execute();
        header('Location: business_types.php');
        exit();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['business_type_id'];

        $stmt = $conn->prepare("DELETE FROM business_types WHERE business_type_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header('Location: business_types.php');
        exit();
    }
}

$types = $conn->query("SELECT * FROM business_types");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประเภทของธุรกิจ</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.1/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.1/sweetalert2.all.min.js"></script>
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="text-center mb-4">ประเภทของธุรกิจ</h2>

    <button class="btn btn-success mb-3" onclick="showAddModal()">เพิ่มประเภทธุรกิจ</button>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
        <tr>
            <th>รหัส</th>
            <th>ชื่อประเภทธุรกิจ</th>
            <th>คำอธิบาย</th>
            <th>จัดการ</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $types->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['business_type_id'] ?></td>
                <td><?= $row['business_type_name'] ?></td>
                <td><?= $row['description'] ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="showEditModal(<?= $row['business_type_id'] ?>, '<?= htmlspecialchars($row['business_type_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>')">แก้ไข</button>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['business_type_id'] ?>)">ลบ</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Template -->
<div class="modal fade" id="businessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="businessForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">เพิ่มประเภทธุรกิจ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="business_type_id" id="business_type_id">
                    <div class="mb-3">
                        <label for="business_type_name" class="form-label">ชื่อประเภทธุรกิจ</label>
                        <input type="text" class="form-control" name="business_type_name" id="business_type_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">คำอธิบาย</label>
                        <textarea class="form-control" name="description" id="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="modalSaveBtn">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showAddModal() {
        $('#modalTitle').text('เพิ่มประเภทธุรกิจ');
        $('#businessForm')[0].reset();
        $('#modalSaveBtn').attr('name', 'add');
        $('#businessModal').modal('show');
    }

    function showEditModal(id, name, desc) {
        $('#modalTitle').text('แก้ไขประเภทธุรกิจ');
        $('#business_type_id').val(id);
        $('#business_type_name').val(name);
        $('#description').val(desc);
        $('#modalSaveBtn').attr('name', 'edit');
        $('#businessModal').modal('show');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบประเภทธุรกิจนี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('business_types.php', { delete: true, business_type_id: id }, function () {
                    location.reload();
                });
            }
        });
    }
</script>
</body>
</html>
