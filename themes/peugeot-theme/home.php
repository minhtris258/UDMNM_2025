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
    <div class="peugeot-slider-slide<?php echo $idx === 0 ? ' active' : ''; ?>" style="background-image:url('<?php echo esc_url($slide['image']); ?>');">
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

<?php get_footer(); ?>