# LectorThema - Referencia de Endpoints AJAX y API

Todos los endpoints AJAX se ejecutan a través del controlador estándar de WordPress en `admin-ajax.php`.

---

## 1. Endpoint: Alternar Favorito (`lectorthema_toggle_favorite`)

Añade o elimina una obra de la biblioteca de favoritos del usuario autenticado.

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: Sí

### Parámetros de la Petición
| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `action` | `string` | Sí | Valor fijo: `lectorthema_toggle_favorite` |
| `security` | `string` | Sí | Token Nonce (`lectorthema_nonce`) |
| `manga_id` | `integer`| Sí | ID de la obra en `wp_posts` |

### Respuesta Exitosa (200 OK)
```json
{
  "success": true,
  "data": {
    "is_favorite": true,
    "total_favs": 42,
    "message": "¡Agregado a tus favoritos! Te avisaremos cuando haya nuevos capítulos."
  }
}
```

---

## 2. Endpoint: Inicio de Sesión Seguro (`lectorthema_ajax_login`)

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: No (Público)

### Parámetros de la Petición
| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `action` | `string` | Sí | Valor fijo: `lectorthema_ajax_login` |
| `security` | `string` | Sí | Token Nonce (`lectorthema_nonce`) |
| `username` | `string` | Sí | Nombre de usuario o email |
| `password` | `string` | Sí | Contraseña del usuario |
| `remember` | `boolean`| No | Recordar sesión activa |

### Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "message": "Inicio de sesión exitoso. Redirigiendo...",
    "redirect": "http://localhost:8000"
  }
}
```

---

## 3. Endpoint: Registro de Usuario (`lectorthema_ajax_register`)

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: No (Público)

### Parámetros de la Petición
| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `action` | `string` | Sí | Valor fijo: `lectorthema_ajax_register` |
| `security` | `string` | Sí | Token Nonce (`lectorthema_nonce`) |
| `username` | `string` | Sí | Nombre de usuario único |
| `email` | `string` | Sí | Correo electrónico válido |
| `password` | `string` | Sí | Contraseña (mínimo 6 caracteres) |

### Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "message": "¡Cuenta creada con éxito! Bienvenido a LectorThema.",
    "redirect": "http://localhost:8000"
  }
}
```

---

## 4. Endpoint: Publicar Comentario o Respuesta (`lectorthema_ajax_submit_comment`)

Permite a los usuarios registrados publicar comentarios o responder en hilos de forma asíncrona.

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: Sí

### Parámetros de la Petición
| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `action` | `string` | Sí | `lectorthema_ajax_submit_comment` |
| `security` | `string` | Sí | Token Nonce (`lectorthema_nonce`) |
| `post_id` | `integer` | Sí | ID del manga o capítulo |
| `comment` | `string` | Sí | Contenido del comentario |
| `comment_parent` | `integer` | No | ID del comentario padre (`0` si es raíz) |

### Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "message": "Comentario publicado con éxito.",
    "html": "<li class=\"custom-comment-item\" ...>...</li>",
    "parent": 0
  }
}
```

---

## 5. Endpoint: Reportar Comentario (`lectorthema_ajax_report_comment`)

Envía un reporte por contenido inapropiado. Si supera 5 reportes únicos, el comentario se envía a revisión.

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: Sí

### Parámetros de la Petición
| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `action` | `string` | Sí | `lectorthema_ajax_report_comment` |
| `security` | `string` | Sí | Token Nonce (`lectorthema_nonce`) |
| `comment_id` | `integer` | Sí | ID del comentario a reportar |

---

## 6. Endpoint: Obtener Notificaciones (`lectorthema_ajax_get_notifications`)

Retorna el contador de notificaciones no leídas y el listado HTML de las últimas 10 alertas.

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: Sí

### Parámetros de la Petición
| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `action` | `string` | Sí | `lectorthema_ajax_get_notifications` |
| `security` | `string` | Sí | Token Nonce (`lectorthema_nonce`) |

### Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "unread_count": 3,
    "html": "<a href=\"...\" class=\"notification-item unread\">...</a>"
  }
}
```

---

## 7. Endpoint: Marcar Notificaciones Leídas (`lectorthema_ajax_mark_notifications_read`)

Marca todas las notificaciones pendientes del usuario como leídas.

* **URL**: `/wp-admin/admin-ajax.php`
* **Método**: `POST`
* **Requiere Autenticación**: Sí
