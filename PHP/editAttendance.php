<?php
session_start();
include './db.php'; // Tệp kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendanceID = $_POST['attendanceID'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];

    // Câu lệnh cập nhật
    $sql = "UPDATE attendance 
            SET checkintime = '$checkIn', checkouttime = '$checkOut' 
            WHERE attendanceID = '$attendanceID'";

    // Thực thi câu lệnh
    if (mysql_query($sql, $conn)) {
        // Chuyển hướng về trang editAttendance với thông báo thành công
        header("Location: ../editAttendance.php?update=success");
    } else {
        // Chuyển hướng với thông báo lỗi
        header("Location: ../editAttendance.php?update=error");
    }
}

// Đóng kết nối
mysql_close($conn);
?>
