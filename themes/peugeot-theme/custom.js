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
(function () {
  document.querySelectorAll(".peugeot-slider").forEach(function (slider) {
    const slides   = Array.from(slider.querySelectorAll(".peugeot-slider-slide"));
    if (!slides.length) return;

    const navItems = Array.from(slider.querySelectorAll(".peugeot-slider-nav-item"));
    const leftBtn  = slider.querySelector(".peugeot-slider-arrow.left");
    const rightBtn = slider.querySelector(".peugeot-slider-arrow.right");

    // slide hiện tại (ưu tiên slide có .active sẵn)
    let current = Math.max(0, slides.findIndex(s => s.classList.contains("active")));
    if (current < 0) current = 0;

    // --- Tính & set chiều cao cho container dựa theo ảnh của slide active ---
    function resizeToActive() {
      const active = slides[current];
      if (!active) return;

      // nếu có ảnh => tính theo tỉ lệ thật; fallback offsetHeight
      const img = active.querySelector("img");
      let h = active.offsetHeight || 600;

      if (img && img.naturalWidth) {
        const ratio = img.naturalHeight / img.naturalWidth;
        const w = slider.clientWidth;
        h = Math.max(180, Math.round(w * ratio));
      }
      slider.style.height = h + "px";
    }

    function activate(n) {
      if (n === current) return;

      slides[current].classList.remove("active");
      navItems[current]?.classList.remove("active");

      current = n;

      slides[current].classList.add("active");
      navItems[current]?.classList.add("active");

      resizeToActive();
    }

    function next() { activate((current + 1) % slides.length); }
    function prev() { activate((current - 1 + slides.length) % slides.length); }

    // Nút trái/phải
    leftBtn?.addEventListener("click", prev);
    rightBtn?.addEventListener("click", next);

    // Tabs nav
    navItems.forEach((el, i) => el.addEventListener("click", () => activate(i)));

    // Swipe (mobile)
    let startX = 0;
    slider.addEventListener("touchstart", e => { startX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener("touchend", e => {
      const dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) > 40) (dx < 0 ? next : prev)();
    });

    // Keyboard (khi slider được focus)
    slider.setAttribute("tabindex", "0");
    slider.addEventListener("keydown", e => {
      if (e.key === "ArrowLeft")  { e.preventDefault(); prev(); }
      if (e.key === "ArrowRight") { e.preventDefault(); next(); }
    });

    // Khởi tạo trạng thái & chiều cao
    slides.forEach((s, i) => s.classList.toggle("active", i === current));
    navItems.forEach((n, i) => n.classList.toggle("active", i === current));

    // canh lại khi load/resize + khi ảnh active vừa load xong
    window.addEventListener("load", resizeToActive);
    window.addEventListener("resize", resizeToActive);
    const firstImg = slides[current].querySelector("img");
    if (firstImg && !firstImg.complete) {
      firstImg.addEventListener("load", resizeToActive, { once: true });
    }
    resizeToActive();
  });
})();


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
  const slides   = Array.from(slider.querySelectorAll(".peugeot-slider4-slide"));
  const contents = Array.from(slider.querySelectorAll(".peugeot-slider4-content"));
  if (!slides.length) return;

  const prevBtn  = slider.querySelector(".peugeot-slider-arrow.prev");
  const nextBtn  = slider.querySelector(".peugeot-slider-arrow.next");
  const prevSm   = slider.querySelector(".peugeot-slider4-counter .peugeot-slider4-arrow.prev");
  const nextSm   = slider.querySelector(".peugeot-slider4-counter .peugeot-slider4-arrow.next");
  const curEl    = slider.querySelector("#current-slide");
  const totalEl  = slider.querySelector("#total-slides");

  // Wraps để điều khiển layer + height
  const imgWrap  = slider.querySelector(".peugeot-slider-col.image-col");
  const cntWrap  = slider.querySelector(".peugeot-slider4-contents");

  // Chuẩn hóa style cần thiết cho animation mượt
  if (imgWrap) imgWrap.style.position = "relative";
  if (cntWrap) {
    cntWrap.style.position = "relative";
    cntWrap.style.overflow = "hidden"; // để animate height gọn
    cntWrap.style.willChange = "height";
  }

  // Khởi tạo hiển thị ban đầu (chỉ 0 hiện)
  slides.forEach((el, i) => {
    el.style.position = "absolute";
    el.style.inset = "0";
    el.style.transition = "opacity 420ms ease, transform 420ms cubic-bezier(.22,.61,.36,1)";
    el.style.willChange = "opacity, transform";
    el.style.backfaceVisibility = "hidden";
    if (i === 0) {
      el.style.display = "block";
      el.style.opacity = "1";
      el.style.transform = "translateX(0)";
      el.classList.add("active");
    } else {
      el.style.display = "none";
      el.style.opacity = "0";
      el.style.transform = "translateX(40px)";
      el.classList.remove("active");
    }
  });

  contents.forEach((el, i) => {
    // Nội dung để đo height cần "flow" bình thường
    el.style.transition = "opacity 420ms ease, transform 420ms cubic-bezier(.22,.61,.36,1)";
    el.style.willChange = "opacity, transform";
    el.style.backfaceVisibility = "hidden";
    if (i === 0) {
      el.style.display = "block";
      el.style.opacity = "1";
      el.style.transform = "translateX(0)";
      el.classList.add("active");
    } else {
      el.style.display = "none";
      el.style.opacity = "0";
      el.style.transform = "translateX(40px)";
      el.classList.remove("active");
    }
  });

  let currentIndex = 0;
  const total = slides.length;
  if (totalEl) totalEl.textContent = total;
  if (curEl)   curEl.textContent   = 1;

  let isAnimating = false;

  // Animate height của cntWrap theo panel mục tiêu
  function animateHeight(toEl) {
    if (!cntWrap || !toEl) return;
    const startH = cntWrap.offsetHeight;

    // Chuẩn bị đo chiều cao mục tiêu
    const prevPos = toEl.style.position;
    const prevDisp= toEl.style.display;
    const prevVis = toEl.style.visibility;

    toEl.style.position = "static";  // cho phép chiếm chỗ thật
    toEl.style.display  = "block";
    toEl.style.visibility = "hidden"; // không nhấp nháy

    // ép reflow để lấy chiều cao mục tiêu
    const endH = toEl.offsetHeight;

    // trả trạng thái đo về
    toEl.style.position = prevPos || "";
    toEl.style.display  = prevDisp  || "";
    toEl.style.visibility = prevVis || "";

    // Thiết lập transition height
    cntWrap.style.height = startH + "px";
    // ép reflow trước khi đổi height
    void cntWrap.offsetWidth;
    cntWrap.style.transition = "height 420ms cubic-bezier(.22,.61,.36,1)";
    cntWrap.style.height = endH + "px";

    cntWrap.addEventListener("transitionend", function onH() {
      cntWrap.style.transition = "";
      cntWrap.style.height = ""; // về auto
      cntWrap.removeEventListener("transitionend", onH);
    }, { once: true });
  }

  function transitionPair(outEl, inEl, dir) {
    // Hiển thị inEl để bắt đầu transition
    inEl.style.display = "block";

    // Đặt trạng thái đầu vào tùy hướng
    inEl.style.opacity = "0";
    inEl.style.transform = dir === "next" ? "translateX(40px)" : "translateX(-40px)";

    // ép reflow để transition chạy
    void inEl.offsetWidth;

    // chạy transition
    outEl.style.opacity = "0";
    outEl.style.transform = dir === "next" ? "translateX(-40px)" : "translateX(40px)";

    inEl.style.opacity = "1";
    inEl.style.transform = "translateX(0)";

    // sau khi outEl xong -> ẩn outEl
    outEl.addEventListener("transitionend", function onOutEnd(e) {
      if (e.propertyName !== "opacity") return; // chỉ chờ 1 lần
      outEl.style.display = "none";
      outEl.removeEventListener("transitionend", onOutEnd);
    }, { once: true });
  }

  function show(toIndex, dir) {
    if (isAnimating) return;
    const to = (toIndex + total) % total;
    const from = currentIndex;
    if (to === from) return;

    isAnimating = true;

    // Ảnh
    const outImg = slides[from];
    const inImg  = slides[to];
    transitionPair(outImg, inImg, dir);

    // Nội dung + animate height
    const outCnt = contents[from];
    const inCnt  = contents[to];

    animateHeight(inCnt);
    transitionPair(outCnt, inCnt, dir);

    // cập nhật chỉ số
    currentIndex = to;
    if (curEl) curEl.textContent = currentIndex + 1;

    // unlock sau khi nội dung hoàn tất (opacity transition ~ 420ms)
    setTimeout(() => { isAnimating = false; }, 460);
  }

  function goPrev() { show(currentIndex - 1, "prev"); }
  function goNext() { show(currentIndex + 1, "next"); }

  prevBtn && prevBtn.addEventListener("click", goPrev);
  nextBtn && nextBtn.addEventListener("click", goNext);
  prevSm  && prevSm .addEventListener("click", goPrev);
  nextSm  && nextSm .addEventListener("click", goNext);

  // Cho phép click tiêu đề trong panel để nhảy tới mục đó (vẫn giữ hướng đúng)
  contents.forEach((panel, i) => {
    const title = panel.querySelector(".peugeot-slider4-nav-item");
    if (!title) return;
    title.addEventListener("click", () => {
      const dir = i > currentIndex ? "next" : "prev";
      show(i, dir);
    });
  });
});
 // ===== PB slider (tabs -> đổi background, mượt & an toàn) =====
