<?php
get_header();
?>

<?php
$link1 = get_field('link1');
$link2 = get_field('link2');
$title_product = get_field('title_product');
$slider = get_field('slider_images');
$first_img = is_array($slider) ? reset($slider) : null;
$first_id  = 0;
$first_url = '';

if ($first_img) {
  if (is_array($first_img)) {
    $first_id  = isset($first_img['ID']) ? (int)$first_img['ID'] : 0;
    $first_url = $first_img['url'] ?? '';
    if (!$first_id && $first_url) $first_id = attachment_url_to_postid($first_url);
  } elseif (is_numeric($first_img)) {
    $first_id = (int)$first_img;
  }
  if ($first_id) {
    $src    = wp_get_attachment_image_url($first_id, 'large');
    $srcset = wp_get_attachment_image_srcset($first_id, 'large');
    echo '<link rel="preload" as="image" fetchpriority="high" imagesizes="100vw" ' .
      'href="' . esc_url($src) . '" ' . ($srcset ? 'imagesrcset="' . esc_attr($srcset) . '"' : '') . ' />' . "\n";
  }
}
if ($slider) : ?>
<div class="peugeot-slider">
    <div class="peugeot-slider-wrapper">
        <?php
      $k = 0;
      foreach ($slider as $img) {
        $id  = 0;
        $alt = '';
        $url = '';

        if (is_array($img)) {
          $id  = isset($img['ID']) ? (int)$img['ID'] : 0;
          $alt = $img['alt'] ?? '';
          $url = $img['url'] ?? '';
          if (!$id && $url) $id = attachment_url_to_postid($url);
        } elseif (is_numeric($img)) {
          $id = (int)$img;
        }

        echo '<div class="peugeot-slide">';
        if ($id) {
          echo wp_get_attachment_image($id, 'large', false, [
            'class'          => 'peugeot-slide-img',
            'loading'        => $k === 0 ? 'eager' : 'lazy',
            'fetchpriority'  => $k === 0 ? 'high'  : 'low',
            'decoding'       => 'async',
            'sizes'          => '100vw',
          ]);
        } elseif ($url) {
          // fallback URL thô
          printf(
            '<img src="%s" alt="%s" class="peugeot-slide-img" %s decoding="async" width="1920" height="1080">',
            esc_url($url),
            esc_attr($alt),
            $k === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'
          );
        }
        echo '</div>';
        $k++;
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
  $img = $content1['image_content' . $i] ?? null;
  $img_id = 0;
  $img_url = '';
  $img_alt = '';

  if (is_array($img)) {
    $img_id  = isset($img['ID']) ? (int)$img['ID'] : 0;
    $img_url = $img['url'] ?? '';
    $img_alt = $img['alt'] ?? '';
    if (!$img_id && $img_url) $img_id = attachment_url_to_postid($img_url);
  } elseif (is_numeric($img)) {
    $img_id = (int)$img;
  }

  if ($img_id || $img_url) {
    $slides[] = [
      'title'   => (string)($content1['text' . $i] ?? ''),
      'content' => (string)($content1['text_content' . $i] ?? ''),
      'img_id'  => $img_id,
      'img_url' => $img_url,
      'img_alt' => $img_alt,
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
        <?php if (!empty($slide['img_id'])): ?>
        <?php echo wp_get_attachment_image($slide['img_id'], 'large', false, [
          'class'         => 'peugeot-slider-img',
          'loading'       => $idx === 0 ? 'eager' : 'lazy',
          'fetchpriority' => $idx === 0 ? 'high'  : 'low',
          'decoding'      => 'async',
          'sizes'         => '100vw',
          'alt'           => $slide['title'],
        ]); ?>
        <?php else: ?>
        <img src="<?php echo esc_url($slide['img_url']); ?>" alt="<?php echo esc_attr($slide['title']); ?>"
            class="peugeot-slider-img"
            <?php echo $idx === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async"
            width="1920" height="1080" />
        <?php endif; ?>


        <?php if ($idx === 0): // chỉ in NAV 1 lần trong slide đầu 
      ?>
        <div class="peugeot-slider-nav">
            <?php foreach ($slides as $j => $s): ?>
            <span class="peugeot-slider-nav-item<?php echo $j === 0 ? ' active' : ''; ?>"
                data-slide="<?php echo $j; ?>">
                <?php echo esc_html($s['title']); ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="peugeot-slider-content">
            <?php echo wp_kses_post($slide['content']); ?>
            <div class="peugeot-buttons text-center mb-5 py-3">

                <a class="peugeot-btn-primary" href="<?php echo esc_url($link2['url']); ?>"
                    target="<?php echo esc_attr($link2['target']); ?>">
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
          'autoplay' => false,  // đổi true nếu muốn tự phát
        ]);
        ?>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>


<?php
$content3 = get_field('content3');
$slides_c3 = [];
for ($i = 1; $i <= 2; $i++) {
  $img = $content3['image_content' . $i] ?? null;
  $img_id = 0;
  $img_url = '';
  $img_alt = '';

  if (is_array($img)) {
    $img_id  = isset($img['ID']) ? (int)$img['ID'] : 0;
    $img_url = $img['url'] ?? '';
    $img_alt = $img['alt'] ?? '';
    if (!$img_id && $img_url) $img_id = attachment_url_to_postid($img_url);
  } elseif (is_numeric($img)) {
    $img_id = (int)$img;
  }

  if ($img_id || $img_url) {
    $slides_c3[] = [
      'title'   => (string)($content3['text' . $i] ?? ''),
      'content' => (string)($content3['text_content' . $i] ?? ''),
      'img_id'  => $img_id,
      'img_url' => $img_url,
      'img_alt' => $img_alt,
    ];
  }
}
?>

<div class="peugeot-slider peugeot-slider3" id="peugeot-slider3">
    <div class="peugeot-slider-row">
        <!-- Cột ảnh (60%) -->
        <div class="peugeot-slider-col image-col">
            <?php foreach ($slides_c3 as $idx => $slide): ?>
            <div class="peugeot-slider3-slide<?php echo $idx === 0 ? ' active' : ''; ?>">
                <?php if (!empty($slide['img_id'])): ?>
                <?php echo wp_get_attachment_image($slide['img_id'], 'large', false, [
              'class'         => 'peugeot-slider3-img',
              'loading'       => $idx === 0 ? 'eager' : 'lazy',
              'fetchpriority' => $idx === 0 ? 'high'  : 'low',
              'decoding'      => 'async',
              'sizes'         => '100vw',
              'alt'           => $slide['title'],
            ]); ?>
                <?php else: ?>
                <img src="<?php echo esc_url($slide['img_url']); ?>" alt="<?php echo esc_attr($slide['title']); ?>"
                    class="peugeot-slider3-img"
                    <?php echo $idx === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>
                    decoding="async" width="1200" height="675">
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Cột nội dung (40%) -->
        <div class="peugeot-slider-col content-col">
            <div class="peugeot-slider3-nav">
                <?php foreach ($slides_c3 as $idx => $slide): ?>
                <span class="peugeot-slider3-nav-item<?php echo $idx === 0 ? ' active' : ''; ?>"
                    data-slide="<?php echo $idx; ?>">
                    <?php echo esc_html($slide['title']); ?>
                </span>
                <?php endforeach; ?>
            </div>

            <div class="peugeot-slider3-contents">
                <?php foreach ($slides_c3 as $idx => $slide): ?>
                <div class="peugeot-slider3-content<?php echo $idx === 0 ? ' active' : ''; ?>">
                    <div class="peugeot-slider3-text">
                        <?php echo wp_kses_post($slide['content']); ?>
                    </div>
                    <div class="peugeot-slider3-buttons">
                        <?php if (!empty($link1['url'])): ?>
                        <a class="peugeot-btn" href="<?php echo esc_url($link1['url']); ?>"
                            target="<?php echo esc_attr($link1['target'] ?? ''); ?>">
                            <?php echo esc_html($link1['title'] ?? 'Xem thêm'); ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($link2['url'])): ?>
                        <a class="peugeot-btn" href="<?php echo esc_url($link2['url']); ?>"
                            target="<?php echo esc_attr($link2['target'] ?? ''); ?>">
                            <?php echo esc_html($link2['title'] ?? 'Xem thêm'); ?>
                        </a>
                        <?php endif; ?>
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
                <?php
            $thumb_id = get_post_thumbnail_id(get_the_ID());
            if ($thumb_id) {
              echo wp_get_attachment_image($thumb_id, 'medium_large', false, [
                'class'    => 'xe-thumb-img',
                'loading'  => 'lazy',
                'decoding' => 'async',
                'sizes'    => '(min-width:1200px) 25vw, (min-width:768px) 33vw, 50vw',
              ]);
            } elseif ($img_url) {
              echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '" class="xe-thumb-img" loading="lazy" decoding="async" width="800" height="450">';
            } else {
              echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/img/placeholder-16x9.jpg') . '" alt="" class="xe-thumb-img" loading="lazy" decoding="async" width="800" height="450">';
            }
            ?>
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
<?php endif;
wp_reset_postdata(); ?>

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
            '<img src="%s" alt="%s" class="thumb-150" loading="lazy" width="150" height="150">',
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
  // Helper lấy ID ảnh từ ACF (array|int|null)
  $get_img_id = function ($img) {
    if (empty($img)) return 0;
    if (is_numeric($img)) return (int) $img;         // ACF return = ID
    if (is_array($img) && !empty($img['ID'])) return (int) $img['ID']; // ACF return = Array
    return 0;
  };

  $left_id  = $get_img_id($content5['left_image']  ?? null);
  $right_id = $get_img_id($content5['right_image'] ?? null);

  // Texts & buttons
  $left_title  = $content5['left_title']  ?? '';
  $left_desc   = $content5['left_desc']   ?? '';
  $left_btn    = $content5['left_button'] ?? null;

  $right_title = $content5['right_title'] ?? '';
  $right_desc  = $content5['right_desc']  ?? '';
  $right_btn   = $content5['right_button'] ?? null;
