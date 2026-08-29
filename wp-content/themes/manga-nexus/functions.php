<?php
/**
 * MangaNexus Theme Functions and Definitions
 *
 * @package MangaNexus
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('MANGA_NEXUS_VERSION', '2.4.0');
define('MANGA_NEXUS_DIR', get_template_directory());
define('MANGA_NEXUS_URI', get_template_directory_uri());

/**
 * 1. Configuración General del Tema
 */
function manga_nexus_setup() {
    // Soporte para traducción
    load_theme_textdomain('manga-nexus', MANGA_NEXUS_DIR . '/languages');

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
        'primary-menu' => __('Menú Principal', 'manga-nexus'),
        'mobile-menu'  => __('Menú Móvil', 'manga-nexus'),
        'footer-menu'  => __('Menú Pie de Página', 'manga-nexus'),
    ]);
}
add_action('after_setup_theme', 'manga_nexus_setup');

/**
 * 2. Encolado Modular de Scripts y Estilos por Vistas (Carga Óptima)
 */
function manga_nexus_enqueue_scripts() {
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
    wp_enqueue_style('manga-nexus-style', get_stylesheet_uri(), [], MANGA_NEXUS_VERSION);
    wp_enqueue_style('manga-nexus-main', MANGA_NEXUS_URI . '/assets/css/main.css', ['manga-nexus-style'], MANGA_NEXUS_VERSION);
    wp_enqueue_style('manga-nexus-components', MANGA_NEXUS_URI . '/assets/css/components.css', ['manga-nexus-main'], MANGA_NEXUS_VERSION);

    // 2. Estilos Específicos por Vista (Carga Condicional Óptima)
    if (is_front_page() || is_home()) {
        wp_enqueue_style('manga-nexus-front-page', MANGA_NEXUS_URI . '/assets/css/front-page.css', ['manga-nexus-components'], MANGA_NEXUS_VERSION);
    } elseif (is_singular('manga')) {
        wp_enqueue_style('manga-nexus-single-manga', MANGA_NEXUS_URI . '/assets/css/single-manga.css', ['manga-nexus-components'], MANGA_NEXUS_VERSION);
    } elseif (is_singular('chapter')) {
        wp_enqueue_style('manga-nexus-reader', MANGA_NEXUS_URI . '/assets/css/reader.css', ['manga-nexus-components'], MANGA_NEXUS_VERSION);
    } elseif (is_page_template('page-favoritos.php') || is_post_type_archive('manga') || is_tax('manga_type') || is_tax('manga_genre') || is_tax('manga_status')) {
        wp_enqueue_style('manga-nexus-favorites', MANGA_NEXUS_URI . '/assets/css/favorites.css', ['manga-nexus-components'], MANGA_NEXUS_VERSION);
    }

    // 3. Estilos Responsivos Dedicados (Sobrescribe media queries)
    wp_enqueue_style('manga-nexus-responsive', MANGA_NEXUS_URI . '/assets/css/responsive.css', ['manga-nexus-components'], MANGA_NEXUS_VERSION);

    // Scripts del Tema
    wp_enqueue_script('manga-nexus-slider', MANGA_NEXUS_URI . '/assets/js/slider.js', [], MANGA_NEXUS_VERSION, true);
    wp_enqueue_script('manga-nexus-favorites', MANGA_NEXUS_URI . '/assets/js/favorites.js', [], MANGA_NEXUS_VERSION, true);
    wp_enqueue_script('manga-nexus-filter', MANGA_NEXUS_URI . '/assets/js/chapter-filter.js', [], MANGA_NEXUS_VERSION, true);
    wp_enqueue_script('manga-nexus-main-js', MANGA_NEXUS_URI . '/assets/js/main.js', ['manga-nexus-slider', 'manga-nexus-favorites'], MANGA_NEXUS_VERSION, true);

    // Localización de variables para AJAX con protección CSRF Nonce
    wp_localize_script('manga-nexus-main-js', 'mangaNexusData', [
        'ajaxUrl'        => admin_url('admin-ajax.php'),
        'nonce'          => wp_create_nonce('manga_nexus_nonce'),
        'isLoggedIn'     => is_user_logged_in(),
        'currentUserId'  => get_current_user_id(),
        'siteUrl'        => home_url(),
        'strings'        => [
            'loginRequired'      => __('Debes iniciar sesión para agregar a favoritos.', 'manga-nexus'),
            'addedFavorite'      => __('Agregado a tus favoritos.', 'manga-nexus'),
            'removedFavorite'    => __('Eliminado de tus favoritos.', 'manga-nexus'),
            'errorOccurred'      => __('Ocurrió un error. Por favor intenta de nuevo.', 'manga-nexus'),
            'processing'         => __('Procesando...', 'manga-nexus'),
        ]
    ]);
}
add_action('wp_enqueue_scripts', 'manga_nexus_enqueue_scripts');

/**
 * 3. Inclusión de Módulos del Sistema
 */
require_once MANGA_NEXUS_DIR . '/inc/security-helpers.php';
require_once MANGA_NEXUS_DIR . '/inc/custom-post-types.php';
require_once MANGA_NEXUS_DIR . '/inc/taxonomies.php';
require_once MANGA_NEXUS_DIR . '/inc/custom-fields.php';
require_once MANGA_NEXUS_DIR . '/inc/favorites-system.php';
require_once MANGA_NEXUS_DIR . '/inc/views-counter.php';
require_once MANGA_NEXUS_DIR . '/inc/svg-icons.php';
require_once MANGA_NEXUS_DIR . '/inc/demo-importer.php';

/**
 * 4. Helper para formatear tiempo relativo en español (Ej: "Hace 2 Horas", "Hace 3 Días")
 */
function manga_nexus_time_ago($timestamp) {
    if (!is_numeric($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    
    $difference = current_time('timestamp') - $timestamp;
    if ($difference < 60) {
        return __('Hace unos segundos', 'manga-nexus');
    } elseif ($difference < 3600) {
        $mins = round($difference / 60);
        return sprintf(_n('Hace %d Minuto', 'Hace %d Minutos', $mins, 'manga-nexus'), $mins);
    } elseif ($difference < 86400) {
        $hours = round($difference / 3600);
        return sprintf(_n('Hace %d Hora', 'Hace %d Horas', $hours, 'manga-nexus'), $hours);
    } elseif ($difference < 604800) {
        $days = round($difference / 86400);
        return sprintf(_n('Hace %d Día', 'Hace %d Días', $days, 'manga-nexus'), $days);
    } elseif ($difference < 2592000) {
        $weeks = round($difference / 604800);
        return sprintf(_n('Hace %d Semana', 'Hace %d Semanas', $weeks, 'manga-nexus'), $weeks);
    } else {
        $months = round($difference / 2592000);
        return sprintf(_n('Hace %d Mes', 'Hace %d Meses', $months, 'manga-nexus'), $months);
    }
}

/**
 * 5. Auto-flush de reglas de reescritura de Permalinks
 */
function manga_nexus_flush_rewrites_once() {
    if (!get_option('manga_nexus_flushed_rewrites_v1')) {
        flush_rewrite_rules();
        update_option('manga_nexus_flushed_rewrites_v1', '1');
    }
}
add_action('init', 'manga_nexus_flush_rewrites_once', 99);
