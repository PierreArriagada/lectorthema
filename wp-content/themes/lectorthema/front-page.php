<?php
/**
 * LectorThema - Front Page Template (Portada Principal)
 *
 * Estructura:
 * 1. Hero Slider táctil con lo más visto arriba.
 * 2. TOP DE LA COMUNIDAD (Arriba de Últimos Agregados): Semanal, Diario, Mensual, Histórico.
 * 3. ÚLTIMOS CAPÍTULOS AGREGADOS: Tarjetas con diseño optimizado (barra superior de puntuación + favoritos, y filas horizontales con blur).
 * 4. TOP POR GÉNERO (Mínimo 8 géneros).
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// 1. Slider Superior
get_template_part('template-parts/hero-slider');
?>

<main id="primary" class="site-main">
    <div class="nexus-container">
        <div class="home-main-layout" style="display: grid; grid-template-columns: 1fr 320px; gap: 30px; margin-top: 25px;">
            
            <!-- Columna Principal Izquierda -->
            <div class="home-primary-col">
                
                <!-- 2. SECCIÓN: TOP DE LA COMUNIDAD (ARRIBA DE ÚLTIMOS AGREGADOS) -->
                <?php get_template_part('template-parts/top-rankings'); ?>

                <!-- 3. SECCIÓN: ÚLTIMOS AGREGADOS / CAPÍTULOS RECIENTES -->
                <section class="latest-updates-section" style="margin-top: 15px;">
                    <div class="section-header-block">
                        <h2 class="section-title">
                            <i class="fa-solid fa-bolt-lightning"></i> <?php _e('Últimos Capítulos Agregados', 'lectorthema'); ?>
                        </h2>
                        <a href="<?php echo esc_url(home_url('/mangas/')); ?>" class="section-more-link">
                            <?php _e('Ver Todos', 'lectorthema'); ?> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

                    <?php
                    $latest_query = new WP_Query([
                        'post_type'      => 'manga',
                        'posts_per_page' => 12,
                        'orderby'        => 'modified',
                        'order'          => 'DESC'
                    ]);

                    if ($latest_query->have_posts()):
                    ?>
                        <div class="manga-grid">
                            <?php while ($latest_query->have_posts()): $latest_query->the_post(); ?>
                                <?php get_template_part('template-parts/card-manga'); ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--text-muted); padding: 40px 0; text-align: center;">
                            <?php _e('No hay mangas registrados en el catálogo.', 'lectorthema'); ?>
                        </p>
                    <?php endif; ?>
                </section>

                <!-- 4. SECCIÓN: TOP POR GÉNERO (MÍNIMO 8 GÉNEROS) -->
                <?php get_template_part('template-parts/genre-section'); ?>

            </div>

            <!-- Columna Lateral Derecha (Sidebar) -->
            <aside class="home-sidebar-col">
                <!-- Widget: Joyas Ocultas & Fan Comics -->
                <div class="top-rankings-box">
                    <div class="section-header-block" style="margin-bottom: 15px;">
                        <h3 class="section-title" style="font-size: 16px;">
                            <i class="fa-solid fa-crown" style="color: var(--warning);"></i> <?php _e('Joyas Ocultas & Fan Comics', 'lectorthema'); ?>
                        </h3>
                    </div>

                    <?php
                    $fancomic_query = new WP_Query([
                        'post_type'      => 'manga',
                        'posts_per_page' => 4,
                        'tax_query'      => [
                            [
                                'taxonomy' => 'manga_type',
                                'field'    => 'slug',
                                'terms'    => ['fan-comic', 'manhua'],
                            ],
                        ],
                    ]);

                    if ($fancomic_query->have_posts()):
                        while ($fancomic_query->have_posts()): $fancomic_query->the_post();
                            $m_id = get_the_ID();
                            $cover = get_the_post_thumbnail_url($m_id, 'manga-poster-sm');
                            if (!$cover) {
                                $cover = get_post_meta($m_id, '_manga_custom_cover', true) ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=200&auto=format&fit=crop&q=80';
                            }
                            $rating = get_post_meta($m_id, '_manga_rating', true) ?: '9.5';
                        ?>
                            <a href="<?php the_permalink(); ?>" class="top-rank-item" style="margin-bottom: 8px;">
                                <div class="rank-thumb">
                                    <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                </div>
                                <div class="rank-info">
                                    <h4 class="rank-title"><?php the_title(); ?></h4>
                                    <div class="rank-meta">
                                        <span style="color: var(--warning); font-size: 11px; font-weight: 700;">
                                            <i class="fa-solid fa-star"></i> <?php echo esc_html($rating); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; wp_reset_postdata();
                    endif; ?>
                </div>

                <!-- Widget: Formatos y Tipos -->
                <div class="top-rankings-box" style="margin-top: 25px;">
                    <div class="section-header-block" style="margin-bottom: 15px;">
                        <h3 class="section-title" style="font-size: 16px;">
                            <i class="fa-solid fa-layer-group" style="color: var(--primary);"></i> <?php _e('Formatos de Lectura', 'lectorthema'); ?>
                        </h3>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="<?php echo esc_url(home_url('/tipo/manhwa/')); ?>" class="top-rank-item" style="justify-content: space-between;">
                            <span style="font-weight: 700; color: var(--success);"><i class="fa-solid fa-scroll"></i> Manhwas (Webtoons)</span>
                            <i class="fa-solid fa-chevron-right" style="font-size: 11px; color: var(--text-muted);"></i>
                        </a>
                        <a href="<?php echo esc_url(home_url('/tipo/manga/')); ?>" class="top-rank-item" style="justify-content: space-between;">
                            <span style="font-weight: 700; color: var(--primary);"><i class="fa-solid fa-book-open"></i> Mangas Tradicionales</span>
                            <i class="fa-solid fa-chevron-right" style="font-size: 11px; color: var(--text-muted);"></i>
                        </a>
                        <a href="<?php echo esc_url(home_url('/tipo/manhua/')); ?>" class="top-rank-item" style="justify-content: space-between;">
                            <span style="font-weight: 700; color: var(--warning);"><i class="fa-solid fa-dragon"></i> Manhuas Orientales</span>
                            <i class="fa-solid fa-chevron-right" style="font-size: 11px; color: var(--text-muted);"></i>
                        </a>
                        <a href="<?php echo esc_url(home_url('/tipo/fan-comic/')); ?>" class="top-rank-item" style="justify-content: space-between;">
                            <span style="font-weight: 700; color: var(--accent);"><i class="fa-solid fa-palette"></i> Fan Comics & Doujinshi</span>
                            <i class="fa-solid fa-chevron-right" style="font-size: 11px; color: var(--text-muted);"></i>
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</main>

<?php
get_footer();
