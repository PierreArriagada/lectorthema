<?php
/**
 * LectorThema Theme Functions and Definitions
 *
 * @package LectorThema
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('LECTORTHEMA_VERSION', '2.4.0');
define('LECTORTHEMA_DIR', get_template_directory());
define('LECTORTHEMA_URI', get_template_directory_uri());

/**
 * 1. Configuración General del Tema
 */
function lectorthema_setup() {
    // Soporte para traducción
    load_theme_textdomain('lectorthema', LECTORTHEMA_DIR . '/languages');

    // Soporte para etiquetas del título dinámicas
    add_theme_support('title-tag');

    // Soporte para imágenes destacadas (Portadas y Banners)
    add_theme_support('post-thumbnails');

    // Tamaños personalizados de imagen optimizados para mangas
    add_image_size('manga-poster', 400, 600, true);       // Portada vertical principal
    add_image_size('manga-poster-sm', 220, 320, true);    // Portada en miniatura (cards/tops)
    add_image_size('manga-banner', 1400, 500, true);      // Banner panorámico de fondo
    add_image_size('manga-slider', 1200, 650, true);      // Portada en slider principal

    // Soporte para HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Registro de Menús de Navegación
    register_nav_menus([
        'primary-menu' => __('Menú Principal', 'lectorthema'),
        'mobile-menu'  => __('Menú Móvil', 'lectorthema'),
        'footer-menu'  => __('Menú Pie de Página', 'lectorthema'),
    ]);
}
add_action('after_setup_theme', 'lectorthema_setup');

/**
 * 2. Encolado Modular de Scripts y Estilos por Vistas (Carga Óptima)
 */
function lectorthema_enqueue_scripts() {
    // Google Fonts (Outfit & Inter)
    wp_enqueue_style(
        'manga-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap',
        [],
        null
    );

    // FontAwesome 6 Free para iconos
    wp_enqueue_style(
        'font-awesome-6',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
        [],
        '6.4.2'
    );

    // 1. Estilos Globales (Base, Variables y Componentes Compartidos)
    wp_enqueue_style('lectorthema-style', get_stylesheet_uri(), [], LECTORTHEMA_VERSION);
    wp_enqueue_style('lectorthema-main', LECTORTHEMA_URI . '/assets/css/main.css', ['lectorthema-style'], LECTORTHEMA_VERSION);
    wp_enqueue_style('lectorthema-components', LECTORTHEMA_URI . '/assets/css/components.css', ['lectorthema-main'], LECTORTHEMA_VERSION);

    // 2. Estilos Específicos por Vista (Carga Condicional Óptima)
    if (is_front_page() || is_home()) {
        wp_enqueue_style('lectorthema-front-page', LECTORTHEMA_URI . '/assets/css/front-page.css', ['lectorthema-components'], LECTORTHEMA_VERSION);
    } elseif (is_singular('manga')) {
        wp_enqueue_style('lectorthema-single-manga', LECTORTHEMA_URI . '/assets/css/single-manga.css', ['lectorthema-components'], LECTORTHEMA_VERSION);
    } elseif (is_singular('chapter')) {
        wp_enqueue_style('lectorthema-reader', LECTORTHEMA_URI . '/assets/css/reader.css', ['lectorthema-components'], LECTORTHEMA_VERSION);
    } elseif (is_page_template('page-favoritos.php')) {
        wp_enqueue_style('lectorthema-favorites', LECTORTHEMA_URI . '/assets/css/favorites.css', ['lectorthema-components'], LECTORTHEMA_VERSION);
    } elseif (is_post_type_archive('manga') || is_tax(['manga_type', 'manga_genre', 'manga_status'])) {
        wp_enqueue_style('lectorthema-directory', LECTORTHEMA_URI . '/assets/css/directory.css', ['lectorthema-components'], LECTORTHEMA_VERSION);
    }

    // 3. Estilos Responsivos Dedicados (Sobrescribe media queries)
    wp_enqueue_style('lectorthema-responsive', LECTORTHEMA_URI . '/assets/css/responsive.css', ['lectorthema-components'], LECTORTHEMA_VERSION);

    // Scripts del Tema
    wp_enqueue_script('lectorthema-slider', LECTORTHEMA_URI . '/assets/js/slider.js', [], LECTORTHEMA_VERSION, true);
    wp_enqueue_script('lectorthema-favorites', LECTORTHEMA_URI . '/assets/js/favorites.js', [], LECTORTHEMA_VERSION, true);
    wp_enqueue_script('lectorthema-filter', LECTORTHEMA_URI . '/assets/js/chapter-filter.js', [], LECTORTHEMA_VERSION, true);
    wp_enqueue_script('lectorthema-comments-realtime', LECTORTHEMA_URI . '/assets/js/comments-realtime.js', [], LECTORTHEMA_VERSION, true);
    wp_enqueue_script('lectorthema-notifications', LECTORTHEMA_URI . '/assets/js/notifications.js', [], LECTORTHEMA_VERSION, true);

    // Script interactivo exclusivo para el directorio y taxonomías de catálogo
    if (is_post_type_archive('manga') || is_tax(['manga_type', 'manga_genre', 'manga_status'])) {
        wp_enqueue_script('lectorthema-directory-js', LECTORTHEMA_URI . '/assets/js/directory.js', [], LECTORTHEMA_VERSION, true);
    }

    wp_enqueue_script('lectorthema-main-js', LECTORTHEMA_URI . '/assets/js/main.js', ['lectorthema-slider', 'lectorthema-favorites', 'lectorthema-comments-realtime', 'lectorthema-notifications'], LECTORTHEMA_VERSION, true);

    // Localización de variables para AJAX con protección CSRF Nonce
    wp_localize_script('lectorthema-main-js', 'lectorThemaData', [
        'ajaxUrl'        => admin_url('admin-ajax.php'),
        'nonce'          => wp_create_nonce('lectorthema_nonce'),
        'isLoggedIn'     => is_user_logged_in(),
        'currentUserId'  => get_current_user_id(),
        'siteUrl'        => home_url(),
        'strings'        => [
            'loginRequired'      => __('Debes iniciar sesión para agregar a favoritos.', 'lectorthema'),
            'addedFavorite'      => __('Agregado a tus favoritos.', 'lectorthema'),
            'removedFavorite'    => __('Eliminado de tus favoritos.', 'lectorthema'),
            'errorOccurred'      => __('Ocurrió un error. Por favor intenta de nuevo.', 'lectorthema'),
            'processing'         => __('Procesando...', 'lectorthema'),
        ]
    ]);
}
add_action('wp_enqueue_scripts', 'lectorthema_enqueue_scripts');

