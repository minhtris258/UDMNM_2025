<?php
/**
 * Plugin Name: Admin Logo
 * Description: Đổi logo trang đăng nhập (wp-login) và logo thanh admin. Có trang cấu hình trong Cài đặt.
 * Version: 2.05.0
 * Author: MinhTris
 * Text Domain: peugeot-admin-logo
 */

if (!defined('ABSPATH')) exit;

/* i18n */
add_action('plugins_loaded', function(){
  load_plugin_textdomain('peugeot-admin-logo', false, dirname(plugin_basename(__FILE__)).'/languages');
});

/* --------- OPTIONS --------- */
function peu_al_get_options() {
  $defaults = [
    'login_logo_id'     => 0,
    'login_width'       => 200,
    'login_height'      => 80,
    'adminbar_logo_id'  => 0,
  ];
  $opt = get_option('peu_admin_logo_options', []);
  return wp_parse_args(is_array($opt) ? $opt : [], $defaults);
}

/* --------- SETTINGS PAGE --------- */
add_action('admin_menu', function(){
  add_options_page(
    __('Peugeot Admin Logo', 'peugeot-admin-logo'),
    __('Peugeot Admin Logo', 'peugeot-admin-logo'),
    'manage_options',
    'peu-admin-logo',
    'peu_al_render_settings_page'
  );
});

add_action('admin_init', function(){
  register_setting('peu_admin_logo_group', 'peu_admin_logo_options', [
    'type' => 'array',
    'sanitize_callback' => function($input){
      $out = [];
      $out['login_logo_id']    = isset($input['login_logo_id']) ? absint($input['login_logo_id']) : 0;
      $out['login_width']      = isset($input['login_width']) ? max(1, (int)$input['login_width']) : 200;
      $out['login_height']     = isset($input['login_height']) ? max(1, (int)$input['login_height']) : 80;
      $out['adminbar_logo_id'] = isset($input['adminbar_logo_id']) ? absint($input['adminbar_logo_id']) : 0;
      return $out;
    }
  ]);
});

function peu_al_media_field($field_id, $attachment_id){
  $img = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
  ?>
  <div class="peu-media-field" data-target="<?php echo esc_attr($field_id); ?>">
    <div class="peu-media-preview" style="margin-bottom:8px;">
      <?php if ($img): ?>
        <img src="<?php echo esc_url($img); ?>" style="max-width:240px;height:auto;border:1px solid #ccd0d4;border-radius:4px;">
      <?php else: ?>
        <em><?php esc_html_e('Chưa chọn ảnh', 'peugeot-admin-logo'); ?></em>
      <?php endif; ?>
    </div>
    <input type="hidden" name="peu_admin_logo_options[<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($attachment_id); ?>">
    <button type="button" class="button peu-media-choose"><?php esc_html_e('Chọn ảnh', 'peugeot-admin-logo'); ?></button>
    <button type="button" class="button peu-media-clear" style="margin-left:8px;"><?php esc_html_e('Xoá', 'peugeot-admin-logo'); ?></button>
  </div>
  <?php
}

