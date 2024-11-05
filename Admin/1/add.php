<?php
session_start();
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}
// รวมไฟล์การเชื่อมต่อฐานข้อมูล
include 'db.php'; // ปรับให้ตรงกับพาธที่ถูกต้องของไฟล์ database.php
include '../sidebar-navbar.php';

// สร้างการเชื่อมต่อ
$conn = getConnection(); // เรียกใช้ฟังก์ชัน getConnection()

// Handle form submission
$message = ''; // กำหนดให้ $message ว่างไว้ก่อน
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = addCard($conn);
}

// Query users
$usersResult = $conn->query("SELECT user_id, username FROM users");

function addCard($conn) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file type
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Move uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Collect form data
                $title = $_POST['title'];
                $text = $_POST['text'];
                $user_id = $_POST['user_id'];
                $card_number = $_POST['card_number'];
                $link = $_POST['link'];

                // Create relative path for image
                $relative_path = "uploads/" . basename($_FILES["image"]["name"]);

                // Check if card_number already exists
                $checkSql = "SELECT * FROM cards WHERE card_number = '$card_number'";
                $result = $conn->query($checkSql);
                if ($result->num_rows > 0) {
                    return "Error: This card number already exists. Please select a different number.";
                }

                // Insert card into database
                $sql = "INSERT INTO cards (image, title, text, user_id, card_number, link) VALUES ('$relative_path', '$title', '$text', '$user_id', '$card_number', '$link')";
                return $conn->query($sql) ? "Card added successfully." : "Error: " . $conn->error;
            }
        }
        return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
    return "No file was uploaded.";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Card</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleLinkSelection() {
            var cardNumber = document.getElementById("card_number").value;
            var linkField = document.getElementById("link-field");
            linkField.style.display = (cardNumber >= 4) ? "block" : "none";

            // Reset the link selection when card number changes
            if (cardNumber < 4) {
                document.getElementById("link").value = ""; // Clear the selection
            }
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Add Card</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="text">Text</label>
            <textarea class="form-control" id="text" name="text" required></textarea>
        </div>
        <div class="form-group">
            <label for="user_id">User</label>
            <select class="form-control" id="user_id" name="user_id" required>
                <option value="">เลือกผู้ใช้</option>
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                    <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="card_number">หมายเลขการ์ด (1-6)</label>
            <select class="form-control" id="card_number" name="card_number" required onchange="toggleLinkSelection()">
                <option value="">เลือกหมายเลขการ์ด</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group" id="link-field" style="display: none;">
            <label for="link">ลิงก์</label>
            <select class="form-control" id="link" name="link">
                <option value="">เลือกลิงก์</option>
                <option value="/beach/ข้อมูลหาดทรายน้อย.php">ข้อมูลหาดทรายน้อย</option>
                <option value="/beach/ที่มาโครงการ.php">ที่มาโครงการ</option>
                <option value="/beach/ภาพบรรยากาศโครงการ.php">ภาพบรรยากาศโครงการ</option>
                <option value="/beach/ธุรกิจชุมชน.php">ธุรกิจชุมชน</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Card</button>
        <a href="show_cards.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
