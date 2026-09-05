<?php
/**
 * LectorThema - Sistema de Filtrado y Búsqueda del Directorio
 *
 * Controla la lógica de filtrado de obras manga por múltiples criterios:
 * - Búsqueda por palabra clave (título y contenido)
 * - Filtrado por Formato / Tipo de Obra (manga_type)
 * - Filtrado por Estado de Emisión (manga_status)
 * - Filtrado por Género (manga_genre)
 * - Ordenamiento dinámico (Más recientes, Más vistos, Mejor calificados, A-Z, Z-A)
 *
 * Estándar Open Source: Seguridad estricta, sanitización profunda y compatibilidad WPCS.
 *
 * @package LectorThema
 * @subpackage Directory
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

/**
 * 1. Modifica la consulta principal de WordPress en el archivo de mangas
 *
 * Aplica los filtros recibidos por método GET preservando la paginación nativa.
 *
 * @param WP_Query $query Objeto de la consulta global de WordPress.
 * @return void
 */
function lectorthema_directory_filter_query($query) {
    // Solo modificar consultas del front-end principales en el archivo de obras o taxonomías
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    // Aplicar al archivo de CPT manga y a los archivos de taxonomías de manga
    $is_manga_archive = $query->is_post_type_archive('manga');
    $is_manga_tax = $query->is_tax(['manga_type', 'manga_status', 'manga_genre']);

    if (!$is_manga_archive && !$is_manga_tax) {
        return;
    }

    // Asegurar que siempre consulte el post_type 'manga'
    $query->set('post_type', 'manga');

    // Array para construir la consulta taxonómica (tax_query)
    $tax_query = $query->get('tax_query') ?: [];
    if (!is_array($tax_query)) {
        $tax_query = [];
    }

    // Mantener relación lógica AND entre taxonomías
    if (!empty($tax_query) && !isset($tax_query['relation'])) {
        $tax_query['relation'] = 'AND';
    }

    // -------------------------------------------------------------------------
    // A. Búsqueda por texto (Palabra clave)
    // -------------------------------------------------------------------------
    if (isset($_GET['s']) && trim($_GET['s']) !== '') {
        $search_term = sanitize_text_field(wp_unslash($_GET['s']));
        $query->set('s', $search_term);
    } elseif (isset($_GET['q']) && trim($_GET['q']) !== '') {
        // Alias de soporte para 'q'
        $search_term = sanitize_text_field(wp_unslash($_GET['q']));
        $query->set('s', $search_term);
    }

    // -------------------------------------------------------------------------
    // B. Filtro por Formato / Tipo de Obra (manga_type)
    // -------------------------------------------------------------------------
    $tipo = '';
    if (!empty($_GET['tipo'])) {
        $tipo = sanitize_key(wp_unslash($_GET['tipo']));
    } elseif (!empty($_GET['manga_type'])) {
        $tipo = sanitize_key(wp_unslash($_GET['manga_type']));
    }

    if ($tipo && $tipo !== 'todos' && $tipo !== 'all') {
        $tax_query[] = [
            'taxonomy' => 'manga_type',
            'field'    => 'slug',
            'terms'    => $tipo,
        ];
    }

    // -------------------------------------------------------------------------
    // C. Filtro por Estado de Emisión (manga_status)
    // -------------------------------------------------------------------------
    $estado = '';
    if (!empty($_GET['estado'])) {
        $estado = sanitize_key(wp_unslash($_GET['estado']));
    } elseif (!empty($_GET['manga_status'])) {
        $estado = sanitize_key(wp_unslash($_GET['manga_status']));
    }

    if ($estado && $estado !== 'todos' && $estado !== 'all') {
        $tax_query[] = [
            'taxonomy' => 'manga_status',
            'field'    => 'slug',
            'terms'    => $estado,
        ];
    }

    // -------------------------------------------------------------------------
    // D. Filtro por Género (manga_genre)
    // -------------------------------------------------------------------------
    $genero = '';
    if (!empty($_GET['genero'])) {
        $genero = sanitize_key(wp_unslash($_GET['genero']));
    } elseif (!empty($_GET['manga_genre'])) {
        $genero = sanitize_key(wp_unslash($_GET['manga_genre']));
    }

    if ($genero && $genero !== 'todos' && $genero !== 'all') {
        $tax_query[] = [
            'taxonomy' => 'manga_genre',
            'field'    => 'slug',
            'terms'    => $genero,
        ];
    }

    // Asignar tax_query si hay condiciones agregadas
    if (!empty($tax_query)) {
        if (!isset($tax_query['relation'])) {
            $tax_query['relation'] = 'AND';
        }
        $query->set('tax_query', $tax_query);
    }

    // -------------------------------------------------------------------------
    // E. Criterios de Ordenamiento (orden)
    // -------------------------------------------------------------------------
    $orden = isset($_GET['orden']) ? sanitize_key(wp_unslash($_GET['orden'])) : 'recientes';

    switch ($orden) {
        case 'populares':
        case 'vistas':
            $query->set('meta_key', '_manga_views');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
            break;

        case 'calificacion':
        case 'rating':
            $query->set('meta_key', '_manga_rating');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
            break;

        case 'az':
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            break;

        case 'za':
            $query->set('orderby', 'title');
            $query->set('order', 'DESC');
            break;

        case 'recientes':
        default:
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
            break;
    }

    // Asegurar cantidad de obras por página (24 para grillas balanceadas)
    $query->set('posts_per_page', 24);
}
add_action('pre_get_posts', 'lectorthema_directory_filter_query');

