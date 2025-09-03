<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="peugeot-header">
    <div class="container-fluid">
        <div class="d-flex align-items-center position-relative w-100 peugeot-header-inner" style="height: 80px;">
            <!-- Hamburger sát mép trái, chỉ hiện mobile/tablet -->
            <button class="peugeot-hamburger d-block d-md-none" aria-label="Menu" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- Gạch ngang trái -->
            <div class="peugeot-mobile-divider divider-left d-block d-md-none"></div>
            <!-- Logo giữa -->
            <a class="peugeot-logo" href="<?php echo esc_url(home_url('/')); ?>">
                <?php
                if (function_exists('the_custom_logo') && has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<span class="site-title">' . esc_html(get_bloginfo('name')) . '</span>';
                }
                ?>
            </a>
            <!-- Gạch ngang phải -->
            <div class="peugeot-mobile-divider divider-right d-block d-md-none"></div>
            <!-- Menu desktop -->
            <!-- Nav chung cho 2 menu -->
        <nav class="peugeot-main-nav d-none d-md-flex flex-grow-1 align-items-center">
            <!-- Menu chính -->
            <?php
            wp_nav_menu([
                'theme_location' => 'primary_menu',
                'container'      => false,
                'menu_class'     => 'peugeot-menu-list d-flex gap-4 mb-0',
                'fallback_cb'    => false,
            ]);
            ?>

            <!-- Menu phụ -->
            <?php
            wp_nav_menu([
                'theme_location' => 'extra_right_menu',
                'container'      => false,
                'menu_class'     => 'extra-menu-list d-flex gap-3 mb-0 ms-auto',
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>
                    


        <!-- Menu mobile overlay -->
        <div class="peugeot-mobile-menu-overlay" id="mobileMenuOverlay">
            <button class="close-mobile-menu" id="closeMobileMenu">&times;</button>
             <?php
            wp_nav_menu([
                'theme_location' => 'primary_menu',
                'container'      => false,
                'menu_class'     => 'peugeot-mobile-menu-list',
                'fallback_cb'    => false,
            ]);
            wp_nav_menu([
                'theme_location' => 'extra_right_menu',
                'container'      => false,
                'menu_class'     => 'peugeot-mobile-menu-list',
                'fallback_cb'    => false,
            ]);
            ?>
        </div>
    </div>
</header>