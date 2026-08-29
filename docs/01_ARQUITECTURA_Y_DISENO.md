# LectorThema - Arquitectura y Guía de Diseño

## 1. Visión General del Proyecto
**LectorThema** es una solución integral sobre WordPress diseñada para portales de información, lectura y seguimiento de:
* **Manga (漫画)**: Obras japonesas tradicionales.
* **Manhwa (만화)**: Webtoons verticales coreanos a todo color.
* **Manhua (漫画)**: Obras chinas de artes marciales y cultivación.
* **Fan Comic**: Cómics independientes, doujinshis y webcomics occidentales.

---

## 2. Arquitectura CSS Modular por Vistas y Rendimiento Óptimo

Cada vista clave del sistema cuenta con su propio archivo CSS desacoplado que contiene su estructura visual y **sus propias reglas responsivas internas**:

```text
wp-content/themes/lectorthema/assets/css/
├── main.css           <-- Estructura base, Reset, Layout, Navbar y Footer (Global)
├── components.css     <-- Componentes compartidos (Cards base, Botones, Modales Auth, Toasts)
├── front-page.css     <-- Vista Portada: Hero Slider, Top de la Comunidad, Géneros, Widgets
├── single-manga.css   <-- Vista Ficha Manga: Cabecera Hero, Tags, Pestañas, Grilla 2x2 con Fechas y Barra Sticky Dual
├── reader.css         <-- Vista Lector: Lienzo #000000 Webtoon, Sticky Top Bar y Navegación
├── favorites.css      <-- Vista Favoritos y Catálogos: Archivo de Obras, Insignias y Paginación
└── responsive.css     <-- Media Queries responsivas globales y de la portada
```

### 2.1 Estrategia de Encolado Condicional (`functions.php`)

| Archivo CSS | Condición de Carga en WordPress | Propósito |
| :--- | :--- | :--- |
| `style.css` + `main.css` | Siempre (Global) | Variables CSS de la paleta editorial, tipografía, navbar y footer. |
| `components.css` | Siempre (Global) | Componentes UI compartidos entre páginas. |
| `front-page.css` | `is_front_page() \|\| is_home()` | Slider sin bordes, Top 3x3 y cuadrículas de portada. |
| `single-manga.css` | `is_singular('manga')` | Ficha técnica detallada, tags, pestañas, grilla móvil 2x2 con fechas y sticky bar dual. |
| `reader.css` | `is_singular('chapter')` | Lector webtoon vertical en negro absoluto `#000000`. |
| `favorites.css` | `is_page_template('page-favoritos.php') \|\| is_archive() \|\| is_tax()` | Catálogo y marcadores personales. |
| `responsive.css` | Siempre (Cargado al final) | Media queries dedicadas globales. |

---

## 3. Ficha Técnica de la Obra (`single-manga.php`)

La vista de información del manga está diseñada según los estándares de OriginOS 6 (superficies de cristal, bordes sutiles, micro-animaciones y compatibilidad integral con Modo Claro y Modo Oscuro):

```text
┌────────────────────────────────────────────────────────────────────────┐
│ [<]                                                         [!] [ ↑ ]  │
│ ┌──────────┐  Solo Leveling: Ragnarok                                  │
│ │          │  ★★★★★ 9.9 / 10                                           │
│ │  POSTER  │  Última actualización: Hace 1 Día • 👁 10.3K • 🔖 24     │
│ └──────────┘  [ Leer Primer Cap. 1 ]  [ Último Cap. 44 ]  [ ★ Favorito ]│
├────────────────────────────────────────────────────────────────────────┤
│ ┌─ Metadatos ────────────────────────────────────────────────────────┐ │
│ │ Géneros: [Manhwa] [Acción] [Fantasía] [Isekai]                      │ │
│ │ Autor: Chugong / Daul        Artista: Redice Studio                │ │
│ └────────────────────────────────────────────────────────────────────┘ │
│ ┌─ Sinopsis ─────────────────────────────────────────────────────────┐ │
│ │ La presencia de los Monarcas se ha debilitado, pero nuevas... ...más │ │
│ └────────────────────────────────────────────────────────────────────┘ │
├────────────────────────────────────────────────────────────────────────┤
│ [ 📖 Capítulos (44) ]                 [ 💬 Comentarios (12) ]          │
├────────────────────────────────────────────────────────────────────────┤
│ En emisión (44)                                          [🔍] Ordenar ⇅│
│ ┌──────────────────────┐ ┌──────────────────────┐                      │
│ │ Cap. 44    [ Nuevo ] │ │ Cap. 43              │   <-- Móvil: 2x2     │
│ │ Hace 1 Día           │ │ Hace 3 Días          │       con Fechas     │
│ └──────────────────────┘ └──────────────────────┘                      │
│ ┌──────────────────────┐ ┌──────────────────────┐                      │
│ │ Cap. 42              │ │ Cap. 41              │                      │
│ │ Hace 1 Semana        │ │ Hace 2 Semanas       │                      │
│ └──────────────────────┘ └──────────────────────┘                      │
├────────────────────────────────────────────────────────────────────────┤
│ [ ★ ]  [ ↑ ]      [ 📖 Cap. 1 ]            [ ⚡ Último Cap. 44 ]       │
└────────────────────────────────────────────────────────────────────────┘
  Barra Inferior Móvil Fija (Sticky Bottom Bar con Fondo Glassmorphism)
```

