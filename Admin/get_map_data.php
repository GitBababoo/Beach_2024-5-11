<?php
include_once 'db.php';

// สร้างอาเรย์หลักสำหรับเก็บข้อมูลทั้งสองส่วน
$data = [
    'cleanup_activities' => [],
    'businesses' => []
];

// ดึงข้อมูลจากตาราง cleanup_activities พร้อมข้อมูลประเภทขยะ
$queryCleanup = "SELECT ca.activity_id, ca.activity_date, ca.location, ca.description, 
                        ca.total_waste, ca.latitude, ca.longitude, wt.waste_type 
                 FROM cleanup_activities AS ca
                 JOIN waste_types AS wt ON ca.waste_type_id = wt.waste_id";

$resultCleanup = $db->query($queryCleanup);

if ($resultCleanup->num_rows > 0) {
    while ($row = $resultCleanup->fetch_assoc()) {
        // เพิ่มข้อมูลกิจกรรมเก็บขยะลงในอาเรย์ 'cleanup_activities'
        $data['cleanup_activities'][] = [
            'activity_id' => $row['activity_id'],
            'activity_date' => $row['activity_date'],
            'location' => $row['location'],
            'description' => $row['description'],
            'total_waste' => (int)$row['total_waste'], // แปลงเป็นจำนวนเต็ม
            'latitude' => (float)$row['latitude'],     // แปลงเป็นทศนิยม
            'longitude' => (float)$row['longitude'],   // แปลงเป็นทศนิยม
            'waste_type' => $row['waste_type']         // ประเภทของขยะ
        ];
    }
}

// ดึงข้อมูลธุรกิจพร้อมข้อมูลประเภทของธุรกิจ
$queryBusiness = "SELECT b.business_id, b.business_name, b.description, b.contact_info, 
                         b.website, b.latitude, b.longitude, bt.business_type_name 
                  FROM businesses AS b
                  JOIN business_types AS bt ON b.business_type_id = bt.business_type_id";

$resultBusiness = $db->query($queryBusiness);

if ($resultBusiness->num_rows > 0) {
    while ($row = $resultBusiness->fetch_assoc()) {
        // เพิ่มข้อมูลธุรกิจลงในอาเรย์ 'businesses'
        $data['businesses'][] = [
            'business_id' => $row['business_id'],
            'business_name' => $row['business_name'],
            'description' => $row['description'],
            'contact_info' => $row['contact_info'],
            'website' => $row['website'],
            'latitude' => (float)$row['latitude'],     // แปลงเป็นทศนิยม
            'longitude' => (float)$row['longitude'],   // แปลงเป็นทศนิยม
            'business_type_name' => $row['business_type_name'] // ชื่อประเภทของธุรกิจ
        ];
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$db->close();

// ตั้งค่า header เป็น JSON
header('Content-Type: application/json');

// ส่งข้อมูลกลับเป็น JSON
echo json_encode($data);
?>
