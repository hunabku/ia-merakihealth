<?php
/**
 * Meraki Theme - page.php
 * Template para páginas estáticas
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

// CASO 2 — Elementor Full Width: header/footer de Theme Builder, sin sidebar
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
                <article id="post-<?php the_ID(); ?>" <?php post_class('page-entry'); ?>>

                    <?php if ( ! is_front_page() ) : ?>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>
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

                </article>
                <?php
            endwhile;
            ?>

        </div><!-- .content-area -->
    </div><!-- .container -->
</main>

<?php
get_footer();
