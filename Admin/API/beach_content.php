<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // เริ่ม session ถ้ายังไม่ได้เริ่ม
}
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include '../sidebar-navbar.php';
include 'db.php';

$conn = getConnection();

// ฟังก์ชันสำหรับเพิ่มข้อมูล
function addCard($conn) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/"; // ที่อยู่สำหรับเก็บภาพที่อัปโหลด
        $target_file = $target_dir . basename($_FILES["image"]["name"]); // เส้นทางที่เต็ม
        $relative_path = "uploads/" . basename($_FILES["image"]["name"]); // เส้นทางที่สัมพันธ์สำหรับฐานข้อมูล
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบประเภทไฟล์
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // อัปโหลดไฟล์
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $title = $_POST['title'];
                $description = $_POST['description'];

                // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูล โดยใช้ $relative_path สำหรับฐานข้อมูล
                $sql = "INSERT INTO beach_content (title, description, image_path) VALUES ('$title', '$description', '$relative_path')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('บันทึกข้อมูลสำเร็จ!');</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "ขอโทษครับ, เกิดข้อผิดพลาดในการอัปโหลดไฟล์.";
            }
        } else {
            echo "ขอโทษครับ, อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น.";
        }
    }
}

// ฟังก์ชันสำหรับลบข้อมูล
// ฟังก์ชันสำหรับลบข้อมูล
function deleteCard($conn, $id) {
    // ดึงเส้นทางของภาพก่อนลบ
    $sql = "SELECT image_path FROM beach_content WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = $row['image_path'];

        // ลบข้อมูลในฐานข้อมูล
        $sqlDelete = "DELETE FROM beach_content WHERE id=$id";
        if ($conn->query($sqlDelete) === TRUE) {
            // ลบไฟล์ภาพ
            if (file_exists("../../" . $imagePath)) {
                unlink("../../" . $imagePath);
            }
            echo "<script>alert('ลบข้อมูลและไฟล์ภาพสำเร็จ!');</script>";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "ไม่พบข้อมูลสำหรับ ID ที่ระบุ";
    }
}


// เพิ่มข้อมูลเมื่อมีการส่งแบบฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    addCard($conn);
}

// ลบข้อมูลเมื่อมีการส่งคำสั่งลบ
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteCard($conn, $id);
}

// ดึงข้อมูลทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM beach_content";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Beach Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">เพิ่มข้อมูลหาดทรายน้อย</h2>

    <!-- ฟอร์มสำหรับเพิ่มข้อมูล -->
    <form action="beach_content.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">หัวข้อ</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">เนื้อหา</label>
            <textarea class="form-control" name="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">เลือกรูปภาพ</label>
            <input type="file" class="form-control" name="image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
    </form>

    <h2 class="text-center mb-4 mt-5">รายการข้อมูลหาดทรายน้อย</h2>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจว่าต้องการลบข้อมูลนี้?');">ลบ</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">ไม่มีข้อมูล</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
