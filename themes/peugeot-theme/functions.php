<?php
/* =========================
 * THEME SETUP (supports + menus + i18n)
 * ========================= */
add_action('after_setup_theme', function () {
  // i18n cho theme
  load_theme_textdomain('peugeot-theme', get_template_directory() . '/languages');

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
    'footer_cta'       => __('Footer CTA (4 ô trên cùng)', 'peugeot-theme'),
    'footer_policies'  => __('Footer Policies (liên kết dưới cùng bên trái)', 'peugeot-theme'),
  ]);
});

/* =========================
 * FOOTER WIDGET AREAS
 * ========================= */
add_action('widgets_init', function () {
  register_sidebar([
    'name'          => __('Footer CTA (tùy chọn)', 'peugeot-theme'),
    'id'            => 'footer_cta_widgets',
    'description'   => __('Nếu cần thêm block cho hàng CTA, dùng Custom HTML/Text. (Ưu tiên menu footer_cta).', 'peugeot-theme'),
    'before_widget' => '<div class="footer-cta-widget %2$s" id="%1$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="footer-cta-title">',
    'after_title'   => '</h3>',
  ]);

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

if (!function_exists('peugeut_theme_display_logo')) {
  function peugeut_theme_display_logo() { peugeot_print_dual_logo(); }
}

/* =========================
 * ENQUEUE assets
 * ========================= */
add_action('wp_enqueue_scripts', function () {
  // CSS
  wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], null);
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', [], null);
  wp_enqueue_style('peugeot-custom-css', get_template_directory_uri().'/custom.css', [], null);
  wp_enqueue_style('peugeot-fonts', get_template_directory_uri() . '/assets/css/fonts.css');

  // JS
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', [], null, true);
  wp_enqueue_script('peugeot-custom-js', get_theme_file_uri('/custom.js'), [], null, true);

  // Trang lọc đại lý
  if (is_page_template('page-tim-dai-ly.php')) {
    wp_enqueue_style('agency-filter-css', get_template_directory_uri().'/assets/css/agency-filter.css', [], null);
    wp_enqueue_script('agency-filter', get_template_directory_uri().'/assets/js/agency-filter.js', ['jquery'], '1.0', true);
    wp_localize_script('agency-filter', 'AGENCY_AJAX', [
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => wp_create_nonce('agency_filter_nonce'),
    ]);
  }
});
require_once get_template_directory() . '/inc/youtube-privacy-embed.php';



// Ảnh đại diện + size dùng cho menu (KHÔNG khai báo lại post-thumbnails lần 2)
add_image_size('menu-thumb', 360, 220, true);

/* =========================
 * Helpers cho mega menu
 * ========================= */
function pg_menu_item_thumb_url($item, $size = 'menu-thumb'){
  if ($item->type === 'post_type' && !empty($item->object_id)) {
    $url = get_the_post_thumbnail_url((int)$item->object_id, $size);
    if ($url) return $url;
  }
  if ($item->type === 'taxonomy' && !empty($item->object_id)) {
    $thumb_id = get_term_meta((int)$item->object_id, 'thumbnail_id', true);
    if ($thumb_id) {
      $url = wp_get_attachment_image_url((int)$thumb_id, $size);
      if ($url) return $url;
    }
  }
  return '';
}

class PG_Mega_Walker extends Walker_Nav_Menu {
  private $current_top_item = null;
  private $current_mega_context = [
    'wrapper_class' => 'pg-mega-wrapper',
    'right_html'    => '',
    'has_right'     => false,
  ];

  private function normalize_acf_link($value){
    if (empty($value)) return null;

    if (is_array($value)) {
      $url = isset($value['url']) ? trim((string)$value['url']) : '';
      if ($url === '') return null;

      $title  = isset($value['title']) && $value['title'] !== '' ? (string)$value['title'] : __('Xem thêm', 'peugeot-theme');
      $target = isset($value['target']) ? trim((string)$value['target']) : '';
      $rel    = isset($value['rel']) ? trim((string)$value['rel']) : '';

      if ($target === '_blank' && stripos($rel, 'noopener') === false) {
        $rel = trim($rel . ' noopener noreferrer');
      }

      return [
        'url'    => $url,
        'title'  => $title,
        'target' => $target,
        'rel'    => $rel,
      ];
    }

    if (is_string($value)) {
      $url = trim($value);
      if ($url === '') return null;

      return [
        'url'    => $url,
        'title'  => __('Xem thêm', 'peugeot-theme'),
        'target' => '',
        'rel'    => '',
      ];
    }

    return null;
  }

