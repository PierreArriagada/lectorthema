<?php
/**
 * LectorThema - Single Chapter Template (Lector de Capítulos)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()): the_post();
    $chapter_id = get_the_ID();
    $manga_id   = get_post_meta($chapter_id, '_chapter_manga_id', true);
    $parent_manga = get_post($manga_id);

    $chapter_num = get_post_meta($chapter_id, '_chapter_number', true) ?: '1';
    $chapter_sub = get_post_meta($chapter_id, '_chapter_subtitle', true);
    $images_raw  = get_post_meta($chapter_id, '_chapter_images', true);
    $images      = !empty($images_raw) ? array_filter(array_map('trim', explode("\n", $images_raw))) : [];

    // Obtener todos los capítulos del manga padre para la navegación
    $sibling_chapters = [];
    if ($manga_id) {
        $sibling_query = new WP_Query([
            'post_type'      => 'chapter',
            'posts_per_page' => -1,
            'meta_key'       => '_chapter_manga_id',
            'meta_value'     => $manga_id,
            'orderby'        => 'date',
            'order'          => 'ASC'
        ]);
        $sibling_chapters = $sibling_query->posts;
        wp_reset_postdata();
    }

    // Identificar Anterior y Siguiente
    $prev_chap = null;
    $next_chap = null;
    foreach ($sibling_chapters as $index => $ch) {
        if ($ch->ID === $chapter_id) {
            if (isset($sibling_chapters[$index - 1])) {
                $prev_chap = $sibling_chapters[$index - 1];
            }
            if (isset($sibling_chapters[$index + 1])) {
                $next_chap = $sibling_chapters[$index + 1];
            }
            break;
        }
    }
?>

<!-- Barra de Control del Lector -->
<div class="reader-top-bar">
    <div class="nexus-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <?php if ($parent_manga): ?>
                <a href="<?php echo esc_url(get_permalink($parent_manga->ID)); ?>" class="btn-header-action">
                    <i class="fa-solid fa-arrow-left"></i> <?php echo esc_html($parent_manga->post_title); ?>
                </a>
            <?php endif; ?>
            <span style="font-weight: 700; color: var(--text-primary); font-size: 14.5px;">
                <?php printf(__('Capítulo %s', 'lectorthema'), esc_html($chapter_num)); ?>
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            <?php if ($prev_chap): ?>
                <a href="<?php echo esc_url(get_permalink($prev_chap->ID)); ?>" class="btn-header-action">
                    <i class="fa-solid fa-chevron-left"></i> <?php _e('Anterior', 'lectorthema'); ?>
                </a>
            <?php endif; ?>

            <!-- Selector desplegable de capítulos -->
            <?php if (!empty($sibling_chapters)): ?>
                <select class="reader-chapter-select" onchange="if (this.value) window.location.href=this.value;">
                    <?php foreach ($sibling_chapters as $s_ch): 
                        $s_num = get_post_meta($s_ch->ID, '_chapter_number', true) ?: '1';
                    ?>
                        <option value="<?php echo esc_url(get_permalink($s_ch->ID)); ?>" <?php selected($s_ch->ID, $chapter_id); ?>>
                            <?php printf(__('Capítulo %s', 'lectorthema'), esc_html($s_num)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <?php if ($next_chap): ?>
                <a href="<?php echo esc_url(get_permalink($next_chap->ID)); ?>" class="btn-header-action btn-auth-primary">
                    <?php _e('Siguiente', 'lectorthema'); ?> <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Contenedor de Lectura Webtoon Vertical (Negro Puro #000000) -->
<div class="reader-webtoon-container">
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $index => $img_url): ?>
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php printf(__('Página %d', 'lectorthema'), $index + 1); ?>" class="webtoon-image-page" loading="lazy">
        <?php endforeach; ?>
    <?php else: ?>
        <div style="padding: 60px 20px; text-align: center; color: var(--text-secondary); background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); margin: 30px 0; width: 100%;">
            <i class="fa-regular fa-image" style="font-size: 36px; margin-bottom: 12px; color: var(--text-muted);"></i>
            <h3 style="color: var(--text-primary); margin-bottom: 6px;"><?php _e('Páginas en proceso de carga', 'lectorthema'); ?></h3>
            <p style="color: var(--text-muted); font-size: 13.5px;"><?php _e('Este capítulo aún no tiene páginas añadidas por el editor.', 'lectorthema'); ?></p>
        </div>
    <?php endif; ?>
</div>

<!-- Navegación Inferior -->
<div class="reader-bottom-nav">
    <?php if ($prev_chap): ?>
        <a href="<?php echo esc_url(get_permalink($prev_chap->ID)); ?>" class="btn-hero-info">
            <i class="fa-solid fa-chevron-left"></i> <?php _e('Capítulo Anterior', 'lectorthema'); ?>
        </a>
    <?php endif; ?>

    <?php if ($parent_manga): ?>
        <a href="<?php echo esc_url(get_permalink($parent_manga->ID)); ?>" class="btn-hero-read">
            <i class="fa-solid fa-list"></i> <?php _e('Índice de Capítulos', 'lectorthema'); ?>
        </a>
    <?php endif; ?>

    <?php if ($next_chap): ?>
        <a href="<?php echo esc_url(get_permalink($next_chap->ID)); ?>" class="btn-hero-info">
            <?php _e('Siguiente Capítulo', 'lectorthema'); ?> <i class="fa-solid fa-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>

<div class="nexus-container">
    <!-- Comentarios del Capítulo -->
    <section class="manga-comments-wrap" style="margin-top: 20px; margin-bottom: 50px;">
        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
    </section>
</div>

<?php
endwhile;

get_footer();
