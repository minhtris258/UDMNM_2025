<?php

/**
 * Template Name: Đặt lịch lái thử
 * Text Domain: peugeot-theme
 */
acf_form_head();
get_header();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acf'])) {
  // Lấy xưng hô, họ, tên
  $salutation = isset($_POST['acf']['field_68c23a85eb757']) ? trim($_POST['acf']['field_68c23a85eb757']) : '';
  $last_name = isset($_POST['acf']['field_68c23a1feb752']) ? trim($_POST['acf']['field_68c23a1feb752']) : '';
  $first_name = isset($_POST['acf']['field_68c23a0eeb751']) ? trim($_POST['acf']['field_68c23a0eeb751']) : '';
  $full_name = trim($last_name . ' ' . $first_name);

  // Lấy loại xe
  $car_id = isset($_POST['acf']['field_68c2380eeb74f']) ? intval($_POST['acf']['field_68c2380eeb74f']) : 0;
  $car_name = $car_id ? get_the_title($car_id) : '';

  // Ngày giờ tạo
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $datetime = date('d/m/Y H:i:s');

  // Ghép title: "Xưng hô Họ Tên - Loại xe - Ngày giờ"
  $post_title = trim($salutation . ' ' . $full_name . ' - ' . $car_name . ' - ' . $datetime);

  // Tạo post mới
  $post_id = wp_insert_post([
    'post_type' => 'lich_lai_thu',
    'post_status' => 'publish',
    'post_title' => $post_title,
  ]);

  if ($post_id && !is_wp_error($post_id)) {
    foreach ($_POST['acf'] as $key => $value) {
      update_field($key, $value, $post_id);
    }
    wp_redirect(add_query_arg('ok', '1', get_permalink()));
    exit;
  } else {
    echo '<div class="td-missing">' . esc_html__('Không tạo được bài mới!', 'peugeot-theme') . '</div>';
  }
}
$TD_KEYS = [
  'car'            => 'field_68c2380eeb74f',
  'dai_ly'         => 'field_68c239ebeb750',
  'salutation'     => 'field_68c23a85eb757',
  'first_name'     => 'field_68c23a0eeb751',
  'last_name'      => 'field_68c23a1feb752',
  'phone'          => 'field_68c23a2eeb753',
  'email'          => 'field_68c23a3ceb754',
  'optin_channels' => 'field_68c23a4eeb755',
];

