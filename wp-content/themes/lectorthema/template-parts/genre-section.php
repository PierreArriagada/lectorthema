<?php
/**
 * LectorThema - Template Part: Top por Género (Mínimo 8 Géneros)
 *
 * Utiliza el mismo componente unificado card-top-manga.php con:
 * - Medallas de ranking #1, #2, #3
 * - Estrellas (★ 9.8) sobre la imagen
 * - Capítulos abajo lado a lado (último en ROJO #EF4444)
 * - Grilla 3x3 en móvil
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

// 10 Géneros Principales
$genres_list = [
    ['name' => 'Acción',           'slug' => 'accion',           'icon' => 'fa-burst'],
    ['name' => 'Artes Marciales',  'slug' => 'artes-marciales',  'icon' => 'fa-hand-fist'],
    ['name' => 'Fantasía',         'slug' => 'fantasia',         'icon' => 'fa-wand-magic-sparkles'],
    ['name' => 'Isekai',           'slug' => 'isekai',           'icon' => 'fa-door-open'],
    ['name' => 'Aventura',         'slug' => 'aventura',         'icon' => 'fa-compass'],
    ['name' => 'Romance',          'slug' => 'romance',          'icon' => 'fa-heart'],
    ['name' => 'Comedia',          'slug' => 'comedia',          'icon' => 'fa-face-laugh-beam'],
    ['name' => 'Sobrenatural',     'slug' => 'sobrenatural',     'icon' => 'fa-ghost'],
    ['name' => 'Misterio',         'slug' => 'misterio',         'icon' => 'fa-mask'],
    ['name' => 'Reencarnación',    'slug' => 'reencarnacion',    'icon' => 'fa-rotate-right'],
];
?>

<section class="genre-top-section" style="margin-top: 45px; margin-bottom: 40px;">
    <div class="section-header-block" style="margin-bottom: 16px;">
        <h2 class="section-title" style="font-size: 19px;">
            <i class="fa-solid fa-shapes"></i> <?php _e('Top Obras por Género', 'lectorthema'); ?>
        </h2>
    </div>

    <!-- Barra Desplazable de Pestañas de Géneros -->
    <div class="genre-pills-bar">
        <?php foreach ($genres_list as $index => $g): ?>
            <button class="genre-pill-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-genre="<?php echo esc_attr($g['slug']); ?>">
                <i class="fa-solid <?php echo esc_attr($g['icon']); ?>"></i> <?php echo esc_html($g['name']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Cuadrículas de Ranking por Género (Card Top Unificada) -->
    <?php foreach ($genres_list as $index => $g):
        $mangas_in_genre = lectorthema_get_top_by_genre($g['slug'], 9);
    ?>
        <div class="genre-manga-grid top-rankings-grid <?php echo $index === 0 ? '' : 'is-hidden'; ?>" id="genreGrid-<?php echo esc_attr($g['slug']); ?>" style="<?php echo $index === 0 ? 'display: grid;' : 'display: none;'; ?>">
            <?php if (!empty($mangas_in_genre)): ?>
                <?php foreach ($mangas_in_genre as $idx => $manga_post): 
                    $rank = $idx + 1;
                    get_template_part('template-parts/card-top-manga', null, [
                        'manga_id' => $manga_post->ID,
                        'rank'     => $rank
                    ]);
                endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; padding: 35px 20px; text-align: center; background: var(--surface-secondary); border-radius: var(--radius-xs); border: 1px solid var(--border); color: var(--text-muted); font-size: 13.5px;">
                    <i class="fa-solid fa-folder-open" style="font-size: 26px; margin-bottom: 8px; color: var(--primary); display: block;"></i>
                    <?php printf(__('Aún no hay obras registradas en el género %s.', 'lectorthema'), esc_html($g['name'])); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>
