<?php
/**
 * Plugin Name: Peugeot Back To Top
 * Description: Nút "Về đầu trang" nổi ở góc trang. Cho phép tùy biến vị trí, màu sắc, kích thước trong phần Cài đặt.
 * Version: 2.05.0
 * Author: MinhTris
 * Text Domain: peugeot-btt
 */

if (!defined('ABSPATH')) exit;

/* i18n */
add_action('plugins_loaded', function () {
  load_plugin_textdomain('peugeot-btt', false, dirname(plugin_basename(__FILE__)).'/languages');
});

/* ------------------ OPTIONS ------------------ */
function peu_btt_defaults() {
  return [
    'pos'        => 'br',   // br|bl|tr|tl
    'offset_x'   => 24,
    'offset_y'   => 24,
    'btn_size'   => 56,     // px
    'radius'     => 14,     // px
    'bg'         => '#0f0f10',
    'color'      => '#ffffff',
    'hover_bg'   => '#1a1a1d',
    'shadow'     => 1,
    'icon_size'  => 18,     // px
    'threshold'  => 300,    // px để bắt đầu hiện nút
    'duration'   => 0,      // 0=auto smooth; đặt >0 để ép ms
    'icon_svg'   => 'arrow',// arrow|chevron|caret
  ];
}
function peu_btt_get_options() {
  $opt = get_option('peu_btt_options', []);
  return wp_parse_args(is_array($opt) ? $opt : [], peu_btt_defaults());
}

/* ------------------ SETTINGS PAGE ------------------ */
add_action('admin_menu', function () {
  add_options_page(
    __('Peugeot Back To Top', 'peugeot-btt'),
    __('Peugeot Back To Top', 'peugeot-btt'),
    'manage_options',
    'peu-btt',
    'peu_btt_render_settings'
  );
});

add_action('admin_init', function () {
  register_setting('peu_btt_group', 'peu_btt_options', [
    'type' => 'array',
    'sanitize_callback' => function($in){
      $d = peu_btt_defaults();
      $out = [];
      $out['pos']        = in_array($in['pos'] ?? 'br', ['br','bl','tr','tl'], true) ? $in['pos'] : 'br';
      $out['offset_x']   = max(0, (int)($in['offset_x'] ?? $d['offset_x']));
      $out['offset_y']   = max(0, (int)($in['offset_y'] ?? $d['offset_y']));
      $out['btn_size']   = max(32, (int)($in['btn_size'] ?? $d['btn_size']));
      $out['radius']     = max(0,  (int)($in['radius'] ?? $d['radius']));
      $out['bg']         = sanitize_hex_color($in['bg'] ?? $d['bg']) ?: $d['bg'];
      $out['color']      = sanitize_hex_color($in['color'] ?? $d['color']) ?: $d['color'];
      $out['hover_bg']   = sanitize_hex_color($in['hover_bg'] ?? $d['hover_bg']) ?: $d['hover_bg'];
      $out['shadow']     = empty($in['shadow']) ? 0 : 1;
      $out['icon_size']  = max(10, (int)($in['icon_size'] ?? $d['icon_size']));
      $out['threshold']  = max(0,  (int)($in['threshold'] ?? $d['threshold']));
      $out['duration']   = max(0,  (int)($in['duration'] ?? $d['duration']));
      $out['icon_svg']   = in_array($in['icon_svg'] ?? 'arrow', ['arrow','chevron','caret'], true) ? $in['icon_svg'] : 'arrow';
      return $out;
    }
  ]);
});

