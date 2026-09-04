<?php
/**
 * LectorThema - Template Part: Top Manga Card
 *
 * Card unificada para Top de la Comunidad y Top por Género:
 * - Medallas #1, #2, #3 en la esquina superior izquierda
 * - Estrellas (★ 9.8) y Título sobre la portada (Overlay)
 * - Capítulos ABAJO lado a lado con abreviatura 'Cap.' (Último en ROJO)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

$manga_id = !empty($args['manga_id']) ? (int) $args['manga_id'] : get_the_ID();
$rank     = isset($args['rank']) ? (int) $args['rank'] : 0;
$manga    = get_post($manga_id);

if (!$manga || $manga->post_type !== 'manga') {
    return;
}

$cover = get_the_post_thumbnail_url($manga_id, 'manga-poster-sm');
if (!$cover) {
    $cover = get_post_meta($manga_id, '_manga_custom_cover', true) ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=300&auto=format&fit=crop&q=80';
}

$types = get_the_terms($manga_id, 'manga_type');
$type_name = !empty($types) && !is_wp_error($types) ? $types[0]->name : 'Manga';
$type_slug = !empty($types) && !is_wp_error($types) ? $types[0]->slug : 'manga';

// Obtener los 2 últimos capítulos
$top_chapters = get_posts([
    'post_type'      => 'chapter',
    'posts_per_page' => 2,
    'meta_key'       => '_chapter_manga_id',
    'meta_value'     => $manga_id,
    'orderby'        => 'date',
    'order'          => 'DESC'
]);

$ch1 = !empty($top_chapters[0]) ? $top_chapters[0] : null;
$ch2 = !empty($top_chapters[1]) ? $top_chapters[1] : null;

$ch1_num = $ch1 ? (get_post_meta($ch1->ID, '_chapter_number', true) ?: '1') : null;
$ch2_num = $ch2 ? (get_post_meta($ch2->ID, '_chapter_number', true) ?: '1') : null;

$rating = get_post_meta($manga_id, '_manga_rating', true) ?: '9.8';
$views_total = isset($args['views']) ? (int) $args['views'] : lectorthema_get_total_views($manga_id);
$views_fmt = lectorthema_format_views($views_total);
$status_info = lectorthema_get_manga_status_info($manga_id);
// Determinar si se muestra el estado de forma armónica
$show_status = isset($args['show_status']) ? (bool) $args['show_status'] : (!is_front_page() && $status_info['slug'] !== 'en-emision');
?>
<article class="top-rank-card" data-rank="<?php echo esc_attr($rank); ?>">
    <div class="top-rank-cover-wrap">
        <!-- Portada -->
        <a href="<?php echo esc_url(get_permalink($manga_id)); ?>" class="top-rank-cover-link" aria-label="<?php echo esc_attr($manga->post_title); ?>">
            <img src="<?php echo esc_url($cover); ?>" alt="<?php echo esc_attr($manga->post_title); ?>" class="top-rank-cover" loading="lazy">
        </a>

        <!-- Barra Superior: Medalla, Tipo y Estado -->
        <div class="top-rank-card-top-bar">
            <div class="manga-top-left-group">
                <?php if ($rank > 0): ?>
                    <div class="rank-badge-wrap">
                        <span class="rank-number <?php echo $rank <= 3 ? 'rank-' . $rank : ''; ?>">
                            #<?php echo $rank; ?>
                        </span>
                    </div>
                <?php endif; ?>

                <span class="manga-type-pill type-<?php echo esc_attr($type_slug); ?>">
                    <?php echo esc_html($type_name); ?>
                </span>
            </div>

            <?php if ($show_status): ?>
                <span class="manga-status-badge <?php echo esc_attr($status_info['class']); ?>" title="<?php printf(esc_attr__('Estado: %s', 'lectorthema'), esc_attr($status_info['name'])); ?>">
                    <span class="status-dot"></span> <span class="status-text"><?php echo esc_html($status_info['name']); ?></span>
                </span>
            <?php endif; ?>
        </div>

        <!-- Degradado Inferior Sobre la Portada: Puntuación y Título -->
        <div class="top-rank-overlay">
            <div class="top-rank-rating-row">
                <span class="top-rank-rating-pill">
                    <i class="fa-solid fa-star"></i> <?php echo esc_html($rating); ?>
                </span>
                <?php if ($views_total > 0): ?>
                    <span class="top-rank-views-pill">
                        <i class="fa-solid fa-eye"></i> <?php echo esc_html($views_fmt); ?>
                    </span>
                <?php endif; ?>
            </div>

            <h4 class="top-rank-title">
                <a href="<?php echo esc_url(get_permalink($manga_id)); ?>" title="<?php echo esc_attr($manga->post_title); ?>">
                    <?php echo esc_html($manga->post_title); ?>
                </a>
            </h4>
        </div>
    </div>

    <!-- Pie de Tarjeta: Capítulos ABAJO lado a lado (Último en ROJO) -->
    <div class="top-rank-bottom-bar">
        <div class="top-rank-caps-row">
            <?php if ($ch1_num): ?>
                <a href="<?php echo esc_url(get_permalink($ch1->ID)); ?>" class="cap-pill cap-latest-red" title="<?php printf(__('Capítulo %s', 'lectorthema'), esc_attr($ch1_num)); ?>">
                    Cap. <?php echo esc_html($ch1_num); ?>
                </a>
            <?php endif; ?>

            <?php if ($ch2_num): ?>
                <a href="<?php echo esc_url(get_permalink($ch2->ID)); ?>" class="cap-pill cap-prev-neutral" title="<?php printf(__('Capítulo %s', 'lectorthema'), esc_attr($ch2_num)); ?>">
                    Cap. <?php echo esc_html($ch2_num); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>
