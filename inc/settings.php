<?php
/**
 * Meraki Theme - Admin Settings Page
 *
 * @package Meraki
 */

defined( 'ABSPATH' ) || exit;

// ── Menú admin ────────────────────────────────────────────────────────────────
add_action( 'admin_menu', function() {
	add_theme_page(
		__( 'Meraki Settings', 'meraki' ),
		__( 'Meraki', 'meraki' ),
		'manage_options',
		'meraki-settings',
		'meraki_settings_page'
	);
} );

// ── Enqueue color picker solo en esta página ──────────────────────────────────
add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( 'appearance_page_meraki-settings' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script(
		'meraki-admin',
		get_template_directory_uri() . '/assets/js/admin.js',
		[ 'wp-color-picker' ],
		MERAKI_VERSION,
		true
	);
} );

// ── Registro de settings ──────────────────────────────────────────────────────
add_action( 'admin_init', function() {
	$colors = [ 'primary', 'secondary', 'accent', 'text', 'light' ];
	foreach ( $colors as $key ) {
		register_setting( 'meraki_settings', "meraki_color_{$key}", [
			'sanitize_callback' => 'sanitize_hex_color',
		] );
	}
	register_setting( 'meraki_settings', 'meraki_google_font', [
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'Inter',
	] );
	register_setting( 'meraki_settings', 'meraki_container_width', [
		'sanitize_callback' => 'absint',
		'default'           => 1200,
	] );
	register_setting( 'meraki_settings', 'meraki_custom_css', [
		'sanitize_callback' => 'wp_strip_all_tags',
	] );
} );

// ── Render de la página ───────────────────────────────────────────────────────
function meraki_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$color_fields = [
		'meraki_color_primary'   => [ 'label' => 'Primary',   'default' => '#2D6BE4' ],
		'meraki_color_secondary' => [ 'label' => 'Secondary', 'default' => '#1A1A2E' ],
		'meraki_color_accent'    => [ 'label' => 'Accent',    'default' => '#FF6B35' ],
		'meraki_color_text'      => [ 'label' => 'Text',      'default' => '#333333' ],
		'meraki_color_light'     => [ 'label' => 'Light',     'default' => '#F8F9FA' ],
	];
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Meraki Theme Settings', 'meraki' ); ?></h1>

		<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Configuración guardada.', 'meraki' ); ?></p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'meraki_settings' ); ?>

			<h2><?php esc_html_e( 'Colores', 'meraki' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php foreach ( $color_fields as $key => $field ) : ?>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
					<td>
						<input
							type="text"
							id="<?php echo esc_attr( $key ); ?>"
							name="<?php echo esc_attr( $key ); ?>"
							value="<?php echo esc_attr( get_option( $key, $field['default'] ) ); ?>"
							class="meraki-color-picker"
							data-default-color="<?php echo esc_attr( $field['default'] ); ?>"
						>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>

			<h2><?php esc_html_e( 'Tipografía', 'meraki' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="meraki_google_font"><?php esc_html_e( 'Google Font', 'meraki' ); ?></label></th>
					<td>
						<input type="text" id="meraki_google_font" name="meraki_google_font" value="<?php echo esc_attr( get_option( 'meraki_google_font', 'Inter' ) ); ?>" class="regular-text">
						<p class="description"><?php esc_html_e( 'Nombre exacto de Google Fonts. Ej: Inter, Roboto, Open Sans', 'meraki' ); ?></p>
					</td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'Layout', 'meraki' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="meraki_container_width"><?php esc_html_e( 'Ancho del contenedor (px)', 'meraki' ); ?></label></th>
					<td>
						<input type="number" id="meraki_container_width" name="meraki_container_width" value="<?php echo absint( get_option( 'meraki_container_width', 1200 ) ); ?>" class="small-text" min="600" max="2560">
						<p class="description"><?php esc_html_e( 'También actualiza el contenedor de Elementor automáticamente.', 'meraki' ); ?></p>
					</td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'CSS personalizado', 'meraki' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="meraki_custom_css"><?php esc_html_e( 'CSS extra', 'meraki' ); ?></label></th>
					<td>
						<textarea id="meraki_custom_css" name="meraki_custom_css" rows="10" class="large-text code"><?php echo esc_textarea( get_option( 'meraki_custom_css', '' ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Se carga después de main.css y style.css.', 'meraki' ); ?></p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
