-- ==============================================================================
-- LectorThema - Esquema de Base de Datos y Tablas Personalizadas
-- Sistema de Alto Rendimiento para Portales de Manga, Manhwa, Manhua y Fan Comics
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Tabla de Favoritos y Marcadores de Usuarios (wp_manga_favorites)
-- Permite a los usuarios guardar sus obras y recibir alertas de nuevos capítulos
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_manga_favorites` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `manga_id` BIGINT(20) UNSIGNED NOT NULL,
  `last_read_chapter` VARCHAR(64) DEFAULT NULL,
  `last_read_at` DATETIME DEFAULT NULL,
  `has_unread_chapter` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_manga` (`user_id`, `manga_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_manga_id` (`manga_id`),
  KEY `idx_unread_alert` (`user_id`, `has_unread_chapter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ------------------------------------------------------------------------------
-- 2. Tabla de Conteo y Analítica de Vistas (wp_manga_views)
-- Soporta los Tops: Diario, Semanal, Mensual y Desde Siempre con alto rendimiento
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_manga_views` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `manga_id` BIGINT(20) UNSIGNED NOT NULL,
  `view_date` DATE NOT NULL,
  `views_count` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_manga_date` (`manga_id`, `view_date`),
  KEY `idx_manga_id` (`manga_id`),
  KEY `idx_view_date` (`view_date`),
  KEY `idx_date_views` (`view_date`, `views_count` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ------------------------------------------------------------------------------
-- 3. Tabla de Calificaciones y Valoraciones de Usuarios (wp_manga_ratings)
-- Sistema seguro de puntuación (1 a 10) con prevención de votos duplicados
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_manga_ratings` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `manga_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `rating` TINYINT(3) UNSIGNED NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_manga_user` (`manga_id`, `user_id`),
  KEY `idx_manga_rating` (`manga_id`, `rating`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ------------------------------------------------------------------------------
-- 4. Tabla de Historial de Lectura y Progreso (wp_manga_reading_history)
-- Permite al usuario continuar la lectura exactamente donde la dejó
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wp_manga_reading_history` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `manga_id` BIGINT(20) UNSIGNED NOT NULL,
  `chapter_id` BIGINT(20) UNSIGNED NOT NULL,
  `chapter_number` VARCHAR(32) NOT NULL,
  `page_number` INT(10) UNSIGNED NOT NULL DEFAULT 1,
  `last_read_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_manga_history` (`user_id`, `manga_id`),
  KEY `idx_user_history` (`user_id`, `last_read_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

SET FOREIGN_KEY_CHECKS = 1;
