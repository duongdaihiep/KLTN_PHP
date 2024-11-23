<?php
// Kết nối cơ sở dữ liệu
// header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu thiếu dữ liệu
    // Kiểm tra nếu thiếu dữ liệu
    $employeeId = isset($_POST['employeeId']) ? $_POST['employeeId'] : null;
    $shiftType = isset($_POST['shiftType']) ? $_POST['shiftType'] : null;
    $shiftTime = isset($_POST['shiftTime']) ? $_POST['shiftTime'] : null;

    if (empty($employeeId)) {
        die("Thiếu mã nhân viên!");
    }
    if (empty($shiftType)) {
        die("Thiếu kiểu xếp ca!");
    }
    if (empty($shiftTime)) {
        die("Thiếu thời gian ca làm!");
    }


    // Lấy thông tin từ POST
    $employeeId = trim($_POST['employeeId']);
    $shiftType = trim($_POST['shiftType']);
    $shiftTime = trim($_POST['shiftTime']);

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

    // Kiểm tra mã nhân viên có tồn tại không
    $checkEmployeeQuery = "SELECT * FROM employees WHERE EmployeeID = '$employeeId'";
    $employeeResult = mysql_query($checkEmployeeQuery, $conn);
    if (!$employeeResult || mysql_num_rows($employeeResult) == 0) {
        die("Nhân viên không tồn tại trong hệ thống!");
    }

    if ($shiftType === 'singleDayShift') {
        // Xếp ca theo ngày
        $shiftDate = isset($_POST['shiftDate']) ? trim($_POST['shiftDate']) : '';
        if (empty($shiftDate)) {
            // die("Ngày làm việc không được để trống!");
            // echo "<form id='redirectForm' action='../manager.php' method='POST'>
            //   <input type='hidden' name='status' value='Ngày làm việc không được để trống!'>
            //     </form>
            //     <script>
            //     document.getElementById('redirectForm').submit();
            //     </script>";
            // header("Location: ../manager.php");
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Ngày làm việc không được để trống!'
            ));
            exit();
        }
        $workDate = trim($_POST['shiftDate']);

        // Kiểm tra xem ca làm việc đã tồn tại cho nhân viên vào ngày cụ thể chưa
        $checkQuery = "SELECT * FROM schedules WHERE EmployeeID = '$employeeId' AND WorkDate = '$workDate'";
        $result = mysql_query($checkQuery, $conn) or die(mysql_error());

        // Lưu kết quả vào một biến và kiểm tra số dòng
        $numRows = mysql_num_rows($result);

        if ($numRows > 0) {
            // Nếu ca làm việc đã tồn tại, cập nhật thời gian bắt đầu và kết thúc
            $updateQuery = "UPDATE schedules 
                            SET StartTime = '$startTime', EndTime = '$endTime' 
                            WHERE EmployeeID = '$employeeId' AND WorkDate = '$workDate'";
            mysql_query($updateQuery, $conn) or die(mysql_error());
            // echo "Cập nhật ca làm thành công cho ngày $workDate.";
            // echo "<form id='redirectForm' action='../manager.php' method='POST'>
            //   <input type='hidden' name='status' value='Cập nhật ca làm thành công cho ngày $workDate.'>
            //     </form>
            //     <script>
            //     document.getElementById('redirectForm').submit();
            //     </script>";
            // header("Location: ../manager.php");
            echo json_encode(array(
                'status' => 'success',
                'message' => "Cập nhật ca làm thành công cho ngày $workDate."
            ));
            exit();
        } else {
            // Nếu ca làm việc chưa tồn tại, thêm mới
            $insertQuery = "INSERT INTO schedules (EmployeeID, WorkDate, StartTime, EndTime) 
                            VALUES ('$employeeId', '$workDate', '$startTime', '$endTime')";
            mysql_query($insertQuery, $conn) or die(mysql_error());
            // echo "Xếp ca thành công cho ngày $workDate.";
            // echo "<form id='redirectForm' action='../manager.php' method='POST'>
            //   <input type='hidden' name='status' value='Xếp ca thành công cho ngày $workDate.'>
            //     </form>
            //     <script>
            //     document.getElementById('redirectForm').submit();
            //     </script>";
            // header("Location: ../manager.php");
            echo json_encode(array(
                'status' => 'success',
                'message' => "Xếp ca thành công cho ngày $workDate."
            ));
            exit();
        }


    } elseif ($shiftType === 'bulkShift') {
        // Xếp ca theo tháng
        $shiftMonth = isset($_POST['shiftMonth']) ? trim($_POST['shiftMonth']) : '';
        if (empty($shiftMonth)) {
            // die("Tháng làm việc không được để trống!");
            // echo "<form id='redirectForm' action='../manager.php' method='POST'>
            //   <input type='hidden' name='status' value='Tháng làm việc không được để trống!'>
            //     </form>
            //     <script>
            //     document.getElementById('redirectForm').submit();
            //     </script>";
            // header("Location: ../manager.php");
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Tháng làm việc không được để trống!'
            ));

            exit();
        }

        $month = trim($_POST['shiftMonth']);
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            // die("Định dạng tháng không hợp lệ!");
            // echo "<form id='redirectForm' action='../manager.php' method='POST'>
            //   <input type='hidden' name='status' value='Định dạng tháng không hợp lệ!'>
            //     </form>
            //     <script>
            //     document.getElementById('redirectForm').submit();
            //     </script>";
            // header("Location: ../manager.php");
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Định dạng tháng không hợp lệ!'
            ));
            exit();
        }

        $startDate = date("Y-m-01", strtotime($month));
        $endDate = date("Y-m-t", strtotime($month));

        $currentDate = $startDate;

        while (strtotime($currentDate) <= strtotime($endDate)) {
            // Kiểm tra ca làm việc đã tồn tại chưa
            $checkQuery = "SELECT * FROM schedules WHERE EmployeeID = '$employeeId' AND WorkDate = '$currentDate'";
            $result = mysql_query($checkQuery, $conn) or die("Lỗi SQL: " . mysql_error());

            if (mysql_num_rows($result) > 0) {
                // Cập nhật nếu đã tồn tại
                $updateQuery = "UPDATE schedules 
                                SET StartTime = '$startTime', EndTime = '$endTime' 
                                WHERE EmployeeID = '$employeeId' AND WorkDate = '$currentDate'";
                mysql_query($updateQuery, $conn) or die("Lỗi SQL: " . mysql_error());
            } else {
                // Thêm mới nếu chưa tồn tại
                $insertQuery = "INSERT INTO schedules (EmployeeID, WorkDate, StartTime, EndTime) 
                                VALUES ('$employeeId', '$currentDate', '$startTime', '$endTime')";
                mysql_query($insertQuery, $conn) or die("Lỗi SQL: " . mysql_error());
            }

            // Chuyển sang ngày tiếp theo
            $currentDate = date("Y-m-d", strtotime($currentDate . ' +1 day'));
        }
        // echo "Xếp ca thành công cho tháng $month.";
        // echo "<form id='redirectForm' action='../manager.php' method='POST'>
        //       <input type='hidden' name='status' value='Xếp ca thành công cho tháng $month.'>
        //         </form>
        //         <script>
        //         document.getElementById('redirectForm').submit();
        //         </script>";
        // header("Location: ../manager.php");
        echo json_encode(array(
            'status' => 'success',
            'message' => "Xếp ca thành công cho tháng $month."
        ));
        exit();

    } else {
        // die("Kiểu xếp ca không hợp lệ!");
        // echo "<form id='redirectForm' action='../manager.php' method='POST'>
        //       <input type='hidden' name='status' value='Kiểu xếp ca không hợp lệ!'>
        //         </form>
        //         <script>
        //         document.getElementById('redirectForm').submit();
        //         </script>";
            // header("Location: ../manager.php");
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Kiểu xếp ca không hợp lệ!'
        ));
        exit();
    }
}
?>
