DROP TABLE IF EXISTS `DSO_groups`;

CREATE TABLE IF NOT EXISTS `DSO_groups` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(20) NOT NULL,
    `description` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `DSO_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

DROP TABLE IF EXISTS `DSO_login_attempts`;

CREATE TABLE IF NOT EXISTS `DSO_login_attempts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(15) NOT NULL,
    `login` varchar(100) NOT NULL,
    `time` int(11) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `DSO_users`;

CREATE TABLE IF NOT EXISTS `DSO_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(15) NOT NULL,
    `username` varchar(100) DEFAULT NULL,
    `password` varchar(255) NOT NULL,
    `salt` varchar(255) DEFAULT NULL,
    `email` varchar(100) NOT NULL,
    `activation_code` varchar(40) DEFAULT NULL,
    `forgotten_password_code` varchar(40) DEFAULT NULL,
    `forgotten_password_time` int(11) unsigned DEFAULT NULL,
    `remember_code` varchar(40) DEFAULT NULL,
    `created_on` int(11) unsigned NOT NULL,
    `last_login` int(11) unsigned DEFAULT NULL,
    `active` tinyint(1) unsigned DEFAULT NULL,
    `first_name` varchar(50) DEFAULT NULL,
    `last_name` varchar(50) DEFAULT NULL,
    `company` varchar(100) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `DSO_users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
    (1, '127.0.0.1', 'administrator', '$2y$08$9T2QTrrmSlwzd33RhAI6KeZweu2EVLPefVB6Bt5pxvZ35iTdV1tPK', '', 'admin@admin.com', '', NULL, NULL, NULL, 1268889823, 1498321047, 1, 'Admin', 'istrator', 'ADMIN', '0');

DROP TABLE IF EXISTS `DSO_users_groups`;

