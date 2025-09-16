<?php
/**
 * Plugin Name: Peugeot Forms (MU)
 * Description: CPT nội bộ lưu form: lich_lai_thu, lien_he (không public).
 * Version: 1.0.0
 * Author: Minhtris
 * Text Domain: peugeot-forms
 */
if (!defined('ABSPATH')) exit;
add_action('init', function () {
  if (!post_type_exists('lich_lai_thu')) {
    register_post_type('lich_lai_thu', [
      'label'      => __('Đăng ký lái thử', 'peugeot-forms'),
      'public'     => false,         // không có URL frontend
      'show_ui'    => true,
      'show_in_rest'=> false,
      'menu_icon'  => 'dashicons-calendar-alt',
      'supports'   => ['title'],
    ]);
  }
  if (!post_type_exists('lien_he')) {
    register_post_type('lien_he', [
      'label'      => __('Liên hệ', 'peugeot-forms'),
      'public'     => false,
      'show_ui'    => true,
      'show_in_rest'=> false,
      'menu_icon'  => 'dashicons-email',
      'supports'   => ['title'],
    ]);
  }
}, 10);
