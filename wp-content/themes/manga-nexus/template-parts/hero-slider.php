<?php
/**
 * MangaNexus - Template Part: Hero Slider (Lo Más Visto en Grande)
 *
 * Carrusel interactivo y táctil optimizado para móvil y escritorio:
 * - Sin bordes ásperos, sombras ambientales profundas
 * - Información enriquecida: Tipo, Puntuación, Estado, Géneros, Último Capítulo y Sinopsis
 * - Colores editoriales según la paleta del sistema (#6366F1 / #070709)
 *
 * @package MangaNexus
 */

if (!defined('ABSPATH')) {
    exit;
}

$featured_query = new WP_Query([
    'post_type'      => 'manga',
    'posts_per_page' => 5,
    'meta_query'     => [
        [
            'key'     => '_manga_is_featured',
            'value'   => '1',
            'compare' => '='
        ]
    ]
]);

// Fallback si no hay destacados marcados
if (!$featured_query->have_posts()) {
    $featured_query = new WP_Query([
        'post_type'      => 'manga',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);
}

if ($featured_query->have_posts()):
?>
<section class="hero-slider-section" aria-label="<?php esc_attr_e('Obras Más Vistas y Destacadas', 'manga-nexus'); ?>">
    <div class="nexus-container">
        <div class="hero-slider-container">
        <div class="hero-slides-track">
            <?php while ($featured_query->have_posts()): $featured_query->the_post();
                $manga_id = get_the_ID();
                $banner_url = get_post_meta($manga_id, '_manga_banner_url', true);
                if (!$banner_url) {
                    $banner_url = get_the_post_thumbnail_url($manga_id, 'manga-banner') ?: 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1400&auto=format&fit=crop&q=80';
                }

                $cover_url = get_the_post_thumbnail_url($manga_id, 'manga-poster');
                if (!$cover_url) {
                    $custom_cover = get_post_meta($manga_id, '_manga_custom_cover', true);
                    $cover_url = $custom_cover ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=600&auto=format&fit=crop&q=80';
                }

                $rating = get_post_meta($manga_id, '_manga_rating', true) ?: '9.8';

                $types = get_the_terms($manga_id, 'manga_type');
                $type_name = !empty($types) && !is_wp_error($types) ? $types[0]->name : 'Manga';
                $type_slug = !empty($types) && !is_wp_error($types) ? $types[0]->slug : 'manga';

                $statuses = get_the_terms($manga_id, 'manga_status');
                $status_name = !empty($statuses) && !is_wp_error($statuses) ? $statuses[0]->name : __('En Emisión', 'manga-nexus');

                $genres = get_the_terms($manga_id, 'manga_genre');
                $genre_names = [];
                if (!empty($genres) && !is_wp_error($genres)) {
                    foreach (array_slice($genres, 0, 3) as $g) {
                        $genre_names[] = $g->name;
                    }
                }
                $genres_str = !empty($genre_names) ? implode(' • ', $genre_names) : '';

                // Obtener el último capítulo
                $latest_chap = get_posts([
                    'post_type'      => 'chapter',
                    'posts_per_page' => 1,
                    'meta_key'       => '_chapter_manga_id',
                    'meta_value'     => $manga_id,
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                ]);

                $last_chap_obj = !empty($latest_chap[0]) ? $latest_chap[0] : null;
                $last_chap_num = $last_chap_obj ? (get_post_meta($last_chap_obj->ID, '_chapter_number', true) ?: '1') : null;
                $last_chap_time = $last_chap_obj ? manga_nexus_time_ago(get_the_time('U', $last_chap_obj->ID)) : '';
                $is_fav = manga_nexus_is_favorite($manga_id);
            ?>
                <div class="hero-slide">
                    <div class="hero-slide-backdrop" style="background-image: url('<?php echo esc_url($banner_url); ?>');"></div>
                    <div class="hero-slide-overlay"></div>

                    <div class="hero-slide-content">
                        <!-- Poster en Desktop con sombra ambiental -->
                        <div class="hero-slide-poster">
                            <img src="<?php echo esc_url($cover_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                        </div>

                        <!-- Información Estructurada -->
                        <div class="hero-slide-info">
                            <!-- Fila de Insignias: Tipo, Puntuación y Estado -->
                            <div class="hero-slide-badges">
                                <span class="manga-type-pill type-<?php echo esc_attr($type_slug); ?>">
                                    <?php echo esc_html($type_name); ?>
                                </span>

                                <span class="hero-rating-pill">
                                    <i class="fa-solid fa-star"></i> <?php echo esc_html($rating); ?>
                                </span>

                                <span class="hero-status-pill">
                                    <span class="status-pulse-dot"></span> <?php echo esc_html($status_name); ?>
                                </span>

                                <?php if (!empty($genres_str)): ?>
                                    <span class="hero-genres-inline"><?php echo esc_html($genres_str); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Título Principal -->
                            <h2 class="hero-slide-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <!-- Indicador de Último Capítulo -->
                            <?php if ($last_chap_obj): ?>
                                <div class="hero-latest-chapter-meta">
                                    <span class="hero-chap-label"><?php _e('Último:', 'manga-nexus'); ?></span>
                                    <a href="<?php echo esc_url(get_permalink($last_chap_obj->ID)); ?>" class="hero-chap-badge">
                                        <i class="fa-solid fa-book-open"></i> <?php printf(__('Cap. %s', 'manga-nexus'), esc_html($last_chap_num)); ?>
                                    </a>
                                    <span class="hero-chap-time"><?php echo esc_html($last_chap_time); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Sinopsis Concisa -->
                            <div class="hero-slide-synopsis">
                                <?php echo wp_trim_words(get_the_excerpt(), 28, '...'); ?>
                            </div>

                            <!-- Botones de Acción (CTA) -->
                            <div class="hero-slide-cta">
                                <a href="<?php the_permalink(); ?>" class="btn-hero-read">
                                    <i class="fa-solid fa-play"></i> <?php _e('Leer Ahora', 'manga-nexus'); ?>
                                </a>

                                <button type="button" 
                                        class="btn-hero-info btn-toggle-favorite <?php echo $is_fav ? 'is-active' : ''; ?>" 
                                        data-manga-id="<?php echo esc_attr($manga_id); ?>">
                                    <i class="<?php echo $is_fav ? 'fa-solid' : 'fa-regular'; ?> fa-bookmark"></i> 
                                    <span class="btn-fav-label"><?php echo $is_fav ? __('En Favoritos', 'manga-nexus') : __('Guardar', 'manga-nexus'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Controles Inferiores: Dots y Botones Abajo a la Derecha -->
        <div class="hero-slider-bottom-controls">
            <div class="hero-slider-dots">
                <?php for ($i = 0; $i < $featured_query->post_count; $i++): ?>
                    <div class="hero-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $i; ?>"></div>
                <?php endfor; ?>
            </div>

            <div class="hero-slider-nav-group">
                <button class="hero-slider-nav hero-slider-prev" aria-label="<?php esc_attr_e('Anterior', 'manga-nexus'); ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="hero-slider-nav hero-slider-next" aria-label="<?php esc_attr_e('Siguiente', 'manga-nexus'); ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
    </div>
</section>
<?php endif; ?>
