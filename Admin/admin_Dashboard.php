<?php
session_start();
include_once 'db.php';
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}

include 'sidebar-navbar.php';

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>แดชบอร์ด</title>
</head>
<body class="bg-gray-100">


<div class="container mx-auto p-5">
    <h1 class="text-2xl font-bold mb-4">แดชบอร์ด</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold">จำนวนขยะที่เก็บได้</h2>
            <p>
                <?php
                $total_waste = mysqli_query($db, "SELECT SUM(total_waste) AS total FROM cleanup_activities");
                $total_waste_result = mysqli_fetch_assoc($total_waste);
                echo $total_waste_result['total'] ?: 0; // แสดงผลรวมขยะ
                ?>
                kg
            </p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold">จำนวนธุรกิจที่ได้รับการโปรโมท</h2>
            <p>
                <?php
                $total_businesses = mysqli_query($db, "SELECT COUNT(*) AS total FROM businesses");
                $total_businesses_result = mysqli_fetch_assoc($total_businesses);
                echo $total_businesses_result['total']; // แสดงจำนวนธุรกิจ
                ?>
            </p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold">จำนวนภาพที่อัพโหลด</h2>
            <p>
                <?php
                $total_photos = mysqli_query($db, "SELECT COUNT(*) AS total FROM cleanup_photos");
                $total_photos_result = mysqli_fetch_assoc($total_photos);
                echo $total_photos_result['total']; // แสดงจำนวนภาพ
                ?>
            </p>
        </div>
    </div>

    <!-- รวม report.html ไว้ที่นี่ -->
    <?php include_once 'report.html'; ?>

    <h2 class="text-xl font-bold mb-4">แผนที่</h2>
    <?php include_once 'get_cleanup_locations.php'; ?>
</div>

</body>
</html>
