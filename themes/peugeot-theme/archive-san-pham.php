<?php
get_header();

// Banner từ trang cài đặt
$settings_id  = pg_get_settings_page_id_by_template('page-archive-settings-san-pham.php');
$banner       = $settings_id ? get_field('image', $settings_id) : null;
$text_banner  = $settings_id ? get_field('text_banner', $settings_id) : null;

// LẤY LINK NÚT DÙNG CHUNG TỪ SETTINGS (ACF Link)

if ($settings_id && function_exists('get_field')) {
    $button_field = get_field('button', $settings_id);
    if (!$button_field) {
        // fallback nếu bạn đặt name là 'button'
        $button_field = get_field('button', $settings_id);
    }

    if (is_array($button_field)) {           // Return format: Link (array)
        $btn_url    = $button_field['url']    ?? '';
        $btn_label  = $button_field['title']  ?: $btn_label;
        $btn_target = $button_field['target'] ?: '_self';
    } elseif (is_string($button_field)) {    // Return format: URL (string)
        $btn_url = $button_field;
    }
}

$banner_title = is_array($text_banner) ? ($text_banner['title'] ?? '') : '';
$banner_sub   = is_array($text_banner) ? ($text_banner['sub']   ?? '') : '';

// Tiêu đề archive
$title = strip_tags(preg_replace('/^(Category:|Tag:|Archives:|Author:|Year:)\s*/', '', get_the_archive_title()));
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

<main id="main" class="container-fluid site-main">



  <?php if (have_posts()) : ?>
    <div class="archive-grid-products">
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('archive-item-product'); ?>>
          <a href="<?php the_permalink(); ?>" class="archive-thumb">
            <?php if (has_post_thumbnail()) the_post_thumbnail('small'); ?>
          </a>

          <h2 class="archive-item-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

          <?php
            // Lấy 3 field ACF của từng post (an toàn nếu ACF tạm chưa load)
            $price      = function_exists('get_field') ? (get_field('price') ?: '') : '';
            $price_sub  = function_exists('get_field') ? (get_field('price_sub') ?: '') : '';
            $price_desc = function_exists('get_field') ? (get_field('price_desc') ?: '') : '';
          ?>
          <div class="archive-excerpt product-meta">
            
            <?php if ($price_sub): ?>
              <div class="product-price-sub"><?php echo esc_html($price_sub); ?></div>
            <?php endif; ?>

            <?php if ($price): ?>
              <div class="product-price"><?php echo esc_html($price); ?></div>
            <?php endif; ?>

            <?php if ($price_desc): ?>
              <div class="product-price-desc"><?php echo nl2br(esc_html($price_desc)); ?></div>
            <?php endif; ?>

            <?php if (!$price && !$price_sub && !$price_desc): ?>
              <div class="fallback-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '…'); ?></div>
            <?php endif; ?>
          </div>

          <div class="product-btns">
  <a class="read-more-product" href="<?php the_permalink(); ?>">XEM THÊM</a>
  <?php if (!empty($btn_url)): ?>
    <a class="read-more-product" href="<?php echo esc_url($btn_url); ?>" target="<?php echo esc_attr($btn_target); ?>">
      <?php echo esc_html($btn_label); ?>
    </a>
  <?php endif; ?>
</div>
        </article>
      <?php endwhile; ?>
    </div>

    <div class="pagination"><?php echo paginate_links(); ?></div>
  <?php else: ?>
    <p>Không có bài viết nào.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
