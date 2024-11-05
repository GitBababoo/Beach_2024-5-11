<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost:3306', 'root', '', 'essduh_bns_member');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการล็อกอิน
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // ใช้รหัสผ่านแบบธรรมดา

    // คิวรีเพื่อดึงข้อมูลผู้ใช้
    $query = "SELECT user_id, username, first_name, last_name, role_id FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind username and plain text password
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // เก็บข้อมูลใน session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = ($user['role_id'] == 1) ? 'admin' : 'user'; // Map role_id to role string

            // เปลี่ยนเส้นทางไปยังหน้า index
            header("Location: index.php");
            exit();
        } else {
            $error = "ไม่พบผู้ใช้ที่ชื่อ $username หรือรหัสผ่านไม่ถูกต้อง";
        }
        $stmt->close();
    } else {
        // Handle query preparation error
        die("Database query preparation failed: " . $conn->error);
    }
}

$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - OTOP</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet">
</head>
<body>
<?php include_once 'Navigation Bar.php'; ?>

<div class="login-container" data-aos="fade-up" data-aos-easing="ease">
    <h2 class="text-center text-2xl font-bold mb-4">เข้าสู่ระบบ</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger bg-red-200 text-red-600 p-2 rounded mb-4">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้</label>
            <input type="text" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400" id="username" name="username" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
            <input type="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400" id="password" name="password" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold p-3 rounded-md hover:bg-blue-500 transition duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            เข้าสู่ระบบ
        </button>
    </form>
    <p class="mt-3 text-center">ยังไม่มีบัญชี? <a class="footer-link" href="register.php">ลงทะเบียนที่นี่</a></p>
    <p class="mt-3 text-center"><a class="footer-link" href="forgot_password.php">ลืมรหัสผ่าน?</a></p>

</div>
<div id="particles-js"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>
    AOS.init();

    // ตรวจสอบว่ามีการส่งข้อความใน URL หรือไม่
    var message = "<?php echo isset($_GET['message']) ? addslashes($_GET['message']) : ''; ?>";
    if (message) {
        alert(message);
    }

    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 120
            },
            "color": {
                "value": "#00ff80"
            },
            "shape": {
                "type": "circle"
            },
            "opacity": {
                "value": 0.5
            },
            "size": {
                "value": 4,
                "random": true
            },
            "line_linked": {
                "enable": true,
                "color": "#00ff80",
                "opacity": 0.4
            },
            "move": {
                "enable": true,
                "speed": 3
            }
        },
        "interactivity": {
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                }
            }
        }
    });
</script>
</body>
</html>
