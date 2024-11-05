<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // เริ่ม session ถ้ายังไม่ได้เริ่ม
}
// ตรวจสอบสถานะการเข้าสู่ระบบ
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../Login_admin.php");
    exit(); // ออกจากสคริปต์หลังจากเปลี่ยนเส้นทาง
}
include '../sidebar-navbar.php';
// ฟังก์ชันสำหรับการเพิ่มโปรเจกต์ (ไม่เปลี่ยนแปลง)
function addProject($newProject) {
    $data = json_decode(file_get_contents('../../data.json'), true);
    if ($data === null) {
        $data = ["projects" => []];
    }
    $data['projects'][] = $newProject;
    file_put_contents('../../data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ฟังก์ชันสำหรับการแก้ไขโปรเจกต์ (ไม่เปลี่ยนแปลง)
function editProject($id, $updatedProject) {
    $data = json_decode(file_get_contents('../../data.json'), true);
    if ($data === null) {
        $data = ["projects" => []];
    }
    foreach ($data['projects'] as &$project) {
        if ($project['id'] == $id) {
            $project = array_merge($project, $updatedProject);
            break;
        }
    }
    file_put_contents('../../data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ฟังก์ชันสำหรับการแสดงโปรเจกต์ทั้งหมด
function showAllProjects() {
    $data = json_decode(file_get_contents('../../data.json'), true);
    return $data['projects'] ?? [];
}

// ฟังก์ชันสำหรับการหาค่า ID ที่สูงสุด (ไม่เปลี่ยนแปลง)
function getNextProjectId($data) {
    $maxId = 0;
    foreach ($data['projects'] as $project) {
        if ($project['id'] > $maxId) {
            $maxId = $project['id'];
        }
    }
    return $maxId + 1;
}

// ฟังก์ชันสำหรับการลบโปรเจกต์
function deleteProject($id) {
    $data = json_decode(file_get_contents('../../data.json'), true);
    if ($data === null) {
        return;
    }

    foreach ($data['projects'] as $key => $project) {
        if ($project['id'] == $id) {
            unset($data['projects'][$key]);
            break;
        }
    }
    // รีเซ็ต index ของ array
    $data['projects'] = array_values($data['projects']);
    file_put_contents('../../data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// การจัดการอัปโหลดรูปภาพ (ไม่เปลี่ยนแปลง)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], '../../' . $imagePath)) {
            // อัปโหลดสำเร็จ
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    }

    $data = json_decode(file_get_contents('../../data.json'), true);
    $newId = getNextProjectId($data);

    $newProject = [
        "id" => $newId,
        "image" => $imagePath,
        "title" => $_POST['title'],
        "content" => $_POST['content']
    ];
    addProject($newProject);
}

// การจัดการลบโปรเจกต์
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $idToDelete = intval($_POST['id']);
    deleteProject($idToDelete);
}

// แสดงโปรเจกต์ทั้งหมด
$projects = showAllProjects();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            color: #343a40;
        }
        .project-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            background-color: #ffffff;
            transition: transform 0.2s;
        }
        .project-card:hover {
            transform: scale(1.02);
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">จัดการโปรเจกต์</h1>

    <h2>แสดงโปรเจกต์ทั้งหมด</h2>
    <?php foreach ($projects as $project): ?>
        <div class="project-card p-3 mb-4">
            <h3><?php echo $project['title']; ?></h3>
            <p><?php echo $project['content']; ?></p>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                <input type="submit" name="delete" value="ลบ" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโปรเจกต์นี้?');">
            </form>
        </div>
    <?php endforeach; ?>
    <?php if (empty($projects)): ?>
        <p>ไม่พบโปรเจกต์</p>
    <?php endif; ?>

    <h2>เพิ่มโปรเจกต์</h2>
    <div class="form-container mb-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">รูปภาพ:</label>
                <input type="file" id="image" name="image" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="title">ชื่อโปรเจกต์:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="content">รายละเอียด:</label>
                <textarea id="content" name="content" class="form-control" required></textarea>
            </div>
            <input type="submit" name="add" value="เพิ่มโปรเจกต์" class="btn btn-primary">
        </form>
    </div>

</body>
</html>
