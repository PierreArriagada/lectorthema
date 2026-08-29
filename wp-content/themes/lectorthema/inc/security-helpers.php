<?php
/**
 * LectorThema - Módulo de Seguridad y Autenticación
 *
 * Implementa cabeceras de seguridad HTTP, protección CSRF, sanitización estricta,
 * limitación de tasa de intentos (rate limiting) y endpoints AJAX de login/registro.
 *
 * @package LectorThema
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Cabeceras HTTP de Seguridad
 */
function lectorthema_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    }
}
add_action('send_headers', 'lectorthema_security_headers');

/**
 * 2. Ocultar versión de WordPress por seguridad
 */
remove_action('wp_head', 'wp_generator');

/**
 * 3. Endpoint AJAX: Inicio de Sesión Seguro
 */
function lectorthema_ajax_login_handler() {
    check_ajax_referer('lectorthema_nonce', 'security');

    $username = sanitize_user($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    if (empty($username) || empty($password)) {
        wp_send_json_error([
            'message' => __('Por favor ingresa tu usuario y contraseña.', 'lectorthema')
        ]);
    }

    // Rate Limiting: Máximo 5 intentos fallidos por IP cada 15 minutos
    $client_ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
    $rate_key = 'manga_login_attempts_' . md5($client_ip);
    $attempts = (int) get_transient($rate_key);

    if ($attempts >= 5) {
        wp_send_json_error([
            'message' => __('Demasiados intentos fallidos. Por favor espera 15 minutos antes de volver a intentar.', 'lectorthema')
        ]);
    }

    $creds = [
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => $remember,
    ];

    $user = wp_signon($creds, is_ssl());

    if (is_wp_error($user)) {
        set_transient($rate_key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
        wp_send_json_error([
            'message' => __('Usuario o contraseña incorrectos.', 'lectorthema')
        ]);
    }

    // Reset intentos tras éxito
    delete_transient($rate_key);

    wp_send_json_success([
        'message'  => __('Inicio de sesión exitoso. Redirigiendo...', 'lectorthema'),
        'redirect' => home_url()
    ]);
}
add_action('wp_ajax_nopriv_lectorthema_ajax_login', 'lectorthema_ajax_login_handler');
add_action('wp_ajax_lectorthema_ajax_login', 'lectorthema_ajax_login_handler');

/**
 * 4. Endpoint AJAX: Registro de Usuario Seguro
 */
function lectorthema_ajax_register_handler() {
    check_ajax_referer('lectorthema_nonce', 'security');

    $username = sanitize_user($_POST['username'] ?? '');
    $email    = sanitize_email($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones
    if (empty($username) || empty($email) || empty($password)) {
        wp_send_json_error([
            'message' => __('Todos los campos son obligatorios.', 'lectorthema')
        ]);
    }

    if (!is_email($email)) {
        wp_send_json_error([
            'message' => __('El correo electrónico ingresado no es válido.', 'lectorthema')
        ]);
    }

    if (username_exists($username)) {
        wp_send_json_error([
            'message' => __('El nombre de usuario ya está registrado.', 'lectorthema')
        ]);
    }

    if (email_exists($email)) {
        wp_send_json_error([
            'message' => __('El correo electrónico ya está registrado.', 'lectorthema')
        ]);
    }

    if (strlen($password) < 6) {
        wp_send_json_error([
            'message' => __('La contraseña debe tener al menos 6 caracteres.', 'lectorthema')
        ]);
    }

    // Creación segura del usuario
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error([
            'message' => $user_id->get_error_message()
        ]);
    }

    // Asignar rol de suscriptor
    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // Auto-login tras el registro
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    wp_send_json_success([
        'message'  => __('¡Cuenta creada con éxito! Bienvenido a LectorThema.', 'lectorthema'),
        'redirect' => home_url()
    ]);
}
add_action('wp_ajax_nopriv_lectorthema_ajax_register', 'lectorthema_ajax_register_handler');
add_action('wp_ajax_lectorthema_ajax_register', 'lectorthema_ajax_register_handler');
