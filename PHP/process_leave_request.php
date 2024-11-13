<?php
// Kết nối đến cơ sở dữ liệu
header('Content-Type: text/html; charset=utf-8');

include('db.php'); // Đảm bảo bạn đã có kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeID = $_POST['employeeID']; // Giả định bạn đã lưu EmployeeID trong session
    $leaveType = $_POST['leaveType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $reason = $_POST['reason'];
    
    // Kiểm tra nếu lý do là rỗng
    if (empty($reason)) {
        // die("Lý do không được để trống!");
        echo "<form id='redirectForm' action='../index.php' method='POST'>
            <input type='hidden' name='status' value='Lý do không được để trống!'>
          </form>
          <script>
            document.getElementById('redirectForm').submit();
          </script>";
    }

    // Thực hiện truy vấn để ghi dữ liệu vào bảng leaveRequests
    $query = "INSERT INTO leaverequests (EmployeeID, StartDate, EndDate, Reason, Status) 
              VALUES ('$employeeID', '$startDate', '$endDate', '$reason', 'Pending')";
    
    if (mysql_query($query)) {
        // echo "<script>alert('Yêu cầu nghỉ phép đã được gửi thành công!')</script>";
        echo "<form id='redirectForm' action='../index.php' method='POST'>
            <input type='hidden' name='status' value='Yêu cầu nghỉ phép đã được gửi thành công!'>
          </form>
          <script>
            document.getElementById('redirectForm').submit();
          </script>";
    
        // Chờ 3 giây trước khi chuyển hướng
        // header("refresh:3;url=../index.php"); 
        exit();
    } else {
        echo "Lỗi: " . mysql_error();
    }
}
?>
