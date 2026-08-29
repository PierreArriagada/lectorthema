<?php
/**
 * LectorThema - Archive Manga Template (Directorio Completo de Obras)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main" style="padding: 40px 0 80px 0;">
    <div class="nexus-container">
        
        <div class="section-header-block" style="flex-direction: column; align-items: flex-start; gap: 15px; margin-bottom: 25px;">
            <div>
                <h1 class="section-title" style="margin: 0 0 6px;">
                    <i class="fa-solid fa-book" style="color: var(--primary);"></i> <?php _e('Directorio Completo de Obras', 'lectorthema'); ?>
                </h1>
                <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">
                    <?php _e('Explora todo el catálogo de cómics, filtra por estado de publicación o tipo de lectura.', 'lectorthema'); ?>
                </p>
            </div>

            <!-- Filtros de Tipos de Obra -->
            <div style="display: flex; flex-direction: column; gap: 10px; width: 100%; padding: 15px 18px; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: var(--radius-md);">
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; min-width: 60px;">
                        <?php _e('Formato:', 'lectorthema'); ?>
                    </span>
                    <a href="<?php echo esc_url(home_url('/mangas/')); ?>" class="tag-pill-btn active" style="font-size: 12px;">
                        <?php _e('Todos', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/tipo/manhwa/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        <?php _e('Manhwas', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/tipo/manga/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        <?php _e('Mangas', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/tipo/manhua/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        <?php _e('Manhuas', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/tipo/fan-comic/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        <?php _e('Fan Comics', 'lectorthema'); ?>
                    </a>
                </div>

                <!-- Filtros de Estados de Emisión -->
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding-top: 10px; border-top: 1px solid var(--border);">
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; min-width: 60px;">
                        <?php _e('Estado:', 'lectorthema'); ?>
                    </span>
                    <a href="<?php echo esc_url(home_url('/mangas/')); ?>" class="tag-pill-btn active" style="font-size: 12px;">
                        <?php _e('Todos', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/estado/en-emision/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        🟢 <?php _e('En emisión', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/estado/pausado/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        🟡 <?php _e('Pausado', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/estado/terminado/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        🟣 <?php _e('Terminado', 'lectorthema'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/estado/abandonado/')); ?>" class="tag-pill-btn" style="font-size: 12px;">
                        🔴 <?php _e('Abandonado', 'lectorthema'); ?>
                    </a>
                </div>
            </div>
        </div>

        <?php if (have_posts()): ?>
            <div class="manga-grid">
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('template-parts/card-manga'); ?>
                <?php endwhile; ?>
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <?php the_posts_pagination([
                    'prev_text' => '<i class="fa-solid fa-arrow-left"></i>',
                    'next_text' => '<i class="fa-solid fa-arrow-right"></i>',
                ]); ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; background: var(--surface-secondary); border-radius: var(--radius-md); border: 1px solid var(--border);">
                <p style="color: var(--text-muted); font-size: 14px;"><?php _e('No se encontraron obras disponibles.', 'lectorthema'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
