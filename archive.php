<?php
/**
 * Meraki Theme - archive.php
 * Template para páginas de archivo (categorías, etiquetas, fechas, autor)
 *
 * @package Meraki
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// CASO 1 — Elementor Pro Theme Builder tiene template de Archive activo
if ( function_exists('meraki_elementor_has_location') && meraki_elementor_has_location('archive') ) {
    get_header();
    elementor_theme_do_location('archive');
    get_footer();
    return;
}

// CASO 2 — Fallback: archive nativo del tema
get_header();
?>

<main id="main-content" class="site-main">
    <div class="container">
        <div class="content-area">

            <!-- Encabezado del archivo -->
            <header class="archive-header">
                <?php
                the_archive_title( '<h1 class="archive-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
            </header>

            <?php if ( have_posts() ) : ?>

                <div class="archive-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('archive-entry'); ?>>

                            <?php if ( has_post_thumbnail() ) : ?>
                            <div class="archive-entry__thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', [ 'class' => 'archive-entry__img' ]); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="archive-entry__body">

                                <header class="archive-entry__header">
                                    <h2 class="archive-entry__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="archive-entry__meta">
                                        <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
                                            <?php echo esc_html( get_the_date() ); ?>
                                        </time>
                                        <span class="archive-entry__author">
                                            <?php esc_html_e('Por', 'meraki'); ?>
                                            <?php the_author_posts_link(); ?>
                                        </span>
                                    </div>
                                </header>

                                <div class="archive-entry__excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="archive-entry__readmore">
                                    <?php esc_html_e('Leer más', 'meraki'); ?>
                                </a>

                            </div><!-- .archive-entry__body -->

                        </article>
                        <?php
                    endwhile;
                    ?>
                </div><!-- .archive-grid -->

                <!-- Paginación -->
                <nav class="archive-pagination" aria-label="<?php esc_attr_e('Navegación de archivo', 'meraki'); ?>">
                    <?php
                    the_posts_pagination( [
                        'mid_size'  => 2,
                        'prev_text' => esc_html__('← Anterior', 'meraki'),
                        'next_text' => esc_html__('Siguiente →', 'meraki'),
                    ] );
                    ?>
                </nav>

            <?php else : ?>

                <div class="archive-empty">
                    <p><?php esc_html_e('No se encontraron entradas.', 'meraki'); ?></p>
                </div>

            <?php endif; ?>

        </div><!-- .content-area -->
    </div><!-- .container -->
</main>

<?php
get_footer();
