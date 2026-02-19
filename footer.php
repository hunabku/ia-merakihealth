<?php
/**
 * Meraki Theme - footer.php
 *
 * @package Meraki
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// CASO 1 — Elementor Canvas: no renderizar nada, solo wp_footer
if ( is_singular() && 'elementor_canvas' === get_post_meta( get_the_ID(), '_wp_page_template', true ) ) {
    wp_footer();
    ?>
    </body>
    </html>
    <?php
    return;
}

// CASO 2 — Elementor Pro Theme Builder tiene Footer activo para esta página
if ( function_exists('meraki_elementor_has_location') && meraki_elementor_has_location('footer') ) {
    elementor_theme_do_location('footer');
    wp_footer();
    ?>
    </body>
    </html>
    <?php
    return;
}

// CASO 3 — Fallback: footer nativo del tema
?>

<footer id="site-footer" class="site-footer">
    <div class="site-footer__inner container">

        <!-- Logo en footer -->
        <div class="site-footer__logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Menú footer -->
        <?php if ( has_nav_menu('footer') ) : ?>
        <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Menú del footer', 'meraki'); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'footer',
                'menu_class'     => 'footer-menu',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 1,
            ] );
            ?>
        </nav>
        <?php endif; ?>

        <!-- Copyright -->
        <div class="site-footer__copy">
            <p>
                &copy; <?php echo esc_html( date_i18n('Y') ); ?>
                <?php bloginfo('name'); ?>.
                <?php esc_html_e('Todos los derechos reservados.', 'meraki'); ?>
            </p>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
