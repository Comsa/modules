
--
-- Tabelstructuur voor tabel `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filetype` smallint(6) DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extension` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filesize` bigint(20) DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `media_filetype`
--

CREATE TABLE IF NOT EXISTS `media_filetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filetype` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `media_linktype`
--

CREATE TABLE IF NOT EXISTS `media_linktype` (
  `id` int(11) NOT NULL,
  `linktype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `media_modules`
--

CREATE TABLE IF NOT EXISTS `media_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `other_id` int(11) DEFAULT NULL,
  `module` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `identifier` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linktype` smallint(6) DEFAULT NULL,
  `temp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `media_type`
--

CREATE TABLE IF NOT EXISTS `media_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;