<?php
$host = 'localhost';
$db = 'ESSDUH_BNS_MEMBER';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบการเรียกใช้งาน POST สำหรับการแก้ไขผู้ใช้
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            $stmt->bind_param("ssssi", $username, $email, $first_name, $last_name, $role_id, $user_id);
        }

        if ($stmt->execute()) {
            echo "<script>Swal.fire('สำเร็จ', 'ข้อมูลผู้ใช้ถูกปรับปรุงเรียบร้อยแล้ว', 'success');</script>";
        } else {
            echo "<script>Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล', 'error');</script>";
        }
        $stmt->close();
    }
}

$conn->close();
?>
