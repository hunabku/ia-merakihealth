<?php
/**
 * Template fallback principal.
 *
 * @package Meraki
 */

get_header(); ?>

<main class="meraki-content" role="main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div>
			</article>
		<?php endwhile; ?>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No se encontraron entradas.', 'meraki' ); ?></p>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
