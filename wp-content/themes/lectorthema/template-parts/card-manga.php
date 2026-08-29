<?php
/**
 * LectorThema - Template Part: Manga Card (Últimos Capítulos Agregados)
 *
 * Diseño Original Calibrado:
 * - Barra superior: Puntuación (★ 9.8), Tipo de Obra (Manhwa/Manga) y Botón de Favorito
 * - Portada con relación 3:4
 * - Sobre la imagen (Overlay): Géneros y Título siempre nítidos y legibles
 * - Pie de tarjeta: 2 Filas de capítulos horizontales (Último capítulo en ROJO)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

$manga_id = get_the_ID();

// Obtener Puntuación
$rating = get_post_meta($manga_id, '_manga_rating', true) ?: '9.8';

// Comprobar si está en favoritos del usuario
$is_fav = lectorthema_is_favorite($manga_id);

// Obtener Tipo de Obra
$types = get_the_terms($manga_id, 'manga_type');
$type_name = !empty($types) && !is_wp_error($types) ? $types[0]->name : 'Manga';
$type_slug = !empty($types) && !is_wp_error($types) ? $types[0]->slug : 'manga';

// Obtener Estado de la Obra
$status_info = lectorthema_get_manga_status_info($manga_id);

// Obtener Géneros (primeros 2 para el resumen visual)
$genres = get_the_terms($manga_id, 'manga_genre');
$genre_names = [];
if (!empty($genres) && !is_wp_error($genres)) {
    foreach (array_slice($genres, 0, 2) as $g) {
        $genre_names[] = $g->name;
    }
}
$genres_str = !empty($genre_names) ? implode(' • ', $genre_names) : '';

// Obtener Portada
$cover_url = get_the_post_thumbnail_url($manga_id, 'manga-poster');
if (!$cover_url) {
    $custom_cover = get_post_meta($manga_id, '_manga_custom_cover', true);
    $cover_url = $custom_cover ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=600&auto=format&fit=crop&q=80';
}

// Obtener los 2 últimos capítulos
$chapters_query = new WP_Query([
    'post_type'      => 'chapter',
    'posts_per_page' => 2,
    'meta_key'       => '_chapter_manga_id',
    'meta_value'     => $manga_id,
    'orderby'        => 'date',
    'order'          => 'DESC'
]);

$latest_chapters = $chapters_query->posts;
wp_reset_postdata();
?>

<article class="manga-card" data-manga-id="<?php echo esc_attr($manga_id); ?>">
    <div class="manga-card-cover-wrap">
        <!-- Portada -->
        <a href="<?php the_permalink(); ?>" class="manga-card-cover-link" aria-label="<?php the_title_attribute(); ?>">
            <img src="<?php echo esc_url($cover_url); ?>" alt="<?php the_title_attribute(); ?>" class="manga-card-cover" loading="lazy">
        </a>

        <!-- Barra Superior de la Card: Puntuación, Tipo y Botón de Favorito Rápido -->
        <div class="manga-card-top-bar">
            <div class="manga-top-left-group">
                <!-- Puntuación -->
                <span class="manga-rating-pill" title="<?php printf(__('Puntuación: %s / 10', 'lectorthema'), esc_attr($rating)); ?>">
                    <i class="fa-solid fa-star"></i> <?php echo esc_html($rating); ?>
                </span>

                <!-- Tipo de Obra -->
                <span class="manga-type-pill type-<?php echo esc_attr($type_slug); ?>">
                    <?php echo esc_html($type_name); ?>
                </span>

                <!-- Estado de la Obra -->
                <span class="manga-status-badge <?php echo esc_attr($status_info['class']); ?>" title="<?php printf(esc_attr__('Estado: %s', 'lectorthema'), esc_attr($status_info['name'])); ?>" style="padding: 2px 7px; font-size: 10.5px;">
                    <span class="status-dot"></span> <?php echo esc_html($status_info['name']); ?>
                </span>
            </div>

            <!-- Botón de Marcador / Guardar en Favoritos -->
            <button type="button" 
                    class="manga-card-fav-btn btn-toggle-favorite <?php echo $is_fav ? 'is-active' : ''; ?>" 
                    data-manga-id="<?php echo esc_attr($manga_id); ?>" 
                    title="<?php echo $is_fav ? esc_attr__('Guardado en Favoritos', 'lectorthema') : esc_attr__('Guardar en Favoritos', 'lectorthema'); ?>"
                    aria-label="<?php esc_attr_e('Guardar en favoritos', 'lectorthema'); ?>">
                <?php echo $is_fav ? lectorthema_svg('bookmark', 'svg-card-fav', 13) : lectorthema_svg('bookmark-outline', 'svg-card-fav', 13); ?>
            </button>
        </div>

        <!-- Degradado Inferior Sobre la Imagen con Géneros y Título -->
        <div class="manga-card-overlay">
            <?php if (!empty($genres_str)): ?>
                <span class="manga-card-genres"><?php echo esc_html($genres_str); ?></span>
            <?php endif; ?>
            <h3 class="manga-card-title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h3>
        </div>
    </div>

    <!-- Pie de Tarjeta: Filas Horizontales de Capítulos (Último Capítulo en ROJO) -->
    <div class="manga-card-chapters-list">
        <?php if (!empty($latest_chapters)): ?>
            <?php 
            $ch1 = $latest_chapters[0];
            $ch1_num = get_post_meta($ch1->ID, '_chapter_number', true) ?: '1';
            $ch1_time = lectorthema_time_ago(get_the_time('U', $ch1->ID));
            ?>
            <a href="<?php echo esc_url(get_permalink($ch1->ID)); ?>" class="chapter-horizontal-row ch-latest-red" title="<?php printf(__('Cap. %s', 'lectorthema'), esc_attr($ch1_num)); ?>">
                <span class="chap-num">
                    <?php printf(__('Cap. %s', 'lectorthema'), esc_html($ch1_num)); ?>
                </span>
                <span class="chap-time"><?php echo esc_html($ch1_time); ?></span>
            </a>

            <?php if (isset($latest_chapters[1])): ?>
                <?php 
                $ch2 = $latest_chapters[1];
                $ch2_num = get_post_meta($ch2->ID, '_chapter_number', true) ?: '1';
                $ch2_time = lectorthema_time_ago(get_the_time('U', $ch2->ID));
                ?>
                <a href="<?php echo esc_url(get_permalink($ch2->ID)); ?>" class="chapter-horizontal-row ch-prev-neutral" title="<?php printf(__('Cap. %s', 'lectorthema'), esc_attr($ch2_num)); ?>">
                    <span class="chap-num">
                        <?php printf(__('Cap. %s', 'lectorthema'), esc_html($ch2_num)); ?>
                    </span>
                    <span class="chap-time"><?php echo esc_html($ch2_time); ?></span>
                </a>
            <?php endif; ?>

        <?php else: ?>
            <div class="chapter-horizontal-row ch-prev-neutral" style="opacity: 0.5; justify-content: center; cursor: default;">
                <span class="chap-num"><?php _e('Próximamente', 'lectorthema'); ?></span>
            </div>
        <?php endif; ?>
    </div>
</article>
