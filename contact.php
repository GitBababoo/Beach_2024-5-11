<?php
// Hero.php
$hero_image = file_get_contents('ภาพรวม.txt');
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ติดต่อเราที่มหาวิทยาลัยเทคโนโลยีราชมงคลรัตนโกสินทร์ วิทยาเขตวังไกลกังวล">
    <meta name="keywords" content="ติดต่อ, มหาวิทยาลัย, ราชมงคลรัตนโกสินทร์">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="contact.css" rel="stylesheet">

</head>
<body>
<div id="particles-js"></div>
<?php include_once 'Navigation Bar.php'; ?>
<br>
<br>
<div class="container mt-5">
    <h1 class="text-center mb-4">หาดสะอาดด้วยมือเรา</h1>
    <h2 class="text-center mb-4">มหาวิทยาลัยเทคโนโลยีราชมงคลรัตนโกสินทร์ วิทยาเขตวังไกลกังวล</h2>

    <div class="neumorphic">
        <h3 class="text-center mb-4">ติดต่อเรา</h3>
        <div class="mt-4 text-center">
            <p>โครงการนี้จัดทำด้วยนักศึกษาที่: มหาวิทยาลัยเทคโนโลยีราชมงคลรัตนโกสินทร์ วิทยาเขตวังไกลกังวล</p>
            <p>ช่องทางติดตาม:</p>
            <div class="d-flex justify-content-center flex-wrap">
                <a href="https://www.facebook.com/profile.php?id=100082120993129" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> เลิศฤทธิ์ สังข์พรหม
                </a>
                <a href="https://www.facebook.com/profile.php?id=61554643897545" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> วงศธร ฉาบสีทอง
                </a>
                <a href="https://www.facebook.com/profile.php?id=100024989019188" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> น้องเลย์
                </a>
                <a href="https://www.facebook.com/profile.php?id=100014003505482" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> Wisarut Prayoonthai
                </a>
                <a href="https://www.facebook.com/profile.php?id=100035077459980" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> เพชร
                </a>
                <a href="https://www.facebook.com/photo/?fbid=1924543951395196&set=a.123888081460801" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> Pang Pond
                </a>
                <a href="https://www.facebook.com/profile.php?id=100022352567661" target="_blank" class="neumorphic-link">
                    <i class="bi bi-facebook me-2"></i> โค้ด
                </a>
            </div>
        </div>
    </div>

    <div class="group-photo">
        <img src="<?php echo $hero_image; ?>" alt="รูปภาพรวมของกลุ่มคน" />
        <div class="caption">รูปภาพรวมของกลุ่มคนในมหาวิทยาลัย</div>
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
            "number": {
                "value": 80
            },
            "size": {
                "value": 3
            },
            "interactivity": {
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    }
                }
            }
        }
    });
</script>
</body>
</html>
