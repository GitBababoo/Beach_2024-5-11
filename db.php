<?php
$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$user = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
$pass = ''; // รหัสผ่านฐานข้อมูล
$dbname = 'essduh_bns_member'; // ชื่อฐานข้อมูล

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
