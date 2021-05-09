USE `mutant_detector`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `records`
--

DROP TABLE IF EXISTS `records`;
CREATE TABLE IF NOT EXISTS `records` (
  `dna` VARCHAR(256) COLLATE utf8_unicode_ci NOT NULL,
  `mutant` TINYINT(1) NOT NULL,
  PRIMARY KEY (`dna`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;