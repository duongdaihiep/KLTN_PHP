function showSection(sectionId) {
    // Ẩn tất cả các phần nội dung
    document.querySelectorAll('.content-section').forEach(function(section) {
        section.classList.add('d-none');
    });

    // Hiển thị phần được chọn
    document.getElementById(sectionId).classList.remove('d-none');

    // Xóa class 'active' từ tất cả các liên kết nav-item
    document.querySelectorAll('.nav-link').forEach(function(navLink) {
        navLink.classList.remove('active');
    });

    // Thêm class 'active' cho liên kết được chọn
    const activeLink = document.querySelector(`a[onclick="showSection('${sectionId}')"]`);
    activeLink.classList.add('active');
}
