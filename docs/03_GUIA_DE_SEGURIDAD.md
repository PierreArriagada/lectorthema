# LectorThema - Guía de Seguridad y Buenas Prácticas

La seguridad es el pilar central de **LectorThema**. El sistema implementa defensas en múltiples capas para garantizar la protección de los datos de los usuarios, evitar ataques de fuerza bruta y neutralizar vulnerabilidades web comunes.

---

## 1. Protección contra Falsificación de Peticiones en Sitios Cruzados (CSRF)

Todas las acciones asíncronas (AJAX) requieren un *Nonce* criptográfico generado por WordPress:
* **Generación del Nonce**:
  ```php
  'nonce' => wp_create_nonce('lectorthema_nonce')
  ```
* **Verificación en el Servidor**:
  ```php
  check_ajax_referer('lectorthema_nonce', 'security');
  ```
Si la petición no incluye un Nonce válido o ha expirado, el servidor rechaza la ejecución inmediatamente con un código HTTP 403.

---

## 2. Prevención de Inyección SQL (SQLi)

Ninguna consulta interactúa directamente con entradas del usuario sin pasar por sentencias preparadas:
```php
// Ejemplo Seguro:
$wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}manga_favorites WHERE user_id = %d AND manga_id = %d",
    $user_id,
    $manga_id
));
```
* `%d` para números enteros.
* `%s` para cadenas de texto.
* `%f` para números flotantes.

---

## 3. Sanitización y Escape de Salida (Prevención XSS)

### 3.1 Sanitización de Entradas
* `sanitize_text_field()`: Para títulos, números de capítulos y nombres de usuario.
* `sanitize_email()`: Para registros de correos electrónicos.
* `esc_url_raw()`: Para URLs de imágenes y portadas al guardar en la base de datos.
* `absint()`: Para IDs numéricos.

### 3.2 Escape de Salida
* `esc_html()`: Para textos mostrados dentro de etiquetas HTML.
* `esc_attr()`: Para valores dentro de atributos HTML (como `id`, `class`, `data-*`).
* `esc_url()`: Para enlaces y atributos `src` o `href`.

---

## 4. Cabeceras HTTP de Seguridad

El módulo `inc/security-helpers.php` inyecta automáticamente las siguientes cabeceras en cada respuesta HTTP:

| Cabecera | Valor | Propósito |
| :--- | :--- | :--- |
| `X-Content-Type-Options` | `nosniff` | Evita la detección errónea de tipos MIME (MIME Sniffing). |
| `X-Frame-Options` | `SAMEORIGIN` | Protege contra ataques de *Clickjacking* en iframes externos. |
| `X-XSS-Protection` | `1; mode=block` | Activa el filtro XSS del navegador. |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Controla la fuga de información sensible en referencias. |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=()` | Bloquea el acceso a hardware no necesario. |

---

## 5. Mitigación de Ataques de Fuerza Bruta (Rate Limiting)

Para evitar ataques de fuerza bruta en el inicio de sesión:
* Se rastrea el número de intentos fallidos por dirección IP mediante transitorios (`set_transient`).
* Si se superan **5 intentos fallidos en 15 minutos**, la IP queda bloqueada temporalmente hasta que expire el periodo de enfriamiento.
* El contador se restablece automáticamente tras un inicio de sesión exitoso.
