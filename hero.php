<?php
// Hero.php
$hero_image = file_get_contents('hero_image.txt');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หาดสะอาดด้วยมือเรา</title>
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif; /* ใช้ฟอนต์ Roboto */
        }

        .hero {
            background-image: url('<?php echo $hero_image; ?>');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            background-attachment: fixed; /* ล็อครูปภาพให้อยู่กับที่ */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* ความมืดของ overlay */
            transition: opacity 0.5s ease; /* การเปลี่ยนแปลงความโปร่งใส */
        }

        .content {
            position: relative;
            z-index: 2; /* ทำให้ข้อความอยู่ด้านบนของรูปภาพ */
            transition: opacity 0.5s ease; /* การเปลี่ยนแปลงความโปร่งใส */
            padding-top: 100px; /* เพิ่มพื้นที่ว่างด้านบน */
        }

        .fade-out {
            opacity: 0; /* ทำให้ความโปร่งใสเป็น 0 */
        }

        h1 {
            font-weight: 700; /* ฟอนต์หนา */
            letter-spacing: 2px; /* เพิ่มช่องว่างระหว่างตัวอักษร */
            color: white; /* สีข้อความ */
        }

        p {
            font-weight: 400; /* ฟอนต์ปกติ */
            line-height: 1.5; /* ระยะห่างระหว่างบรรทัด */
            color: lightgrey; /* สีข้อความ */
        }
    </style>
</head>

<body>
<!-- ส่วน Hero -->
<div class="hero">
    <!-- โฮเวอร์ครอบคลุมพื้นหลัง -->
    <div class="overlay"></div>

    <div class="container text-center position-relative content">
        <h1 class="display-4 font-weight-bold" data-aos="zoom-in-up">ยินดีต้อนรับสู่</h1>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100"> โครงการหาดสะอาดด้วยมือเรา</p>
    </div>
</div>

<!-- JavaScript สำหรับการตรวจจับ scroll -->
<script>
    window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // แสดง overlay และ content แค่ 2 วินาที
    window.onload = function () {
        const overlay = document.querySelector('.overlay');
        const content = document.querySelector('.content');

        // ใช้ setTimeout เพื่อทำให้ fade-out หลังจาก 2 วินาที
        setTimeout(() => {
            overlay.classList.add('fade-out');
            content.classList.add('fade-out');
        }, 2000); // 2000ms = 2 seconds
    };
</script>
</body>

</html>
