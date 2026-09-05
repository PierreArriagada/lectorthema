<?php
/**
 * LectorThema - Archive Manga Template (Directorio Completo de Obras)
 *
 * Muestra el catálogo integral de obras con sistema avanzado de filtrado:
 * - Búsqueda en vivo por palabra clave
 * - Filtros combinados por formato, estado y género
 * - Ordenamiento por popularidad, valoración, fecha o título
 * - Grilla responsive optimizada para Modo Claro y Modo Oscuro
 *
 * @package LectorThema
 * @subpackage Templates
 * @version 2.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main directory-main-content">
    <div class="nexus-container">
        
        <!-- 1. Cabecera Principal del Directorio -->
        <header class="directory-header-block">
            <div class="directory-title-wrap">
                <h1 class="directory-main-title">
                    <span class="directory-title-icon" aria-hidden="true">
                        <i class="fa-solid fa-book-open"></i>
                    </span>
                    <?php _e('Directorio Completo de Obras', 'lectorthema'); ?>
                </h1>
            </div>
            <p class="directory-subtitle">
                <?php _e('Explora todo el catálogo de mangas, manhwas y cómics. Filtra por formato, estado de emisión, géneros o busca directamente por título.', 'lectorthema'); ?>
            </p>
        </header>

        <!-- 2. Componente de Filtros y Búsqueda -->
        <?php get_template_part('template-parts/directory-filter'); ?>

        <!-- 3. Grilla de Obras o Estado Vacío -->
        <?php if (have_posts()): ?>
            <div class="manga-grid" id="directoryMangaGrid">
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('template-parts/card-manga'); ?>
                <?php endwhile; ?>
            </div>

            <!-- 4. Paginación con Parámetros GET Preservados -->
            <?php echo lectorthema_directory_pagination(); ?>

        <?php else: ?>
            <!-- Estado Vacío cuando ningún manga coincide con los filtros -->
            <div class="directory-empty-state" role="alert">
                <div class="directory-empty-icon-box" aria-hidden="true">
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                </div>
                <h2 class="directory-empty-title">
                    <?php _e('No se encontraron obras con los criterios seleccionados', 'lectorthema'); ?>
                </h2>
                <p class="directory-empty-desc">
                    <?php _e('Prueba combinando otros géneros, cambiando el estado o buscando con términos más amplios.', 'lectorthema'); ?>
                </p>
                <a href="<?php echo esc_url(get_post_type_archive_link('manga') ?: home_url('/mangas/')); ?>" class="btn-directory-submit directory-empty-btn">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span><?php _e('Restablecer todos los filtros', 'lectorthema'); ?></span>
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
