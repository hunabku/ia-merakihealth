<?php
/**
 * Meraki Theme - header.php
 *
 * @package Meraki
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// CASO 1 — Elementor Canvas: sin header, sin footer, sin HTML del tema
if ( is_singular() && 'elementor_canvas' === get_post_meta( get_the_ID(), '_wp_page_template', true ) ) {
    return;
}

// CASO 2 — Elementor Pro Theme Builder tiene Header activo para esta página
if ( function_exists('meraki_elementor_has_location') && meraki_elementor_has_location('header') ) {
    // Imprimir solo el doctype y wp_head, Theme Builder renderiza el header
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php
    // Renderizar el Header de Elementor Pro Theme Builder
    elementor_theme_do_location('header');
    return;
}

// CASO 3 — Fallback: header nativo del tema
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('meraki-site'); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header">
    <div class="site-header__inner container">

        <!-- Logo -->
        <div class="site-header__logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-header__site-name">
                    <?php bloginfo('name'); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Navegación principal -->
        <nav id="site-navigation" class="site-header__nav" aria-label="<?php esc_attr_e('Menú principal', 'meraki'); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 'primary-menu',
                'container'      => false,
                'depth'          => 2,
                'fallback_cb'    => false,
            ] );
            ?>
        </nav>

        <!-- Botón hamburguesa mobile -->
        <button class="site-header__toggle" aria-controls="site-navigation" aria-expanded="false">
            <span class="screen-reader-text"><?php esc_html_e('Abrir menú', 'meraki'); ?></span>
            <span class="site-header__toggle-icon"></span>
        </button>

    </div>
</header>
