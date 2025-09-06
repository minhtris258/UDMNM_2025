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

<div><?php the_content(); ?></div>
<?php
get_footer();
?>