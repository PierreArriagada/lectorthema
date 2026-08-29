<?php
/**
 * LectorThema - Sistema de Favoritos y Marcadores con Alerta de Nuevos Capítulos
 *
 * Gestión de la tabla wp_manga_favorites con máxima seguridad (Nonce, Prepared SQL, Sanitization)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Inicialización de la Tabla de Favoritos
 */
function lectorthema_init_favorites_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'manga_favorites';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        manga_id BIGINT(20) UNSIGNED NOT NULL,
        last_read_chapter VARCHAR(64) DEFAULT NULL,
        last_read_at DATETIME DEFAULT NULL,
        has_unread_chapter TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uk_user_manga (user_id, manga_id),
        KEY idx_user_id (user_id),
        KEY idx_manga_id (manga_id),
        KEY idx_unread_alert (user_id, has_unread_chapter)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
add_action('after_switch_theme', 'lectorthema_init_favorites_table');
add_action('admin_init', 'lectorthema_init_favorites_table');

/**
 * 2. Comprobar si un manga es favorito del usuario actual
 */
function lectorthema_is_favorite($manga_id, $user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id || !$manga_id) {
        return false;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'manga_favorites';
    
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE user_id = %d AND manga_id = %d LIMIT 1",
        $user_id,
        $manga_id
    ));

    return !empty($exists);
}

/**
 * 3. Obtener el número total de usuarios que tienen el manga en favoritos
 */
function lectorthema_get_favorites_count($manga_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'manga_favorites';
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE manga_id = %d",
        $manga_id
    ));

    return (int) $count;
}

/**
 * 4. Endpoint AJAX: Alternar Favorito (Agregar / Quitar)
 */
function lectorthema_ajax_toggle_favorite() {
    check_ajax_referer('lectorthema_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error([
            'message' => __('Debes iniciar sesión para agregar a tus favoritos.', 'lectorthema'),
            'require_login' => true
        ]);
    }

    $user_id  = get_current_user_id();
    $manga_id = absint($_POST['manga_id'] ?? 0);

    if (!$manga_id) {
        wp_send_json_error(['message' => __('ID de manga no válido.', 'lectorthema')]);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'manga_favorites';

    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT id FROM $table WHERE user_id = %d AND manga_id = %d",
        $user_id,
        $manga_id
    ));

    if ($existing) {
        // Eliminar de favoritos
        $wpdb->delete($table, ['id' => $existing->id], ['%d']);
        $is_fav = false;
        $msg = __('Eliminado de tus favoritos.', 'lectorthema');
    } else {
        // Agregar a favoritos
        $wpdb->insert($table, [
            'user_id'            => $user_id,
            'manga_id'           => $manga_id,
            'has_unread_chapter' => 0,
            'created_at'         => current_time('mysql'),
        ], ['%d', '%d', '%d', '%s']);
        $is_fav = true;
        $msg = __('¡Agregado a tus favoritos! Te avisaremos cuando haya nuevos capítulos.', 'lectorthema');
    }

    $total_favs = lectorthema_get_favorites_count($manga_id);

    wp_send_json_success([
        'is_favorite' => $is_fav,
        'total_favs'  => $total_favs,
        'message'     => $msg
    ]);
}
add_action('wp_ajax_lectorthema_toggle_favorite', 'lectorthema_ajax_toggle_favorite');

/**
 * 5. Obtener los mangas favoritos de un usuario con indicador de alerta
 */
function lectorthema_get_user_favorites_list($user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    if (!$user_id) {
        return [];
    }

    global $wpdb;
    $table = $wpdb->prefix . 'manga_favorites';

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT manga_id, last_read_chapter, has_unread_chapter, created_at 
         FROM $table 
         WHERE user_id = %d 
         ORDER BY has_unread_chapter DESC, updated_at DESC",
        $user_id
    ));

    return $results;
}

/**
 * 6. Endpoint AJAX: Marcar capítulo como leído y resetear alerta
 */
function lectorthema_ajax_mark_chapter_read() {
    check_ajax_referer('lectorthema_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'No autorizado']);
    }

    $user_id        = get_current_user_id();
    $manga_id       = absint($_POST['manga_id'] ?? 0);
    $chapter_number = sanitize_text_field($_POST['chapter_number'] ?? '');

    if ($manga_id && $chapter_number) {
        global $wpdb;
        $table = $wpdb->prefix . 'manga_favorites';

        $wpdb->query($wpdb->prepare(
            "UPDATE $table 
             SET last_read_chapter = %s, last_read_at = %s, has_unread_chapter = 0 
             WHERE user_id = %d AND manga_id = %d",
            $chapter_number,
            current_time('mysql'),
            $user_id,
            $manga_id
        ));

        wp_send_json_success(['message' => 'Capítulo registrado']);
    }

    wp_send_json_error(['message' => 'Datos insuficientes']);
}
add_action('wp_ajax_lectorthema_mark_read', 'lectorthema_ajax_mark_chapter_read');
