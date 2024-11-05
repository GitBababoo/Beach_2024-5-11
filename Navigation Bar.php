<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost:3306', 'root', '', 'essduh_bns_member');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
$userProfile = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $query = "SELECT first_name, last_name, role_id, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userProfile = $result->fetch_assoc();
    $stmt->close();

    // Map role_id to role string
    if ($userProfile) {
        $userProfile['role'] = ($userProfile['role_id'] == 1) ? 'admin' : 'user';
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หาดสะอาดด้วยมือเรา</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Custom CSS */
        .dropdown-toggle::after {
            display: none;
        }
        .colorrole {
            color: rgb(128, 128, 128);
            font-weight: bold;
        }
        .nav-link {
            position: relative;
            margin-left: 30px;
        }
        .navbar-custom {
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .nav-link-custom {
            color: #333;
            transition: color 0.3s ease;
        }
        .nav-link-custom:hover {
            color: #007bff;
        }
        .dropdown-menu-custom {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item-custom {
            transition: background-color 0.3s ease;
        }
        .dropdown-item-custom:hover {
            background-color: #e9ecef;
        }
        /* รีเซ็ตการตั้งค่า marker */
        /* ลบการจัดรูปแบบทั้งหมดจาก marker */
        ::marker {
            content: none !important; /* ไม่แสดงเนื้อหา */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/beach/index.php">หาดสะอาดด้วยมือเรา</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/beach/ข้อมูลหาดทรายน้อย.php">
                        <i class="bi bi-gift"></i>  ข้อมูลหาดทรายน้อย
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/beach/ที่มาโครงการ.php">
                        <i class="bi bi-book"></i> ที่มาโครงการ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/beach/ภาพบรรยากาศโครงการ.php">
                        <i class="bi bi-camera"></i> ภาพบรรยากาศโครงการ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/beach/ธุรกิจชุมชน.php">
                        <i class="bi bi-house"></i> รวมธุรกิจ ชุมชน
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/beach/contact.php">
                        <i class="bi bi-envelope"></i> ติดต่อ
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <?php if ($userProfile): ?>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle nav-link dropdown-user-link" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-nav d-sm-flex d-none me-2 flex-column text-end">
                                    <span class="user-name font-weight-bolder"><?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?></span>
                                    <span class="user-status text-muted">
                                        <?php echo htmlspecialchars($userProfile['role']); ?>
                                        <i class="<?php echo ($userProfile['role'] === 'admin') ? 'bi bi-shield-lock' : 'bi bi-person'; ?>"></i>
                                    </span>
                                </div>
                                <img src="default.png" alt="Profile" class="rounded-circle" width="40" height="40"> <!-- รูปโปรไฟล์ -->
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end text-center dropdown-menu-custom" aria-labelledby="profileDropdown">
                            <li class="colorrole">
                                <i class="<?php echo ($userProfile['role'] === 'admin') ? 'bi bi-shield-lock' : 'bi bi-person'; ?>"></i>
                                <?php echo htmlspecialchars($userProfile['role']); ?>
                            </li>
                            <li class="colorrole">
                                <?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($userProfile['role'] === 'admin'): ?>
                                <li><a class="dropdown-item dropdown-item-custom" href="/beach/Admin/admin_Dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item dropdown-item-custom" href="/beach/profile.php"><i class="bi bi-pencil"></i> โปรไฟล์</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="/beach/logout.php"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/beach/login.php">
                            <i class="bi bi-box-arrow-in-right"></i> ล็อกอิน
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/beach/register.php">
                            <i class="bi bi-pencil"></i> ลงทะเบียน
                        </a>
                    </li>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
