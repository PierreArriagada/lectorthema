<?php
/**
 * LectorThema - Taxonomy Template: Estado de la Obra (En emisión, Pausado, Terminado, Abandonado)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$current_term = get_queried_object();
$slug = $current_term->slug;

$descriptions = [
    'en-emision' => __('Obras en curso que reciben nuevos capítulos de forma periódica.', 'lectorthema'),
    'pausado'    => __('Obras en pausa temporal o hiatus por parte de sus autores o editoriales.', 'lectorthema'),
    'en-pausa'   => __('Obras en pausa temporal o hiatus por parte de sus autores o editoriales.', 'lectorthema'),
    'terminado'  => __('Obras completas con su historia totalmente finalizada y lista para maratonear.', 'lectorthema'),
    'finalizado' => __('Obras completas con su historia totalmente finalizada y lista para maratonear.', 'lectorthema'),
    'abandonado' => __('Obras canceladas o discontinuadas por la editorial o equipo de traducción.', 'lectorthema'),
    'cancelado'  => __('Obras canceladas o discontinuadas por la editorial o equipo de traducción.', 'lectorthema'),
];

$desc = $descriptions[$slug] ?? $current_term->description;

// Mapeo de clase CSS para el badge
switch ($slug) {
    case 'terminado':
    case 'finalizado':
        $badge_class = 'status-terminado';
        $icon = 'fa-circle-check';
        break;
    case 'pausado':
    case 'en-pausa':
        $badge_class = 'status-pausado';
        $icon = 'fa-circle-pause';
        break;
    case 'abandonado':
    case 'cancelado':
        $badge_class = 'status-abandonado';
        $icon = 'fa-circle-xmark';
        break;
    case 'en-emision':
    default:
        $badge_class = 'status-en-emision';
        $icon = 'fa-circle-play';
        break;
}
?>

<main id="primary" class="site-main" style="padding: 40px 0 80px 0;">
    <div class="nexus-container">
        
        <!-- Cabecera de la Sección de Estado -->
        <div class="section-header-block" style="flex-direction: column; align-items: flex-start; gap: 10px; margin-bottom: 30px;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span class="manga-status-badge <?php echo esc_attr($badge_class); ?>" style="font-size: 13px; padding: 6px 14px;">
                    <span class="status-dot"></span>
                    <?php echo esc_html($current_term->name); ?>
                </span>
                <h1 class="section-title" style="margin: 0; font-size: 22px;">
                    <?php printf(__('Obras %s', 'lectorthema'), esc_html($current_term->name)); ?>
                </h1>
            </div>
            <?php if (!empty($desc)): ?>
                <p style="color: var(--text-secondary); font-size: 14.5px; margin: 0; max-width: 700px; line-height: 1.5;"><?php echo esc_html($desc); ?></p>
            <?php endif; ?>

            <!-- Pestañas de Acceso Rápido a Otros Estados -->
            <div style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap;">
                <a href="<?php echo esc_url(home_url('/estado/en-emision/')); ?>" class="tag-pill-btn <?php echo ($slug === 'en-emision') ? 'active' : ''; ?>" style="font-size: 12px;">
                    🟢 <?php _e('En emisión', 'lectorthema'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/estado/pausado/')); ?>" class="tag-pill-btn <?php echo ($slug === 'pausado' || $slug === 'en-pausa') ? 'active' : ''; ?>" style="font-size: 12px;">
                    🟡 <?php _e('Pausado', 'lectorthema'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/estado/terminado/')); ?>" class="tag-pill-btn <?php echo ($slug === 'terminado' || $slug === 'finalizado') ? 'active' : ''; ?>" style="font-size: 12px;">
                    🟣 <?php _e('Terminado', 'lectorthema'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/estado/abandonado/')); ?>" class="tag-pill-btn <?php echo ($slug === 'abandonado' || $slug === 'cancelado') ? 'active' : ''; ?>" style="font-size: 12px;">
                    🔴 <?php _e('Abandonado', 'lectorthema'); ?>
                </a>
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
                <p style="color: var(--text-muted); font-size: 14px;"><?php printf(__('No se encontraron obras con estado "%s".', 'lectorthema'), esc_html($current_term->name)); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
