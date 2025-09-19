<?php
/**
 * Plugin Name: PEU Chatbox
 * Description: Nút chat nổi có badge, kịch bản hỏi–đáp nhanh, đa ngôn ngữ (Polylang/Loco), và webhook gọi API ngoài.
 * Version: 1.2.0
 * Author: MinhTris
 * Text Domain: peu-chatbox
 */

if (!defined('ABSPATH')) exit;

define('PEU_CB_VER', '1.2.0');
define('PEU_CB_URL', plugin_dir_url(__FILE__));
define('PEU_CB_PATH', plugin_dir_path(__FILE__));

/* ---------------------------
 * SETTINGS (Settings API)
 * --------------------------- */
function peu_cb_default_options() {
  return [
    'enabled'         => 1,
    'position'        => 'right',         // right|left
    'offset_vh'       => 3,
    'offset_px'       => 24,
    'primary'         => '#0ea5e9',
    'text'            => '#ffffff',
    'bubble_bg'       => '#101114',
    'bubble_text'     => '#ffffff',
    'logo_id'         => 0,
    'title'           => 'Chăm sóc Khách hàng',
    'greeting'        => 'Chúng tôi sẵn sàng trợ giúp. Hãy chọn một mục bên dưới!',
    'start_badge'     => 1,

    // Liên kết tùy chỉnh theo ngôn ngữ (để trống sẽ fallback Polylang)
    'contact_url_vi'     => '',
    'contact_url_en'     => '',
    'test_drive_url_vi'  => '',
    'test_drive_url_en'  => '',

    // (giữ backward-compat nếu trước đây bạn cấu hình 1 link chung)
    'contact_url'        => '',
    'test_drive_url'     => '',

    // Webhook tùy chọn
    'api_url'         => '',
    'api_key'         => '',

    // Kịch bản nhanh (JSON)
    'quick_flows'     => wp_json_encode([
      [
        'label'   => 'Đăng ký lái thử',
        'reply'   => 'Quý khách vui lòng cho biết khu vực sinh sống?',
        'choices' => [
          ['label'=>'Khu vực Miền Bắc',  'value'=>'north'],
          ['label'=>'Khu vực Miền Trung','value'=>'central'],
          ['label'=>'Khu vực Miền Nam',  'value'=>'south'],
        ],
        'then' => [
          'north'   => 'Cảm ơn! Nhân viên khu vực **Miền Bắc** sẽ liên hệ. Bạn có thể đặt lịch tại: {{test_drive_url}}',
          'central' => 'Cảm ơn! Nhân viên khu vực **Miền Trung** sẽ liên hệ. Bạn có thể đặt lịch tại: {{test_drive_url}}',
          'south'   => 'Cảm ơn! Nhân viên khu vực **Miền Nam** sẽ liên hệ. Bạn có thể đặt lịch tại: {{test_drive_url}}',
        ],
      ],
      [
        'label' => 'Tư vấn sản phẩm',
        'reply' => 'Bạn muốn tìm dòng xe nào? (ví dụ: 2008, 3008, 5008…)',
      ],
    ]),
  ];
}

