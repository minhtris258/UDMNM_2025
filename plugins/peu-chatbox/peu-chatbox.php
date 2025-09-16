<?php
/**
 * Plugin Name: PEU Chatbox
 * Description: Nút chat nổi có badge, kịch bản hỏi–đáp nhanh, đổi màu/logo, và webhook gọi API ngoài.
 * Version: 1.0.0
 * Author: MinhTris
 * Text Domain: peu-chatbox
 */

if (!defined('ABSPATH')) exit;

define('PEU_CB_VER', '1.0.0');
define('PEU_CB_URL', plugin_dir_url(__FILE__));
define('PEU_CB_PATH', plugin_dir_path(__FILE__));

/* ---------------------------
 * SETTINGS (Settings API)
 * --------------------------- */
function peu_cb_default_options() {
  return [
    'enabled'         => 1,
    'position'        => 'right',         // right|left
    'offset_vh'       => 3,               // khoảng cách đáy (vh)
    'offset_px'       => 24,              // khoảng cách ngang (px)
    'primary'         => '#0ea5e9',       // màu chủ đạo
    'text'            => '#ffffff',
    'bubble_bg'       => '#101114',
    'bubble_text'     => '#ffffff',
    'logo_id'         => 0,
    'title'           => 'Chăm sóc Khách hàng',
    'greeting'        => 'Chúng tôi sẵn sàng trợ giúp. Hãy chọn một mục bên dưới!',
    'start_badge'     => 1,
    'test_drive_url'  => home_url('/dat-lich-lai-thu/'),
    'api_url'         => '',              // webhook tùy chọn
    'api_key'         => '',              // header Authorization: Bearer
    // Kịch bản nhanh (JSON)
    'quick_flows'     => wp_json_encode([
      [
        'label' => 'Đăng ký lái thử',
        'reply' => 'Quý khách vui lòng cho biết khu vực sinh sống?',
        'choices' => [
          ['label'=>'Khu vực Miền Bắc',  'value'=>'north'],
          ['label'=>'Khu vực Miền Trung','value'=>'central'],
          ['label'=>'Khu vực Miền Nam',  'value'=>'south'],
        ],
        'then' => [
          'north'   => 'Cảm ơn! Nhân viên khu vực **Miền Bắc** sẽ liên hệ. Bạn có thể đặt lịch tại: {{test_drive_url}}',
          'central' => 'Cảm ơn! Nhân viên khu vực **Miền Trung** sẽ liên hệ. Bạn có thể đặt lịch tại: {{test_drive_url}}',
          'south'   => 'Cảm ơn! Nhân viên khu vực **Miền Nam** sẽ liên hệ. Bạn có thể đặt lịch tại: {{test_drive_url}}'
        ]
      ],
      [
        'label' => 'Tư vấn sản phẩm',
        'reply' => 'Bạn muốn tìm dòng xe nào? (ví dụ: 2008, 3008, 5008…)'
      ]
    ]),
  ];
}

