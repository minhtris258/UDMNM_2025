<?php
get_header();
?>

<main id="main" class="site-main" role="main">
    <header class="archive-header">
        <h1 class="archive-title"><?php the_archive_title(); ?></h1>
        <div class="archive-description"><?php the_archive_description(); ?></div>
    </header>

    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
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
    <p>No posts found.</p>
    <?php endif; ?>
</main>

<?php
get_footer();
?>