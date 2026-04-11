// Chỉ hiện 1 Popup trên cùng 1 thời gian
// Hiện lần lượt các popup cho đến khi hiện đến Popup cuối cùng thì
// lại hiện popup đầu tiên.

document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function(event) {
        if (event.target.closest('.close-popup')) {
            const parentPopup = event.target.closest('.popup');
            if (parentPopup) {
                parentPopup.style.display = 'none';
            }
        }
    });

    // Lấy tất cả các popup trên trang
    const popups = document.querySelectorAll('.auto-popup');

    let currentPopup = 0;

    function showPopup() {
        if (popups.length > 0) {

            // Hiển thị popup hiện tại
            popups[currentPopup].style.display = 'block';

            // Thiết lập sự kiện để tắt popup khi nhấp vào nút đóng
            const closeButtons = popups[currentPopup].querySelectorAll('.close-popup');
            let hasClosed = false; // Cờ để kiểm soát việc tăng currentPopup

            closeButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.stopPropagation(); // Ngăn chặn sự kiện lan truyền

                    if (!hasClosed) {
                        
                        popups[currentPopup].style.display = 'none';
                        currentPopup++;

                        // Nếu đã hiển thị đến popup cuối cùng, bắt đầu lại từ popup đầu tiên
                        if (currentPopup >= popups.length) {
                            currentPopup = 0; // Mỗi popup chỉ hiển thị 1 lần thì đóng dòng code này
                        }
                        hasClosed = true; // Đặt cờ để tránh việc tăng currentPopup nhiều lần

                        if(currentPopup < popups.length) {
                            setTimeout(showPopup, 1820000); // 30s
                        }
                    }
                }, { once: true }); // Sự kiện chỉ được thêm một lần duy nhất
            });
        }
    }

    // Khởi đầu bằng việc hiển thị popup đầu tiên sau 3 giây
    setTimeout(showPopup, 40000);
});