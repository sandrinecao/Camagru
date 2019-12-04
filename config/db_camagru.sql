-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2019 at 05:16 AM
-- Server version: 8.0.18
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_camagru`
--
CREATE DATABASE IF NOT EXISTS `db_camagru` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_camagru`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id_comment` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_img` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id_comment`, `id_user`, `id_img`, `comment`, `date`) VALUES
(58, 18, 295, 'Beautiful alley!', '2019-12-02 18:14:30'),
(59, 18, 296, '#gig', '2019-12-02 18:15:35'),
(61, 16, 309, 'Amazing cat', '2019-12-02 18:30:33'),
(62, 18, 309, 'Where\'s your smile?', '2019-12-02 18:31:36'),
(63, 18, 308, 'Amazing dudette', '2019-12-02 18:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id_like` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_img` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id_like`, `id_user`, `id_img`, `date`) VALUES
(105, 16, 285, '2019-12-02 18:03:01'),
(106, 18, 295, '2019-12-02 18:14:21'),
(107, 18, 296, '2019-12-02 18:15:29'),
(109, 16, 302, '2019-12-02 18:24:57'),
(110, 16, 309, '2019-12-02 18:30:26'),
(111, 18, 309, '2019-12-02 18:30:53'),
(112, 18, 308, '2019-12-02 18:30:56'),
(113, 18, 306, '2019-12-02 18:30:58'),
(114, 18, 305, '2019-12-02 18:31:00'),
(115, 18, 304, '2019-12-02 18:31:02'),
(116, 18, 307, '2019-12-02 18:31:04'),
(117, 18, 303, '2019-12-02 18:31:08'),
(118, 16, 305, '2019-12-04 12:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `picture`
--

CREATE TABLE `picture` (
  `id_img` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `picture`
--

INSERT INTO `picture` (`id_img`, `id_user`, `img`, `date`, `likes`) VALUES
(295, 18, 'public/upload/20191202181411.png', '2019-12-02 18:14:12', 1),
(296, 18, 'public/upload/20191202181519.png', '2019-12-02 18:15:20', 1),
(299, 16, 'public/upload/20191202182325.png', '2019-12-02 18:23:25', 0),
(300, 16, 'public/upload/20191202182344.png', '2019-12-02 18:23:44', 0),
(301, 16, 'public/upload/20191202182416.png', '2019-12-02 18:24:17', 0),
(302, 16, 'public/upload/20191202182443.png', '2019-12-02 18:24:43', 1),
(303, 18, 'public/upload/20191202182755.png', '2019-12-02 18:27:55', 1),
(304, 18, 'public/upload/20191202182805.png', '2019-12-02 18:28:05', 1),
(305, 18, 'public/upload/20191202182818.png', '2019-12-02 18:28:18', 2),
(306, 18, 'public/upload/20191202182830.png', '2019-12-02 18:28:30', 1),
(307, 16, 'public/upload/20191202182939.png', '2019-12-02 18:29:39', 1),
(308, 16, 'public/upload/20191202182950.png', '2019-12-02 18:29:51', 1),
(309, 16, 'public/upload/20191202183007.png', '2019-12-02 18:30:07', 2),
(310, 18, 'public/upload/20191202183313.png', '2019-12-02 18:33:14', 0),
(311, 18, 'public/upload/20191202183344.png', '2019-12-02 18:33:44', 0),
(312, 18, 'public/upload/20191202183400.png', '2019-12-02 18:34:00', 0),
(313, 18, 'public/upload/20191202183418.png', '2019-12-02 18:34:19', 0),
(314, 16, 'public/upload/20191204130359.png', '2019-12-04 13:04:00', 0),
(315, 16, 'public/upload/20191204130419.png', '2019-12-04 13:04:20', 0),
(316, 16, 'public/upload/20191204130430.png', '2019-12-04 13:04:30', 0),
(317, 16, 'public/upload/20191204140048.png', '2019-12-04 14:00:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(500) NOT NULL,
  `activation_code` varchar(500) NOT NULL,
  `user_status` varchar(50) NOT NULL DEFAULT 'not verified',
  `token` varchar(255) NOT NULL,
  `notif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `activation_code`, `user_status`, `token`, `notif`) VALUES
(16, 'scao', 'sandrine.cao@gmail.com', '$2y$10$aBm8mC.xYt9mazRQBvHkEOSSpdmrBCxVxhlUVFIiSdbP1ScJO.C/y', '5c936263f3428a40227908d5a3847c0b', 'verified', '102dc4007eed5b4df2c36ee3d3c6277e', 1),
(18, 'mimi', 'emilie.brun.dmv@gmail.com', '$2y$10$s/NlEGrny3.qHxbJzKo0weaKW.MsQwlczIynoTf13jYCtTQewMs72', '54a367d629152b720749e187b3eaa11b', 'verified', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`);

--
-- Indexes for table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`id_img`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `picture`
--
ALTER TABLE `picture`
  MODIFY `id_img` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
