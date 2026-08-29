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
