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
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

function getCleanupPhotos($conn) {
    return $conn->query("SELECT cp.*, ca.activity_date FROM cleanup_photos cp JOIN cleanup_activities ca ON cp.activity_id = ca.activity_id");
}

function getActivityOptions($conn, $selected_id = null) {
    $activities = $conn->query("SELECT activity_id, location FROM cleanup_activities");
    while ($activity = $activities->fetch_assoc()) {
        $selected = ($activity['activity_id'] == $selected_id) ? "selected" : "";
        echo "<option value='{$activity['activity_id']}' {$selected}>{$activity['location']}</option>";
    }
}

$alertMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo_id = $_POST['photo_id'] ?? null;
    $activity_id = $_POST['activity_id'];
    $description = $_POST['description'];
    $show_image = isset($_POST['show_image']) ? 1 : 0;

    // Adding photo
    if (isset($_POST['add_photo']) && isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] == UPLOAD_ERR_OK) {
        $stmt = $conn->prepare("SELECT activity_date FROM cleanup_activities WHERE activity_id = ?");
        $stmt->bind_param("i", $activity_id);
        $stmt->execute();
        $stmt->bind_result($activity_date);
        $stmt->fetch();
        $stmt->close();

        $formattedDate = date('d-m-Y', strtotime($activity_date));
        $counter = $conn->query("SELECT COUNT(*) AS count FROM cleanup_photos WHERE photo_url LIKE '{$formattedDate}_cleanup_%'")->fetch_assoc()['count'];
        $newFileName = "{$formattedDate}_cleanup_" . chr(65 + $counter) . '.' . pathinfo($_FILES['photo_file']['name'], PATHINFO_EXTENSION);

        $destination = __DIR__ . '/../uploads/' . $newFileName;
        if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $destination)) {
            $stmt = $conn->prepare("INSERT INTO cleanup_photos (activity_id, photo_url, description, show_image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $activity_id, $newFileName, $description, $show_image);
            $alertMessage = $stmt->execute() ? "เพิ่มภาพกิจกรรมเรียบร้อยแล้ว!" : "เกิดข้อผิดพลาดในการเพิ่มภาพกิจกรรม!";
            $stmt->close();
        } else $alertMessage = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์!";
    }

    // Deleting photo
    if (isset($_POST['delete_photo'])) {
        $stmt = $conn->prepare("SELECT photo_url FROM cleanup_photos WHERE photo_id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();
        $stmt->bind_result($photo_url);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM cleanup_photos WHERE photo_id = ?");
        $stmt->bind_param("i", $photo_id);
        if ($stmt->execute() && file_exists(__DIR__ . '/../uploads/' . $photo_url)) {
            unlink(__DIR__ . '/../uploads/' . $photo_url);
            $alertMessage = "ลบภาพกิจกรรมเรียบร้อยแล้ว!";
        } else $alertMessage = "เกิดข้อผิดพลาดในการลบภาพกิจกรรม!";
        $stmt->close();
    }

    // Editing photo
    if (isset($_POST['edit_photo'])) {
        $stmt = $conn->prepare("UPDATE cleanup_photos SET activity_id = ?, description = ?, show_image = ? WHERE photo_id = ?");
        $stmt->bind_param("isii", $activity_id, $description, $show_image, $photo_id);
        $alertMessage = $stmt->execute() ? "แก้ไขภาพกิจกรรมเรียบร้อยแล้ว!" : "เกิดข้อผิดพลาดในการแก้ไขภาพกิจกรรม!";
        $stmt->close();
    }
}

$cleanup_photos = getCleanupPhotos($conn);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการภาพกิจกรรมเก็บขยะ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>การจัดการภาพกิจกรรมเก็บขยะ</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>รหัสกิจกรรม</label>
                <select name="activity_id" class="form-control" required><?php getActivityOptions($conn); ?></select>
            </div>
            <div class="form-group col-md-3">
                <label>ไฟล์รูปภาพ</label>
                <input type="file" name="photo_file" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>คำอธิบายภาพ</label>
                <input type="text" name="description" class="form-control">
            </div>
            <div class="form-group col-md-3">
                <label>แสดงภาพ</label>
                <input type="checkbox" name="show_image" value="1" checked>
            </div>
        </div>
        <button type="submit" name="add_photo" class="btn btn-primary">เพิ่มภาพกิจกรรม</button>
    </form>

    <h2>รายการภาพกิจกรรมเก็บขยะ</h2>
    <table class="table table-striped">
        <thead>
        <tr><th>รหัสภาพ</th><th>รหัสกิจกรรม</th><th>วันที่กิจกรรม</th><th>ลิงก์รูปภาพ</th><th>คำอธิบาย</th><th>แสดงภาพ</th><th>จัดการ</th></tr>
        </thead>
        <tbody>
        <?php while ($photo = $cleanup_photos->fetch_assoc()): ?>
            <tr>
                <td><?= $photo['photo_id'] ?></td>
                <td><?= $photo['activity_id'] ?></td>
                <td><?= $photo['activity_date'] ?></td>
                <td><a href="../uploads/<?= $photo['photo_url'] ?>" target="_blank">ดูภาพ</a></td>
                <td><?= $photo['description'] ?></td>
                <td><?= $photo['show_image'] ? 'แสดง' : 'ไม่แสดง' ?></td>
                <td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="photo_id" value="<?= $photo['photo_id'] ?>">
                        <button type="submit" name="delete_photo" class="btn btn-danger">ลบ</button>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $photo['photo_id'] ?>">แก้ไข</button>
                    </form>
                </td>
            </tr>
            <div class="modal fade" id="editModal<?= $photo['photo_id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $photo['photo_id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title" id="editModalLabel<?= $photo['photo_id'] ?>">แก้ไขภาพกิจกรรม</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="photo_id" value="<?= $photo['photo_id'] ?>">
                                <div class="form-group">
                                    <label>รหัสกิจกรรม</label>
                                    <select name="activity_id" class="form-control" required><?php getActivityOptions($conn, $photo['activity_id']); ?></select>
                                </div>
                                <div class="form-group">
                                    <label>คำอธิบาย</label>
                                    <input type="text" name="description" class="form-control" value="<?= $photo['description'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>แสดงภาพ</label>
                                    <input type="checkbox" name="show_image" value="1" <?= $photo['show_image'] ? 'checked' : '' ?>>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button><button type="submit" name="edit_photo" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button></div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php if ($alertMessage): ?>
    <script>Swal.fire("<?= $alertMessage ?>");</script>
<?php endif; ?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
