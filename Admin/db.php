<?php
$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$user = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
$pass = ''; // รหัสผ่านฐานข้อมูล
$dbname = 'ESSDUH_BNS_MEMBER'; // ชื่อฐานข้อมูล

// เชื่อมต่อฐานข้อมูล
$db = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
