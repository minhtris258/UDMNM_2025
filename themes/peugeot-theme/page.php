<?php
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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