function peu_al_render_settings_page(){
  if (!current_user_can('manage_options')) return;
  $o = peu_al_get_options();
  ?>
  <div class="wrap">
    <h1><?php esc_html_e('Peugeot Admin Logo', 'peugeot-admin-logo'); ?></h1>
    <form method="post" action="options.php">
      <?php settings_fields('peu_admin_logo_group'); ?>
      <table class="form-table" role="presentation">
        <tr>
          <th scope="row"><?php esc_html_e('Logo trang đăng nhập', 'peugeot-admin-logo'); ?></th>
          <td><?php peu_al_media_field('login_logo_id', $o['login_logo_id']); ?></td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Kích thước logo đăng nhập', 'peugeot-admin-logo'); ?></th>
          <td>
            <label>
              <?php esc_html_e('Rộng (px):', 'peugeot-admin-logo'); ?>
              <input type="number" min="1" name="peu_admin_logo_options[login_width]" value="<?php echo esc_attr($o['login_width']); ?>" class="small-text">
            </label>
            &nbsp;&nbsp;
            <label>
              <?php esc_html_e('Cao (px):', 'peugeot-admin-logo'); ?>
              <input type="number" min="1" name="peu_admin_logo_options[login_height]" value="<?php echo esc_attr($o['login_height']); ?>" class="small-text">
            </label>
            <p class="description"><?php esc_html_e('Khuyến nghị tỉ lệ ngang; nền trong suốt (PNG/SVG).', 'peugeot-admin-logo'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Logo thanh admin (góc trái trên)', 'peugeot-admin-logo'); ?></th>
          <td><?php peu_al_media_field('adminbar_logo_id', $o['adminbar_logo_id']); ?></td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

/* Media uploader cho trang cài đặt */
add_action('admin_enqueue_scripts', function($hook){
  if ($hook !== 'settings_page_peu-admin-logo') return;
  wp_enqueue_media();
  wp_add_inline_script('jquery-core', <<<JS
jQuery(function($){
  function openFrame(targetWrap){
    var frame = wp.media({ title: 'Chọn ảnh', library:{type:'image'}, multiple:false });
    frame.on('select', function(){
      var att = frame.state().get('selection').first().toJSON();
      targetWrap.find('input[type=hidden]').val(att.id);
      var url = att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url;
      targetWrap.find('.peu-media-preview').html('<img src="'+url+'" style="max-width:240px;height:auto;border:1px solid #ccd0d4;border-radius:4px;">');
    });
    frame.open();
  }
  $('.peu-media-choose').on('click', function(){
    openFrame($(this).closest('.peu-media-field'));
  });
  $('.peu-media-clear').on('click', function(){
    var wrap = $(this).closest('.peu-media-field');
    wrap.find('input[type=hidden]').val('');
    wrap.find('.peu-media-preview').html('<em>Chưa chọn ảnh</em>');
  });
});
JS);
});

/* --------- APPLY: LOGIN LOGO --------- */
add_action('login_enqueue_scripts', function () {
  $o = peu_al_get_options();
  if (empty($o['login_logo_id'])) return;

  $url = wp_get_attachment_image_url((int)$o['login_logo_id'], 'full');
  if (!$url) return;

  $w = (int) $o['login_width'];  if ($w <= 0) $w = 200;
  $h = (int) $o['login_height']; if ($h <= 0) $h = 80;

  echo '<style id="peu-admin-login-logo">
    body.login div#login h1 a{
      background-image: url("'.esc_url($url).'") !important;
      background-size: contain !important;
      background-repeat: no-repeat !important;
      background-position: center center !important;
      width: '.absint($w).'px !important;
      height: '.absint($h).'px !important;
      display: block !important;
    }
  </style>';
}, 999); // priority cao để override CSS của theme

/* --------- APPLY: ADMIN BAR LOGO --------- */
add_action('admin_bar_menu', function($wp_admin_bar){
  if (!is_admin_bar_showing()) return;
  $o = peu_al_get_options();
  $id = (int)$o['adminbar_logo_id'];
  if (!$id) return;

  $url = wp_get_attachment_image_url($id, 'full');
  if (!$url) return;

  // Bỏ logo WP mặc định
  $wp_admin_bar->remove_node('wp-logo');

  // Thêm node mới với <img>
  $wp_admin_bar->add_node([
    'id'    => 'peu-admin-logo',
    'title' => '<img src="'.esc_url($url).'" style="height:20px;width:auto;vertical-align:middle;" alt="logo">',
    'href'  => admin_url(),
    'meta'  => ['class' => 'peu-admin-logo ab-item']
  ]);
}, 0);

/* Một chút CSS căn chỉnh */
add_action('admin_head', function(){
  echo '<style>
  #wpadminbar #wp-admin-bar-peu-admin-logo > .ab-item { padding-left: 8px; padding-right: 8px; }
  </style>';
});
