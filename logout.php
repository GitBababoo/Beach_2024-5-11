<?php
session_start(); // เริ่มเซสชัน

// ทำลายเซสชันทั้งหมด
$_SESSION = array(); // ลบข้อมูลทั้งหมดในเซสชัน

// ถ้ามีการตั้งค่า cookie ของเซสชัน ก็ให้ลบออก
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ทำลายเซสชัน
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบหรือหน้าแรก
header("Location: login.php");
exit();
?>
