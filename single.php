<?php
/**
 * Meraki Theme - single.php
 * Template para entradas del blog
 *
 * @package Meraki
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

// CASO 1 — Elementor Canvas: página 100% en blanco
if ( 'elementor_canvas' === $page_template ) {
    while ( have_posts() ) {
        the_post();
        the_content();
    }
    return;
}

// CASO 2 — Elementor Full Width
if ( 'elementor_header_footer' === $page_template ) {
    get_header();
    ?>
    <main id="main-content" class="site-main elementor-full-width">
        <?php
        while ( have_posts() ) {
            the_post();
            the_content();
        }
        ?>
    </main>
    <?php
    get_footer();
    return;
}

// CASO 3 — Template normal del tema
get_header();
?>

<main id="main-content" class="site-main">
    <div class="container">
        <div class="content-area">

            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-entry'); ?>>

                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        <div class="entry-meta">
                            <span class="entry-date">
                                <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                            </span>
                            <span class="entry-author">
                                <?php esc_html_e( 'Por', 'meraki' ); ?>
                                <?php the_author_posts_link(); ?>
                            </span>
                            <?php if ( has_category() ) : ?>
                            <span class="entry-categories">
                                <?php the_category(', '); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail('large', [ 'class' => 'entry-thumbnail__img' ]); ?>
                    </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();
                        wp_link_pages( [
                            'before' => '<nav class="page-links"><span>' . esc_html__( 'Páginas:', 'meraki' ) . '</span>',
                            'after'  => '</nav>',
                        ] );
                        ?>
                    </div>

                    <footer class="entry-footer">
                        <?php if ( has_tag() ) : ?>
                        <div class="entry-tags">
                            <?php the_tags( '<span>' . esc_html__('Etiquetas:', 'meraki') . '</span> ', ', ' ); ?>
                        </div>
                        <?php endif; ?>
                    </footer>

                    <?php
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                    ?>

                </article>

                <!-- Navegación entre entradas -->
                <nav class="post-navigation" aria-label="<?php esc_attr_e('Navegación de entradas', 'meraki'); ?>">
                    <?php
                    the_post_navigation( [
                        'prev_text' => '← %title',
                        'next_text' => '%title →',
                    ] );
                    ?>
                </nav>

                <?php
            endwhile;
            ?>

        </div><!-- .content-area -->
    </div><!-- .container -->
</main>

<?php
get_footer();