document.querySelectorAll(".peugeot-sliderpb").forEach(function (root) {
  const tabs   = Array.from(root.querySelectorAll(".pb-tab"));
  const panels = Array.from(root.querySelectorAll(".pb-panel"));
  const prev   = root.querySelector(".pb-prev");
  const next   = root.querySelector(".pb-next");
  const visual = root.querySelector(".pb-visual");          // khung ảnh
  const bgNode = root.querySelector(".pb-visual-bg");       // lớp nền thật

  if (!visual || !bgNode || !panels.length) return;

  // Lấy index active ban đầu
  let current = Math.max(0, panels.findIndex(p => p.classList.contains("is-active")));
  if (current < 0) current = 0;

  // ===== Helpers =====
  const clampIndex = (i) => (i < 0 ? panels.length - 1 : (i >= panels.length ? 0 : i));
  const getBG = (i) => panels[i]?.dataset?.bg || "";

  // Preload tất cả ảnh để đổi nền mượt
  (function preloadAll() {
    panels.forEach(p => {
      const url = p?.dataset?.bg;
      if (!url) return;
      const img = new Image();
      img.decoding = "async";
      img.src = url;
    });
  })();

  // Đổi nền qua CSS var + class fade
  let fadeTimer = null;
  function setBG(url) {
    if (!url) return;
    // Nếu đang fade thì reset để không nhấp nháy
    if (fadeTimer) {
      clearTimeout(fadeTimer);
      fadeTimer = null;
    }
    // Thêm lớp để CSS animate opacity lớp bg
    visual.classList.add("is-fading");
    root.style.setProperty("--pb-bg", `url('${url}')`);
    // kết thúc fade
    fadeTimer = setTimeout(() => {
      visual.classList.remove("is-fading");
      fadeTimer = null;
    }, 220); // khớp tốc độ trong CSS (~200ms)
  }

  // Kích hoạt tab/panel theo index
  function activate(i) {
    tabs.forEach(t => t.classList.remove("is-active"));
    panels.forEach(p => p.classList.remove("is-active"));
    tabs[i]?.classList.add("is-active");
    panels[i]?.classList.add("is-active");
  }

  // Chuyển slide
  let lock = false;
  function show(to) {
    if (lock) return;
    const nextIdx = clampIndex(to);
    if (nextIdx === current) return;

    lock = true;
    activate(nextIdx);
    setBG(getBG(nextIdx));

    // unlock sau khi fade xong
    setTimeout(() => { lock = false; }, 260);
    current = nextIdx;
  }

  function goPrev() { show(current - 1); }
  function goNext() { show(current + 1); }

  // ===== Bind events =====
  tabs.forEach((t, i) => t.addEventListener("click", () => show(i)));
  prev && prev.addEventListener("click", goPrev);
  next && next.addEventListener("click", goNext);

  // Vuốt trái/phải (pointer events – hoạt động cả chuột & touch)
  (function enableSwipe(area) {
    if (!area) return;
    let startX = 0, tracking = false;

    area.addEventListener("pointerdown", (e) => {
      tracking = true;
      startX = e.clientX;
      area.setPointerCapture?.(e.pointerId);
    });

    area.addEventListener("pointerup", (e) => {
      if (!tracking) return;
      const dx = e.clientX - startX;
      tracking = false;
      if (Math.abs(dx) < 40) return;     // ngưỡng lọc
      dx < 0 ? goNext() : goPrev();
    });

    area.addEventListener("pointercancel", () => { tracking = false; });
    area.addEventListener("pointerleave",  () => { tracking = false; });
  })(visual);

  // Phím mũi tên
  root.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft")  { e.preventDefault(); goPrev(); }
    if (e.key === "ArrowRight") { e.preventDefault(); goNext(); }
  });

  // ===== Init =====
  activate(current);
  setBG(getBG(current));
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


