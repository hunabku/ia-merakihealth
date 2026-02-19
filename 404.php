<?php
/**
 * Template de error 404.
 *
 * @package Meraki
 */

get_header(); ?>

<main class="meraki-content" role="main">
	<div class="meraki-404">
		<h1>404</h1>
		<p><?php esc_html_e( 'La página que buscas no existe o fue movida.', 'meraki' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'meraki' ); ?></a>
	</div>
</main>

<?php get_footer(); ?>
