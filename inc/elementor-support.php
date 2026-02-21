<?php
/**
 * Meraki Theme - Elementor Support
 *
 * Compatibilidad completa con Elementor Free + Elementor Pro.
 *
 * @package Meraki
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar soporte base para Elementor.
 */
function meraki_elementor_support() {
	add_theme_support( 'elementor' );
	add_theme_support( 'header-footer-elementor' );
}
add_action( 'after_setup_theme', 'meraki_elementor_support', 11 );

/**
 * Registrar todas las ubicaciones del Theme Builder de Elementor Pro.
 *
 * Esto permite que Elementor Pro controle header, footer, single, archive, etc.
 */
function meraki_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'meraki_register_elementor_locations' );

/**
 * Detectar si Elementor Pro Theme Builder tiene un template asignado para una ubicación.
 *
 * @param string $location La ubicación a verificar ('header', 'footer', 'single', 'archive').
 * @return bool
 */
function meraki_elementor_has_location( $location ) {
	// Verificar que Elementor Pro está activo y el Theme Builder disponible.
	if ( ! class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
		return false;
	}

	$module = \ElementorPro\Modules\ThemeBuilder\Module::instance();

	if ( ! method_exists( $module, 'get_conditions_manager' ) ) {
		return false;
	}

	$conditions_manager = $module->get_conditions_manager();

	if ( ! method_exists( $conditions_manager, 'get_documents_for_location' ) ) {
		return false;
	}

	$documents = $conditions_manager->get_documents_for_location( $location );

	return ! empty( $documents );
}

/**
 * Agregar clases al body para identificar el estado de Elementor.
 */
function meraki_elementor_body_classes( $classes ) {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return $classes;
	}

	$classes[] = 'meraki-elementor-active';

	if ( function_exists( 'elementor_theme_do_location' ) ) {
		$classes[] = 'meraki-elementor-pro';
	}

	// Detectar si la página actual fue editada con Elementor.
	if ( is_singular() ) {
		$post_id = get_the_ID();

		if ( $post_id && \Elementor\Plugin::$instance->documents->get( $post_id ) ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post_id );
			if ( $document && $document->is_built_with_elementor() ) {
				$classes[] = 'meraki-built-with-elementor';
			}
		}

		// Clase según el template de página.
		$template = get_page_template_slug( $post_id );
		if ( 'elementor_canvas' === $template ) {
			$classes[] = 'meraki-elementor-canvas';
		} elseif ( 'elementor_header_footer' === $template ) {
			$classes[] = 'meraki-elementor-full-width';
		}
	}

	// Detectar si Theme Builder controla header/footer.
	if ( meraki_elementor_has_location( 'header' ) ) {
		$classes[] = 'meraki-has-elementor-header';
	}
	if ( meraki_elementor_has_location( 'footer' ) ) {
		$classes[] = 'meraki-has-elementor-footer';
	}

	return $classes;
}
add_filter( 'body_class', 'meraki_elementor_body_classes' );

/**
 * No cargar estilos del tema en Elementor Canvas.
 */
function meraki_elementor_dequeue_on_canvas() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return;
	}

	$is_canvas = false;

	// Preview del editor de Elementor.
	if ( isset( $_GET['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return; // En preview dejar que Elementor maneje todo.
	}

	// Canvas en frontend.
	if ( is_singular() && 'elementor_canvas' === get_page_template_slug() ) {
		$is_canvas = true;
	}

	if ( $is_canvas ) {
		wp_dequeue_style( 'meraki-style' );
		wp_dequeue_style( 'meraki-google-fonts' );
	}
}
add_action( 'wp_enqueue_scripts', 'meraki_elementor_dequeue_on_canvas', 999 );

/**
 * Eliminar márgenes y paddings del contenedor principal en páginas de Elementor.
 */
function meraki_elementor_inline_overrides() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return;
	}

	echo '<style id="meraki-elementor-overrides">
			/* Reset absoluto del body */
			html,
			body {
				margin: 0 !important;
				padding: 0 !important;
			}
			.elementor-editor-active body,
			.elementor-editor-active html {
				margin: 0 !important;
				padding: 0 !important;
			}
			body.elementor-page-template-canvas {
				margin: 0 !important;
				padding: 0 !important;
			}
			body.elementor-page-template-canvas #page,
			body.elementor-page-template-canvas .site-header,
			body.elementor-page-template-canvas .site-footer {
				display: none !important;
			}
			body.elementor-template-full-width .site-header,
			body.elementor-template-full-width .site-footer {
				display: none !important;
			}
			body.meraki-elementor-full-width .meraki-content {
				max-width: 100%;
				padding: 0;
				margin: 0;
			}
		</style>' . "\n";
}
add_action( 'wp_head', 'meraki_elementor_inline_overrides', 99 );


/**
 * Registrar widget areas adicionales para Elementor.
 */
function meraki_elementor_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Elementor Sidebar', 'meraki' ),
		'id'            => 'elementor-sidebar',
		'description'   => esc_html__( 'Sidebar para usar con widgets de Elementor.', 'meraki' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'meraki_elementor_widgets_init' );

// Forzar carga de main.css + style.css en el editor y preview de Elementor
add_action( 'elementor/editor/after_enqueue_styles', function() {
	$v = filemtime( get_template_directory() . '/assets/css/main.css' );
	wp_enqueue_style( 'meraki-main-editor', get_template_directory_uri() . '/assets/css/main.css', [], $v );
	wp_enqueue_style( 'meraki-editor', get_stylesheet_uri(), [ 'meraki-main-editor' ], filemtime( get_stylesheet_directory() . '/style.css' ) );
} );

add_action( 'elementor/preview/enqueue_styles', function() {
	$v = filemtime( get_template_directory() . '/assets/css/main.css' );
	wp_enqueue_style( 'meraki-main-preview', get_template_directory_uri() . '/assets/css/main.css', [], $v );
	wp_enqueue_style( 'meraki-preview', get_stylesheet_uri(), [ 'meraki-main-preview' ], filemtime( get_stylesheet_directory() . '/style.css' ) );
} );