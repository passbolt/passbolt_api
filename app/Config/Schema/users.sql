SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `modified_by` char(36) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Anonymous User / Guest
--

INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `active`, `created`, `modified`, `created_by`, `modified_by`) VALUES
('bbd56042-c5cd-11e1-a0c5-080027796c4c', '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'Anonymous', NULL, 1, '2012-07-04 13:45:11', '2012-07-04 13:45:14', '0208f3a4-c5cd-11e1-a0c5-080027796c4c', '0208f3a4-c5cd-11e1-a0c5-080027796c4c');

