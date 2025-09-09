<?php
get_header();
?>
<?php
$banner = get_field('image'); // trả về array
if ($banner && isset($banner['url'])) {
    echo '<img class="peugeot-banner" src="' . esc_url($banner['url']) . '" alt="' . esc_attr($banner['alt']) . '" />';
}
?>
<h1><?php the_title(); ?></h1>
<?php
$content1 = get_field('content1');
$slides = [];
for ($i = 1; $i <= 5; $i++) {
    if (!empty($content1['image_content' . $i])) {
        $slides[] = [
            'title' => $content1['text' . $i] ?? '',
            'content' => $content1['text_content' . $i] ?? '',
            'image' => is_array($content1['image_content' . $i]) ? $content1['image_content' . $i]['url'] : wp_get_attachment_image_url($content1['image_content' . $i], 'full'),
        ];
    }
}
?>
<div class="peugeot-slider peugeot-slider3" id="peugeot-slider3">
    <div class="peugeot-slider-row">
        <!-- Cột ảnh (60%) -->
        <div class="peugeot-slider-col image-col">
            <?php foreach ($slides as $idx => $slide): ?>
                <div class="peugeot-slider3-slide<?php echo $idx === 0 ? ' active' : ''; ?>">
                    <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" class="peugeot-slider3-img" />
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Cột nội dung (40%) -->
        <div class="peugeot-slider-col content-col">
            <!-- Nav -->
            <div class="peugeot-slider3-nav">
                <?php foreach ($slides as $idx => $slide): ?>
                    <span class="peugeot-slider3-nav-item<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo $idx; ?>">
                        <?php echo esc_html($slide['title']); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <!-- Nội dung -->
            <div class="peugeot-slider3-contents">
                <?php foreach ($slides as $idx => $slide): ?>
                    <div class="peugeot-slider3-content<?php echo $idx === 0 ? ' active' : ''; ?>">
                        <div class="peugeot-slider3-text">
                            <?php echo wp_kses_post($slide['content']); ?>
                        </div>
                        <div class="peugeot-slider3-buttons">
                            <button class="peugeot-slider-btn">LIÊN HỆ</button>
                            <button class="peugeot-slider-btn">ĐẶT LỊCH LÁI THỬ</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php
$content2 = get_field('content2');
$slides = [];
for ($i = 1; $i <= 3; $i++) {
    if (!empty($content2['image_content' . $i])) {
        $slides[] = [
            'content1' => $content2['text' . $i] ?? '',
            'content2' => $content2['text_content' . $i] ?? '',
            'image' => is_array($content2['image_content' . $i]) ? $content2['image_content' . $i]['url'] : wp_get_attachment_image_url($content2['image_content' . $i], 'full'),
        ];
    }
}
?>
<div class="peugeot-slider peugeot-slider4" id="peugeot-slider4">
  <div class="peugeot-slider-row">
    
    <!-- Cột nội dung (40%) -->
    <div class="peugeot-slider-col content-col">
      <h2 class="peugeot-slider4-title">
        <?php echo esc_html($content2['title']); ?>
      </h2>

     <!-- Thanh số slide -->
        <div class="peugeot-slider4-counter">
            <button class="peugeot-slider4-arrow small prev">&#10094;</button>
                <span id="current-slide">1</span> / <span id="total-slides"><?php echo count($slides); ?></span>
            <button class="peugeot-slider4-arrow small next">&#10095;</button>
        </div>

      <!-- Nội dung -->
      <div class="peugeot-slider4-contents">
        <?php foreach ($slides as $idx => $slide): ?>
          <div class="peugeot-slider4-content<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo $idx; ?>">
            <h3 class="peugeot-slider4-nav-item"><?php echo esc_html($slide['content1']); ?></h3>
            <div class="peugeot-slider4-text">
              <?php echo wp_kses_post($slide['content2']); ?>
            </div>
            <div class="peugeot-slider4-buttons">
              <button class="peugeot-slider-btn">LIÊN HỆ</button>
              <button class="peugeot-slider-btn">ĐẶT LỊCH LÁI THỬ</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    
    <!-- Cột ảnh (60%) -->
    <div class="peugeot-slider-col image-col">
      <?php foreach ($slides as $idx => $slide): ?>
        <div class="peugeot-slider4-slide<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo $idx; ?>">
          <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['content1']); ?>" class="peugeot-slider4-img" />
        </div>
      <?php endforeach; ?>

      <!-- Nút điều hướng mũi tên -->
      <button class="peugeot-slider-arrow prev">&#10094;</button>
      <button class="peugeot-slider-arrow next">&#10095;</button>
    </div>
  </div>
