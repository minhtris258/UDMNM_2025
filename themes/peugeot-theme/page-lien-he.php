<?php
/**
 * Template Name: liên hệ
 */
acf_form_head();
get_header();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acf'])) {
    // Lấy xưng hô, họ, tên
    $salutation = isset($_POST['acf']['field_68c45ac49ae86']) ? trim($_POST['acf']['field_68c45ac49ae86']) : '';
    $last_name = isset($_POST['acf']['field_68c45ac4a2397']) ? trim($_POST['acf']['field_68c45ac4a2397']) : '';
    $first_name = isset($_POST['acf']['field_68c45ac49e9fa']) ? trim($_POST['acf']['field_68c45ac49e9fa']) : '';
    $full_name = trim($last_name . ' ' . $first_name);

    // Lấy loại xe
  $yeu_cau_value = isset($_POST['acf']['field_68c45ac49373b']) ? trim($_POST['acf']['field_68c45ac49373b']) : '';

    // Ngày giờ tạo
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $datetime = date('d/m/Y H:i:s');

    // Ghép title: "Xưng hô Họ Tên - Loại xe - Ngày giờ"
    $post_title = trim($salutation . ' ' . $full_name . ' - ' . $yeu_cau_value . ' - ' . $datetime);

    // Tạo post mới
    $post_id = wp_insert_post([
        'post_type' => 'lien_he',
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
        echo '<div class="td-missing">Không tạo được bài mới!</div>';
    }
}
$TD_KEYS = [
  'yeu_cau'            => 'field_68c45ac49373b',
  'note'               => 'field_68c45c2f19a70',
  'dai_ly'             => 'field_68c45ac49715a',
  'salutation'         => 'field_68c45ac49ae86',
  'first_name'         => 'field_68c45ac49e9fa',
  'last_name'          => 'field_68c45ac4a2397',
  'phone'              => 'field_68c45ac4a5eca',
  'email'              => 'field_68c45ac4a9aa1',
  'optin_channels'     => 'field_68c45ac4ad730',
];

$TD_LABELS = [
  'yeu_cau'        => 'Vui lòng chọn dòng xe bạn quan tâm:',
  'note'         => 'Chi tiết/Ghi chú:',
  'dai_ly'         => 'Chọn đại lý',
  'salutation'     => 'Danh xưng',
  'first_name'     => 'Tên',
  'last_name'      => 'Họ',
  'phone'          => 'Số điện thoại',
  'email'          => 'Email',
  'optin_channels' => 'Tôi muốn nhận thêm thông tin về các sản phẩm và dịch vụ của Peugeot Việt Nam bằng cách',
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
    <div class="td-success">Cảm ơn bạn! Yêu cầu đã được ghi nhận.</div>
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
          <span>NHẬP YÊU CẦU CỦA BẠN</span><i class="td-acc-icon"></i>
        </button>
        <div class="td-acc-content">
          <?php td_render($TD_KEYS['yeu_cau'], $TD_LABELS['yeu_cau'], 'Chọn dòng xe'); ?>
        </div>
        <div class="td-acc-content">
          <?php td_render($TD_KEYS['note'], $TD_LABELS['note'], 'Chọn dòng xe'); ?>
        </div>
      </section>

      <section class="td-acc td-open td-acc--half">
        <button class="td-acc-head" type="button">
          <span>CHỌN CỬA HÀNG / ĐẠI LÝ</span><i class="td-acc-icon"></i>
        </button>
        <div class="td-acc-content">
          <?php td_render($TD_KEYS['dai_ly'], $TD_LABELS['dai_ly'], 'Chọn đại lý'); ?>
        </div>
      </section>
    </div>

    <!-- Nhóm 2 -->
    <section class="td-acc">
      <button class="td-acc-head" type="button">
        <span>VUI LÒNG CUNG CẤP THÔNG TIN CỦA BẠN</span><i class="td-acc-icon"></i>
      </button>
      <div class="td-acc-content">
        <div class="td-policy">
            <p>Vui lòng cho chúng tôi biết thông tin sau để liên hệ với bạn:</p>
        </div>
        <?php
          td_render($TD_KEYS['salutation'], $TD_LABELS['salutation']);
          td_render($TD_KEYS['first_name'], $TD_LABELS['first_name'], 'Nhập tên');
          td_render($TD_KEYS['last_name'],  $TD_LABELS['last_name'],  'Nhập họ');
          td_render($TD_KEYS['phone'],      $TD_LABELS['phone'],      'Nhập số điện thoại');
          td_render($TD_KEYS['email'],      $TD_LABELS['email'],      'email@domain.com');
        ?>
      </div>
    </section>

    <!-- Nhóm 3 -->
    <section class="td-acc">
      <button class="td-acc-head" type="button">
        <span>CUNG CẤP THÔNG TIN</span><i class="td-acc-icon"></i>
      </button>
      <div class="td-acc-content">
        <div class="td-policy">
          <p>Thông tin cá nhân của bạn sẽ được xử lý theo Chính sách Bảo mật.</p>
          <p>Giữ liên lạc nhé! (Tùy chọn)</p>
        </div>
        
        <?php td_render($TD_KEYS['optin_channels'], $TD_LABELS['optin_channels']); ?>
        <div class="td-policy">
        <p>và bằng cách chọn ít nhất một trong các kênh này, tôi đồng ý rằng Peugeot Việt Nam xử lý dữ liệu cá nhân của tôi cho các mục đích tiếp thị, nhận dạng và xác minh, như được mô tả trong Chính sách Cho phép sử dụng Thông tin Cá nhân.</p>

        <p>Tôi có quyền rút lại sự đồng ý của mình bất cứ lúc nào (để biết thêm chi tiết, vui lòng xem tại đây). Việc rút lại sự đồng ý sẽ không ảnh hưởng đến tính hợp pháp của việc xử lý dựa trên sự đồng ý trước khi rút lại.</p>
      </div>
        <div class="td-actions">  <button type="submit" class="td-submit">XÁC NHẬN YÊU CẦU LÁI THỬ</button></div>
      </div>
    </section>
  </form>

  
</div>
<?php get_footer(); ?>