<?php
$host = 'localhost';
$db = 'ESSDUH_BNS_MEMBER';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // เริ่ม session ถ้ายังไม่ได้เริ่ม
}
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}

// ฟังก์ชันเพื่อดึงข้อมูลผู้ใช้
function getUsers($conn, $search = "") {
    $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    return $stmt->get_result();
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการเพิ่มผู้ใช้
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password']; // ไม่เข้ารหัสรหัสผ่าน
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $role_id = $_POST['role_id'];

        // ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลนี้อยู่ในฐานข้อมูลแล้วหรือไม่
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            echo "<script>Swal.fire('ผิดพลาด', 'ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้แล้ว กรุณาใช้อีเมลอื่น', 'error');</script>";
        } else {
            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, role_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $username, $email, $password, $first_name, $last_name, $role_id);

            if ($stmt->execute()) {
                echo "<script>Swal.fire('สำเร็จ', 'ผู้ใช้ถูกเพิ่มเรียบร้อยแล้ว', 'success');</script>";
            } else {
                echo "<script>Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้', 'error');</script>";
            }

            $stmt->close();
        }
    }

    // ลบผู้ใช้
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>Swal.fire('สำเร็จ', 'ผู้ใช้ถูกลบเรียบร้อยแล้ว', 'success');</script>";
    }

    // แก้ไขผู้ใช้
    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $role_id = $_POST['role_id'];

        // Prepare the update statement
        if (!empty($password)) {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, role_id = ? WHERE user_id = ?");
            $stmt->bind_param("sssssii", $username, $email, $password, $first_name, $last_name, $role_id, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, role_id = ? WHERE user_id = ?");
            $stmt->bind_param("ssssii", $username, $email, $first_name, $last_name, $role_id, $user_id);
        }

        if ($stmt->execute()) {
            echo "<script>Swal.fire('สำเร็จ', 'ข้อมูลผู้ใช้ถูกปรับปรุงเรียบร้อยแล้ว', 'success');</script>";
        } else {
            echo "<script>Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล', 'error');</script>";
        }
        $stmt->close();
    }
}

$search = isset($_POST['search']) ? $_POST['search'] : '';
$users = getUsers($conn, $search);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการผู้ใช้</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        th.sortable:hover {
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>    <?php include_once '../sidebar-navbar.php'?>
<div class="container mt-5">
    <h2>การจัดการผู้ใช้</h2>

    <!-- ฟอร์มสำหรับเพิ่มผู้ใช้ -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-2">
                <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้" required>
            </div>
            <div class="form-group col-md-3">
                <input type="email" name="email" class="form-control" placeholder="อีเมล" required>
            </div>
            <div class="form-group col-md-2">
                <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
            </div>
            <div class="form-group col-md-2">
                <input type="text" name="first_name" class="form-control" placeholder="ชื่อจริง">
            </div>
            <div class="form-group col-md-2">
                <input type="text" name="last_name" class="form-control" placeholder="นามสกุล">
            </div>
        </div>
        <div class="form-group">
            <select name="role_id" class="form-control" required>
                <option value="">เลือกบทบาท</option>
                <?php
                // ดึงข้อมูล roles สำหรับ dropdown
                $roles = $conn->query("SELECT * FROM roles");
                while ($role = $roles->fetch_assoc()) {
                    echo "<option value='{$role['role_id']}'>{$role['role_name']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="add_user" class="btn btn-primary">เพิ่มผู้ใช้</button>
    </form>

    <!-- ฟิลด์ค้นหา -->
    <div class="form-group">
        <input type="text" id="search" class="form-control" placeholder="ค้นหาผู้ใช้..." onkeyup="searchUsers(this.value)">
    </div>

    <!-- ตารางผู้ใช้ -->
    <table class="table table-striped" id="userTable">
        <thead>
        <tr>
            <th>ลำดับที่</th>
            <th class="sortable" onclick="sortTable(1)">ชื่อผู้ใช้ <i class="fas fa-sort"></i></th>
            <th class="sortable" onclick="sortTable(2)">อีเมล <i class="fas fa-sort"></i></th>
            <th class="sortable" onclick="sortTable(3)">ชื่อจริง <i class="fas fa-sort"></i></th>
            <th class="sortable" onclick="sortTable(4)">นามสกุล <i class="fas fa-sort"></i></th>
            <th>บทบาท</th>
            <th>การจัดการ</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $index = 1;
        while ($user = $users->fetch_assoc()) {
            echo "<tr>
                    <td>{$index}</td>
                    <td>{$user['username']}</td>
                    <td>{$user['email']}</td>
                    <td>{$user['first_name']}</td>
                    <td>{$user['last_name']}</td>
                    <td>{$user['role_id']}</td>
                    <td>
                        <form method='POST' class='d-inline'>
                            <input type='hidden' name='user_id' value='{$user['user_id']}'>
                            <button type='submit' name='delete_user' class='btn btn-danger'>ลบ</button>
                        </form>
                        <button class='btn btn-warning' data-toggle='modal' data-target='#editUserModal' onclick='fillEditForm({$user['user_id']}, \"{$user['username']}\", \"{$user['email']}\", \"{$user['first_name']}\", \"{$user['last_name']}\", {$user['role_id']})'>แก้ไข</button>
                    </td>
                </tr>";
            $index++;
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Modal สำหรับแก้ไขผู้ใช้ -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">แก้ไขผู้ใช้</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="form-group">
                        <input type="text" name="username" id="edit_username" class="form-control" placeholder="ชื่อผู้ใช้" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" id="edit_email" class="form-control" placeholder="อีเมล" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="edit_password" class="form-control" placeholder="รหัสผ่าน (ถ้าเปลี่ยน)">
                    </div>
                    <div class="form-group">
                        <input type="text" name="first_name" id="edit_first_name" class="form-control" placeholder="ชื่อจริง">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" id="edit_last_name" class="form-control" placeholder="นามสกุล">
                    </div>
                    <div class="form-group">
                        <select name="role_id" id="edit_role_id" class="form-control" required>
                            <option value="">เลือกบทบาท</option>
                            <?php
                            // ดึงข้อมูล roles สำหรับ dropdown
                            $roles = $conn->query("SELECT * FROM roles");
                            while ($role = $roles->fetch_assoc()) {
                                echo "<option value='{$role['role_id']}'>{$role['role_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" name="edit_user" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชันสำหรับค้นหาผู้ใช้
    function searchUsers(value) {
        $.ajax({
            method: 'POST',
            url: 'search_users.php', // URL สำหรับค้นหาผู้ใช้
            data: { search: value },
            success: function (data) {
                $('#userTable tbody').html(data);
            }
        });
    }

    // ฟังก์ชันเติมฟอร์มแก้ไข
    function fillEditForm(userId, username, email, firstName, lastName, roleId) {
        $('#edit_user_id').val(userId);
        $('#edit_username').val(username);
        $('#edit_email').val(email);
        $('#edit_first_name').val(firstName);
        $('#edit_last_name').val(lastName);
        $('#edit_role_id').val(roleId);
    }
</script>
</body>
</html>