add_action('admin_init', function () {
  register_setting('peu_cb_group', 'peu_cb_options', [
    'type' => 'array',
    'sanitize_callback' => function($in){
      $d   = peu_cb_default_options();
      $out = is_array($in) ? $in : [];
      $out = wp_parse_args($out, $d);

      $out['enabled']        = empty($in['enabled']) ? 0 : 1;
      $out['position']       = in_array(($in['position'] ?? 'right'), ['left','right'], true) ? $in['position'] : 'right';
      $out['offset_vh']      = max(0, intval($in['offset_vh'] ?? $d['offset_vh']));
      $out['offset_px']      = max(0, intval($in['offset_px'] ?? $d['offset_px']));
      $out['logo_id']        = absint($in['logo_id'] ?? 0);

      $out['primary']        = sanitize_text_field($in['primary'] ?? $d['primary']);
      $out['text']           = sanitize_text_field($in['text'] ?? $d['text']);
      $out['bubble_bg']      = sanitize_text_field($in['bubble_bg'] ?? $d['bubble_bg']);
      $out['bubble_text']    = sanitize_text_field($in['bubble_text'] ?? $d['bubble_text']);
      $out['title']          = sanitize_text_field($in['title'] ?? $d['title']);
      $out['greeting']       = sanitize_textarea_field($in['greeting'] ?? $d['greeting']);
      $out['start_badge']    = max(0, intval($in['start_badge'] ?? $d['start_badge']));

      // Links theo ngôn ngữ
      $out['contact_url_vi']    = isset($in['contact_url_vi'])    ? esc_url_raw($in['contact_url_vi'])    : $d['contact_url_vi'];
      $out['contact_url_en']    = isset($in['contact_url_en'])    ? esc_url_raw($in['contact_url_en'])    : $d['contact_url_en'];
      $out['test_drive_url_vi'] = isset($in['test_drive_url_vi']) ? esc_url_raw($in['test_drive_url_vi']) : $d['test_drive_url_vi'];
      $out['test_drive_url_en'] = isset($in['test_drive_url_en']) ? esc_url_raw($in['test_drive_url_en']) : $d['test_drive_url_en'];

      // Backward-compat: nếu vẫn còn field cũ
      $out['contact_url']       = isset($in['contact_url'])       ? esc_url_raw($in['contact_url'])       : $out['contact_url'];
      $out['test_drive_url']    = isset($in['test_drive_url'])    ? esc_url_raw($in['test_drive_url'])    : $out['test_drive_url'];

      $out['api_url']        = isset($in['api_url']) ? esc_url_raw($in['api_url']) : '';
      $out['api_key']        = sanitize_text_field($in['api_key'] ?? '');

      $out['quick_flows']    = is_string($in['quick_flows']) ? $in['quick_flows'] : $d['quick_flows'];
      return $out;
    }
  ]);
});

add_action('admin_menu', function () {
  add_options_page(
    'PEU Chatbox',
    'PEU Chatbox',
    'manage_options',
    'peu-chatbox',
    'peu_cb_render_settings'
  );
});

function peu_cb_field_media($field, $val){
  $img = $val ? wp_get_attachment_image_url($val, 'thumbnail') : '';
  ?>
<div class="peu-media" data-target="<?php echo esc_attr($field); ?>">
    <div class="preview" style="margin-bottom:8px">
        <?php echo $img ? '<img src="'.esc_url($img).'" style="max-height:60px">' : '<em>Chưa chọn</em>'; ?>
    </div>
    <input type="hidden" name="peu_cb_options[<?php echo esc_attr($field); ?>]" value="<?php echo esc_attr($val); ?>">
    <button type="button" class="button choose">Chọn ảnh</button>
    <button type="button" class="button clear">Xoá</button>
</div>
<?php
}

