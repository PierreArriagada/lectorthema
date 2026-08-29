<?php
/**
 * LectorThema - Template: Mis Favoritos y Marcadores
 * Template Name: Mis Favoritos
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$user_id = get_current_user_id();
$favorites_list = $user_id ? lectorthema_get_user_favorites_list($user_id) : [];
?>

<main id="primary" class="site-main" style="padding: 35px 0 70px 0;">
    <div class="nexus-container">
        
        <div class="section-header-block">
            <h1 class="section-title">
                <i class="fa-solid fa-bookmark" style="color: var(--accent);"></i> <?php _e('Mis Series Favoritas y Marcadores', 'lectorthema'); ?>
            </h1>
            <span style="font-size: 13.5px; color: var(--text-muted);">
                <?php printf(_n('%d serie guardada', '%d series guardadas', count($favorites_list), 'lectorthema'), count($favorites_list)); ?>
            </span>
        </div>

        <?php if (!is_user_logged_in()): ?>
            <div style="text-align: center; padding: 60px 20px; background: var(--surface); border-radius: var(--radius-md); border: 1px solid var(--border); max-width: 540px; margin: 40px auto;">
                <i class="fa-solid fa-lock" style="font-size: 40px; color: var(--primary); margin-bottom: 16px;"></i>
                <h2 style="font-family: var(--font-heading); font-size: 22px; color: var(--text-primary); margin-bottom: 10px;">
                    <?php _e('Inicia sesión para ver tus favoritos', 'lectorthema'); ?>
                </h2>
                <p style="color: var(--text-secondary); margin-bottom: 22px; line-height: 1.6; font-size: 14px;">
                    <?php _e('Guarda tus mangas, manhwas y fan comics preferidos para enterarte al instante de nuevos capítulos.', 'lectorthema'); ?>
                </p>
                <button type="button" class="btn-hero-read btn-open-auth" data-auth-tab="login" style="margin: 0 auto;">
                    <i class="fa-solid fa-right-to-bracket"></i> <?php _e('Iniciar Sesión / Registrarse', 'lectorthema'); ?>
                </button>
            </div>

        <?php elseif (!empty($favorites_list)): ?>
            <div class="manga-grid">
                <?php foreach ($favorites_list as $fav):
                    $post = get_post($fav->manga_id);
                    if (!$post) continue;
                    setup_postdata($post);
                ?>
                    <div style="position: relative;">
                        <?php if ($fav->has_unread_chapter): ?>
                            <div style="position: absolute; top: -6px; right: -6px; z-index: 10; background: var(--error); color: #ffffff; font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: var(--radius-xs); display: flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-bell"></i> <?php _e('¡NUEVO!', 'lectorthema'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php get_template_part('template-parts/card-manga'); ?>
                    </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>

        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; background: var(--surface); border-radius: var(--radius-md); border: 1px solid var(--border); max-width: 540px; margin: 40px auto;">
                <i class="fa-regular fa-bookmark" style="font-size: 40px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h2 style="font-family: var(--font-heading); font-size: 20px; color: var(--text-primary); margin-bottom: 8px;">
                    <?php _e('Tu lista de favoritos está vacía', 'lectorthema'); ?>
                </h2>
                <p style="color: var(--text-secondary); margin-bottom: 22px; font-size: 14px;">
                    <?php _e('Explora nuestro catálogo y presiona "Agregar a Favoritos" en tus obras favoritas para seguirlas aquí.', 'lectorthema'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-hero-read" style="margin: 0 auto; display: inline-flex;">
                    <i class="fa-solid fa-compass"></i> <?php _e('Explorar Obras Populares', 'lectorthema'); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
