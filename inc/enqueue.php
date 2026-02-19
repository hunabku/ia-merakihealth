<?php
/**
 * Meraki Theme - Enqueue scripts & styles
 *
 * @package Meraki
 */

defined( 'ABSPATH' ) || exit;

/**
 * Encolar estilos y scripts del tema.
 */
function meraki_enqueue_assets() {
	// No encolar nada en Elementor Canvas — Elementor controla todo.
	if ( is_singular() && 'elementor_canvas' === get_post_meta( get_the_ID(), '_wp_page_template', true ) ) {
		return;
	}

	// Google Fonts: Inter.
	wp_enqueue_style(
		'meraki-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	// Versioning dinámico: filemtime en desarrollo, MERAKI_VERSION en producción.
	$version = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/style.css' )
		: MERAKI_VERSION;

	// Estilo principal del tema.
	wp_enqueue_style(
		'meraki-style',
		get_stylesheet_uri(),
		array( 'meraki-google-fonts' ),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'meraki_enqueue_assets' );

/**
 * Preconectar a Google Fonts para mejorar rendimiento.
 */
function meraki_preconnect_google_fonts( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'meraki_preconnect_google_fonts', 10, 2 );

/**
 * Encolar estilos en el EDITOR de Elementor.
 * Sin esto, el style.css no se carga en el builder.
 */
function meraki_enqueue_elementor_editor_styles() {
	$version = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/style.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-style-editor',
		get_stylesheet_uri(),
		array(),
		$version
	);
}
add_action( 'elementor/editor/after_enqueue_styles', 'meraki_enqueue_elementor_editor_styles' );

/**
 * Encolar estilos en la PREVIEW de Elementor (vista previa).
 */
function meraki_enqueue_elementor_preview_styles() {
	$version = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/style.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-style-preview',
		get_stylesheet_uri(),
		array(),
		$version
	);
}
add_action( 'elementor/preview/enqueue_styles', 'meraki_enqueue_elementor_preview_styles' );