function peu_cb_render_settings(){
  if (!current_user_can('manage_options')) return;
  $o = wp_parse_args(get_option('peu_cb_options', []), peu_cb_default_options());
  ?>
<div class="wrap">
    <h1>PEU Chatbox</h1>
    <form method="post" action="options.php">
        <?php settings_fields('peu_cb_group'); ?>
        <table class="form-table" role="presentation">
            <tr>
                <th>Kích hoạt</th>
                <td><label><input type="checkbox" name="peu_cb_options[enabled]" value="1"
                            <?php checked($o['enabled']); ?>> Bật widget</label></td>
            </tr>
            <tr>
                <th>Vị trí</th>
                <td>
                    <label><input type="radio" name="peu_cb_options[position]" value="right"
                            <?php checked($o['position'],'right'); ?>> Bên phải</label>&nbsp;
                    <label><input type="radio" name="peu_cb_options[position]" value="left"
                            <?php checked($o['position'],'left');  ?>> Bên trái</label>
                    <p class="description">Offset đáy (vh) <input type="number" name="peu_cb_options[offset_vh]"
                            value="<?php echo esc_attr($o['offset_vh']); ?>" class="small-text">,
                        Ngang (px) <input type="number" name="peu_cb_options[offset_px]"
                            value="<?php echo esc_attr($o['offset_px']); ?>" class="small-text"></p>
                </td>
            </tr>
            <tr>
                <th>Giao diện</th>
                <td>
                    Màu chủ đạo <input type="text" name="peu_cb_options[primary]"
                        value="<?php echo esc_attr($o['primary']); ?>" class="regular-text" style="max-width:120px">
                    &nbsp;Màu chữ nút <input type="text" name="peu_cb_options[text]"
                        value="<?php echo esc_attr($o['text']); ?>" class="regular-text" style="max-width:120px"><br>
                    Nền khung <input type="text" name="peu_cb_options[bubble_bg]"
                        value="<?php echo esc_attr($o['bubble_bg']); ?>" class="regular-text" style="max-width:120px">
                    &nbsp;Chữ khung <input type="text" name="peu_cb_options[bubble_text]"
                        value="<?php echo esc_attr($o['bubble_text']); ?>" class="regular-text" style="max-width:120px">
                </td>
            </tr>
            <tr>
                <th>Logo khung chat</th>
                <td><?php peu_cb_field_media('logo_id', $o['logo_id']); ?></td>
            </tr>
            <tr>
                <th>Tiêu đề & Lời chào</th>
                <td>
                    <input type="text" name="peu_cb_options[title]" value="<?php echo esc_attr($o['title']); ?>"
                        class="regular-text" placeholder="Tiêu đề"><br>
                    <textarea name="peu_cb_options[greeting]" rows="3" class="large-text"
                        placeholder="Lời chào"><?php echo esc_textarea($o['greeting']); ?></textarea><br>
                    Badge khởi tạo <input type="number" name="peu_cb_options[start_badge]"
                        value="<?php echo esc_attr($o['start_badge']); ?>" class="small-text">
                </td>
            </tr>

            <tr>
                <th>Link liên hệ / khiếu nại</th>
                <td>
                    <label>VN:&nbsp;<input type="url" name="peu_cb_options[contact_url_vi]" class="regular-text"
                            value="<?php echo esc_url($o['contact_url_vi']); ?>"></label><br>
                    <label>EN:&nbsp;<input type="url" name="peu_cb_options[contact_url_en]" class="regular-text"
                            value="<?php echo esc_url($o['contact_url_en']); ?>"></label>
                    <p class="description">Để trống sẽ tự lấy theo Polylang từ slug <code>lien-he-khieu-nai</code> (tự
                        map sang EN nếu có bản dịch). Nếu trước đây bạn từng cấu hình <em>1 link chung</em>, plugin vẫn
                        tự dùng như dự phòng.</p>
                </td>
            </tr>

            <tr>
                <th>Link đặt lịch lái thử</th>
                <td>
                    <label>VN:&nbsp;<input type="url" name="peu_cb_options[test_drive_url_vi]" class="regular-text"
                            value="<?php echo esc_url($o['test_drive_url_vi']); ?>"></label><br>
                    <label>EN:&nbsp;<input type="url" name="peu_cb_options[test_drive_url_en]" class="regular-text"
                            value="<?php echo esc_url($o['test_drive_url_en']); ?>"></label>
                    <p class="description">Để trống sẽ tự lấy theo Polylang từ slug <code>dang-ky-lai-thu</code>.</p>
                </td>
            </tr>

            <tr>
                <th>Webhook API (tuỳ chọn)</th>
                <td>
                    URL <input type="url" name="peu_cb_options[api_url]" class="regular-text"
                        value="<?php echo esc_url($o['api_url']); ?>"><br>
                    API key (Bearer) <input type="text" name="peu_cb_options[api_key]" class="regular-text"
                        value="<?php echo esc_attr($o['api_key']); ?>">
                    <p class="description">Nếu điền, mỗi tin người dùng gửi sẽ POST JSON đến webhook.</p>
                </td>
            </tr>
            <tr>
                <th>Kịch bản nhanh (JSON)</th>
                <td>
                    <textarea name="peu_cb_options[quick_flows]" rows="12"
                        class="large-text code"><?php echo esc_textarea($o['quick_flows']); ?></textarea>
                    <p class="description">Có thể dùng biến <code>{{contact_url}}</code> và
                        <code>{{test_drive_url}}</code> trong câu trả lời.</p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<?php
}

