<?php
// inc/agency-card.php
if (!function_exists('pg_qs')) {
  function pg_qs(array $add = []) {
    $base = array_filter([
      'keyword'    => isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '',
      'tinhthanh'  => isset($_GET['tinhthanh']) ? absint($_GET['tinhthanh']) : '',
    ]);
    return esc_url( add_query_arg( array_filter(array_merge($base, $add)), get_permalink() ) );
  }
}

if (!function_exists('pg_media_url')) {
  function pg_media_url($val, $size = 'large'){
    if (is_numeric($val)) return wp_get_attachment_image_url((int)$val, $size);
    if (is_array($val))   return $val['sizes'][$size] ?? ($val['url'] ?? '');
    if (is_string($val))  return $val;
    return '';
  }
}

if (!function_exists('pg_render_agency_card')) {
  function pg_render_agency_card($post_id){
    $tax_mien = 'mien';
    $tax_tinh = 'tinhthanh';

    $tt = function_exists('get_field') ? (get_field('thong_tin', $post_id) ?: []) : [];
    $dia_chi = $tt['dia_chi'] ?? '';
    $cskh    = $tt['hotline_cskh'] ?? '';
    $kd      = $tt['hotline_kinh_doanh'] ?? '';
    $dv      = $tt['hotline_dich_vu'] ?? '';
    $map_url = $tt['map'] ?? '';

    if (has_post_thumbnail($post_id)) {
      $thumb_html = get_the_post_thumbnail($post_id, 'large', ['class'=>'agency-thumb','alt'=>get_the_title($post_id)]);
    } else {
      $banner     = function_exists('get_field') ? (get_field('banner', $post_id) ?: ($tt['banner'] ?? '')) : '';
      $banner_url = pg_media_url($banner, 'large');
      $thumb_html = $banner_url ? '<img class="agency-thumb" src="'.esc_url($banner_url).'" alt="'.esc_attr(get_the_title($post_id)).'">' : '';
    }

    $mien_names = wp_get_post_terms($post_id, $tax_mien, ['fields' => 'names']);
    $tinh_names = wp_get_post_terms($post_id, $tax_tinh, ['fields' => 'names']);

    $mien_text = !is_wp_error($mien_names) && !empty($mien_names) ? implode(', ', $mien_names) : '';
    $tinh_text = !is_wp_error($tinh_names) && !empty($tinh_names) ? implode(', ', $tinh_names) : '';

    $map_href   = $map_url ?: ($dia_chi ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($dia_chi) : '');

    ?>
    <article class="agency-card">
      <?php echo $thumb_html; ?>
      <div class="agency-meta">
        <h3 class="agency-title"><?php echo esc_html(get_the_title($post_id)); ?></h3>

        <div class="agency-tax">
          <?php if ($mien_text): ?>
            <div><strong><?php echo esc_html__('Miền:', 'peugeot-theme'); ?></strong> <?php echo esc_html($mien_text); ?></div>
          <?php endif; ?>

          <?php if ($tinh_text): ?>
            <div><strong><?php echo esc_html__('Tỉnh/Thành:', 'peugeot-theme'); ?></strong> <?php echo esc_html($tinh_text); ?></div>
          <?php endif; ?>
        </div>

        <div class="agency-info">
          <?php if ($dia_chi): ?>
            <div><strong><?php echo esc_html__('Địa chỉ:', 'peugeot-theme'); ?></strong> <?php echo nl2br(esc_html($dia_chi)); ?></div>
          <?php endif; ?>
          <?php if ($cskh): ?>
            <div><strong><?php echo esc_html__('Hotline CSKH:', 'peugeot-theme'); ?></strong> <a href="tel:<?php echo esc_attr(preg_replace('/\D+/','',$cskh)); ?>"><?php echo esc_html($cskh); ?></a></div>
          <?php endif; ?>
          <?php if ($kd): ?>
            <div><strong><?php echo esc_html__('Hotline Kinh doanh:', 'peugeot-theme'); ?></strong> <a href="tel:<?php echo esc_attr(preg_replace('/\D+/','',$kd)); ?>"><?php echo esc_html($kd); ?></a></div>
          <?php endif; ?>
          <?php if ($dv): ?>
            <div><strong><?php echo esc_html__('Hotline Dịch vụ:', 'peugeot-theme'); ?></strong> <a href="tel:<?php echo esc_attr(preg_replace('/\D+/','',$dv)); ?>"><?php echo esc_html($dv); ?></a></div>
          <?php endif; ?>
        </div>

        <?php if ($map_href): ?>
          <a class="btn-direction" target="_blank" rel="noopener" href="<?php echo esc_url($map_href); ?>">
            <?php echo esc_html__('DẪN ĐƯỜNG', 'peugeot-theme'); ?>
          </a>
        <?php endif; ?>
      </div>
    </article>
    <?php
  }
}