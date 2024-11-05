<?php
include_once 'Navigation Bar.php';

include 'db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลธุรกิจพร้อมรูปภาพ โดยกรองเฉพาะที่มี show_image = 1
$sql = "SELECT b.business_id, b.business_name, b.description, b.latitude, b.longitude, 
               GROUP_CONCAT(bp.photo_url SEPARATOR ',') AS photos
        FROM businesses b
        INNER JOIN business_photos bp ON b.business_id = bp.business_id 
        WHERE bp.show_image = 1
        GROUP BY b.business_id";

$result = $conn->query($sql);

if ($result === false) {
    die("Query failed: " . $conn->error);
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
            font-family: 'Orbitron', sans-serif;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .neumorphic {
            background: #e0e0e0;
            border-radius: 20px;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            padding: 20px;
            margin: 30px 0;
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }
        .neumorphic:hover {
            transform: translateY(-5px);
        }
        .business-img {
            width: 200px; /* กำหนดความกว้างของรูปภาพ */
            height: auto;
            border-radius: 10px;
            margin-right: 20px; /* เพิ่มระยะห่างระหว่างรูปภาพกับข้อความ */
        }
        .business {
            margin-bottom: 20px;
        }
        .business-link {
            color: #007bff;
            text-decoration: none;
        }
        .business-link:hover {
            text-decoration: underline;
        }
        .flex-row-reverse .business-img {
            margin-right: 0;
            margin-left: 20px; /* สำหรับกรณีที่รูปภาพอยู่ทางขวา */
        }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div class="container mt-5">

    <br>
    <br>
    <br>
    <br>
    <h1 class="text-center mb-4">ธุรกิจภายในชุมชน</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php $isOdd = true; // ตัวแปรสำหรับตรวจสอบเลขคู่หรือคี่ ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="neumorphic p-4 d-flex <?php echo $isOdd ? '' : 'flex-row-reverse'; ?>">
                <?php
                $photos = explode(',', $row['photos']);
                $firstPhoto = !empty($photos[0]) ? htmlspecialchars($photos[0]) : 'default.jpg'; // ใช้ภาพเริ่มต้นหากไม่มีรูป
                ?>
                <img src="Admin/uploads/<?php echo $firstPhoto; ?>" alt="Business Photo" class="business-img" />
                <div>
                    <h2><?php echo htmlspecialchars($row['business_name']); ?></h2>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>ตำแหน่ง: <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($row['latitude']) . ',' . htmlspecialchars($row['longitude']); ?>" target="_blank" class="business-link">ดูบน Google Maps</a></p>
                </div>
            </div>
            <?php $isOdd = !$isOdd; // สลับค่าตัวแปร ?>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">ไม่มีธุรกิจในชุมชนที่แสดงภาพ</div>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
<!-- Particles JS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
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
</body>
</html>
