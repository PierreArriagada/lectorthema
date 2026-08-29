<?php
/**
 * MangaNexus - Meta Boxes y Campos Personalizados
 *
 * Añade campos para autor, artista, año, banner, slider, capítulos e imágenes.
 *
 * @package MangaNexus
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Registro de Meta Boxes
 */
function manga_nexus_add_meta_boxes() {
    // Meta box para Obras (Manga)
    add_meta_box(
        'manga_nexus_details_box',
        __('Información Detallada de la Obra', 'manga-nexus'),
        'manga_nexus_render_manga_meta_box',
        'manga',
        'normal',
        'high'
    );

    // Meta box para Capítulos (Chapter)
    add_meta_box(
        'manga_nexus_chapter_box',
        __('Detalles del Capítulo y Lector', 'manga-nexus'),
        'manga_nexus_render_chapter_meta_box',
        'chapter',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'manga_nexus_add_meta_boxes');

/**
 * 2. Renderizado del Meta Box de Manga
 */
function manga_nexus_render_manga_meta_box($post) {
    wp_nonce_field('manga_nexus_meta_nonce_action', 'manga_nexus_meta_nonce');

    $alt_titles   = get_post_meta($post->ID, '_manga_alt_titles', true);
    $author       = get_post_meta($post->ID, '_manga_author', true);
    $artist       = get_post_meta($post->ID, '_manga_artist', true);
    $release_year = get_post_meta($post->ID, '_manga_release_year', true);
    $rating       = get_post_meta($post->ID, '_manga_rating', true);
    $banner_url   = get_post_meta($post->ID, '_manga_banner_url', true);
    $is_featured  = get_post_meta($post->ID, '_manga_is_featured', true);
    $badge_icon   = get_post_meta($post->ID, '_manga_badge_icon', true);
    ?>
    <style>
        .manga-field-group { margin-bottom: 15px; }
        .manga-field-group label { display: block; font-weight: 600; margin-bottom: 5px; }
        .manga-field-group input[type="text"], .manga-field-group input[type="number"], .manga-field-group input[type="url"], .manga-field-group select { width: 100%; max-width: 600px; padding: 8px; }
        .manga-field-desc { color: #666; font-size: 12px; margin-top: 3px; }
        .manga-row-flex { display: flex; gap: 20px; flex-wrap: wrap; }
    </style>

    <div class="manga-row-flex">
        <div class="manga-field-group" style="flex: 1; min-width: 250px;">
            <label for="_manga_alt_titles"><?php _e('Títulos Alternativos / Originales', 'manga-nexus'); ?></label>
            <input type="text" id="_manga_alt_titles" name="_manga_alt_titles" value="<?php echo esc_attr($alt_titles); ?>" placeholder="Ej: Cheonmachunhwang, Heavenly Demon...">
        </div>

        <div class="manga-field-group" style="flex: 1; min-width: 200px;">
            <label for="_manga_release_year"><?php _e('Fecha / Año de Lanzamiento', 'manga-nexus'); ?></label>
            <input type="text" id="_manga_release_year" name="_manga_release_year" value="<?php echo esc_attr($release_year); ?>" placeholder="Ej: 2024 o Marzo 2024">
        </div>
    </div>

    <div class="manga-row-flex">
        <div class="manga-field-group" style="flex: 1; min-width: 200px;">
            <label for="_manga_author"><?php _e('Autor(es)', 'manga-nexus'); ?></label>
            <input type="text" id="_manga_author" name="_manga_author" value="<?php echo esc_attr($author); ?>" placeholder="Ej: Redice Studio">
        </div>

        <div class="manga-field-group" style="flex: 1; min-width: 200px;">
            <label for="_manga_artist"><?php _e('Artista(s)', 'manga-nexus'); ?></label>
            <input type="text" id="_manga_artist" name="_manga_artist" value="<?php echo esc_attr($artist); ?>" placeholder="Ej: Studio Dragon">
        </div>

        <div class="manga-field-group" style="flex: 1; min-width: 150px;">
            <label for="_manga_rating"><?php _e('Puntuación Inicial (1 - 10)', 'manga-nexus'); ?></label>
            <input type="number" step="0.1" min="1" max="10" id="_manga_rating" name="_manga_rating" value="<?php echo esc_attr($rating ?: '9.5'); ?>">
        </div>
    </div>

    <div class="manga-field-group">
        <label for="_manga_banner_url"><?php _e('URL de Imagen Banner (Fondo Panorámico)', 'manga-nexus'); ?></label>
        <input type="url" id="_manga_banner_url" name="_manga_banner_url" value="<?php echo esc_url($banner_url); ?>" placeholder="https://ejemplo.com/banner-hd.jpg">
        <p class="manga-field-desc"><?php _e('Imagen ancha para el fondo de la ficha o slider.', 'manga-nexus'); ?></p>
    </div>

    <div class="manga-row-flex">
        <div class="manga-field-group" style="flex: 1;">
            <label for="_manga_badge_icon"><?php _e('Emblema / Sello de Portada (Opcional)', 'manga-nexus'); ?></label>
            <input type="url" id="_manga_badge_icon" name="_manga_badge_icon" value="<?php echo esc_url($badge_icon); ?>" placeholder="https://ejemplo.com/emblema-dorado.png">
            <p class="manga-field-desc"><?php _e('Emblema circular en la esquina superior izquierda de la tarjeta.', 'manga-nexus'); ?></p>
        </div>

        <div class="manga-field-group" style="flex: 1; display: flex; align-items: center; margin-top: 20px;">
            <label style="cursor: pointer;">
                <input type="checkbox" name="_manga_is_featured" value="1" <?php checked($is_featured, '1'); ?>>
                <strong><?php _e('⭐ Destacar en el Slider Principal Superior', 'manga-nexus'); ?></strong>
            </label>
        </div>
    </div>
    <?php
}

/**
 * 3. Renderizado del Meta Box de Capítulos
 */
function manga_nexus_render_chapter_meta_box($post) {
    wp_nonce_field('manga_nexus_chapter_nonce_action', 'manga_nexus_chapter_nonce');

    $manga_id       = get_post_meta($post->ID, '_chapter_manga_id', true);
    $chapter_num    = get_post_meta($post->ID, '_chapter_number', true);
    $chapter_sub    = get_post_meta($post->ID, '_chapter_subtitle', true);
    $chapter_images = get_post_meta($post->ID, '_chapter_images', true);
    $is_hot         = get_post_meta($post->ID, '_chapter_is_hot', true);

    // Obtener lista de todos los mangas disponibles
    $mangas = get_posts([
        'post_type'      => 'manga',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    ]);
    ?>
    <div class="manga-row-flex">
        <div class="manga-field-group" style="flex: 2; min-width: 300px;">
            <label for="_chapter_manga_id"><?php _e('Selecciona la Obra / Manga Perteneciente *', 'manga-nexus'); ?></label>
            <select id="_chapter_manga_id" name="_chapter_manga_id" required style="max-width: 100%;">
                <option value=""><?php _e('-- Seleccionar Manga --', 'manga-nexus'); ?></option>
                <?php foreach ($mangas as $m): ?>
                    <option value="<?php echo esc_attr($m->ID); ?>" <?php selected($manga_id, $m->ID); ?>>
                        <?php echo esc_html($m->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="manga-field-group" style="flex: 1; min-width: 150px;">
            <label for="_chapter_number"><?php _e('Número de Capítulo *', 'manga-nexus'); ?></label>
            <input type="text" id="_chapter_number" name="_chapter_number" value="<?php echo esc_attr($chapter_num ?: '1'); ?>" required placeholder="Ej: 89, 88.5">
        </div>

        <div class="manga-field-group" style="flex: 1; min-width: 150px; display: flex; align-items: center; margin-top: 25px;">
            <label style="cursor: pointer;">
                <input type="checkbox" name="_chapter_is_hot" value="1" <?php checked($is_hot, '1'); ?>>
                <strong style="color: #e11d48;">🔥 <?php _e('Marcar como Capítulo HOT / Nuevo', 'manga-nexus'); ?></strong>
            </label>
        </div>
    </div>

    <div class="manga-field-group">
        <label for="_chapter_subtitle"><?php _e('Título / Subtítulo del Capítulo (Opcional)', 'manga-nexus'); ?></label>
        <input type="text" id="_chapter_subtitle" name="_chapter_subtitle" value="<?php echo esc_attr($chapter_sub); ?>" placeholder="Ej: El regreso del patriarca demoníaco">
    </div>

    <div class="manga-field-group">
        <label for="_chapter_images"><?php _e('Imágenes del Capítulo (Una URL por línea para el Lector Webtoon / Manga)', 'manga-nexus'); ?></label>
        <textarea id="_chapter_images" name="_chapter_images" rows="8" style="width: 100%; max-width: 800px; font-family: monospace; font-size: 13px;" placeholder="https://servidor.com/capitulo-89/01.jpg&#10;https://servidor.com/capitulo-89/02.jpg&#10;https://servidor.com/capitulo-89/03.jpg"><?php echo esc_textarea($chapter_images); ?></textarea>
        <p class="manga-field-desc"><?php _e('Pega las URLs de las páginas del capítulo en orden descendente. El lector las cargará de forma fluida.', 'manga-nexus'); ?></p>
    </div>
    <?php
}

/**
 * 4. Guardado Seguro de Metadatos
 */
function manga_nexus_save_meta_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Guardado de Manga
    if (isset($_POST['manga_nexus_meta_nonce']) && wp_verify_nonce($_POST['manga_nexus_meta_nonce'], 'manga_nexus_meta_nonce_action')) {
        $fields = [
            '_manga_alt_titles'   => 'sanitize_text_field',
            '_manga_author'       => 'sanitize_text_field',
            '_manga_artist'       => 'sanitize_text_field',
            '_manga_release_year' => 'sanitize_text_field',
            '_manga_rating'       => 'sanitize_text_field',
            '_manga_banner_url'   => 'esc_url_raw',
            '_manga_badge_icon'   => 'esc_url_raw',
        ];

        foreach ($fields as $field => $sanitizer) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, $sanitizer($_POST[$field]));
            }
        }

        $is_featured = !empty($_POST['_manga_is_featured']) ? '1' : '0';
        update_post_meta($post_id, '_manga_is_featured', $is_featured);
    }

    // Guardado de Capítulo
    if (isset($_POST['manga_nexus_chapter_nonce']) && wp_verify_nonce($_POST['manga_nexus_chapter_nonce'], 'manga_nexus_chapter_nonce_action')) {
        if (isset($_POST['_chapter_manga_id'])) {
            $manga_id = absint($_POST['_chapter_manga_id']);
            update_post_meta($post_id, '_chapter_manga_id', $manga_id);

            // Marcar alerta de nuevo capítulo para los usuarios que tienen este manga en favoritos
            if ($manga_id > 0) {
                global $wpdb;
                $table_favorites = $wpdb->prefix . 'manga_favorites';
                if ($wpdb->get_var("SHOW TABLES LIKE '$table_favorites'") === $table_favorites) {
                    $wpdb->query($wpdb->prepare(
                        "UPDATE $table_favorites SET has_unread_chapter = 1 WHERE manga_id = %d",
                        $manga_id
                    ));
                }
            }
        }

        if (isset($_POST['_chapter_number'])) {
            update_post_meta($post_id, '_chapter_number', sanitize_text_field($_POST['_chapter_number']));
        }

        if (isset($_POST['_chapter_subtitle'])) {
            update_post_meta($post_id, '_chapter_subtitle', sanitize_text_field($_POST['_chapter_subtitle']));
        }

        if (isset($_POST['_chapter_images'])) {
            update_post_meta($post_id, '_chapter_images', sanitize_textarea_field($_POST['_chapter_images']));
        }

        $is_hot = !empty($_POST['_chapter_is_hot']) ? '1' : '0';
        update_post_meta($post_id, '_chapter_is_hot', $is_hot);
    }
}
add_action('save_post', 'manga_nexus_save_meta_data');