### 3.1 Especificaciones Clave de la Vista

1. **Cabecera Hero Dinámica**:
   - Fondo difuminado con portada desenfocada (*ambient blur*).
   - Modo Oscuro: Obsidian Smoked Glass con gradiente hacia `#070709`.
   - Modo Claro: Frosted Milky Glass con gradiente suave hacia `#F4F4F8`, manteniendo alto contraste y tipografía nítida `#0F172A`.
   - Accesos directos para PC: **Primer Cap.**, **Último Cap.** y **Favoritos** con estado activo instantáneo.

2. **Tarjetas de Metadatos y Sinopsis**:
   - Tarjeta de géneros estructurada con tags interactivos.
   - Tarjeta de autor/artista con nombres resaltados.
   - Bloque de sinopsis con expandible `...más / ...menos`.

3. **Cuadrícula de Capítulos**:
   - **En Móviles (`<= 768px`)**: Grilla estricta **2x2** (`grid-template-columns: repeat(2, 1fr)`).
   - **Contenido de la Card**: Número del capítulo (`Cap. 44`) en la parte superior con badge `Nuevo`, y fecha relativa de publicación (`Hace 1 Día`, `Hace 3 Horas`) en la parte inferior.
   - **En Escritorio/Tablet**: Grilla adaptable multi-columna (3 a 6 columnas).

4. **Barra de Acción Fija Inferior para Móviles (Sticky Bottom Bar)**:
   - Fondo de cristal esmerilado adaptable (`var(--surface-glass-heavy)`) con `backdrop-filter: var(--glass-blur-md)`.
   - Botón de **Favoritos** (★) y **Compartir** (↑).
   - Botón secundario: **Primer Capítulo** (`Cap. 1` con ícono de lectura).
   - Botón primario destacado: **Último Capítulo** (`Último Cap. 44` con gradiente naranja/índigo e ícono de rayo).

---

## 4. Paleta Editorial Estricta (Tokens de Color)

| Uso | Modo Oscuro (Por Defecto) | Modo Claro | Propósito y Aplicación |
| :--- | :--- | :--- | :--- |
| **Fondo principal** | `#070709` | `#F4F4F8` | Fondo global de la aplicación. |
| **Fondo lector manga** | `#000000` | `#E8E8EE` | **Negro absoluto** en el lienzo del lector webtoon. |
| **Superficie / Cards** | `#0D0D12` | `#FFFFFF` | Fondo de las tarjetas de mangas, ficha técnica y capítulos. |
| **Superficie Hover** | `#1A1A24` | `#F1F3F9` | Estado interactivo hover. |
| **Cristal Glassmorphism** | `rgba(13, 13, 18, 0.72)` | `rgba(255, 255, 255, 0.78)` | Navbar, barra sticky inferior y modales. |
| **Bordes generales** | `rgba(255, 255, 255, 0.08)` | `rgba(0, 0, 0, 0.08)` | Separadores de bloques, inputs y botones. |
| **Texto principal** | `#F8FAFC` | `#0F172A` | Títulos, encabezados y números de capítulos. |
| **Texto secundario** | `#94A3B8` | `#475569` | Sinopsis y metadatos complementarios. |
| **Texto silenciado / Fechas** | `#64748B` | `#8E9BAE` | Fechas relativas de capítulos y contadores. |
| **Color principal** | `#6366F1` | `#4F46E5` | Índigo eléctrico: Acentos y estados activos. |
| **Acento Acción / Lectura** | `#F97316` / `#EA580C` | `#EA580C` | Botón "Último Cap.", pestañas activas y estrellas de puntuación. |
| **Éxito / Ongoing** | `#22C55E` | `#16A34A` | Indicador "Ongoing / En emisión". |
| **Error / HOT / Nuevo** | `#EF4444` | `#DC2626` | Badge "Nuevo" y alertas. |

---

## 5. Estados Canónicos de Publicación (`manga_status`)

Toda obra en LectorThema pertenece a uno de los 4 estados canónicos, identificados con insignias visuales unificadas:

| Estado | Slug | Color | Indicador Visual | Descripción |
| :--- | :--- | :--- | :--- | :--- |
| **En emisión** | `en-emision` | Verde (`#10B981`) | Punto verde palpitante | Obras en curso que reciben nuevos capítulos activamente. |
| **Pausado** | `pausado` | Ámbar (`#F59E0B`) | Punto amarillo | Obras en pausa temporal o hiatus por el autor/editorial. |
| **Terminado** | `terminado` | Morado / Azul (`#A855F7`) | Punto morado | Obras con la historia totalmente finalizada y completa. |
| **Abandonado** | `abandonado` | Rojo / Gris (`#EF4444`) | Punto rojo | Obras canceladas o discontinuadas. |

