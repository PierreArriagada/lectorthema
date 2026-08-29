<?php
/**
 * LectorThema - Single Manga Template (Ficha de Información de la Obra)
 *
 * 100% en Español, diseño optimizado para PC, Tablet y Móvil con Iconos SVG:
 * - PC: Layout a 2 columnas sin espacios perdidos, sidebar con detalles y recomendaciones, CTAs en el hero.
 * - Móvil: Barra de acción fija inferior, sinopsis expandible (...más / ...menos), grilla 3x3 de capítulos (Cap. 244).
 * - Sincronización completa con Favoritos, Vistas, Comentarios y Lector.
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()): the_post();
    $manga_id = get_the_ID();

    // Registrar vista
    lectorthema_record_view($manga_id);

    // Metadatos
    $author       = get_post_meta($manga_id, '_manga_author', true) ?: 'Desconocido';
    $artist       = get_post_meta($manga_id, '_manga_artist', true) ?: 'Desconocido';
    $release_year = get_post_meta($manga_id, '_manga_release_year', true) ?: '2024';
    $rating_num   = (float) (get_post_meta($manga_id, '_manga_rating', true) ?: '9.8');
    $stars_active = round($rating_num / 2); // Convertir escala 10 a 5 estrellas

    $banner_url   = get_post_meta($manga_id, '_manga_banner_url', true);
    if (!$banner_url) {
        $banner_url = get_the_post_thumbnail_url($manga_id, 'manga-banner') ?: 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1400&auto=format&fit=crop&q=80';
    }

    $cover_url = get_the_post_thumbnail_url($manga_id, 'manga-poster');
    if (!$cover_url) {
        $custom_cover = get_post_meta($manga_id, '_manga_custom_cover', true);
        $cover_url = $custom_cover ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=600&auto=format&fit=crop&q=80';
    }

    // Taxonomías
    $types = get_the_terms($manga_id, 'manga_type');
    $type_name = !empty($types) && !is_wp_error($types) ? $types[0]->name : 'Manga';
    $type_slug = !empty($types) && !is_wp_error($types) ? $types[0]->slug : 'manga';

    $genres = get_the_terms($manga_id, 'manga_genre');

    $statuses = get_the_terms($manga_id, 'manga_status');
    $status_name = !empty($statuses) && !is_wp_error($statuses) ? $statuses[0]->name : __('En emisión', 'lectorthema');
    $is_ongoing = strtolower($status_name) !== 'finalizado';

    // Vistas y Favoritos
    $total_views = lectorthema_get_total_views($manga_id);
    $views_fmt   = lectorthema_format_views($total_views);
    $is_fav      = lectorthema_is_favorite($manga_id);
    $favs_count  = lectorthema_get_favorites_count($manga_id);

    // Consulta de todos los capítulos (Orden DESC por defecto)
    $chapters_query = new WP_Query([
        'post_type'      => 'chapter',
        'posts_per_page' => -1,
        'meta_key'       => '_chapter_manga_id',
        'meta_value'     => $manga_id,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    $all_chapters = $chapters_query->posts;
    $total_chapters = count($all_chapters);

    // Primer y Último capítulo para botones de acción rápida
    $latest_chapter_url = '#';
    $latest_chapter_num = '1';
    $first_chapter_url  = '#';
    $first_chapter_num  = '1';

    if (!empty($all_chapters)) {
        // Último capítulo (el más reciente, índice 0 en orden DESC)
        $latest_chap = $all_chapters[0];
        $latest_chapter_url = get_permalink($latest_chap->ID);
        $latest_chapter_num = get_post_meta($latest_chap->ID, '_chapter_number', true) ?: '1';

        // Primer capítulo (el más antiguo al final del array)
        $oldest_chap = end($all_chapters);
        $first_chapter_url = get_permalink($oldest_chap->ID);
        $first_chapter_num = get_post_meta($oldest_chap->ID, '_chapter_number', true) ?: '1';
    }

    // Última actualización en formato legible en español
    $last_update_str = '';
    if (!empty($all_chapters[0])) {
        $last_update_str = lectorthema_time_ago(get_the_time('U', $all_chapters[0]->ID));
    } else {
        $last_update_str = lectorthema_time_ago(get_the_time('U'));
    }

    // Mangas Recomendados (Mismo género o tipo)
    $recommended_mangas = [];
    if (!empty($genres) && !is_wp_error($genres)) {
        $first_genre = $genres[0]->slug;
        $recommended_mangas = lectorthema_get_top_by_genre($first_genre, 4);
    }
?>

<div class="manga-info-page-wrapper">
    <!-- 1. Cabecera Hero con Fondo Difuminado e Iconos SVG -->
    <div class="manga-info-hero-header">
        <div class="manga-info-backdrop" style="background-image: url('<?php echo esc_url($banner_url); ?>');"></div>
        <div class="manga-info-backdrop-overlay"></div>

        <div class="nexus-container manga-info-hero-inner">
            <!-- Barra Superior de Navegación Rápida -->
            <div class="manga-info-top-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="manga-info-back-btn" title="<?php esc_attr_e('Volver al Inicio', 'lectorthema'); ?>" aria-label="<?php esc_attr_e('Volver', 'lectorthema'); ?>">
                    <?php echo lectorthema_svg('chevron-left', 'svg-icon', 18); ?>
                </a>
                <div class="manga-info-top-right-tools">
                    <button type="button" class="manga-info-tool-btn btn-open-auth" title="<?php esc_attr_e('Información / Reporte', 'lectorthema'); ?>" aria-label="<?php esc_attr_e('Info', 'lectorthema'); ?>">
                        <?php echo lectorthema_svg('info', 'svg-icon', 17); ?>
                    </button>
                    <button type="button" class="manga-info-tool-btn" id="mangaShareBtn" title="<?php esc_attr_e('Compartir Obra', 'lectorthema'); ?>" aria-label="<?php esc_attr_e('Compartir', 'lectorthema'); ?>">
                        <?php echo lectorthema_svg('share', 'svg-icon', 17); ?>
                    </button>
                </div>
            </div>

            <!-- Ficha Principal: Portada + Título + Estrellas + CTAs de Escritorio -->
            <div class="manga-info-main-card">
                <div class="manga-info-poster-box">
                    <img src="<?php echo esc_url($cover_url); ?>" alt="<?php the_title_attribute(); ?>" class="manga-info-poster-img">
                </div>

                <div class="manga-info-header-meta">
                    <div class="manga-info-badge-row-pc">
                        <span class="manga-type-pill type-<?php echo esc_attr($type_slug); ?>">
                            <?php echo esc_html($type_name); ?>
                        </span>
                        <span class="manga-status-badge <?php echo $is_ongoing ? 'is-ongoing' : 'is-completed'; ?>">
                            <span class="status-pulse-dot"></span> <?php echo esc_html($status_name); ?>
                        </span>
                    </div>

                    <h1 class="manga-info-title"><?php the_title(); ?></h1>

                    <!-- Estrellas SVG y Calificación Numérica -->
                    <div class="manga-info-stars-rating">
                        <div class="stars-icons">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <?php echo $s <= $stars_active ? lectorthema_svg('star', 'star-active', 17) : lectorthema_svg('star-outline', 'star-empty', 17); ?>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-number-text"><?php echo number_format($rating_num, 1); ?></span>
                        <span class="rating-max-label">/ 10</span>
                    </div>

                    <!-- Última Actualización y Vistas en Español -->
                    <div class="manga-info-last-update">
                        <span class="meta-label-tag"><?php _e('Última actualización:', 'lectorthema'); ?></span>
                        <span class="meta-val-tag"><?php echo esc_html($last_update_str); ?></span>
                        <span class="pc-meta-divider">•</span>
                        <span class="pc-meta-views"><?php echo lectorthema_svg('eye', 'svg-meta', 14); ?> <?php echo esc_html($views_fmt); ?> <?php _e('vistas', 'lectorthema'); ?></span>
                        <span class="pc-meta-divider">•</span>
                        <span class="pc-meta-favs"><?php echo lectorthema_svg('bookmark', 'svg-meta', 14); ?> <span class="fav-count-<?php echo esc_attr($manga_id); ?>"><?php echo esc_html($favs_count); ?></span> <?php _e('seguidores', 'lectorthema'); ?></span>
                    </div>

                    <!-- CTAs de Cabecera para PC -->
                    <div class="manga-info-hero-actions-pc">
                        <a href="<?php echo esc_url($first_chapter_url); ?>" class="btn-hero-read-primary" title="<?php printf(esc_attr__('Leer Primer Capítulo %s', 'lectorthema'), esc_attr($first_chapter_num)); ?>">
                            <?php echo lectorthema_svg('book-reader', 'svg-icon-cta', 17); ?>
                            <span><?php printf(__('Primer Cap. %s', 'lectorthema'), esc_html($first_chapter_num)); ?></span>
                        </a>

                        <?php if ($total_chapters > 1): ?>
                            <a href="<?php echo esc_url($latest_chapter_url); ?>" class="btn-hero-read-secondary" title="<?php printf(esc_attr__('Leer Último Capítulo %s', 'lectorthema'), esc_attr($latest_chapter_num)); ?>">
                                <?php echo lectorthema_svg('zap', 'svg-icon-cta', 16); ?>
                                <span><?php printf(__('Último Cap. %s', 'lectorthema'), esc_html($latest_chapter_num)); ?></span>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn-hero-fav-action btn-toggle-favorite <?php echo $is_fav ? 'is-active' : ''; ?>" data-manga-id="<?php echo esc_attr($manga_id); ?>">
                            <?php echo $is_fav ? lectorthema_svg('star', 'svg-star', 17) : lectorthema_svg('star-outline', 'svg-star', 17); ?>
                            <span><?php echo $is_fav ? __('En Favoritos', 'lectorthema') : __('Agregar a Favoritos', 'lectorthema'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Cuerpo Principal de Información y Capítulos -->
    <div class="nexus-container manga-info-body-container">
        <div class="manga-info-layout-grid">
            
            <!-- Columna Principal (Izquierda en PC) -->
            <div class="manga-info-primary-column">
                
                <!-- Tarjeta de Metadatos de la Obra: Géneros y Autores -->
                <div class="manga-info-meta-card">
                    <!-- Fila de Etiquetas / Géneros -->
                    <div class="manga-info-tags-row">
                        <span class="tags-label"><?php _e('Géneros:', 'lectorthema'); ?></span>
                        <div class="tags-pills-list">
                            <span class="tag-pill-btn tag-format type-<?php echo esc_attr($type_slug); ?>">
                                <?php echo esc_html($type_name); ?>
                            </span>
                            <?php if (!empty($genres) && !is_wp_error($genres)): ?>
                                <?php foreach ($genres as $g): ?>
                                    <a href="<?php echo esc_url(get_term_link($g)); ?>" class="tag-pill-btn">
                                        <?php echo esc_html($g->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Fila de Autor y Artista -->
                    <div class="manga-info-author-row">
                        <div class="author-item-wrap">
                            <span class="author-label"><?php _e('Autor:', 'lectorthema'); ?></span>
                            <span class="author-name-accent"><?php echo esc_html($author); ?></span>
                        </div>
                        <?php if ($artist && $artist !== $author && $artist !== 'Desconocido'): ?>
                            <div class="author-item-wrap">
                                <span class="author-label"><?php _e('Artista:', 'lectorthema'); ?></span>
                                <span class="author-name-accent"><?php echo esc_html($artist); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sinopsis con Expandible "...más / ...menos" -->
                <div class="manga-info-synopsis-block" id="mangaSynopsisBlock">
                    <div class="synopsis-header-title">
                        <span class="synopsis-label"><?php _e('Sinopsis', 'lectorthema'); ?></span>
                    </div>
                    <div class="synopsis-text-content" id="synopsisTextContent">
                        <?php the_content(); ?>
                    </div>
                    <button type="button" class="btn-toggle-synopsis" id="btnToggleSynopsis">
                        <span class="more-text">...más</span>
                        <span class="less-text" style="display: none;">...menos</span>
                    </button>
                </div>

                <!-- 3. Pestañas: Capítulos y Comentarios -->
                <div class="manga-info-tabs-nav">
                    <button type="button" class="info-tab-btn active" data-tab="chapters" id="tabBtnChapters">
                        <?php echo lectorthema_svg('book-reader', 'tab-svg-icon', 15); ?>
                        <span><?php _e('Capítulos', 'lectorthema'); ?></span>
                        <span class="tab-badge-count">(<?php echo esc_html($total_chapters); ?>)</span>
                    </button>
                    <button type="button" class="info-tab-btn" data-tab="comments" id="tabBtnComments">
                        <?php echo lectorthema_svg('info', 'tab-svg-icon', 15); ?>
                        <span><?php _e('Comentarios', 'lectorthema'); ?></span>
                        <span class="tab-badge-count">(<?php echo get_comments_number($manga_id); ?>)</span>
                    </button>
                </div>

                <!-- Panel de Pestaña: CAPÍTULOS -->
                <div class="manga-tab-content active" id="tabContentChapters">
                    <!-- Barra de Herramientas de Capítulos: Estado/Conteo y Ordenar ⇅ -->
                    <div class="chapters-toolbar-row">
                        <div class="chapters-status-count">
                            <span class="status-state-text <?php echo $is_ongoing ? 'is-ongoing' : 'is-completed'; ?>">
                                <?php echo $is_ongoing ? __('En emisión', 'lectorthema') : __('Finalizado', 'lectorthema'); ?>
                            </span>
                            <span class="status-count-num">(<?php echo esc_html($total_chapters); ?>)</span>
                        </div>

                        <div class="chapters-toolbar-right">
                            <!-- Buscador Rápido de Capítulos -->
                            <div class="chapters-mini-search">
                                <?php echo lectorthema_svg('search', 'svg-search', 13); ?>
                                <input type="search" id="quickChapterSearch" placeholder="<?php esc_attr_e('Buscar cap...', 'lectorthema'); ?>" autocomplete="off">
                            </div>

                            <!-- Botón de Ordenar Asc/Desc -->
                            <button type="button" class="btn-sort-order" id="btnSortChapters" data-order="desc">
                                <span><?php _e('Ordenar', 'lectorthema'); ?></span>
                                <span class="sort-icon-wrap" id="sortOrderIconWrap">
                                    <?php echo lectorthema_svg('sort', 'svg-sort', 14); ?>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Cuadrícula de Capítulos con Fecha de Subida -->
                    <div class="chapters-grid-container" id="chaptersGridContainer">
                        <?php if (!empty($all_chapters)): ?>
                            <?php foreach ($all_chapters as $idx => $chap):
                                $ch_num = get_post_meta($chap->ID, '_chapter_number', true) ?: '1';
                                $ch_title = get_post_meta($chap->ID, '_chapter_subtitle', true);
                                $is_latest = ($idx === 0);
                                $ch_time_ago = lectorthema_time_ago(get_the_time('U', $chap->ID));
                            ?>
                                <a href="<?php echo esc_url(get_permalink($chap->ID)); ?>" 
                                   class="chapter-grid-btn <?php echo $is_latest ? 'is-latest-badge' : ''; ?>" 
                                   data-chapter-num="<?php echo esc_attr($ch_num); ?>"
                                   title="<?php echo esc_attr($ch_title ? sprintf(__('Capítulo %s: %s', 'lectorthema'), $ch_num, $ch_title) : sprintf(__('Capítulo %s', 'lectorthema'), $ch_num)); ?>">
                                    <div class="ch-btn-top">
                                        <span class="ch-text-label"><?php printf(__('Cap. %s', 'lectorthema'), esc_html($ch_num)); ?></span>
                                        <?php if ($is_latest): ?>
                                            <span class="ch-new-tag"><?php esc_html_e('Nuevo', 'lectorthema'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ch-btn-bottom">
                                        <span class="ch-date-text"><?php echo esc_html($ch_time_ago); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="chapters-empty-message">
                                <?php echo lectorthema_svg('book-reader', 'svg-empty-icon', 36); ?>
                                <p><?php _e('Aún no hay capítulos disponibles para esta obra.', 'lectorthema'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Panel de Pestaña: COMENTARIOS -->
                <div class="manga-tab-content" id="tabContentComments" style="display: none;">
                    <div class="manga-comments-container">
                        <?php
                        if (comments_open() || get_comments_number()) {
                            comments_template();
                        } else {
                            echo '<p style="text-align: center; color: var(--text-muted); padding: 30px;">' . __('Los comentarios están cerrados para esta obra.', 'lectorthema') . '</p>';
                        }
                        ?>
                    </div>
                </div>

            </div>

            <!-- Columna Lateral (Sidebar en PC / Tablet Grande) -->
            <div class="manga-info-sidebar-column">
                
                <!-- Caja de Información Técnica -->
                <div class="manga-sidebar-card">
                    <h3 class="manga-sidebar-title">
                        <?php echo lectorthema_svg('info', 'svg-sidebar-icon', 16); ?> <?php _e('Detalles de la Obra', 'lectorthema'); ?>
                    </h3>
                    <ul class="manga-details-list">
                        <li>
                            <span class="detail-name"><?php _e('Tipo de Obra:', 'lectorthema'); ?></span>
                            <span class="detail-val"><?php echo esc_html($type_name); ?></span>
                        </li>
                        <li>
                            <span class="detail-name"><?php _e('Estado:', 'lectorthema'); ?></span>
                            <span class="detail-val" style="color: var(--success); font-weight: 700;"><?php echo esc_html($status_name); ?></span>
                        </li>
                        <li>
                            <span class="detail-name"><?php _e('Lanzamiento:', 'lectorthema'); ?></span>
                            <span class="detail-val"><?php echo esc_html($release_year); ?></span>
                        </li>
                        <li>
                            <span class="detail-name"><?php _e('Vistas Totales:', 'lectorthema'); ?></span>
                            <span class="detail-val"><?php echo esc_html($views_fmt); ?></span>
                        </li>
                        <li>
                            <span class="detail-name"><?php _e('Seguidores:', 'lectorthema'); ?></span>
                            <span class="detail-val"><?php echo esc_html($favs_count); ?></span>
                        </li>
                    </ul>
                </div>

                <!-- Caja de Mangas Recomendados -->
                <?php if (!empty($recommended_mangas)): ?>
                    <div class="manga-sidebar-card" style="margin-top: 20px;">
                        <h3 class="manga-sidebar-title">
                            <?php echo lectorthema_svg('fire', 'svg-sidebar-icon', 16); ?> <?php _e('Obras Relacionadas', 'lectorthema'); ?>
                        </h3>
                        <div class="manga-sidebar-recomms-list">
                            <?php foreach ($recommended_mangas as $rm):
                                if ($rm->ID === $manga_id) continue;
                                $r_cover = get_the_post_thumbnail_url($rm->ID, 'manga-poster-sm') ?: get_post_meta($rm->ID, '_manga_custom_cover', true);
                                $r_rating = get_post_meta($rm->ID, '_manga_rating', true) ?: '9.8';
                            ?>
                                <a href="<?php echo esc_url(get_permalink($rm->ID)); ?>" class="sidebar-recomm-item">
                                    <div class="recomm-thumb">
                                        <img src="<?php echo esc_url($r_cover); ?>" alt="<?php echo esc_attr($rm->post_title); ?>" loading="lazy">
                                    </div>
                                    <div class="recomm-info">
                                        <h4 class="recomm-title"><?php echo esc_html($rm->post_title); ?></h4>
                                        <span class="recomm-rating"><?php echo lectorthema_svg('star', 'svg-star-mini', 12); ?> <?php echo esc_html($r_rating); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>

    <!-- 4. Barra de Acción Inferior Fija para Móviles (Sticky Bottom Bar) -->
    <div class="manga-bottom-action-bar" id="mangaBottomActionBar">
        <div class="nexus-container bottom-action-inner">
            <!-- Botón Favoritos / Bookmark (★) -->
            <button type="button" 
                    class="btn-bottom-icon btn-toggle-favorite <?php echo $is_fav ? 'is-active' : ''; ?>" 
                    data-manga-id="<?php echo esc_attr($manga_id); ?>"
                    title="<?php echo $is_fav ? esc_attr__('En Favoritos', 'lectorthema') : esc_attr__('Guardar en Favoritos', 'lectorthema'); ?>"
                    aria-label="<?php esc_attr_e('Favoritos', 'lectorthema'); ?>">
                <?php echo $is_fav ? lectorthema_svg('star', 'svg-star-bottom', 18) : lectorthema_svg('star-outline', 'svg-star-bottom', 18); ?>
            </button>

            <!-- Botón Compartir (↑) -->
            <button type="button" 
                    class="btn-bottom-icon" 
                    id="btnBottomShare"
                    title="<?php esc_attr_e('Compartir manga', 'lectorthema'); ?>"
                    aria-label="<?php esc_attr_e('Compartir', 'lectorthema'); ?>">
                <?php echo lectorthema_svg('share', 'svg-share-bottom', 17); ?>
            </button>

            <!-- Botón Primer Capítulo -->
            <a href="<?php echo esc_url($first_chapter_url); ?>" class="btn-bottom-cta-first" title="<?php printf(esc_attr__('Leer Primer Capítulo %s', 'lectorthema'), esc_attr($first_chapter_num)); ?>">
                <?php echo lectorthema_svg('book-reader', 'svg-bottom-cta-icon', 15); ?>
                <span><?php printf(__('Cap. %s', 'lectorthema'), esc_html($first_chapter_num)); ?></span>
            </a>

            <!-- Botón Último Capítulo -->
            <a href="<?php echo esc_url($latest_chapter_url); ?>" class="btn-bottom-cta-latest" title="<?php printf(esc_attr__('Leer Último Capítulo %s', 'lectorthema'), esc_attr($latest_chapter_num)); ?>">
                <?php echo lectorthema_svg('zap', 'svg-bottom-cta-icon', 15); ?>
                <span><?php printf(__('Último Cap. %s', 'lectorthema'), esc_html($latest_chapter_num)); ?></span>
            </a>
        </div>
    </div>
</div>

<!-- Scripts interactivos de la ficha -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Alternar Sinopsis (...más / ...menos)
    const btnToggleSynopsis = document.getElementById('btnToggleSynopsis');
    const synopsisText = document.getElementById('synopsisTextContent');
    if (btnToggleSynopsis && synopsisText) {
        btnToggleSynopsis.addEventListener('click', () => {
            synopsisText.classList.toggle('expanded');
            const isExpanded = synopsisText.classList.contains('expanded');
            btnToggleSynopsis.querySelector('.more-text').style.display = isExpanded ? 'none' : 'inline';
            btnToggleSynopsis.querySelector('.less-text').style.display = isExpanded ? 'inline' : 'none';
        });
    }

    // 2. Pestañas de Capítulos vs Comentarios
    const tabBtnChapters = document.getElementById('tabBtnChapters');
    const tabBtnComments = document.getElementById('tabBtnComments');
    const tabContentChapters = document.getElementById('tabContentChapters');
    const tabContentComments = document.getElementById('tabContentComments');

    if (tabBtnChapters && tabBtnComments) {
        tabBtnChapters.addEventListener('click', () => {
            tabBtnChapters.classList.add('active');
            tabBtnComments.classList.remove('active');
            tabContentChapters.style.display = 'block';
            tabContentComments.style.display = 'none';
        });

        tabBtnComments.addEventListener('click', () => {
            tabBtnComments.classList.add('active');
            tabBtnChapters.classList.remove('active');
            tabContentChapters.style.display = 'none';
            tabContentComments.style.display = 'block';
        });
    }

    // 3. Orden de Capítulos (Ascendente / Descendente)
    const btnSortChapters = document.getElementById('btnSortChapters');
    const gridContainer = document.getElementById('chaptersGridContainer');

    if (btnSortChapters && gridContainer) {
        btnSortChapters.addEventListener('click', () => {
            const currentOrder = btnSortChapters.getAttribute('data-order');
            const chapterBtns = Array.from(gridContainer.querySelectorAll('.chapter-grid-btn'));
            chapterBtns.reverse();
            
            gridContainer.innerHTML = '';
            chapterBtns.forEach(btn => gridContainer.appendChild(btn));

            btnSortChapters.setAttribute('data-order', currentOrder === 'desc' ? 'asc' : 'desc');
        });
    }

    // 4. Buscador Rápido de Capítulos en Cuadrícula
    const quickSearch = document.getElementById('quickChapterSearch');
    if (quickSearch && gridContainer) {
        quickSearch.addEventListener('input', (e) => {
            const query = e.target.value.trim().toLowerCase();
            const chapterBtns = gridContainer.querySelectorAll('.chapter-grid-btn');
            chapterBtns.forEach(btn => {
                const num = (btn.getAttribute('data-chapter-num') || '').toLowerCase();
                const text = btn.textContent.toLowerCase();
                if (num.includes(query) || text.includes(query)) {
                    btn.style.display = 'inline-flex';
                } else {
                    btn.style.display = 'none';
                }
            });
        });
    }

    // 5. Botón Compartir
    const shareBtns = document.querySelectorAll('#mangaShareBtn, #btnBottomShare');
    shareBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Enlace copiado al portapapeles.');
            }
        });
    });
});
</script>

<?php
endwhile;

get_footer();
