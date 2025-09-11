<?php
/* =========================
 * THEME SETUP (supports + menus)
 * ========================= */
add_action('after_setup_theme', function () {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
  add_theme_support('custom-logo', [
    'height'=>100,'width'=>80,'flex-height'=>true,'flex-width'=>true,
    'header-text'=>['site-title','site-description'],
  ]);

  register_nav_menus([
    'primary_menu'     => __('Menu chính', 'peugeot-theme'),
    'footer_menu_1'    => __('Menu footer 1', 'peugeot-theme'),
    'footer_menu_2'    => __('Menu footer 2', 'peugeot-theme'),
    'extra_right_menu' => __('Menu bên phải', 'peugeot-theme'),
  ]);
});

/* =========================
 * CUSTOMIZER: logo thứ 2 khi header đen
 * ========================= */
add_action('customize_register', function ($c) {
  $c->add_setting('custom_logo_scrolled', ['default'=>'', 'sanitize_callback'=>'esc_url_raw']);
  $c->add_control(new WP_Customize_Image_Control($c, 'custom_logo_scrolled', [
    'label'=>__('Logo thứ 2 (khi cuộn / header đen)', 'peugeot-theme'),
    'section'=>'title_tagline', 'settings'=>'custom_logo_scrolled', 'priority'=>9,
  ]));
});

/* =========================
 * LOGO helpers
 * ========================= */
if (!function_exists('peugeot_print_dual_logo')) {
  function peugeot_print_dual_logo() {
    $name = get_bloginfo('name');
    $logo_default  = get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '';
    $logo_scrolled = get_theme_mod('custom_logo_scrolled') ?: $logo_default;

    if ($logo_default) {
      echo '<img class="custom-logo logo-default" src="'.esc_url($logo_default).'" alt="'.esc_attr($name).'" width="180" height="54">';
      echo '<img class="custom-logo logo-alt" src="'.esc_url($logo_scrolled).'" alt="'.esc_attr($name).'" width="180" height="54" loading="lazy">';
    } else {
      echo '<span class="site-title logo-default">'.esc_html($name).'</span>';
      echo '<span class="site-title logo-alt">'.esc_html($name).'</span>';
    }
  }
}

/* Alias để tương thích tên cũ/nhầm chính tả ở header.php */
if (!function_exists('peugeot_theme_display_logo')) {
  function peugeot_theme_display_logo() { peugeot_print_dual_logo(); }
}
if (!function_exists('peugeut_theme_display_logo')) { // alias cho tên bị gõ sai
  function peugeut_theme_display_logo() { peugeot_print_dual_logo(); }
}

/* =========================
 * ENQUEUE assets (gộp 1 chỗ)
 * ========================= */
add_action('wp_enqueue_scripts', function () {
  // CSS
  wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], null);
  wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700|Roboto:100,300,400,700', [], null);
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', [], null);
  wp_enqueue_style('peugeot-custom-css', get_template_directory_uri().'/custom.css', [], null);

  // JS
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
  wp_enqueue_script('peugeot-custom-js', get_theme_file_uri('/custom.js'), [], null, true);

  // JS lọc trang đại lý
  if (is_page_template('page-tim-dai-ly.php')) {
    wp_enqueue_style('agency-filter-css', get_template_directory_uri().'/assets/css/agency-filter.css', [], null);
  wp_enqueue_script('agency-filter', get_template_directory_uri().'/assets/js/agency-filter.js', ['jquery'], '1.0', true);

    wp_localize_script('agency-filter', 'AGENCY_AJAX', [
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => wp_create_nonce('agency_filter_nonce'),
    ]);
  }
});

/* =========================
 * Tối ưu nhỏ + login logo
 * ========================= */
add_filter('jpeg_quality', fn() => 80);
add_action('login_enqueue_scripts', function () {
  echo '<style>.login h1 a{background-image:url("'.esc_url(get_template_directory_uri().'/images/login-logo.png').'")!important;background-size:contain!important;width:200px!important;height:80px!important;display:block!important}</style>';
});

/* =========================
 * AJAX filter (giữ nguyên logic bạn đang dùng)
 * ========================= */
add_action('wp_ajax_filter_agencies', 'handle_filter_agencies');
add_action('wp_ajax_nopriv_filter_agencies', 'handle_filter_agencies');

