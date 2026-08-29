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
?>

<div id="comments" class="comments-area" style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 24px;">
    
    <div class="section-header-block" style="margin-bottom: 20px;">
        <h3 class="section-title" style="font-size: 17px;">
            <i class="fa-solid fa-comments" style="color: var(--primary);"></i>
            <span id="comments-counter-text">
                <?php
                $comment_count = get_comments_number();
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
        <?php endif; ?>
    </ol>

    <!-- Contenedor del Formulario Principal para Publicar Comentario -->
    <div id="lectorthema-main-comment-form">
        <?php
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');

        comment_form([
            'title_reply'          => __('Deja tu Comentario o Teoría', 'lectorthema'),
            'title_reply_to'       => __('Responder a %s', 'lectorthema'),
            'cancel_reply_link'    => __('Cancelar Respuesta', 'lectorthema'),
            'label_submit'         => __('Publicar Comentario', 'lectorthema'),
            'class_submit'         => 'btn-hero-read',
            'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s" style="margin-top: 10px; cursor: pointer; border: none; padding: 10px 20px; font-weight: bold; border-radius: 4px;"><i class="fa-solid fa-paper-plane"></i> %4$s</button>',
            'comment_field'        => '<div class="auth-form-group"><label for="comment">' . _x('Tu Mensaje *', 'noun', 'lectorthema') . '</label><textarea id="comment" name="comment" cols="45" rows="4" style="width: 100%; padding: 10px; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: var(--radius-xs); color: var(--text-primary); font-family: inherit; font-size: 13.5px; outline: none;" required></textarea></div>',
            'must_log_in'          => '<p style="color: var(--text-secondary); margin-bottom: 12px;">' . sprintf(__('Debes <a href="#" class="btn-open-auth" style="color: var(--primary); font-weight: 700;">iniciar sesión</a> para comentar.', 'lectorthema')) . '</p>',
            'logged_in_as'         => '<p style="color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">' . sprintf(__('Conectado como <strong style="color: var(--text-primary);">%s</strong>. <a href="%s" style="color: var(--error); margin-left: 8px;">Cerrar sesión</a>', 'lectorthema'), wp_get_current_user()->display_name, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
        ]);
        ?>
    </div>
</div>
