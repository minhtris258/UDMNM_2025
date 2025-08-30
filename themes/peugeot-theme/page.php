<?php
get_header();
?>
<?php 
$slider = get_field('slider_images'); // group field

if ($slider) : ?>
    <div class="peugeot-slider">
        <div class="peugeot-slider-wrapper">
            <?php 
            foreach ($slider as $img) {
                if (!empty($img)) {
                    // Nếu return format = Array
                    if (is_array($img) && isset($img['url'])) {
                        echo '<div class="peugeot-slide">
                                <img src="' . esc_url($img['url']) . '" alt="' . esc_attr($img['alt'] ?? '') . '" />
                              </div>';
                    }
                    // Nếu return format = ID
                    elseif (is_numeric($img)) {
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

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="container my-5">
        <h1 class="mb-4"><?php the_title(); ?></h1>
        <div>
            <?php the_content(); ?>
        </div>
    </div>
<?php endwhile; endif; ?>

<?php
get_footer();
?>