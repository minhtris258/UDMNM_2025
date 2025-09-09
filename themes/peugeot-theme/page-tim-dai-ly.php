<?php
/**
 * Template Name: Trang Tìm Đại Lý
 */
get_header();
?>
<?php 
$slider = get_field('slider_images');
if ($slider) : ?>
    <div class="peugeot-slider">
        <div class="peugeot-slider-wrapper">
            <?php 
            foreach ($slider as $img) {
                if (!empty($img)) {
                    if (is_array($img) && isset($img['url'])) {
                        echo '<div class="peugeot-slide">
                                <img src="' . esc_url($img['url']) . '" alt="' . esc_attr($img['alt'] ?? '') . '" />
                              </div>';
                    } elseif (is_numeric($img)) {
                        echo '<div class="peugeot-slide">
                                <img src="' . esc_url(wp_get_attachment_image_url($img, 'full')) . '" alt="" />
                              </div>';
                    }
                }
            }
            ?>
        </div>
        <div class="peugeot-slider-nav-zone left"></div>
        <div class="peugeot-slider-nav-zone right"></div>
    </div>
<?php endif; ?>
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

<div class="container">
    <?php
// Tabs: 'TẤT CẢ' + các term của taxonomy miền
$top_mien_terms = get_terms(['taxonomy'=>$tax_mien,'hide_empty'=>false]);

// Giữ lại keyword & tỉnh đang chọn khi bấm tab

?>
 <h1 class="ag-title"><?php the_title(); ?></h1>
 <div class="container py-2" style="background-color: #ebebeb;">
<div class="agency-tabs">
  <a class="agency-tab <?php echo $mien ? '' : 'is-active'; ?>" href="<?php echo pg_qs(['mien'=>null]); ?>">Tất cả</a>
  <?php if (!is_wp_error($top_mien_terms)) foreach ($top_mien_terms as $t): ?>
    <a class="agency-tab <?php echo $mien == $t->term_id ? 'is-active' : ''; ?>" href="<?php echo pg_qs(['mien'=>$t->term_id]); ?>">
      <?php echo esc_html($t->name); ?>
    </a>
  <?php endforeach; ?>
</div>



 

  <!-- FORM -->
<form class="agency-filter-form agency-filter form-compact"
      method="get" action="<?php echo esc_url(get_permalink()); ?>">


  <!-- Hàng 1: ô search full width + icon -->
  <div class="row1 search-wrap">
    <label for="keyword" class="sr-only">Từ khoá</label>
    <input id="keyword" type="text" name="keyword"
           value="<?php echo esc_attr($kw); ?>"
           placeholder="Nhập từ khoá tìm kiếm" />
  </div>

  <!-- Hàng 2: 2 select -->
  <div class="row2">
    <div>
      <label for="mien" class="sr-only">Miền</label>
      <select id="mien" name="mien">
        <option value=""><?php echo esc_html('Toàn quốc'); ?></option>
        <?php
        $mien_terms = get_terms(['taxonomy'=>$tax_mien,'hide_empty'=>false]);
        if (!is_wp_error($mien_terms)) {
          foreach ($mien_terms as $term) {
            printf('<option value="%d"%s>%s</option>',
              $term->term_id,
              selected($mien,$term->term_id,false),
              esc_html($term->name)
            );
          }
        }
        ?>
      </select>
    </div>

    <div>
      <label for="tinhthanh" class="sr-only">Tỉnh/Thành</label>
      <select id="tinhthanh" name="tinhthanh">
        <option value=""><?php echo esc_html('Chọn Tỉnh/Thành phố'); ?></option>
        <?php
        $tinh_terms = get_terms(['taxonomy'=>$tax_tinh,'hide_empty'=>false]);
        if (!is_wp_error($tinh_terms)) {
          foreach ($tinh_terms as $term) {
            printf('<option value="%d"%s>%s</option>',
              $term->term_id,
              selected($tinh,$term->term_id,false),
              esc_html($term->name)
            );
          }
        }
        ?>
      </select>
    </div>

    <!-- Nếu vẫn muốn nút Lọc / Reset thì để lại; còn không, xoá 2 div dưới -->
    <div class="actions">
      <a class="btn-ghost" href="<?php echo esc_url(get_permalink()); ?>">Reset</a>
    </div>
  </div>
</form>
</div>
<h2 class="agency-count">
  Có <?php echo (int) $query->found_posts; ?> Đại lý / Chi Nhánh / Workshop
</h2>
 <!-- KẾT QUẢ (server-side lần đầu) -->
<div id="agency-results" data-page="<?php echo esc_attr($paged); ?>">
  <?php if ($query->have_posts()) : ?>
    <div class="agency-grid">
      <?php while ($query->have_posts()) { $query->the_post(); pg_render_agency_card(get_the_ID()); } ?>
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
