<?php
get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

    // Lấy ảnh bìa (featured image)
    $banner_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if ($banner_url): ?>
<div class="peugeot-banner">
    <img src="<?php echo esc_url($banner_url); ?>" alt="<?php the_title_attribute(); ?>">
</div>
<?php endif; ?>
<div class="container">
    <h1 class="peugeot-post-title"><?php the_title(); ?></h1>
    <div class="peugeot-post-content"><?php the_content(); ?></div>
</div>

<?php
endwhile; // End of the loop.

get_footer();