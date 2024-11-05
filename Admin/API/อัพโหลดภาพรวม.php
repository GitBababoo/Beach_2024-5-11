<?php
session_start();
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}

$alertMessage = ''; // ตัวแปรสำหรับข้อความแจ้งเตือน

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "../../uploads/"; // ที่อยู่สำหรับเก็บภาพที่อัปโหลด
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // บันทึกชื่อไฟล์ลงใน hero_image.txt โดยไม่ต้องใช้ ../../
        file_put_contents('../../ภาพรวม.txt', 'uploads/' . basename($_FILES["image"]["name"]));
        $alertMessage = 'ไฟล์ถูกอัปโหลดเรียบร้อยแล้ว.';
    } else {
        $alertMessage = 'ขอโทษค่ะ, เกิดข้อผิดพลาดในการอัปโหลดไฟล์.';
    }
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดภาพ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .upload-container {
            margin-top: 100px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include '../sidebar-navbar.php'; ?>
<div class="container upload-container">
    <h2 class="text-center mb-4">อัปโหลดภาพ</h2>
    <?php if (!empty($alertMessage)): ?>
        <script>
            Swal.fire({
                icon: '<?= strpos($alertMessage, 'เรียบร้อย') !== false ? 'success' : 'error' ?>',
                title: '<?= strpos($alertMessage, 'เรียบร้อย') !== false ? 'สำเร็จ!' : 'เกิดข้อผิดพลาด' ?>',
                text: '<?= htmlspecialchars($alertMessage) ?>'
            });
        </script>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="image">เลือกภาพเพื่ออัปโหลด:</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">อัปโหลดภาพ</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
