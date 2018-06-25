-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Lug 16, 2015 alle 11:22
-- Versione del server: 5.5.41-0ubuntu0.14.04.1
-- Versione PHP: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mooc`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Sicurezza'),
(2, 'Ingegneria Elettronica'),
(3, 'Data Science'),
(4, 'Programmazione'),
(5, 'Matematica'),
(6, 'Fisica'),
(7, 'Chimica'),
(8, 'Computer Graphics'),
(9, 'Medicina');

-- --------------------------------------------------------

--
-- Struttura della tabella `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `link` varchar(128) DEFAULT NULL,
  `owner_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `host_id` bigint(20) unsigned DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `categories_fk` (`category_id`),
  KEY `owners_fk` (`owner_id`),
  KEY `hosts_fk` (`host_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `courses`
--

INSERT INTO `courses` (`id`, `name`, `link`, `owner_id`, `category_id`, `host_id`) VALUES
(1, 'Crittografia 1', 'https://www.coursera.org/course/crypto', 1, 1, 1),
(3, 'Introduction to Web Development', 'http://www.pluralsight.com/courses/web-development-intro', 1, 4, 3),
(4, 'Introduction to Animation in 3ds Max', 'http://www.digitaltutors.com/tutorial/1649-Introduction-to-Animation-in-3ds-Max', 1, 8, 4),
(5, 'Advanced Android App Development ', 'https://www.udacity.com/course/advanced-android-app-development--ud855', 1, 4, 5),
(7, 'iOS8 and Swift', 'https://www.udemy.com/complete-ios-developer-course/', 1, 4, NULL),
(16, 'C#', 'https://www.edx.org/course/programming-c-microsoft-dev204x-0', 1, 4, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `courses_learners`
--

CREATE TABLE IF NOT EXISTS `courses_learners` (
  `learner_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `course_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`learner_id`,`course_id`),
  KEY `courses_fk` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `courses_learners`
--

INSERT INTO `courses_learners` (`learner_id`, `course_id`) VALUES
(4, 1),
(4, 16);

-- --------------------------------------------------------

--
-- Struttura della tabella `hosts`
--

CREATE TABLE IF NOT EXISTS `hosts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `link` varchar(128) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `hosts`
--

INSERT INTO `hosts` (`id`, `name`, `link`) VALUES
(1, 'coursera', 'https://www.coursera.org/'),
(2, 'edX', 'https://www.edx.org/'),
(3, 'pluralsight', 'http://www.pluralsight.com/'),
(4, 'digital-tutors', 'http://www.digitaltutors.com/11/index.php'),
(5, 'udacity', 'https://www.udacity.com/');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `username` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `isProvider` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `isProvider`) VALUES
(1, 'Mattia', 'Atzeni', 'mattia.atzeni@outlook.it', 'mattia', 'amigdala', 1),
(4, 'Utente', 'Utente', 'utente@user.com', 'utente', 'utente', 0),
(5, 'Gabriele', 'Atzeni', 'gabriatzeni@gmail.com', 'gabri', 'moocca', 1);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `courses_ibfk_3` FOREIGN KEY (`host_id`) REFERENCES `hosts` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `courses_learners`
--
ALTER TABLE `courses_learners`
  ADD CONSTRAINT `courses_learners_ibfk_1` FOREIGN KEY (`learner_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `courses_learners_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
