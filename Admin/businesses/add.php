<?php
require 'db.php';

// ตรวจสอบการเพิ่มธุรกิจ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $business_name = $_POST['business_name'];
    $business_type_id = $_POST['business_type_id'];
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $contact_info = $_POST['contact_info'];
    $website = $_POST['website'] ?: null; // ใช้ null หากไม่กรอกข้อมูล
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // ตรวจสอบค่าที่ซ้ำในฟิลด์เว็บไซต์
    if ($website) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM businesses WHERE website = ?");
        $stmt->execute([$website]);
        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('เว็บไซต์นี้มีอยู่แล้วในระบบ กรุณาลองใหม่อีกครั้ง');</script>";
            echo "<script>window.location.href='businesses.php';</script>";
            exit;
        }
    }

    // เพิ่มธุรกิจ
    $stmt = $pdo->prepare("INSERT INTO businesses (business_name, business_type_id, user_id, description, contact_info, website, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$business_name, $business_type_id, $user_id, $description, $contact_info, $website, $latitude, $longitude]);

    echo "<script>alert('เพิ่มธุรกิจเรียบร้อยแล้ว'); window.location.href='businesses.php';</script>";
}
?>