add_action('admin_init', function () {
  register_setting('peu_cb_group', 'peu_cb_options', [
    'type' => 'array',
    'sanitize_callback' => function($in){
      $d = peu_cb_default_options();
      $out = is_array($in) ? $in : [];
      $out = wp_parse_args($out, $d);
      $out['enabled']   = empty($in['enabled']) ? 0 : 1;
      $out['position']  = in_array(($in['position'] ?? 'right'), ['left','right'], true) ? $in['position'] : 'right';
      $out['offset_vh'] = max(0, intval($in['offset_vh'] ?? $d['offset_vh']));
      $out['offset_px'] = max(0, intval($in['offset_px'] ?? $d['offset_px']));
      $out['logo_id']   = absint($in['logo_id'] ?? 0);
      $out['quick_flows'] = is_string($in['quick_flows']) ? $in['quick_flows'] : $d['quick_flows'];
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
        <tr><th>Kích hoạt</th>
          <td><label><input type="checkbox" name="peu_cb_options[enabled]" value="1" <?php checked($o['enabled']); ?>> Bật widget</label></td>
        </tr>
        <tr><th>Vị trí</th>
          <td>
            <label><input type="radio" name="peu_cb_options[position]" value="right" <?php checked($o['position'],'right'); ?>> Bên phải</label>&nbsp;
            <label><input type="radio" name="peu_cb_options[position]" value="left"  <?php checked($o['position'],'left');  ?>> Bên trái</label>
            <p class="description">Offset đáy (vh) <input type="number" name="peu_cb_options[offset_vh]" value="<?php echo esc_attr($o['offset_vh']); ?>" class="small-text">, Ngang (px) <input type="number" name="peu_cb_options[offset_px]" value="<?php echo esc_attr($o['offset_px']); ?>" class="small-text"></p>
          </td>
        </tr>
        <tr><th>Giao diện</th>
          <td>
            Màu chủ đạo <input type="text" name="peu_cb_options[primary]" value="<?php echo esc_attr($o['primary']); ?>" class="regular-text" style="max-width:120px">
            &nbsp;Màu chữ nút <input type="text" name="peu_cb_options[text]" value="<?php echo esc_attr($o['text']); ?>" class="regular-text" style="max-width:120px"><br>
            Nền khung <input type="text" name="peu_cb_options[bubble_bg]" value="<?php echo esc_attr($o['bubble_bg']); ?>" class="regular-text" style="max-width:120px">
            &nbsp;Chữ khung <input type="text" name="peu_cb_options[bubble_text]" value="<?php echo esc_attr($o['bubble_text']); ?>" class="regular-text" style="max-width:120px">
          </td>
        </tr>
        <tr><th>Logo khung chat</th><td><?php peu_cb_field_media('logo_id', $o['logo_id']); ?></td></tr>
        <tr><th>Tiêu đề & Lời chào</th>
          <td>
            <input type="text" name="peu_cb_options[title]" value="<?php echo esc_attr($o['title']); ?>" class="regular-text" placeholder="Tiêu đề"><br>
            <textarea name="peu_cb_options[greeting]" rows="3" class="large-text" placeholder="Lời chào"><?php echo esc_textarea($o['greeting']); ?></textarea><br>
            Badge khởi tạo <input type="number" name="peu_cb_options[start_badge]" value="<?php echo esc_attr($o['start_badge']); ?>" class="small-text">
          </td>
        </tr>
        <tr><th>Link đặt lịch lái thử</th>
          <td><input type="url" name="peu_cb_options[test_drive_url]" class="regular-text" value="<?php echo esc_url($o['test_drive_url']); ?>"></td>
        </tr>
        <tr><th>Webhook API (tuỳ chọn)</th>
          <td>
            URL <input type="url" name="peu_cb_options[api_url]" class="regular-text" value="<?php echo esc_url($o['api_url']); ?>"><br>
            API key (Bearer) <input type="text" name="peu_cb_options[api_key]" class="regular-text" value="<?php echo esc_attr($o['api_key']); ?>">
            <p class="description">Nếu điền, mỗi tin người dùng gửi sẽ POST JSON đến webhook.</p>
          </td>
        </tr>
        <tr><th>Kịch bản nhanh (JSON)</th>
          <td>
<textarea name="peu_cb_options[quick_flows]" rows="12" class="large-text code"><?php echo esc_textarea($o['quick_flows']); ?></textarea>
<p class="description">Sửa nhãn, câu trả lời, lựa chọn. Có thể dùng biến <code>{{test_drive_url}}</code>.</p>
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
add_action('wp_enqueue_scripts', function(){
  $o = wp_parse_args(get_option('peu_cb_options', []), peu_cb_default_options());
  if (empty($o['enabled'])) return;

  wp_enqueue_style('peu-cb', PEU_CB_URL.'assets/chatbox.css', [], PEU_CB_VER);
  wp_enqueue_script('peu-cb', PEU_CB_URL.'assets/chatbox.js', [], PEU_CB_VER, true);

  $logo = $o['logo_id'] ? wp_get_attachment_image_url($o['logo_id'], 'thumbnail') : '';
  $flows = json_decode($o['quick_flows'], true);
  if (!is_array($flows)) $flows = [];

  wp_localize_script('peu-cb', 'PEU_CB', [
    'position'   => $o['position'],
    'offset_vh'  => (int)$o['offset_vh'],
    'offset_px'  => (int)$o['offset_px'],
    'primary'    => $o['primary'],
    'text'       => $o['text'],
    'bubble_bg'  => $o['bubble_bg'],
    'bubble_text'=> $o['bubble_text'],
    'title'      => $o['title'],
    'greeting'   => $o['greeting'],
    'badge'      => (int)$o['start_badge'],
    'logo'       => $logo,
    'flows'      => $flows,
    'vars'       => ['test_drive_url' => $o['test_drive_url']],
    'rest'       => [
      'ajax'   => admin_url('admin-ajax.php'),
      'nonce'  => wp_create_nonce('peu_cb_ajax')
    ],
    'webhook'    => [
      'url' => $o['api_url'],
      'key' => $o['api_key']
    ]
  ]);
});

/* AJAX: forward message to webhook nếu cấu hình */
add_action('wp_ajax_nopriv_peu_cb_forward', 'peu_cb_forward');
add_action('wp_ajax_peu_cb_forward', 'peu_cb_forward');
function peu_cb_forward(){
  check_ajax_referer('peu_cb_ajax', 'nonce');
  $msg = sanitize_text_field(wp_unslash($_POST['message'] ?? ''));
  $meta = wp_unslash($_POST['meta'] ?? []);
  $opt = wp_parse_args(get_option('peu_cb_options', []), peu_cb_default_options());
  $url = $opt['api_url']; $key = $opt['api_key'];

  if (!$url) wp_send_json_success(['ok'=>true,'skipped'=>true]);

  $resp = wp_remote_post($url, [
    'timeout'=>8,
    'headers'=>[
      'Content-Type'=>'application/json',
      'Authorization'=> $key ? ('Bearer '.$key) : ''
    ],
    'body'=> wp_json_encode([
      'message'=>$msg,
      'meta'=>$meta,
      'site'=>home_url()
    ])
  ]);

  if (is_wp_error($resp)) {
    wp_send_json_error(['error'=>$resp->get_error_message()]);
  } else {
    $body = wp_remote_retrieve_body($resp);
    wp_send_json_success(['ok'=>true,'response'=>$body]);
  }
}
