<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: dangNhap.php");
    exit();
}
include './PHP/dungChung.php'; // Đường dẫn đến file chứa hàm checkUserRole
checkUserRole('manager.php');
include './PHP/db.php';

// Xử lý yêu cầu duyệt nghỉ phép
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leaveRequestId']) && isset($_POST['leaveStatus'])) {
    $leaveRequestId = $_POST['leaveRequestId'];
    $leaveStatus = $_POST['leaveStatus'];

    // Cập nhật trạng thái yêu cầu nghỉ phép
    $sql_update = "UPDATE LeaveRequests SET Status = '$leaveStatus' WHERE LeaveRequestID = $leaveRequestId";
    if (mysql_query($sql_update, $conn)) {
        echo "<script>alert('Cập nhật trạng thái yêu cầu thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật trạng thái: " . mysql_error() . "');</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý - Hệ Thống Chấm Công</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/manager.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Quản Lý - Hệ Thống Chấm Công</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showSection('shiftScheduling')">Xếp Ca</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('attendanceApproval')">Duyệt Chấm Công</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('leaveApproval')">Duyệt Nghỉ Phép</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('overtimeApproval')">Duyệt Đăng Ký Tăng Ca</a>
                    </li>
                    <li class="nav-item">
                        <form action="./PHP/logout.php" method="post" style="display:inline;">
                            <button type="submit" class="btn btn-link nav-link" style="border: none; background: none; padding: 0;">
                                Đăng Xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Xếp Ca -->
        <section id="shiftScheduling" class="content-section">
            <div class="mb-3">
                <h2 class="mb-3 text-center">Xếp Ca</h2>
            </div>
            <form id="shiftForm" method="POST" action="../KLTN_20029511/PHP/shiftScheduling.php">
                <div class="mb-3">
                    <label for="shiftOption" class="form-label">Chọn kiểu xếp ca</label>
                    <div class="btn-group" role="group" aria-label="Shift Options">
                        <button type="button" class="btn btn-outline-primary" id="singleDayShift" data-shift="singleDayShift">Xếp Ca Theo Ngày</button>
                        <button type="button" class="btn btn-outline-primary" id="bulkShift" data-shift="bulkShift">Xếp Ca Theo Tháng</button>
                    </div>
                </div>

                <!-- Hiển thị ngày hoặc tháng tùy chọn -->
                <div class="mb-3" id="shiftDateWrapper">
                    <label for="shiftDate" class="form-label">Ngày</label>
                    <input type="date" id="shiftDate" name="shiftDate" class="form-control">
                </div>

                <div class="mb-3" id="shiftMonthWrapper" style="display: none;">
                    <label for="shiftMonth" class="form-label">Chọn Tháng</label>
                    <input type="month" id="shiftMonth" name="shiftMonth" class="form-control">
                </div>

                <!-- Nhập mã nhân viên -->
                <div class="mb-3">
                    <label for="employeeIdShift" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeIdShift" name="employeeIdShift" class="form-control" required>
                </div>

                <!-- Nhập ca làm -->
                <div class="mb-3">
                    <label for="shiftTime" class="form-label">Ca Làm</label>
                    <select id="shiftTime" name="shiftTime" class="form-select" required>
                        <option value="morning">7H-17H</option>
                        <option value="afternoon">14H-0H</option>
                        <option value="night">22H-8H</option>
                    </select>
                </div>

                <!-- Hidden input để lưu kiểu xếp ca -->
                <input type="hidden" id="shiftType" name="shiftType" value="singleDayShift">


                <button type="submit" class="btn btn-primary">Xếp Ca</button>
            </form>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const singleDayShiftBtn = document.getElementById('singleDayShift');
                const bulkShiftBtn = document.getElementById('bulkShift');
                const shiftDateWrapper = document.getElementById('shiftDateWrapper');
                const shiftMonthWrapper = document.getElementById('shiftMonthWrapper');
                const shiftTypeInput = document.getElementById('shiftType');

                singleDayShiftBtn.addEventListener('click', function () {
                    shiftTypeInput.value = 'singleDay';
                    shiftDateWrapper.style.display = 'block';
                    shiftMonthWrapper.style.display = 'none';
                    singleDayShiftBtn.classList.add('btn-primary');
                    singleDayShiftBtn.classList.remove('btn-outline-primary');
                    bulkShiftBtn.classList.remove('btn-primary');
                    bulkShiftBtn.classList.add('btn-outline-primary');
                });

                bulkShiftBtn.addEventListener('click', function () {
                    shiftTypeInput.value = 'bulk';
                    shiftDateWrapper.style.display = 'none';
                    shiftMonthWrapper.style.display = 'block';
                    bulkShiftBtn.classList.add('btn-primary');
                    bulkShiftBtn.classList.remove('btn-outline-primary');
                    singleDayShiftBtn.classList.remove('btn-primary');
                    singleDayShiftBtn.classList.add('btn-outline-primary');
                });
            });
        </script>

        <!-- Duyệt Chấm Công -->
        <section id="attendanceApproval" class="content-section d-none">
            <div class="mb-3">
                <h2 class='mb-3 text-center'>Duyệt Chấm Công</h2>

            </div>
            <!-- Form để tìm kiếm lịch sử chấm công -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="employeeId" class="form-label">Mã Nhân Viên</label>
                    <input type="text" id="employeeId" name="employeeId" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Lịch Sử Chấm Công</button>
            </form>

            <!-- Bảng hiển thị lịch sử chấm công -->
            <table class="table mt-4" id="attendanceTable">
                
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employeeId']) && !empty($_POST['employeeId'])) {
                        $employeeId = $_POST['employeeId'];

                        // Truy vấn lấy lịch sử chấm công của nhân viên
                        $sql = "SELECT attendanceID, EmployeeID, date, checkintime, checkouttime, 
                                statuscheckin, statuscheckout 
                                FROM attendance 
                                WHERE EmployeeID = '$employeeId'";
                        include './PHP/db.php';
                        $result = mysql_query($sql, $conn);

                        echo "<thead>
                                <tr>
                                    <th>Attendance ID</th>
                                    <th>Employee ID</th>
                                    <th>Ngày</th>
                                    <th>Giờ Vào</th>
                                    <th>Trạng Thái Vào</th>
                                    <th>Giờ Ra</th>
                                    <th>Trạng Thái Ra</th>
                                    <th>Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>";

                        // Kiểm tra và hiển thị dữ liệu
                        if ($result && mysql_num_rows($result) > 0) {
                            while ($row = mysql_fetch_assoc($result)) {
                                echo "<tr data-attendance-id='{$row['attendanceID']}'>
                                        <td><span class='attendanceID'>{$row['attendanceID']}</span></td>
                                        <td><span class='employeeID'>{$row['EmployeeID']}</span></td>
                                        <td><span class='date'>{$row['date']}</span></td>
                                        <td><span class='checkIn'>{$row['checkintime']}</span></td>
                                        
                                        <!-- Dropdown cho trạng thái vào -->
                                        <td>
                                            <span class='statusCheckIn'>{$row['statuscheckin']}</span>
                                            <div class='dropdown d-inline'>
                                                <button class='btn btn-secondary dropdown-toggle' type='button' id='statusCheckInDropdown' data-bs-toggle='dropdown' aria-expanded='false'>
                                                    ▼
                                                </button>
                                                <ul class='dropdown-menu' aria-labelledby='statusCheckInDropdown'>
                                                    <li><a class='dropdown-item' href='#' data-value='late'>Late</a></li>
                                                    <li><a class='dropdown-item' href='#' data-value='approved'>Approved</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
                                        <td><span class='checkOut'>{$row['checkouttime']}</span></td>
                                        
                                        <!-- Dropdown cho trạng thái ra -->
                                        <td>
                                            <span class='statusCheckOut'>{$row['statuscheckout']}</span>
                                            <div class='dropdown d-inline'>
                                                <button class='btn btn-secondary dropdown-toggle' type='button' id='statusCheckInDropdown' data-bs-toggle='dropdown' aria-expanded='false'>
                                                    ▼
                                                </button>
                                                <ul class='dropdown-menu' aria-labelledby='statusCheckInDropdown'>
                                                    <li><a class='dropdown-item' href='#' data-value='soon'>Soon</a></li>
                                                    <li><a class='dropdown-item' href='#' data-value='approved'>Approved</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <button class='btn btn-success saveBtn'>Lưu</button>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Không có dữ liệu cho nhân viên này.</td></tr>";
                        }
                        // mysql_close($conn);
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Duyệt Nghỉ Phép -->
        <section id="leaveApproval" class="content-section d-none">
            <div class="mb-3">
                <h3 class='mb-3 text-center'>Danh Sách Yêu Cầu Nghỉ Phép</h3>

            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã Yêu Cầu</th>
                        <th>Mã Nhân Viên</th>
                        <th>Ngày Bắt Đầu</th>
                        <th>Ngày Kết Thúc</th>
                        <th>Lý Do</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = mysql_connect($servername, $username, $password);
                    $sql = "SELECT * FROM LeaveRequests WHERE `status` = 'Pending' ORDER BY LeaveRequestID DESC";
                    $result = mysql_query($sql, $conn);  // Sử dụng mysql_query()
                    echo "";
                    // Kiểm tra nếu có dữ liệu trả về
                    if (mysql_num_rows($result) > 0) {
                        while ($row = mysql_fetch_assoc($result)) {
                            // Hiển thị dữ liệu yêu cầu nghỉ phép
                            echo "<tr>";
                            echo "<td>" . $row['LeaveRequestID'] . "</td>";
                            echo "<td>" . $row['EmployeeID'] . "</td>";
                            echo "<td>" . $row['StartDate'] . "</td>";
                            echo "<td>" . $row['EndDate'] . "</td>";
                            echo "<td>" . $row['Reason'] . "</td>";
                            echo "<td>" . $row['Status'] . "</td>";
                            echo 
                                "<td>
                                    <form method='POST' action='./PHP/leaveApproval.php' style='display:inline;'>
                                        <input type='hidden' name='leaveRequestId' value='" . $row['LeaveRequestID'] . "'>
                                        <input type='hidden' name='leaveStatus' value='approved'>
                                        <button type='submit' class='btn btn-success'>Duyệt</button>
                                    </form>
                                    <form method='POST' action='./PHP/leaveApproval.php' style='display:inline;'>
                                        <input type='hidden' name='leaveRequestId' value='" . $row['LeaveRequestID'] . "'>
                                        <input type='hidden' name='leaveStatus' value='Refuse'>
                                        <button type='submit' class='btn btn-danger'>Từ chối</button>
                                    </form>
                                </td>"  ;
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không có yêu cầu nào.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Duyệt Đăng Ký Tăng Ca -->
        <section id="overtimeApproval" class="content-section d-none">
            <div class="mb-3">
                <h2 class='mb-3 text-center'>Duyệt Đăng Ký Tăng Ca</h2>

            </div>
            <form>
                <div class="mb-3">
                    <label for="overtimeRequestId" class="form-label">Mã Đăng Ký Tăng Ca</label>
                    <input type="text" id="overtimeRequestId" name="overtimeRequestId" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="overtimeStatus" class="form-label">Trạng Thái</label>
                    <select id="overtimeStatus" name="overtimeStatus" class="form-select">
                        <option value="approved">Duyệt</option>
                        <option value="rejected">Từ Chối</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </form>
        </section>

        <!-- Đăng Xuất -->
        <section id="logout" class="content-section d-none">
            <h2>Đăng Xuất</h2>
            <p>Bạn có chắc chắn muốn đăng xuất?</p>
            <button class="btn btn-warning">Đăng Xuất</button>
        </section>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
        echo "<script>alert(" . json_encode($_POST['status']) . ");</script>";
    }
    ?>
    <footer class="bg-primary text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin Script -->
    <script src="./Script/manager.js"></script>
    <!-- <script src="./Script/admin.js"></script> -->

</body>
</html>
