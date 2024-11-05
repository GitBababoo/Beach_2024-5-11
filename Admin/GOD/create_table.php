<?php
$host = 'localhost';
$dbname = 'essduh_bns_member';
$username = 'root';
$password = '';

try {
    // เชื่อมต่อฐานข้อมูล
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // คำสั่ง SQL สำหรับลบตารางที่มีอยู่ก่อน (เรียงตามความสัมพันธ์ของ Foreign Key)
    $dropTables = "
        SET FOREIGN_KEY_CHECKS = 0;
        DROP TABLE IF EXISTS business_photos, businesses, business_types, cleanup_photos, 
        cleanup_activities, waste_types, users, role_permissions, permissions, roles;
        SET FOREIGN_KEY_CHECKS = 1;
    ";
    $pdo->exec($dropTables);

    // คำสั่ง SQL สำหรับสร้างตารางทั้งหมด
    $createTables = "
    CREATE TABLE IF NOT EXISTS roles (
        role_id INT AUTO_INCREMENT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL UNIQUE
    );

    CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        role_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        reset_token VARCHAR(255) NULL,
        reset_token_expires INT NULL,
        FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE SET NULL
    );

CREATE TABLE IF NOT EXISTS activity_waste_types (
    activity_id INT NOT NULL,             -- รหัสกิจกรรม (Foreign Key)
    waste_type_id INT NOT NULL,           -- รหัสประเภทขยะ (Foreign Key)
    PRIMARY KEY (activity_id, waste_type_id),
    FOREIGN KEY (activity_id) REFERENCES cleanup_activities(activity_id) ON DELETE CASCADE,
    FOREIGN KEY (waste_type_id) REFERENCES waste_types(waste_id) ON DELETE CASCADE
);


    CREATE TABLE IF NOT EXISTS waste_types (
        waste_id INT PRIMARY KEY AUTO_INCREMENT,
        waste_type VARCHAR(50) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS cleanup_activities (
        activity_id INT PRIMARY KEY AUTO_INCREMENT,
        activity_date DATE NOT NULL,
        location VARCHAR(50) NOT NULL,
        description VARCHAR(255),
        total_waste INT NOT NULL,
        latitude DECIMAL(10, 9) NOT NULL,
        longitude DECIMAL(11, 9) NOT NULL,
        waste_type_id INT,
        user_id INT,
        FOREIGN KEY (waste_type_id) REFERENCES waste_types(waste_id) ON DELETE SET NULL,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
    );

    CREATE TABLE IF NOT EXISTS cleanup_photos (
        photo_id INT PRIMARY KEY AUTO_INCREMENT,
        activity_id INT NOT NULL,
        photo_url VARCHAR(255) NOT NULL,
        description VARCHAR(255),
        FOREIGN KEY (activity_id) REFERENCES cleanup_activities(activity_id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS business_types (
        business_type_id INT PRIMARY KEY AUTO_INCREMENT,
        business_type_name VARCHAR(50) NOT NULL UNIQUE,
        description VARCHAR(100)
    );

    CREATE TABLE IF NOT EXISTS businesses (
        business_id INT PRIMARY KEY AUTO_INCREMENT,
        business_name VARCHAR(100) NOT NULL,
        business_type_id INT,
        user_id INT,
        description VARCHAR(255),
        contact_info VARCHAR(50),
        website VARCHAR(100) UNIQUE,
        latitude DECIMAL(10, 9) NOT NULL,
        longitude DECIMAL(11, 9) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (business_type_id) REFERENCES business_types(business_type_id) ON DELETE SET NULL,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
    );

    CREATE TABLE IF NOT EXISTS business_photos (
        photo_id INT PRIMARY KEY AUTO_INCREMENT,
        business_id INT NOT NULL,
        photo_url VARCHAR(255) NOT NULL UNIQUE,
        description VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (business_id) REFERENCES businesses(business_id) ON DELETE CASCADE
    );
    ";

    // รันคำสั่ง SQL
    $pdo->exec($createTables);
    echo "Tables created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// ปิดการเชื่อมต่อ
$pdo = null;
?>
