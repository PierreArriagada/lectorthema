<?php
/**
 * LectorThema - Template Part: Filtro del Directorio de Obras
 *
 * Componente modular con búsqueda por texto, selector de formatos,
 * estados de emisión con dots cromáticos, géneros dinámicos y ordenamiento.
 *
 * @package LectorThema
 * @subpackage Directory
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $wp_query;

// Obtener estado actual de filtros sanitizados
$state = lectorthema_get_directory_filter_state();

// URL de destino del formulario
$form_action = get_post_type_archive_link('manga');
if (!$form_action) {
    $form_action = home_url('/mangas/');
}

// 1. Obtener Tipos/Formatos disponibles
$all_types = get_terms([
    'taxonomy'   => 'manga_type',
    'hide_empty' => false,
]);

// 2. Definir Estados Canónicos con sus indicadores visuales
$canonical_statuses = [
    'en-emision' => [
        'name' => __('En emisión', 'lectorthema'),
        'dot'  => 'dot-en-emision',
    ],
    'pausado' => [
        'name' => __('Pausado', 'lectorthema'),
        'dot'  => 'dot-pausado',
    ],
    'terminado' => [
        'name' => __('Terminado', 'lectorthema'),
        'dot'  => 'dot-terminado',
    ],
    'abandonado' => [
        'name' => __('Abandonado', 'lectorthema'),
        'dot'  => 'dot-abandonado',
    ],
];

// 3. Obtener Géneros disponibles
$all_genres = get_terms([
    'taxonomy'   => 'manga_genre',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

// 4. Opciones de Ordenamiento
$order_options = [
    'recientes'    => __('Más recientes', 'lectorthema'),
    'populares'    => __('Más vistos / Populares', 'lectorthema'),
    'calificacion' => __('Mejor calificados', 'lectorthema'),
    'az'           => __('Alfabético (A - Z)', 'lectorthema'),
    'za'           => __('Alfabético (Z - A)', 'lectorthema'),
];

// Chips activos y contador de resultados
$active_chips = lectorthema_get_directory_active_chips();
$total_results = (int) $wp_query->found_posts;
$has_active_filters = !empty($active_chips);
?>

<!-- Contenedor Principal del Formulario de Filtros -->
<section class="directory-filter-section" aria-label="<?php esc_attr_e('Filtros y Búsqueda de Obras', 'lectorthema'); ?>">
    <form method="get" action="<?php echo esc_url($form_action); ?>" id="directoryFilterForm" class="directory-filter-box">
        
        <!-- 1. Fila de Búsqueda por Texto -->
        <div class="directory-search-row">
            <div class="directory-search-input-wrap">
                <i class="fa-solid fa-magnifying-glass directory-search-icon-left" aria-hidden="true"></i>
                <input type="search" 
                       name="s" 
                       id="directorySearchInput" 
                       class="directory-search-input" 
                       placeholder="<?php esc_attr_e('Buscar por título de obra o palabra clave...', 'lectorthema'); ?>" 
                       value="<?php echo esc_attr($state['search']); ?>" 
                       autocomplete="off"
                       aria-label="<?php esc_attr_e('Buscar mangas y cómics', 'lectorthema'); ?>">
                
                <button type="button" 
                        id="btnDirectoryClear" 
                        class="btn-directory-clear" 
                        title="<?php esc_attr_e('Limpiar búsqueda', 'lectorthema'); ?>" 
                        aria-label="<?php esc_attr_e('Borrar texto de búsqueda', 'lectorthema'); ?>">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <button type="submit" class="btn-directory-submit" aria-label="<?php esc_attr_e('Ejecutar búsqueda', 'lectorthema'); ?>">
                <i class="fa-solid fa-filter"></i>
                <span><?php _e('Buscar', 'lectorthema'); ?></span>
            </button>
        </div>

        <!-- Inputs Ocultos para Mantener Estado al Interactuar con Pills -->
        <input type="hidden" name="tipo" value="<?php echo esc_attr($state['tipo']); ?>">
        <input type="hidden" name="estado" value="<?php echo esc_attr($state['estado']); ?>">

        <!-- 2. Grupos de Filtros Secundarios -->
        <div class="directory-filters-groups">
            
            <!-- Fila: Formato / Tipo de Obra -->
            <div class="directory-filter-row">
                <span class="directory-group-label">
                    <i class="fa-solid fa-book-bookmark"></i> <?php _e('Formato:', 'lectorthema'); ?>
                </span>
                <div class="directory-pills-wrap">
                    <button type="button" 
                            class="directory-pill <?php echo empty($state['tipo']) || $state['tipo'] === 'todos' ? 'is-active' : ''; ?>" 
                            data-param="tipo" 
                            data-value="">
                        <?php _e('Todos', 'lectorthema'); ?>
                    </button>

                    <?php if (!empty($all_types) && !is_wp_error($all_types)): ?>
                        <?php foreach ($all_types as $type_term): ?>
                            <button type="button" 
                                    class="directory-pill <?php echo ($state['tipo'] === $type_term->slug) ? 'is-active' : ''; ?>" 
                                    data-param="tipo" 
                                    data-value="<?php echo esc_attr($type_term->slug); ?>">
                                <?php echo esc_html($type_term->name); ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Fila: Estado de Publicación -->
            <div class="directory-filter-row">
                <span class="directory-group-label">
                    <i class="fa-solid fa-circle-notch"></i> <?php _e('Estado:', 'lectorthema'); ?>
                </span>
                <div class="directory-pills-wrap">
                    <button type="button" 
                            class="directory-pill <?php echo empty($state['estado']) || $state['estado'] === 'todos' ? 'is-active' : ''; ?>" 
                            data-param="estado" 
                            data-value="">
                        <?php _e('Todos', 'lectorthema'); ?>
                    </button>

                    <?php foreach ($canonical_statuses as $st_slug => $st_data): ?>
                        <button type="button" 
                                class="directory-pill <?php echo ($state['estado'] === $st_slug) ? 'is-active' : ''; ?>" 
                                data-param="estado" 
                                data-value="<?php echo esc_attr($st_slug); ?>">
                            <span class="directory-pill-dot <?php echo esc_attr($st_data['dot']); ?>"></span>
                            <?php echo esc_html($st_data['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Fila: Menús Desplegables para Género y Ordenamiento -->
            <div class="directory-dropdowns-row">
                
                <!-- Selector de Género -->
                <div class="directory-select-field">
                    <span class="directory-group-label">
                        <i class="fa-solid fa-tags"></i> <?php _e('Género:', 'lectorthema'); ?>
                    </span>
                    <div class="directory-select-wrap">
                        <i class="fa-solid fa-layer-group directory-select-icon" aria-hidden="true"></i>
                        <select name="genero" id="directorySelectGenre" class="directory-select" aria-label="<?php esc_attr_e('Filtrar por género', 'lectorthema'); ?>">
                            <option value=""><?php _e('Todos los géneros', 'lectorthema'); ?></option>
                            <?php if (!empty($all_genres) && !is_wp_error($all_genres)): ?>
                                <?php foreach ($all_genres as $genre_term): ?>
                                    <option value="<?php echo esc_attr($genre_term->slug); ?>" <?php selected($state['genero'], $genre_term->slug); ?>>
                                        <?php echo esc_html($genre_term->name); ?> (<?php echo (int) $genre_term->count; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <i class="fa-solid fa-chevron-down directory-select-arrow" aria-hidden="true"></i>
                    </div>
                </div>

                <!-- Selector de Ordenamiento -->
                <div class="directory-select-field">
                    <span class="directory-group-label">
                        <i class="fa-solid fa-arrow-down-short-wide"></i> <?php _e('Ordenar por:', 'lectorthema'); ?>
                    </span>
                    <div class="directory-select-wrap">
                        <i class="fa-solid fa-arrow-down-wide-short directory-select-icon" aria-hidden="true"></i>
                        <select name="orden" id="directorySelectOrder" class="directory-select" aria-label="<?php esc_attr_e('Ordenar resultados', 'lectorthema'); ?>">
                            <?php foreach ($order_options as $ord_key => $ord_label): ?>
                                <option value="<?php echo esc_attr($ord_key); ?>" <?php selected($state['orden'], $ord_key); ?>>
                                    <?php echo esc_html($ord_label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fa-solid fa-chevron-down directory-select-arrow" aria-hidden="true"></i>
                    </div>
                </div>

            </div>

        </div>
    </form>

    <!-- 3. Barra de Resumen: Contador y Chips de Filtros Activos -->
    <div class="directory-summary-bar">
        <div class="directory-summary-left">
            <span class="directory-results-count">
                <i class="fa-solid fa-layer-group"></i>
                <?php printf(
                    _n('Mostrando %s obra', 'Mostrando %s obras', $total_results, 'lectorthema'),
                    '<span class="directory-results-badge">' . number_format_i18n($total_results) . '</span>'
                ); ?>
            </span>

            <!-- Chips de Filtros Activos -->
            <?php if (!empty($active_chips)): ?>
                <div class="directory-active-chips" role="region" aria-label="<?php esc_attr_e('Filtros aplicados', 'lectorthema'); ?>">
                    <?php foreach ($active_chips as $chip): ?>
                        <a href="<?php echo esc_url($chip['remove_url']); ?>" 
                           class="directory-chip" 
                           title="<?php printf(esc_attr__('Quitar filtro %s', 'lectorthema'), esc_attr($chip['label'])); ?>">
                            <span class="directory-chip-label"><?php echo esc_html($chip['label']); ?>:</span>
                            <span class="directory-chip-val"><?php echo esc_html($chip['value']); ?></span>
                            <span class="directory-chip-remove" aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botón Restablecer Todo -->
        <?php if ($has_active_filters): ?>
            <a href="<?php echo esc_url(get_post_type_archive_link('manga') ?: home_url('/mangas/')); ?>" 
               class="directory-reset-btn" 
               title="<?php esc_attr_e('Restablecer todos los filtros aplicados', 'lectorthema'); ?>">
                <i class="fa-solid fa-rotate-left"></i>
                <span><?php _e('Limpiar filtros', 'lectorthema'); ?></span>
            </a>
        <?php endif; ?>
    </div>
</section>
