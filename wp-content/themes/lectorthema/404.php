<?php
/**
 * LectorThema - 404 Error Page
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main" style="padding: 80px 0; text-align: center;">
    <div class="nexus-container">
        <div style="max-width: 500px; margin: 0 auto; background: var(--surface); padding: 45px 30px; border-radius: var(--radius-md); border: 1px solid var(--border);">
            <div style="font-family: var(--font-heading); font-size: 72px; font-weight: 900; color: var(--primary); line-height: 1; margin-bottom: 16px;">
                404
            </div>
            <h1 style="color: var(--text-primary); font-size: 22px; margin-bottom: 10px; font-family: var(--font-heading);">
                <?php _e('Página no encontrada', 'lectorthema'); ?>
            </h1>
            <p style="color: var(--text-secondary); margin-bottom: 25px; line-height: 1.6; font-size: 14px;">
                <?php _e('El capítulo u obra que buscas no existe o ha sido movido a otra dimensión.', 'lectorthema'); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-hero-read" style="display: inline-flex; margin: 0 auto;">
                <i class="fa-solid fa-house"></i> <?php _e('Volver a la Portada', 'lectorthema'); ?>
            </a>
        </div>
    </div>
</main>

<?php
get_footer();
