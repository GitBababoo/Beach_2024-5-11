<?php
// db.php
function getConnection() {
    $host = 'localhost';
    $db = 'ESSDUH_BNS_MEMBER';
    $user = 'root';
    $pass = '';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