  private function resolve_image_url($image){
    if (empty($image)) return '';

    if (is_array($image)) {
      if (!empty($image['ID'])) {
        $url = wp_get_attachment_image_url((int)$image['ID'], 'full');
        if ($url) return $url;
      }
      if (!empty($image['id'])) {
        $url = wp_get_attachment_image_url((int)$image['id'], 'full');
        if ($url) return $url;
      }
      if (!empty($image['url'])) {
        return (string)$image['url'];
      }
    }

    if (is_numeric($image)) {
      $url = wp_get_attachment_image_url((int)$image, 'full');
      if ($url) return $url;
    }

    if (is_string($image)) {
      return trim($image);
    }

    return '';
  }

  private function prepare_mega_panel_context($item){
    $context = [
      'wrapper_class' => 'pg-mega-wrapper pg-mega-wrapper--no-hero',
      'right_html'    => '',
      'has_right'     => false,
    ];

    if (!$item instanceof \WP_Post) {
      return apply_filters('pg/mega_panel/context', $context, $item);
    }

    if (!function_exists('get_field')) {
      return apply_filters('pg/mega_panel/context', $context, $item);
    }

    $group = get_field('mega_panel', $item);
    if (!is_array($group)) {
      $group = get_field('mega_panel', 'nav_menu_item_' . $item->ID);
    }

    if (!is_array($group)) {
      return apply_filters('pg/mega_panel/context', $context, $item);
    }

    $image_url = $this->resolve_image_url($group['mega_panel_image'] ?? '');

    $slots = [];
    for ($i = 1; $i <= 4; $i++) {
      $key = 'mega_panel_slot' . $i;
      if (!empty($group[$key]) && is_string($group[$key])) {
        $slot = trim($group[$key]);
        if ($slot !== '') $slots[] = $slot;
      }
    }

    $links = [];
    foreach (['mega_panel_link', 'mega_panel_link2'] as $link_key) {
      if (!isset($group[$link_key])) continue;
      $normalized = $this->normalize_acf_link($group[$link_key]);
      if ($normalized) $links[] = $normalized;
    }

    $has_content = ($image_url !== '' || !empty($slots) || !empty($links));

    if (!$has_content) {
      return apply_filters('pg/mega_panel/context', $context, $item);
    }

    $style = $image_url !== '' ? ' style="background-image:url(' . esc_url($image_url) . ');"' : '';
    $html  = '<aside class="pg-mega-right">';
    $html .= '<div class="pg-mega-hero"' . $style . '>';

    if (!empty($slots)) {
      $title = array_shift($slots);
      if ($title !== '') {
        $html .= '<h3 class="pg-mega-hero-title">' . esc_html($title) . '</h3>';
      }
      foreach ($slots as $slot_text) {
        if ($slot_text === '') continue;
        $html .= '<p class="pg-mega-hero-text">' . esc_html($slot_text) . '</p>';
      }
    }

    if (!empty($links)) {
      $html .= '<div class="pg-mega-hero-actions">';
      foreach ($links as $link) {
        $attrs = ' href="' . esc_url($link['url']) . '"';
        if (!empty($link['target'])) {
          $attrs .= ' target="' . esc_attr($link['target']) . '"';
        }
        if (!empty($link['rel'])) {
          $attrs .= ' rel="' . esc_attr($link['rel']) . '"';
        }
        $html .= '<a class="pg-mega-hero-btn"' . $attrs . '>' . esc_html($link['title']) . '</a>';
      }
      $html .= '</div>';
    }

    $html .= '</div></aside>';

    $context['wrapper_class'] = 'pg-mega-wrapper';
    $context['right_html']    = $html;
    $context['has_right']     = true;

    return apply_filters('pg/mega_panel/context', $context, $item);
  }