/**
 * 2. Helper: Retorna los datos de estado actual de los filtros
 *
 * Facilita la renderización de chips, inputs y valores seleccionados.
 *
 * @return array Datos sanitizados de los filtros actuales.
 */
function lectorthema_get_directory_filter_state() {
    $search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : (isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '');
    $tipo   = isset($_GET['tipo']) ? sanitize_key(wp_unslash($_GET['tipo'])) : (isset($_GET['manga_type']) ? sanitize_key(wp_unslash($_GET['manga_type'])) : '');
    $estado = isset($_GET['estado']) ? sanitize_key(wp_unslash($_GET['estado'])) : (isset($_GET['manga_status']) ? sanitize_key(wp_unslash($_GET['manga_status'])) : '');
    $genero = isset($_GET['genero']) ? sanitize_key(wp_unslash($_GET['genero'])) : (isset($_GET['manga_genre']) ? sanitize_key(wp_unslash($_GET['manga_genre'])) : '');
    $orden  = isset($_GET['orden']) ? sanitize_key(wp_unslash($_GET['orden'])) : 'recientes';

    // En páginas de taxonomía, auto-detectar el término actual si no fue pasado explícitamente
    if (is_tax('manga_type') && empty($tipo)) {
        $obj = get_queried_object();
        if ($obj && isset($obj->slug)) {
            $tipo = $obj->slug;
        }
    } elseif (is_tax('manga_status') && empty($estado)) {
        $obj = get_queried_object();
        if ($obj && isset($obj->slug)) {
            $estado = $obj->slug;
        }
    } elseif (is_tax('manga_genre') && empty($genero)) {
        $obj = get_queried_object();
        if ($obj && isset($obj->slug)) {
            $genero = $obj->slug;
        }
    }

    return [
        'search' => $search,
        'tipo'   => $tipo,
        'estado' => $estado,
        'genero' => $genero,
        'orden'  => $orden,
    ];
}

/**
 * 3. Helper: Genera la URL de base del directorio con o sin parámetros
 *
 * @param array $new_params Parámetros adicionales a mergear o sobrescribir.
 * @param array $remove_params Parámetros a remover.
 * @return string URL completa y escapada.
 */
function lectorthema_get_directory_url($new_params = [], $remove_params = []) {
    $base_url = get_post_type_archive_link('manga');
    if (!$base_url) {
        $base_url = home_url('/mangas/');
    }

    $current_params = [];
    $allowed_keys = ['s', 'tipo', 'estado', 'genero', 'orden'];

    foreach ($allowed_keys as $key) {
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            $current_params[$key] = sanitize_text_field(wp_unslash($_GET[$key]));
        }
    }

    // Merge con nuevos parámetros
    foreach ($new_params as $k => $v) {
        if ($v === '' || $v === null || $v === 'todos') {
            unset($current_params[$k]);
        } else {
            $current_params[$k] = $v;
        }
    }

    // Remover parámetros indicados
    foreach ($remove_params as $rem) {
        unset($current_params[$rem]);
    }

    // Limpiar 'paged' si se cambian filtros
    unset($current_params['paged']);

    if (empty($current_params)) {
        return esc_url($base_url);
    }

    return esc_url(add_query_arg($current_params, $base_url));
}

/**
 * 4. Helper: Construye las etiquetas de filtros activos (Chips)
 *
 * Permite al usuario ver qué filtros están aplicados y eliminarlos individualmente.
 *
 * @return array Lista de chips activos con nombre, valor y url de eliminación.
 */
