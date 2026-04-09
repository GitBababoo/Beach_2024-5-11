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

function getBusinessPhotos($conn) {
    $sql = "SELECT bp.*, b.business_name FROM business_photos bp 
            JOIN businesses b ON bp.business_id = b.business_id";
    return $conn->query($sql);
}

function getBusinessOptions($conn, $selected_id = null) {
    $sql = "SELECT business_id, business_name FROM businesses";
    $businesses = $conn->query($sql);
    while ($business = $businesses->fetch_assoc()) {
        $selected = ($business['business_id'] == $selected_id) ? "selected" : "";
        echo "<option value='{$business['business_id']}' {$selected}>{$business['business_name']}</option>";
    }
}

$alertMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $photo_id = $_POST['photo_id'] ?? null;
    $business_id = $_POST['business_id'];
    $description = $_POST['description'] ?? '';
    $show_image = isset($_POST['show_image']) ? 1 : 0;

    // Handle adding a photo
    if (isset($_POST['add_photo']) && isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] == UPLOAD_ERR_OK) {
        $formattedDate = date('d-m-Y');
        $counter = $conn->query("SELECT COUNT(*) AS count FROM business_photos WHERE photo_url LIKE '{$formattedDate}_business_%'")->fetch_assoc()['count'];
        $newFileName = "{$formattedDate}_business_" . chr(65 + $counter) . '.' . pathinfo($_FILES['photo_file']['name'], PATHINFO_EXTENSION);
        $destination = __DIR__ . '/../uploads/' . $newFileName;

        if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $destination)) {
            $stmt = $conn->prepare("INSERT INTO business_photos (business_id, photo_url, description, show_image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $business_id, $newFileName, $description, $show_image);
            $alertMessage = $stmt->execute() ? "เพิ่มภาพธุรกิจเรียบร้อยแล้ว!" : "เกิดข้อผิดพลาดในการเพิ่มภาพธุรกิจ!";
            $stmt->close();
        } else {
            $alertMessage = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์!";
        }
    }

    // Handle deleting a photo
    if (isset($_POST['delete_photo'])) {
        $stmt = $conn->prepare("SELECT photo_url FROM business_photos WHERE photo_id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();
        $stmt->bind_result($photo_url);
        $stmt->fetch();
        $stmt->close();

        if ($photo_url) {
            $stmt = $conn->prepare("DELETE FROM business_photos WHERE photo_id = ?");
            $stmt->bind_param("i", $photo_id);
            if ($stmt->execute() && file_exists(__DIR__ . '/../uploads/' . $photo_url)) {
                unlink(__DIR__ . '/../uploads/' . $photo_url);
                $alertMessage = "ลบภาพธุรกิจเรียบร้อยแล้ว!";
            } else {
                $alertMessage = "เกิดข้อผิดพลาดในการลบภาพธุรกิจ!";
            }
            $stmt->close();
        } else {
            $alertMessage = "ไม่พบภาพที่ต้องการลบ!";
        }
    }

    // Handle editing a photo
    if (isset($_POST['edit_photo'])) {
        $stmt = $conn->prepare("UPDATE business_photos SET business_id = ?, description = ?, show_image = ? WHERE photo_id = ?");
        $stmt->bind_param("isii", $business_id, $description, $show_image, $photo_id);
        $alertMessage = $stmt->execute() ? "แก้ไขภาพธุรกิจเรียบร้อยแล้ว!" : "เกิดข้อผิดพลาดในการแก้ไขภาพธุรกิจ!";
        $stmt->close();
    }
}

$business_photos = getBusinessPhotos($conn);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการภาพธุรกิจ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>การจัดการภาพธุรกิจ</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>รหัสธุรกิจ</label>
                <select name="business_id" class="form-control" required><?php getBusinessOptions($conn); ?></select>
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
        <button type="submit" name="add_photo" class="btn btn-primary">เพิ่มภาพธุรกิจ</button>
    </form>

    <h2>รายการภาพธุรกิจ</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>รหัสภาพ</th>
            <th>รหัสธุรกิจ</th>
            <th>ชื่อธุรกิจ</th>
            <th>ลิงก์รูปภาพ</th>
            <th>คำอธิบาย</th>
            <th>แสดงภาพ</th>
            <th>จัดการ</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($photo = $business_photos->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($photo['photo_id']) ?></td>
                <td><?= htmlspecialchars($photo['business_id']) ?></td>
                <td><?= htmlspecialchars($photo['business_name']) ?></td>
                <td><a href="../uploads/<?= htmlspecialchars($photo['photo_url']) ?>" target="_blank">ดูภาพ</a></td>
                <td><?= htmlspecialchars($photo['description']) ?></td>
                <td><?= $photo['show_image'] ? 'แสดง' : 'ไม่แสดง' ?></td>
                <td>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="photo_id" value="<?= htmlspecialchars($photo['photo_id']) ?>">
                        <button type="submit" name="delete_photo" class="btn btn-danger">ลบ</button>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $photo['photo_id'] ?>">แก้ไข</button>
                    </form>
                </td>
            </tr>
            <div class="modal fade" id="editModal<?= $photo['photo_id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $photo['photo_id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel<?= $photo['photo_id'] ?>">แก้ไขภาพธุรกิจ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="photo_id" value="<?= htmlspecialchars($photo['photo_id']) ?>">
                                <div class="form-group">
                                    <label>รหัสธุรกิจ</label>
                                    <select name="business_id" class="form-control" required><?php getBusinessOptions($conn, $photo['business_id']); ?></select>
                                </div>
                                <div class="form-group">
                                    <label>คำอธิบายภาพ</label>
                                    <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($photo['description']) ?>">
                                </div>
                                <div class="form-group">
                                    <label>แสดงภาพ</label>
                                    <input type="checkbox" name="show_image" value="1" <?= $photo['show_image'] ? 'checked' : '' ?>>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_photo" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($alertMessage): ?>
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: 'ข้อมูล!',
                    text: '<?= $alertMessage ?>',
                    icon: 'info',
                    confirmButtonText: 'ตกลง'
                });
            });
        </script>
    <?php endif; ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