</div>
<?php 
$bao_gia = get_field('bao_gia');
if ($bao_gia): 
?>
<div class="peugeot-register container">
    <h2><?php echo esc_html($bao_gia['title']); ?></h2>
    <p><?php echo esc_html($bao_gia['desc']); ?></p>

    <div class="peugeot-register-boxes">
        <!-- Box trái -->
        <div class="peugeot-register-box">
            <?php if (!empty($bao_gia['left_image'])): ?>
                <img src="<?php echo esc_url($bao_gia['left_image']['url']); ?>" alt="" class="peugeot-baogia-img">
            <?php endif; ?>

            <h3><?php echo esc_html($bao_gia['left_title']); ?></h3>
            <p><?php echo esc_html($bao_gia['left_desc']); ?></p>

            <?php if (!empty($bao_gia['left_button'])): 
                $btn = $bao_gia['left_button']; ?>
                <a class="peugeot-btn" href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>">
                    <?php echo esc_html($btn['title']); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Box phải -->
        <div class="peugeot-register-box">
            <?php if (!empty($bao_gia['right_image'])): ?>
                <img src="<?php echo esc_url($bao_gia['right_image']['url']); ?>" alt=""  class="peugeot-baogia-img">
            <?php endif; ?>

            <h3><?php echo esc_html($bao_gia['right_title']); ?></h3>
            <p><?php echo esc_html($bao_gia['right_desc']); ?></p>

            <?php if (!empty($bao_gia['right_button'])): 
                $btn = $bao_gia['right_button']; ?>
                <a class="peugeot-btn" href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>">
                    <?php echo esc_html($btn['title']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<pre><?php print_r($bao_gia['left_button']); ?></pre>
<pre><?php print_r($bao_gia['right_button']); ?></pre>

<?php endif; ?>

<?php 
$gioi_thieu = get_field('gioi_thieu');

if ($gioi_thieu) : ?>
<section class="video-section text-center py-10">
  <?php if (!empty($gioi_thieu['title'])): ?>
    <h3 class="text-xl font-bold"><?php echo esc_html($gioi_thieu['title']); ?></h3>
  <?php endif; ?>

  <?php if (!empty($gioi_thieu['subtitle'])): ?>
    <h2 class="text-3xl text-blue-600 font-extrabold my-2">
      <?php echo esc_html($gioi_thieu['subtitle']); ?>
    </h2>
  <?php endif; ?>

  <?php if (!empty($gioi_thieu['desc'])): ?>
    <p class="text-gray-600 mb-6"><?php echo esc_html($gioi_thieu['desc']); ?></p>
  <?php endif; ?>

  <?php if (!empty($gioi_thieu['video_url'])): ?>
    <div class="video-wrapper" style="max-width:1200px;margin:0 auto;">
      <iframe width="100%" height="700"
        src="<?php echo esc_url($gioi_thieu['video_url']); ?>"
        title="YouTube video" frameborder="0" allowfullscreen></iframe>
    </div>
  <?php endif; ?>

  <?php
  // ==== GALLERY 1-4 ẢNH + DESC ====
  $items = [];
  for ($i = 1; $i <= 4; $i++) {
    $img_field  = $gioi_thieu['image' . $i] ?? null;
    $desc_field = $gioi_thieu['desc_image' . $i] ?? '';

    if (!empty($img_field)) {
      $img_id = null;
      if (is_numeric($img_field)) {
        $img_id = (int)$img_field;
      } elseif (is_array($img_field)) {
        $img_id = isset($img_field['ID']) ? (int)$img_field['ID'] : null;
        $img_url_fallback = $img_field['url'] ?? '';
        $img_alt_fallback = $img_field['alt'] ?? '';
      }

      if ($img_id) {
        $img_html = wp_get_attachment_image(
          $img_id,
          'large',
          false,
          [
            'class'   => 'img-fluid w-100 object-fit-cover',
            'loading' => 'lazy',
            'sizes'   => '(min-width:1200px) 25vw, (min-width:768px) 50vw, 100vw',
          ]
        );
      } else {
        $img_html = sprintf(
          '<img src="%s" alt="%s" class="img-fluid w-100 object-fit-cover" loading="lazy">',
          esc_url($img_url_fallback ?? ''),
          esc_attr($img_alt_fallback ?? '')
        );
      }

      $items[] = [
        'img_html' => $img_html,
        'desc'     => $desc_field,
      ];
    }
  }

  $n = count($items);
  if ($n > 0) :
    // Tính bề rộng cột trên màn lớn: 1 -> 12, 2 -> 6, 3 -> 4, 4+ -> 3
    $lgCols = 12 / min($n, 4);
    // Trên tablet, hiển thị 2 cột cho đẹp
    $mdCols = ($n === 1) ? 12 : 6;
  ?>
    <section class="container my-4">
      <div class="row g-3 justify-content-center">
        <?php foreach ($items as $item) : ?>
          <div class="col-12 col-md-<?php echo (int)$mdCols; ?> col-lg-<?php echo (int)$lgCols; ?>">
            <article class="card h-100 border-0 shadow-sm d-flex">
              <div class="ratio ratio-16x9">
                <?php echo $item['img_html']; ?>
              </div>
              <?php if (!empty($item['desc'])) : ?>
                <div class="p-3 d-flex">
                  <p class="fw-semibold mb-0"><?php echo esc_html($item['desc']); ?></p>
                </div>
              <?php endif; ?>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</section>
<?php endif; ?>
<?php 
$tinh_nang = get_field('tinh_nang');

if ($tinh_nang) : ?>
<section class="video-section text-center py-10">
  <?php if (!empty($tinh_nang['title'])): ?>
    <h3 class="text-xl font-bold"><?php echo esc_html($tinh_nang['title']); ?></h3>
  <?php endif; ?>

  <?php if (!empty($tinh_nang['subtitle'])): ?>
    <h2 class="text-3xl text-blue-600 font-extrabold my-2">
      <?php echo esc_html($tinh_nang['subtitle']); ?>
    </h2>
  <?php endif; ?>

  <?php if (!empty($tinh_nang['desc'])): ?>
    <div class="text-gray-600 mb-6 text-start mx-auto" style="max-width:800px">
      <?php echo apply_filters('the_content', $tinh_nang['desc']); ?>
    </div>
  <?php endif; ?>

  <?php
  // ==== GALLERY 1-4 ẢNH + DESC ====
  $items = [];
  for ($i = 1; $i <= 4; $i++) {
    $img_field  = $tinh_nang['image' . $i] ?? null;
    $desc_field = $tinh_nang['desc_image' . $i] ?? ''; // WYSIWYG

    if (!empty($img_field)) {
      $img_id = null;
      if (is_numeric($img_field)) {
        $img_id = (int)$img_field;
      } elseif (is_array($img_field)) {
        $img_id = isset($img_field['ID']) ? (int)$img_field['ID'] : null;
        $img_url_fallback = $img_field['url'] ?? '';
        $img_alt_fallback = $img_field['alt'] ?? '';
      }

      if ($img_id) {
        $img_html = wp_get_attachment_image(
          $img_id,
          'large',
          false,
          [
            'class'   => 'thumb-150',
            'loading' => 'lazy',
            'sizes'   => '(min-width:1200px) 25vw, (min-width:768px) 50vw, 100vw',
          ]
        );
      } else {
        $img_html = sprintf(
          '<img src="150px" alt="150px" class="thumb-150" loading="lazy">',
          esc_url($img_url_fallback ?? ''),
          esc_attr($img_alt_fallback ?? '')
        );
      }

      $items[] = [
        'img_html' => $img_html,
        'desc'     => $desc_field, // giữ nguyên HTML từ WYSIWYG
      ];
    }
  }

$n = count($items);
if ($n > 0) :
  $lgCols = 12 / min($n, 4); // 1:12, 2:6, 3:4, 4:3
  $mdCols = ($n === 1) ? 12 : 6;
?>
  <section class="container my-4">
    <div class="row g-3 justify-content-center">
      <?php foreach ($items as $item) : ?>
        <div class="col-12 col-md-<?php echo (int)$mdCols; ?> col-lg-<?php echo (int)$lgCols; ?>">
          <article class="h-100 text-center p-3">
  <div class="d-flex justify-content-center mb-3">
    <div class="thumb-wrapper">
      <?php echo $item['img_html']; ?>
    </div>
  </div>

  <?php if (!empty($item['desc'])) : ?>
    <div class="fw-bold">
      <?php echo apply_filters('the_content', $item['desc']); ?>
    </div>
  <?php endif; ?>
</article>

        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>
  </section>
<?php endif; ?>



<?php
$content6 = get_field('content6');
$slides = [];
for ($i = 1; $i <= 5; $i++) { // cho phép 5 màu
    if (!empty($content6['image' . $i])) {
        $slides[] = [
            'color' => $content6['color' . $i] ?? '',
            'image' => is_array($content6['image' . $i]) 
                ? $content6['image' . $i]['url'] 
                : wp_get_attachment_image_url($content6['image' . $i], 'full'),
        ];
    }
}
?>
<div class="peugeot-product-colors container">
    <h2 class="peugeot-product-colors-title">
        <?php echo esc_html($content6['title1']); ?>
    </h2>
    <h2 class="peugeot-product-colors-title">
        <?php echo esc_html($content6['title2']); ?>
    </h2>
    <p><?php echo esc_html($content6['number_of_colors']); ?></p>

    <!-- Ảnh xe chính -->
    <?php if (!empty($slides)): ?>
        <div class="peugeot-product-main">
            <img id="peugeot-main-img" src="<?php echo esc_url($slides[0]['image']); ?>" alt="Car color" />
        </div>
    <?php endif; ?>

    <!-- Danh sách màu -->
    <div class="peugeot-product-colors-list">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="peugeot-product-color-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                data-image="<?php echo esc_url($slide['image']); ?>" 
                style="background-color: <?php echo esc_attr($slide['color']); ?>;">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php 
$dich_vu = get_field('dich_vu');

if ($dich_vu) : ?>
<section class="video-section text-center py-10">
  <?php if (!empty($dich_vu['title'])): ?>
    <h3 class="text-xl font-bold"><?php echo esc_html($dich_vu['title']); ?></h3>
  <?php endif; ?>

  <?php if (!empty($dich_vu['subtitle'])): ?>
    <h2 class="text-3xl text-blue-600 font-extrabold my-2">
      <?php echo esc_html($dich_vu['subtitle']); ?>
    </h2>
  <?php endif; ?>

  <?php if (!empty($dich_vu['desc'])): ?>
    <div class="text-gray-600 mb-6 text-start mx-auto" style="max-width:800px">
      <?php echo apply_filters('the_content', $dich_vu['desc']); ?>
    </div>
  <?php endif; ?>

  <?php
  // ==== GALLERY 1–4: ẢNH (URL) + DESC ====
$items = [];
$fallback_img = get_theme_file_uri('/assets/images/placeholder-150.png'); // optional

for ($i = 1; $i <= 4; $i++) {
  $img_url   = trim($dich_vu['image' . $i] ?? '');      // bạn dán URL vào ACF
  $desc_html = $dich_vu['desc_image' . $i] ?? '';       // WYSIWYG

  // Nếu trống dùng fallback (có thể bỏ)
  if ($img_url === '' || !filter_var($img_url, FILTER_VALIDATE_URL)) {
    $img_url = $fallback_img;
  }

  // GIẢM KÍCH THƯỚC: set imwidth=300 (thay vì 1920)
  // add_query_arg sẽ thêm mới hoặc thay thế tham số nếu đã có
  $img_url = add_query_arg('imwidth', '300', $img_url);

  // ẢNH 150x150 (ép bằng CSS), thêm width/height để tránh layout shift
  $img_html = sprintf(
    '<img src="%s" alt="" class="thumb-150" loading="lazy" width="150" height="150">',
    esc_url($img_url)
  );

  if ($img_url || $desc_html !== '') {
    $items[] = ['img_html' => $img_html, 'desc' => $desc_html];
  }
}


$n = count($items);
if ($n > 0) :
  $lgCols = 12 / min($n, 4); // 1:12, 2:6, 3:4, 4:3
  $mdCols = ($n === 1) ? 12 : 6;
?>
  <section class="container my-4">
    <div class="row g-3 justify-content-center">
      <?php foreach ($items as $item) : ?>
        <div class="col-12 col-md-<?php echo (int)$mdCols; ?> col-lg-<?php echo (int)$lgCols; ?>">
          <article class="h-100 text-center p-3">
  <div class="d-flex justify-content-center mb-3">
    <div class="thumb-wrapper">
      <?php echo $item['img_html']; ?>
    </div>
  </div>

  <?php if (!empty($item['desc'])) : ?>
    <div class="fw-bold">
      <?php echo apply_filters('the_content', $item['desc']); ?>
    </div>
  <?php endif; ?>
</article>

        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>
  </section>
<?php endif; ?>

<div><?php the_content(); ?></div>
<?php
get_footer();
?>