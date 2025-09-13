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
    'footer_cta'      => __('Footer CTA (4 ô trên cùng)', 'peugeot-theme'),
    'footer_policies' => __('Footer Policies (liên kết dưới cùng bên trái)', 'peugeot-theme'),
  ]);
});
// widget
// === FOOTER WIDGET AREAS ===
add_action('widgets_init', function () {

  // Hàng CTA (nếu bạn muốn kéo-thả thay vì menu, nhưng mình vẫn ưu tiên dùng menu)
  register_sidebar([
    'name'          => __('Footer CTA (tùy chọn)', 'peugeot-theme'),
    'id'            => 'footer_cta_widgets',
    'description'   => 'Nếu cần thêm block cho hàng CTA, dùng Custom HTML/Text. (Ưu tiên menu footer_cta).',
    'before_widget' => '<div class="footer-cta-widget %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="footer-cta-title">',
    'after_title'   => '</h3>',
  ]);

  // 4 cột chính
  register_sidebar([
    'name' => __('Footer – Cột 1: Về Peugeot Việt Nam (text/logo/địa chỉ)', 'peugeot-theme'),
    'id'   => 'footer_col_1',
    'before_widget' => '<div class="footer-col footer-col-1 %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="footer-title">',
    'after_title'   => '</h4>',
  ]);

  register_sidebar([
    'name' => __('Footer – Cột 2: Truy cập nhanh (kéo widget “Navigation Menu”)', 'peugeot-theme'),
    'id'   => 'footer_col_2',
    'before_widget' => '<div class="footer-col footer-col-2 %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="footer-title">',
    'after_title'   => '</h4>',
  ]);

  register_sidebar([
    'name' => __('Footer – Cột 3: Dành cho chủ xe (kéo widget “Navigation Menu”)', 'peugeot-theme'),
    'id'   => 'footer_col_3',
    'before_widget' => '<div class="footer-col footer-col-3 %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="footer-title">',
    'after_title'   => '</h4>',
  ]);

  register_sidebar([
    'name' => __('Footer – Cột 4: Tìm hiểu thêm (kéo widget “Navigation Menu”)', 'peugeot-theme'),
    'id'   => 'footer_col_4',
    'before_widget' => '<div class="footer-col footer-col-4 %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4 class="footer-title">',
    'after_title'   => '</h4>',
  ]);

  // Hàng dưới
  register_sidebar([
    'name' => __('Footer dưới – Trái (Policies: kéo “Navigation Menu”)', 'peugeot-theme'),
    'id'   => 'footer_bottom_left',
    'before_widget' => '<div class="footer-bottom-left %2$s" id="%1$s">',
    'after_widget'  => '</div>',
  ]);

  register_sidebar([
    'name' => __('Footer dưới – Giữa (Logo)', 'peugeot-theme'),
    'id'   => 'footer_bottom_center',
    'before_widget' => '<div class="footer-bottom-center %2$s" id="%1$s">',
    'after_widget'  => '</div>',
  ]);

  register_sidebar([
    'name' => __('Footer dưới – Phải (Hotline + Social – dùng Text/HTML)', 'peugeot-theme'),
    'id'   => 'footer_bottom_right',
    'before_widget' => '<div class="footer-bottom-right %2$s" id="%1$s">',
    'after_widget'  => '</div>',
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
/** ========================
 * CPT
 * ======================== */
add_action('init', function () {
  if (!post_type_exists('lich_lai_thu')) {
    register_post_type('lich_lai_thu', [
      'label'      => 'Đăng ký lái thử',
      'public'     => false,
      'show_ui'    => true,
      'menu_icon'  => 'dashicons-calendar-alt',
      'supports'   => ['title'],
    ]);
  }

  if (!post_type_exists('lien_he')) {
    register_post_type('lien_he', [
      'label'      => 'Liên hệ',
      'public'     => false,
      'show_ui'    => true,
      'menu_icon'  => 'dashicons-email',
      'supports'   => ['title'],
    ]);
  }
});

/** ========================
 * Helper: xác định context form theo template
 * return 'test_drive' | 'lien_he' | ''
 * ======================== */
if (!function_exists('td_form_context')) {
  function td_form_context(): string {
    if (is_page_template('page-dat-lich-lai-thu.php')) return 'test_drive';
    if (is_page_template('page-lien-he.php'))         return 'lien_he';
    return '';
  }
}

/** ========================
 * Enqueue assets dùng CHUNG cho 2 form
 * ======================== */
add_action('wp_enqueue_scripts', function () {
  $ctx = td_form_context();
  if (!$ctx) return;

  wp_enqueue_style('td-form', get_template_directory_uri().'/assets/css/td-form.css', [], '1.2');
  wp_enqueue_script('td-form', get_template_directory_uri().'/assets/js/td-form.js', ['jquery'], '1.2', true);

  wp_localize_script('td-form', 'TD_AJAX', [
    'url'     => admin_url('admin-ajax.php'),
    'nonce'   => wp_create_nonce('td_validate'), // <<< đồng nhất với AJAX handler
    'context' => $ctx,
  ]);
});

/** ========================
 * Đặt tiêu đề tự động khi lưu ACF
 * ======================== */
add_action('acf/save_post', function ($post_id) {
  $pt = get_post_type($post_id);
  if (!in_array($pt, ['lich_lai_thu','lien_he'], true)) return;

  // Dùng try-get để không lỗi khi thiếu ACF
  $get = function ($field_key) use ($post_id) {
    $v = function_exists('get_field') ? get_field($field_key, $post_id) : '';
    return is_scalar($v) ? (string)$v : '';
  };

  $salute = $get('salutation');
  $fname  = $get('first_name');
  $lname  = $get('last_name');
  $name   = trim($lname.' '.$fname);

  if ($pt === 'lich_lai_thu') {
    $car_id = (int) $get('car');
    $car    = $car_id ? get_the_title($car_id) : 'Không rõ xe';
    $title  = sprintf('[Lái thử] %s %s – %s – %s', $salute, $name, $car, wp_date('d/m/Y H:i'));
  } else { // lien_he
    $yeu_cau = $get('yeu_cau');
    $title   = sprintf('[Liên hệ] %s %s – %s – %s', $salute, $name, ($yeu_cau ?: 'Không rõ yêu cầu'), wp_date('d/m/Y H:i'));
  }

  // Tránh loop
  remove_action('acf/save_post', __FUNCTION__);
  wp_update_post(['ID'=>$post_id, 'post_title'=>$title]);
  add_action('acf/save_post', __FUNCTION__);
}, 20);

/** ========================
 * Helper: bộ field bắt buộc theo context
 * ======================== */
function td_required_keys(string $ctx): array {
  if ($ctx === 'test_drive') {
    return [
      'field_68c2380eeb74f', // car
      'field_68c239ebeb750', // dai_ly
      'field_68c23a85eb757', // salutation
      'field_68c23a0eeb751', // first_name
      'field_68c23a1feb752', // last_name
      'field_68c23a2eeb753', // phone
      'field_68c23a3ceb754', // email
    ];
  }
  // lien_he
  return [
    'field_68c45ac49373b', // yeu_cau
    'field_68c45ac49715a', // dai_ly
    'field_68c45ac49ae86', // salutation
    'field_68c45ac49e9fa', // first_name
    'field_68c45ac4a2397', // last_name
    'field_68c45ac4a5eca', // phone
    'field_68c45ac4a9aa1', // email
  ];
}

/** ========================
 * AJAX validate realtime (dùng chung)
 * ======================== */
add_action('wp_ajax_td_validate_field',    'td_validate_field');
add_action('wp_ajax_nopriv_td_validate_field', 'td_validate_field');

function td_validate_field() {
  // Đồng nhất với nonce phía enqueue
  check_ajax_referer('td_validate', 'nonce');

  $ctx   = isset($_POST['context']) ? sanitize_text_field(wp_unslash($_POST['context'])) : '';
  if (!in_array($ctx, ['test_drive','lien_he'], true)) {
    wp_send_json_success(['ok'=>true, 'message'=>'']); // không có context thì bỏ qua
  }

  $key   = isset($_POST['key'])   ? sanitize_text_field(wp_unslash($_POST['key']))   : '';
  $value = isset($_POST['value']) ? wp_unslash($_POST['value']) : '';
  $value = is_string($value) ? trim($value) : $value;

  $ok = true; $msg = '';
  $required = td_required_keys($ctx);

  // Bắt buộc
  if (in_array($key, $required, true)) {
    if ($value === '' || $value === '0' || $value === '---') {
      wp_send_json_success(['ok'=>false, 'message'=>'Trường này bắt buộc.']);
    }
  }

  // Phone
  if (in_array($key, ['field_68c23a2eeb753','field_68c45ac4a5eca'], true)) {
    $digits = preg_replace('/\D+/', '', (string)$value);
    if (!preg_match('/^(0\d{9,10}|84\d{9,10})$/', $digits)) {
      $ok=false; $msg='Số điện thoại không hợp lệ.';
    }
  }

  // Email
  if (in_array($key, ['field_68c23a3ceb754','field_68c45ac4a9aa1'], true)) {
    if (!is_email($value)) { $ok=false; $msg='Email không hợp lệ.'; }
    else {
      // tuỳ chọn: check trùng email trong cả 2 CPT
      $dupe = new WP_Query([
        'post_type'      => ['lich_lai_thu','lien_he'],
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'meta_query'     => [[ 'key'=>'email', 'value'=>$value, 'compare'=>'=' ]],
        'no_found_rows'  => true,
      ]);
      if ($dupe->have_posts()) { $ok=false; $msg='Email này đã được gửi trước đó.'; }
      wp_reset_postdata();
    }
  }

  wp_send_json_success(['ok'=>$ok, 'message'=>$msg]);
}
if (!function_exists('td_render')) {
  function td_render($key, $label = null, $placeholder = null){
    if (!function_exists('acf_get_field')) { 
      echo '<p>ACF chưa kích hoạt.</p>'; 
      return; 
    }
    $field = acf_get_field($key);
    if (!$field) { 
      echo '<p class="td-missing">Không tìm thấy field: '.esc_html($key).'</p>'; 
      return; 
    }

    if ($label !== null)       $field['label'] = $label;
    if ($placeholder !== null) $field['placeholder'] = $placeholder;

    $field['prefix'] = 'acf'; // name="acf[field_xxx]"
    $field['wrapper']['class'] = trim(($field['wrapper']['class'] ?? '').' td-field');

    $field = acf_prepare_field($field);
    acf_render_field_wrap($field);
  }
}
