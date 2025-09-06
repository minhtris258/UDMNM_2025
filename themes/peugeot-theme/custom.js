document.addEventListener("DOMContentLoaded", function () {
    // Xử lý tất cả slider kiểu wrapper (ví dụ content1)
    document.querySelectorAll(".peugeot-slider-wrapper").forEach(function (wrapper) {
        const slider = wrapper.closest(".peugeot-slider");
        const slides = wrapper.querySelectorAll(".peugeot-slide");

        let currentIndex = 0;
        const totalSlides = slides.length;
        let autoSlideInterval;
        let startX = 0;
        let isDragging = false;

        function updateSlide(index) {
            wrapper.style.transform = `translateX(-${index * 100}%)`;
            currentIndex = index;
        }

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

        // Click nav zone
        slider.querySelector(".peugeot-slider-nav-zone.left")?.addEventListener("click", () => {
            updateSlide((currentIndex - 1 + totalSlides) % totalSlides);
            startAutoSlide();
        });
        slider.querySelector(".peugeot-slider-nav-zone.right")?.addEventListener("click", () => {
            updateSlide((currentIndex + 1) % totalSlides);
            startAutoSlide();
        });

        // Swipe
        wrapper.addEventListener("touchstart", (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            stopAutoSlide();
        });
        wrapper.addEventListener("touchend", (e) => {
            if (!isDragging) return;
            let endX = e.changedTouches[0].clientX;
            let diff = startX - endX;
            if (diff > 50) updateSlide((currentIndex + 1) % totalSlides);
            else if (diff < -50) updateSlide((currentIndex - 1 + totalSlides) % totalSlides);
            isDragging = false;
            startAutoSlide();
        });

        // Init
        updateSlide(0);
        startAutoSlide();
    });

    // Xử lý mobile menu
    var btn = document.getElementById('mobileMenuBtn');
    var overlay = document.getElementById('mobileMenuOverlay');
    var closeBtn = document.getElementById('closeMobileMenu');
    if (btn && overlay && closeBtn) {
        btn.onclick = function () {
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
        closeBtn.onclick = function () {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        };
        overlay.onclick = function (e) {
            if (e.target === overlay) {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        };
    }

    // Slider fade (content1 - dạng .peugeot-slider với .peugeot-slider-slide)
document.querySelectorAll(".peugeot-slider").forEach(function (slider) {
    const slides = slider.querySelectorAll('.peugeot-slider-slide');
    const navItems = slider.querySelectorAll('.peugeot-slider-nav-item');
    const leftBtn = slider.querySelector('.peugeot-slider-arrow.left');
    const rightBtn = slider.querySelector('.peugeot-slider-arrow.right');
    if (!slides.length) return;

    let current = 0;
    function showSlide(n) {
        slides.forEach((s, i) => s.classList.toggle('active', i === n));
        navItems.forEach((navi, i) => navi.classList.toggle('active', i === n));
        current = n;
    }

    leftBtn?.addEventListener("click", () => {
        let n = (current - 1 + slides.length) % slides.length;
        showSlide(n);
    });
    rightBtn?.addEventListener("click", () => {
        let n = (current + 1) % slides.length;
        showSlide(n);
    });
    navItems.forEach((navi, i) => {
        navi.onclick = () => showSlide(i);
    });

    showSlide(0);
});

// Slider 2 cột fade (content3 - dạng .peugeot-slider3 với .peugeot-slider3-slide)
document.querySelectorAll(".peugeot-slider3").forEach(function (slider) {
    const slides = slider.querySelectorAll('.peugeot-slider3-slide');
    const navItems = slider.querySelectorAll('.peugeot-slider3-nav-item');
    const contents = slider.querySelectorAll('.peugeot-slider3-content');
    if (!slides.length) return;

    function showSlide(n) {
        slides.forEach((s, i) => s.classList.toggle('active', i === n));
        contents.forEach((c, i) => c.classList.toggle('active', i === n));
        navItems.forEach((navi, i) => navi.classList.toggle('active', i === n));
    }

    navItems.forEach((navi, i) => {
        navi.onclick = () => showSlide(i);
    });

    showSlide(0); // init
});

 document.querySelectorAll('.peugeot-menu-list li.menu-item-has-children > a').forEach(function(parentLink) {
            parentLink.addEventListener('click', function(e) {
                e.preventDefault();
                var li = parentLink.parentElement;
                var isOpen = li.classList.contains('open');
                document.querySelectorAll('.peugeot-menu-list li.menu-item-has-children').forEach(function(item) {
                    item.classList.remove('open');
                });
                if (!isOpen) li.classList.add('open');
            });
        });
        // Đóng submenu khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.peugeot-menu-list li.menu-item-has-children')) {
                document.querySelectorAll('.peugeot-menu-list li.menu-item-has-children').forEach(function(item) {
                    item.classList.remove('open');
                });
            }
        });

  // Slider 4 (dạng .peugeot-slider4 với .peugeot-slider4-slide)
document.querySelectorAll(".peugeot-slider4").forEach(function (slider) {
    const slides = slider.querySelectorAll(".peugeot-slider4-slide");
    const contents = slider.querySelectorAll(".peugeot-slider4-content");
    const prevBtn = slider.querySelector(".peugeot-slider-arrow.prev");
    const nextBtn = slider.querySelector(".peugeot-slider-arrow.next");
    const prevBtnSmall = slider.querySelector(".peugeot-slider4-counter .peugeot-slider4-arrow.prev");
    const nextBtnSmall = slider.querySelector(".peugeot-slider4-counter .peugeot-slider4-arrow.next");
    const currentSlideEl = slider.querySelector("#current-slide");
    const totalSlidesEl = slider.querySelector("#total-slides");

    if (!slides.length) return;

    let currentIndex = 0;
    const total = slides.length;
    if (totalSlidesEl) totalSlidesEl.textContent = total;

    function showSlide(index) {
        slides.forEach((s, i) => s.classList.toggle("active", i === index));
        contents.forEach((c, i) => c.classList.toggle("active", i === index));
        if (currentSlideEl) currentSlideEl.textContent = index + 1;
        currentIndex = index;
    }

    function goPrev() {
        currentIndex = (currentIndex - 1 + total) % total;
        showSlide(currentIndex);
    }

    function goNext() {
        currentIndex = (currentIndex + 1) % total;
        showSlide(currentIndex);
    }

    // gán sự kiện cho cả nút to + nhỏ
    prevBtn?.addEventListener("click", goPrev);
    nextBtn?.addEventListener("click", goNext);
    prevBtnSmall?.addEventListener("click", goPrev);
    nextBtnSmall?.addEventListener("click", goNext);

    // init
    showSlide(currentIndex);
});
const mainImg = document.getElementById("peugeot-main-img");
    const colorItems = document.querySelectorAll(".peugeot-product-color-item");

    colorItems.forEach(item => {
        item.addEventListener("click", function () {
            // đổi ảnh chính
            const newImg = this.getAttribute("data-image");

            // hiệu ứng fade
            mainImg.style.opacity = 0;
            setTimeout(() => {
                mainImg.src = newImg;
                mainImg.style.opacity = 1;
            }, 300);

            // active state
            colorItems.forEach(el => el.classList.remove("active"));
            this.classList.add("active");
        });
    });
});
