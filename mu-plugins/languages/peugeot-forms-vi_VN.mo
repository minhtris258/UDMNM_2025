add_action('muplugins_loaded', function () {
  // WP sẽ tìm file .mo trong thư mục dưới đây:
  load_muplugin_textdomain('peugeot-forms', 'languages');
});
