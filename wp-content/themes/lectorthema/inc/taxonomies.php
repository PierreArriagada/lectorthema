<?php
/**
 * LectorThema - Taxonomías Personalizadas
 *
 * Registra 'manga_type' (Manga, Manhwa, Manhua, Fan Comic),
 * 'manga_genre' (Géneros) y 'manga_status' (Estado de emisión)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

function lectorthema_register_taxonomies() {
    // 1. Taxonomía: TIPO DE OBRA (Manga, Manhwa, Manhua, Fan Comic)
    $type_labels = [
        'name'              => _x('Tipos de Obra', 'taxonomy general name', 'lectorthema'),
        'singular_name'     => _x('Tipo de Obra', 'taxonomy singular name', 'lectorthema'),
        'search_items'      => __('Buscar Tipos', 'lectorthema'),
        'all_items'         => __('Todos los Tipos', 'lectorthema'),
        'edit_item'         => __('Editar Tipo', 'lectorthema'),
        'update_item'       => __('Actualizar Tipo', 'lectorthema'),
        'add_new_item'      => __('Añadir Nuevo Tipo', 'lectorthema'),
        'new_item_name'     => __('Nuevo Nombre de Tipo', 'lectorthema'),
        'menu_name'         => __('Tipos de Obra', 'lectorthema'),
    ];

    register_taxonomy('manga_type', ['manga'], [
        'hierarchical'      => true,
        'labels'            => $type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tipo', 'with_front' => false],
        'show_in_rest'      => true,
    ]);

    // 2. Taxonomía: GÉNERO
    $genre_labels = [
        'name'              => _x('Géneros', 'taxonomy general name', 'lectorthema'),
        'singular_name'     => _x('Género', 'taxonomy singular name', 'lectorthema'),
        'search_items'      => __('Buscar Géneros', 'lectorthema'),
        'all_items'         => __('Todos los Géneros', 'lectorthema'),
        'edit_item'         => __('Editar Género', 'lectorthema'),
        'update_item'       => __('Actualizar Género', 'lectorthema'),
        'add_new_item'      => __('Añadir Nuevo Género', 'lectorthema'),
        'new_item_name'     => __('Nuevo Nombre de Género', 'lectorthema'),
        'menu_name'         => __('Géneros', 'lectorthema'),
    ];

    register_taxonomy('manga_genre', ['manga'], [
        'hierarchical'      => true,
        'labels'            => $genre_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'genero', 'with_front' => false],
        'show_in_rest'      => true,
    ]);

    // 3. Taxonomía: ESTADO DE EMISIÓN
    $status_labels = [
        'name'              => _x('Estado', 'taxonomy general name', 'lectorthema'),
        'singular_name'     => _x('Estado', 'taxonomy singular name', 'lectorthema'),
        'search_items'      => __('Buscar Estados', 'lectorthema'),
        'all_items'         => __('Todos los Estados', 'lectorthema'),
        'edit_item'         => __('Editar Estado', 'lectorthema'),
        'update_item'       => __('Actualizar Estado', 'lectorthema'),
        'add_new_item'      => __('Añadir Nuevo Estado', 'lectorthema'),
        'new_item_name'     => __('Nuevo Estado', 'lectorthema'),
        'menu_name'         => __('Estados', 'lectorthema'),
    ];

    register_taxonomy('manga_status', ['manga'], [
        'hierarchical'      => true,
        'labels'            => $status_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'estado', 'with_front' => false],
        'show_in_rest'      => true,
    ]);
}
add_action('init', 'lectorthema_register_taxonomies', 0);

/**
 * Inserta términos por defecto si no existen
 */
function lectorthema_create_default_terms() {
    // Tipos principales requeridos
    $default_types = [
        'Manga'      => 'manga',
        'Manhwa'     => 'manhwa',
        'Manhua'     => 'manhua',
        'Fan Comic'  => 'fan-comic',
    ];

    foreach ($default_types as $name => $slug) {
        if (!term_exists($slug, 'manga_type')) {
            wp_insert_term($name, 'manga_type', ['slug' => $slug]);
        }
    }

    // Géneros por defecto (mínimo 8 solicitados)
    $default_genres = [
        'Acción'            => 'accion',
        'Aventura'          => 'aventura',
        'Artes Marciales'   => 'artes-marciales',
        'Fantasía'          => 'fantasia',
        'Isekai'            => 'isekai',
        'Romance'           => 'romance',
        'Comedia'           => 'comedia',
        'Sobrenatural'      => 'sobrenatural',
        'Misterio'          => 'misterio',
        'Drama'             => 'drama',
        'Reencarnación'     => 'reencarnacion',
        'Ciencia Ficción'   => 'ciencia-ficcion'
    ];

    foreach ($default_genres as $name => $slug) {
        if (!term_exists($slug, 'manga_genre')) {
            wp_insert_term($name, 'manga_genre', ['slug' => $slug]);
        }
    }

    // Estados por defecto canónicos de LectorThema
    $default_statuses = [
        'En emisión'  => 'en-emision',
        'Pausado'     => 'pausado',
        'Terminado'   => 'terminado',
        'Abandonado'  => 'abandonado',
        // Compatibilidad adicional
        'Finalizado'  => 'finalizado',
        'En pausa'    => 'en-pausa',
        'Cancelado'   => 'cancelado'
    ];

    foreach ($default_statuses as $name => $slug) {
        if (!term_exists($slug, 'manga_status')) {
            wp_insert_term($name, 'manga_status', ['slug' => $slug]);
        }
    }
}
add_action('after_switch_theme', 'lectorthema_create_default_terms');
add_action('admin_init', 'lectorthema_create_default_terms');
