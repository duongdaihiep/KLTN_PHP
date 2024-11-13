<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./CSS/dangNhap.css">
</head>
<body>

<div class="login-container">
    <h1>Attendance</h1>
    <h3 class="text-center">Đăng Nhập</h3>
    <form id="loginForm">
        <div class="mb-4">
            <label for="username" class="form-label">Email hoặc Số Điện Thoại</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập email hoặc số điện thoại" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Mật Khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
        </div>
        <button type="submit" class="btn login-btn w-100">Đăng Nhập</button>
    </form>
</div>

<!-- Khu vực hiển thị thông báo lỗi -->
<!-- <div id="errorMessage" class="text-danger mt-3 text-center"></div> -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $("#loginForm").submit(function(e) {
            e.preventDefault(); // Ngăn chặn form submit mặc định

            $.ajax({
                url: './PHP/login_process.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        // Chuyển hướng đến URL được trả về
                        window.location.href = response.redirect;
                    } else {
                        // Sử dụng alert để hiển thị thông báo lỗi
                        alert(response.message);
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            });
        });
    });
</script>

</body>
</html>
