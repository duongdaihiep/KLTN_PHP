<?php
include './db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendanceID = $_POST['attendanceID'];
    $statusCheckIn = $_POST['statusCheckIn'];
    $statusCheckOut = $_POST['statusCheckOut'];

    // Kiểm tra dữ liệu đầu vào
    if (!empty($attendanceID) && !empty($statusCheckIn) && !empty($statusCheckOut)) {
        // Cập nhật trạng thái vào và ra cho hàng chấm công tương ứng
        $sql = "UPDATE attendance SET 
                statuscheckin = '$statusCheckIn', 
                statuscheckout = '$statusCheckOut' 
                WHERE attendanceID = '$attendanceID'";

        if (mysql_query($sql, $conn)) {
            echo "Cập nhật thành công!";
        } else {
            echo "Lỗi khi cập nhật: " . mysql_error($conn);
        }
    } else {
        echo "Dữ liệu không hợp lệ!";
    }

    mysql_close($conn); // Đóng kết nối
}
?>
