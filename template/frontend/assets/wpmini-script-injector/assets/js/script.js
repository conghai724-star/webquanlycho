document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('notification-container');
    
    // Kiểm tra xem container có tồn tại không
    if (!container) {
        console.warn('Notification container not found');
        return;
    }
    
    const notificationText = container.querySelector('.notification-text');
    const formContainer = container.querySelector('.form-container');
    
    // Kiểm tra các element con có tồn tại không
    if (!notificationText || !formContainer) {
        console.warn('Required notification elements not found');
        return;
    }
    
    // Lấy cài đặt thời gian từ WordPress Settings
    let initial_delay = 10000;
    let notification_duration = 6000;
    let notification_interval = 10000;
    
    if (typeof wpminiHsinjectorSettings !== 'undefined') {
        initial_delay = wpminiHsinjectorSettings.initial_delay || 10000;
        notification_duration = wpminiHsinjectorSettings.notification_duration || 6000;
        notification_interval = wpminiHsinjectorSettings.notification_interval || 10000;
    }
    
    if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        formContainer.querySelectorAll('svg').forEach(svg => {
            svg.setAttribute('viewBox', '0 0 512 512');
        });
        setTimeout(() => showNotifications(container, notificationText, formContainer, notification_duration, notification_interval), initial_delay); // Hiện Notification đầu tiên sau thời gian cài đặt chỉ trên mobile
    }
});

function showNotifications(container, notificationText, formContainer, notification_duration, notification_interval) {
    let currentIndex = 0;
    
    function showNextNotification() {
        if (currentIndex >= notifications.length) {
            return; // Đã hiển thị hết tất cả notifications
        }
        
        const notification = notifications[currentIndex];
        const isLastNotification = currentIndex === notifications.length - 1;
        
        updateNotification(notification, container, notificationText, formContainer, isLastNotification);
        
        // Chỉ tự động tắt nếu không phải notification cuối cùng
        if (!notification.showForm) {
            setTimeout(() => {
                resetNotification(container, notificationText, formContainer);
                currentIndex++; // Chuyển sang notification tiếp theo
                showNextNotification(); // Hiển thị notification tiếp theo ngay lập tức
            }, notification_duration); // Tắt Notification sau thời gian cài đặt
        } else {
            // Nếu là notification cuối cùng và có form, không tự động chuyển
            currentIndex++;
        }
    }
    
    // Bắt đầu hiển thị notification đầu tiên
    showNextNotification();
}

function updateNotification(notification, container, notificationText, formContainer, isLastNotification) {
    notificationText.innerHTML = ''; // Xóa nội dung cũ
    const link = document.createElement('a');
    link.classList.add('livechat-link');
    link.href = 'javascript:void(0);';
    link.onclick = function() {
        if (typeof openZoosUrl !== 'undefined') {
            openZoosUrl();
            return false;
        }
    };
    link.innerHTML = notification.text;
    notificationText.appendChild(link);

    // Chỉ thêm nút đóng cho notification cuối cùng
    if (isLastNotification) {
        const closeBtn = document.createElement('span');
        closeBtn.className = 'close-btn';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = function() {
            container.remove();
        };
        notificationText.appendChild(closeBtn);
    }

    // Hiển thị form nếu có yêu cầu
    if (notification.showForm) {
        formContainer.style.display = 'flex';
    } else {
        formContainer.style.display = 'none';
    }

    container.style.display = 'block';
}

function resetNotification(container, notificationText, formContainer) {
    notificationText.innerHTML = '';
    container.style.display = 'none';
    formContainer.style.display = 'none';
}
