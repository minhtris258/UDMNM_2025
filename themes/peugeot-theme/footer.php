<?php
/**
 * Footer template
 */
?>
<footer class="peugeot-footer">

  <!-- ===== HÀNG CTA (menu 4 ô) ===== -->
  <div class="peugeot-footer-top">
    <div class="container-fluid text-center">
      <?php
      if (has_nav_menu('footer_cta')) {
        wp_nav_menu([
          'theme_location' => 'footer_cta',
          'container'      => 'nav',
          'container_class'=> 'footer-cta',
          'menu_class'     => 'footer-cta-menu',
          'depth'          => 1,
          'fallback_cb'    => '__return_empty_string',
        ]);
      } elseif (is_active_sidebar('footer_cta_widgets')) {
        dynamic_sidebar('footer_cta_widgets');
      }
      ?>
    </div>
  </div>

  <!-- ===== 4 CỘT CHÍNH ===== -->
  <div class="peugeot-footer-main">
    <div class="container">
      <div class="row peugeot-footer-main-row">
        <div class="col-12 col-md-6 col-lg-3">
          <?php if (is_active_sidebar('footer_col_1')) { dynamic_sidebar('footer_col_1'); }
          else { ?>
            <h4 class="footer-title">Về Peugeot Việt Nam</h4>
            <p class="footer-desc">Kéo widget “Text/Custom HTML” vào khu vực này để thêm mô tả, địa chỉ, giấy phép…</p>
          <?php } ?>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <?php if (is_active_sidebar('footer_col_2')) { dynamic_sidebar('footer_col_2'); }
          else { ?>
            <h4 class="footer-title">Truy cập nhanh</h4>
            <p class="footer-desc">Kéo widget “Navigation Menu” (trỏ tới 1 menu link).</p>
          <?php } ?>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <?php if (is_active_sidebar('footer_col_3')) { dynamic_sidebar('footer_col_3'); }
          else { ?>
            <h4 class="footer-title">Dành cho chủ xe Peugeot</h4>
            <p class="footer-desc">Kéo widget “Navigation Menu”.</p>
          <?php } ?>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <?php if (is_active_sidebar('footer_col_4')) { dynamic_sidebar('footer_col_4'); }
          else { ?>
            <h4 class="footer-title">Tìm hiểu thêm</h4>
            <p class="footer-desc">Kéo widget “Navigation Menu”.</p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== HÀNG DƯỚI: policies | logo | hotline+social ===== -->
  <div class="peugeot-footer-bottom">
    <div class="container">
      <div class="footer-bottom-grid">

        <div class="fb-left">
          <?php
          if (is_active_sidebar('footer_bottom_left')) {
            dynamic_sidebar('footer_bottom_left');
          } elseif (has_nav_menu('footer_policies')) {
            wp_nav_menu([
              'theme_location' => 'footer_policies',
              'container'      => 'nav',
              'container_class'=> 'footer-policies',
              'menu_class'     => 'footer-policy-links',
              'depth'          => 1,
              'fallback_cb'    => '__return_empty_string',
            ]);
          } else {
            echo '';
          }
          ?>
        </div>

        <div class="fb-center">
          <?php
          if (is_active_sidebar('footer_bottom_center')) {
            dynamic_sidebar('footer_bottom_center');
          } else {
            // fallback: logo thương hiệu
            if (function_exists('the_custom_logo') && has_custom_logo()) {
              the_custom_logo();
            } else {
              echo '<span class="footer-logo-text">'.esc_html(get_bloginfo('name')).'</span>';
            }
          }
          ?>
        </div>

        <div class="fb-right">
          <?php
          if (is_active_sidebar('footer_bottom_right')) {
            dynamic_sidebar('footer_bottom_right');
          } else {
            echo '<div class="footer-hotline">HOTLINE: 1900 1101</div>
                  <div class="footer-social">
                    <a class="footer-social-icon" href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                    <a class="footer-social-icon" href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                    <a class="footer-social-icon" href="#" aria-label="YouTube"><i class="fa fa-youtube-play"></i></a>
                    <a class="footer-social-icon" href="#" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
                  </div>';
          }
          ?>
        </div>

      </div>
    </div>
    
  </div>
  <div class="peugeot-footer-bottom text-center py-3">
        &copy; <?php echo date('Y'); ?> | Thiết kế & phát triển bởi <span class="fw-bold text-primary">MinhTris</span>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>