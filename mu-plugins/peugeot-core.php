<?php
/**
 * Plugin Name: Peugeot Core (MU)
 * Description: MU plugin: 3 CPT (san-pham, tin-tuc, dai-ly) + Tax (san-pham-cate, tin-tuc-cate, mien, tinhthanh).
 * Author: You
 * Version: 1.0.0
 * Text Domain: peugeot-core
 */

if (!defined('ABSPATH')) exit;

/** i18n (đặt .mo tại: wp-content/mu-plugins/languages/peugeot-core-vi_VN.mo) */
add_action('muplugins_loaded', function () {
  load_muplugin_textdomain('peugeot-core', 'languages');
});

/** Đăng ký CPT/Tax */
add_action('init', function () {

  /* ========== CPT: Sản phẩm ========== */
  if (!post_type_exists('san-pham')) {
    register_post_type('san-pham', [
      'labels' => [
        'name'               => __('Sản phẩm', 'peugeot-core'),
        'singular_name'      => __('Sản phẩm', 'peugeot-core'),
        'add_new'            => __('Thêm mới', 'peugeot-core'),
        'add_new_item'       => __('Thêm Sản phẩm', 'peugeot-core'),
        'edit_item'          => __('Sửa Sản phẩm', 'peugeot-core'),
        'new_item'           => __('Sản phẩm mới', 'peugeot-core'),
        'view_item'          => __('Xem Sản phẩm', 'peugeot-core'),
        'search_items'       => __('Tìm Sản phẩm', 'peugeot-core'),
        'not_found'          => __('Không có Sản phẩm nào', 'peugeot-core'),
        'not_found_in_trash' => __('Không có trong thùng rác', 'peugeot-core'),
        'all_items'          => __('Tất cả Sản phẩm', 'peugeot-core'),
      ],
      'public'        => true,
      'show_in_rest'  => true,
      'menu_icon'     => 'dashicons-products',
      'has_archive'   => true,
      'rewrite'       => ['slug' => 'san-pham', 'with_front' => false],
      'supports'      => ['title','editor','thumbnail','excerpt','custom-fields'],
    ]);
  }

  /* ========== TAX: Danh mục Sản phẩm (san-pham-cate) ========== */
  if (!taxonomy_exists('san-pham-cate')) {
    register_taxonomy('san-pham-cate', ['san-pham'], [
      'labels' => [
        'name'          => __('Danh mục Sản phẩm', 'peugeot-core'),
        'singular_name' => __('Danh mục Sản phẩm', 'peugeot-core'),
        'search_items'  => __('Tìm danh mục', 'peugeot-core'),
        'all_items'     => __('Tất cả danh mục', 'peugeot-core'),
        'edit_item'     => __('Sửa danh mục', 'peugeot-core'),
        'update_item'   => __('Cập nhật danh mục', 'peugeot-core'),
        'add_new_item'  => __('Thêm danh mục', 'peugeot-core'),
        'new_item_name' => __('Tên danh mục mới', 'peugeot-core'),
        'menu_name'     => __('Danh mục', 'peugeot-core'),
      ],
      'public'        => true,
      'hierarchical'  => true,
      'show_in_rest'  => true,
      'rewrite'       => ['slug' => 'san-pham-cate', 'with_front' => false],
    ]);
  }

  /* ========== CPT: Tin tức ========== */
  if (!post_type_exists('tin-tuc')) {
    register_post_type('tin-tuc', [
      'labels' => [
        'name'               => __('Tin tức', 'peugeot-core'),
        'singular_name'      => __('Tin tức', 'peugeot-core'),
        'add_new_item'       => __('Thêm Tin tức', 'peugeot-core'),
        'edit_item'          => __('Sửa Tin tức', 'peugeot-core'),
        'new_item'           => __('Tin mới', 'peugeot-core'),
        'view_item'          => __('Xem Tin', 'peugeot-core'),
        'search_items'       => __('Tìm Tin', 'peugeot-core'),
        'not_found'          => __('Không có Tin nào', 'peugeot-core'),
        'not_found_in_trash' => __('Không có trong thùng rác', 'peugeot-core'),
        'all_items'          => __('Tất cả Tin tức', 'peugeot-core'),
      ],
      'public'        => true,
      'show_in_rest'  => true,
      'menu_icon'     => 'dashicons-media-text',
      'has_archive'   => true,
      'rewrite'       => ['slug' => 'tin-tuc', 'with_front' => false],
      'supports'      => ['title','editor','thumbnail','excerpt','custom-fields'],
    ]);
  }

  /* ========== TAX: Danh mục Tin tức (tin-tuc-cate) ========== */
  if (!taxonomy_exists('tin-tuc-cate')) {
    register_taxonomy('tin-tuc-cate', ['tin-tuc'], [
      'labels' => [
        'name'          => __('Chuyên mục Tin tức', 'peugeot-core'),
        'singular_name' => __('Chuyên mục Tin tức', 'peugeot-core'),
        'search_items'  => __('Tìm chuyên mục', 'peugeot-core'),
        'all_items'     => __('Tất cả chuyên mục', 'peugeot-core'),
        'edit_item'     => __('Sửa chuyên mục', 'peugeot-core'),
        'update_item'   => __('Cập nhật chuyên mục', 'peugeot-core'),
        'add_new_item'  => __('Thêm chuyên mục', 'peugeot-core'),
        'new_item_name' => __('Tên chuyên mục mới', 'peugeot-core'),
        'menu_name'     => __('Chuyên mục', 'peugeot-core'),
      ],
      'public'        => true,
      'hierarchical'  => true,
      'show_in_rest'  => true,
      'rewrite'       => ['slug' => 'tin-tuc-cate', 'with_front' => false],
    ]);
  }

  /* ========== CPT: Đại lý ========== */
  if (!post_type_exists('dai-ly')) {
    register_post_type('dai-ly', [
      'labels' => [
        'name'               => __('Đại lý', 'peugeot-core'),
        'singular_name'      => __('Đại lý', 'peugeot-core'),
        'add_new_item'       => __('Thêm Đại lý', 'peugeot-core'),
        'edit_item'          => __('Sửa Đại lý', 'peugeot-core'),
        'new_item'           => __('Đại lý mới', 'peugeot-core'),
        'view_item'          => __('Xem Đại lý', 'peugeot-core'),
        'search_items'       => __('Tìm Đại lý', 'peugeot-core'),
        'not_found'          => __('Không có Đại lý nào', 'peugeot-core'),
        'not_found_in_trash' => __('Không có trong thùng rác', 'peugeot-core'),
        'all_items'          => __('Tất cả Đại lý', 'peugeot-core'),
      ],
      'public'        => true,
      'show_in_rest'  => true,
      'menu_icon'     => 'dashicons-store',
      'has_archive'   => true,
      'rewrite'       => ['slug' => 'dai-ly', 'with_front' => false],
      'supports'      => ['title','editor','thumbnail','excerpt','custom-fields'],
    ]);
  }

  /* ========== TAX: Miền (cho Đại lý) ========== */
  if (!taxonomy_exists('mien')) {
    register_taxonomy('mien', ['dai-ly'], [
      'labels' => [
        'name'          => __('Miền', 'peugeot-core'),
        'singular_name' => __('Miền', 'peugeot-core'),
        'search_items'  => __('Tìm miền', 'peugeot-core'),
        'all_items'     => __('Tất cả miền', 'peugeot-core'),
        'edit_item'     => __('Sửa miền', 'peugeot-core'),
        'update_item'   => __('Cập nhật miền', 'peugeot-core'),
        'add_new_item'  => __('Thêm miền', 'peugeot-core'),
        'new_item_name' => __('Tên miền mới', 'peugeot-core'),
        'menu_name'     => __('Miền', 'peugeot-core'),
      ],
      'public'        => true,
      'hierarchical'  => true,
      'show_in_rest'  => true,
      'rewrite'       => ['slug' => 'mien', 'with_front' => false],
    ]);
  }

  /* ========== TAX: Tỉnh/Thành (cho Đại lý) ========== */
  if (!taxonomy_exists('tinhthanh')) {
    register_taxonomy('tinhthanh', ['dai-ly'], [
      'labels' => [
        'name'          => __('Tỉnh/Thành', 'peugeot-core'),
        'singular_name' => __('Tỉnh/Thành', 'peugeot-core'),
        'search_items'  => __('Tìm Tỉnh/Thành', 'peugeot-core'),
        'all_items'     => __('Tất cả Tỉnh/Thành', 'peugeot-core'),
        'edit_item'     => __('Sửa Tỉnh/Thành', 'peugeot-core'),
        'update_item'   => __('Cập nhật Tỉnh/Thành', 'peugeot-core'),
        'add_new_item'  => __('Thêm Tỉnh/Thành', 'peugeot-core'),
        'new_item_name' => __('Tên Tỉnh/Thành mới', 'peugeot-core'),
        'menu_name'     => __('Tỉnh/Thành', 'peugeot-core'),
      ],
      'public'        => true,
      'hierarchical'  => true,
      'show_in_rest'  => true,
      'rewrite'       => ['slug' => 'tinh-thanh', 'with_front' => false],
    ]);
  }

}, 10);

/** MU-plugin không có activation hook → tự flush rewrite đúng 1 lần */
add_action('admin_init', function () {
  if (!get_option('peugeot_core_rewrite_flushed')) {
    flush_rewrite_rules(false);
    update_option('peugeot_core_rewrite_flushed', 1);
  }
});
