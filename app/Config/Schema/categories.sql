SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` char(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

