SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `modified_by` char(36) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Default Roles / created by anonymous user 
--

INSERT INTO `roles` (`id`, `name`, `description`, `created`, `modified`, `created_by`, `modified_by`) VALUES
('0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'guest', 'Non logged-in user', '2012-07-04 13:39:25', '2012-07-04 13:39:25', 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
('0208f57a-c5cd-11e1-a0c5-080027796c4c', 'user', 'Logged in default user', '2012-07-04 13:39:25', '2012-07-04 13:39:25', 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
('142c1188-c5cd-11e1-a0c5-080027796c4c', 'admin', 'Organization administrator', '2012-07-04 13:39:25', '2012-07-04 13:39:25', 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'bbd56042-c5cd-11e1-a0c5-080027796c4c'),
('142c1340-c5cd-11e1-a0c5-080027796c4c', 'root', 'Super Administrator', '2012-07-04 13:39:25', '2012-07-04 13:39:25', 'bbd56042-c5cd-11e1-a0c5-080027796c4c', 'bbd56042-c5cd-11e1-a0c5-080027796c4c');