  public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args = [], &$output = ''){
    if (!$element) return;
    $id_field = $this->db_fields['id'];
    if (isset($args[0]) && is_object($args[0])) {
      $args[0]->has_children = ! empty($children_elements[$element->$id_field]);
    }
    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }

  public function start_lvl( &$output, $depth = 0, $args = [] ){
    $indent = str_repeat("\t", $depth);
    if ($depth === 0){
      $this->current_mega_context = $this->prepare_mega_panel_context($this->current_top_item);
      $wrapper_class = isset($this->current_mega_context['wrapper_class']) ? $this->current_mega_context['wrapper_class'] : 'pg-mega-wrapper';

      $output .= "\n{$indent}<div class=\"mega-panel\" role=\"dialog\" aria-modal=\"false\">"
               . "<button class=\"mega-close\" aria-label=\"".esc_attr__('Đóng menu', 'peugeot-theme')."\">&times;</button>"
               . "<div class=\"" . esc_attr($wrapper_class) . "\">"
               . "<div class=\"pg-mega-left\">"
               . "<ul class=\"mega-grid\">\n";
    } else {
      $output .= "\n{$indent}<ul class=\"sub-menu\">\n";
    }
  }

  public function end_lvl( &$output, $depth = 0, $args = [] ){
    $indent = str_repeat("\t", $depth);
    if ($depth === 0) {
      $output .= "{$indent}</ul></div>";
      if (!empty($this->current_mega_context['right_html']) && !empty($this->current_mega_context['has_right'])) {
        $output .= $this->current_mega_context['right_html'];
      }
      $output .= "</div></div>\n";
      $this->current_mega_context = [
        'wrapper_class' => 'pg-mega-wrapper',
        'right_html'    => '',
        'has_right'     => false,
      ];
    } else {
      $output .= "{$indent}</ul>\n";
    }
  }

  public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
    $title = apply_filters('the_title', $item->title, $item->ID);
    $level       = $depth + 1;
    $depth_li    = 'depth-' . $depth;
    $text_class  = 'text-menu-' . $level;

    $li_classes = ['menu-item-' . (int)$item->ID, $depth_li];
    if ($depth === 0) {
      $this->current_top_item = $item;
    }
    if ($depth === 0 && !empty($args->has_children)) {
      $li_classes[] = 'menu-item-has-children';
      $li_classes[] = 'has-mega';
    }
    $output .= '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

    $base_link_class = ($depth === 1 ? 'mega-link' : 'menu-link') . ' ' . $text_class;
    $atts = [
      'href'   => !empty($item->url) ? $item->url : '',
      'class'  => $base_link_class,
      'target' => !empty($item->target) ? $item->target : '',
      'rel'    => !empty($item->xfn) ? $item->xfn : '',
      'title'  => !empty($item->attr_title) ? $item->attr_title : '',
    ];
    $attr = '';
    foreach ($atts as $k => $v) if (!empty($v)) $attr .= ' '.$k.'="'.esc_attr($v).'"';

    if ($depth === 1) {
      $img = pg_menu_item_thumb_url($item, 'menu-thumb');
      $thumbFlag = $img ? 'has-thumb' : 'no-thumb';
      $output .= '<a'.$attr.'>';
        $output .= '<span class="mega-thumb-wrap '.$thumbFlag.'">';
        if ($img) {
          $output .= '<span class="mega-thumb"><img src="'.esc_url($img).'" alt="'.esc_attr($title).'" loading="lazy" decoding="async"></span>';
        }
        $output .= '</span>';
        $output .= '<span class="mega-title '.$text_class.'">'.esc_html($title).'</span>';
      $output .= '</a>';
    } else {
      $output .= '<a'.$attr.'><span class="menu-text '.$text_class.'">'.esc_html($title).'</span></a>';
    }
  }

  public function end_el( &$output, $item, $depth = 0, $args = [] ){
    $output .= "</li>\n";
    if ($depth === 0) {
      $this->current_top_item = null;
    }
  }
}

add_filter('nav_menu_css_class', function($classes, $item, $args, $depth){
  if (isset($args->walker) && $args->walker instanceof PG_Mega_Walker && $depth === 0) {
    if (in_array('menu-item-has-children', (array)$item->classes, true)) {
      $classes[] = 'has-mega';
    }
  }
  return $classes;
}, 10, 4);

/* =========================
 * Helper: lấy ID trang settings theo template
 * ========================= */
function pg_get_settings_page_id_by_template( $template_file ) {
  $q = new WP_Query([
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'meta_key'       => '_wp_page_template',
    'meta_value'     => $template_file,
    'posts_per_page' => 1,
    'fields'         => 'ids',
  ]);
  return $q->have_posts() ? (int) $q->posts[0] : 0;
}

/* =========================
 * AJAX filter đại lý
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
    echo '<p>' . esc_html__('Không có bài nào.', 'peugeot-theme') . '</p>';
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

/* Nạp card đại lý (nếu có) */
add_action('after_setup_theme', function () {
  $file = get_template_directory() . '/inc/agency-card.php';
  if (file_exists($file)) require_once $file;
});

/* =========================
 * (ĐÃ XOÁ) CPT nội bộ — chuyển sang MU-plugin/peugeot-forms.php
 * ========================= */

/* =========================
 * Form helpers + validate (giữ nguyên, chỉ bọc dịch)
 * ========================= */
if (!function_exists('td_form_context')) {
  function td_form_context(): string {
    if (is_page_template('page-dat-lich-lai-thu.php')) return 'test_drive';
    if (is_page_template('page-lien-he.php'))         return 'lien_he';
    return '';
  }
}

