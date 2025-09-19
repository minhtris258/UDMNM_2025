<?php
/**
 * Template Name: Menu Settings (Mega Panel)
 * Description: Trang cài đặt dữ liệu cho mega panel (40% bên phải)
 */
get_header(); ?>

<main id="primary" class="site-main container py-5">
    <h1 class="mb-3"><?php the_title(); ?></h1>
    <p>Nhập dữ liệu ACF cho group <code>mega_panel</code> ở dưới (Ảnh, Link dưới ảnh, CTA 1/2...).</p>

    <?php
  while ( have_posts() ) : the_post();
    the_content();
  endwhile;
  ?>
</main>

<?php get_footer();