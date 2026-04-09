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

// ฟังก์ชันเพื่อดึงข้อมูลบทบาท
function getRoles($conn) {
    $sql = "SELECT * FROM roles";
    return $conn->query($sql);
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการเพิ่มบทบาท
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_role'])) {
    $role_name = $_POST['role_name'];
    $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->bind_param("s", $role_name);
    $stmt->execute();
    $stmt->close();
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการลบบทบาท
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_role'])) {
    $role_id = $_POST['role_id'];
    $stmt = $conn->prepare("DELETE FROM roles WHERE role_id = ?");
    $stmt->bind_param("i", $role_id);
    $stmt->execute();
    $stmt->close();
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการแก้ไขบทบาท
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_role'])) {
    $role_id = $_POST['role_id'];
    $role_name = $_POST['role_name'];
    $stmt = $conn->prepare("UPDATE roles SET role_name = ? WHERE role_id = ?");
    $stmt->bind_param("si", $role_name, $role_id);
    $stmt->execute();
    $stmt->close();
}

$roles = getRoles($conn);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการบทบาทผู้ใช้</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>การจัดการบทบาทผู้ใช้</h2>

    <!-- ฟอร์มสำหรับเพิ่มบทบาท -->
    <form method="POST" class="mb-4">
        <div class="form-group">
            <input type="text" name="role_name" class="form-control" placeholder="ชื่อบทบาท" required>
        </div>
        <button type="submit" name="add_role" class="btn btn-primary">เพิ่มบทบาท</button>
    </form>

    <!-- ตารางบทบาท -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>รหัสบทบาท</th>
            <th>ชื่อบทบาท</th>
            <th>การจัดการ</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($role = $roles->fetch_assoc()): ?>
            <tr>
                <td><?= $role['role_id'] ?></td>
                <td><?= $role['role_name'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="role_id" value="<?= $role['role_id'] ?>">
                        <button type="submit" name="delete_role" class="btn btn-danger" onclick="return confirm('คุณแน่ใจว่าต้องการลบบทบาทนี้?');">ลบ</button>
                    </form>
                    <!-- ปุ่มแก้ไขบทบาท -->
                    <button class="btn btn-warning" onclick="editRole(<?= $role['role_id'] ?>, '<?= $role['role_name'] ?>')">แก้ไข</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal สำหรับแก้ไขบทบาท -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">แก้ไขบทบาท</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm" method="POST">
                    <input type="hidden" name="role_id" id="edit_role_id">
                    <div class="form-group">
                        <input type="text" name="role_name" class="form-control" placeholder="ชื่อบทบาท" required id="edit_role_name">
                    </div>
                    <button type="submit" name="edit_role" class="btn btn-primary">อัปเดตบทบาท</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editRole(roleId, roleName) {
        $('#edit_role_id').val(roleId);
        $('#edit_role_name').val(roleName);
        $('#editRoleModal').modal('show');
    }
</script>
</body>
</html>
