# MangaNexus - Guía de Instalación y Despliegue

## 1. Requisitos del Sistema
* **Docker** >= 20.10 & **Docker Compose** >= 2.0 (para despliegue local o contenerizado).
* O bien servidor **Linux (Ubuntu/Debian/RHEL)** con:
  * **PHP** >= 8.0 (con extensiones `mysqli`, `curl`, `mbstring`, `gd`, `xml`).
  * **MySQL** >= 8.0 o **MariaDB** >= 10.6.
  * **Nginx** o **Apache** con módulo `mod_rewrite` habilitado.

---

## 2. Puesta en Marcha con Docker (Puerto 8000)

### Paso 1: Clonar / Abrir la Carpeta del Proyecto
```bash
cd /home/pierre/Documentos/Mangas
```

### Paso 2: Iniciar los Contenedores
```bash
docker-compose up -d
```

### Paso 3: Acceder al Instalador Web
Abre tu navegador en:
`http://localhost:8000`

### Paso 4: Completar la Instalación Inicial de WordPress
1. Selecciona el idioma (**Español**).
2. Título del sitio: **MangaNexus**.
3. Nombre de usuario y contraseña de administrador.

### Paso 5: Activar el Tema MangaNexus
1. Ingresa a `http://localhost:8000/wp-admin/`.
2. Ve a **Apariencia > Temas**.
3. Selecciona y activa **MangaNexus**.
4. ¡El tema autoinstalará las tablas personalizadas de base de datos (`wp_manga_favorites`, `wp_manga_views`) y generará el contenido demo de bienvenida automáticamente!

### Paso 6: Configurar Enlaces Permanentes
1. Ve a **Ajustes > Enlaces permanentes**.
2. Selecciona **Nombre de la entrada** (`/%postname%/`).
3. Guarda los cambios.

---

## 3. Comandos Útiles de Administración

```bash
# Ver estado de los contenedores
docker-compose ps

# Ver logs en tiempo real de WordPress
docker-compose logs -f wordpress

# Ver logs de la base de datos MariaDB
docker-compose logs -f db

# Reiniciar los servicios
docker-compose restart

# Detener el entorno
docker-compose down
```
