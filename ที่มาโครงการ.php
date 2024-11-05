
<?php
// ดึงข้อมูลจากไฟล์ JSON
$data = json_decode(file_get_contents('data.json'), true);
$project1 = null;
$project2 = null;

// ตรวจสอบว่าข้อมูลถูกต้องและเป็น array ก่อนที่จะใช้งาน
if (isset($data['projects']) && is_array($data['projects'])) {
    // ค้นหาโปรเจกต์ที่มี id = 1 และ id = 2
    foreach ($data['projects'] as $item) {
        if ($item['id'] == 1) {
            $project1 = $item;
        } elseif ($item['id'] == 2) {
            $project2 = $item;
        }
    }
} else {
    echo "ไม่สามารถโหลดข้อมูลจากไฟล์ JSON ได้";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts (Orbitron) -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Orbitron', sans-serif;
            position: relative;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .neumorphic {
            background: #e0e0e0;
            border-radius: 30px;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            padding: 30px;
            margin: 50px 0;
            transition: transform 0.3s ease;
        }
        .neumorphic:hover {
            transform: translateY(-5px);
        }
        .content {
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .img-responsive {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include_once 'Navigation Bar.php'; ?>



<div id="particles-js"></div>
<br>
<!-- Content Section -->
<div class="container mt-5">
    <div class="neumorphic content d-flex justify-content-center" data-aos="fade-up">
        <h2 class="text-center">ที่มาของโครงงาน</h2>
    </div>
</div>
<br>


<!-- Content Section -->
<div class="container mt-5">
    <div class="neumorphic content d-flex me-auto" data-aos="fade-up" style="width: 50%; margin-right: auto;">
        <h2 class="text-center w-100">ทำไมถึงคิดโครงการนี้?</h2>
    </div>
</div>

<!-- Section with Image on the Right and Text on the Left for ID 1 -->
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6 text-center" data-aos="fade-right">
            <?php if ($project1): ?>
                <img src="<?php echo htmlspecialchars($project1['image']); ?>"
                     alt="<?php echo htmlspecialchars($project1['title']); ?>"
                     class="img-responsive"
                     style="width: 100%; max-width: 400px; border-radius: 10px;">
            <?php else: ?>
                <p>ไม่พบข้อมูลโปรเจกต์</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <?php if ($project1): ?>
                <h3 class="mb-3"><?php echo htmlspecialchars($project1['title']); ?></h3>
                <p><?php echo htmlspecialchars($project1['content']); ?></p>
            <?php else: ?>
                <p>ไม่พบข้อมูลโปรเจกต์</p>
            <?php endif; ?>
        </div>
    </div>
</div>




<!-- Content Section -->
<div class="container mt-5">
    <div class="neumorphic content d-flex ms-auto" data-aos="fade-up" style="width: 50%;">
        <h2 class="text-center w-100">หาดสะอาดด้วยมือเรา</h2>
    </div>
</div>


<!-- Section with Text on the Left and Image on the Right for ID 2 -->
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6" data-aos="fade-right">
            <?php if ($project2): ?>
                <h3 class="mb-3"><?php echo htmlspecialchars($project2['title']); ?></h3>
                <p><?php echo htmlspecialchars($project2['content']); ?></p>
            <?php else: ?>
                <h3 class="mb-3">ไม่พบข้อมูลโปรเจกต์</h3>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-center" data-aos="fade-left">
            <?php if ($project2): ?>
                <img src="<?php echo htmlspecialchars($project2['image']); ?>"
                     alt="<?php echo htmlspecialchars($project2['title']); ?>"
                     class="img-responsive"
                     style="width: 100%; max-width: 400px; border-radius: 10px;">
            <?php else: ?>
                <p>ไม่พบข้อมูลโปรเจกต์</p>
            <?php endif; ?>
        </div>
    </div>
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
