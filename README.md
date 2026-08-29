# 📖 LectorThema - Tema WordPress y Plataforma de Lectura de Manga

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759B.svg?logo=wordpress&logoColor=white)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4.svg?logo=php&logoColor=white)](https://php.net)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED.svg?logo=docker&logoColor=white)](https://www.docker.com)
[![Status](https://img.shields.io/badge/Status-Open%20Source-success.svg)]()

**LectorThema** es un tema y sistema de portal de alto rendimiento para **WordPress**, diseñado específicamente para sitios de información, seguimiento y lectura de:
* 🇯🇵 **Manga (漫画)**: Obras japonesas tradicionales.
* 🇰🇷 **Manhwa (만화)**: Webtoons verticales coreanos a todo color.
* 🇨🇳 **Manhua (漫画)**: Obras chinas de artes marciales y cultivación.
* 🎨 **Fan Comics & Webcomics**: Cómics independientes y doujinshis occidentales.

El proyecto es **100% libre y de código abierto** bajo la licencia **GNU General Public License v3.0 (GPL-3.0)**, lo que permite que cualquier desarrollador o comunidad pueda utilizarlo, estudiarlo, mejorarlo y adaptarlo libremente, garantizando que todos los trabajos derivados se mantengan siempre libres y abiertos.

---

## ✨ Características Principales

### 🎨 1. Estética OriginOS 6 & Glassmorphism
* **Modo Oscuro (Obsidian Smoked Glass)**: Fondo profundo `#070709` con acentos en Índigo Eléctrico (`#6366F1`) y naranja de acción (`#F97316`).
* **Modo Claro (Frosted Milky Glass)**: Fondo suave `#F4F4F8`, tarjetas con cristal esmerilado blanco, sombras sutiles y texto de alto contraste `#0F172A`.
* **Sin parpadeo de tema (FOUC)**: Script inline ultraligero que lee `localStorage` antes del renderizado.

### 📱 2. Ficha Técnica de la Obra (`single-manga.php`)
* **Hero Banner con Ambient Blur**: Portada con efecto de profundidad 3D, estrellas doradas de puntuación, contador de vistas y seguidores.
* **CTAs Rápidos**: Botón de **Primer Capítulo** (`Primer Cap. 1`), **Último Capítulo** (`Último Cap. 45`) y **Favoritos** con sincronización AJAX.
* **Organización Limpia**: Tarjeta de géneros con etiquetas interactivas, autores y artistas destacados, y sinopsis expandible (`...más / ...menos`).
* **Grilla Móvil 2x2 con Fechas**: En dispositivos móviles (`<= 768px`), los capítulos se presentan en una **cuadrícula de 2 columnas (2x2)** con el título del capítulo arriba y la fecha de publicación abajo (`Hace 1 Día`, `Hace 3 Horas`).
* **Barra Móvil Flotante Dual (Sticky Bottom Bar)**: Acceso instantáneo en móviles a Favoritos (★), Compartir (↑), Primer Capítulo y Último Capítulo con acento destacado.

### 📖 3. Lector Webtoon Inmersivo (`single-chapter.php`)
* **Lienzo en Negro Puro (`#000000`)**: Lectura vertical continua tipo webtoon sin distracciones ni fatiga visual.
* **Sticky Top Bar de Navegación**: Selector rápido de capítulos, salto directo a anterior/siguiente y botón de retorno a la ficha técnica.

### 🏆 4. Rankings y Tops Dinámicos
* **Tops sin Recarga (AJAX)**: Clasificaciones por período (Top Diario, Semanal, Mensual y Desde Siempre).
* **Rankings por Género**: Filtrado ágil por Acción, Artes Marciales, Fantasía, Isekai, Romance, etc.

### 🔖 5. Marcadores y Favoritos
* Sistema de favoritos persistente en base de datos MySQL con soporte para usuarios autenticados y visitantes.
* Indicador visual de capítulos no leídos y notificaciones tipo toast integradas.

### ⚡ 6. Rendimiento y Seguridad
* **Iconos SVG Nativos**: Cero librerías externas pesadas; iconos vectoriales ultraligeros en PHP/SVG.
* **Seguridad Robusta**: Validación con Nonces CSRF, sentencias preparadas con `$wpdb->prepare()`, cabeceras HTTP de seguridad y limitador de tasa contra fuerza bruta.

---

## 📂 Estructura del Repositorio

```text
lectorthema/
├── .env.example                    <-- Plantilla de variables de entorno
├── .gitignore                      <-- Reglas de exclusión para Git
├── docker-compose.yml              <-- Entorno Docker listo para desarrollo (Puerto 8000)
├── LICENSE                         <-- Licencia GNU General Public License v3.0 (GPL-3.0)
├── README.md                       <-- Esta documentación
├── database/
│   ├── schema_manga_system.sql     <-- Tablas personalizadas de vistas, favoritos y logs
│   └── seed_demo_data.sql          <-- Datos de demostración (obras, capítulos, estadísticas)
├── docs/                           <-- Documentación técnica detallada
│   ├── 01_ARQUITECTURA_Y_DISENO.md
│   ├── 02_ESTRUCTURA_BASE_DE_DATOS.md
│   ├── 03_GUIA_DE_SEGURIDAD.md
│   ├── 04_SISTEMA_DE_FAVORITOS_Y_USUARIOS.md
│   ├── 05_GUIA_DE_INSTALACION_Y_DESPLIEGUE.md
│   └── 06_API_Y_ENDPOINTS_AJAX.md
└── wp-content/
    └── themes/
        └── lectorthema/            <-- Tema WordPress LectorThema
            ├── style.css           <-- Metadatos y tokens CSS globales
            ├── functions.php       <-- Encolado, helpers y configuración del tema
            ├── front-page.php      <-- Portada del sitio
            ├── single-manga.php    <-- Ficha técnica detallada de la obra
            ├── single-chapter.php  <-- Lector webtoon vertical
            ├── page-favoritos.php  <-- Catálogo de favoritos del usuario
            ├── taxonomy-*.php      <-- Archivos de géneros y formatos
            ├── comments.php        <-- Sistema de comentarios adaptativo
            ├── inc/                <-- Módulos PHP internos (CPTs, taxonomías, SVG, AJAX)
            ├── template-parts/     <-- Componentes modulares reutilizables
            └── assets/
                ├── css/            <-- CSS desacoplado por vistas (single-manga, reader, main)
                └── js/             <-- Scripts interactivos y clientes AJAX
```

---

## 🚀 Instalación Rápida

### Opción A: Despliegue con Docker Compose (Recomendado)

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/PierreArriagada/lectorthema.git
   cd lectorthema
   ```

2. **Configurar el archivo `.env`:**
   ```bash
   cp .env.example .env
   ```

3. **Iniciar los contenedores:**
   ```bash
   docker compose up -d
   ```

4. **Acceder a la aplicación:**
   * Abrir en el navegador: `http://localhost:8000`
   * Completar la instalación inicial de WordPress o cargar los datos de demostración incluidos en `/database`.

---

### Opción B: Instalación en un WordPress Existente

1. Copia la carpeta `wp-content/themes/lectorthema/` dentro de tu directorio `wp-content/themes/`.
2. Importa el archivo SQL `database/schema_manga_system.sql` en tu base de datos de WordPress para crear las tablas de estadísticas y favoritos.
3. Ingresa al panel de administración de WordPress (`wp-admin`) -> **Apariencia** -> **Temas** y activa **LectorThema**.

---

## 🎨 Paleta de Tokens de Color

| Token | Modo Oscuro (Obsidian) | Modo Claro (Frosted) | Aplicación |
| :--- | :--- | :--- | :--- |
| **Fondo Principal** | `#070709` | `#F4F4F8` | Fondo global de la aplicación |
| **Lector Webtoon** | `#000000` | `#E8E8EE` | Lienzo inmersivo de lectura |
| **Superficie / Cards**| `#0D0D12` | `#FFFFFF` | Tarjetas de manga y cajas de información |
| **Superficie Hover** | `#1A1A24` | `#F1F3F9` | Estados interactivos hover |
| **Cristal Translúcido**| `rgba(13, 13, 18, 0.72)` | `rgba(255, 255, 255, 0.78)` | Navbar y barra flotante móvil |
| **Bordes** | `rgba(255, 255, 255, 0.08)` | `rgba(0, 0, 0, 0.08)` | Separadores sutiles y tarjetas |
| **Texto Primario** | `#F8FAFC` | `#0F172A` | Títulos, encabezados y números |
| **Texto Secundario** | `#94A3B8` | `#475569` | Sinopsis y autores |
| **Texto Fechas** | `#64748B` | `#8E9BAE` | Fechas relativas de publicación |
| **Color Principal** | `#6366F1` | `#4F46E5` | Índigo eléctrico: Acentos y estados activos |
| **Acento Acción** | `#F97316` / `#EA580C` | `#EA580C` | Botones de lectura y pestañas activas |
| **Éxito / Ongoing** | `#22C55E` | `#16A34A` | Indicador de obras en emisión |
| **Alerta / Nuevo** | `#EF4444` | `#DC2626` | Distintivo `Nuevo` y avisos |

---

## 📄 Licencia de Uso y Modificación

Este proyecto se distribuye bajo los términos de la **GNU General Public License v3.0 (GPL-3.0)**.

> **¿Qué significa esto?**
> * Eres libre de **usar**, **copiar**, **estudiar** y **modificar** este código fuente para proyectos personales o comerciales.
> * Si decides redistribuir o publicar una versión modificada del tema, **debes mantener la misma licencia libre (GPL v3.0)** y compartir el código fuente correspondiente de manera pública.

Para más detalles, consulta el archivo [LICENSE](file:///home/pierre/Documentos/Mangas/LICENSE) incluido en la raíz de este proyecto o visita [https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html).

---

## 👤 Autor y Contacto

* **Autor**: Pierre Arriagada
* **Correo Electrónico**: [sotopierre1@gmail.com](mailto:sotopierre1@gmail.com)
* **Repositorio en GitHub**: [https://github.com/PierreArriagada/lectorthema](https://github.com/PierreArriagada/lectorthema)

---

¡Las contribuciones, sugerencias y pull requests de la comunidad son bienvenidas! Si encuentras algún problema o tienes una idea para mejorar el tema, no dudes en abrir un *Issue* en el repositorio.
