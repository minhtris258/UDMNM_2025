<?php
get_header();

// Lấy banner từ trang cài đặt
$settings_id = pg_get_settings_page_id_by_template('page-archive-settings-tin-tuc.php');
$banner = $settings_id ? get_field('image', $settings_id) : null;
$text_banner  = $settings_id ? get_field('text_banner', $settings_id) : null;

$banner_title = is_array($text_banner) ? ($text_banner['title'] ?? '') : '';
$banner_sub   = is_array($text_banner) ? ($text_banner['sub']   ?? '') : '';
// Tiêu đề archive
$title = get_the_archive_title();
$title = preg_replace('/^(Category:|Tag:|Archives:|Author:|Year:)\s*/', '', $title);
$title = strip_tags($title);
?>

<?php if (!empty($banner['url'])): ?>
  <div class="archive-banner">
    <img src="<?php echo esc_url($banner['url']); ?>" alt="<?php echo esc_attr($banner['alt'] ?? ''); ?>" />
    <div class="archive-banner-overlay">
      <h1 class="archive-title"><?php echo esc_html($banner_title ?: $title); ?></h1>
      <?php if ($banner_sub): ?><p class="archive-sub"><?php echo esc_html($banner_sub); ?></p><?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<main id="main" class="site-main container">
    
    <!-- Tabs filter -->
    <div class="archive-tabs">
        <a href="<?php echo get_post_type_archive_link('post'); ?>" class="tab <?php if(!is_tax()) echo 'active'; ?>">Tất cả</a>
        <?php
        $terms = get_terms([
            'taxonomy' => 'tin-tuc-cat',
            'hide_empty' => true,
        ]);
        foreach ($terms as $term) {
            echo '<a href="' . esc_url(get_term_link($term)) . '" class="tab ' . (is_tax('category', $term->slug) ? 'active' : '') . '">' . esc_html($term->name) . '</a>';
        }
        ?>
    </div>

    <?php if ( have_posts() ) : ?>
        <div class="archive-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('archive-item'); ?>>
                    <a href="<?php the_permalink(); ?>" class="archive-thumb">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('small');
                        } ?>
                    </a>
                    <h2 class="archive-item-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="archive-excerpt">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>
                    <a class="read-more" href="<?php the_permalink(); ?>">XEM THÊM</a>
                </article>
            <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php echo paginate_links(); ?>
        </div>
    <?php else : ?>
        <p>Không có bài viết nào.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
