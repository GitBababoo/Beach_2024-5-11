<?php
session_start();
include 'DB_OC.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // ค้นหา token ในฐานข้อมูล
    $stmt = $db->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expires > ?");
    $expires = date("U");
    $stmt->bind_param("si", $token, $expires);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['new_password'];

            // ดึง user_id
            $stmt->bind_result($user_id);
            $stmt->fetch(); // ดึง user_id ที่ตรงกับ token

            // อัปเดตรหัสผ่านในฐานข้อมูลและลบ token
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE user_id = ?");
            $stmt->bind_param("si", $new_password, $user_id);
            $stmt->execute();

            // แสดงข้อความและ redirect ไปยังหน้า index.php
            echo "<script>alert('รหัสผ่านของคุณถูกรีเซ็ตรเรียบร้อยแล้ว.'); window.location.href='/beach/login.php';</script>";
            exit();
        }
    } else {
        echo "ลิงค์รีเซ็ตรหัสผ่านนี้ไม่ถูกต้องหรือหมดอายุแล้ว.";
        exit();
    }
} else {
    echo "ไม่มี token สำหรับการรีเซ็ตรหัสผ่าน.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts (Orbitron) -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f0f0;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .neumorphic {
            background: #e0e0e0;
            border-radius: 50px;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            padding: 20px;
            margin: 50px 0;
        }
    </style>
</head>
<body>
<div id="particles-js"></div>
<div class="container">
    <h2 class="text-center">Reset Your Password</h2>
    <form method="post" class="neumorphic">
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="text" class="form-control" name="new_password" required> <!-- เปลี่ยนเป็น input type="text" -->
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
<!-- Particles JS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 80
            },
            "size": {
                "value": 3
            },
            "interactivity": {
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    }
                }
            }
        }
    });
</script>
</body>
</html>