CREATE TABLE IF NOT EXISTS `DSO_users_groups` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `group_id` mediumint(8) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
    KEY `fk_users_groups_users1_idx` (`user_id`),
    KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `DSO_users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 1, 2);

ALTER TABLE `DSO_users_groups`
    ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `DSO_groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `DSO_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;











DROP TABLE IF EXISTS `DSO_ward`;

CREATE TABLE IF NOT EXISTS `DSO_ward` (
    `ward_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
    `name` char(255) COLLATE latin1_german2_ci NOT NULL,
    `description` blob,
    PRIMARY KEY (`ward_id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `DSO_parish`;

CREATE TABLE IF NOT EXISTS `DSO_parish` (
    `parish_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
    `ward_id` int(12) unsigned NOT NULL,
    `name` char(255) COLLATE latin1_german2_ci NOT NULL,
    `description` blob,
    PRIMARY KEY (`parish_id`),
    UNIQUE KEY `name` (`name`),
    KEY `ward_id` (`ward_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

ALTER TABLE `DSO_parish`
    ADD CONSTRAINT `fk_parish_ward` FOREIGN KEY (`ward_id`) REFERENCES `DSO_ward` (`ward_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DROP TABLE IF EXISTS `DSO_surname`;

CREATE TABLE IF NOT EXISTS `DSO_surname` (
    `surname_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
    `surname` char(255) COLLATE latin1_german2_ci NOT NULL,
    PRIMARY KEY (`surname_id`),
    KEY `surname` (`surname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `DSO_variant`;

CREATE TABLE IF NOT EXISTS `DSO_variant` (
    `variant_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
    `surname_id` int(12) unsigned NOT NULL,
    `variant` char(255) COLLATE latin1_german2_ci NOT NULL,
    PRIMARY KEY (`variant_id`),
    KEY `surname_id` (`surname_id`,`variant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

ALTER TABLE `DSO_variant`
    ADD CONSTRAINT `fk_surname_variant` FOREIGN KEY (`surname_id`) REFERENCES `DSO_surname` (`surname_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DROP TABLE IF EXISTS `DSO_parish_surname`;

CREATE TABLE IF NOT EXISTS `DSO_parish_surname` (
    `parish_surname_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
    `parish_id` int(12) unsigned NOT NULL,
    `surname_id` int(12) unsigned NOT NULL,
    PRIMARY KEY (`parish_surname_id`),
    KEY `parish_id` (`parish_id`,`surname_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

ALTER TABLE `DSO_parish_surname`
    ADD CONSTRAINT `fk_surname_parish_surname` FOREIGN KEY (`surname_id`) REFERENCES `DSO_surname` (`surname_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_parish_parish_surname` FOREIGN KEY (`parish_id`) REFERENCES `DSO_parish` (`parish_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DROP TABLE IF EXISTS `DSO_parish_surname_data`;

CREATE TABLE IF NOT EXISTS `DSO_parish_surname_data` (
    `parish_surname_data_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
    `parish_surname_id` int(12) unsigned NOT NULL,
    `year` int(4) unsigned NOT NULL DEFAULT 0,
    `births` int(5) unsigned NOT NULL DEFAULT 0,
    `baptisms` int(5) unsigned NOT NULL DEFAULT 0,
    `marriages` int(5) unsigned NOT NULL DEFAULT 0,
    `burials` int(5) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`parish_surname_data_id`),
    KEY `year` (`year`,`births`,`baptisms`,`marriages`,`burials`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;

ALTER TABLE `DSO_parish_surname_data`
    ADD CONSTRAINT `fk_parish_surname_parish_surname_data` FOREIGN KEY (`parish_surname_id`) REFERENCES `DSO_parish_surname` (`parish_surname_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;







insert into DSO_surname (surname) VALUES
    ('surname_1'),
    ('surname_2'),
    ('surname_3'),
    ('surname_4'),
    ('surname_5'),
    ('surname_6'),
    ('surname_7'),
    ('surname_8'),
    ('surname_9'),
    ('surname_10');

insert into DSO_variant (surname_id, variant) VALUES
    (1, 'surname_1 variant_1'),
    (1, 'surname_1 variant_2'),
    (1, 'surname_1 variant_3'),
    (1, 'surname_1 variant_4'),
    (1, 'surname_1 variant_5'),
    (1, 'surname_1 variant_6'),
    (1, 'surname_1 variant_7'),
    (1, 'surname_1 variant_8'),
    (1, 'surname_1 variant_9'),
    (1, 'surname_1 variant_10'),
    (2, 'surname_2 variant_1'),
    (2, 'surname_2 variant_2'),
    (2, 'surname_2 variant_3'),
    (2, 'surname_2 variant_4'),
    (2, 'surname_2 variant_5'),
    (2, 'surname_2 variant_6'),
    (2, 'surname_2 variant_7'),
    (2, 'surname_2 variant_8'),
    (2, 'surname_2 variant_9'),
    (2, 'surname_2 variant_10'),
    (3, 'surname_3 variant_1'),
    (3, 'surname_3 variant_2'),
    (3, 'surname_3 variant_3'),
    (3, 'surname_3 variant_4'),
    (3, 'surname_3 variant_5'),
    (3, 'surname_3 variant_6'),
    (3, 'surname_3 variant_7'),
    (3, 'surname_3 variant_8'),
    (3, 'surname_3 variant_9'),
    (3, 'surname_3 variant_10'),
    (4, 'surname_4 variant_1'),
    (4, 'surname_4 variant_2'),
    (4, 'surname_4 variant_3'),
    (4, 'surname_4 variant_4'),
    (4, 'surname_4 variant_5'),
    (4, 'surname_4 variant_6'),
    (4, 'surname_4 variant_7'),
    (4, 'surname_4 variant_8'),
    (4, 'surname_4 variant_9'),
    (4, 'surname_4 variant_10'),
    (5, 'surname_5 variant_1'),
    (5, 'surname_5 variant_2'),
    (5, 'surname_5 variant_3'),
    (5, 'surname_5 variant_4'),
    (5, 'surname_5 variant_5'),
    (5, 'surname_5 variant_6'),
    (5, 'surname_5 variant_7'),
    (5, 'surname_5 variant_8'),
    (5, 'surname_5 variant_9'),
    (5, 'surname_5 variant_10'),
    (6, 'surname_6 variant_1'),
    (6, 'surname_6 variant_2'),
    (6, 'surname_6 variant_3'),
    (6, 'surname_6 variant_4'),
    (6, 'surname_6 variant_5'),
    (6, 'surname_6 variant_6'),
    (6, 'surname_6 variant_7'),
    (6, 'surname_6 variant_8'),
    (6, 'surname_6 variant_9'),
    (6, 'surname_6 variant_10'),
    (7, 'surname_7 variant_1'),
    (7, 'surname_7 variant_2'),
    (7, 'surname_7 variant_3'),
    (7, 'surname_7 variant_4'),
    (7, 'surname_7 variant_5'),
    (7, 'surname_7 variant_6'),
    (7, 'surname_7 variant_7'),
    (7, 'surname_7 variant_8'),
    (7, 'surname_7 variant_9'),
    (7, 'surname_7 variant_10'),
    (8, 'surname_8 variant_1'),
    (8, 'surname_8 variant_2'),
    (8, 'surname_8 variant_3'),
    (8, 'surname_8 variant_4'),
    (8, 'surname_8 variant_5'),
    (8, 'surname_8 variant_6'),
    (8, 'surname_8 variant_7'),
    (8, 'surname_8 variant_8'),
    (8, 'surname_8 variant_9'),
    (8, 'surname_8 variant_10'),
    (9, 'surname_9 variant_1'),
    (9, 'surname_9 variant_2'),
    (9, 'surname_9 variant_3'),
    (9, 'surname_9 variant_4'),
    (9, 'surname_9 variant_5'),
    (9, 'surname_9 variant_6'),
    (9, 'surname_9 variant_7'),
    (9, 'surname_9 variant_8'),
    (9, 'surname_9 variant_9'),
    (9, 'surname_9 variant_10'),
    (10, 'surname_10 variant_1'),
    (10, 'surname_10 variant_2'),
    (10, 'surname_10 variant_3'),
    (10, 'surname_10 variant_4'),
    (10, 'surname_10 variant_5'),
    (10, 'surname_10 variant_6'),
    (10, 'surname_10 variant_7'),
    (10, 'surname_10 variant_8'),
    (10, 'surname_10 variant_9'),
    (10, 'surname_10 variant_10');

INSERT INTO DSO_parish (ward_id, name) VALUES
    (1, 'parish_1'),
    (1, 'parish_2'),
    (1, 'parish_3'),
    (1, 'parish_4'),
    (1, 'parish_5'),
    (2, 'parish_6'),
    (2, 'parish_7'),
    (2, 'parish_8'),
    (2, 'parish_9'),
    (2, 'parish_10'),
    (3, 'parish_11'),
    (3, 'parish_12'),
    (3, 'parish_13'),
    (3, 'parish_14'),
    (3, 'parish_15'),
    (4, 'parish_16'),
    (4, 'parish_17'),
    (4, 'parish_18'),
    (4, 'parish_19'),
    (4, 'parish_20'),
    (5, 'parish_21'),
    (5, 'parish_22'),
    (5, 'parish_23'),
    (5, 'parish_24'),
    (5, 'parish_25');

insert into DSO_parish_surname(surname_id, parish_id) VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 9),
    (10, 10),
    (1, 11),
    (2, 12),
    (3, 13),
    (4, 14),
    (5, 15),
    (6, 16),
    (7, 17),
    (8, 18),
    (9, 19),
    (10, 20),
    (1, 21),
    (2, 22),
    (3, 23),
    (4, 24),
    (5, 25);