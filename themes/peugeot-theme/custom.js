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
    // === XỬ LÝ MOBILE MENU (REPLACE) ===
var btn = document.getElementById('mobileMenuBtn');
var overlay = document.getElementById('mobileMenuOverlay');
var closeBtn = document.getElementById('closeMobileMenu');
var headerEl = document.getElementById('siteHeader') || document.querySelector('.peugeot-header');

if (btn && overlay && closeBtn && headerEl) {
  const THRESHOLD = 100;

  btn.onclick = function () {
    overlay.classList.add('active');
    headerEl.classList.add('nav-open');     // cờ nav mở
    headerEl.classList.add('header-dark');  // tối header khi mở menu
    document.body.style.overflow = 'hidden';
  };

  function closeOverlay() {
    overlay.classList.remove('active');
    headerEl.classList.remove('nav-open');

    // Nếu ở đầu trang & không còn submenu mobile mở -> bỏ dark
    const hasAnyMobileOpen = !!overlay.querySelector('.peugeot-mobile-menu-list li.menu-item-has-children.open');
    if (window.scrollY <= THRESHOLD && !hasAnyMobileOpen) {
      headerEl.classList.remove('header-dark');
    } else {
      headerEl.classList.add('header-dark');
    }
    document.body.style.overflow = '';
  }

  closeBtn.onclick = closeOverlay;
  overlay.onclick = function (e) { if (e.target === overlay) closeOverlay(); };
}

// === SUBMENU MOBILE (OVERLAY): ACCORDION (ADD) ===
document.querySelectorAll('.peugeot-mobile-menu-list').forEach(function(menu){
  menu.querySelectorAll('li.menu-item-has-children > a').forEach(function(a){
    a.addEventListener('click', function(e){
      e.preventDefault(); // nếu muốn <a> đi link, bỏ dòng này và gắn click vào caret riêng

      const li = a.parentElement;
      const willOpen = !li.classList.contains('open');

      // đóng anh em cùng cấp
      li.parentElement.querySelectorAll(':scope > li.menu-item-has-children.open').forEach(function(sib){
        if (sib !== li) sib.classList.remove('open');
      });

      // toggle chính nó
      li.classList.toggle('open', willOpen);

      // đồng bộ header-dark khi overlay đang mở
      const headerEl = document.getElementById('siteHeader') || document.querySelector('.peugeot-header');
      if (headerEl) {
        if (willOpen) {
          headerEl.classList.add('header-dark');
        } else {
          const hasAnyOpen = !!menu.querySelector('li.menu-item-has-children.open');
          if (window.scrollY <= 100 && !hasAnyOpen && !headerEl.classList.contains('nav-open')) {
            headerEl.classList.remove('header-dark');
          }
        }
      }
    });
  });
});

    // ===== Header states: scroll (desktop & mobile), submenu (desktop), hamburger (mobile) =====
(function () {
  const header  = document.getElementById('siteHeader') || document.querySelector('.peugeot-header');
  const overlay = document.getElementById('mobileMenuOverlay');
  const btnOpen = document.getElementById('mobileMenuBtn');
  const btnClose= document.getElementById('closeMobileMenu');
  const mainNav = document.querySelector('.peugeot-main-nav');
  if (!header) return;

  // ====== Cấu hình ======
  const THRESHOLD = 100; // px - coi như "đầu trang" nếu scrollY <= THRESHOLD

  // ====== Helpers ======
  const atTop   = () => window.scrollY <= THRESHOLD;
  const anyOpen = () => !!mainNav?.querySelector('li.menu-item-has-children.open');
  const navOpen = () => header.classList.contains('nav-open');

  function updateHeaderDark() {
    // Bật dark nếu: đã cuộn qua ngưỡng  || có submenu đang mở || nav mobile đang mở
    const shouldDark = !atTop() || anyOpen() || navOpen();
    header.classList.toggle('header-dark', shouldDark);
  }

  // ====== Scroll (giữ hành vi cũ) ======
  function onScroll() {
    updateHeaderDark();
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // ====== Submenu: bấm để mở/đóng; đóng & đang ở đầu trang -> tắt header-dark ======
  if (mainNav) {
    const parents = mainNav.querySelectorAll('li.menu-item-has-children');

    parents.forEach(li => {
      const a = li.querySelector(':scope > a');
      if (!a) return;

      a.addEventListener('click', (e) => {
        // Nếu muốn anchor đi link, bỏ dòng này và gán click vào nút caret riêng.
        e.preventDefault();

        const willOpen = !li.classList.contains('open');

        // (Tuỳ chọn) đóng các submenu khác:
        parents.forEach(x => x !== li && x.classList.remove('open'));

        // Toggle submenu hiện tại
        li.classList.toggle('open', willOpen);

        // Cập nhật màu header theo đúng luật:
        // - Nếu submenu vừa đóng và đang ở đầu trang + không mở nav -> tắt dark.
        // - Ngược lại (đang mở submenu / đã cuộn / đang mở nav) -> bật dark.
        updateHeaderDark();
      });
    });

    // Click ra ngoài: đóng tất cả submenu, rồi về trạng thái theo scroll/hamburger
    document.addEventListener('click', (e) => {
      if (!mainNav.contains(e.target)) {
        mainNav.querySelectorAll('li.menu-item-has-children.open').forEach(li => li.classList.remove('open'));
        updateHeaderDark();
      }
    });

    // Nhấn ESC: đóng submenu
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        mainNav.querySelectorAll('li.menu-item-has-children.open').forEach(li => li.classList.remove('open'));
        updateHeaderDark();
      }
    });
  }

  // ====== Hamburger (mobile) ======
  if (btnOpen) {
    btnOpen.addEventListener('click', () => {
      header.classList.add('nav-open');
      updateHeaderDark();
    });
  }
  if (btnClose) {
    btnClose.addEventListener('click', () => {
      header.classList.remove('nav-open');
      updateHeaderDark();
    });
  }

  // (Tuỳ chọn) Click overlay để đóng nav
  if (overlay) {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        header.classList.remove('nav-open');
        updateHeaderDark();
      }
    });
  }
})();


});