$TD_LABELS = [
  'car'            => __('Vui lòng chọn dòng xe bạn quan tâm:', 'peugeot-theme'),
  'dai_ly'         => __('Chọn đại lý', 'peugeot-theme'),
  'salutation'     => __('Danh xưng', 'peugeot-theme'),
  'first_name'     => __('Tên', 'peugeot-theme'),
  'last_name'      => __('Họ', 'peugeot-theme'),
  'phone'          => __('Số điện thoại', 'peugeot-theme'),
  'email'          => __('Email', 'peugeot-theme'),
  'optin_channels' => __('Tôi muốn nhận thêm thông tin về các sản phẩm và dịch vụ của Peugeot Việt Nam bằng cách', 'peugeot-theme'),
];
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
<div class="container-fluid">
  <h1 class="td-title"><?php the_title(); ?></h1>
  <?php if (!empty($_GET['ok'])): ?>
    <div class="td-success"><?php echo esc_html__('Cảm ơn bạn! Yêu cầu đã được ghi nhận.', 'peugeot-theme'); ?></div>
  <?php endif; ?>
  <form id="test-drive-form" class="acf-form td-grid" method="post">
    <input type="hidden" name="acf_form" value="1">
    <input type="hidden" name="post_id" value="new_post">
    <input type="hidden" name="new_post[post_type]" value="lich_lai_thu">
    <input type="hidden" name="new_post[post_status]" value="publish">
    <?php wp_nonce_field('acf_form_submit', 'acf_nonce'); ?>

    <!-- Nhóm 1: Xe & Cửa hàng (2 cột 1–1) -->
    <div class="td-row-2">
      <section class="td-acc td-open td-acc--half">
        <button class="td-acc-head" type="button">
          <span><?php echo esc_html__('HÃY CHỌN MẪU PEUGEOT PHÙ HỢP VỚI BẠN NHẤT', 'peugeot-theme'); ?></span><i class="td-acc-icon"></i>
        </button>
        <div class="td-acc-content">
          <?php td_render($TD_KEYS['car'], $TD_LABELS['car'], esc_html__('Chọn dòng xe', 'peugeot-theme')); ?>
        </div>
      </section>

      <section class="td-acc td-open td-acc--half">
        <button class="td-acc-head" type="button">
          <span><?php echo esc_html__('CHỌN CỬA HÀNG / ĐẠI LÝ', 'peugeot-theme'); ?></span><i class="td-acc-icon"></i>
        </button>
        <div class="td-acc-content">
          <?php td_render($TD_KEYS['dai_ly'], $TD_LABELS['dai_ly'], esc_html__('Chọn đại lý', 'peugeot-theme')); ?>
        </div>
      </section>
    </div>

    <!-- Nhóm 2 -->
    <section class="td-acc">
      <button class="td-acc-head" type="button">
        <span><?php echo esc_html__('VUI LÒNG CUNG CẤP THÔNG TIN CỦA BẠN', 'peugeot-theme'); ?></span><i class="td-acc-icon"></i>
      </button>
      <div class="td-acc-content">
        <div class="td-policy">
          <p><?php echo esc_html__('Vui lòng cho chúng tôi biết thông tin sau để liên hệ với bạn:', 'peugeot-theme'); ?></p>
        </div>
        <?php
        td_render($TD_KEYS['salutation'], $TD_LABELS['salutation']);
        td_render($TD_KEYS['first_name'], $TD_LABELS['first_name'], esc_html__('Nhập tên', 'peugeot-theme'));
        td_render($TD_KEYS['last_name'],  $TD_LABELS['last_name'],  esc_html__('Nhập họ', 'peugeot-theme'));
        td_render($TD_KEYS['phone'],      $TD_LABELS['phone'],      esc_html__('Nhập số điện thoại', 'peugeot-theme'));
        td_render($TD_KEYS['email'],      $TD_LABELS['email'],      esc_html__('email@domain.com', 'peugeot-theme'));
        ?>
      </div>
    </section>

    <!-- Nhóm 3 -->
    <section class="td-acc">
      <button class="td-acc-head" type="button">
        <span><?php echo esc_html__('CUNG CẤP THÔNG TIN', 'peugeot-theme'); ?></span><i class="td-acc-icon"></i>
      </button>
      <div class="td-acc-content">
        <div class="td-policy">
          <p><?php echo esc_html__('Thông tin cá nhân của bạn sẽ được xử lý theo Chính sách Bảo mật.', 'peugeot-theme'); ?></p>
          <p><?php echo esc_html__('Giữ liên lạc nhé! (Tùy chọn)', 'peugeot-theme'); ?></p>
        </div>

        <?php td_render($TD_KEYS['optin_channels'], $TD_LABELS['optin_channels']); ?>
        <div class="td-policy">
          <p><?php echo esc_html__('và bằng cách chọn ít nhất một trong các kênh này, tôi đồng ý rằng Peugeot Việt Nam xử lý dữ liệu cá nhân của tôi cho các mục đích tiếp thị, nhận dạng và xác minh, như được mô tả trong Chính sách Cho phép sử dụng Thông tin Cá nhân.', 'peugeot-theme'); ?></p>

          <p><?php echo esc_html__('Tôi có quyền rút lại sự đồng ý của mình bất cứ lúc nào (để biết thêm chi tiết, vui lòng xem tại đây). Việc rút lại sự đồng ý sẽ không ảnh hưởng đến tính hợp pháp của việc xử lý dựa trên sự đồng ý trước khi rút lại.', 'peugeot-theme'); ?></p>
        </div>
        <div class="td-actions"> <button type="submit" class="td-submit"><?php echo esc_html__('XÁC NHẬN YÊU CẦU LÁI THỬ', 'peugeot-theme'); ?></button></div>
      </div>
    </section>
  </form>
</div>
<?php get_footer(); ?>