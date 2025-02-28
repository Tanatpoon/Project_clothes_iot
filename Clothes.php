<?php
$servername = "localhost"; // หรือ IP ของเครื่องที่ติดตั้ง MySQL
$username = "root";        // ชื่อผู้ใช้ MySQL
$password = "";            // รหัสผ่าน MySQL
$dbname = "iot_db"; // ชื่อฐานข้อมูล

// เชื่อมต่อกับ MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจาก URL และทำให้แน่ใจว่าเป็น string
$temp = isset($_GET['temp']) ? strval($_GET['temp']) : null;
$humi = isset($_GET['humi']) ? strval($_GET['humi']) : null;
$status = isset($_GET['status']) ? strval($_GET['status']) : null;

// ตรวจสอบค่าที่ได้รับและบันทึกลงฐานข้อมูล
if (!is_null($temp) && !is_null($humi)) {
    $sql = "INSERT INTO sensor_data (temperature, humidity) VALUES ('$temp', '$humi')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Sensor data recorded successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ตรวจสอบว่าค่า $status มีค่าเป็น "OPEN" หรือ "CLOSE" ก่อนบันทึก
if (!is_null($status) && ($status === "open" || $status === "close")) {
    $sql = "INSERT INTO racks (status, timestamp) VALUES ('$status', CURRENT_TIMESTAMP)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Rack status recorded successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid status value received: " . htmlspecialchars($status) . "<br>";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>