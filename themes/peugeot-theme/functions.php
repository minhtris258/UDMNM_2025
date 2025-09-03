<?php
// ==========================
// Theme Supports
// ==========================
add_theme_support('post-thumbnails');
add_theme_support('custom-logo', [
    'height'      => 100,
    'width'       => 80,
    'flex-height' => true,
    'flex-width'  => true,
    'header-text' => ['site-title', 'site-description'],
]);
add_theme_support('title-tag');

// ==========================
// Enqueue Scripts & Styles
// ==========================
if (!function_exists('peugeut_theme_enqueue_assets')) {
    function peugeut_theme_enqueue_assets() {
        // Bootstrap
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);

        // Fonts & Icons
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

        // JS (custom.js)
        wp_enqueue_script('peugeot-slider-js', get_theme_file_uri('/custom.js'), array(), null, true);
        // CSS (custom.css)
        wp_enqueue_style('peugeot-custom-css', get_template_directory_uri() . '/custom.css');
    }
    add_action('wp_enqueue_scripts', 'peugeut_theme_enqueue_assets');
}

// ==========================
// Register Menus
// ==========================
if (!function_exists('peugeut_theme_register_menus')) {
   function peugeut_theme_register_menus() {
    register_nav_menus([
        'primary_menu'    => __('Menu chính', 'peugeut-theme'),
        'footer_menu_1'   => __('Menu footer 1', 'peugeut-theme'),
        'footer_menu_2'   => __('Menu footer 2', 'peugeut-theme'),
        'extra_right_menu'=> __('Menu bên phải', 'peugeut-theme'), // thêm mới
    ]);
}
add_action('after_setup_theme', 'peugeut_theme_register_menus');

}

// ==========================
// Custom Logo Display (in header.php)
// ==========================
if (!function_exists('peugeut_theme_display_logo')) {
    function peugeut_theme_display_logo() {
        if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
        } else {
            echo '<h1 class="site-title">' . esc_html(get_bloginfo('name')) . '</h1>';
        }
    }
}

// ==========================
// Optimize Uploaded Images
// ==========================
add_filter('jpeg_quality', function() { return 80; }); // JPG quality
add_filter('wp_handle_upload_prefilter', function($file) {
    // Extend here for advanced compression (Imagick/ImageMagick)
    return $file;
});

// ==========================
// Custom Login Logo
// ==========================
if (!function_exists('peugeut_theme_custom_login_logo')) {
    function peugeut_theme_custom_login_logo() {
        ?>
        <style type="text/css">
            .login h1 a {
                background-image: url('<?php echo esc_url(get_template_directory_uri() . '/images/login-logo.png'); ?>') !important;
                background-size: contain !important;
                width: 200px !important;
                height: 80px !important;
                display: block !important;
            }
        </style>
        <?php
    }
    add_action('login_enqueue_scripts', 'peugeut_theme_custom_login_logo');
}