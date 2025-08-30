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
        <div class="row align-items-center">
            <!-- Logo chiếm 2 phần -->
            <div class="col-1 d-flex align-items-center">
                <a class="peugeot-logo" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        echo '<span class="site-title">' . esc_html(get_bloginfo('name')) . '</span>';
                    }
                    ?>
                </a>
            </div>
            <!-- Nav chiếm 10 phần -->
            <div class="col-11">
                <nav class="peugeot-menu">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu',
                        'container'      => false,
                        'menu_class'     => 'peugeot-menu-list d-flex gap-4 justify-content-start mb-0 ps-2',
                        'fallback_cb'    => false,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    ));
                    ?>
                </nav>
                <hr class="peugeot-divider">
            </div>
        </div>
    </div>
</header>