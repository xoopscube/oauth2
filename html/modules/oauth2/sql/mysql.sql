CREATE TABLE `{prefix}_oauth2_user_map` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `xoops_uid` int(10) unsigned NOT NULL,
  `external_id` varchar(255) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `linked_on` int(10) unsigned NOT NULL,
  PRIMARY KEY (`map_id`),
  UNIQUE KEY `idx_provider_external_id` (`provider`,`external_id`),
  KEY `idx_xoops_uid_provider` (`xoops_uid`,`provider`)
) ENGINE=InnoDB;

