<?php
// get_environmental_data.php
header('Content-Type: application/json');
require_once 'db.php'; // แฟ้มนี้ควรมีข้อมูลเชื่อมต่อฐานข้อมูล

$sqlCleanup = "
    SELECT activity_date, total_waste 
    FROM cleanup_activities 
    ORDER BY activity_date;
";

$sqlWasteTypes = "
    SELECT wt.waste_type, COUNT(ca.waste_type_id) AS count 
    FROM cleanup_activities AS ca 
    JOIN waste_types AS wt ON ca.waste_type_id = wt.waste_id 
    GROUP BY ca.waste_type_id;
";

$data = [
    "cleanup" => [],
    "waste_types" => []
];

// ดึงข้อมูลจำนวนขยะในแต่ละกิจกรรม
$resultCleanup = $db->query($sqlCleanup);
if ($resultCleanup->num_rows > 0) {
    while ($row = $resultCleanup->fetch_assoc()) {
        $data["cleanup"][] = $row;
    }
}

// ดึงข้อมูลประเภทขยะ
$resultWasteTypes = $db->query($sqlWasteTypes);
if ($resultWasteTypes->num_rows > 0) {
    while ($row = $resultWasteTypes->fetch_assoc()) {
        $data["waste_types"][] = $row;
    }
}

echo json_encode($data);
?>
