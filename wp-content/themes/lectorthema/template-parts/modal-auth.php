<?php
/**
 * LectorThema - Template Part: Modal de Autenticación Seguro (Login / Registro)
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

if (is_user_logged_in()) {
    return; // No renderizar si ya está autenticado
}
?>

<div class="auth-modal-backdrop" id="mangaAuthModal" role="dialog" aria-modal="true" aria-labelledby="authModalHeading">
    <div class="auth-modal-box">
        <button type="button" class="auth-modal-close" aria-label="<?php esc_attr_e('Cerrar', 'lectorthema'); ?>">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="auth-tabs">
            <button type="button" class="auth-tab-btn active" data-tab="login" id="authModalHeading">
                <?php _e('Iniciar Sesión', 'lectorthema'); ?>
            </button>
            <button type="button" class="auth-tab-btn" data-tab="register">
                <?php _e('Registrarse', 'lectorthema'); ?>
            </button>
        </div>

        <!-- Formulario: INICIO DE SESIÓN -->
        <div class="auth-form-wrap" data-form="login">
            <div class="auth-msg-box" id="loginMsgBox"></div>

            <form id="mangaLoginForm" method="POST">
                <div class="auth-form-group">
                    <label for="loginUser"><?php _e('Usuario o Correo Electrónico', 'lectorthema'); ?></label>
                    <input type="text" id="loginUser" name="username" required autocomplete="username">
                </div>

                <div class="auth-form-group">
                    <label for="loginPass"><?php _e('Contraseña', 'lectorthema'); ?></label>
                    <input type="password" id="loginPass" name="password" required autocomplete="current-password">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--text-secondary);">
                        <input type="checkbox" name="remember" value="1"> <?php _e('Recordarme', 'lectorthema'); ?>
                    </label>
                </div>

                <button type="submit" class="auth-submit-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> <?php _e('Iniciar Sesión', 'lectorthema'); ?>
                </button>
            </form>
        </div>

        <!-- Formulario: REGISTRO DE CUENTA -->
        <div class="auth-form-wrap" data-form="register" style="display: none;">
            <div class="auth-msg-box" id="registerMsgBox"></div>

            <form id="mangaRegisterForm" method="POST">
                <div class="auth-form-group">
                    <label for="regUser"><?php _e('Nombre de Usuario', 'lectorthema'); ?></label>
                    <input type="text" id="regUser" name="username" required autocomplete="username" placeholder="Ej: lector_pro">
                </div>

                <div class="auth-form-group">
                    <label for="regEmail"><?php _e('Correo Electrónico', 'lectorthema'); ?></label>
                    <input type="email" id="regEmail" name="email" required autocomplete="email" placeholder="nombre@correo.com">
                </div>

                <div class="auth-form-group">
                    <label for="regPass"><?php _e('Contraseña (mínimo 6 caracteres)', 'lectorthema'); ?></label>
                    <input type="password" id="regPass" name="password" required minlength="6" autocomplete="new-password">
                </div>

                <button type="submit" class="auth-submit-btn">
                    <i class="fa-solid fa-user-plus"></i> <?php _e('Crear Cuenta', 'lectorthema'); ?>
                </button>
            </form>
        </div>
    </div>
</div>
