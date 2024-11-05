<?php
session_start();
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}
// Include database connection
include 'db.php'; // Ensure you have the correct filename for your DB connection
include '../sidebar-navbar.php';

$conn = getConnection();

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $message = updateCard($conn);
}

// Fetch the card to edit
$card = null;
if (isset($_GET['id'])) {
    $cardId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->bind_param("i", $cardId);
    $stmt->execute();
    $result = $stmt->get_result();
    $card = $result->fetch_assoc();
    $stmt->close();
}

function updateCard($conn) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $text = $_POST['text'];
    $user_id = intval($_POST['user_id']);
    $card_number = intval($_POST['card_number']);
    $link = $_POST['link'];
    $existing_image = $_POST['existing_image'];

    // Check for image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file type
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Move uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update image path
                $relative_path = "uploads/" . basename($_FILES["image"]["name"]);
            } else {
                return "Error uploading image.";
            }
        } else {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        // Keep the existing image if no new file is uploaded
        $relative_path = $existing_image;
    }

    // Update card in database
    $stmt = $conn->prepare("UPDATE cards SET title = ?, text = ?, user_id = ?, card_number = ?, link = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssiiisi", $title, $text, $user_id, $card_number, $link, $relative_path, $id);
    $result = $stmt->execute();
    $stmt->close();

    return $result ? "Card updated successfully." : "Error: " . $conn->error;
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Card</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Card</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($card): ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $card['id']; ?>">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($card['image']); ?>">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($card['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="text">Text</label>
                <textarea class="form-control" id="text" name="text" required><?php echo htmlspecialchars($card['text']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="user_id">User</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">Select User</option>
                    <?php
                    // Fetch users for selection
                    $usersResult = $conn->query("SELECT user_id, username FROM users");
                    while ($user = $usersResult->fetch_assoc()): ?>
                        <option value="<?php echo $user['user_id']; ?>" <?php echo $user['user_id'] == $card['user_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['username']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="card_number">Card Number (1-6)</label>
                <select class="form-control" id="card_number" name="card_number" required>
                    <option value="">Select Card Number</option>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == $card['card_number'] ? 'selected' : ''; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="link">Link</label>
                <select class="form-control" id="link" name="link">
                    <option value="">Select Link</option>
                    <option value="/beach/ข้อมูลหาดทรายน้อย.php" <?php echo $card['link'] == '/beach/ข้อมูลหาดทรายน้อย.php' ? 'selected' : ''; ?>>ข้อมูลหาดทรายน้อย</option>
                    <option value="/beach/ที่มาโครงการ.php" <?php echo $card['link'] == '/beach/ที่มาโครงการ.php' ? 'selected' : ''; ?>>ที่มาโครงการ</option>
                    <option value="/beach/ภาพบรรยากาศโครงการ.php" <?php echo $card['link'] == '/beach/ภาพบรรยากาศโครงการ.php' ? 'selected' : ''; ?>>ภาพบรรยากาศโครงการ</option>
                    <option value="/beach/ธุรกิจชุมชน.php" <?php echo $card['link'] == '/beach/ธุรกิจชุมชน.php' ? 'selected' : ''; ?>>ธุรกิจชุมชน</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Upload New Image (optional)</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                <small>Current image: <img src="<?php echo htmlspecialchars($card['image']); ?>" alt="Current Image" style="max-width: 150px;"></small>
            </div>

            <button type="submit" class="btn btn-primary">Update Card</button>
            <a href="show_cards.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Card not found.</div>
    <?php endif; ?>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
