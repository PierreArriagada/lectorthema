<?php
/**
 * LectorThema - Taxonomy Template: Género
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$current_term = get_queried_object();
?>

<main id="primary" class="site-main" style="padding: 40px 0 80px 0;">
    <div class="nexus-container">
        
        <div class="section-header-block">
            <h1 class="section-title">
                <i class="fa-solid fa-tag" style="color: var(--accent-primary);"></i> 
                <?php printf(__('Género: %s', 'lectorthema'), esc_html($current_term->name)); ?>
            </h1>
        </div>

        <?php if (have_posts()): ?>
            <div class="manga-grid">
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('template-parts/card-manga'); ?>
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
                <p style="color: var(--text-muted);"><?php printf(__('No hay obras registradas con el género %s.', 'lectorthema'), esc_html($current_term->name)); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
