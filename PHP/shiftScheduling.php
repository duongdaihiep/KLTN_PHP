<?php
// Kết nối cơ sở dữ liệu
include 'db.php';

// Kiểm tra nếu form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['shiftType'])) {
        die("Thiếu kiểu xếp ca!");
    }

    $employeeId = $_POST['employeeId'];
    $shiftType = $_POST['shiftType']; // singleDayShift hoặc bulkShift
    $shiftTime = $_POST['shiftTime'];

    // Tách giờ bắt đầu và kết thúc từ shiftTime
    switch ($shiftTime) {
        case 'morning':
            $startTime = '07:00:00';
            $endTime = '17:00:00';
            break;
        case 'afternoon':
            $startTime = '14:00:00';
            $endTime = '00:00:00';
            break;
        case 'night':
            $startTime = '22:00:00';
            $endTime = '08:00:00';
            break;
        default:
            die("Ca làm không hợp lệ!");
    }

    if ($shiftType === 'singleDayShift') {
        // Xếp ca theo ngày
        if (!isset($_POST['shiftDate']) || empty($_POST['shiftDate'])) {
            die("Ngày làm việc không được để trống!");
        }
        $workDate = $_POST['shiftDate'];

        // Kiểm tra xem ca làm việc đã tồn tại cho nhân viên vào ngày cụ thể chưa
        $checkQuery = "SELECT * FROM schedules WHERE EmployeeID = '$employeeId' AND WorkDate = '$workDate'";
        $result = mysql_query($checkQuery, $conn) or die(mysql_error());

        if (mysql_num_rows($result) > 0) {
            // Nếu ca làm việc đã tồn tại, cập nhật thời gian bắt đầu và kết thúc
            $updateQuery = "UPDATE schedules 
                            SET StartTime = '$startTime', EndTime = '$endTime' 
                            WHERE EmployeeID = '$employeeId' AND WorkDate = '$workDate'";
            mysql_query($updateQuery, $conn) or die(mysql_error());
            echo "Cập nhật ca làm thành công cho ngày $workDate.";
        } else {
            // Nếu ca làm việc chưa tồn tại, thêm mới
            $query = "INSERT INTO schedules (EmployeeID, WorkDate, StartTime, EndTime) 
                    VALUES ('$employeeId', '$workDate', '$startTime', '$endTime')";
            mysql_query($query, $conn) or die(mysql_error());
            echo "Xếp ca thành công cho ngày $workDate.";
        }
    }elseif ($shiftType === 'bulkShift') {
        // Xếp ca theo tháng
        if (!isset($_POST['shiftMonth']) || empty($_POST['shiftMonth'])) {
            // die("Tháng làm việc không được để trống!");
        }
        $month = $_POST['shiftMonth'];
        $startDate = date("Y-m-01", strtotime($month)); // Ngày đầu tháng
        $endDate = date("Y-m-t", strtotime($month)); // Ngày cuối tháng

        $currentDate = $startDate;

        while (strtotime($currentDate) <= strtotime($endDate)) {
            // echo "<script>alert($endDate)</script>";
            // Kiểm tra xem ca làm việc đã tồn tại cho nhân viên vào ngày cụ thể chưa
            $checkQuery = "SELECT * FROM schedules WHERE EmployeeID = '$employeeId' AND WorkDate = '$currentDate'";
            $result = mysql_query($checkQuery, $conn) or die(mysql_error());

            if (mysql_num_rows($result) > 0) {
                // Nếu ca làm việc đã tồn tại, cập nhật thời gian bắt đầu và kết thúc
                $updateQuery = "UPDATE schedules 
                                SET StartTime = '$startTime', EndTime = '$endTime' 
                                WHERE EmployeeID = '$employeeId' AND WorkDate = '$currentDate'";
                mysql_query($updateQuery, $conn) or die(mysql_error());
            } else {
                // Nếu ca làm việc chưa tồn tại, thêm mới
                $insertQuery = "INSERT INTO schedules (EmployeeID, WorkDate, StartTime, EndTime) 
                                VALUES ('$employeeId', '$currentDate', '$startTime', '$endTime')";
                mysql_query($insertQuery, $conn) or die(mysql_error());
            }

            // Chuyển sang ngày tiếp theo
            $currentDate = date("Y-m-d", strtotime($currentDate . ' +1 day'));
        }
        echo "Xếp ca thành công cho tháng $month.";

    } else {
        die("Kiểu xếp ca không hợp lệ!");
    }
}
?>
