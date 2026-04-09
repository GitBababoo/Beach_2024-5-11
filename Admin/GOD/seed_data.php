<?php
$host = 'localhost';
$dbname = 'essduh_bns_member';
$username = 'root';
$password = '';

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ฟังก์ชันสำหรับตรวจสอบว่าข้อมูลมีอยู่แล้วหรือไม่
    function recordExists($pdo, $table, $column, $value) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetchColumn() > 0;
    }

    // ข้อมูลตัวอย่างสำหรับตาราง roles
    $roles = [
        ['role_name' => 'admin'],
        ['role_name' => 'user']
    ];

    // เพิ่มข้อมูล roles
    $stmt = $pdo->prepare("INSERT INTO roles (role_name) VALUES (:role_name)");
    foreach ($roles as $role) {
        if (!recordExists($pdo, 'roles', 'role_name', $role['role_name'])) {
            $stmt->execute(['role_name' => $role['role_name']]);
        }
    }


    // ข้อมูลตัวอย่างสำหรับตาราง users
    $users = [
        ['username' => 'admin', 'email' => 'admin@example.com', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'role_id' => 1],
        ['username' => 'user', 'email' => 'user@example.com', 'password' => password_hash('user123', PASSWORD_DEFAULT), 'role_id' => 2]
    ];

    // เพิ่มข้อมูล users
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)");
    foreach ($users as $user) {
        if (!recordExists($pdo, 'users', 'username', $user['username'])) {
            $stmt->execute([
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'role_id' => $user['role_id']
            ]);
        }
    }

    echo "Seed data inserted successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// ปิดการเชื่อมต่อ
$pdo = null;
?>