function lectorthema_get_directory_active_chips() {
    $state = lectorthema_get_directory_filter_state();
    $chips = [];

    // Chip de Búsqueda
    if (!empty($state['search'])) {
        $chips[] = [
            'id'         => 'search',
            'label'      => __('Búsqueda', 'lectorthema'),
            'value'      => '“' . esc_html($state['search']) . '”',
            'remove_url' => lectorthema_get_directory_url([], ['s', 'q']),
        ];
    }

    // Chip de Formato
    if (!empty($state['tipo']) && $state['tipo'] !== 'todos') {
        $term = get_term_by('slug', $state['tipo'], 'manga_type');
        $name = $term ? $term->name : ucfirst($state['tipo']);
        $chips[] = [
            'id'         => 'tipo',
            'label'      => __('Formato', 'lectorthema'),
            'value'      => esc_html($name),
            'remove_url' => lectorthema_get_directory_url([], ['tipo', 'manga_type']),
        ];
    }

    // Chip de Estado
    if (!empty($state['estado']) && $state['estado'] !== 'todos') {
        $term = get_term_by('slug', $state['estado'], 'manga_status');
        $name = $term ? $term->name : ucfirst($state['estado']);
        $chips[] = [
            'id'         => 'estado',
            'label'      => __('Estado', 'lectorthema'),
            'value'      => esc_html($name),
            'remove_url' => lectorthema_get_directory_url([], ['estado', 'manga_status']),
        ];
    }

    // Chip de Género
    if (!empty($state['genero']) && $state['genero'] !== 'todos') {
        $term = get_term_by('slug', $state['genero'], 'manga_genre');
        $name = $term ? $term->name : ucfirst($state['genero']);
        $chips[] = [
            'id'         => 'genero',
            'label'      => __('Género', 'lectorthema'),
            'value'      => esc_html($name),
            'remove_url' => lectorthema_get_directory_url([], ['genero', 'manga_genre']),
        ];
    }

    // Chip de Orden (solo si no es el orden por defecto)
    if (!empty($state['orden']) && $state['orden'] !== 'recientes') {
        $orden_labels = [
            'populares'    => __('Más Populares', 'lectorthema'),
            'vistas'       => __('Más Vistos', 'lectorthema'),
            'calificacion' => __('Mejor Calificados', 'lectorthema'),
            'az'           => __('A - Z', 'lectorthema'),
            'za'           => __('Z - A', 'lectorthema'),
        ];
        $label = $orden_labels[$state['orden']] ?? ucfirst($state['orden']);
        $chips[] = [
            'id'         => 'orden',
            'label'      => __('Orden', 'lectorthema'),
            'value'      => esc_html($label),
            'remove_url' => lectorthema_get_directory_url([], ['orden']),
        ];
    }

    return $chips;
}

/**
 * 5. Helper: Paginación que preserva parámetros GET del filtro
 *
 * @param WP_Query|null $query Consulta opcional (usa la global si es null).
 * @return string HTML de la paginación.
 */
function lectorthema_directory_pagination($query = null) {
    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }

    $total_pages = $query->max_num_pages;
    if ($total_pages <= 1) {
        return '';
    }

    $current_page = max(1, get_query_var('paged'));

    // Conservar todos los parámetros GET activos en los enlaces de página
    $add_args = [];
    $allowed_keys = ['s', 'tipo', 'estado', 'genero', 'orden'];
    foreach ($allowed_keys as $k) {
        if (isset($_GET[$k]) && $_GET[$k] !== '') {
            $add_args[$k] = sanitize_text_field(wp_unslash($_GET[$k]));
        }
    }

    $pagination_links = paginate_links([
        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '?paged=%#%',
        'current'   => $current_page,
        'total'     => $total_pages,
        'prev_text' => '<i class="fa-solid fa-arrow-left"></i> <span class="nav-text">' . __('Anterior', 'lectorthema') . '</span>',
        'next_text' => '<span class="nav-text">' . __('Siguiente', 'lectorthema') . '</span> <i class="fa-solid fa-arrow-right"></i>',
        'type'      => 'array',
        'add_args'  => $add_args,
    ]);

    if (!empty($pagination_links)) {
        $html = '<nav class="directory-pagination" aria-label="' . esc_attr__('Navegación del directorio', 'lectorthema') . '">';
        $html .= '<ul class="pagination-list">';
        foreach ($pagination_links as $link) {
            $is_current = strpos($link, 'current') !== false;
            $html .= '<li class="pagination-item ' . ($is_current ? 'is-active' : '') . '">' . $link . '</li>';
        }
        $html .= '</ul>';
        $html .= '</nav>';
        return $html;
    }

    return '';
}
