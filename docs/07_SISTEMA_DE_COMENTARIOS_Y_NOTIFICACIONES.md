# 07 - Sistema de Comentarios en Tiempo Real y Notificaciones

El tema **LectorThema** implementa un sistema avanzado de comunidad altamente optimizado, construido sobre la arquitectura de comentarios nativa de WordPress pero operando de manera completamente asíncrona mediante peticiones AJAX.

## Arquitectura del Sistema

### Comentarios y Hilos (Threads)
Los comentarios se guardan en la tabla estándar `wp_comments`, lo que mantiene compatibilidad total con plugins antispam (Akismet) y el panel de administración de WordPress.

1. **Obligatoriedad de Cuenta (Auth Wall)**:
   - Para comentar en la ficha de cualquier manga o en los capítulos individuales, el usuario debe poseer una cuenta activa y estar autenticado.
   - A los visitantes no autenticados se les presenta una tarjeta de invitación a la comunidad (**Crear Cuenta / Iniciar Sesión**) con acceso directo al modal de autenticación. Se desactivan por completo los formularios anónimos para proteger el sitio contra spam y fomentar el registro.
2. **AJAX Submission (`comments-realtime.js`)**: Cuando el usuario envía el formulario (`#commentform`), interceptamos el evento `submit` y hacemos la petición a `admin-ajax.php`.
3. **Backend (`inc/comments-system.php`)**: El endpoint `lectorthema_ajax_submit_comment_handler` procesa los datos y utiliza `wp_new_comment()`.
4. **Inyección en el DOM**: Tras guardarse, el servidor retorna el HTML exacto del comentario (renderizado por la función `lectorthema_custom_comment_render`). JavaScript toma este HTML y lo inyecta inmediatamente en la lista sin necesidad de recargar la página.
5. **Respuestas (Replies)**: Al responder un comentario, el JS mueve temporalmente el formulario principal debajo del comentario destino y asocia el ID (`comment_parent`). Tras publicarlo de forma asíncrona, el comentario hijo se anida en una etiqueta `ul.children` del padre.

### Sistema de Notificaciones
A diferencia de los comentarios, las notificaciones se almacenan en una tabla personalizada (`wp_manga_notifications`) para un rendimiento de consultas extremo (evitando búsquedas costosas en user_meta u options).

1. **Estructura de la Tabla**:
   - `id`: Primary key.
   - `user_id`: Destinatario.
   - `sender_id`: Remitente de la acción.
   - `type`: Ej: `comment_reply`.
   - `reference_id`: El ID del manga o post.
   - `is_read`: Boolean (1=leído, 0=no leído).
   - `created_at`: Fecha y hora.
2. **Generación**: Al momento de que alguien envía un `comment_parent`, el backend detecta si el usuario al que le responden es diferente al actual, e inserta una fila en la tabla de notificaciones.
3. **UI y Polling (`notifications.js`)**: El archivo intercepta clics en el botón con la campana. Carga el menú desplegable y realiza una consulta AJAX para listar el contador de no leídos.
4. **Marcar como leído**: El usuario puede limpiar su lista haciendo click en "Marcar leídas", ejecutando un `UPDATE` masivo por `user_id`.

### Sistema de Reportes de Moderación
Se añadió una funcionalidad para la auto-moderación de la comunidad:
- Cada comentario tiene un botón con una señal de alerta ⚠️.
- Al presionarlo, guarda en la tabla `wp_commentmeta` las llaves `_reported_by` (Array de IDs) y `_report_count`.
- **Mitigación Automática**: Si un comentario recibe 5 o más reportes, su estado cambia automáticamente a moderación manual (`hold`), por lo que desaparece del sitio público hasta que un administrador lo revise.

## Ajustes Automáticos del Tema (Auto Theme)
El archivo `assets/js/main.js` ahora implementa `window.matchMedia('(prefers-color-scheme: dark)')` para detectar el tema que usa el dispositivo del usuario (iOS, Windows, Android).
1. Si el usuario **nunca** ha modificado el tema manualmente (`localStorage` vacío), el sitio reaccionará en tiempo real al cambio de estado del sistema operativo.
2. Si el usuario escoge un tema de forma manual, la preferencia se fija en `localStorage` y tiene prioridad sobre el sistema operativo.
