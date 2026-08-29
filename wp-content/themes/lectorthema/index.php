<?php
/**
 * LectorThema - Main Index Template
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main" style="padding: 40px 0;">
    <div class="nexus-container">
        <div class="section-header-block">
            <h1 class="section-title">
                <?php
                if (is_search()) {
                    printf(__('Resultados para: "%s"', 'lectorthema'), get_search_query());
                } elseif (is_archive()) {
                    the_archive_title();
                } else {
                    _e('Catálogo de Obras', 'lectorthema');
                }
                ?>
            </h1>
        </div>

        <?php if (have_posts()): ?>
            <div class="manga-grid">
                <?php while (have_posts()): the_post(); ?>
                    <?php 
                    if (get_post_type() === 'manga') {
                        get_template_part('template-parts/card-manga');
                    } else {
                        ?>
                        <article class="manga-card" style="padding: 20px;">
                            <h3 class="manga-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div style="color: var(--text-secondary); font-size: 13.5px; margin-top: 10px;">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                        <?php
                    }
                    ?>
                <?php endwhile; ?>
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <?php the_posts_pagination([
                    'prev_text' => '<i class="fa-solid fa-arrow-left"></i>',
                    'next_text' => '<i class="fa-solid fa-arrow-right"></i>',
                ]); ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; background: var(--bg-surface); border-radius: var(--radius-lg);">
                <i class="fa-solid fa-box-open" style="font-size: 40px; color: var(--text-muted); margin-bottom: 15px;"></i>
                <h2 style="font-size: 20px; color: #fff; margin-bottom: 8px;"><?php _e('No se encontraron resultados', 'lectorthema'); ?></h2>
                <p style="color: var(--text-secondary);"><?php _e('Intenta buscar con otros términos o explorar los géneros en la página principal.', 'lectorthema'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
