<?php
session_start();

$host = 'localhost';
$db = 'ESSDUH_BNS_MEMBER';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$alertMessage = ''; // Variable for alert message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the statement
    $stmt = $conn->prepare("SELECT user_id, role_id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $role_id);
        $stmt->fetch();

        // Check if the role_id is for admin
        if ($role_id == 1) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role_id'] = $role_id;

            // Redirect to the admin dashboard
            header("Location: admin_dashboard.php");
            exit(); // Exit after redirecting
        } else {
            $alertMessage = 'เฉพาะผู้ดูแลเท่านั้นที่สามารถเข้าสู่ระบบได้';
        }
    } else {
        $alertMessage = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ดูแล</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <link rel="stylesheet" href="login_admin.css">
</head>
<body>

<div id="particles-js"></div>

<div class="container">
    <div class="login-container">
        <h2 class="text-center mb-4"><i class="fas fa-lock"></i> เข้าสู่ระบบผู้ดูแล</h2>
        <?php if (!empty($alertMessage)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: '<?= htmlspecialchars($alertMessage) ?>'
                });
            </script>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group mb-3">
                <label><i class="fas fa-user"></i> ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group mb-4">
                <label><i class="fas fa-lock"></i> รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
        </form>
    </div>
</div>

<script>
    particlesJS("particles-js", {
        "particles": {
            "number": { "value": 120 },
            "color": { "value": "#00ff80" },
            "shape": { "type": "circle" },
            "opacity": { "value": 0.5 },
            "size": { "value": 4, "random": true },
            "line_linked": { "enable": true, "color": "#00ff80", "opacity": 0.4 },
            "move": { "enable": true, "speed": 3 }
        },
        "interactivity": {
            "events": {
                "onhover": { "enable": true, "mode": "repulse" },
                "onclick": { "enable": true, "mode": "push" }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
