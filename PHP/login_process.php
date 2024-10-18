<?php
// Kết nối đến MySQL
$servername = "localhost";
$username = "root"; // Tên người dùng MySQL
$password = ""; // Mật khẩu MySQL
$dbname = "EmployeeAttendanceSystem"; // Tên cơ sở dữ liệu

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu từ form đăng nhập
$usernameInput = $_POST['username'];
$passwordInput = $_POST['password'];

// Mã hóa mật khẩu nhập vào để so sánh
$passwordHash = md5($passwordInput); // Sử dụng MD5 (hoặc bạn có thể thay bằng hàm mã hóa khác như bcrypt)

// Truy vấn cơ sở dữ liệu để kiểm tra thông tin người dùng
$sql = "SELECT * FROM User WHERE Username = $usernameInput AND PasswordHash = $passwordHash";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usernameInput, $passwordHash);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu thông tin đăng nhập đúng
if ($result->num_rows > 0) {
    // Đăng nhập thành công, chuyển hướng đến trang index.php
    session_start();
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $user['Username'];
    $_SESSION['role'] = $user['Role']; // Gán quyền của người dùng (nếu có)
    header("Location: index.php"); // Chuyển hướng tới trang chính
    exit();
} else {
    // Đăng nhập thất bại, hiển thị thông báo
    echo "<script>alert('Tên đăng nhập hoặc mật khẩu không đúng. Vui lòng thử lại.'); window.location.href='login.php';</script>";
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
