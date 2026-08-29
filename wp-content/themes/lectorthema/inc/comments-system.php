<?php
/**
 * LectorThema - Comments & Notifications System
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Custom Comment Render (Walker Alternative)
 * Renderiza el HTML de cada comentario individual para wp_list_comments y AJAX.
 */
function lectorthema_custom_comment_render($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    
    // Obtener información del autor
    $author_id = $comment->user_id;
    $author_name = get_comment_author();
    $avatar_char = strtoupper(substr($author_name, 0, 1));
    $is_admin = false;
    
    if ($author_id) {
        $user = get_userdata($author_id);
        if ($user && in_array('administrator', (array) $user->roles)) {
            $is_admin = true;
        }
    }
    
    // Configuración CSS inline temporal para la estructura (idealmente en CSS)
    $depth_margin = ($depth > 1) ? 'margin-left: ' . (($depth - 1) * 20) . 'px;' : '';
    ?>
    <li <?php comment_class('custom-comment-item'); ?> id="comment-<?php comment_ID(); ?>" style="<?php echo $depth_margin; ?> background: var(--surface-secondary); border-radius: var(--radius-md); padding: 16px; margin-bottom: 12px; list-style: none; position: relative;">
        <div class="comment-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="user-avatar-mini" style="width: 35px; height: 35px; font-size: 14px;">
                    <?php echo $avatar_char; ?>
                </div>
                <div class="comment-meta">
                    <strong style="color: var(--text-primary); font-size: 14px;"><?php echo esc_html($author_name); ?></strong>
                    <?php if ($is_admin): ?>
                        <span style="background: var(--primary); color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Admin</span>
                    <?php endif; ?>
                    <span style="display: block; font-size: 12px; color: var(--text-secondary);">
                        <?php echo lectorthema_time_ago(get_comment_time('U')); ?>
                    </span>
                </div>
            </div>
            
            <div class="comment-actions" style="display: flex; gap: 10px;">
                <?php if (is_user_logged_in()): ?>
                    <button class="btn-comment-action btn-reply" data-comment-id="<?php comment_ID(); ?>" title="<?php esc_attr_e('Responder', 'lectorthema'); ?>" style="background: transparent; border: none; color: var(--text-secondary); cursor: pointer; padding: 5px;">
                        <i class="fa-solid fa-reply"></i>
                    </button>
                    <button class="btn-comment-action btn-report" data-comment-id="<?php comment_ID(); ?>" title="<?php esc_attr_e('Reportar Comentario', 'lectorthema'); ?>" style="background: transparent; border: none; color: var(--text-secondary); cursor: pointer; padding: 5px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="comment-content" style="color: var(--text-primary); font-size: 14px; line-height: 1.5;">
            <?php if ($comment->comment_approved == '0'): ?>
                <em style="color: var(--warning);"><?php _e('Tu comentario está pendiente de moderación.', 'lectorthema'); ?></em>
                <br />
            <?php endif; ?>
            <?php comment_text(); ?>
        </div>
        
        <!-- Contenedor para inyectar formulario de respuesta y respuestas anidadas -->
        <div class="reply-form-container" id="reply-form-<?php comment_ID(); ?>" style="display: none; margin-top: 15px;"></div>
        <ul class="children" id="children-of-<?php comment_ID(); ?>" style="padding-left: 0;"></ul>
    </li>
    <?php
}

/**
 * 2. Endpoint AJAX: Publicar Comentario
 */
add_action('wp_ajax_lectorthema_ajax_submit_comment', 'lectorthema_ajax_submit_comment_handler');
add_action('wp_ajax_nopriv_lectorthema_ajax_submit_comment', 'lectorthema_ajax_submit_comment_handler');

