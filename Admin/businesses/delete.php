<?php
require 'db.php';

if (isset($_GET['id'])) {
    $business_id = $_GET['id'];

    // ลบธุรกิจ
    $stmt = $pdo->prepare("DELETE FROM businesses WHERE business_id = ?");
    $stmt->execute([$business_id]);

    echo "<script>alert('ลบธุรกิจเรียบร้อยแล้ว'); window.location.href='businesses.php';</script>";
}
?>
