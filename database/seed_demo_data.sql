-- ==============================================================================
-- LectorThema - Datos de Prueba y Demostración (Seed Data)
-- Contiene registros de muestra para Vistas, Favoritos y Calificaciones
-- ==============================================================================

SET NAMES utf8mb4;

-- Inserción de estadísticas de vistas simuladas para Tops
-- (ID 1 al 12 correspondientes a los posts de manga de muestra)
INSERT INTO `wp_manga_views` (`manga_id`, `view_date`, `views_count`) VALUES
-- Hoy (Top Diario)
(1, CURDATE(), 1450),
(2, CURDATE(), 1890),
(3, CURDATE(), 980),
(4, CURDATE(), 2100),
(5, CURDATE(), 760),
(6, CURDATE(), 1340),
(7, CURDATE(), 890),
(8, CURDATE(), 1650),
(9, CURDATE(), 1120),
(10, CURDATE(), 670),
(11, CURDATE(), 1430),
(12, CURDATE(), 820),

-- Días anteriores de esta semana (Top Semanal)
(1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 1320),
(2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 1750),
(3, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 890),
(4, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 2200),
(5, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 710),
(6, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 1290),

(1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1410),
(2, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1680),
(3, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 950),
(4, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 2050),
(5, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 800),
(6, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1310),

(1, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 1500),
(2, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 1900),
(3, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 920),
(4, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 2300),
(5, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 750),
(6, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 1400),

-- Mes actual (Top Mensual)
(1, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 12500),
(2, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 18200),
(3, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 9400),
(4, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 24100),
(5, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 7800),
(6, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 14600),
(7, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 8900),
(8, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 16200),

-- Histórico (Top All-Time)
(1, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 145000),
(2, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 280000),
(3, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 95000),
(4, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 340000),
(5, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 87000),
(6, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 195000),
(7, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 110000),
(8, DATE_SUB(CURDATE(), INTERVAL 60 DAY), 215000)
ON DUPLICATE KEY UPDATE `views_count` = `views_count` + VALUES(`views_count`);
