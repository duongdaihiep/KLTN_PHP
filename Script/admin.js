
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