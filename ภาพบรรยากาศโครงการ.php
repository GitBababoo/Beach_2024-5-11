<?php
$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$user = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
$pass = ''; // รหัสผ่านฐานข้อมูล
$dbname = 'essduh_bns_member'; // ชื่อฐานข้อมูล

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$uploadDir = 'Admin/uploads/';
$photos = [];

// ดึงข้อมูลรูปภาพและวันที่กิจกรรมจากฐานข้อมูล
$sql = "SELECT c.photo_url, a.activity_date 
        FROM cleanup_photos c
        JOIN cleanup_activities a ON c.activity_id = a.activity_id 
        WHERE c.show_image = 1"; // ดึงเฉพาะรูปที่แสดง
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // เก็บข้อมูลรูปภาพและวันที่กิจกรรมในอาเรย์
    while ($row = $result->fetch_assoc()) {
        $photos[] = [
            'url' => $row['photo_url'], // เก็บชื่อไฟล์ในอาเรย์
            'date' => $row['activity_date'] // เก็บวันที่กิจกรรม
        ];
    }
} else {
    echo "ไม่พบรูปภาพในฐานข้อมูล";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <style>
        body {
            background-color: #f0f0f0;
        }
        .neumorphic {
            background: #e0e0e0;
            border-radius: 50px;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            padding: 20px;
            margin: 50px 0;
        }
        .photo-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            overflow: hidden;
        }
        .photo-card img {
            width: 50%;
            height: auto;
        }
        .photo-date {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            text-align: left;
            padding-left: 10px;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
<?php include_once 'Navigation Bar.php'; ?>
<div class="container my-5">
    <br>
    <br>
    <br>

    <h1 class="text-center mb-4">รูปภาพกิจกรรม</h1>
    <div class="row">
        <?php foreach ($photos as $photo): ?>
            <div class="col-md-12 mb-4" data-aos="fade-up">
                <div class="photo-card neumorphic">
                    <div class="photo-date">
                        <small class="text-muted"><?= $photo['date'] ?></small> <!-- แสดงวันที่กิจกรรม -->
                    </div>
                    <img src="<?= $uploadDir . $photo['url'] ?>" alt="รูปภาพกิจกรรม" class="img-fluid">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();

</script>

</body>
</html>
