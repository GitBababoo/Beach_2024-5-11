<?php
session_start(); // เริ่มต้นเซสชัน
if (!isset($_SESSION['user_id'])) {

}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรเจคหาดสะอาดด้วยมือเรา</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #f0f0f0;
        }
        .hero {
            position: relative;
            height: 400px;
            background-image: url('beach.jpg'); /* เปลี่ยนเป็น URL ภาพที่ต้องการ */
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .hero h1 {
            font-size: 48px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .footer {
            background: rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            color: white;
        }
    </style>
</head>
<body>

<header class="text-center mb-4">
    <?php include_once 'Navigation Bar.php'; ?>
    <?php include_once 'hero.php'; ?>

</header>

<?php include_once 'cards.php'; ?>



<footer class="footer mt-5">
    <p>สงวนสิทธิ์ &copy; 2024 - All rights reserved</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>

</body>
</html>
