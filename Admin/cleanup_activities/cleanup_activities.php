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

$alertMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_activity'])) {
        // Add activity logic here
        $activity_date = $_POST['activity_date'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $total_waste = $_POST['total_waste'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $waste_type_id = $_POST['waste_type_id'];
        $user_id = $_POST['user_id'];

        $stmt = $conn->prepare("INSERT INTO cleanup_activities (activity_date, location, description, total_waste, latitude, longitude, waste_type_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $activity_date, $location, $description, $total_waste, $latitude, $longitude, $waste_type_id, $user_id);
        if ($stmt->execute()) {
            $alertMessage = 'เพิ่มกิจกรรมสำเร็จ';
        }
        $stmt->close();
    } elseif (isset($_POST['delete_activity'])) {
        $activity_id = $_POST['activity_id'];
        $stmt = $conn->prepare("DELETE FROM cleanup_activities WHERE activity_id = ?");
        $stmt->bind_param("s", $activity_id);
        $stmt->execute();
        $stmt->close();
        $alertMessage = 'ลบกิจกรรมสำเร็จ';
    } elseif (isset($_POST['edit_activity'])) {
        $activity_id = $_POST['activity_id'];
        $activity_date = $_POST['activity_date'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $total_waste = $_POST['total_waste'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $waste_type_id = $_POST['waste_type_id'];
        $user_id = $_POST['user_id'];

        $stmt = $conn->prepare("UPDATE cleanup_activities SET activity_date = ?, location = ?, description = ?, total_waste = ?, latitude = ?, longitude = ?, waste_type_id = ?, user_id = ? WHERE activity_id = ?");
        $stmt->bind_param("sssssssss", $activity_date, $location, $description, $total_waste, $latitude, $longitude, $waste_type_id, $user_id, $activity_id);
        if ($stmt->execute()) {
            $alertMessage = 'แก้ไขกิจกรรมสำเร็จ';
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT ca.*, wt.waste_type, u.username FROM cleanup_activities ca JOIN waste_types wt ON ca.waste_type_id = wt.waste_id JOIN users u ON ca.user_id = u.user_id");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจัดการกิจกรรมเก็บขยะ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>การจัดการกิจกรรมเก็บขยะ</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addActivityModal">เพิ่มกิจกรรม</button>

    <table class="table mt-3">
        <thead>
        <tr>
            <th>รหัสกิจกรรม</th>
            <th>วันที่ทำกิจกรรม</th>
            <th>สถานที่</th>
            <th>คำอธิบาย</th>
            <th>น้ำหนักขยะ (kg)</th>
            <th>ละติจูด</th>
            <th>ลองจิจูด</th>
            <th>ประเภทขยะ</th>
            <th>ผู้ใช้</th>
            <th>ดำเนินการ</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['activity_id']; ?></td>
                <td><?php echo $row['activity_date']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['total_waste']; ?></td>
                <td><?php echo $row['latitude']; ?></td>
                <td><?php echo $row['longitude']; ?></td>
                <td><?php echo $row['waste_type']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td>
                    <button class="btn btn-warning" onclick="editActivity(<?php echo $row['activity_id']; ?>, '<?php echo $row['activity_date']; ?>', '<?php echo $row['location']; ?>', '<?php echo $row['description']; ?>', '<?php echo $row['total_waste']; ?>', '<?php echo $row['latitude']; ?>', '<?php echo $row['longitude']; ?>', '<?php echo $row['waste_type_id']; ?>', '<?php echo $row['user_id']; ?>')">แก้ไข</button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="activity_id" value="<?php echo $row['activity_id']; ?>">
                        <button type="submit" name="delete_activity" class="btn btn-danger">ลบ</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Activity Modal -->
<div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="addActivityLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addActivityLabel">เพิ่มกิจกรรมการเก็บขยะ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>วันที่ทำกิจกรรม</label>
                        <input type="date" class="form-control" name="activity_date" required>
                    </div>
                    <div class="form-group">
                        <label>สถานที่</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    <div class="form-group">
                        <label>คำอธิบาย</label>
                        <input type="text" class="form-control" name="description">
                    </div>
                    <div class="form-group">
                        <label>น้ำหนักขยะ</label>
                        <input type="number" class="form-control" name="total_waste" required>
                    </div>
                    <div class="form-group">
                        <label>ละติจูด</label>
                        <input type="text" class="form-control" name="latitude" required>
                    </div>
                    <div class="form-group">
                        <label>ลองจิจูด</label>
                        <input type="text" class="form-control" name="longitude" required>
                    </div>
                    <div class="form-group">
                        <label>ประเภทขยะ</label>
                        <select name="waste_type_id" class="form-control" required>
                            <?php
                            $waste_types = $conn->query("SELECT * FROM waste_types");
                            while ($type = $waste_types->fetch_assoc()) {
                                echo "<option value='{$type['waste_id']}'>{$type['waste_type']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ผู้ใช้</label>
                        <select name="user_id" class="form-control" required>
                            <?php
                            $users = $conn->query("SELECT user_id, username FROM users");
                            while ($user = $users->fetch_assoc()) {
                                echo "<option value='{$user['user_id']}'>{$user['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" name="add_activity" class="btn btn-primary">เพิ่มกิจกรรม</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" role="dialog" aria-labelledby="editActivityLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editActivityLabel">แก้ไขกิจกรรมการเก็บขยะ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="editActivityForm">
                <div class="modal-body">
                    <input type="hidden" name="activity_id" id="editActivityId">
                    <div class="form-group">
                        <label>วันที่ทำกิจกรรม</label>
                        <input type="date" class="form-control" name="activity_date" id="editActivityDate" required>
                    </div>
                    <div class="form-group">
                        <label>สถานที่</label>
                        <input type="text" class="form-control" name="location" id="editActivityLocation" required>
                    </div>
                    <div class="form-group">
                        <label>คำอธิบาย</label>
                        <input type="text" class="form-control" name="description" id="editActivityDescription">
                    </div>
                    <div class="form-group">
                        <label>น้ำหนักขยะ</label>
                        <input type="number" class="form-control" name="total_waste" id="editActivityTotalWaste" required>
                    </div>
                    <div class="form-group">
                        <label>ละติจูด</label>
                        <input type="text" class="form-control" name="latitude" id="editActivityLatitude" required>
                    </div>
                    <div class="form-group">
                        <label>ลองจิจูด</label>
                        <input type="text" class="form-control" name="longitude" id="editActivityLongitude" required>
                    </div>
                    <div class="form-group">
                        <label>ประเภทขยะ</label>
                        <select name="waste_type_id" class="form-control" id="editWasteTypeId" required>
                            <?php
                            $waste_types = $conn->query("SELECT * FROM waste_types");
                            while ($type = $waste_types->fetch_assoc()) {
                                echo "<option value='{$type['waste_id']}'>{$type['waste_type']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ผู้ใช้</label>
                        <select name="user_id" class="form-control" id="editUserId" required>
                            <?php
                            $users = $conn->query("SELECT user_id, username FROM users");
                            while ($user = $users->fetch_assoc()) {
                                echo "<option value='{$user['user_id']}'>{$user['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" name="edit_activity" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editActivity(id, date, location, description, totalWaste, latitude, longitude, wasteTypeId, userId) {
        $('#editActivityId').val(id);
        $('#editActivityDate').val(date);
        $('#editActivityLocation').val(location);
        $('#editActivityDescription').val(description);
        $('#editActivityTotalWaste').val(totalWaste);
        $('#editActivityLatitude').val(latitude);
        $('#editActivityLongitude').val(longitude);
        $('#editWasteTypeId').val(wasteTypeId);
        $('#editUserId').val(userId);
        $('#editActivityModal').modal('show');
    }
</script>

<?php if ($alertMessage): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: '<?= $alertMessage ?>',
            confirmButtonText: 'ตกลง'
        });
    </script>
<?php endif; ?>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