function lectorthema_ajax_submit_comment_handler() {
    check_ajax_referer('lectorthema_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('Debes iniciar sesión para comentar.', 'lectorthema')]);
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $comment_parent = isset($_POST['comment_parent']) ? intval($_POST['comment_parent']) : 0;
    $comment_content = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if (!$post_id || empty($comment_content)) {
        wp_send_json_error(['message' => __('Faltan datos obligatorios.', 'lectorthema')]);
    }

    $current_user = wp_get_current_user();

    $commentdata = [
        'comment_post_ID'      => $post_id,
        'comment_author'       => $current_user->display_name,
        'comment_author_email' => $current_user->user_email,
        'comment_author_url'   => $current_user->user_url,
        'comment_content'      => $comment_content,
        'comment_type'         => 'comment',
        'comment_parent'       => $comment_parent,
        'user_id'              => $current_user->ID,
        'comment_approved'     => 1,
    ];

    $comment_id = wp_new_comment($commentdata);

    if ($comment_id) {
        $comment = get_comment($comment_id);
        
        // --- SISTEMA DE NOTIFICACIONES ---
        if ($comment_parent > 0) {
            $parent_comment = get_comment($comment_parent);
            if ($parent_comment && $parent_comment->user_id && $parent_comment->user_id != $current_user->ID) {
                global $wpdb;
                $table_notif = $wpdb->prefix . 'manga_notifications';
                $wpdb->insert(
                    $table_notif,
                    [
                        'user_id'      => $parent_comment->user_id,
                        'sender_id'    => $current_user->ID,
                        'type'         => 'comment_reply',
                        'reference_id' => $post_id,
                        'created_at'   => current_time('mysql')
                    ],
                    ['%d', '%d', '%s', '%d', '%s']
                );
            }
        }

        ob_start();
        lectorthema_custom_comment_render($comment, ['depth' => ($comment_parent > 0 ? 2 : 1)], ($comment_parent > 0 ? 2 : 1));
        $html = ob_get_clean();

        wp_send_json_success([
            'message' => __('Comentario publicado con éxito.', 'lectorthema'),
            'html'    => $html,
            'parent'  => $comment_parent
        ]);
    } else {
        wp_send_json_error(['message' => __('Error al publicar el comentario.', 'lectorthema')]);
    }
}

/**
 * 3. Endpoint AJAX: Reportar Comentario
 */
add_action('wp_ajax_lectorthema_ajax_report_comment', 'lectorthema_ajax_report_comment_handler');

function lectorthema_ajax_report_comment_handler() {
    check_ajax_referer('lectorthema_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('Debes iniciar sesión para reportar.', 'lectorthema')]);
    }

    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    if (!$comment_id) {
        wp_send_json_error(['message' => __('Comentario no válido.', 'lectorthema')]);
    }

    $user_id = get_current_user_id();
    $reported_by = get_comment_meta($comment_id, '_reported_by', true);
    
    if (!is_array($reported_by)) {
        $reported_by = [];
    }

    if (in_array($user_id, $reported_by)) {
        wp_send_json_error(['message' => __('Ya has reportado este comentario.', 'lectorthema')]);
    }

    $reported_by[] = $user_id;
    update_comment_meta($comment_id, '_reported_by', $reported_by);
    
    $report_count = count($reported_by);
    update_comment_meta($comment_id, '_report_count', $report_count);

    if ($report_count >= 5) {
        wp_set_comment_status($comment_id, 'hold');
    }

    wp_send_json_success(['message' => __('Comentario reportado exitosamente.', 'lectorthema')]);
}

/**
 * 4. Endpoint AJAX: Obtener Notificaciones
 */
add_action('wp_ajax_lectorthema_ajax_get_notifications', 'lectorthema_ajax_get_notifications_handler');

function lectorthema_ajax_get_notifications_handler() {
    check_ajax_referer('lectorthema_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error();
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $table_notif = $wpdb->prefix . 'manga_notifications';

    // Asegurar que exista la tabla antes de consultar
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_notif'") != $table_notif) {
        wp_send_json_success(['unread_count' => 0, 'html' => '<div style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 13px;">' . __('Sistema en configuración.', 'lectorthema') . '</div>']);
    }

    $unread_count = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_notif WHERE user_id = %d AND is_read = 0",
        $user_id
    ));

    $notifications = $wpdb->get_results($wpdb->prepare(
        "SELECT n.*, u.display_name as sender_name 
         FROM $table_notif n 
         LEFT JOIN {$wpdb->users} u ON n.sender_id = u.ID 
         WHERE n.user_id = %d 
         ORDER BY n.created_at DESC LIMIT 10",
        $user_id
    ));

    $html = '';
    if ($notifications) {
        foreach ($notifications as $notif) {
            $class = $notif->is_read ? 'read' : 'unread';
            $bg = $notif->is_read ? 'transparent' : 'var(--surface-glass)';
            $url = get_permalink($notif->reference_id);
            $message = '';

            if ($notif->type === 'comment_reply') {
                $message = sprintf(__('<strong>%s</strong> respondió a tu comentario.', 'lectorthema'), esc_html($notif->sender_name));
            }

            $html .= '<a href="' . esc_url($url) . '" class="notification-item ' . $class . '" data-id="' . $notif->id . '" style="display: block; padding: 12px 15px; border-bottom: 1px solid var(--border); text-decoration: none; background: ' . $bg . ';">';
            $html .= '<p style="margin: 0; font-size: 13.5px; color: var(--text-primary);">' . $message . '</p>';
            $html .= '<span style="font-size: 11px; color: var(--text-secondary);">' . lectorthema_time_ago($notif->created_at) . '</span>';
            $html .= '</a>';
        }
    } else {
        $html = '<div style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 13px;">' . __('No tienes notificaciones.', 'lectorthema') . '</div>';
    }

    wp_send_json_success([
        'unread_count' => $unread_count,
        'html'         => $html
    ]);
}

/**
 * 5. Endpoint AJAX: Marcar Notificaciones como Leídas
 */
add_action('wp_ajax_lectorthema_ajax_mark_notifications_read', 'lectorthema_ajax_mark_notifications_read_handler');

function lectorthema_ajax_mark_notifications_read_handler() {
    check_ajax_referer('lectorthema_nonce', 'security');

    if (!is_user_logged_in()) {
        wp_send_json_error();
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $table_notif = $wpdb->prefix . 'manga_notifications';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_notif'") === $table_notif) {
        $wpdb->update(
            $table_notif,
            ['is_read' => 1],
            ['user_id' => $user_id, 'is_read' => 0],
            ['%d'],
            ['%d', '%d']
        );
    }

    wp_send_json_success();
}
