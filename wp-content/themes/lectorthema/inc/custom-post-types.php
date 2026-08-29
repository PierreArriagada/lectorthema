<?php
/**
 * LectorThema - Custom Post Types
 *
 * Registra los CPTs 'manga' (obras) y 'chapter' (capítulos)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

function lectorthema_register_post_types() {
    // 1. Custom Post Type: MANGA / SERIE
    $manga_labels = [
        'name'                  => _x('Mangas y Series', 'Post type general name', 'lectorthema'),
        'singular_name'         => _x('Manga / Obra', 'Post type singular name', 'lectorthema'),
        'menu_name'             => _x('Mangas & Cómics', 'Admin Menu text', 'lectorthema'),
        'name_admin_bar'        => _x('Manga', 'Add New on Toolbar', 'lectorthema'),
        'add_new'               => __('Añadir Nueva Obra', 'lectorthema'),
        'add_new_item'          => __('Añadir Nueva Obra / Manga', 'lectorthema'),
        'new_item'              => __('Nueva Obra', 'lectorthema'),
        'edit_item'             => __('Editar Obra', 'lectorthema'),
        'view_item'             => __('Ver Obra', 'lectorthema'),
        'all_items'             => __('Todas las Obras', 'lectorthema'),
        'search_items'          => __('Buscar Mangas', 'lectorthema'),
        'not_found'             => __('No se encontraron obras.', 'lectorthema'),
        'not_found_in_trash'    => __('No hay obras en la papelera.', 'lectorthema'),
    ];

    $manga_args = [
        'labels'             => $manga_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'manga', 'with_front' => false],
        'capability_type'    => 'post',
        'has_archive'        => 'mangas',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields'],
        'show_in_rest'       => true,
    ];

    register_post_type('manga', $manga_args);

    // 2. Custom Post Type: CAPÍTULO
    $chapter_labels = [
        'name'                  => _x('Capítulos', 'Post type general name', 'lectorthema'),
        'singular_name'         => _x('Capítulo', 'Post type singular name', 'lectorthema'),
        'menu_name'             => _x('Capítulos', 'Admin Menu text', 'lectorthema'),
        'name_admin_bar'        => _x('Capítulo', 'Add New on Toolbar', 'lectorthema'),
        'add_new'               => __('Añadir Nuevo Capítulo', 'lectorthema'),
        'add_new_item'          => __('Añadir Nuevo Capítulo', 'lectorthema'),
        'new_item'              => __('Nuevo Capítulo', 'lectorthema'),
        'edit_item'             => __('Editar Capítulo', 'lectorthema'),
        'view_item'             => __('Ver Capítulo', 'lectorthema'),
        'all_items'             => __('Todos los Capítulos', 'lectorthema'),
        'search_items'          => __('Buscar Capítulos', 'lectorthema'),
        'not_found'             => __('No se encontraron capítulos.', 'lectorthema'),
        'not_found_in_trash'    => __('No hay capítulos en la papelera.', 'lectorthema'),
    ];

    $chapter_args = [
        'labels'             => $chapter_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'capitulo', 'with_front' => false],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => ['title', 'editor', 'thumbnail', 'comments', 'custom-fields'],
        'show_in_rest'       => true,
    ];

    register_post_type('chapter', $chapter_args);
}
add_action('init', 'lectorthema_register_post_types');
