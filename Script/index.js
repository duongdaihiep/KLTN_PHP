function showSection(sectionId) {
    // Ẩn tất cả các phần nội dung
    document.querySelectorAll('.content-section').forEach(function(section) {
        section.classList.add('d-none');
    });

    // Hiển thị phần được chọn
    document.getElementById(sectionId).classList.remove('d-none');
}
// Hàm hiển thị các phần nội dung
function showSection(sectionId) {
    // Ẩn tất cả các phần nội dung
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.add('d-none'); // Thêm lớp 'd-none' để ẩn
    });

    // Hiển thị phần được chọn
    const selectedSection = document.getElementById(sectionId);
    selectedSection.classList.remove('d-none'); // Xóa lớp 'd-none' để hiển thị

    // Cập nhật trạng thái active cho menu
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        link.classList.remove('active'); // Xóa lớp active
    });
    
    // Thêm lớp active cho liên kết được chọn
    const activeLink = document.querySelector(`.navbar-nav .nav-link[onclick="showSection('${sectionId}')"]`);
    activeLink.classList.add('active');
}

// Bắt đầu hiển thị phần "home" khi trang được tải
document.addEventListener('DOMContentLoaded', () => {
    showSection('home'); // Mặc định hiển thị phần trang chủ
});


// Hàm truy cập camera (ưu tiên camera trước)
function startCamera() {
    const video = document.getElementById('video');

    // Yêu cầu quyền truy cập vào camera trước
    const constraints = {
        video: {
            facingMode: "user" // "user" cho camera trước, "environment" cho camera sau
        }
    };

    // Truy cập camera với điều kiện là camera trước
    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error('Lỗi khi truy cập camera: ', err);
            alert('Không thể truy cập camera.');
        });
}

// Hàm dừng camera
function stopCamera() {
    const video = document.getElementById('video');
    const stream = video.srcObject;
    const tracks = stream.getTracks();

    // Dừng tất cả các track của stream
    tracks.forEach(track => track.stop());
    video.srcObject = null;
}

// Bắt đầu camera khi trang được tải
document.addEventListener('DOMContentLoaded', startCamera);



function chamCong() {
    // Lấy thời gian hiện tại
    const now = new Date();
    const timestamp = now.toLocaleString(); // Định dạng thời gian thành chuỗi

    // Hiển thị thời gian chấm công
    document.getElementById('timestamp').textContent = timestamp;

    // Lấy vị trí hiện tại của thiết bị
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        document.getElementById('location').textContent = "Trình duyệt của bạn không hỗ trợ định vị.";
    }
}

function showPosition(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    const accuracy = position.coords.accuracy;

    // Hiển thị tọa độ GPS
    document.getElementById('location').textContent = `Vĩ độ: ${latitude}, Kinh độ: ${longitude} (Độ chính xác: ${accuracy}m)`;
}

function showError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            document.getElementById('location').textContent = "Người dùng đã từ chối yêu cầu định vị.";
            break;
        case error.POSITION_UNAVAILABLE:
            document.getElementById('location').textContent = "Thông tin vị trí không khả dụng.";
            break;
        case error.TIMEOUT:
            document.getElementById('location').textContent = "Yêu cầu lấy vị trí đã hết thời gian.";
            break;
        case error.UNKNOWN_ERROR:
            document.getElementById('location').textContent = "Lỗi không xác định khi lấy vị trí.";
            break;
    }
}