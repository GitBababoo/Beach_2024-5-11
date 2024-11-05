<?php
$host = 'localhost';
$db = 'ESSDUH_BNS_MEMBER';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันเพื่อดึงข้อมูลผู้ใช้ตามคำค้นหา
function getUsers($conn, $search) {
    $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    return $stmt->get_result();
}

$search = isset($_POST['search']) ? $_POST['search'] : '';
$users = getUsers($conn, $search);
$index = 1; // ตัวแปรนับลำดับ

while ($user = $users->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$index}</td>"; // แสดงลำดับที่
    echo "<td>{$user['username']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td>{$user['first_name']}</td>";
    echo "<td>{$user['last_name']}</td>";
    echo "<td>{$user['role_id']}</td>";
    echo "<td>
            <form method='POST' style='display:inline;'>
                <input type='hidden' name='user_id' value='{$user['user_id']}'>
                <button type='submit' name='delete_user' class='btn btn-danger' onclick=\"return confirm('คุณแน่ใจว่าต้องการลบผู้ใช้คนนี้?');\">ลบ</button>
            </form>
            <button class='btn btn-warning' onclick='editUser({$user['user_id']})'>แก้ไข</button>
          </td>";
    echo "</tr>";
    $index++; // เพิ่มค่า index ทีหลัง
}

$conn->close();
?>
