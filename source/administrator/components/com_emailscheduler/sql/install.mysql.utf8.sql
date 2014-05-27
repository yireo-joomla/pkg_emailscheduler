
CREATE TABLE IF NOT EXISTS `#__emailscheduler_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `message_id` varchar(32) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body_html` TEXT NOT NULL,
  `body_text` TEXT NOT NULL,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `cc` varchar(255) NOT NULL,
  `bcc` varchar(255) NOT NULL,
  `headers` TEXT NOT NULL,
  `attachments` TEXT NOT NULL,
  `send_state` varchar(20) NOT NULL,
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__emailscheduler_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` INT(11) NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL,
  `send_state` varchar(20) NOT NULL,
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__emailscheduler_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` TEXT NOT NULL,
  PRIMARY KEY (`id`)
);

