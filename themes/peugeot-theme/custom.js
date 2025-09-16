document.addEventListener("DOMContentLoaded", function () {
  // =========================
  // 1) SLIDER KIỂU WRAPPER (ví dụ: hero có .peugeot-slider-wrapper + .peugeot-slide)
  // =========================
  document.querySelectorAll(".peugeot-slider-wrapper").forEach(function (wrapper) {
    const slider = wrapper.closest(".peugeot-slider");
    const slides = wrapper.querySelectorAll(".peugeot-slide");
    if (!slider || !slides.length) return;

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
      if (autoSlideInterval) clearInterval(autoSlideInterval);
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
    }, { passive: true });

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

  // =========================
  // 2) SLIDER FADE (.peugeot-slider với .peugeot-slider-slide) — HỖ TRỢ NAV NẰM TRONG SLIDE (CÁCH B)
  // =========================
  // =========================
// 2) SLIDER FADE (.peugeot-slider với .peugeot-slider-slide)
//    Hoist NAV ra top-level để luôn hiển thị
// =========================
(function () {
  document.querySelectorAll(".peugeot-slider").forEach(function (slider) {
    // Chỉ khởi tạo khi container có slide trực tiếp
    const slides = Array.from(slider.querySelectorAll(":scope > .peugeot-slider-slide"));
    if (!slides.length) return;

    // 👉 HOIST NAV: nếu nav nằm trong slide, nhấc cụm nav đầu tiên ra làm con trực tiếp của slider
    let navRoot = slider.querySelector(":scope > .peugeot-slider-nav");
    if (!navRoot) {
      const nestedNav = slider.querySelector(".peugeot-slider-slide .peugeot-slider-nav");
      if (nestedNav) {
        slider.insertBefore(nestedNav, slider.firstChild); // đặt lên đầu
        navRoot = nestedNav;
      }
    }

    const navItems = navRoot ? Array.from(navRoot.querySelectorAll(".peugeot-slider-nav-item")) : [];
    const leftBtn  = slider.querySelector(".peugeot-slider-arrow.left");
    const rightBtn = slider.querySelector(".peugeot-slider-arrow.right");

    // slide hiện tại (ưu tiên slide có .active sẵn)
    let current = slides.findIndex(s => s.classList.contains("active"));
    if (current < 0) current = 0;

    function resizeToActive() {
      slider.style.height = ""; // không ép height
    }

    function updateSlidesActive(idx) {
      slides.forEach((s, i) => s.classList.toggle("active", i === idx));
    }

    // Toggle active cho TẤT CẢ nav-items theo data-slide (phòng khi sau này bạn có nav phụ)
    function updateNavActive(idx) {
      slider.querySelectorAll(".peugeot-slider-nav-item").forEach(el => {
        const n = parseInt(el.dataset.slide, 10);
        el.classList.toggle("active", n === idx);
      });
    }

    function setActive(idx) {
      if (idx === current || idx < 0 || idx >= slides.length) return;
      current = idx;
      updateSlidesActive(current);
      updateNavActive(current);
      resizeToActive();
    }

    const next = () => setActive((current + 1) % slides.length);
    const prev = () => setActive((current - 1 + slides.length) % slides.length);

    // Nút trái/phải
    leftBtn?.addEventListener("click", prev);
    rightBtn?.addEventListener("click", next);

    // Uỷ quyền click cho nav-items (nav ở đâu cũng được)
    slider.addEventListener("click", (e) => {
      const el = e.target.closest(".peugeot-slider-nav-item");
      if (!el || !slider.contains(el)) return;
      const i = parseInt(el.dataset.slide, 10);
      if (!Number.isNaN(i)) setActive(i);
    });

    // Swipe (mobile)
    let startX = 0;
    slider.addEventListener("touchstart", e => { startX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener("touchend", e => {
      const dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) > 40) (dx < 0 ? next : prev)();
    });

    // Keyboard
    slider.setAttribute("tabindex", "0");
    slider.addEventListener("keydown", e => {
      if (e.key === "ArrowLeft")  { e.preventDefault(); prev(); }
      if (e.key === "ArrowRight") { e.preventDefault(); next(); }
    });

    // Khởi tạo
    updateSlidesActive(current);
    updateNavActive(current);

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

  document.querySelectorAll(".peugeot-product-colors").forEach(function (block) {
    var mainImg = block.querySelector("#peugeot-main-img");
    var colorItems = block.querySelectorAll(".peugeot-product-color-item");
    if (!mainImg || !colorItems.length) return;

    colorItems.forEach(function (item) {
      item.addEventListener("click", function () {
        // Bỏ active ở tất cả item
        colorItems.forEach(function (el) { el.classList.remove("active"); });
        // Đặt active cho item đang click
        item.classList.add("active");
        // Đổi ảnh xe
        var imgUrl = item.getAttribute("data-image");
        if (imgUrl) mainImg.setAttribute("src", imgUrl);
      });
    });
  });

/* ===== Header state + Mobile overlay (single source) ===== */
(function () {
  const header  = document.getElementById('siteHeader') || document.querySelector('.peugeot-header');
  const overlay = document.getElementById('mobileMenuOverlay');
  const btnOpen = document.getElementById('mobileMenuBtn');
  const btnClose= document.getElementById('closeMobileMenu');
  const mainNav = document.querySelector('.peugeot-main-nav');
  if (!header) return;

  const THRESHOLD = 100;
  const atTop   = () => window.scrollY <= THRESHOLD;
  const anyDesktopSubOpen = () => !!mainNav?.querySelector('li.open');
  const navOpen = () => header.classList.contains('nav-open');

  // Make it global so other blocks can call it
  window.updateHeaderDark = function updateHeaderDark () {
    const shouldDark = !atTop() || anyDesktopSubOpen() || navOpen();
    header.classList.toggle('header-dark', shouldDark);
  };

  function openOverlay(){
    if (!overlay) return;
    overlay.classList.add('active');
    header.classList.add('nav-open');
    document.body.style.overflow = 'hidden';
    window.updateHeaderDark();
  }
  function closeOverlay(){
    if (!overlay) return;
    overlay.classList.remove('active');
    header.classList.remove('nav-open');
    document.body.style.overflow = '';
    window.updateHeaderDark();
  }

  btnOpen?.addEventListener('click', openOverlay);
  btnClose?.addEventListener('click', closeOverlay);
  overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeOverlay(); });

  // Scroll: đồng bộ màu header
  window.addEventListener('scroll', window.updateHeaderDark, { passive: true });
  window.updateHeaderDark();
})();

   /* ---------- DESKTOP SUBMENU (mega) — CLICK TOGGLE (robust) ---------- */
(function(){
  const header  = document.getElementById('siteHeader') || document.querySelector('.peugeot-header');
  const mainNav = document.querySelector('.peugeot-main-nav');
  if (!header || !mainNav) return;

  // Hàm kiểm tra li có .mega-panel là CON TRỰC TIẾP
  function hasMegaPanel(li){
    for (const el of li.children) {
      if (el.classList && el.classList.contains('mega-panel')) return true;
    }
    return false;
  }

  // Bắt sự kiện click ngay trên nav (uỷ quyền)
  mainNav.addEventListener('click', (e) => {
    const a  = e.target.closest('a');
    const li = a?.closest('li');
    if (!a || !li || !mainNav.contains(li)) return;

    // Nếu li có mega-panel -> chặn đi link & toggle .open
    if (hasMegaPanel(li)) {
      e.preventDefault();

      const willOpen = !li.classList.contains('open');
      // Đóng tất cả mục khác
      mainNav.querySelectorAll('li.open').forEach(x => { if (x !== li) x.classList.remove('open'); });
      // Mở mục hiện tại
      li.classList.toggle('open', willOpen);

      // Cập nhật màu header (nếu bạn đang dùng hàm này ở nơi khác thì gọi lại)
      if (typeof updateHeaderDark === 'function') updateHeaderDark();
      return;
    }

    // Nếu KHÔNG có mega-panel => để mặc định đi link
  });

  // Click ra ngoài để đóng
  document.addEventListener('click', (e) => {
    if (!mainNav.contains(e.target)) {
      mainNav.querySelectorAll('li.open').forEach(li => li.classList.remove('open'));
      if (typeof updateHeaderDark === 'function') updateHeaderDark();
    }
  });

  // ESC để đóng
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      mainNav.querySelectorAll('li.open').forEach(li => li.classList.remove('open'));
      if (typeof updateHeaderDark === 'function') updateHeaderDark();
    }
  });
  // Mobile submenu accordion (uỷ quyền sự kiện, không đụng desktop)
// === SUBMENU MOBILE (overlay): first tap opens, second tap navigates ===
document.querySelectorAll('.peugeot-mobile-menu-list').forEach(function(menu){
  menu.querySelectorAll('li.menu-item-has-children > a').forEach(function(a){
    a.addEventListener('click', function(e){
      const li   = this.parentElement;
      const href = this.getAttribute('href') || '';

      // Lần 1: nếu đang đóng -> mở và chặn điều hướng
      if (!li.classList.contains('open')) {
        e.preventDefault();
        // đóng anh em cùng cấp
        li.parentElement.querySelectorAll(':scope > li.menu-item-has-children.open')
          .forEach(sib => { if (sib !== li) sib.classList.remove('open'); });
        li.classList.add('open');
        return;
      }

      // Lần 2: nếu đã mở
      // - nếu href trống hoặc '#': chỉ toggle
      // - nếu href là URL thật: KHÔNG preventDefault => đi link
      if (!href || href === '#') {
        e.preventDefault();
        li.classList.toggle('open');
      } 
      // else: để trình duyệt điều hướng bình thường
    });
  });
});

  document.body.addEventListener("click", (e) => {
    if (e.target.classList.contains("mega-close")) {
      const li = e.target.closest("li.open");
      if (li) li.classList.remove("open");
    }
  });
})();
