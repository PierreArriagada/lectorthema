<?php
/**
 * LectorThema - Header Template
 *
 * Navbar optimizado para PC, Laptop y Móvil (iPhone SE compatible):
 * - Logo y marca a la izquierda con alta visibilidad
 * - Buscador como botón de icono (Lupa) interactivo y desplegable en PC
 * - Botón de Favoritos en icono compacto
 * - Selector interactivo de Modo Claro / Modo Oscuro con persistencia
 * - En Móvil: Botón Sandwich a la derecha, cero desbordamientos
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();
$unread_favs_count = 0;
if ($user_id) {
    global $wpdb;
    $table_favs = $wpdb->prefix . 'manga_favorites';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_favs'") === $table_favs) {
        $unread_favs_count = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_favs WHERE user_id = %d AND has_unread_chapter = 1",
            $user_id
        ));
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#070709">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Script de inicialización de tema Automático / Manual -->
    <script>
        (function() {
            let savedTheme = localStorage.getItem('lectorThemaTheme');
            if (!savedTheme) {
                // Auto-detect system preference
                savedTheme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="siteHeader">
    <div class="nexus-container header-inner">
        <!-- 1. Logo y Marca a la Izquierda -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <div class="logo-icon">
                <?php echo lectorthema_svg('book-reader', 'svg-logo-icon', 16); ?>
            </div>
            <span class="logo-text">LECTOR<span class="logo-accent">THEMA</span></span>
        </a>

        <!-- 2. Navegación Principal Desktop -->
        <nav class="main-nav" aria-label="<?php esc_attr_e('Navegación Principal', 'lectorthema'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>"><?php _e('Inicio', 'lectorthema'); ?></a>
            <a href="<?php echo esc_url(home_url('/tipo/manhwa/')); ?>"><?php _e('Manhwas', 'lectorthema'); ?></a>
            <a href="<?php echo esc_url(home_url('/tipo/manga/')); ?>"><?php _e('Mangas', 'lectorthema'); ?></a>
            <a href="<?php echo esc_url(home_url('/tipo/manhua/')); ?>"><?php _e('Manhuas', 'lectorthema'); ?></a>
            <a href="<?php echo esc_url(home_url('/tipo/fan-comic/')); ?>"><?php _e('Fan Comics', 'lectorthema'); ?></a>
            <a href="<?php echo esc_url(home_url('/mangas/')); ?>"><?php _e('Directorio', 'lectorthema'); ?></a>
        </nav>

        <!-- 3. Acciones a la Derecha (Buscador Lupa, Favoritos, Modo Claro/Oscuro y Usuario) -->
        <div class="header-actions">
            <!-- Barra de Búsqueda Desplegable en PC -->
            <div class="header-search-collapsible" id="headerSearchCollapsible">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="header-search-form" id="headerSearchForm">
                    <div class="search-input-wrap">
                        <?php echo lectorthema_svg('search', 'svg-search-nav', 14); ?>
                        <input type="search" name="s" id="headerSearchInput" placeholder="<?php esc_attr_e('Buscar series, autores...', 'lectorthema'); ?>" value="<?php echo get_search_query(); ?>" autocomplete="off">
                        <input type="hidden" name="post_type" value="manga">
                        <button type="button" class="btn-search-close" id="btnSearchClose" aria-label="<?php esc_attr_e('Cerrar búsqueda', 'lectorthema'); ?>">
                            <?php echo lectorthema_svg('close', 'svg-close-nav', 14); ?>
                        </button>
                    </div>
                </form>

                <!-- Botón Lupa en PC -->
                <button type="button" class="btn-header-icon-btn btn-search-toggle hide-on-mobile" id="btnSearchToggle" title="<?php esc_attr_e('Buscar series', 'lectorthema'); ?>" aria-label="<?php esc_attr_e('Buscar', 'lectorthema'); ?>">
                    <?php echo lectorthema_svg('search', 'svg-nav-icon', 16); ?>
                </button>
            </div>

            <!-- Botón de Favoritos en PC (Icono Compacto con SVG y Badge) -->
            <a href="<?php echo esc_url(home_url('/favoritos/')); ?>" class="btn-header-icon-btn hide-on-mobile" title="<?php esc_attr_e('Mis Favoritos y Marcadores', 'lectorthema'); ?>" aria-label="<?php esc_attr_e('Favoritos', 'lectorthema'); ?>">
                <?php echo lectorthema_svg('bookmark', 'svg-fav-nav', 16); ?>
                <?php if ($unread_favs_count > 0): ?>
                    <span class="badge-alert-dot" title="<?php printf(__('%d capítulos nuevos', 'lectorthema'), $unread_favs_count); ?>"></span>
                <?php endif; ?>
            </a>

            <!-- Botón de Notificaciones (Reemplaza Modo Oscuro) -->
            <?php if (is_user_logged_in()): ?>
                <button type="button" class="btn-header-icon-btn btn-notifications-toggle" id="btnNotificationsToggle" title="<?php esc_attr_e('Notificaciones', 'lectorthema'); ?>" aria-label="<?php esc_attr_e('Notificaciones', 'lectorthema'); ?>">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge-alert-dot" id="notificationsUnreadBadge" style="display:none;" title="<?php esc_attr_e('Nuevas notificaciones', 'lectorthema'); ?>"></span>
                </button>
            <?php endif; ?>

            <!-- Usuario Desktop & Móvil -->
            <?php if (is_user_logged_in()): 
                $current_user = wp_get_current_user();
            ?>
                <div class="user-profile-menu" title="<?php echo esc_attr($current_user->display_name); ?>">
                    <div class="user-avatar-mini">
                        <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                    </div>
                    <span class="user-name-desktop hide-on-mobile">
                        <?php echo esc_html($current_user->display_name); ?>
                    </span>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="hide-on-mobile user-logout-link" title="<?php esc_attr_e('Cerrar Sesión', 'lectorthema'); ?>">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </a>
                </div>
            <?php else: ?>
                <button type="button" class="btn-header-action btn-auth-primary btn-open-auth hide-on-mobile" data-auth-tab="login">
                    <i class="fa-solid fa-user"></i>
                    <span><?php _e('Ingresar', 'lectorthema'); ?></span>
                </button>
                <button type="button" class="btn-header-icon-mobile btn-open-auth show-on-mobile-only" data-auth-tab="login" aria-label="<?php esc_attr_e('Ingresar', 'lectorthema'); ?>" title="<?php esc_attr_e('Ingresar', 'lectorthema'); ?>">
                    <i class="fa-solid fa-user"></i>
                </button>
            <?php endif; ?>

            <!-- Botón Sandwich / Menú Hamburguesa a la DERECHA -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="<?php esc_attr_e('Abrir Menú', 'lectorthema'); ?>">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>
    </div>
</header>

<!-- Menú Drawer Móvil -->
<div class="mobile-nav-drawer" id="mobileNavDrawer">
    <div class="mobile-nav-header">
        <div class="site-logo">
            <div class="logo-icon">
                <?php echo lectorthema_svg('book-reader', 'svg-logo-icon', 15); ?>
            </div>
            <span class="logo-text">LECTOR<span class="logo-accent">THEMA</span></span>
        </div>
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="<?php esc_attr_e('Cerrar Menú', 'lectorthema'); ?>">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Buscador Móvil -->
    <div class="mobile-search-box" style="margin-bottom: 20px;">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="search-input-wrap">
                <?php echo lectorthema_svg('search', 'svg-search-drawer', 14); ?>
                <input type="search" name="s" placeholder="<?php esc_attr_e('Buscar series, autores...', 'lectorthema'); ?>" autocomplete="off" required>
                <input type="hidden" name="post_type" value="manga">
            </div>
        </form>
    </div>

    <!-- Enlaces de Navegación Móvil -->
    <nav class="mobile-nav-links">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">
            <i class="fa-solid fa-house"></i> <?php _e('Inicio', 'lectorthema'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/favoritos/')); ?>">
            <i class="fa-solid fa-bookmark" style="color: var(--accent);"></i> <?php _e('Mis Favoritos y Marcadores', 'lectorthema'); ?>
            <?php if ($unread_favs_count > 0): ?>
                <span class="drawer-badge-alert"><?php echo $unread_favs_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo esc_url(home_url('/tipo/manhwa/')); ?>">
            <i class="fa-solid fa-scroll" style="color: var(--success);"></i> <?php _e('Manhwas (Webtoons)', 'lectorthema'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/tipo/manga/')); ?>">
            <i class="fa-solid fa-book-open" style="color: var(--primary);"></i> <?php _e('Mangas Tradicionales', 'lectorthema'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/tipo/manhua/')); ?>">
            <i class="fa-solid fa-dragon" style="color: var(--warning);"></i> <?php _e('Manhuas Orientales', 'lectorthema'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/tipo/fan-comic/')); ?>">
            <i class="fa-solid fa-palette" style="color: var(--accent);"></i> <?php _e('Fan Comics & Doujinshi', 'lectorthema'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/mangas/')); ?>">
            <i class="fa-solid fa-list-ul"></i> <?php _e('Directorio Completo', 'lectorthema'); ?>
        </a>
    </nav>

    <!-- Botón de Tema Móvil / Drawer -->
    <div style="padding: 10px 0; border-top: 1px solid var(--border); margin-top: 15px;">
        <button type="button" class="btn-theme-toggle" id="btnThemeToggle" style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px; background: var(--surface-secondary); border-radius: var(--radius-sm); border: none; cursor: pointer; color: var(--text-primary); font-weight: 600;">
            <span><?php _e('Tema Visual', 'lectorthema'); ?></span>
            <div style="display: flex; gap: 8px;">
                <span class="theme-icon-dark" style="color: var(--accent);"><i class="fa-solid fa-moon"></i></span>
                <span class="theme-icon-light" style="color: var(--warning);"><i class="fa-solid fa-sun"></i></span>
            </div>
        </button>
    </div>

    <!-- Estado de Usuario en Drawer -->
    <div class="mobile-drawer-user-section" style="margin-top: 15px; padding-top: 20px; border-top: 1px solid var(--border);">
        <?php if (is_user_logged_in()): 
            $current_user = wp_get_current_user();
        ?>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="user-avatar-mini">
                        <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                    </div>
                    <span style="font-size: 14px; font-weight: 700; color: var(--text-primary);">
                        <?php echo esc_html($current_user->display_name); ?>
                    </span>
                </div>
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" style="color: var(--error); font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> <?php _e('Salir', 'lectorthema'); ?>
                </a>
            </div>
        <?php else: ?>
            <button type="button" class="btn-hero-read btn-open-auth" data-auth-tab="login" style="width: 100%; justify-content: center;">
                <i class="fa-solid fa-right-to-bracket"></i> <?php _e('Iniciar Sesión / Registro', 'lectorthema'); ?>
            </button>
        <?php endif; ?>
    </div>
</div>
