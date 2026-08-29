<?php
/**
 * MangaNexus - Template Part: Top de la Comunidad (Grilla 3x3 en Móvil y Capítulos Lado a Lado)
 *
 * Características:
 * - 9 Obras para conformar grilla 3x3 en móvil y 4-5 en desktop
 * - Utiliza el componente unificado card-top-manga.php
 *
 * @package MangaNexus
 */

if (!defined('ABSPATH')) {
    exit;
}

$top_weekly  = manga_nexus_get_top_weekly(9);
$top_daily   = manga_nexus_get_top_daily(9);
$top_monthly = manga_nexus_get_top_monthly(9);
$top_alltime = manga_nexus_get_top_alltime(9);

function render_top_manga_grid($items, $list_id, $is_active = false) {
    ?>
    <div class="top-rankings-grid <?php echo $is_active ? '' : 'is-hidden'; ?>" id="<?php echo esc_attr($list_id); ?>" style="<?php echo $is_active ? 'display: grid;' : 'display: none;'; ?>">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $idx => $row):
                $rank = $idx + 1;
                get_template_part('template-parts/card-top-manga', null, [
                    'manga_id' => $row->manga_id,
                    'rank'     => $rank,
                    'views'    => $row->total_views
                ]);
            endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 35px 20px; color: var(--text-muted); font-size: 14px; background: var(--surface-secondary); border-radius: var(--radius-xs); border: 1px solid var(--border);">
                <i class="fa-solid fa-ranking-star" style="font-size: 28px; margin-bottom: 8px; color: var(--primary);"></i>
                <p><?php _e('Actualizando estadísticas de la comunidad...', 'manga-nexus'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>

<section class="top-community-section" style="margin-bottom: 35px;">
    <div class="section-header-block" style="margin-bottom: 16px;">
        <h2 class="section-title" style="font-size: 19px;">
            <i class="fa-solid fa-ranking-star"></i> <?php _e('Top de la Comunidad', 'manga-nexus'); ?>
        </h2>

        <!-- Navegación por Pestañas -->
        <div class="top-tabs-nav" style="margin-bottom: 0;">
            <button class="top-tab-btn active" data-tab="weekly"><?php _e('Semanal', 'manga-nexus'); ?></button>
            <button class="top-tab-btn" data-tab="daily"><?php _e('Diario', 'manga-nexus'); ?></button>
            <button class="top-tab-btn" data-tab="monthly"><?php _e('Mensual', 'manga-nexus'); ?></button>
            <button class="top-tab-btn" data-tab="alltime"><?php _e('Histórico', 'manga-nexus'); ?></button>
        </div>
    </div>

    <!-- Cuadrículas de Rankings (3x3 en Móvil) -->
    <?php 
    render_top_manga_grid($top_weekly, 'topList-weekly', true);
    render_top_manga_grid($top_daily, 'topList-daily', false);
    render_top_manga_grid($top_monthly, 'topList-monthly', false);
    render_top_manga_grid($top_alltime, 'topList-alltime', false);
    ?>
</section>