function handle_filter_agencies() {
  check_ajax_referer('agency_filter_nonce', 'nonce');

  $kw    = isset($_POST['keyword'])   ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
  $mien  = isset($_POST['mien'])      ? absint($_POST['mien']) : 0;
  $tinh  = isset($_POST['tinhthanh']) ? absint($_POST['tinhthanh']) : 0;
  $paged = max(1, absint($_POST['paged'] ?? 1));

  $args = [
    'post_type'      => 'dai-ly',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ];

  $tax_query = ['relation' => 'AND'];
  if ($kw !== '') $args['s'] = $kw;
  if ($mien) $tax_query[] = ['taxonomy'=>'mien',       'field'=>'term_id', 'terms'=>[$mien]];
  if ($tinh) $tax_query[] = ['taxonomy'=>'tinhthanh',  'field'=>'term_id', 'terms'=>[$tinh]];
  if (count($tax_query) > 1) $args['tax_query'] = $tax_query;

  $q = new WP_Query($args);

ob_start();

if ($q->have_posts()) {
  echo '<div class="agency-grid">';
  while ($q->have_posts()) { $q->the_post(); pg_render_agency_card(get_the_ID()); }
  echo '</div>';
} else {
  echo '<p>Không có bài nào.</p>';
}
wp_reset_postdata();

$html_list = ob_get_clean();


  $pagination = paginate_links([
    'total'=>$q->max_num_pages, 'current'=>$paged, 'type'=>'array',
    'prev_text'=>'&laquo;', 'next_text'=>'&raquo;', 'add_args'=>false,
  ]);
  $pagination = is_array($pagination)
    ? '<div class="agency-pagination" style="margin-top:20px;display:flex;gap:8px;flex-wrap:wrap;">'
        .implode('', array_map(fn($l)=>'<span class="page-link">'.$l.'</span>', $pagination)).'</div>'
    : '';

  wp_send_json_success([
    'html'        => $html_list.$pagination,
    'found_posts' => (int) $q->found_posts,
    'max_pages'   => (int) $q->max_num_pages,
    'current'     => (int) $paged,
  ]);
}
add_action('after_setup_theme', function () {
  $file = get_template_directory() . '/inc/agency-card.php'; // dùng child theme nếu có
  if (file_exists($file)) require_once $file;
});
// CPT: lich_lai_thu
add_action('init', function () {
  register_post_type('lich_lai_thu', [
    'label' => 'Đăng ký lái thử',
    'public' => false,
    'show_ui' => true,
    'menu_icon' => 'dashicons-calendar-alt',
    'supports' => ['title'],
  ]);
});
// functions.php
add_action('acf/save_post', function ($post_id) {
  if (get_post_type($post_id) !== 'lich_lai_thu') return;

  $car_id  = (int) get_field('car', $post_id);
  $car     = $car_id ? get_the_title($car_id) : '';
  $salute  = (string) get_field('salutation', $post_id);
  $fname   = (string) get_field('first_name', $post_id);
  $lname   = (string) get_field('last_name', $post_id);

  $title = sprintf('[Lái thử] %s %s – %s – %s',
    $salute,
    trim($lname.' '.$fname),
    $car ?: 'Không rõ xe',
    wp_date('d/m/Y H:i')
  );

  remove_action('acf/save_post', __FUNCTION__);
  wp_update_post(['ID' => $post_id, 'post_title' => $title]);
  add_action('acf/save_post', __FUNCTION__);
}, 20);
// Enqueue cho trang Đặt lịch lái thử
add_action('wp_enqueue_scripts', function () {
  if (is_page_template('page-dat-lich-lai-thu.php')) {
    wp_enqueue_style('td-form', get_template_directory_uri().'/assets/css/td-form.css', [], '1.0');
    wp_enqueue_script('td-form', get_template_directory_uri().'/assets/js/td-form.js', ['jquery'], '1.0', true);
    wp_localize_script('td-form', 'TD_AJAX', [
      'url'   => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('td_val_nonce'),
    ]);
  }
});

// AJAX validate realtime
add_action('wp_ajax_td_validate_field', 'td_validate_field');
add_action('wp_ajax_nopriv_td_validate_field', 'td_validate_field');

function td_validate_field() {
  check_ajax_referer('td_val_nonce', 'nonce');

  $key   = isset($_POST['key'])   ? sanitize_text_field(wp_unslash($_POST['key']))   : '';
  $value = isset($_POST['value']) ? wp_unslash($_POST['value']) : '';
  $value = is_string($value) ? trim($value) : $value;

  $ok = true; $msg = '';

  // Các field bắt buộc
  $required = [
    'field_68c2380eeb74f', // car
    'field_68c239ebeb750', // dai_ly
    'field_68c23a85eb757', // salutation
    'field_68c23a0eeb751', // first_name
    'field_68c23a1feb752', // last_name
    'field_68c23a2eeb753', // phone
    'field_68c23a3ceb754', // email
  ];
  if (in_array($key, $required, true)) {
    if ($value === '' || $value === '0' || $value === '---') {
      wp_send_json_success(['ok'=>false, 'message'=>'Trường này bắt buộc.']);
    }
  }

  // Kiểm tra riêng từng loại
  if ($key === 'field_68c23a2eeb753') { // phone
    $digits = preg_replace('/\D+/', '', $value);
    if (!preg_match('/^(0\d{9,10}|84\d{9,10})$/', $digits)) {
      $ok=false; $msg='Số điện thoại không hợp lệ.';
    }
  }

  if ($key === 'field_68c23a3ceb754') { // email
    if (!is_email($value)) { $ok=false; $msg='Email không hợp lệ.'; }
    else {
      // (tuỳ chọn) chống trùng email đã đăng ký
      $dupe = new WP_Query([
        'post_type' => 'lich_lai_thu',
        'post_status' => 'any',
        'posts_per_page' => 1,
        'meta_query' => [['key'=>'email','value'=>$value,'compare'=>'=']],
        'no_found_rows' => true,
      ]);
      if ($dupe->have_posts()) { $ok=false; $msg='Email này đã có đăng ký trước đó.'; }
      wp_reset_postdata();
    }
  }

  wp_send_json_success(['ok'=>$ok, 'message'=>$msg]);
}
add_action('acf/save_post', function($post_id){
    if(get_post_type($post_id) === 'lich_lai_thu') {
        // Xử lý sau khi tạo post mới, ví dụ gửi mail, đổi title, v.v.
    }
}, 20);