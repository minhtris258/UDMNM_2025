<?php

/**
 * Template Name: Lịch sử thương hiệu (ACF Group 14 mốc)
 * Description: Dùng ACF Group 'timeline' có các field: year1..year14, title1..title14, desc1..desc14, image1..image14
 * Text Domain: peugeot-theme
 */

get_header(); ?>
<?php
$banner = get_field('banner');
if ($banner && isset($banner['url'])) {
  echo '<div class="archive-banner">
  <img class="peugeot-banner" src="' . esc_url($banner['url']) . '" alt="' . esc_attr($banner['alt']) . '" />
    <div class="archive-banner-overlay">
      <h1 class="archive-title">' . esc_html(get_the_title()) . '</h1>
    </div>
  </div>';
}
?>
<main id="primary" class="site-main container py-2 py-lg-5 brand-history">
  <header class="content-header">
    <p><?php echo esc_html(get_the_excerpt()); ?></p>
  </header>

  <?php
  // Lấy toàn bộ group 'timeline' một lần
  $tl = function_exists('get_field') ? (array) get_field('timeline') : [];
  $items = [];

  for ($i = 1; $i <= 14; $i++) {
    $year = isset($tl["year{$i}"]) ? trim((string)$tl["year{$i}"]) : '';
    $title = isset($tl["title{$i}"]) ? (string)$tl["title{$i}"] : '';
    $desc = isset($tl["desc{$i}"]) ? (string)$tl["desc{$i}"] : '';
    $img  = $tl["image{$i}"] ?? '';
    $img_url = '';

    if ($img) {
      // ACF có thể trả về array hoặc ID
      $img_url = is_array($img) ? ($img['sizes']['large'] ?? $img['url']) : wp_get_attachment_image_url((int)$img, 'large');
    }

    // Bỏ qua nếu rỗng hết
    if ($year === '' && $title === '' && $desc === '' && !$img_url) continue;

    $items[] = [
      'i'      => $i,
      'year'   => $year,
      'title'  => $title,
      'desc'   => $desc,
      'img'    => $img_url,
    ];
  }
  ?>

  <?php if (!empty($items)) : ?>

    <!-- Thanh năm -->
    <nav class=" timeline-yearbar mb-4" aria-label="<?php echo esc_attr__('Thanh năm', 'peugeot-theme'); ?>">
      <ul class="yearbar list-unstyled d-flex flex-wrap gap-2">
        <li class="year-item"><?php echo esc_html($tl["title_year"]); ?></li>
        <?php foreach ($items as $it): $anchor = 'moc-' . $it['i']; ?>
          <li>
            <a href="#<?php echo esc_attr($anchor); ?>" class="year-link btn btn-light btn-sm js-year-link" data-target="<?php echo esc_attr($anchor); ?>">
              <?php echo esc_html($it['year'] ?: sprintf('%s %d', __('Mốc', 'peugeot-theme'), $it['i'])); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <!-- Timeline -->
    <ol class="timeline list-unstyled">
      <?php foreach ($items as $it): $anchor = 'moc-' . $it['i']; ?>
        <li id="<?php echo esc_attr($anchor); ?>" class="timeline-item d-md-flex">
          <div class="timeline-year">
            <span><?php echo esc_html($it['year'] ?: '—'); ?></span>
          </div>
          <div class="timeline-content card shadow-sm">

            <div class="timeline-body">
              <?php if ($it['title']): ?>
                <h3 class="h5 mb-2 fw-bold"><?php echo esc_html($it['title']); ?></h3>
              <?php endif; ?>
              <?php if ($it['desc']): ?>
                <div class="timeline-text"><?php echo wp_kses_post(wpautop($it['desc'])); ?></div>
              <?php endif; ?>
              <div class="mt-3">
                <a href="#top" class="small text-decoration-none js-back-top"><?php echo esc_html__('Về đầu trang', 'peugeot-theme'); ?></a>
              </div>
            </div>
            <?php if ($it['img']): ?>
              <div class="timeline-media">
                <img src="<?php echo esc_url($it['img']); ?>" alt="<?php echo esc_attr($it['title'] ?: $it['year']); ?>" loading="lazy" decoding="async">
              </div>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>

  <?php else: ?>
    <p class="text-muted"><?php echo esc_html__('Không có dữ liệu timeline.', 'peugeot-theme'); ?></p>
  <?php endif; ?>
</main>

<script>
  // Smooth scroll + đánh dấu năm đang xem
  (function() {
    const links = document.querySelectorAll('.js-year-link');
    links.forEach(a => {
      a.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-target');
        const el = document.getElementById(id);
        if (!el) return;
        window.history.replaceState(null, '', '#' + id);
        el.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      });
    });

    const topLinks = document.querySelectorAll('.js-back-top');
    topLinks.forEach(a => a.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }));

    const map = {};
    links.forEach(a => map[a.getAttribute('data-target')] = a);
    const io = new IntersectionObserver((entries) => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          const id = en.target.id;
          links.forEach(a => a.classList.remove('active'));
          if (map[id]) map[id].classList.add('active');
        }
      });
    }, {
      rootMargin: '-40% 0px -50% 0px',
      threshold: 0
    });
    document.querySelectorAll('.timeline-item[id]').forEach(el => io.observe(el));
  })();
</script>

<?php get_footer();
