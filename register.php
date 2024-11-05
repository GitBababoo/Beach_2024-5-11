<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost:3306', 'root', '', 'essduh_bns_member');

// ตรวจสอบการลงทะเบียน
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email']; // อีเมลใหม่
    $password = $_POST['password']; // รหัสผ่านไม่เข้ารหัส
    $confirm_password = $_POST['confirm_password']; // รหัสผ่านยืนยัน

    // ตรวจสอบว่ารหัสผ่านทั้งสองตรงกัน
    if ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน!";
    } else {
        // คิวรีเพื่อบันทึกข้อมูลผู้ใช้
        $query = "INSERT INTO users (username, first_name, last_name, email, password, role_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $role_id = 1; // กำหนด role_id เป็น 1 สำหรับผู้ใช้ทั่วไป
        $stmt->bind_param("sssssi", $username, $first_name, $last_name, $email, $password, $role_id); // ใส่รหัสผ่านแบบธรรมดา
        if ($stmt->execute()) {
            // ลงทะเบียนสำเร็จ
            $success_message = "ลงทะเบียนสำเร็จ! กรุณาเข้าสู่ระบบ.";
            header("Location: login.php?message=" . urlencode($success_message)); // เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบพร้อมข้อความ
            exit();
        } else {
            $error = "ไม่สามารถลงทะเบียนได้: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน - OTOP</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>
<body>
<?php include_once 'Navigation Bar.php'; ?>

<div class="register-container">
    <h2 class="text-center text-2xl font-bold mb-4">ลงทะเบียน</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger bg-red-200 text-red-600 p-2 rounded mb-4">
            <?php echo $error; ?>
        </div>
    <?php elseif (isset($success_message)): ?>
        <div class="alert alert-success bg-green-200 text-green-600 p-2 rounded mb-4">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้</label>
            <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-fda085" id="username" name="username" required>
        </div>
        <div class="mb-4">
            <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อจริง</label>
            <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-fda085" id="first_name" name="first_name" required>
        </div>
        <div class="mb-4">
            <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
            <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-fda085" id="last_name" name="last_name" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
            <input type="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-fda085" id="email" name="email" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
            <div class="relative">
                <input type="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-fda085" id="password" name="password" required>
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
            <div class="relative">
                <input type="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-fda085" id="confirm_password" name="confirm_password" required>
                <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold p-3 rounded-md hover:bg-blue-500 transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            ลงทะเบียน
        </button>
    </form>

    <p class="mt-3 text-center">มีบัญชีอยู่แล้ว? <a class="footer-link" href="login.php">เข้าสู่ระบบที่นี่</a></p>
</div>

<div id="particles-js"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    AOS.init();

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

    // ฟังก์ชันเปิดปิดรหัสผ่าน
    document.getElementById('togglePassword').onclick = function() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    document.getElementById('toggleConfirmPassword').onclick = function() {
        const confirmPasswordInput = document.getElementById('confirm_password');
        const icon = document.getElementById('toggleConfirmIcon');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            confirmPasswordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };
</script>
</body>
</html>
