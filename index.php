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
                        <a class="nav-link" href="#" onclick="showSection('leave')">Đăng Xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Chấm Công (Home) -->
        <section id="home" class="content-section">
            <div class="container-camera">
                <!-- Khung hình camera -->
                <h3>Camera Chấm Công</h3>
                <div class="camera-container">
                    <video id="video" width="100%" autoplay></video>
                </div>
        
                <!-- Nút chấm công -->
                <button class="btn btn-success btn-cham-cong" onclick="chamCong()">Chấm Công</button>
        
                <!-- Khu vực hiển thị ngày giờ và vị trí -->
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
                            <th scope="col">Giờ ra</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Thêm dữ liệu chấm công ở đây -->
                    </tbody>
                </table>
            </div>
        </section>



        <!-- Xem Bảng Lương -->
        <section id="payroll" class="content-section d-none">
            <h2 class="text-center mt-4">Bảng Lương</h2>
            <table class="table table-bordered payroll-table">
                <thead class="table-success">
                    <tr>
                        <th scope="col">Thông Tin</th>
                        <th scope="col">Giá Trị Thông Tin</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mã Nhân Viên</td>
                        <td>NV001</td>
                    </tr>
                    <tr>
                        <td>Lương Cơ Bản</td>
                        <td>10,000,000 VNĐ</td>
                    </tr>
                    <tr>
                        <td>Thưởng</td>
                        <td>1,000,000 VNĐ</td>
                    </tr>
                    <tr>
                        <td>Đã Ứng</td>
                        <td>500,000 VNĐ</td>
                    </tr>
                    <tr>
                        <td>Còn Lại</td>
                        <td>10,500,000 VNĐ</td>
                    </tr>
                    <tr>
                        <td>Ngày Trả Lương</td>
                        <td>15/10/2024</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Xin Nghỉ Phép -->
        <section id="leave" class="content-section d-none">
            <h2>Xin Nghỉ Phép</h2>
            <form>
                <div class="mb-3">
                    <label for="leaveType" class="form-label">Loại nghỉ phép:</label>
                    <select id="leaveType" name="leaveType" class="form-select">
                        <option value="ngay">Nghỉ ngày</option>
                        <option value="phepnam">Nghỉ phép năm</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="startDate" class="form-label">Ngày bắt đầu:</label>
                    <input type="date" id="startDate" name="startDate" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="endDate" class="form-label">Ngày kết thúc:</label>
                    <input type="date" id="endDate" name="endDate" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Lý do:</label>
                    <textarea id="reason" name="reason" rows="4" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Gửi Yêu Cầu</button>
            </form>
        </section>
    </div>

    <footer class="bg-success text-white text-center py-3 mt-5">
        <p>&copy; 2024 Hệ Thống Chấm Công</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script to handle section display -->
    <script src="./Script/index.js"></script>
</body>
</html>
