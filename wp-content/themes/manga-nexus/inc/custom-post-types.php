<?php
/**
 * MangaNexus - Custom Post Types
 *
 * Registra los CPTs 'manga' (obras) y 'chapter' (capítulos)
 *
 * @package MangaNexus
 */

if (!defined('ABSPATH')) {
    exit;
}

function manga_nexus_register_post_types() {
    // 1. Custom Post Type: MANGA / SERIE
    $manga_labels = [
        'name'                  => _x('Mangas y Series', 'Post type general name', 'manga-nexus'),
        'singular_name'         => _x('Manga / Obra', 'Post type singular name', 'manga-nexus'),
        'menu_name'             => _x('Mangas & Cómics', 'Admin Menu text', 'manga-nexus'),
        'name_admin_bar'        => _x('Manga', 'Add New on Toolbar', 'manga-nexus'),
        'add_new'               => __('Añadir Nueva Obra', 'manga-nexus'),
        'add_new_item'          => __('Añadir Nueva Obra / Manga', 'manga-nexus'),
        'new_item'              => __('Nueva Obra', 'manga-nexus'),
        'edit_item'             => __('Editar Obra', 'manga-nexus'),
        'view_item'             => __('Ver Obra', 'manga-nexus'),
        'all_items'             => __('Todas las Obras', 'manga-nexus'),
        'search_items'          => __('Buscar Mangas', 'manga-nexus'),
        'not_found'             => __('No se encontraron obras.', 'manga-nexus'),
        'not_found_in_trash'    => __('No hay obras en la papelera.', 'manga-nexus'),
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
        'name'                  => _x('Capítulos', 'Post type general name', 'manga-nexus'),
        'singular_name'         => _x('Capítulo', 'Post type singular name', 'manga-nexus'),
        'menu_name'             => _x('Capítulos', 'Admin Menu text', 'manga-nexus'),
        'name_admin_bar'        => _x('Capítulo', 'Add New on Toolbar', 'manga-nexus'),
        'add_new'               => __('Añadir Nuevo Capítulo', 'manga-nexus'),
        'add_new_item'          => __('Añadir Nuevo Capítulo', 'manga-nexus'),
        'new_item'              => __('Nuevo Capítulo', 'manga-nexus'),
        'edit_item'             => __('Editar Capítulo', 'manga-nexus'),
        'view_item'             => __('Ver Capítulo', 'manga-nexus'),
        'all_items'             => __('Todos los Capítulos', 'manga-nexus'),
        'search_items'          => __('Buscar Capítulos', 'manga-nexus'),
        'not_found'             => __('No se encontraron capítulos.', 'manga-nexus'),
        'not_found_in_trash'    => __('No hay capítulos en la papelera.', 'manga-nexus'),
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
add_action('init', 'manga_nexus_register_post_types');
