<?php
/**
 * Meraki Theme - functions.php
 *
 * @package Meraki
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'MERAKI_VERSION', '1.0.0' );
define( 'MERAKI_DIR', get_template_directory() );
define( 'MERAKI_URI', get_template_directory_uri() );

/**
 * Soporte del tema.
 */
function meraki_setup() {
	load_theme_textdomain( 'meraki', MERAKI_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 250,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary' => esc_html__( 'Menú principal', 'meraki' ),
		'footer'  => esc_html__( 'Menú del footer', 'meraki' ),
	) );
}
add_action( 'after_setup_theme', 'meraki_setup' );

/**
 * Registrar sidebars / widget areas.
 */
function meraki_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'meraki' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'meraki_widgets_init' );

/* ============================================
   Verificación de dependencia — Elementor
============================================ */
function meraki_check_elementor_dependency() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function() {
            $install_url = admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' );
            ?>
            <div class="notice notice-error">
                <p>
                    <strong>Meraki Theme</strong> requiere el plugin
                    <strong>Elementor</strong> para funcionar correctamente.
                    <a href="<?php echo esc_url( $install_url ); ?>">
                        Instalar Elementor ahora →
                    </a>
                </p>
            </div>
            <?php
        } );
        return false;
    }
    return true;
}

/* ============================================
   Cargar archivos /inc/ — hook correcto
============================================ */
function meraki_load_includes() {
    $includes = [
        '/inc/enqueue.php',
        '/inc/elementor-support.php',
        '/inc/elementor-globals.php',
    ];

    foreach ( $includes as $file ) {
        $path = get_template_directory() . $file;
        if ( file_exists( $path ) ) {
            require_once $path;
        }
    }
}

add_action( 'after_setup_theme', function() {
    if ( meraki_check_elementor_dependency() ) {
        meraki_load_includes();
    } else {
        // Fallback: cargar solo enqueue sin dependencias de Elementor
        $enqueue = get_template_directory() . '/inc/enqueue.php';
        if ( file_exists( $enqueue ) ) {
            require_once $enqueue;
        }
    }
}, 20 );
