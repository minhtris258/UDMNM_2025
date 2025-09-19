<?php
get_header();
?>

<main id="main" class="site-main" role="main">
    <header class="archive-header">
        <h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
    </header>

    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
    </article>
    <?php endwhile; ?>

    <div class="pagination">
        <?php echo paginate_links(); ?>
    </div>
    <?php else : ?>
    <p>No events found.</p>
    <?php endif; ?>
</main>

<?php
get_footer();
?>