<?php

?>

<!DOCTYPE HTML>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แผนที่กิจกรรมการเก็บขยะ</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.4/leaflet.awesome-markers.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.4/leaflet.awesome-markers.min.js"></script>

    <style>
        html, body { height: 100%; margin: 0; }
        #map { height: 100vh; width: 100%; }
    </style>
</head>
<body>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // สร้างแผนที่
    var map = L.map('map').setView([12.4535899, 99.9814185], 20); // กำหนดพิกัดเริ่มต้น

    // เพิ่มแผนที่จาก OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // กำหนดไอคอนสำหรับกิจกรรมการเก็บขยะ
    var trashIcon = L.icon({
        iconUrl: 'bin.png', // ใส่ URL รูปไอคอนขยะ
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    // กำหนดไอคอนสำหรับธุรกิจ
    var businessIcon = L.icon({
        iconUrl: 'business.png', // ใส่ URL รูปไอคอนธุรกิจ
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    // ฟังก์ชันในการโหลดมาร์คเกอร์จาก get_map_data.php
    function loadMarkers() {
        fetch('get_map_data.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.cleanup_activities.length === 0 && data.businesses.length === 0) {
                    alert('ไม่มีข้อมูลกิจกรรมการเก็บขยะหรือธุรกิจ');
                    return;
                }

                // เพิ่มมาร์คเกอร์สำหรับกิจกรรมการเก็บขยะ
                data.cleanup_activities.forEach(function(activity) {
                    if (activity.latitude && activity.longitude) {
                        var marker = L.marker([activity.latitude, activity.longitude], { icon: trashIcon }).addTo(map);
                        marker.bindPopup(
                            '<strong>กิจกรรมที่: ' + activity.location + '</strong><br>' +
                            'วันที่: ' + activity.activity_date + '<br>' +
                            'รายละเอียด: ' + activity.description + '<br>' +
                            'น้ำหนักขยะ: ' + activity.total_waste + ' กิโลกรัม<br>' +
                            'ประเภทขยะ: ' + activity.waste_type
                        );
                    }
                });

                // เพิ่มมาร์คเกอร์สำหรับธุรกิจ
                data.businesses.forEach(function(business) {
                    if (business.latitude && business.longitude) {
                        var marker = L.marker([business.latitude, business.longitude], { icon: businessIcon }).addTo(map);
                        marker.bindPopup(
                            '<strong>ธุรกิจ: ' + business.business_name + '</strong><br>' +
                            'รายละเอียด: ' + business.description + '<br>' +
                            'ข้อมูลติดต่อ: ' + (business.contact_info || 'ไม่มีข้อมูล') + '<br>' +
                            'เว็บไซต์: ' + (business.website ? '<a href="' + business.website + '" target="_blank">เยี่ยมชม</a>' : 'ไม่มีเว็บไซต์') + '<br>' +
                            'ประเภทธุรกิจ: ' + business.business_type_name
                        );
                    }
                });
            })
            .catch(error => console.error('Error fetching markers:', error));
    }

    // เรียกใช้ฟังก์ชันเพื่อโหลดมาร์คเกอร์
    loadMarkers();
</script>
</body>
</html>
