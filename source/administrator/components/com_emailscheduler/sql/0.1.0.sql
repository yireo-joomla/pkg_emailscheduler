
CREATE TABLE IF NOT EXISTS `#__emailscheduler_triggers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `condition` varchar(255) NOT NULL,
  `actions` varchar(255) NOT NULL,
  `access` tinyint(3) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
);
