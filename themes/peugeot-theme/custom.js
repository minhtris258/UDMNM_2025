document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".peugeot-slider");
    const wrapper = document.querySelector(".peugeot-slider-wrapper");
    const slides = document.querySelectorAll(".peugeot-slide");

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoSlideInterval;
    let startX = 0;
    let isDragging = false;

    // Hàm cập nhật slide
    function updateSlide(index) {
        wrapper.style.transform = `translateX(-${index * 100}%)`;
        currentIndex = index;
    }

    // Hàm chuyển slide tự động
    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(() => {
            let nextIndex = (currentIndex + 1) % totalSlides;
            updateSlide(nextIndex);
        }, 4000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Chuyển slide theo hướng
    function goToPrevSlide() {
        let prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlide(prevIndex);
    }

    function goToNextSlide() {
        let nextIndex = (currentIndex + 1) % totalSlides;
        updateSlide(nextIndex);
    }

    // Xử lý click vùng điều hướng
    document.querySelector(".peugeot-slider-nav-zone.left")?.addEventListener("click", () => {
        goToPrevSlide();
        startAutoSlide();
    });

    document.querySelector(".peugeot-slider-nav-zone.right")?.addEventListener("click", () => {
        goToNextSlide();
        startAutoSlide();
    });

    // Xử lý kéo (swipe)
    wrapper.addEventListener("touchstart", (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
        stopAutoSlide();
    });

    wrapper.addEventListener("touchend", (e) => {
        if (!isDragging) return;
        let endX = e.changedTouches[0].clientX;
        let diff = startX - endX;

        if (diff > 50) {
            goToNextSlide();
        } else if (diff < -50) {
            goToPrevSlide();
        }

        isDragging = false;
        startAutoSlide();
    });

    // Khởi động
    updateSlide(0);
    startAutoSlide();
});
