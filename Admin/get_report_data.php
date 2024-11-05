<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ESSDUH_BNS_MEMBER';

// เชื่อมต่อฐานข้อมูล
$db = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// ดึงข้อมูลกิจกรรมการเก็บขยะรวมและประเภทขยะที่พบบ่อย
$sql = "SELECT a.activity_date, SUM(a.total_waste) AS total_waste, 
               GROUP_CONCAT(DISTINCT w.waste_type ORDER BY w.waste_type ASC SEPARATOR ', ') AS waste_types
        FROM cleanup_activities AS a
        LEFT JOIN waste_types AS w ON a.waste_type_id = w.waste_id
        GROUP BY a.activity_date
        ORDER BY a.activity_date DESC";

$result = $db->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'activity_date' => $row['activity_date'],
            'total_waste' => (int)$row['total_waste'],
            'waste_types' => $row['waste_types']
        ];
    }
}

// ส่งข้อมูลเป็น JSON
echo json_encode($data);

$db->close();
?>
