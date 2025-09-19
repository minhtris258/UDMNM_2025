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
          'container_class' => 'footer-cta',
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
                    <?php if (is_active_sidebar('footer_col_1')) {
            dynamic_sidebar('footer_col_1');
          } else { ?>
                    <h4 class="footer-title"><?php echo esc_html__('Về Peugeot Việt Nam', 'peugeot-theme'); ?></h4>
                    <p class="footer-desc">
                        <?php echo esc_html__('Kéo widget “Text/Custom HTML” vào khu vực này để thêm mô tả, địa chỉ, giấy phép…', 'peugeot-theme'); ?>
                    </p>
                    <?php } ?>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <?php if (is_active_sidebar('footer_col_2')) {
            dynamic_sidebar('footer_col_2');
          } else { ?>
                    <h4 class="footer-title"><?php echo esc_html__('Truy cập nhanh', 'peugeot-theme'); ?></h4>
                    <p class="footer-desc">
                        <?php echo esc_html__('Kéo widget “Navigation Menu” (trỏ tới 1 menu link).', 'peugeot-theme'); ?>
                    </p>
                    <?php } ?>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <?php if (is_active_sidebar('footer_col_3')) {
            dynamic_sidebar('footer_col_3');
          } else { ?>
                    <h4 class="footer-title"><?php echo esc_html__('Dành cho chủ xe Peugeot', 'peugeot-theme'); ?></h4>
                    <p class="footer-desc"><?php echo esc_html__('Kéo widget “Navigation Menu”.', 'peugeot-theme'); ?>
                    </p>
                    <?php } ?>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <?php if (is_active_sidebar('footer_col_4')) {
            dynamic_sidebar('footer_col_4');
          } else { ?>
                    <h4 class="footer-title"><?php echo esc_html__('Tìm hiểu thêm', 'peugeot-theme'); ?></h4>
                    <p class="footer-desc"><?php echo esc_html__('Kéo widget “Navigation Menu”.', 'peugeot-theme'); ?>
                    </p>
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
              'container_class' => 'footer-policies',
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
            echo '';
          }
          ?>
                </div>

                <div class="fb-right">
                    <?php
          if (is_active_sidebar('footer_bottom_right')) {
            dynamic_sidebar('footer_bottom_right');
          } else {
            $hotline = '1900 1101';
            echo '<div class="footer-hotline">' .
              sprintf(esc_html__('HOTLINE: %s', 'peugeot-theme'), esc_html($hotline)) .
              '</div>
                  <div class="footer-social">
                    <a class="footer-social-icon" href="#" aria-label="' . esc_attr__('Facebook', 'peugeot-theme') . '"><i class="fa fa-facebook"></i></a>
                    <a class="footer-social-icon" href="#" aria-label="' . esc_attr__('Instagram', 'peugeot-theme') . '"><i class="fa fa-instagram"></i></a>
                    <a class="footer-social-icon" href="#" aria-label="' . esc_attr__('YouTube', 'peugeot-theme') . '"><i class="fa fa-youtube-play"></i></a>
                    <a class="footer-social-icon" href="#" aria-label="' . esc_attr__('LinkedIn', 'peugeot-theme') . '"><i class="fa fa-linkedin"></i></a>
                  </div>';
          }
          ?>
                </div>

            </div>
        </div>
        <div class="text-center py-3">
            <?php
      if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
      }
      ?>
        </div>
    </div>

    <div class="peugeot-footer-bottom text-center py-3">
        &copy; <?php echo esc_html(date_i18n('Y')); ?> |
        <?php
    /* Translators: %s: developer/agency name */
    printf(
      wp_kses(
        /* translators: keep <span> tags */
        __('Thiết kế &amp; phát triển bởi <span class="fw-bold text-primary">%s</span>', 'peugeot-theme'),
        ['span' => ['class' => []]]
      ),
      'MinhTris'
    );
    ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>