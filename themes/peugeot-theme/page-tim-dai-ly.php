<?php
/**
 * Template Name: Trang Tìm Đại Lý
 */
get_header();
?>
<?php
$kw    = isset($_GET['keyword']) ? sanitize_text_field(wp_unslash($_GET['keyword'])) : '';
$mien  = isset($_GET['mien']) ? absint($_GET['mien']) : 0;
$tinh  = isset($_GET['tinhthanh']) ? absint($_GET['tinhthanh']) : 0;

$post_type_slug = 'dai-ly';
$tax_mien       = 'mien';
$tax_tinh       = 'tinhthanh';

$paged = max(1, get_query_var('paged') ?: (isset($_GET['paged']) ? absint($_GET['paged']) : 1));

// Query server-side lần đầu (vào trang hoặc SEO)
$args = [
  'post_type'      => $post_type_slug,
  'post_status'    => 'publish',
  'posts_per_page' => 12,
  'paged'          => $paged,
  'orderby'        => 'date',
  'order'          => 'DESC',
];

$has_filter = false;
if ($kw !== '') { $args['s'] = $kw; $has_filter = true; }

$tax_query = ['relation' => 'AND'];
if ($mien) { $tax_query[] = ['taxonomy'=>$tax_mien,'field'=>'term_id','terms'=>[$mien]]; $has_filter = true; }
if ($tinh) { $tax_query[] = ['taxonomy'=>$tax_tinh,'field'=>'term_id','terms'=>[$tinh]]; $has_filter = true; }
if ($has_filter && count($tax_query) > 1) { $args['tax_query'] = $tax_query; }

$query = new WP_Query($args);
?>

<div class="container" style="max-width:1100px;margin:40px auto;">
  <h1><?php the_title(); ?></h1>

  <!-- FORM -->
  <form class="agency-filter-form" method="get" action="<?php echo esc_url(get_permalink()); ?>"
        style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end;margin:20px 0;">
    <div>
      <label for="keyword"><strong>Tên</strong></label>
      <input id="keyword" type="text" name="keyword" value="<?php echo esc_attr($kw); ?>" placeholder="Nhập tên cần tìm" />
    </div>

    <div>
      <label for="mien"><strong>Miền</strong></label>
      <select id="mien" name="mien">
        <option value="">— Tất cả —</option>
        <?php
        $mien_terms = get_terms(['taxonomy'=>$tax_mien,'hide_empty'=>false]);
        if (!is_wp_error($mien_terms)) {
          foreach ($mien_terms as $term) {
            printf('<option value="%d"%s>%s</option>', $term->term_id, selected($mien,$term->term_id,false), esc_html($term->name));
          }
        }
        ?>
      </select>
    </div>

    <div>
      <label for="tinhthanh"><strong>Tỉnh/Thành</strong></label>
      <select id="tinhthanh" name="tinhthanh">
        <option value="">— Tất cả —</option>
        <?php
        $tinh_terms = get_terms(['taxonomy'=>$tax_tinh,'hide_empty'=>false]);
        if (!is_wp_error($tinh_terms)) {
          foreach ($tinh_terms as $term) {
            printf('<option value="%d"%s>%s</option>', $term->term_id, selected($tinh,$term->term_id,false), esc_html($term->name));
          }
        }
        ?>
      </select>
    </div>

    <div style="display:flex;gap:8px;">
      <button type="submit">Lọc</button>
      <a class="button-reset" href="<?php echo esc_url(get_permalink()); ?>">Reset</a>
    </div>
  </form>

  <!-- KẾT QUẢ (server-side lần đầu) -->
  <div id="agency-results" data-page="<?php echo esc_attr($paged); ?>">
    <?php if ($query->have_posts()) : ?>
      <div class="agency-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
  <?php
  // Lấy group ACF
  $tt = get_field('thong_tin', get_the_ID()) ?: [];
  $dia_chi   = isset($tt['dia_chi']) ? $tt['dia_chi'] : '';
  $cskh      = isset($tt['hotline_cskh']) ? $tt['hotline_cskh'] : '';
  $kinhdoanh = isset($tt['hotline_kinh_doanh']) ? $tt['hotline_kinh_doanh'] : '';
  $dichvu    = isset($tt['hotline_dich_vu']) ? $tt['hotline_dich_vu'] : '';
  $map_url   = isset($tt['map']) ? $tt['map'] : '';
  ?>

  <article class="agency-card" style="border:1px solid #eee;border-radius:8px;padding:16px;">
    <h3 style="margin:0 0 8px;">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <!-- Taxonomy -->
    <div style="font-size:14px;color:#666;margin-bottom:10px;">
      <?php
        $mien_links = get_the_term_list(get_the_ID(), $tax_mien, '<strong>Miền:</strong> ', ', ', '');
        $tinh_links = get_the_term_list(get_the_ID(), $tax_tinh, '<strong>Tỉnh/Thành:</strong> ', ', ', '');
        echo wp_kses_post($mien_links ?: '');
        echo ($mien_links && $tinh_links) ? '<br>' : '';
        echo wp_kses_post($tinh_links ?: '');
      ?>
    </div>

    <!-- ACF: Thông tin đại lý -->
    <div class="agency-info" style="font-size:14px;line-height:1.5;margin-bottom:10px;">
      <?php if ($dia_chi) : ?>
        <div><strong>Địa chỉ:</strong> <?php echo nl2br(esc_html($dia_chi)); ?></div>
      <?php endif; ?>

      <?php if ($cskh) : ?>
        <div><strong>Hotline CSKH:</strong> <a href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $cskh)); ?>">
          <?php echo esc_html($cskh); ?></a></div>
      <?php endif; ?>

      <?php if ($kinhdoanh) : ?>
        <div><strong>Hotline Kinh doanh:</strong> <a href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $kinhdoanh)); ?>">
          <?php echo esc_html($kinhdoanh); ?></a></div>
      <?php endif; ?>

      <?php if ($dichvu) : ?>
        <div><strong>Hotline Dịch vụ:</strong> <a href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $dichvu)); ?>">
          <?php echo esc_html($dichvu); ?></a></div>
      <?php endif; ?>

      <?php if ($map_url) : ?>
        <div><a target="_blank" rel="noopener" href="<?php echo esc_url($map_url); ?>">Xem bản đồ</a></div>
      <?php endif; ?>
    </div>

    <!-- Mô tả/ngắn gọn nếu muốn -->
    <div style="font-size:14px;color:#444;"><?php echo wp_kses_post(get_the_excerpt()); ?></div>
  </article>
<?php endwhile; ?>

      </div>

      <div class="agency-pagination" style="margin-top:20px;display:flex;gap:8px;flex-wrap:wrap;">
        <?php
        echo implode('', array_map(function($link){
          return '<span class="page-link">'.$link.'</span>';
        }, (array) paginate_links([
          'total'      => $query->max_num_pages,
          'current'    => $paged,
          'type'       => 'array',
          'prev_text'  => '&laquo;',
          'next_text'  => '&raquo;',
        ])));
        ?>
      </div>

    <?php else: ?>
      <p>Không có bài nào.</p>
    <?php endif; wp_reset_postdata(); ?>
  </div>
</div>

<?php get_footer(); ?>
