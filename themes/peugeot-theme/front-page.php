<?php
get_header();
?>

<?php 
$link1 = get_field('link1');
$link2 = get_field('link2');
$title_product = get_field('title_product');
$slider = get_field('slider_images');
if ($slider) : ?>
    <div class="peugeot-slider">
        <div class="peugeot-slider-wrapper">
            <?php 
            foreach ($slider as $img) {
                if (!empty($img)) {
                    if (is_array($img) && isset($img['url'])) {
                        echo '<div class="peugeot-slide">
                                <img src="' . esc_url($img['url']) . '" alt="' . esc_attr($img['alt'] ?? '') . '" />
                              </div>';
                    } elseif (is_numeric($img)) {
                        echo '<div class="peugeot-slide">
                                <img src="' . esc_url(wp_get_attachment_image_url($img, 'full')) . '" alt="" />
                              </div>';
                    }
                }
            }
            ?>
        </div>
        <div class="peugeot-slider-nav-zone left"></div>
        <div class="peugeot-slider-nav-zone right"></div>
    </div>
<?php endif; ?>

<?php
$content1 = get_field('content1');
$slides = [];
for ($i = 1; $i <= 3; $i++) {
    if (!empty($content1['image_content' . $i])) {
        $slides[] = [
            'title' => $content1['text' . $i] ?? '',
            'content' => $content1['text_content' . $i] ?? '',
            'image' => is_array($content1['image_content' . $i]) ? $content1['image_content' . $i]['url'] : wp_get_attachment_image_url($content1['image_content' . $i], 'full'),
        ];
    }
}
?>
<section class="peugeot-content-block">
    <h2><?php echo esc_html($content1['title1']); ?></h2>
</section>
<div class="peugeot-slider" id="peugeot-slider" data-min-h="0">
  <?php foreach ($slides as $idx => $slide): ?>
    <div class="peugeot-slider-slide<?php echo $idx === 0 ? ' active' : ''; ?>">
      <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" class="peugeot-slider-img" />

      <?php if ($idx === 0): // chỉ in NAV 1 lần trong slide đầu ?>
        <div class="peugeot-slider-nav">
          <?php foreach ($slides as $j => $s): ?>
            <span class="peugeot-slider-nav-item<?php echo $j === 0 ? ' active' : ''; ?>" data-slide="<?php echo $j; ?>">
              <?php echo esc_html($s['title']); ?>
            </span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="peugeot-slider-content">
        <?php echo wp_kses_post($slide['content']); ?>
        <div class="peugeot-buttons text-center mb-5 py-3">

            <a class="peugeot-btn-primary" href="<?php echo esc_url($link2['url']); ?>" target="<?php echo esc_attr($link2['target']); ?>">
                <?php echo esc_html($link2['title']); ?>
            </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <button class="peugeot-slider-arrow left">&#10094;</button>
  <button class="peugeot-slider-arrow right">&#10095;</button>
</div>

<?php 
$content2 = get_field('content2');

if ($content2) : ?>
  <section class="video-section text-center py-5">
    <?php if (!empty($content2['title'])): ?>
      <h1 class="text-xl font-weight-bold"><?php echo esc_html($content2['title']); ?></h1>
    <?php endif; ?>

    <?php if (!empty($content2['subtitle'])): ?>
      <h1 class="text-3xl text-primary font-extrabold my-2 py-3 font-weight-bold">
        <?php echo esc_html($content2['subtitle']); ?>
      </h1>
    <?php endif; ?>

    <?php if (!empty($content2['description'])): ?>
      <p class="text-gray-600 mb-6"><?php echo esc_html($content2['description']); ?></p>
    <?php endif; ?>

    <?php if (!empty($content2['youtube_url'])): ?>
      <div class="video-wrapper py-3" style="max-width:1200px;margin:0 auto;">
        <?php
          echo pg_youtube_privacy_iframe($content2['youtube_url'], [
            'width'   => 1200,
            'height'  => 700,
            'lazy'    => true,   // bật lazy-load
            'autoplay'=> false,  // đổi true nếu muốn tự phát
          ]);
        ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>


