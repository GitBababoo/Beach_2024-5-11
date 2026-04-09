<div class="container mt-5">
    <div class="row">
        <?php
        // เชื่อมต่อฐานข้อมูล
        $host = 'localhost'; // เปลี่ยนตามเซิร์ฟเวอร์ของคุณ
        $dbname = 'ESSDUH_BNS_MEMBER'; // เปลี่ยนชื่อฐานข้อมูลของคุณ
        $username = 'root'; // ชื่อผู้ใช้ของคุณ
        $password = ''; // รหัสผ่านของคุณ

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</div>";
        }

        // ดึงข้อมูลการ์ดทั้งหมดและเรียงตาม card_number
        $sql = "SELECT * FROM cards WHERE card_number BETWEEN 1 AND 6 ORDER BY card_number ASC"; // ดึงการ์ดที่หมายเลข 1-6 และเรียงตามหมายเลข
        $stmt = $pdo->query($sql);
        $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // แสดงการ์ด
        foreach ($cards as $card) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded">
                    <img src="<?= htmlspecialchars($card['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($card['title']) ?>" style="height: 500px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($card['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($card['text']) ?></p>
                        <?php if (in_array($card['card_number'], [4, 5, 6])): ?>
                            <a href="<?= htmlspecialchars($card['link']) ?>" class="btn btn-primary">ไปหน้า</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
