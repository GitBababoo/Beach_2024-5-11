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
require 'db.php';
// ดึงข้อมูลประเภทธุรกิจ
$business_types_stmt = $pdo->query("SELECT * FROM business_types");
$business_types = $business_types_stmt->fetchAll();

// ดึงข้อมูลผู้ใช้
$users_stmt = $pdo->query("SELECT * FROM users");
$users = $users_stmt->fetchAll();

// ตรวจสอบการเพิ่มธุรกิจ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $business_name = $_POST['business_name'];
    $business_type_id = $_POST['business_type_id'];
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $contact_info = $_POST['contact_info'];
    $website = $_POST['website']?:null;
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // ตรวจสอบค่าที่ซ้ำในฟิลด์เว็บไซต์
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM businesses WHERE website = ?");
    $stmt->execute([$website]);
    if ($stmt->fetchColumn() > 0) {
        echo "<script>alert('เว็บไซต์นี้มีอยู่แล้วในระบบ กรุณาลองใหม่อีกครั้ง');</script>";
    } else {
        // เพิ่มธุรกิจ
        $stmt = $pdo->prepare("INSERT INTO businesses (business_name, business_type_id, user_id, description, contact_info, website, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$business_name, $business_type_id, $user_id, $description, $contact_info, $website, $latitude, $longitude]);
        echo "<script>alert('เพิ่มธุรกิจเรียบร้อยแล้ว'); window.location.href='businesses.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการธุรกิจ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h2>จัดการธุรกิจ</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">เพิ่มธุรกิจ</button>

    <table class="table table-striped mt-3">
        <thead>
        <tr>
            <th>รหัสธุรกิจ</th>
            <th>ชื่อธุรกิจ</th>
            <th>ประเภทธุรกิจ</th>
            <th>ผู้ใช้</th>
            <th>คำอธิบาย</th>
            <th>ข้อมูลติดต่อ</th>
            <th>เว็บไซต์</th>
            <th>ละติจูด</th>
            <th>ลองจิจูด</th>
            <th>การดำเนินการ</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // แสดงข้อมูลธุรกิจ
        $stmt = $pdo->query("SELECT * FROM businesses");
        $businesses = $stmt->fetchAll();

        foreach ($businesses as $business): ?>
            <tr>
                <td><?= $business['business_id'] ?></td>
                <td><?= $business['business_name'] ?></td>
                <td><?= $business['business_type_id'] ?></td>
                <td><?= $business['user_id'] ?></td>
                <td><?= $business['description'] ?></td>
                <td><?= $business['contact_info'] ?></td>
                <td><?= $business['website'] ?></td>
                <td><?= $business['latitude'] ?></td>
                <td><?= $business['longitude'] ?></td>
                <td>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $business['business_id'] ?>">แก้ไข</button>
                    <a href="delete.php?id=<?= $business['business_id'] ?>" class="btn btn-danger">ลบ</a>
                </td>
            </tr>

            <!-- Modal แก้ไข -->
            <div class="modal fade" id="editModal<?= $business['business_id'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="update.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">แก้ไขธุรกิจ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="business_id" value="<?= $business['business_id'] ?>">
                                <div class="form-group">
                                    <label for="business_name">ชื่อธุรกิจ</label>
                                    <input type="text" class="form-control" name="business_name" value="<?= $business['business_name'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="business_type_id">ประเภทธุรกิจ</label>
                                    <select class="form-control" name="business_type_id" required>
                                        <?php foreach ($business_types as $type): ?>
                                            <option value="<?= $type['business_type_id'] ?>" <?= ($business['business_type_id'] == $type['business_type_id']) ? 'selected' : '' ?>>
                                                <?= $type['business_type_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="user_id">ผู้ใช้</label>
                                    <select class="form-control" name="user_id" required>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user['user_id'] ?>" <?= ($business['user_id'] == $user['user_id']) ? 'selected' : '' ?>>
                                                <?= $user['username'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">คำอธิบาย</label>
                                    <textarea class="form-control" name="description" required><?= $business['description'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="contact_info">ข้อมูลติดต่อ</label>
                                    <input type="text" class="form-control" name="contact_info" value="<?= $business['contact_info'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="website">เว็บไซต์</label>
                                    <input type="url" class="form-control" name="website" value="<?= $business['website'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="latitude">ละติจูด</label>
                                    <input type="text" class="form-control" name="latitude" value="<?= $business['latitude'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="longitude">ลองจิจูด</label>
                                    <input type="text" class="form-control" name="longitude" value="<?= $business['longitude'] ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal เพิ่มธุรกิจ -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="add.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มธุรกิจ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="business_name">ชื่อธุรกิจ</label>
                        <input type="text" class="form-control" name="business_name" required>
                    </div>
                    <div class="form-group">
                        <label for="business_type_id">ประเภทธุรกิจ</label>
                        <select class="form-control" name="business_type_id" required>
                            <?php foreach ($business_types as $type): ?>
                                <option value="<?= $type['business_type_id'] ?>"><?= $type['business_type_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_id">ผู้ใช้</label>
                        <select class="form-control" name="user_id" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['user_id'] ?>"><?= $user['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">คำอธิบาย</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="contact_info">ข้อมูลติดต่อ</label>
                        <input type="text" class="form-control" name="contact_info" required>
                    </div>
                    <div class="form-group">
                        <label for="website">เว็บไซต์ (ถ้ามี)</label>
                        <input type="url" class="form-control" name="website" placeholder="เช่น: http://www.example.com">
                    </div>
                    <div class="form-group">
                        <label for="latitude">ละติจูด</label>
                        <input type="text" class="form-control" name="latitude" required>
                    </div>
                    <div class="form-group">
                        <label for="longitude">ลองจิจูด</label>
                        <input type="text" class="form-control" name="longitude" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