<?php
$content3 = get_field('content3');
$slides = [];
for ($i = 1; $i <= 2; $i++) {
    if (!empty($content3['image_content' . $i])) {
        $slides[] = [
            'title' => $content3['text' . $i] ?? '',
            'content' => $content3['text_content' . $i] ?? '',
            'image' => is_array($content3['image_content' . $i]) ? $content3['image_content' . $i]['url'] : wp_get_attachment_image_url($content3['image_content' . $i], 'full'),
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

                            <a class="peugeot-btn" href="<?php echo esc_url($link1['url']); ?>" target="<?php echo esc_attr($link1['target']); ?>">
                    <?php echo esc_html($link1['title']); ?>
                </a>
                           <a class="peugeot-btn" href="<?php echo esc_url($link2['url']); ?>" target="<?php echo esc_attr($link2['target']); ?>">
                    <?php echo esc_html($link2['title']); ?>
                </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php
$args = [
  'post_type'      => 'san-pham',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
];
$q = new WP_Query($args);

if ($q->have_posts()): ?>
  <section class="peugeot-content-block">
    <h2><?php echo esc_html($title_product); ?></h2>
  <div class="bang-gia-xe">
    <?php while ($q->have_posts()): $q->the_post();
      $thong_tin   = get_field('thong_tin_xe');
      // 1) Ảnh đại diện của bài
      $img_url     = get_the_post_thumbnail_url(get_the_ID(), 'large');
      // 2) fallback ACF nếu không có ảnh đại diện
      if (!$img_url && !empty($thong_tin['hinh_anh']['url'])) {
        $img_url = $thong_tin['hinh_anh']['url'];
      }
      // 3) Link chi tiết: ưu tiên ACF, không có thì permalink
      $detail_link = !empty($thong_tin['link_chi_tiet']) ? $thong_tin['link_chi_tiet'] : get_permalink();
    ?>
      <div class="xe-item">
        <div class="xe-thumb">
          <?php if ($img_url): ?>
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
          <?php else: ?>
            <img src="<?php echo esc_url(get_template_directory_uri().'/assets/img/placeholder-16x9.jpg'); ?>" alt="">
          <?php endif; ?>
        </div>

        <h3 class="xe-title"><?php the_title(); ?></h3>

        <?php
            $price     = get_field('price');      // Text
            $price_sub = get_field('price_sub');  // Text
            ?>

            <?php if (!empty($price)): ?>
              <p class="xe-gia"><?php echo esc_html($price); ?></p>
            <?php endif; ?>

            <?php if (!empty($price_sub)): ?>
              <p class="xe-gia-sub"><?php echo esc_html($price_sub); ?></p>
            <?php endif; ?>

        <a href="<?php echo esc_url($detail_link); ?>" class="btn-xem">
  <?php printf(esc_html__('Xem chi tiết %s', 'peugeot-theme'), esc_html(get_the_title())); ?>
</a>
      </div>
    <?php endwhile; ?>
  </div>
  <?php
// Link archive CPT san-pham
$archive_link = get_post_type_archive_link('san-pham');
// Fallback nếu CPT chưa có has_archive
if (!$archive_link) {
  $archive_link = home_url('/san-pham/');
}
?>
<div class="xem-tat-ca-wrap">
<a class="btn-xem-tat-ca" href="<?php echo esc_url($archive_link); ?>">
  <?php echo esc_html__('Xem tất cả', 'peugeot-theme'); ?>
</a>
</div>
</section>
<?php endif; wp_reset_postdata(); ?>

<?php 
$content4 = get_field('content4');

if ($content4) : ?>
<section class="video-section text-center py-5">
  <div class="peugeot-title">
  <?php if (!empty($content4['title'])): ?>
    <h1 class="text-xl font-bold"><?php echo esc_html($content4['title']); ?></h1>
  <?php endif; ?>

  <?php if (!empty($content4['subtitle'])): ?>
    <h2 class="text-3xl text-blue-600 font-extrabold my-2">
      <?php echo esc_html($content4['subtitle']); ?>
    </h2>
  <?php endif; ?>

  <?php if (!empty($content4['desc'])): ?>
    <div class="text-gray-600 mb-6 text-center mx-auto">
      <?php echo apply_filters('the_content', $content4['desc']); ?>
    </div>
  <?php endif; ?>
</div>
  <?php
  // ==== GALLERY 1-4 ẢNH + DESC ====
  $items = [];
  for ($i = 1; $i <= 4; $i++) {
    $img_field  = $content4['image' . $i] ?? null;
    $desc_field = $content4['desc_image' . $i] ?? ''; // WYSIWYG

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
        <div class="col-3 col-md-<?php echo (int)$mdCols; ?> col-lg-<?php echo (int)$lgCols; ?>">
          <article class="h-100 text-center p-3">
  <div class="d-flex justify-content-center mb-3">
    <div class="thumb-wrapper">
      <?php echo $item['img_html']; ?>
    </div>
  </div>

  <?php if (!empty($item['desc'])) : ?>
    <div class="justify-content-center peugeot-desc-mobile">
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
$content5 = get_field('content5');
if ($content5): 
?>
<div class="peugeot-register container">
   
    <div class="peugeot-register-boxes">
        <!-- Box trái -->
        <div class="peugeot-register-box">
            <?php if (!empty($content5['left_image'])): ?>
                <img src="<?php echo esc_url($content5['left_image']['url']); ?>" alt="" class="peugeot-baogia-img">
            <?php endif; ?>
              <div class="peugeot-box-title">
            <h3><?php echo esc_html($content5['left_title']); ?></h3>
            </div>
            <div class="peugeot-box-desc">
            <p><?php echo esc_html($content5['left_desc']); ?></p>
            </div>

            <?php if (!empty($content5['left_button'])): 
                $btn = $content5['left_button']; ?>
                <a class="peugeot-btn" href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>">
                    <?php echo esc_html($btn['title']); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Box phải -->
        <div class="peugeot-register-box">
            <?php if (!empty($content5['right_image'])): ?>
                <img src="<?php echo esc_url($content5['right_image']['url']); ?>" alt=""  class="peugeot-baogia-img">
            <?php endif; ?>
              <div class="peugeot-box-title">
            <h3><?php echo esc_html($content5['right_title']); ?></h3>
            </div>
            <div class="peugeot-box-desc">
              <p><?php echo esc_html($content5['right_desc']); ?></p>
            </div>

            <?php if (!empty($content5['right_button'])): 
                $btn = $content5['right_button']; ?>
                <a class="peugeot-btn" href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>">
                    <?php echo esc_html($btn['title']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endif; ?>

<?php
// Lấy 5 bài mới nhất từ category 'tin-tuc'
$news_q = new WP_Query([
  'post_type'           => 'tin-tuc',
  'category_name'       => '',
  'posts_per_page'      => 5,
  'ignore_sticky_posts' => true,
]);

$slides = [];
if ($news_q->have_posts()) {
  while ($news_q->have_posts()) {
    $news_q->the_post();
    $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
    if (!$img) {
      // fallback ảnh trống (có thể thay bằng ảnh placeholder của bạn)
      $img = get_template_directory_uri() . '/assets/img/placeholder-16x9.jpg';
    }
    $slides[] = [
      'content1' => get_the_title(),
      'content2' => wpautop( wp_kses_post( wp_trim_words( get_the_excerpt(), 40, esc_html__('…','peugeot-theme') ) ) ) .
              '<p><a class="peugeot-slider-btn" href="'. esc_url( get_permalink() ) .'">'.
                esc_html__('Đọc tiếp','peugeot-theme').
              '</a></p>',
      'image'    => $img,
      'alt'      => get_the_title(),
    ];
  }
  wp_reset_postdata();
}
?>

<?php if (!empty($slides)): ?>
<div class="peugeot-slider peugeot-slider4" id="peugeot-slider4-news">
  <div class="peugeot-slider-row">

    

    <!-- Cột ảnh (60%) -->
    <div class="peugeot-slider-col image-col">
      <?php foreach ($slides as $idx => $slide): ?>
        <div class="peugeot-slider4-slide<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo (int) $idx; ?>">
          <img src="<?php echo esc_url($slide['image']); ?>"
               alt="<?php echo esc_attr($slide['alt']); ?>"
               class="peugeot-slider4-img" />
        </div>
      <?php endforeach; ?>

      <!-- Nút điều hướng mũi tên -->
      <button class="peugeot-slider-arrow prev" type="button">&#10094;</button>
      <button class="peugeot-slider-arrow next" type="button">&#10095;</button>
    </div>
<!-- Cột nội dung (40%) -->
    <div class="peugeot-slider-col content-col">
      <!-- Thanh số slide -->
      <div class="peugeot-slider4-counter">
        <button class="peugeot-slider4-arrow small prev" type="button">&#10094;</button>
        <span id="current-slide">1</span> / <span id="total-slides"><?php echo (int) count($slides); ?></span>
        <button class="peugeot-slider4-arrow small next" type="button">&#10095;</button>
      </div>

      <!-- Nội dung -->
      <div class="peugeot-slider4-contents">
        <?php foreach ($slides as $idx => $slide): ?>
          <div class="peugeot-slider4-content<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo (int) $idx; ?>">
            <h3 class="peugeot-slider4-nav-item"><?php echo esc_html($slide['content1']); ?></h3>
            <div class="peugeot-slider4-text">
              <?php echo $slide['content2']; // đã kses ở trên ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php else: ?>
  <p>
  <?php
    printf(
      esc_html__('Chưa có bài viết nào trong chuyên mục %s.', 'peugeot-theme'),
      '<em>tin-tuc</em>'
    );
  ?>
</p>
<?php endif; ?>


<?php get_footer(); ?>