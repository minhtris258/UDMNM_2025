<?php
/**
 * Template Name: Archive Settings – Sản phẩm
 * Text Domain: peugeot-theme
 */
get_header();
// Dùng hàm dịch với text domain
echo '<div class="wrap">
  <h1>' . esc_html__('Archive Settings – Sản phẩm', 'peugeot-theme') . '</h1>
  <p>' . esc_html__('Nhập banner, tiêu đề, mô tả… (ACF).', 'peugeot-theme') . '</p>
</div>';
get_footer();