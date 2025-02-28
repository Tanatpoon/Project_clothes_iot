<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$host = "localhost";
$username = "root"; // แก้ไขให้ตรงกับการตั้งค่าของคุณ
$password = ""; // แก้ไขให้ตรงกับการตั้งค่าของคุณ
$database = "iot_db";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// ดึงข้อมูล rack_id, status และ timestamp
$sql = "SELECT rack_id, status, timestamp FROM Racks";
$result = $conn->query($sql);

$racks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $racks[] = [
            "rack_id" => $row["rack_id"],
            "status" => $row["status"],
            "timestamp" => $row["timestamp"] // เพิ่ม timestamp เข้าไป
        ];
    }
    echo json_encode(["status" => "success", "data" => $racks]);
} else {
    echo json_encode(["status" => "success", "data" => []]);
}

$conn->close();
?>