function peu_btt_render_settings() {
  if (!current_user_can('manage_options')) return;
  $o = peu_btt_get_options(); ?>
  <div class="wrap">
    <h1><?php esc_html_e('Peugeot Back To Top', 'peugeot-btt'); ?></h1>
    <form method="post" action="options.php">
      <?php settings_fields('peu_btt_group'); ?>
      <table class="form-table" role="presentation">
        <tr>
          <th scope="row"><?php esc_html_e('Vị trí', 'peugeot-btt'); ?></th>
          <td>
            <label><input type="radio" name="peu_btt_options[pos]" value="br" <?php checked($o['pos'],'br'); ?>> BR</label>&nbsp;
            <label><input type="radio" name="peu_btt_options[pos]" value="bl" <?php checked($o['pos'],'bl'); ?>> BL</label>&nbsp;
            <label><input type="radio" name="peu_btt_options[pos]" value="tr" <?php checked($o['pos'],'tr'); ?>> TR</label>&nbsp;
            <label><input type="radio" name="peu_btt_options[pos]" value="tl" <?php checked($o['pos'],'tl'); ?>> TL</label>
            <p class="description"><?php esc_html_e('Chọn góc hiển thị (Bottom/Top – Left/Right).', 'peugeot-btt'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Khoảng cách mép (px)', 'peugeot-btt'); ?></th>
          <td>
            <label><?php esc_html_e('X:', 'peugeot-btt'); ?>
              <input type="number" min="0" class="small-text" name="peu_btt_options[offset_x]" value="<?php echo esc_attr($o['offset_x']); ?>">
            </label>
            &nbsp;&nbsp;
            <label><?php esc_html_e('Y:', 'peugeot-btt'); ?>
              <input type="number" min="0" class="small-text" name="peu_btt_options[offset_y]" value="<?php echo esc_attr($o['offset_y']); ?>">
            </label>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Kích thước nút & bo góc', 'peugeot-btt'); ?></th>
          <td>
            <label><?php esc_html_e('Kích thước (px):', 'peugeot-btt'); ?>
              <input type="number" min="32" class="small-text" name="peu_btt_options[btn_size]" value="<?php echo esc_attr($o['btn_size']); ?>">
            </label>
            &nbsp;&nbsp;
            <label><?php esc_html_e('Bo góc (px):', 'peugeot-btt'); ?>
              <input type="number" min="0" class="small-text" name="peu_btt_options[radius]" value="<?php echo esc_attr($o['radius']); ?>">
            </label>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Màu sắc', 'peugeot-btt'); ?></th>
          <td>
            <label><?php esc_html_e('Nền:', 'peugeot-btt'); ?>
              <input type="color" name="peu_btt_options[bg]" value="<?php echo esc_attr($o['bg']); ?>">
            </label>
            &nbsp;&nbsp;
            <label><?php esc_html_e('Icon:', 'peugeot-btt'); ?>
              <input type="color" name="peu_btt_options[color]" value="<?php echo esc_attr($o['color']); ?>">
            </label>
            &nbsp;&nbsp;
            <label><?php esc_html_e('Nền hover:', 'peugeot-btt'); ?>
              <input type="color" name="peu_btt_options[hover_bg]" value="<?php echo esc_attr($o['hover_bg']); ?>">
            </label>
            <p><label><input type="checkbox" name="peu_btt_options[shadow]" value="1" <?php checked($o['shadow'],1); ?>> <?php esc_html_e('Bật đổ bóng', 'peugeot-btt'); ?></label></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Icon', 'peugeot-btt'); ?></th>
          <td>
            <label><input type="radio" name="peu_btt_options[icon_svg]" value="arrow"   <?php checked($o['icon_svg'],'arrow');   ?>> ↑ Arrow</label>&nbsp;
            <label><input type="radio" name="peu_btt_options[icon_svg]" value="chevron" <?php checked($o['icon_svg'],'chevron'); ?>> ˄ Chevron</label>&nbsp;
            <label><input type="radio" name="peu_btt_options[icon_svg]" value="caret"   <?php checked($o['icon_svg'],'caret');   ?>> ^ Caret</label>
            &nbsp;&nbsp;
            <label><?php esc_html_e('Kích thước (px):', 'peugeot-btt'); ?>
              <input type="number" min="10" class="small-text" name="peu_btt_options[icon_size]" value="<?php echo esc_attr($o['icon_size']); ?>">
            </label>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Hành vi', 'peugeot-btt'); ?></th>
          <td>
            <label><?php esc_html_e('Ngưỡng hiển thị (px):', 'peugeot-btt'); ?>
              <input type="number" min="0" class="small-text" name="peu_btt_options[threshold]" value="<?php echo esc_attr($o['threshold']); ?>">
            </label>
            &nbsp;&nbsp;
            <label><?php esc_html_e('Thời gian cuộn (ms, 0=trình duyệt smooth):', 'peugeot-btt'); ?>
              <input type="number" min="0" class="small-text" name="peu_btt_options[duration]" value="<?php echo esc_attr($o['duration']); ?>">
            </label>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
<?php }

/* ------------------ OUTPUT BUTTON ------------------ */
add_action('wp_footer', function () {
  if (is_admin()) return;
  $o = peu_btt_get_options();

  // chọn SVG theo option
  $svg = '';
  switch ($o['icon_svg']) {
    case 'chevron':
      $svg = '<path d="M12 8l-6 6h4v2h4v-2h4z" fill="currentColor"/>';
      break;
    case 'caret':
      $svg = '<path d="M7 14l5-6 5 6H7z" fill="currentColor"/>';
      break;
    default: // arrow
      $svg = '<path d="M12 5l-7 7h4v7h6v-7h4z" fill="currentColor"/>';
  }

  echo '<button id="peu-btt"
          class="peu-btt"
          type="button"
          aria-label="'.esc_attr__('Về đầu trang', 'peugeot-btt').'"
          title="'.esc_attr__('Về đầu trang', 'peugeot-btt').'">
          <svg class="peu-btt__icon" width="'.absint($o['icon_size']).'" height="'.absint($o['icon_size']).'" viewBox="0 0 24 24" aria-hidden="true" focusable="false">'.$svg.'</svg>
        </button>';
});

/* ------------------ CSS & JS ------------------ */
add_action('wp_enqueue_scripts', function () {
  $o = peu_btt_get_options();

  // CSS
  wp_register_style('peu-btt', false);
  wp_enqueue_style('peu-btt');

  // vị trí
  $pos_css = '';
  switch ($o['pos']) {
    case 'bl': $pos_css = "left:{$o['offset_x']}px; bottom:{$o['offset_y']}px;"; break;
    case 'tr': $pos_css = "right:{$o['offset_x']}px; top:{$o['offset_y']}px;";   break;
    case 'tl': $pos_css = "left:{$o['offset_x']}px; top:{$o['offset_y']}px;";    break;
    default:   $pos_css = "right:{$o['offset_x']}px; bottom:{$o['offset_y']}px;";
  }
  $shadow_css = $o['shadow'] ? 'box-shadow:0 8px 24px rgba(0,0,0,.25);' : 'box-shadow:none;';

  $css = "
  .peu-btt{
    position:fixed; {$pos_css}
    width:{$o['btn_size']}px; height:{$o['btn_size']}px; border-radius:{$o['radius']}px;
    background:{$o['bg']}; color:{$o['color']};
    border:1px solid rgba(0,0,0,.1);
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; z-index:9999; {$shadow_css}

    opacity:0; transform:translateY(8px);
    visibility:hidden; pointer-events:none;
    transition:opacity .25s ease, transform .25s ease, visibility .25s, background-color .2s;
  }
  .peu-btt:hover{ background:{$o['hover_bg']}; }
  .peu-btt.is-visible{ opacity:1; transform:none; visibility:visible; pointer-events:auto; }
  @media (max-width:575.98px){
    .peu-btt{ width:".max(32, (int)round($o['btn_size']*0.86))."px; height:".max(32, (int)round($o['btn_size']*0.86))."px; }
  }";
  wp_add_inline_style('peu-btt', $css);

  // JS
  wp_register_script('peu-btt', '', [], false, true);
  wp_enqueue_script('peu-btt');

  $threshold = (int)$o['threshold'];
  $duration  = (int)$o['duration'];

  $js = "(function(){
    var btn=document.getElementById('peu-btt'); if(!btn) return;
    var TH={$threshold};

    function toggle(){ if(window.scrollY>TH) btn.classList.add('is-visible'); else btn.classList.remove('is-visible'); }
    toggle(); window.addEventListener('scroll', toggle, {passive:true});

    btn.addEventListener('click', function(e){
      e.preventDefault();
      var dur={$duration};
      if(dur<=0){ try{ window.scrollTo({top:0, behavior:'smooth'}); return; }catch(_){} }
      // fallback animate tay (hoặc khi ép duration)
      var start=null, from=window.scrollY;
      function step(ts){
        if(!start) start=ts;
        var p=Math.min(1,(ts-start)/dur), y=Math.floor(from*(1-p)); window.scrollTo(0,y);
        if(p<1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    });
  })();";
  wp_add_inline_script('peu-btt', $js);
});
