// Hàm hiển thị các phần nội dung
function showSection(sectionId) {
    // Ẩn tất cả các phần nội dung
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.add('d-none');
    });

    // Hiển thị phần được chọn
    const selectedSection = document.getElementById(sectionId);
    selectedSection.classList.remove('d-none');

    // Cập nhật trạng thái active cho menu
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        link.classList.remove('active');
    });

    // Thêm lớp active cho liên kết được chọn
    const activeLink = document.querySelector(`.navbar-nav .nav-link[onclick="showSection('${sectionId}')"]`);
    if (activeLink) activeLink.classList.add('active');

    // Lưu section ID vào localStorage
    localStorage.setItem('currentSection', sectionId);
}

// Khi trang được tải lại, hiển thị phần đã lưu
// document.addEventListener('DOMContentLoaded', () => {
//     const savedSection = localStorage.getItem('currentSection');
//     if (savedSection) {
//         showSection(savedSection);
//     } else {
//         showSection('shiftScheduling'); // Section mặc định
//     }
// });


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

const video = document.getElementById('video');
const imagePreview = document.getElementById('imagePreview');
const capturedImage = document.getElementById('capturedImage');
const timestampElement = document.getElementById('timestamp');
const locationElement = document.getElementById('location');

// Khởi tạo camera
navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream;
    })
    .catch(err => {
        console.error("Error accessing the camera: ", err);
    });

function chamCong() {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Chuyển đổi hình ảnh thành dữ liệu URL
    const dataURL = canvas.toDataURL('image/png');
    
    // Hiển thị hình ảnh chấm công
    capturedImage.src = dataURL;
    imagePreview.style.display = 'block';
    video.style.display = 'none'; // Ẩn camera

    // Định dạng thời gian
    const currentDate = new Date();
    const formattedDate = currentDate.toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false // Nếu bạn muốn dùng 24 giờ
    });

    timestampElement.textContent = formattedDate; // Cập nhật thời gian
    locationElement.textContent = document.getElementById('location').textContent; // Cập nhật vị trí từ showPosition


    // Gửi hình ảnh đến server (PHP hoặc Node.js)
    fetch('PHP/save_image.php', {
        method: 'POST',
        body: JSON.stringify({ 
            employeeID: 'YOUR_EMPLOYEE_ID', // Thay 'YOUR_EMPLOYEE_ID' bằng ID thực tế của nhân viên
            timestamp: formattedDate,
            image: dataURL 
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    
    .then(response => response.json())
    .then(data => {
        console.log('Image uploaded successfully:', data);
    })
    .catch((error) => {
        console.error('Error uploading image:', error);
    });
}


// Hàm hiển thị vị trí hiện tại của người dùng
function showPosition(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    const accuracy = position.coords.accuracy;

    // Hiển thị tọa độ GPS
    document.getElementById('location').textContent = `Vĩ độ: ${latitude}, Kinh độ: ${longitude} (Độ chính xác: ${accuracy}m)`;
}

// Hàm hiển thị lỗi khi không thể lấy được vị trí
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

// Hàm chụp ảnh từ camera
function captureImage() {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Vẽ khung hình hiện tại của video lên canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Lấy dữ liệu hình ảnh dưới dạng base64
    const imageData = canvas.toDataURL('image/png');

    // Hiển thị hình ảnh vừa chụp
    document.getElementById('capturedImage').src = imageData;

    // Dừng camera sau khi chụp
    stopCamera();

    return imageData; // Trả về dữ liệu hình ảnh
}

// Hàm gửi dữ liệu chấm công đến backend
function sendAttendance() {
    const imageData = captureImage(); // Dữ liệu hình ảnh từ hàm chụp ảnh
    const checkType = document.querySelector('input[name="checkType"]:checked').value; // Loại chấm công (Vào/Ra)

    // Gửi dữ liệu hình ảnh và loại chấm công đến server
    fetch('process_attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            image: imageData,
            checkType: checkType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Chấm công thành công!");
            document.getElementById('timestamp').innerText = data.timestamp; // Hiển thị thời gian chấm công
            document.getElementById('location').innerText = data.location; // Hiển thị vị trí
        } else {
            alert("Chấm công thất bại: " + data.message);
        }
    })
    .catch(error => {
        console.error('Lỗi khi chấm công:', error);
    });
}

// Bắt đầu camera khi trang được tải
document.addEventListener('DOMContentLoaded', startCamera);
