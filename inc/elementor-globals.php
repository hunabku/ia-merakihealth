<?php
/**
 * Meraki Theme - Elementor Global Colors & Typography
 *
 * @package Meraki
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/* ============================================
   GLOBAL COLORS — Elementor Pro Kit
============================================ */
add_action( 'elementor/element/kit/section_color/before_section_end', function( $element, $args ) {

    $element->add_control(
        'meraki_colors_heading', [
            'label'     => esc_html__( '— Meraki Theme Colors —', 'meraki' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]
    );

    $colors = [
        [
            'id'    => 'meraki_color_primary',
            'label' => esc_html__( 'Primary', 'meraki' ),
            'value' => get_option( 'meraki_color_primary', '#2D6BE4' ),
        ],
        [
            'id'    => 'meraki_color_secondary',
            'label' => esc_html__( 'Secondary', 'meraki' ),
            'value' => get_option( 'meraki_color_secondary', '#1A1A2E' ),
        ],
        [
            'id'    => 'meraki_color_accent',
            'label' => esc_html__( 'Accent', 'meraki' ),
            'value' => get_option( 'meraki_color_accent', '#FF6B35' ),
        ],
        [
            'id'    => 'meraki_color_text',
            'label' => esc_html__( 'Text', 'meraki' ),
            'value' => get_option( 'meraki_color_text', '#333333' ),
        ],
        [
            'id'    => 'meraki_color_light',
            'label' => esc_html__( 'Light', 'meraki' ),
            'value' => get_option( 'meraki_color_light', '#F8F9FA' ),
        ],
    ];

    foreach ( $colors as $color ) {
        $element->add_control(
            $color['id'], [
                'label'     => $color['label'],
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => $color['value'],
            ]
        );
    }

}, 10, 2 );

/* ============================================
   CUSTOM FONTS — Registrar en Elementor
============================================ */
add_filter( 'elementor/fonts/additional_fonts', function( $fonts ) {
    // Fuentes locales del tema desde /assets/fonts/
    // Agregar aquí las fuentes cuando se suban al tema
    // Ejemplo:
    // $fonts['Meraki Sans']  = 'custom';
    // $fonts['Meraki Serif'] = 'custom';
    return $fonts;
} );

/* ============================================
   CSS VARIABLES — Sincronizar con Elementor
   Inyecta las variables CSS del tema para que
   Elementor y el tema compartan los mismos valores
============================================ */
add_action( 'wp_enqueue_scripts', function() {
	$primary   = get_option( 'meraki_color_primary',   '#2D6BE4' );
	$secondary = get_option( 'meraki_color_secondary', '#1A1A2E' );
	$accent    = get_option( 'meraki_color_accent',    '#FF6B35' );
	$text      = get_option( 'meraki_color_text',      '#333333' );
	$light     = get_option( 'meraki_color_light',     '#F8F9FA' );

	$css = ":root {
		--color-primary:    {$primary};
		--color-secondary:  {$secondary};
		--color-accent:     {$accent};
		--color-text:       {$text};
		--color-light:      {$light};
		--meraki-primary:   {$primary};
		--meraki-secondary: {$secondary};
		--meraki-accent:    {$accent};
		--meraki-text:      {$text};
		--meraki-light:     {$light};
	}";

	wp_add_inline_style( 'meraki-main', $css );

	$custom_css = get_option( 'meraki_custom_css', '' );
	if ( $custom_css ) {
		wp_add_inline_style( 'meraki-style', $custom_css );
	}
}, 20 );

/* ============================================
   ELEMENTOR PRO — Verificar antes de cargar
   hooks que dependen exclusivamente de Pro
============================================ */
add_action( 'elementor/init', function() {
    if ( ! class_exists('\ElementorPro\Plugin') ) {
        return;
    }
    // Hooks exclusivos de Elementor Pro van aquí
} );
