--
-- Base de datos: `mutant_detector`
--
CREATE DATABASE IF NOT EXISTS `mutant_detector` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `mutant_detector`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `records`
--

DROP TABLE IF EXISTS `records`;
CREATE TABLE IF NOT EXISTS `records` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dna` VARCHAR(256) COLLATE utf8_unicode_ci NOT NULL,
  `mutant` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_dna` (`dna`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;