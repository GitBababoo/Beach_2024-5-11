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
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}


// ฟังก์ชันเพื่อดึงข้อมูล waste_types
function getWasteTypes($conn) {
    return $conn->query("SELECT * FROM waste_types");
}

$alertMessage = ""; // ตัวแปรเพื่อเก็บข้อความแจ้งเตือน
$alertType = ""; // ตัวแปรเพื่อเก็บประเภทของการแจ้งเตือน

// ตรวจสอบการเรียกใช้งาน POST สำหรับการเพิ่มประเภทขยะ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_waste_type'])) {
    $waste_type = $_POST['waste_type'];
    $stmt = $conn->prepare("INSERT INTO waste_types (waste_type) VALUES (?)");
    $stmt->bind_param("s", $waste_type);
    if ($stmt->execute()) {
        $alertMessage = "เพิ่มประเภทขยะเรียบร้อยแล้ว!";
        $alertType = "success";
    } else {
        $alertMessage = "เกิดข้อผิดพลาดในการเพิ่มประเภทขยะ!";
        $alertType = "error";
    }
    $stmt->close();
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการลบประเภทขยะ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_waste_type'])) {
    $waste_id = $_POST['waste_id'];
    $stmt = $conn->prepare("DELETE FROM waste_types WHERE waste_id = ?");
    $stmt->bind_param("i", $waste_id);
    if ($stmt->execute()) {
        $alertMessage = "ลบประเภทขยะเรียบร้อยแล้ว!";
        $alertType = "success";
    } else {
        $alertMessage = "เกิดข้อผิดพลาดในการลบประเภทขยะ!";
        $alertType = "error";
    }
    $stmt->close();
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการแก้ไขประเภทขยะ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_waste_type'])) {
    $waste_id = $_POST['waste_id'];
    $waste_type = $_POST['waste_type'];
    $stmt = $conn->prepare("UPDATE waste_types SET waste_type = ? WHERE waste_id = ?");
    $stmt->bind_param("si", $waste_type, $waste_id);
    if ($stmt->execute()) {
        $alertMessage = "แก้ไขประเภทขยะเรียบร้อยแล้ว!";
        $alertType = "success";
    } else {
        $alertMessage = "เกิดข้อผิดพลาดในการแก้ไขประเภทขยะ!";
        $alertType = "error";
    }
    $stmt->close();
}

// ดึงข้อมูลทั้งหมด
$waste_types = getWasteTypes($conn);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการประเภทขยะ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>การจัดการประเภทขยะ</h2>
    <form method="POST" class="mb-4">
        <div class="form-group">
            <input type="text" name="waste_type" class="form-control" placeholder="ประเภทขยะ" required>
        </div>
        <button type="submit" name="add_waste_type" class="btn btn-primary">เพิ่มประเภทขยะ</button>
    </form>

    <h2>รายการประเภทขยะ</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>รหัสประเภทขยะ</th>
            <th>ประเภทของขยะ</th>
            <th>การจัดการ</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($waste = $waste_types->fetch_assoc()): ?>
            <tr>
                <td><?= $waste['waste_id'] ?></td>
                <td>
                    <!-- ฟอร์มการแก้ไข -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="waste_id" value="<?= $waste['waste_id'] ?>">
                        <input type="text" name="waste_type" value="<?= $waste['waste_type'] ?>" required>
                        <button type="submit" name="edit_waste_type" class="btn btn-success">แก้ไข</button>
                    </form>
                </td>
                <td>
                    <!-- ปุ่มลบ -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="waste_id" value="<?= $waste['waste_id'] ?>">
                        <button type="submit" name="delete_waste_type" class="btn btn-danger">ลบ</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php if ($alertMessage): ?>
    <script>
        Swal.fire({
            icon: '<?= $alertType ?>',
            title: 'ผลลัพธ์!',
            text: '<?= $alertMessage ?>',
            confirmButtonText: 'ตกลง'
        });
    </script>
<?php endif; ?>

</body>
</html>