/* Media uploader nhỏ cho field logo */
add_action('admin_enqueue_scripts', function($hook){
  if ($hook !== 'settings_page_peu-chatbox') return;
  wp_enqueue_media();
  wp_add_inline_script('jquery-core', <<<JS
jQuery(function($){
  $('.peu-media .choose').on('click', function(){
    var wrap=$(this).closest('.peu-media');
    var f=wp.media({title:'Chọn logo',library:{type:'image'},multiple:false});
    f.on('select', function(){
      var att=f.state().get('selection').first().toJSON();
      wrap.find('input[type=hidden]').val(att.id);
      var url=att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
      wrap.find('.preview').html('<img src="'+url+'" style="max-height:60px">');
    });
    f.open();
  });
  $('.peu-media .clear').on('click', function(){
    var wrap=$(this).closest('.peu-media');
    wrap.find('input[type=hidden]').val('');
    wrap.find('.preview').html('<em>Chưa chọn</em>');
  });
});
JS);
});

/* ---------------------------
 * FRONTEND
 * --------------------------- */
add_action('wp_enqueue_scripts', function () {
  // Load assets từ plugin
  wp_enqueue_style('peu-cb', PEU_CB_URL . 'assets/chatbox.css', [], PEU_CB_VER);
  wp_enqueue_script('peu-cb', PEU_CB_URL . 'assets/chatbox.js', [], PEU_CB_VER, true);

  // Lấy options
  $o = wp_parse_args(get_option('peu_cb_options', []), peu_cb_default_options());

  // Ngôn ngữ hiện tại
  $lang = function_exists('pll_current_language') ? pll_current_language('slug') : 'vi';

  // Helper: get permalink by base slug + translation
  $get_translated_link = function($base_slug, $fallback_path = null) use ($lang){
    $pg = get_page_by_path($base_slug);
    $id = $pg ? $pg->ID : 0;
    if (function_exists('pll_get_post') && $id) {
      $tr = pll_get_post($id, $lang);
      if ($tr) $id = $tr;
    }
    if ($id) return get_permalink($id);
    return $fallback_path ? home_url($fallback_path) : home_url('/');
  };

  // ==== Chọn link theo NGÔN NGỮ ====
  // Liên hệ/khiếu nại
  if ($lang === 'en') {
    $contact_url = trim($o['contact_url_en']) ?: trim($o['contact_url']) ?: $get_translated_link('lien-he-khieu-nai', '/lien-he-khieu-nai/');
    $test_drive_url = trim($o['test_drive_url_en']) ?: trim($o['test_drive_url']) ?: $get_translated_link('dang-ky-lai-thu', '/dang-ky-lai-thu/');
  } else {
    $contact_url = trim($o['contact_url_vi']) ?: trim($o['contact_url']) ?: $get_translated_link('lien-he-khieu-nai', '/lien-he-khieu-nai/');
    $test_drive_url = trim($o['test_drive_url_vi']) ?: trim($o['test_drive_url']) ?: $get_translated_link('dang-ky-lai-thu', '/dang-ky-lai-thu/');
  }

  // i18n chuỗi UI (Loco bắt được)
  $i18n = [
    'title'    => __('Hỗ trợ trực tuyến', 'peu-chatbox'),
    'greeting' => __('Xin chào! Mình có thể giúp gì cho bạn? 👋', 'peu-chatbox'),
    'default'  => __('Cảm ơn bạn! Nhân viên sẽ sớm liên hệ.', 'peu-chatbox'),
  ];

  // FAQ theo ngôn ngữ
  if ($lang === 'en') {
    $faq = [
      'complaint'   => 'Sorry for the issue. Please submit here: {{contact_url}}',
      'warranty'    => 'Warranty info: ' . site_url('/en/warranty/'),
      'maintenance' => 'Maintenance schedule & booking: ' . site_url('/en/maintenance/'),
      'test drive'  => 'Register for a test drive: {{test_drive_url}}',
      'price'       => 'Pricing & promotions: ' . site_url('/en/pricing/'),
      'buy car'     => 'Book a consultation: ' . site_url('/en/sales-consultation/'),
    ];
  } else {
    $faq = [
      'khiếu nại'   => 'Rất xin lỗi vì sự bất tiện. Bạn có thể gửi khiếu nại tại: {{contact_url}}',
      'khieu nai'   => 'Rất xin lỗi vì sự bất tiện. Bạn có thể gửi khiếu nại tại: {{contact_url}}',
      'complaint'   => 'Rất xin lỗi vì sự bất tiện. Bạn có thể gửi khiếu nại tại: {{contact_url}}',
      'bảo hành'    => 'Thông tin bảo hành: ' . site_url('/bao-hanh/'),
      'bảo dưỡng'   => 'Lịch bảo dưỡng & đặt lịch: ' . site_url('/bao-duong/'),
      'test drive'  => 'Đăng ký lái thử tại: {{test_drive_url}}',
      'lái thử'     => 'Đăng ký lái thử tại: {{test_drive_url}}',
      'giá'         => 'Bảng giá & ưu đãi: ' . site_url('/bang-gia/'),
      'mua xe'      => 'Đặt lịch tư vấn mua xe: ' . site_url('/tu-van-mua-xe/'),
    ];
  }

  // LOGO
  $logo = $o['logo_id'] ? wp_get_attachment_image_url($o['logo_id'], 'thumbnail') : (get_site_icon_url() ?: '');

  // Localize config cho JS
  $opts = [
    'lang'         => $lang,
    'i18n'         => $i18n,

    'position'     => $o['position'],
    'offset_px'    => (int) $o['offset_px'],
    'offset_vh'    => (int) $o['offset_vh'],
    'primary'      => $o['primary'],
    'text'         => $o['text'],
    'bubble_bg'    => $o['bubble_bg'],
    'bubble_text'  => $o['bubble_text'],
    'title'        => $o['title'],
    'greeting'     => $o['greeting'],
    'badge'        => (int) $o['start_badge'],
    'logo'         => $logo,

    'flows'        => json_decode($o['quick_flows'], true) ?: [],
    'faq'          => $faq,

    'vars'         => [
      'contact_url'    => $contact_url,
      'test_drive_url' => $test_drive_url,
    ],

    'rest'         => [
      'ajax'   => admin_url('admin-ajax.php'),
      'nonce'  => wp_create_nonce('peu_cb'),
    ],
    'webhook'      => [
      'url' => $o['api_url'],
      'key' => $o['api_key'],
    ],
  ];

  wp_add_inline_script('peu-cb', 'window.PEU_CB = ' . wp_json_encode($opts) . ';', 'before');
});

