document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("img.sensitive-img").forEach(img => {
    // Tạo wrapper
    const wrapper = document.createElement("div");
    wrapper.classList.add("sensitive-wrapper");

    // Nếu ảnh có class aligncenter thì thêm class centered
    if (img.classList.contains("aligncenter")) {
      wrapper.classList.add("centered");
    }

    // Tạo overlay
    const overlay = document.createElement("div");
    overlay.classList.add("sensitive-overlay");
    overlay.textContent = "Ảnh nhạy cảm - Click để hiển thị";

    // Đưa img vào wrapper
    img.parentNode.insertBefore(wrapper, img);
    wrapper.appendChild(img);
    wrapper.appendChild(overlay);

    // Xử lý click
    wrapper.addEventListener("click", () => {
      wrapper.classList.add("revealed");
      overlay.remove();
    });
  });
});
