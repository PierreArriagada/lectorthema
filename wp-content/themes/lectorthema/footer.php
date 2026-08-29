<?php
/**
 * LectorThema - Footer Template
 *
 * @package LectorThema
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
                    <span>LECTOR<span class="logo-accent">THEMA</span></span>
                </div>
                <p>
                    <?php _e('LectorThema es tu plataforma definitiva para descubrir, seguir y leer los mejores Mangas, Manhwas, Manhuas y Fan Comics en alta calidad con actualizaciones al instante.', 'lectorthema'); ?>
                </p>
            </div>

            <!-- Columna 2: Categorías y Formatos -->
            <div class="footer-col">
                <h4><?php _e('Formatos', 'lectorthema'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/tipo/manhwa/')); ?>"><?php _e('Manhwas (Webtoons)', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/tipo/manga/')); ?>"><?php _e('Mangas Tradicionales', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/tipo/manhua/')); ?>"><?php _e('Manhuas Orientales', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/tipo/fan-comic/')); ?>"><?php _e('Fan Comics & Webcomics', 'lectorthema'); ?></a></li>
                </ul>
            </div>

            <!-- Columna 3: Géneros Populares -->
            <div class="footer-col">
                <h4><?php _e('Géneros Populares', 'lectorthema'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/genero/accion/')); ?>"><?php _e('Acción y Aventura', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/genero/artes-marciales/')); ?>"><?php _e('Artes Marciales (Murim)', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/genero/isekai/')); ?>"><?php _e('Isekai & Reencarnación', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/genero/fantasia/')); ?>"><?php _e('Fantasía y Magia', 'lectorthema'); ?></a></li>
                </ul>
            </div>

            <!-- Columna 4: Usuario y Soporte -->
            <div class="footer-col">
                <h4><?php _e('Comunidad', 'lectorthema'); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/favoritos/')); ?>"><?php _e('Marcadores & Favoritos', 'lectorthema'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/mangas/')); ?>"><?php _e('Biblioteca Completa', 'lectorthema'); ?></a></li>
                    <li><a href="#" class="btn-open-auth" data-auth-tab="register"><?php _e('Crear Cuenta Gratuita', 'lectorthema'); ?></a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <strong>LectorThema</strong>. <?php _e('Todos los derechos reservados. Desarrollado con tecnología de alta velocidad.', 'lectorthema'); ?></p>
            <p><?php _e('Plataforma optimizada para dispositivos móviles y lectores webtoon.', 'lectorthema'); ?></p>
        </div>
    </div>
</footer>

<!-- Modal de Autenticación -->
<?php get_template_part('template-parts/modal-auth'); ?>

<?php wp_footer(); ?>
</body>
</html>