/**
 * 3. Inclusión de Módulos del Sistema
 */
require_once LECTORTHEMA_DIR . '/inc/security-helpers.php';
require_once LECTORTHEMA_DIR . '/inc/custom-post-types.php';
require_once LECTORTHEMA_DIR . '/inc/taxonomies.php';
require_once LECTORTHEMA_DIR . '/inc/custom-fields.php';
require_once LECTORTHEMA_DIR . '/inc/directory-system.php';
require_once LECTORTHEMA_DIR . '/inc/favorites-system.php';
require_once LECTORTHEMA_DIR . '/inc/comments-system.php';
require_once LECTORTHEMA_DIR . '/inc/views-counter.php';
require_once LECTORTHEMA_DIR . '/inc/svg-icons.php';
require_once LECTORTHEMA_DIR . '/inc/demo-importer.php';

/**
 * 4. Helper para formatear tiempo relativo en español (Ej: "Hace 2 Horas", "Hace 3 Días")
 */
function lectorthema_time_ago($timestamp) {
    if (!is_numeric($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    
    $difference = current_time('timestamp') - $timestamp;
    if ($difference < 60) {
        return __('Hace unos segundos', 'lectorthema');
    } elseif ($difference < 3600) {
        $mins = round($difference / 60);
        return sprintf(_n('Hace %d Minuto', 'Hace %d Minutos', $mins, 'lectorthema'), $mins);
    } elseif ($difference < 86400) {
        $hours = round($difference / 3600);
        return sprintf(_n('Hace %d Hora', 'Hace %d Horas', $hours, 'lectorthema'), $hours);
    } elseif ($difference < 604800) {
        $days = round($difference / 86400);
        return sprintf(_n('Hace %d Día', 'Hace %d Días', $days, 'lectorthema'), $days);
    } elseif ($difference < 2592000) {
        $weeks = round($difference / 604800);
        return sprintf(_n('Hace %d Semana', 'Hace %d Semanas', $weeks, 'lectorthema'), $weeks);
    } else {
        $months = round($difference / 2592000);
        return sprintf(_n('Hace %d Mes', 'Hace %d Meses', $months, 'lectorthema'), $months);
    }
}

/**
 * 5. Auto-flush de reglas de reescritura de Permalinks
 */
function lectorthema_flush_rewrites_once() {
    if (!get_option('lectorthema_flushed_rewrites_v1')) {
        flush_rewrite_rules();
        update_option('lectorthema_flushed_rewrites_v1', '1');
    }
}
add_action('init', 'lectorthema_flush_rewrites_once', 99);
