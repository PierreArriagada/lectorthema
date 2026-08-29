<?php
/**
 * LectorThema - Comments Template
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}

$comment_count = get_comments_number();
?>

<div id="comments" class="comments-area" style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 24px;">
    
    <div class="section-header-block" style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <h3 class="section-title" style="font-size: 17px; margin: 0;">
            <i class="fa-solid fa-comments" style="color: var(--primary);"></i>
            <span id="comments-counter-text">
                <?php
                printf(
                    _n('%d Comentario', '%d Comentarios', $comment_count, 'lectorthema'),
                    number_format_i18n($comment_count)
                );
                ?>
            </span>
        </h3>
    </div>

    <!-- Lista de Comentarios Existentes -->
    <ol class="comment-list" id="comment-list-container" style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 25px; padding: 0;">
        <?php if (have_comments()): ?>
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 40,
                'callback'    => 'lectorthema_custom_comment_render'
            ]);
            ?>
        <?php else: ?>
            <li id="no-comments-yet" style="list-style: none; text-align: center; padding: 25px; color: var(--text-secondary); font-size: 13.5px;">
                <i class="fa-regular fa-comment-dots" style="font-size: 28px; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                <?php _e('Sé el primero en compartir tu opinión o teoría sobre esta obra.', 'lectorthema'); ?>
            </li>
        <?php endif; ?>
    </ol>

    <!-- Contenedor del Formulario / Llamado a la Acción de Registro -->
    <div id="lectorthema-main-comment-form">
        <?php if (is_user_logged_in()): 
            $current_user = wp_get_current_user();
        ?>
            <form id="commentform" class="comment-form" method="post" action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="user-avatar-mini" style="width: 32px; height: 32px; font-size: 13px;">
                            <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                        </div>
                        <span style="font-size: 13.5px; color: var(--text-secondary);">
                            <?php _e('Comentando como', 'lectorthema'); ?> <strong style="color: var(--text-primary);"><?php echo esc_html($current_user->display_name); ?></strong>
                        </span>
                    </div>
                    <a href="#" id="cancel-comment-reply-link" style="display: none; font-size: 12px; color: var(--error); font-weight: 600; text-decoration: none;">
                        <i class="fa-solid fa-xmark"></i> <?php _e('Cancelar Respuesta', 'lectorthema'); ?>
                    </a>
                </div>

                <div class="auth-form-group" style="margin-bottom: 12px;">
                    <textarea id="comment" name="comment" cols="45" rows="4" placeholder="<?php esc_attr_e('Escribe tu comentario o teoría aquí...', 'lectorthema'); ?>" style="width: 100%; padding: 12px; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-primary); font-family: inherit; font-size: 13.5px; outline: none; resize: vertical; box-sizing: border-box;" required></textarea>
                </div>

                <input type="hidden" name="comment_post_ID" value="<?php echo get_the_ID(); ?>" id="comment_post_ID">
                <input type="hidden" name="comment_parent" id="comment_parent" value="0">

                <button type="submit" class="btn-hero-read" style="cursor: pointer; border: none; padding: 10px 22px; font-weight: 700; border-radius: var(--radius-sm); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-paper-plane"></i> <?php _e('Publicar Comentario', 'lectorthema'); ?>
                </button>
            </form>

        <?php else: ?>

            <!-- Bloque Prominente: Invitar a Crear Cuenta / Iniciar Sesión -->
            <div class="comment-auth-wall-card" style="background: var(--surface-secondary); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 30px 20px; text-align: center; margin-top: 10px;">
                <div style="width: 52px; height: 52px; margin: 0 auto 14px; background: rgba(168, 85, 247, 0.12); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 22px;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                
                <h4 style="font-size: 17px; font-family: var(--font-heading); font-weight: 700; color: var(--text-primary); margin: 0 0 8px;">
                    <?php _e('Únete a la comunidad de LectorThema', 'lectorthema'); ?>
                </h4>
                
                <p style="font-size: 13.5px; color: var(--text-secondary); max-width: 500px; margin: 0 auto 20px; line-height: 1.5;">
                    <?php _e('Crea una cuenta para guardar tus mangas en favoritos, llevar el historial de lectura de tus capítulos y debatir en los comentarios de cada obra.', 'lectorthema'); ?>
                </p>

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="btn-hero-read btn-open-auth" data-auth-tab="register" style="padding: 11px 24px; font-weight: 700; border-radius: var(--radius-sm); border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-user-plus"></i> <?php _e('Crear Cuenta', 'lectorthema'); ?>
                    </button>
                    <button type="button" class="btn-header-action btn-auth-primary btn-open-auth" data-auth-tab="login" style="padding: 11px 20px; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; background: var(--surface); border: 1px solid var(--border); color: var(--text-primary);">
                        <i class="fa-solid fa-right-to-bracket"></i> <?php _e('Iniciar Sesión', 'lectorthema'); ?>
                    </button>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

