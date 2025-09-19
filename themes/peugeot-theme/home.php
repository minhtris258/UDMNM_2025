<?php

/**
 * Template Name: trang chủ
 * Description: Trang chủ của theme Peugeot
 */
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
        <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>"
            class="peugeot-slider-img" />
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
        <span class="peugeot-slider-nav-item<?php echo $idx === 0 ? ' active' : ''; ?>"
            data-slide="<?php echo $idx; ?>">
            <?php echo esc_html($slide['title']); ?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
<?php
$content2 = get_field('content2');

if ($content2) : ?>
<section class="video-section text-center py-4">
    <?php if (!empty($content2['title'])): ?>
    <h3 class="text-xl font-bold"><?php echo esc_html($content2['title']); ?></h3>
    <?php endif; ?>

    <?php if (!empty($content2['subtitle'])): ?>
    <h2 class="text-3xl text-blue-600 font-extrabold my-2">
        <?php echo esc_html($content2['subtitle']); ?>
    </h2>
    <?php endif; ?>

    <?php if (!empty($content2['description'])): ?>
    <p class="text-gray-600 mb-6"><?php echo esc_html($content2['description']); ?></p>
    <?php endif; ?>

    <?php if (!empty($content2['youtube_url'])): ?>
    <div class="video-wrapper" style="max-width:800px;margin:0 auto;">
        <iframe width="100%" height="450" src="<?php echo esc_url($content2['youtube_url']); ?>" title="YouTube video"
            frameborder="0" allowfullscreen>
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
                <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>"
                    class="peugeot-slider3-img" />
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Cột nội dung (40%) -->
        <div class="peugeot-slider-col content-col">
            <!-- Nav -->
            <div class="peugeot-slider3-nav">
                <?php foreach ($slides as $idx => $slide): ?>
                <span class="peugeot-slider3-nav-item<?php echo $idx === 0 ? ' active' : ''; ?>"
                    data-slide="<?php echo $idx; ?>">
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

<?php get_footer(); ?>