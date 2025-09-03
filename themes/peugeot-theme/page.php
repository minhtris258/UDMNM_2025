<?php get_header(); ?>

<main id="page-content" class="container">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <div class="content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile;
    else :
        echo '<p>Không tìm thấy nội dung.</p>';
    endif;
    ?>
</main>

<?php get_footer(); ?>
