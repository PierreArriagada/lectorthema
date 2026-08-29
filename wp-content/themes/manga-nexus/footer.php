<?php
/**
 * MangaNexus - Footer Template
 *
 * @package MangaNexus
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<footer class="site-footer">
    <div class="nexus-container">
        <div class="footer-grid">
            <!-- Columna 1: Info del Portal -->
            <div class="footer-col">
                <div class="site-logo" style="margin-bottom: 15px;">
                    <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
                    <span>MANGA<span class="logo-accent">NEXUS</span></span>
                </div>
                <p>
                    <?php _e('MangaNexus es tu plataforma definitiva para descubrir, seguir y leer los mejores Mangas, Manhwas, Manhuas y Fan Comics en alta calidad con actualizaciones al instante.', 'manga-nexus'); ?>
                </p>
            </div>

            <!-- Columna 2: Categorías y Formatos -->
            <div class="footer-col">
                <h4><?php _e('Formatos', 'manga-nexus'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/tipo/manhwa/')); ?>"><?php _e('Manhwas (Webtoons)', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/tipo/manga/')); ?>"><?php _e('Mangas Tradicionales', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/tipo/manhua/')); ?>"><?php _e('Manhuas Orientales', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/tipo/fan-comic/')); ?>"><?php _e('Fan Comics & Webcomics', 'manga-nexus'); ?></a></li>
                </ul>
            </div>

            <!-- Columna 3: Géneros Populares -->
            <div class="footer-col">
                <h4><?php _e('Géneros Populares', 'manga-nexus'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/genero/accion/')); ?>"><?php _e('Acción y Aventura', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/genero/artes-marciales/')); ?>"><?php _e('Artes Marciales (Murim)', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/genero/isekai/')); ?>"><?php _e('Isekai & Reencarnación', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/genero/fantasia/')); ?>"><?php _e('Fantasía y Magia', 'manga-nexus'); ?></a></li>
                </ul>
            </div>

            <!-- Columna 4: Usuario y Soporte -->
            <div class="footer-col">
                <h4><?php _e('Comunidad', 'manga-nexus'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/favoritos/')); ?>"><?php _e('Marcadores & Favoritos', 'manga-nexus'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/mangas/')); ?>"><?php _e('Biblioteca Completa', 'manga-nexus'); ?></a></li>
                    <li><a href="#" class="btn-open-auth" data-auth-tab="register"><?php _e('Crear Cuenta Gratuita', 'manga-nexus'); ?></a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <strong>MangaNexus</strong>. <?php _e('Todos los derechos reservados. Desarrollado con tecnología de alta velocidad.', 'manga-nexus'); ?></p>
            <p><?php _e('Plataforma optimizada para dispositivos móviles y lectores webtoon.', 'manga-nexus'); ?></p>
        </div>
    </div>
</footer>

<!-- Modal de Autenticación -->
<?php get_template_part('template-parts/modal-auth'); ?>

<?php wp_footer(); ?>
</body>
</html>
