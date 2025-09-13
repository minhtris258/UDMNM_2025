<?php
get_header();
?>

<?php 
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
<div class="peugeot-slider" id="peugeot-slider">
    <?php foreach ($slides as $idx => $slide): ?>
    <div class="peugeot-slider-slide<?php echo $idx === 0 ? ' active' : ''; ?>">
        <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" class="peugeot-slider-img" />
        <div class="peugeot-slider-content">
            <?php echo wp_kses_post($slide['content']); ?>
            <button class="peugeot-slider-btn">ĐẶT LỊCH LÁI THỬ</button>
        </div>
    </div>
    <?php endforeach; ?>

    <button class="peugeot-slider-arrow left">&#10094;</button>
    <button class="peugeot-slider-arrow right">&#10095;</button>
    <div class="peugeot-slider-nav">
        <?php foreach ($slides as $idx => $slide): ?>
            <span class="peugeot-slider-nav-item<?php echo $idx === 0 ? ' active' : ''; ?>" data-slide="<?php echo $idx; ?>">
                <?php echo esc_html($slide['title']); ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>
<?php 
$content2 = get_field('content2');

if ($content2) : ?>
    <section class="video-section text-center py-10">
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
                <iframe width="100%" height="700" 
                        src="<?php echo esc_url($content2['youtube_url']); ?>" 
                        title="YouTube video" frameborder="0" allowfullscreen>
                </iframe>
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
$args = [
  'post_type'      => 'san-pham',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
];
$q = new WP_Query($args);

if ($q->have_posts()): ?>
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

        <?php if (!empty($thong_tin['gia'])): ?>
          <p class="xe-gia">Từ <?php echo esc_html($thong_tin['gia']); ?> triệu đồng</p>
          <div class="xe-note">Giá niêm yết <span class="i">i</span></div>
        <?php endif; ?>

        <a href="<?php echo esc_url($detail_link); ?>" class="btn-xem">
          Xem chi tiết <?php echo esc_html(get_the_title()); ?>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; wp_reset_postdata(); ?>


<?php
// Lấy 5 bài mới nhất từ category 'tin_tuc'
$news_q = new WP_Query([
  'post_type'           => 'post',
  'category_name'       => 'tin-tuc',
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
      'content2' => wpautop( wp_kses_post( wp_trim_words( get_the_excerpt(), 40, '…' ) ) ) .
                    '<p><a class="peugeot-slider-btn" href="'. esc_url( get_permalink() ) .'">ĐỌC TIẾP</a></p>',
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
  <p>Chưa có bài viết nào trong chuyên mục <em>tin_tuc</em>.</p>
<?php endif; ?>


<?php get_footer(); ?>