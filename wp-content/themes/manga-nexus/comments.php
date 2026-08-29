<?php
/**
 * MangaNexus - Comments Template
 *
 * @package MangaNexus
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
            <?php
            $comment_count = get_comments_number();
            printf(
                _n('%d Comentario', '%d Comentarios', $comment_count, 'manga-nexus'),
                number_format_i18n($comment_count)
            );
            ?>
        </h3>
    </div>

    <!-- Lista de Comentarios Existentes -->
    <?php if (have_comments()): ?>
        <ol class="comment-list" style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 25px;">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 40,
                'callback'    => 'manga_nexus_custom_comment_render'
            ]);
            ?>
        </ol>
    <?php endif; ?>

    <!-- Formulario para Publicar Comentario -->
    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');

    comment_form([
        'title_reply'          => __('Deja tu Comentario o Teoría', 'manga-nexus'),
        'title_reply_to'       => __('Responder a %s', 'manga-nexus'),
        'cancel_reply_link'    => __('Cancelar Respuesta', 'manga-nexus'),
        'label_submit'         => __('Publicar Comentario', 'manga-nexus'),
        'class_submit'         => 'btn-hero-read',
        'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s" style="margin-top: 10px; cursor: pointer;"><i class="fa-solid fa-paper-plane"></i> %4$s</button>',
        'comment_field'        => '<div class="auth-form-group"><label for="comment">' . _x('Tu Mensaje *', 'noun', 'manga-nexus') . '</label><textarea id="comment" name="comment" cols="45" rows="4" style="width: 100%; padding: 10px; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: var(--radius-xs); color: var(--text-primary); font-family: inherit; font-size: 13.5px; outline: none;" required></textarea></div>',
        'must_log_in'          => '<p style="color: var(--text-secondary); margin-bottom: 12px;">' . sprintf(__('Debes <a href="#" class="btn-open-auth" style="color: var(--primary); font-weight: 700;">iniciar sesión</a> para comentar.', 'manga-nexus')) . '</p>',
        'logged_in_as'         => '<p style="color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">' . sprintf(__('Conectado como <strong style="color: var(--text-primary);">%s</strong>. <a href="%s" style="color: var(--error); margin-left: 8px;">Cerrar sesión</a>', 'manga-nexus'), wp_get_current_user()->display_name, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
    ]);
    ?>
</div>

<?php
/**
 * Callback para renderizado personalizado de comentarios
 */
function manga_nexus_custom_comment_render($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class('single-comment-item'); ?> id="comment-<?php comment_ID(); ?>" style="padding: 14px; background: var(--surface-secondary); border: 1px solid var(--border-card); border-radius: var(--radius-xs);">
        <div style="display: flex; gap: 12px;">
            <div style="flex-shrink: 0; border-radius: var(--radius-xs); overflow: hidden; border: 1px solid var(--border); width: 38px; height: 38px;">
                <?php echo get_avatar($comment, 38); ?>
            </div>

            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <strong style="color: var(--text-primary); font-family: var(--font-heading); font-size: 14px;">
                        <?php echo get_comment_author_link(); ?>
                    </strong>
                    <span style="color: var(--text-muted); font-size: 11.5px;">
                        <?php echo manga_nexus_time_ago(get_comment_time('U')); ?>
                    </span>
                </div>

                <div style="color: var(--text-secondary); font-size: 13.5px; line-height: 1.6; margin-bottom: 6px;">
                    <?php comment_text(); ?>
                </div>

                <div style="font-size: 12px; color: var(--primary);">
                    <?php 
                    comment_reply_link(array_merge($args, [
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' => '<i class="fa-solid fa-reply"></i> ' . __('Responder', 'manga-nexus'),
                    ]));
                    ?>
                </div>
            </div>
        </div>
    <?php
}
