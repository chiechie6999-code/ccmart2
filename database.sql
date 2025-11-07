CREATE DATABASE IF NOT EXISTS ccmart_db;
USE ccmart_db;

CREATE TABLE IF NOT EXISTS `users` (
  `id_number` varchar(9) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `family_name` varchar(50) NOT NULL,
  `name_extension` varchar(10) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `purok_street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `municipality_city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_number`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `auth_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auth_questions` (`id`, `question`) VALUES
(1, 'Who is your best friend in Elementary?'),
(2, 'What is the name of your favorite pet?'),
(3, 'Who is your favorite teacher in high school?');

CREATE TABLE IF NOT EXISTS `auth_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_number` varchar(9) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL COMMENT 'Stores hashed answer',
  PRIMARY KEY (`id`),
  KEY `user_id_number` (`user_id_number`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `auth_answers_ibfk_1` FOREIGN KEY (`user_id_number`) REFERENCES `users` (`id_number`) ON DELETE CASCADE,
  CONSTRAINT `auth_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `auth_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