/* ---------------------------
 * AJAX: forward message to webhook (optional)
 * --------------------------- */
add_action('wp_ajax_nopriv_peu_cb_forward', 'peu_cb_forward');
add_action('wp_ajax_peu_cb_forward', 'peu_cb_forward');
function peu_cb_forward(){
  check_ajax_referer('peu_cb','nonce');
  $msg  = sanitize_text_field(wp_unslash($_POST['message'] ?? ''));
  $meta = wp_unslash($_POST['meta'] ?? '');

  $opt = wp_parse_args(get_option('peu_cb_options', []), peu_cb_default_options());
  $url = trim($opt['api_url']);
  $key = trim($opt['api_key']);

  if (!$url) wp_send_json_success(['ok'=>true,'skipped'=>true]);

  $resp = wp_remote_post($url, [
    'timeout'=> 8,
    'headers'=> [
      'Content-Type'=>'application/json',
      'Authorization'=> $key ? ('Bearer '.$key) : '',
    ],
    'body'=> wp_json_encode([
      'message'=> $msg,
      'meta'   => $meta,
      'site'   => home_url(),
    ]),
  ]);

  if (is_wp_error($resp)) {
    wp_send_json_error(['error'=>$resp->get_error_message()]);
  } else {
    wp_send_json_success(['ok'=>true,'response'=> wp_remote_retrieve_body($resp)]);
  }
}