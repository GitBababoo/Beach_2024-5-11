<?php
session_start();
include 'DB_OC.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // ค้นหาผู้ใช้ในฐานข้อมูล
    $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ? AND first_name = ? AND last_name = ?");
    $stmt->bind_param("sss", $email, $first_name, $last_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // สร้าง token สำหรับการรีเซ็ตรหัสผ่าน
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 300; // ตั้งเวลาให้หมดอายุใน 5 นาที

        // บันทึก token และเวลาหมดอายุในฐานข้อมูล
        $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $stmt->bind_param("sis", $token, $expires, $email);
        $stmt->execute();

        // แสดงลิงค์รีเซ็ตรหัสผ่านในหน้าเว็บ
        $reset_link = 'http://localhost/beach/reset_password.php?token=' . $token;
        $link_display = htmlspecialchars($reset_link); // เพื่อความปลอดภัย
    } else {
        $error_message = "ไม่พบผู้ใช้ที่ตรงกับข้อมูลที่ให้มา.";
    }
}

// เช็คการรีเซ็ตรหัสผ่านจาก token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // ตรวจสอบ token ในฐานข้อมูล
    $stmt = $db->prepare("SELECT user_id FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // ถ้ามี token ที่ถูกต้อง ให้แสดงฟอร์มสำหรับเปลี่ยนรหัสผ่าน
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
            $new_password = $_POST['new_password'];

            // อัปเดตรหัสผ่านในฐานข้อมูล
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();

            echo "รหัสผ่านของคุณถูกเปลี่ยนเรียบร้อยแล้ว.";
            exit();
        }
    } else {
        echo "ลิงค์รีเซ็ตรหัสผ่านนี้ไม่ถูกต้อง.";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
            padding: 20px;
        }
        .neumorphic {
            background: #e0e0e0;
            border-radius: 50px;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            padding: 20px;
            margin: 50px 0;
        }
        .link-box {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
        }
        .link-box input {
            border: none;
            background: none;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <?php include_once 'Navigation Bar.php'; ?>

    <h2 class="text-center">ลืมรหัสผ่าน</h2>
    <form method="post" class="neumorphic">
        <div class="mb-3">
            <label for="email" class="form-label">อีเมล</label>
            <input type="email" class="form-control" name="email" placeholder="กรุณากรอกอีเมลของคุณ" required>
        </div>
        <div class="mb-3">
            <label for="first_name" class="form-label">ชื่อจริง</label>
            <input type="text" class="form-control" name="first_name" placeholder="กรุณากรอกชื่อจริงของคุณ" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">นามสกุล</label>
            <input type="text" class="form-control" name="last_name" placeholder="กรุณากรอกนามสกุลของคุณ" required>
        </div>
        <button type="submit" class="btn btn-primary">ส่งลิงค์รีเซ็ตรหัสผ่าน</button>
        <a href="/beach/login.php" class="btn btn-secondary">ยกเลิก</a>
    </form>

    <?php if (isset($link_display)): ?>
        <h3 class="text-center">ลิงค์รีเซ็ตรหัสผ่านของคุณคือ:</h3>
        <div class="link-box">
            <input type="text" value="<?php echo $link_display; ?>" readonly onclick="this.select();">
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['token'])): ?>
        <h3 class="text-center">รีเซ็ตรหัสผ่านของคุณ</h3>
        <form method="post" class="neumorphic">
            <div class="mb-3">
                <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                <input type="password" class="form-control" name="new_password" placeholder="กรุณากรอกรหัสผ่านใหม่" required>
            </div>
            <button type="submit" class="btn btn-primary">รีเซ็ตรหัสผ่าน</button>
            <a href="/beach/login.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>

