-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2018 at 04:15 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trl`
--
CREATE DATABASE IF NOT EXISTS `trl` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `trl`;

-- --------------------------------------------------------

--
-- Table structure for table `performers`
--

CREATE TABLE IF NOT EXISTS `performers` (
  `unique_id` varchar(255) DEFAULT NULL,
  `performerID` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `stagename` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `message` varchar(500) NOT NULL,
  `image` varchar(50) NOT NULL,
  `percentage` int(11) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`performerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `performers`
--

INSERT INTO `performers` (`unique_id`, `performerID`, `firstname`, `lastname`, `stagename`, `email`, `password`, `message`, `image`, `percentage`, `last_login`, `created_at`, `updated_at`) VALUES
('7848B409-9660-0AC2-0050-1497826BAD46', 1, 'GRAEME', 'CONNORS', 'Club Theatrette', 'graeme4545@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Iconic Australian Artist, Graeme Connors,will do what he does best and take to the road from August with his touring band for showsthroughout Queensland, New South Wales and Victoria.\n\nConnors'' new album, From the Backcountry, will be released in early August and follows his highly successful 2016 album, 60 Summers: The Ultimate Collection, which reached #1 on the ARIA Country Album Chart and saw the lead single, also titled "60 Summers", spend three weeks at #1 on the The Music Network Official', 'Micheal.png', 0, '2018-11-05 06:03:05', '2018-10-08 05:08:08', '2018-10-11 03:12:10'),
('877EB49B-4310-46BB-1160-DFFAE150EF15', 2, 'Nick', 'Johns', 'Club Theatrette', 'Nick2525@gmail.com', '4297f44b13955235245b2497399d7a93', 'Iconic Australian Artist, Graeme Connors,will do what he does best and take to the road from August with his touring band for showsthroughout Queensland, New South Wales and Victoria.\r\n\r\nConnors'' new album, From the Backcountry, will be released in early August and follows his highly successful 2016 album, 60 Summers: The Ultimate Collection, which reached #1 on the ARIA Country Album Chart and saw the lead single, also titled "60 Summers", spend three weeks at #1 on the The Music Network Offici', 'Micheal.png', 0, '2018-11-18 19:15:18', '2018-10-08 05:08:08', '2018-10-11 03:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `performersongs`
--

CREATE TABLE IF NOT EXISTS `performersongs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `performerID` int(11) DEFAULT NULL,
  `songID` int(11) DEFAULT NULL,
  `is_favorite` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`performerID`,`songID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `performersongs`
--

INSERT INTO `performersongs` (`id`, `performerID`, `songID`, `is_favorite`) VALUES
(1, 2, 3, 0),
(2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `songID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `artist` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`songID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`songID`, `name`, `artist`) VALUES
(1, 'Dhadak', 'Ajay Gogavale & Shreya Ghoshal'),
(2, 'Dilbar', 'Neha Kakkar, Dhvani Bhanushali, Ikka'),
(3, 'Proper Patola', 'Diljit Dosanjh, Badshah, Aastha Gill'),
(4, 'Morni Banke', 'Guru Randhawa & Neha Kakkar');

-- --------------------------------------------------------

--
-- Table structure for table `users_authentication`
--

CREATE TABLE IF NOT EXISTS `users_authentication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `users_authentication`
--

INSERT INTO `users_authentication` (`id`, `users_id`, `token`, `expired_at`, `created_at`, `updated_at`) VALUES
(1, '1', '$1$M83.lf0.$k5PoHRmK.8/GJvGI5b4LP.', '2018-10-15 00:34:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '1', '$1$Lp0.2/..$cfLgbaP2RNEd9hQkPL5hx0', '2018-10-15 00:35:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '1', '$1$Wd/.nT2.$IDZncjvnERy3GJQM5rVu9.', '2018-10-15 00:35:21', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '1', '$1$2N3.hA0.$ydtdN2CAh9aWz0nWXMkJs.', '2018-10-15 11:51:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, '1', '$1$dQ2.ie5.$KFnNydf.VNTyb88ULUGS6/', '2018-10-15 12:00:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, '1', '$1$QD/.Zn0.$ALqG4gEPs56lmkEnDaOWk/', '2018-10-15 12:02:10', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, '1', '$1$aw0.bR3.$S4mEFkuAleGyj42tx64YC0', '2018-10-15 23:11:56', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, '1', '$1$xK4.mS/.$DKVfORoAf1zotFjiw5/881', '2018-10-16 10:51:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, '1', '$1$.84.t00.$/dB9p66v1ZZ7KGffFjzy3.', '2018-10-16 10:54:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, '1', '$1$Tl2.gO0.$vfCaWs//liQQWq65DFHvk1', '2018-10-16 10:59:20', '0000-00-00 00:00:00', '2018-10-15 22:59:20'),
(11, '1', '$1$8g4.vx2.$V8ksp3ZY9g.z.kvD.AOHf1', '2018-10-16 11:00:16', '0000-00-00 00:00:00', '2018-10-15 23:00:16'),
(12, '1', '$1$lr0.Kr0.$f0NsUtkj2CCLSAOHmOAdH1', '2018-10-16 11:01:40', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, '1', '$1$2x/.hU1.$o8fjA/ggjamTCHCQvEmwk0', '2018-10-16 11:02:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, '1', '$1$n91.ki4.$RcEyVv1W3cvumYxig5/6A/', '2018-10-16 11:22:20', '0000-00-00 00:00:00', '2018-10-15 23:22:20'),
(15, '1', '$1$i40.Dh..$KmApke54gaKogb24dmRpz.', '2018-10-16 11:17:49', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '1', '$1$ht/.We3.$mYaWFyMYBD4tlt1SqeG.t/', '2018-10-16 11:18:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '2', '$1$kP1.dF1.$jyGA4ZOZbYvrmPxyW8e1C0', '2018-10-16 11:22:49', '0000-00-00 00:00:00', '2018-10-15 23:22:49'),
(18, '1', '$1$Dy1.Q63.$7uf6rut/cWZpDG1BVLMvy1', '2018-10-16 11:23:37', '0000-00-00 00:00:00', '2018-10-15 23:23:37'),
(19, '1', '$1$uj/.fK2.$YK5z7vi0KZQU5r.OjSvFb/', '2018-10-16 11:41:00', '0000-00-00 00:00:00', '2018-10-15 23:41:00'),
(20, '1', '$1$060.HQ2.$q3WA6J8PsIdsntagQqVb.0', '2018-11-05 05:27:38', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, '1', '$1$7h0.Cl3.$tXpmVStRBNCJHPe3aE2XL1', '2018-11-05 06:04:34', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, '1', '$1$wt3.3A1.$N.Xbape1AzwaD9Yoqgswu0', '2018-11-05 06:22:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, '1', '$1$9K..cN4.$6BKHaSXqSjUJKdYocI4nM.', '2018-11-05 06:23:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, '1', '$1$aQ0.bx4.$liV1DVPihqO7fyYwjQTOH.', '2018-11-05 06:23:32', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, '1', '$1$xq1.my..$k5HMEjjEy7P68pUis9HZQ/', '2018-11-05 06:31:36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, '1', '$1$qj/.rj..$1RQRW4dZJeZNsFQYYEsPL.', '2018-11-05 17:43:20', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '1', '$1$BH/.04..$wMpcMyrlb6pVlvwsRjOj3/', '2018-11-05 17:45:19', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, '1', '$1$EX4.711.$f.FOwZv8OdzaBJUBg68Kb0', '2018-11-05 18:03:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, '877EB49B-4310-46BB-1160-DFFAE150EF15', '$1$C80.je0.$Tbh87SwPLU7vmUNyRJpZe1', '2018-11-19 07:29:10', '0000-00-00 00:00:00', '2018-11-18 19:29:10');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
