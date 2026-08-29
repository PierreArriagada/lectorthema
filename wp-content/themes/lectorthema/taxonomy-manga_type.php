<?php
/**
 * LectorThema - Taxonomy Template: Tipo de Obra (Manga, Manhwa, Manhua, Fan Comic)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$current_term = get_queried_object();
$slug = $current_term->slug;

$descriptions = [
    'manhwa'    => __('Manhwas Coreanos: Cómics digitales en formato Webtoon vertical a todo color.', 'lectorthema'),
    'manga'     => __('Mangas Japoneses: Obras maestras tradicionales en blanco y negro de lectura derecha a izquierda.', 'lectorthema'),
    'manhua'    => __('Manhuas Chinos: Historias épicas de cultivación, artes marciales y reencarnación a todo color.', 'lectorthema'),
    'fan-comic' => __('Fan Comics & Doujinshi: Creaciones independientes y tributos de la comunidad.', 'lectorthema'),
];

$desc = $descriptions[$slug] ?? $current_term->description;
?>

<main id="primary" class="site-main" style="padding: 40px 0 80px 0;">
    <div class="nexus-container">
        
        <div class="section-header-block" style="flex-direction: column; align-items: flex-start; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span class="manga-type-pill type-<?php echo esc_attr($slug); ?>" style="position: static; font-size: 14px; padding: 6px 14px;">
                    <?php echo esc_html($current_term->name); ?>
                </span>
                <h1 class="section-title" style="margin: 0;">
                    <?php printf(__('Catálogo de %s', 'lectorthema'), esc_html($current_term->name)); ?>
                </h1>
            </div>
            <?php if (!empty($desc)): ?>
                <p style="color: var(--text-secondary); font-size: 14.5px;"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>
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
                <p style="color: var(--text-muted);"><?php printf(__('No hay obras disponibles para %s.', 'lectorthema'), esc_html($current_term->name)); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
