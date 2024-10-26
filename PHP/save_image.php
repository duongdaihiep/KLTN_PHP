<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ yêu cầu POST
    $data = json_decode(file_get_contents("php://input"), true);

    $employeeID = isset($data['employeeID']) ? $data['employeeID'] : 'unknown';
    $timestamp = isset($data['timestamp']) ? $data['timestamp'] : date('Y-m-d_H-i-s'); // Đặt mặc định nếu không có timestamp
    $imageData = isset($data['image']) ? $data['image'] : '';

    // Giải mã dữ liệu hình ảnh base64
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $image = base64_decode($imageData);

    // Định nghĩa đường dẫn file
    $fileName = 'Images/' . $employeeID . '_' . $timestamp . '.png';

    // Lưu hình ảnh vào thư mục
    if (file_put_contents($fileName, $image) !== false) {
        // Trả về phản hồi thành công
        echo json_encode(['status' => 'success', 'message' => 'Image saved successfully!']);
    } else {
        // Trả về phản hồi thất bại
        echo json_encode(['status' => 'error', 'message' => 'Failed to save image.']);
    }
} else {
    // Nếu không phải là yêu cầu POST
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
