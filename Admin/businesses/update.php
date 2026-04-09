<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_id = $_POST['business_id'];
    $business_name = $_POST['business_name'];
    $business_type_id = $_POST['business_type_id'];
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $contact_info = $_POST['contact_info'];
    $website = $_POST['website'] ?: null; // ใช้ null หากไม่กรอกข้อมูล
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูล
    $stmt = $pdo->prepare("UPDATE businesses SET business_name = ?, business_type_id = ?, user_id = ?, description = ?, contact_info = ?, website = ?, latitude = ?, longitude = ? WHERE business_id = ?");
    $stmt->execute([$business_name, $business_type_id, $user_id, $description, $contact_info, $website, $latitude, $longitude, $business_id]);

    // Redirect ไปยังหน้าธุรกิจหลังจากอัปเดตเสร็จสิ้น
    header('Location: businesses.php');
    exit();
}
?>
