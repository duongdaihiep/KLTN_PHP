<?php
// Kết nối với cơ sở dữ liệu
include './PHP/db.php';
// Lấy dữ liệu từ yêu cầu POST
$employeeId = $_POST['employeeId'];
$shiftTime = $_POST['shiftTime'];
$shiftOption = $_POST['shiftOption'];

if ($shiftOption == 'singleDay') {
    $workDate = $_POST['shiftDate'];  // Dữ liệu ngày
    $workMonth = null; // Không cần tháng
} elseif ($shiftOption == 'bulk') {
    $workDate = null;  // Không cần ngày
    $workMonth = $_POST['shiftMonth'];  // Dữ liệu tháng
}

// Đảm bảo dữ liệu đã được gửi đầy đủ
if (empty($employeeId) || empty($shiftTime)) {
    die("Thiếu thông tin nhân viên hoặc ca làm.");
}

// Thêm dữ liệu vào bảng schedules
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu từ yêu cầu POST
$employeeId = $_POST['employeeId'];
$shiftTime = $_POST['shiftTime'];
$shiftOption = $_POST['shiftOption'];

if ($shiftOption == 'singleDay') {
    $workDate = $_POST['shiftDate'];  // Dữ liệu ngày
    $workMonth = null; // Không cần tháng
} elseif ($shiftOption == 'bulk') {
    $workDate = null;  // Không cần ngày
    $workMonth = $_POST['shiftMonth'];  // Dữ liệu tháng
}

// Đảm bảo dữ liệu đã được gửi đầy đủ
if (empty($employeeId) || empty($shiftTime)) {
    die("Thiếu thông tin nhân viên hoặc ca làm.");
}

// Khai báo biến thông báo
$statusMessage = '';

if ($workDate) {
    // Xử lý xếp ca theo ngày
    $sql = "INSERT INTO schedules (EmployeeID, WorkDate, StartTime, EndTime) VALUES ('$employeeId', '$workDate', '$shiftTime', '$shiftTime')";
    if ($conn->query($sql) === TRUE) {
        $statusMessage = 'Xếp ca theo ngày thành công!';
    } else {
        $statusMessage = 'Có lỗi xảy ra khi xếp ca theo ngày: ' . $conn->error;
    }
} elseif ($workMonth) {
    // Xử lý xếp ca theo tháng
    $startDate = "$workMonth-01"; // Tạo ngày bắt đầu của tháng
    $endDate = date("Y-m-t", strtotime($startDate)); // Lấy ngày cuối cùng của tháng

    // Lặp qua các ngày trong tháng và lưu vào bảng
    $currentDate = $startDate;
    $allSuccess = true;
    while (strtotime($currentDate) <= strtotime($endDate)) {
        $sql = "INSERT INTO schedules (EmployeeID, WorkDate, StartTime, EndTime) VALUES ('$employeeId', '$currentDate', '$shiftTime', '$shiftTime')";
        if ($conn->query($sql) !== TRUE) {
            $allSuccess = false;
            break;
        }
        $currentDate = date("Y-m-d", strtotime($currentDate . " +1 day"));
    }

    if ($allSuccess) {
        $statusMessage = 'Xếp ca đồng loạt trong tháng thành công!';
    } else {
        $statusMessage = 'Có lỗi xảy ra khi xếp ca theo tháng: ' . $conn->error;
    }
}

$conn->close();

// Gửi thông báo trở lại trang quản lý (admin.php)
header('Content-Type: text/html; charset=utf-8');
echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../admin.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = \"$statusMessage\";
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
      </script>";
exit;
?>
