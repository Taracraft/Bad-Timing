CREATE TABLE IF NOT EXISTS `accounts` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(100) NOT NULL,
    `role` varchar(20) NOT NULL DEFAULT 'user', -- Neue Spalte für die Benutzerrolle
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

ALTER TABLE `accounts` ADD `activation_code` varchar(50) DEFAULT '';

-- Beispiel-Eintrag mit der neuen Rolle
INSERT INTO `accounts` (`id`, `username`, `password`, `email`, `role`)
VALUES (1, 'test', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'test@test.com', 'admin');
