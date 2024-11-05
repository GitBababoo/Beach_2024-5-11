<?php
session_start();
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}
include '../sidebar-navbar.php';


$servername = "localhost"; // เปลี่ยนตามเซิร์ฟเวอร์ของคุณ
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านฐานข้อมูล
$dbname = "essduh_bns_member"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the sorting order from the URL or set default to ascending
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
$nextOrder = $order === 'asc' ? 'desc' : 'asc';

// Fetch card and user data for display with ordering
// เปลี่ยนให้เรียงตาม card_number แทน
$cardsResult = $conn->query("SELECT * FROM cards ORDER BY card_number $order");
$usersResult = $conn->query("SELECT user_id, username FROM users");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Cards</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Show Cards</h2>
    <a href="add.php" class="btn btn-primary mb-3">Add Card</a> <!-- ปุ่ม Add ที่เพิ่มขึ้น -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>
                <a href="?order=<?php echo $nextOrder; ?>" style="text-decoration: none; color: inherit;">
                    Card Number <?php echo $order === 'asc' ? '↑' : '↓'; ?>
                </a>
            </th>
            <th>Title</th>
            <th>Text</th>
            <th>User</th>
            <th>ID</th> <!-- เปลี่ยนให้เป็น ID -->
            <th>Link</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $cardsResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['card_number']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['text']); ?></td>
                <td>
                    <?php
                    // Fetching username based on user_id
                    $userId = $row['user_id'];
                    $userQuery = $conn->query("SELECT username FROM users WHERE user_id = '$userId'");
                    if ($user = $userQuery->fetch_assoc()) {
                        echo htmlspecialchars($user['username']);
                    } else {
                        echo "Unknown User";
                    }
                    ?>
                </td>
                <td><?php echo $row['id']; ?></td> <!-- เพิ่ม ID -->
                <td><?php echo htmlspecialchars($row['link']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                    <form action="del.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
