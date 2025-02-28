<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตั้งค่าการเชื่อมต่อ MySQL
$servername = "localhost";
$username = "root";  // แก้ไขตามการตั้งค่าของคุณ
$password = "";      // แก้ไขตามการตั้งค่าของคุณ
$dbname = "iot_db";  // ชื่อฐานข้อมูล

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// ดึงข้อมูล 10 รายการล่าสุดจากตาราง sensor_data
$sql = "SELECT id, temperature, humidity, timestamp FROM sensor_data ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// ส่งข้อมูลออกเป็น JSON
echo json_encode($data);

$conn->close();
?>
