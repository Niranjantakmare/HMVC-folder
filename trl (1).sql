-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2018 at 05:49 PM
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
('7848B409-9660-0AC2-0050-1497826BAD46', 1, 'GRAEME', 'CONNORS', 'Club Theatrette', 'graeme4545@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Iconic Australian Artist, Graeme Connors,will do what he does best and take to the road from August with his touring band for showsthroughout Queensland, New South Wales and Victoria.l', 'Micheal.png', 0, '2018-11-05 06:03:05', '2018-10-08 05:08:08', '2018-10-11 03:12:10'),
('877EB49B-4310-46BB-1160-DFFAE150EF15', 2, 'Nick', 'Johns', 'Club Theatrette', 'Nick2525@gmail.com', '4297f44b13955235245b2497399d7a93', 'Iconic Australian Artist, Graeme Connors,will do what he does best and take to the road from August with his touring band for showsthroughout Queensland, New South Wales and Victoria.', 'Micheal.png', 0, '2018-11-18 19:15:18', '2018-10-08 05:08:08', '2018-10-11 03:12:10');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `performersongs`
--

INSERT INTO `performersongs` (`id`, `performerID`, `songID`, `is_favorite`) VALUES
(1, 2, 3, 0),
(2, 2, 2, 1),
(3, 2, 1, 1),
(4, 2, 4, 1),
(5, 2, 5, 1),
(6, 2, 6, 1),
(7, 2, 7, 1),
(8, 2, 8, 1),
(9, 2, 9, 1),
(10, 2, 11, 1),
(11, 2, 10, 1),
(12, 2, 12, 1),
(13, 2, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `description`, `link`) VALUES
(1, 'Parallax effect on hover with Tilt.js', 'This jQuery plugin adds parallax effect on the element that responds according to mouse movement.\r\n\r\nYou have only need to include jQuery script and no external CSS.', 'http://makitweb.com/parallax-effect-on-hover-with-tilt-js/'),
(2, 'Upload file with AngularJS and PHP', 'For upload file with AngularJS need to send the file by $http service and with the use PHP store the requested file to the server and return a response.', 'http://makitweb.com/upload-file-with-angularjs-and-php/'),
(3, 'Extract the Zip file with PHP', 'You don''t need to require any other extra plugin for working with Zip files.\n\nPHP has ZipArchive class that allow us to create a zip file or extract existing file.\n\nZipArchive class extractTo() method is used to extract the zip file that takes destination absolute path as argument.', 'http://makitweb.com/extract-the-zip-file-with-php/'),
(4, 'Password Strength checker with jQuery Complexify', 'The main aim of jQuery Complexify plugin is to measure how complex is the user entered password and show the live feedback in the form of strength bars.', 'http://makitweb.com/password-strength-checker-with-jquery-complexify/'),
(5, 'How to get data from MySQL with AngularJS - PHP', 'With only using AngularJS it is not possible to get data from MySQL database because it only handles Client side requests.\r\n\r\nYou have to use any Server side language at backend which handles the request and returns response.\r\n\r\n', 'http://makitweb.com/how-to-get-data-from-mysql-with-angularjs-php/'),
(6, 'jQuery - Zoom images on mouse over with ZooMove', 'You have seen on e-commerce websites for display the details view of a product. The part of the image will zoom according to mouse movement.', 'http://makitweb.com/zoom-images-on-mouse-over-with-zoomove/'),
(7, 'Material design ripple click with Rippleria - jQuery', 'Rippleria is a lightweight jQuery plugin which adds material design ripple click/tap effect on the HTML elements. You can implement this either with attribute or method.', 'http://makitweb.com/material-design-rippler-click-with-rippleria-jquery/'),
(8, 'Upload and store an image in the Database with PHP', 'You can save your uploading images in the database table for later use e.g. display user profile or product image, create the image gallery, etc.\r\n\r\n', 'http://makitweb.com/upload-and-store-an-image-in-the-database-with-php/'),
(9, 'Show Notification, prompt, and confirmation with Overhang.js', 'Overhang.js is a lightweight jQuery plugin which displays notification, prompt, and confirmation on the screen.', 'http://makitweb.com/show-notificationprompt-and-confirmation-with-overhang-js/'),
(10, 'Login page with Remember me in PHP', 'Remember me option allow the user to automatically get logged in to the website without entering its username and password again.', 'Remember me option allow the user to automatically get logged in to the website without entering its username and password again.'),
(11, 'How to change page title and icon on Page leave with jQuery', 'To get back the user attention back to your site you can animate the site page title and icon when the user leaves your site or open an another tab.\r\n\r\nIn this tutorial, I am using two jQuery plugins - iMissYou and mFancyTitle for customization.\r\n\r\n', 'http://makitweb.com/how-to-change-page-title-and-icon-on-page-leave-with-jquery/'),
(12, 'Lazy image load with BttrLazyLoading jQuery plugin', 'The BttrLazyLoading is a jQuery plugin that load images which are within the viewport. This delays loading of images in long web pages.\r\n\r\nIt allows defining images for 4 different screen sizes ( mobile, tablet, desktop and large desktop ). It has various options for customization.', 'http://makitweb.com/lazy-image-load-with-bttrlazyloading-jquery-plugin/'),
(13, 'Back to top with CSS and jQuery', 'The Back to Top button takes the user back to the top of the page.\r\n\r\nThe button is visible at the bottom when the user starts scrolling the web page and crosses the defined range. It remains fixed at its position during the scroll.\r\n\r\nIt auto hide when the user reaches the top of the page.', 'http://makitweb.com/back-to-top-with-css-and-jquery/'),
(14, 'Display estimated reading time with ReadRemaining.js', 'The ReadRemaining.js is a jQuery library which shows readers how much time is left to finish reading an article.\r\n\r\nThe estimated time will be different for each user because it calculates time-based on the speed at which the user is scrolling the page.\r\n', 'http://makitweb.com/display-estimated-reading-time-with-readremaining-js/'),
(15, 'Create Duplicate of the elements with .clone() - jQuery', 'The .clone() method creates the duplicate of the matched elements. It allows either event handler will be copy or not, it by default doesn''t copy attached events.\n\nThis simply your code when you need to make the clone of the group of elements. You don’t have the need to create each group elements and insert it.', 'http://makitweb.com/create-duplicate-of-the-elements-with-clone-jquery/'),
(16, 'Detect when all AJAX requests are complete - jQuery', 'When working with multiple AJAX requests at that time its hard to detect when will be all request is being completed.\r\n\r\nYou can use the setTimout() method which will execute your action after your given time. But it is not a better solution.', 'http://makitweb.com/detect-when-all-ajax-requests-are-complete-jquery/'),
(17, 'How to use jQuery UI slider to filter records with AJAX', 'A slider is a good to avoid bad input from the user if you want them to pick values within the range.\r\n\r\nTo add slider control I am using jQuery UI slider.', 'http://makitweb.com/how-to-use-jquery-ui-slider-to-filter-records-with-ajax/'),
(18, 'Page Redirect after specified time with JavaScript', 'For adding delay for execution of some action setTimeout() and setInterval() methods is begin used in JavaScript.\r\n\r\nThe setTimout() method execute the statement only once but setInterval() method execute repeatedly until the interval is not being cleared.', 'http://makitweb.com/page-redirect-after-specified-time-with-javascript/'),
(19, 'Capture Signature in the webpage with jQuery plugins', 'In this tutorial, I show you some jQuery plugins by using it you can capture the user signature on your web page.\r\n\r\nThey add a container on the web page where the user can draw its signature using mouse pointer.\r\n\r\n', 'http://makitweb.com/capture-signature-in-the-webpage-with-jquery-plugins/'),
(20, 'Generate PDF from HTML with Dompdf in PHP', 'The Pdf file creation in PHP mainly requires when we need to generate the file on the basis of the available data otherwise, we simply create it manually with the external applications.\r\n\r\nFor example – generating the report, the user certificate, etc.', 'http://makitweb.com/generate-pdf-from-html-with-dompdf-in-php/');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `requestID` int(11) NOT NULL AUTO_INCREMENT,
  `songID` int(11) NOT NULL,
  `songName` varchar(100) DEFAULT NULL,
  `tip` int(11) DEFAULT NULL,
  `showID` int(11) DEFAULT NULL,
  `customerID` int(11) DEFAULT NULL,
  `customerName` varchar(50) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`requestID`),
  UNIQUE KEY `requestID` (`requestID`,`songID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shorten_urls`
--

CREATE TABLE IF NOT EXISTS `shorten_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_url_code` varchar(100) DEFAULT NULL,
  `actual_url` varchar(500) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `expired_on` datetime DEFAULT NULL,
  `is_expired` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shorten_urls`
--

INSERT INTO `shorten_urls` (`id`, `short_url_code`, `actual_url`, `created_on`, `expired_on`, `is_expired`) VALUES
(1, '7aG7FkN9pA', '877EB49B-4310-46BB-1160-DFFAE150EF15', '2018-11-22 00:00:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE IF NOT EXISTS `shows` (
  `unique_id` varchar(255) NOT NULL,
  `showID` int(11) NOT NULL AUTO_INCREMENT,
  `showname` varchar(50) DEFAULT NULL,
  `showDate` datetime DEFAULT NULL,
  `showDescription` varchar(500) DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'S' COMMENT 'Status Options: S- Scheduled, L - Live, and  E- Ended',
  `performerID` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`showID`),
  UNIQUE KEY `ShowID` (`showID`,`performerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shows`
--

INSERT INTO `shows` (`unique_id`, `showID`, `showname`, `showDate`, `showDescription`, `status`, `performerID`, `created_on`, `updated_on`) VALUES
('877EB49B-4310-46BB-1160-DFFAE150EF15', 1, 'It''s About Time', '2018-11-26 00:00:00', 'American singer Nick Jonas has released three studio albums, one extended play (EP), and twentytwo singles (including four as a featured artist and seven promotional singles).', 'S', 2, '2018-11-26 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE IF NOT EXISTS `songs` (
  `songID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `artist` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`songID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`songID`, `name`, `artist`) VALUES
(1, 'Dhadak', 'Ajay Gogavale & Shreya Ghoshal'),
(2, 'Dilbar', 'Neha Kakkar, Dhvani Bhanushali, Ikka'),
(3, 'Proper Patola', 'Diljit Dosanjh, Badshah, Aastha Gill'),
(4, 'Morni Banke', 'Guru Randhawa & Neha Kakkar'),
(5, 'Pal', 'Arijit Singh, Shreya Ghoshal, Javed Mohsin'),
(6, 'Le Ja Tu Kahin', ' Arijit Singh'),
(7, 'Tera Fitoor ', ' Arijit Singh'),
(8, 'Pani Da Rang ', 'Ayushmann Khurrana'),
(9, 'Mitti Di Khushboo', 'Ayushmann Khurrana'),
(10, 'Dekhte Dekhte - Batti Gul Meter Chalu ', 'Atif Aslam'),
(11, 'Paniyon Sa - Satyameva Jayate', 'Atif Aslam'),
(12, 'Dil Diyan Gallan', 'Atif Aslam'),
(13, 'Dil Na Jaane Kyun ', 'Atif Aslam'),
(14, 'Piya O Re Piya ', 'Atif Aslam'),
(15, 'Darasal', 'Atif Aslam');

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
(29, '877EB49B-4310-46BB-1160-DFFAE150EF15', '$1$C80.je0.$Tbh87SwPLU7vmUNyRJpZe1', '2018-11-25 02:56:07', '0000-00-00 00:00:00', '2018-11-24 14:56:07');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
