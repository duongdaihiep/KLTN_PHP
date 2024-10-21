<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data === null) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        exit;
    }

    // Lấy các giá trị
    $attendanceID = $data['attendanceID'];
    $date = $data['date'];
    $checkIn = $data['checkIn'];
    $checkOut = $data['checkOut'];
    $statusCheckIn = $data['statusCheckIn'];
    $statusCheckOut = $data['statusCheckOut'];

    // Kết nối đến cơ sở dữ liệu
    include './db.php'; // Đảm bảo db.php kết nối đúng

    // Cập nhật dữ liệu trong cơ sở dữ liệu
    $stmt = $conn->prepare("UPDATE attendance SET date=?, checkintime=?, checkouttime=?, statuscheckin=?, statuscheckout=? WHERE AttendanceID=?");
    $stmt->bind_param("sssssi", $date, $checkIn, $checkOut, $statusCheckIn, $statusCheckOut, $attendanceID); // Chú ý kiểu dữ liệu

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
