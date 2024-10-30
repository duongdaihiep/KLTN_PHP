<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra phương thức gửi dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy ID yêu cầu nghỉ phép và trạng thái từ form
    $leaveRequestId = $_POST['leaveRequestId'];
    $leaveStatus = $_POST['leaveStatus'];

    // Cập nhật trạng thái yêu cầu nghỉ phép
    $sql = "UPDATE LeaveRequests SET Status = '$leaveStatus' WHERE LeaveRequestID = '$leaveRequestId'";

    // Thực hiện truy vấn
    if (mysql_query($sql, $conn)) {
        echo "<form id='redirectForm' action='../manager.php' method='POST'>
              <input type='hidden' name='status' value='Trạng thái yêu cầu nghỉ phép đã được cập nhật thành công!!'>
                </form>
            <script>
              document.getElementById('redirectForm').submit();
            </script>";

    } else {
        // Nếu có lỗi, hiển thị thông báo lỗi
        echo "<form id='redirectForm' action='../manager.php' method='POST'>
              <input type='hidden' name='status' value='Có lỗi xảy ra khi cập nhật trạng thái yêu cầu nghỉ phép!'>
                </form>
                <script>
                document.getElementById('redirectForm').submit();
                </script>";
    }
}

// Đóng kết nối
mysql_close($conn);
?>
