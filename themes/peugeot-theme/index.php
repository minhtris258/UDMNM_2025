<?php
get_header();
?>

<div class="container my-5">
    <h1 class="mb-4">Welcome to our blog!</h1>
    <?php if (have_posts()) : ?>
        <div class="row">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', ['class' => 'card-img-top', 'alt' => get_the_title()]); ?>
                            </a>
                        <?php endif; ?>
                        <div class="card-body">
                            <h2 class="card-title h5">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                            <a class="btn btn-primary btn-sm" href="<?php the_permalink(); ?>">Continue reading &raquo;</a>
                        </div>
                        <div class="card-footer small text-muted">
                            Posted by <?php the_author_posts_link(); ?> on <?php the_time('M j, Y'); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-4">
            <?php echo paginate_links(); ?>
        </div>
    <?php else : ?>
        <p>No posts found.</p>
    <?php endif; ?>
</div>

<?php
get_footer();
?>