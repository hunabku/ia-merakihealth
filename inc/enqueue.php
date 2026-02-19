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

	// Google Fonts — fuente configurable desde Apariencia > Meraki.
	$font     = get_option( 'meraki_google_font', 'Inter' );
	$font_url = 'https://fonts.googleapis.com/css2?family=' . rawurlencode( $font ) . ':wght@400;500;600;700;800&display=swap';

	wp_enqueue_style( 'meraki-google-fonts', $font_url, array(), null );

	// main.css — SCSS compilado, carga primero.
	$version_main = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/assets/css/main.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'meraki-google-fonts' ),
		$version_main
	);

	// style.css — ediciones rápidas, carga después (puede sobreescribir main.css).
	$version_style = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/style.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-style',
		get_stylesheet_uri(),
		array( 'meraki-main' ),
		$version_style
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
 */
function meraki_enqueue_elementor_editor_styles() {
	$version_main = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/assets/css/main.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-main-editor',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$version_main
	);

	$version_style = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/style.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-style-editor',
		get_stylesheet_uri(),
		array( 'meraki-main-editor' ),
		$version_style
	);
}
add_action( 'elementor/editor/after_enqueue_styles', 'meraki_enqueue_elementor_editor_styles' );

/**
 * Encolar estilos en la PREVIEW de Elementor (vista previa).
 */
function meraki_enqueue_elementor_preview_styles() {
	$version_main = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/assets/css/main.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-main-preview',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$version_main
	);

	$version_style = defined( 'WP_DEBUG' ) && WP_DEBUG
		? filemtime( get_template_directory() . '/style.css' )
		: MERAKI_VERSION;

	wp_enqueue_style(
		'meraki-style-preview',
		get_stylesheet_uri(),
		array( 'meraki-main-preview' ),
		$version_style
	);
}
add_action( 'elementor/preview/enqueue_styles', 'meraki_enqueue_elementor_preview_styles' );