add_action('wp_enqueue_scripts', function () {
  $ctx = td_form_context();
  if (!$ctx) return;

  wp_enqueue_style('td-form', get_template_directory_uri().'/assets/css/td-form.css', [], '1.2');
  wp_enqueue_script('td-form', get_template_directory_uri().'/assets/js/td-form.js', ['jquery'], '1.2', true);

  wp_localize_script('td-form', 'TD_AJAX', [
    'url'     => admin_url('admin-ajax.php'),
    'nonce'   => wp_create_nonce('td_validate'),
    'context' => $ctx,
  ]);
});

add_action('acf/save_post', function ($post_id) {
  $pt = get_post_type($post_id);
  if (!in_array($pt, ['lich_lai_thu','lien_he'], true)) return;

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
    $car    = $car_id ? get_the_title($car_id) : esc_html__('Không rõ xe', 'peugeot-theme');
    $title  = sprintf('[%s] %s %s – %s – %s',
              esc_html__('Lái thử', 'peugeot-theme'),
              $salute, $name, $car, wp_date('d/m/Y H:i'));
  } else {
    $yeu_cau = $get('yeu_cau');
    $title   = sprintf('[%s] %s %s – %s – %s',
              esc_html__('Liên hệ', 'peugeot-theme'),
              $salute, $name, ($yeu_cau ?: esc_html__('Không rõ yêu cầu', 'peugeot-theme')), wp_date('d/m/Y H:i'));
  }

  remove_action('acf/save_post', __FUNCTION__);
  wp_update_post(['ID'=>$post_id, 'post_title'=>$title]);
  add_action('acf/save_post', __FUNCTION__);
}, 20);

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

add_action('wp_ajax_td_validate_field',    'td_validate_field');
add_action('wp_ajax_nopriv_td_validate_field', 'td_validate_field');

function td_validate_field() {
  check_ajax_referer('td_validate', 'nonce');

  $ctx   = isset($_POST['context']) ? sanitize_text_field(wp_unslash($_POST['context'])) : '';
  if (!in_array($ctx, ['test_drive','lien_he'], true)) {
    wp_send_json_success(['ok'=>true, 'message'=>'']);
  }

  $key   = isset($_POST['key'])   ? sanitize_text_field(wp_unslash($_POST['key']))   : '';
  $value = isset($_POST['value']) ? wp_unslash($_POST['value']) : '';
  $value = is_string($value) ? trim($value) : $value;

  $ok = true; $msg = '';
  $required = td_required_keys($ctx);

  if (in_array($key, $required, true)) {
    if ($value === '' || $value === '0' || $value === '---') {
      wp_send_json_success(['ok'=>false, 'message'=>__('Trường này bắt buộc.', 'peugeot-theme')]);
    }
  }

  if (in_array($key, ['field_68c23a2eeb753','field_68c45ac4a5eca'], true)) {
    $digits = preg_replace('/\D+/', '', (string)$value);
    if (!preg_match('/^(0\d{9,10}|84\d{9,10})$/', $digits)) {
      $ok=false; $msg=__('Số điện thoại không hợp lệ.', 'peugeot-theme');
    }
  }

  if (in_array($key, ['field_68c23a3ceb754','field_68c45ac4a9aa1'], true)) {
    if (!is_email($value)) { $ok=false; $msg=__('Email không hợp lệ.', 'peugeot-theme'); }
    else {
      $dupe = new WP_Query([
        'post_type'      => ['lich_lai_thu','lien_he'],
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'meta_query'     => [[ 'key'=>'email', 'value'=>$value, 'compare'=>'=' ]],
        'no_found_rows'  => true,
      ]);
      if ($dupe->have_posts()) { $ok=false; $msg=__('Email này đã được gửi trước đó.', 'peugeot-theme'); }
      wp_reset_postdata();
    }
  }

  wp_send_json_success(['ok'=>$ok, 'message'=>$msg]);
}

if (!function_exists('td_render')) {
  function td_render($key, $label = null, $placeholder = null){
    if (!function_exists('acf_get_field')) {
      echo '<p>' . esc_html__('ACF chưa kích hoạt.', 'peugeot-theme') . '</p>';
      return;
    }
    $field = acf_get_field($key);
    if (!$field) {
      echo '<p class="td-missing">' . sprintf(esc_html__('Không tìm thấy field: %s', 'peugeot-theme'), esc_html($key)) . '</p>';
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
// Xoá generator meta (hiển thị version WP)
remove_action('wp_head', 'wp_generator');

// Xoá version trong link CSS/JS
function remove_wp_version_strings($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'remove_wp_version_strings', 9999);
add_filter('script_loader_src', 'remove_wp_version_strings', 9999);

// Ẩn phiên bản trong RSS
add_filter('the_generator', '__return_empty_string');