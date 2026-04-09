<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="style_บรรยากาศ.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .neumorphic {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }
        .neumorphic:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }
        .neumorphic img {
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .neumorphic img:hover {
            transform: scale(1.1);
        }
        .wide-image-section {
            margin-top: 30px;
            text-align: right;
        }
        .wide-image-section img {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            height: auto;
        }
        .wide-image-section h2, .wide-image-section p {
            text-align: right;
        }
        .image-section img {
            max-height: 300px; /* กำหนดความสูงสูงสุดของภาพ */
            object-fit: cover; /* ปรับขนาดภาพให้เหมาะสม */
            border-radius: 10px; /* รัศมีมุม */
        }



    </style>
</head>

<body>
<div id="particles-js"></div>
<?php include_once 'Navigation Bar.php'; ?>

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'essduh_bns_member';

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $user, $pass, $dbname);
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM beach_content";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <br>
    <br>
    <h2 class="text-center mb-4">ข้อมูลหาดทรายน้อย</h2>
    <div class="row justify-content-center"> <!-- ใช้ justify-content-center เพื่อจัดเรียงให้ชิดกลาง -->
        <?php if ($result->num_rows > 0): ?>
            <?php $index = 0; // ตัวแปรนับ index ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-12 mb-4" data-aos="fade-up"> <!-- ใช้ col-md-12 เพื่อให้เต็มแถว -->
                    <div class="wide-image-section d-flex <?php echo $index % 2 === 0 ? 'flex-row' : 'flex-row-reverse'; ?>" style="background-color: #ffffff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                        <div class="text-section flex-grow-1 pe-2"> <!-- ลด padding ขวา -->
                            <h2 class="mb-3" style="font-size: 1.5rem; color: #007bff;"><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="mt-2" style="font-size: 1rem; line-height: 1.5;"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                        <div class="image-section pe-2"> <!-- เพิ่ม padding ขวานิดหน่อย -->
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($row['title']); ?>"> <!-- จำกัดขนาดภาพ -->
                        </div>
                    </div>
                </div>
                <?php $index++; // เพิ่มค่าของ index ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">ไม่มีข้อมูล</p>
        <?php endif; ?>
    </div>
</div>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
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

<?php
$conn->close();
?>
