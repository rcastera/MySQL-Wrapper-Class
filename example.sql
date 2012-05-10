CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `contacts` (`id`, `first_name`, `last_name`, `email`) VALUES
(1, 'Elisabeth', 'Castera', 'email@domain.com'),
(2, 'Jennifer', 'Castera', 'email@domain.com'),
(3, 'Richard', 'Castera', 'email@domain.com');