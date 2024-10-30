<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: dangNhap.php");
    exit();
}

include './PHP/db.php'; // Đường dẫn tới file db.php chứa kết nối tới database
include './PHP/dungChung.php';
checkUserRole('index.php');

// Lấy `EmployeeID` từ bảng `users` dựa trên `Username` trong session
$username = mysql_real_escape_string($_SESSION['username']); // Xử lý đặc biệt ký tự để tránh lỗi SQL injection
$query = "SELECT EmployeeID FROM user WHERE Username = '$username'";
$result = mysql_query($query);

if (!$result) {
    die("Lỗi truy vấn SQL: " . mysql_error()); // Thông báo lỗi SQL nếu có
}

if ($row = mysql_fetch_assoc($result)) {
    $employeeID = $row['EmployeeID'];
} else {
    die("Không tìm thấy EmployeeID.");
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Chấm Công</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <header class="bg-success text-white text-center py-3">
        <h1>Hệ Thống Chấm Công</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showSection('home')">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('attendance')">Theo Dõi Chấm Công</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('payroll')">Xem Bảng Lương</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('leave')">Xin Nghỉ Phép</a>
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
        <section id="home" class="content-section">
            <div class="container-camera">
                <h3>Camera Chấm Công</h3>
                <div class="camera-container">
                    <video id="video" width="100%" autoplay></video>
                    <!-- Nơi hiển thị hình ảnh chấm công -->
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="capturedImage" src="" alt="Captured Image" width="100%">
                    </div>
                </div>

                <!-- Thêm tùy chọn Check In / Check Out -->
                <div class="form-group mt-3">
                    <label>Loại chấm công:</label><br>
                    <div class="radio-container">
                        <input type="radio" id="checkIn" name="checkType" value="Vào" checked>
                        <label for="checkIn" class="radio-label">Vào</label>

                        <input type="radio" id="checkOut" name="checkType" value="Ra">
                        <label for="checkOut" class="radio-label">Ra</label>
                    </div>
                </div>

                <button class="btn btn-success btn-cham-cong mt-3" onclick="chamCong()">Chấm Công</button>

                <div id="info" class="mt-3">
                    <p><strong>Thời gian chấm công:</strong> <span id="timestamp"></span></p>
                    <p><strong>Vị trí:</strong> <span id="location"></span></p>
                </div>
            </div>
        </section>




        <!-- Theo Dõi Chấm Công -->
        <section id="attendance" class="content-section d-none">
            <h2>Theo Dõi Chấm Công</h2>
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Ngày</th>
                        <th scope="col">Giờ vào</th>
                        <th scope="col">Trạng thái giờ vào</th>
                        <th scope="col">Giờ ra</th>
                        <th scope="col">Trạng thái giờ ra</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Truy vấn dữ liệu chấm công dựa trên EmployeeID
                $attendanceQuery = "SELECT Date, CheckInTime, StatusCheckIn, CheckOutTime, StatusCheckOut 
                                    FROM attendance WHERE EmployeeID = '$employeeID'";
                $attendanceResult = mysql_query($attendanceQuery);
                $workingDays = 0;
                while ($row = mysql_fetch_assoc($attendanceResult)) { 
                    $workingDays++;
                    ?>
                    <tr>
                        <td><?php echo $row['Date']; ?></td>
                        <td><?php echo $row['CheckInTime']; ?></td>
                        <td><?php echo $row['StatusCheckIn']; ?></td>
                        <td><?php echo $row['CheckOutTime']; ?></td>
                        <td><?php echo $row['StatusCheckOut']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            </div>
        </section>

        <!-- Xem Bảng Lương -->
        <section id="payroll" class="content-section mt-5">
            <h2 class="text-center mt-4">Bảng Lương Tạm Tính</h2>
            <table class="table table-bordered payroll-table">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Thông Tin</th>
                        <th scope="col">Giá Trị Thông Tin</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Truy vấn dữ liệu bảng lương
                $salaryQuery = "SELECT BasicSalary, Bonus, AdvanceSalary, NetSalary, PayDate 
                                FROM salaries WHERE EmployeeID = '$employeeID'";
                $salaryResult = mysql_query($salaryQuery);
                // Kiểm tra xem truy vấn có thành công hay không
                if (!$salaryResult) {
                    die("Truy vấn bảng lương thất bại: " . mysql_error());
                }

                // Lấy dữ liệu lương
                $salaryData = mysql_fetch_assoc($salaryResult);
                $currentMonth = date('m'); // Lấy tháng
                $currentYear = date('Y');  // Lấy năm
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                $totalSalary = ($salaryData['BasicSalary'] /$daysInMonth) * $workingDays;
                echo $totalSalary;
                

                // Nếu không tìm thấy dữ liệu
                if (!$salaryData) {
                    echo "Không tìm thấy dữ liệu lương cho nhân viên có mã: " . $employeeID;
                }
                ?>

                    <tr>
                        <td>Mã Nhân Viên</td>
                        <td><?php echo $employeeID; ?></td>
                    </tr>
                    <tr>
                        <td>Ngày Công</td>
                        <td><?php echo $workingDays.'/'.$daysInMonth;  ?></td>
                    </tr>
                    <tr>
                        <td>Lương Cơ Bản</td>
                        <td><?php echo number_format($totalSalary, 0, ',', '.') ,"/",number_format($salaryData['BasicSalary'], 0, ',', '.') . ' VNĐ'; ?></td>
                    </tr>
                    <tr>
                        <td>Thưởng</td>
                        <td><?php echo number_format($salaryData['Bonus'], 0, ',', '.') . ' VNĐ'; ?></td>
                    </tr>
                    <tr>
                        <td>Đã Ứng</td>
                        <td><?php echo number_format($salaryData['AdvanceSalary'], 0, ',', '.') . ' VNĐ'; ?></td>
                    </tr>
                    <tr>
                        <td>Còn Lại</td>
                        <td><?php echo number_format($totalSalary+$salaryData['Bonus']-$salaryData['AdvanceSalary'], 0, ',', '.') . ' VNĐ'; ?></td>
                    </tr>
                    <tr>
                        <?php
                        
                        
                        // Tính tháng và năm tiếp theo
                        if ($currentMonth == 12) {
                            $nextMonth = 1; // Tháng 1
                            $nextYear = $currentYear + 1; // Năm tiếp theo
                        } else {
                            $nextMonth = $currentMonth + 1; // Tháng tiếp theo
                            $nextYear = $currentYear; // Năm hiện tại
                        }
                        ?>
                        <td>Ngày Trả Lương</td>
                        <td><?php echo "5/", $nextMonth, "/", $nextYear; ?></td>
                    </tr>

                </tbody>
            </table>
        </section>



        <!-- Xin Nghỉ Phép -->
        <section id="leave" class="content-section d-none">
            <h2>Xin Nghỉ Phép</h2>
            <form id="leaveRequestForm" method="POST" action="./PHP/process_leave_request.php">
                <div class="mb-3">
                    <label for="leaveType" class="form-label">Mã nhân Viên:</label>
                    <input name="employeeID" name="employeeID" class="form-control" required value="<?php echo $employeeID; ?>" readonly>
                </div>    
            
                <div class="mb-3">
                    <label for="leaveType" class="form-label">Loại nghỉ phép:</label>
                    <select id="leaveType" name="leaveType" class="form-select">
                        <option value="ngay">Nghỉ ngày</option>
                        <option value="phepnam">Nghỉ phép năm</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="startDate" class="form-label">Ngày bắt đầu:</label>
                    <input type="date" id="startDate" name="startDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="endDate" class="form-label">Ngày kết thúc:</label>
                    <input type="date" id="endDate" name="endDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Lý do:</label>
                    <textarea id="reason" name="reason" rows="4" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Gửi Yêu Cầu</button>
            </form>
        </section>

        <script>
        // Ràng buộc dữ liệu nhập vào bằng JavaScript
        document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            const today = new Date();

            // Kiểm tra nếu ngày bắt đầu hoặc ngày kết thúc là trong quá khứ
            if (startDate < today || endDate < today) {
                alert("Ngày tháng nhập vào không hợp lệ!");
                e.preventDefault(); // Ngăn không cho gửi form
                return;
            }

            // Kiểm tra nếu ngày kết thúc trước ngày bắt đầu
            if (endDate < startDate) {
                alert("Ngày kết thúc không được phép trước ngày bắt đầu!");
                e.preventDefault(); // Ngăn không cho gửi form
                return;
            }
        });
        </script>

    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
        echo "<script>alert(" . json_encode($_POST['status']) . ");</script>";
        location.reload();
    }
    ?>
    <footer class="bg-success text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./Script/index.js"></script>
</body>
</html>