?>
<div class="peugeot-register container">
    <div class="peugeot-register-boxes">
        <!-- Box trái -->
        <div class="peugeot-register-box">
            <?php
        if ($left_id) {
          echo wp_get_attachment_image(
            $left_id,
            'baogia-card',
            false,
            [
              'class'         => 'peugeot-baogia-img',
              'loading'       => 'lazy',
              'decoding'      => 'async',
              'fetchpriority' => 'low',   // đổi 'high' nếu là ảnh LCP duy nhất
            ]
          );
        }
        ?>
            <div class="peugeot-box-title">
                <h3><?php echo esc_html($left_title); ?></h3>
            </div>
            <div class="peugeot-box-desc">
                <p><?php echo esc_html($left_desc); ?></p>
            </div>
            <?php if (!empty($left_btn['url'])): ?>
            <a class="peugeot-btn" href="<?php echo esc_url($left_btn['url']); ?>"
                target="<?php echo esc_attr($left_btn['target'] ?? '_self'); ?>">
                <?php echo esc_html($left_btn['title'] ?? ''); ?>
            </a>
            <?php endif; ?>
        </div>

        <!-- Box phải -->
        <div class="peugeot-register-box">
            <?php
        if ($right_id) {
          echo wp_get_attachment_image(
            $right_id,
            'baogia-card',
            false,
            [
              'class'         => 'peugeot-baogia-img',
              'loading'       => 'lazy',
              'decoding'      => 'async',
              'fetchpriority' => 'low',
            ]
          );
        }
        ?>
            <div class="peugeot-box-title">
                <h3><?php echo esc_html($right_title); ?></h3>
            </div>
            <div class="peugeot-box-desc">
                <p><?php echo esc_html($right_desc); ?></p>
            </div>
            <?php if (!empty($right_btn['url'])): ?>
            <a class="peugeot-btn" href="<?php echo esc_url($right_btn['url']); ?>"
                target="<?php echo esc_attr($right_btn['target'] ?? '_self'); ?>">
                <?php echo esc_html($right_btn['title'] ?? ''); ?>
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
    $thumb_id = get_post_thumbnail_id(get_the_ID());
    $img_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'large')
      : get_template_directory_uri() . '/assets/img/placeholder-16x9.jpg';
    $slides[] = [
      'content1' => get_the_title(),
      'content2' => wpautop(wp_kses_post(wp_trim_words(get_the_excerpt(), 40, esc_html__('…', 'peugeot-theme')))) .
        '<p><a class="peugeot-slider-btn" href="' . esc_url(get_permalink()) . '">' .
        esc_html__('Đọc tiếp', 'peugeot-theme') . '</a></p>',
      'img_id'   => $thumb_id,
      'image'    => $img_url,
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
            <div class="peugeot-slider4-slide<?php echo $idx === 0 ? ' active' : ''; ?>"
                data-slide="<?php echo (int) $idx; ?>">
                <?php if (!empty($slide['img_id'])): ?>
                <?php echo wp_get_attachment_image($slide['img_id'], 'large', false, [
                'class'         => 'peugeot-slider4-img',
                'loading'       => $idx === 0 ? 'eager' : 'lazy',
                'fetchpriority' => $idx === 0 ? 'high'  : 'low',
                'decoding'      => 'async',
                'sizes'         => '100vw',
                'alt'           => $slide['alt'],
              ]); ?>
                <?php else: ?>
                <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['alt']); ?>"
                    class="peugeot-slider4-img"
                    <?php echo $idx === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; ?>
                    decoding="async" width="1200" height="675">
                <?php endif; ?>

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
                <div class="peugeot-slider4-content<?php echo $idx === 0 ? ' active' : ''; ?>"
                    data-slide="<?php echo (int) $idx; ?>">
                    <h3 class="peugeot-slider4-nav-item"><?php echo esc_html($slide['content1']); ?></h3>
                    <div class="peugeot-slider4-text">
                        <?php echo $slide['content2']; // đã kses ở trên 
                ?>
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