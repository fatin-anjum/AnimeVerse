-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 09:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `animeverse`
--

-- --------------------------------------------------------

--
-- Table structure for table `anime`
--

CREATE TABLE `anime` (
  `anime_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `status` enum('Ongoing','Completed','Upcoming') DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anime`
--

INSERT INTO `anime` (`anime_id`, `title`, `description`, `release_year`, `status`, `cover_image`, `created_at`) VALUES
(1, 'Attack on Titan', 'Humanity fights for survival against giant humanoid Titans.', 2013, 'Completed', 'https://cdn.myanimelist.net/images/anime/10/47347l.jpg', '2025-09-07 12:43:46'),
(2, 'Demon Slayer', 'A young boy becomes a demon slayer to save his sister.', 2019, 'Ongoing', 'https://cdn.myanimelist.net/images/anime/1286/99889l.jpg', '2025-09-07 12:43:46'),
(3, 'One Piece', 'Monkey D. Luffy searches for the legendary treasure One Piece.', 1999, 'Ongoing', 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg', '2025-09-07 12:43:46'),
(4, 'My Hero Academia', 'In a world of superpowers, a powerless boy aims to become a hero.', 2016, 'Ongoing', 'https://cdn.myanimelist.net/images/anime/10/78745l.jpg', '2025-09-07 12:43:46'),
(5, 'Death Note', 'A high school student finds a supernatural notebook that can kill anyone.', 2006, 'Completed', 'https://cdn.myanimelist.net/images/anime/9/9453l.jpg', '2025-09-07 12:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `anime_genres`
--

CREATE TABLE `anime_genres` (
  `anime_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anime_genres`
--

INSERT INTO `anime_genres` (`anime_id`, `genre_id`) VALUES
(1, 1),
(1, 4),
(2, 1),
(2, 4),
(3, 1),
(3, 3),
(4, 1),
(4, 4),
(5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `badge_visibility_preferences`
--

CREATE TABLE `badge_visibility_preferences` (
  `preference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `activity_type` enum('reviewing','debating','fanart_uploading','selling','buying','profile_display') NOT NULL DEFAULT 'profile_display',
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badge_visibility_preferences`
--

INSERT INTO `badge_visibility_preferences` (`preference_id`, `user_id`, `badge_name`, `activity_type`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 23, 'First Reviewer', 'reviewing', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(2, 23, 'First Artist', 'reviewing', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(3, 23, 'Conversation Starter', 'reviewing', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(4, 24, 'First Reviewer', 'reviewing', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(5, 24, 'First Artist', 'reviewing', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(6, 24, 'Conversation Starter', 'reviewing', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(7, 23, 'First Reviewer', 'debating', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(8, 23, 'First Artist', 'debating', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(9, 23, 'Conversation Starter', 'debating', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(10, 24, 'First Reviewer', 'debating', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(11, 24, 'First Artist', 'debating', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(12, 24, 'Conversation Starter', 'debating', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(13, 23, 'First Reviewer', 'fanart_uploading', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(14, 23, 'First Artist', 'fanart_uploading', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(15, 23, 'Conversation Starter', 'fanart_uploading', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(16, 24, 'First Reviewer', 'fanart_uploading', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(17, 24, 'First Artist', 'fanart_uploading', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(18, 24, 'Conversation Starter', 'fanart_uploading', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(19, 23, 'First Reviewer', 'selling', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(20, 23, 'First Artist', 'selling', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(21, 23, 'Conversation Starter', 'selling', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(22, 24, 'First Reviewer', 'selling', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(23, 24, 'First Artist', 'selling', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(24, 24, 'Conversation Starter', 'selling', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(25, 23, 'First Reviewer', 'buying', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(26, 23, 'First Artist', 'buying', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(27, 23, 'Conversation Starter', 'buying', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(28, 24, 'First Reviewer', 'buying', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(29, 24, 'First Artist', 'buying', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(30, 24, 'Conversation Starter', 'buying', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(31, 23, 'First Reviewer', 'profile_display', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(32, 23, 'First Artist', 'profile_display', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(33, 23, 'Conversation Starter', 'profile_display', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(34, 24, 'First Reviewer', 'profile_display', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(35, 24, 'First Artist', 'profile_display', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50'),
(36, 24, 'Conversation Starter', 'profile_display', 1, '2025-09-07 15:16:50', '2025-09-07 15:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `collectibles`
--

CREATE TABLE `collectibles` (
  `collectible_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_sold` tinyint(1) DEFAULT 0,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `buyer_name` varchar(255) DEFAULT NULL,
  `buyer_contact` varchar(255) DEFAULT NULL,
  `buyer_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collectibles`
--

INSERT INTO `collectibles` (`collectible_id`, `user_id`, `title`, `description`, `price`, `image_url`, `is_sold`, `posted_at`, `buyer_name`, `buyer_contact`, `buyer_location`) VALUES
(12, 24, 'Action Figure', 'ken-kaneki', 1200.00, 'uploads/collectibles/collectible_1757237268.avif', 1, '2025-09-07 09:27:48', 'rahin', 'fatin@gmail.com', 'Motijheel'),
(13, 27, 'action figure', 'kakashi', 1500.00, 'uploads/collectibles/collectible_1757269760.webp', 1, '2025-09-07 18:29:20', 'rahin', 'fatin@gmail.com', 'Mohammadpur'),
(14, 24, 'asdas', '', 1500.00, 'uploads/collectibles/collectible_1757304104.jpg', 0, '2025-09-08 04:01:44', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `debates`
--

CREATE TABLE `debates` (
  `debate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_spoiler` tinyint(1) DEFAULT 0,
  `spoiler_warning` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debates`
--

INSERT INTO `debates` (`debate_id`, `user_id`, `title`, `content`, `created_at`, `is_spoiler`, `spoiler_warning`) VALUES
(7, 24, 'One piece is too long.', 'Not if you enjoy it.', '2025-08-22 09:05:55', 0, NULL),
(8, 24, 'dwqeqwe', 'ccccc', '2025-08-24 05:51:00', 0, NULL),
(9, 24, 'aasdasdasd', 'vzzxc', '2025-08-24 06:07:18', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `debate_replies`
--

CREATE TABLE `debate_replies` (
  `reply_id` int(11) NOT NULL,
  `debate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `votes` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debate_replies`
--

INSERT INTO `debate_replies` (`reply_id`, `debate_id`, `user_id`, `content`, `votes`, `created_at`) VALUES
(1, 7, 23, 'yes if you are a busy person', 20, '2025-08-22 09:10:57'),
(2, 7, 24, 'sdfdfsd', 0, '2025-08-24 05:59:20'),
(3, 9, 24, 'sdasd', 0, '2025-08-24 06:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `debate_reply_votes`
--

CREATE TABLE `debate_reply_votes` (
  `id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debate_reply_votes`
--

INSERT INTO `debate_reply_votes` (`id`, `reply_id`, `user_id`, `vote`) VALUES
(1, 1, 23, 1),
(2, 1, 24, -1),
(3, 3, 24, -1);

-- --------------------------------------------------------

--
-- Table structure for table `fanart`
--

CREATE TABLE `fanart` (
  `fanart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_spoiler` tinyint(1) DEFAULT 0,
  `spoiler_warning` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fanart`
--

INSERT INTO `fanart` (`fanart_id`, `user_id`, `title`, `description`, `filename`, `file_path`, `created_at`, `is_spoiler`, `spoiler_warning`) VALUES
(5, 23, 'gojo', '', 'fanart_68a83ffa00d40.jpg', 'uploads/fanart_68a83ffa00d40.jpg', '2025-08-22 10:01:30', 0, NULL),
(12, 24, 'adfasf', '', 'fanart_68aaab57dc77e.jpg', '', '2025-08-24 06:04:07', 1, 'Contains spoilers'),
(13, 27, 'samurai', '', 'fanart_68bdcebe2b659.jpg', '', '2025-09-07 18:28:14', 1, 'asdasd');

-- --------------------------------------------------------

--
-- Table structure for table `fanart_comments`
--

CREATE TABLE `fanart_comments` (
  `comment_id` int(11) NOT NULL,
  `fanart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fanart_comments`
--

INSERT INTO `fanart_comments` (`comment_id`, `fanart_id`, `user_id`, `comment`, `created_at`) VALUES
(2, 5, 24, 'great job!', '2025-08-22 12:08:46'),
(3, 5, 24, 'qweqweq', '2025-08-24 05:56:21'),
(4, 12, 24, 'adasdasd', '2025-08-24 06:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `fanart_hearts`
--

CREATE TABLE `fanart_hearts` (
  `heart_id` int(11) NOT NULL,
  `fanart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fanart_hearts`
--

INSERT INTO `fanart_hearts` (`heart_id`, `fanart_id`, `user_id`, `created_at`) VALUES
(5, 5, 23, '2025-08-22 10:04:24'),
(6, 5, 24, '2025-08-22 10:14:11');

-- --------------------------------------------------------

--
-- Table structure for table `fan_art`
--

CREATE TABLE `fan_art` (
  `art_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_spoiler` tinyint(1) DEFAULT 0,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `follower_id` int(11) NOT NULL,
  `followee_id` int(11) NOT NULL,
  `followed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`follower_id`, `followee_id`, `followed_at`) VALUES
(26, 23, '2025-09-07 18:26:29'),
(26, 24, '2025-09-07 18:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `name`) VALUES
(1, 'Action'),
(3, 'Comedy'),
(4, 'Fantasy'),
(2, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `genre_discussions`
--

CREATE TABLE `genre_discussions` (
  `discussion_id` int(11) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_spoiler` tinyint(1) DEFAULT 0,
  `spoiler_warning` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre_discussions`
--

INSERT INTO `genre_discussions` (`discussion_id`, `genre_id`, `user_id`, `title`, `content`, `posted_at`, `is_spoiler`, `spoiler_warning`) VALUES
(1, 1, 23, 'Do u think Tanjiro shouldve just left nezuko?', 'In my opinion, the anime wouldnt exist if that happened.', '2025-08-22 07:50:39', 0, NULL),
(2, 3, 24, 'Sakamoto days', 'Sakamoto needs to get his prime look back.', '2025-08-22 08:00:40', 0, NULL),
(3, 3, 24, 'School babysitters', 'This is good anime for time passing.', '2025-08-22 08:01:59', 0, NULL),
(4, 3, 24, 'School babysitters', 'This is good anime for time passing.', '2025-08-22 08:17:26', 0, NULL),
(5, 2, 24, 'qweqwe', 'sssssd', '2025-08-24 05:57:34', 0, NULL),
(6, 4, 24, 'qeqwewq', 'asdasds', '2025-08-24 06:05:34', 0, NULL),
(7, 1, 24, '123', '123', '2025-09-07 11:15:48', 1, 'Contains spoilers');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `collectible_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `ordered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `poll_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`poll_id`, `title`, `description`, `created_by`, `created_at`) VALUES
(1, 'The best among these 3', 'shounen', 24, '2025-08-22 08:59:17'),
(2, 'is baki strong enough?', '', 24, '2025-08-22 08:59:53'),
(3, 'qweqweq', '', 24, '2025-08-24 05:50:46'),
(4, 'qewqwe', '', 24, '2025-08-24 05:58:22'),
(5, 'xcvxccx', '', 24, '2025-08-24 06:06:08'),
(6, 'which one should i watch first', '', 24, '2025-09-07 16:16:17'),
(7, 'which one should i watch first', '', 24, '2025-09-07 16:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `option_id` int(11) NOT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_options`
--

INSERT INTO `poll_options` (`option_id`, `poll_id`, `option_text`) VALUES
(1, 1, 'One piece'),
(2, 1, 'Naruto'),
(3, 1, 'Bleach'),
(4, 2, 'Yes'),
(5, 2, 'No'),
(6, 3, '1'),
(7, 3, '2'),
(8, 3, '3'),
(9, 4, 'sd'),
(10, 4, 'gdg'),
(11, 4, 'ada'),
(12, 5, 'adasd'),
(13, 5, 'fdfd'),
(14, 5, 'bvbvb'),
(15, 5, 'cvcvxc'),
(16, 6, 'gachiakuta'),
(17, 6, 'tougen anki'),
(18, 7, 'gachiakuta'),
(19, 7, 'tougen anki');

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `user_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_votes`
--

INSERT INTO `poll_votes` (`user_id`, `option_id`) VALUES
(23, 2),
(23, 4),
(24, 2),
(24, 4),
(24, 13),
(24, 16),
(24, 18);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `anime_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL CHECK (`rating` between 1 and 10),
  `comment` text DEFAULT NULL,
  `is_spoiler` tinyint(1) DEFAULT 0,
  `reviewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `spoiler_warning` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `anime_id`, `user_id`, `rating`, `comment`, `is_spoiler`, `reviewed_at`, `spoiler_warning`) VALUES
(1, 1, 23, 9, 'Absolutely incredible series! The plot twists and character development are phenomenal. The animation quality is top-notch and the soundtrack is emotionally powerful.', 0, '2025-09-07 12:44:12', NULL),
(2, 1, 24, 8, 'good anime but  protagonist dies in the end', 1, '2025-09-08 04:18:49', 'iuiduhfg'),
(3, 2, 23, 9, 'Beautiful animation and heartwarming story about family bonds. Tanjiro is such a kind protagonist and the breathing techniques are amazing!', 0, '2025-09-07 12:44:12', NULL),
(4, 2, 24, 8, 'Stunning visuals and great fight scenes. The character relationships are well developed and emotional.', 0, '2025-09-07 12:44:12', NULL),
(5, 3, 23, 10, 'The greatest adventure story ever told! Luffy and his crew never fail to inspire. The world-building is unmatched.', 0, '2025-09-07 12:44:12', NULL),
(6, 3, 24, 9, 'Epic journey with amazing character development. Each arc brings new excitement and emotional moments.', 0, '2025-09-07 12:44:12', NULL),
(7, 4, 23, 8, 'Great superhero anime with inspiring messages about perseverance and heroism. Deku is a relatable protagonist.', 0, '2025-09-07 12:44:12', NULL),
(8, 4, 24, 9, 'Love the quirks system and how creative the powers are. The school setting works really well.', 0, '2025-09-07 12:44:12', NULL),
(9, 5, 23, 10, 'Psychological masterpiece! The cat and mouse game between Light and L is absolutely thrilling.', 0, '2025-09-07 12:44:12', NULL),
(11, 5, 24, 9, 'L dies', 1, '2025-09-09 07:33:56', 'character death spoiler');

-- --------------------------------------------------------

--
-- Table structure for table `spoiler_tags`
--

CREATE TABLE `spoiler_tags` (
  `spoiler_id` int(11) NOT NULL,
  `content_type` enum('review','fanart','discussion','debate') NOT NULL,
  `content_id` int(11) NOT NULL,
  `spoiler_warning` varchar(255) DEFAULT NULL,
  `anime_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spoiler_tags`
--

INSERT INTO `spoiler_tags` (`spoiler_id`, `content_type`, `content_id`, `spoiler_warning`, `anime_id`, `created_at`) VALUES
(1, 'fanart', 123, 'Contains ending spoiler', 2, '2025-09-07 13:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `thread_likes`
--

CREATE TABLE `thread_likes` (
  `like_id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_like` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thread_likes`
--

INSERT INTO `thread_likes` (`like_id`, `discussion_id`, `user_id`, `is_like`, `created_at`) VALUES
(8, 1, 23, 0, '2025-08-22 09:31:48'),
(9, 1, 24, 0, '2025-09-07 16:17:45'),
(12, 7, 24, 0, '2025-09-07 16:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `thread_replies`
--

CREATE TABLE `thread_replies` (
  `reply_id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `replied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thread_replies`
--

INSERT INTO `thread_replies` (`reply_id`, `discussion_id`, `user_id`, `content`, `replied_at`) VALUES
(1, 1, 24, 'True that.', '2025-08-22 07:58:28'),
(2, 5, 24, 'adasd', '2025-08-24 05:57:45'),
(3, 4, 24, 'adadas', '2025-08-24 06:05:10'),
(4, 7, 24, 'AS', '2025-09-07 16:24:13'),
(5, 7, 24, 'SDFSDF', '2025-09-07 16:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `level` int(11) DEFAULT 1,
  `badge` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `profile_picture`, `level`, `badge`, `bio`, `created_at`, `is_active`) VALUES
(23, 'sakamoto', 'sakamoto123@gmail.com', '$2y$10$Mr.aKvT5IahGn.ftcytoourEBLCDv9xVBEKZ82euUJakYYBqg0l1q', NULL, 2, 'First Reviewer', NULL, '2025-08-08 16:08:15', 1),
(24, 'rahin', 'fatin@gmail.com', '$2y$10$vMfY2DjYXirS.bgcGtRhVeHiqnmLQzB3ttOjZ48rQfqJIs/p8.Vom', NULL, 2, 'First Reviewer', NULL, '2025-08-22 07:51:07', 1),
(26, 'gojo satoru', 'gojo@gmail.com', '$2y$10$LrGIxeHcmaVazmvCdXweoeleqfbalqGOz1cQMvsJfekyOYfn/lCAC', NULL, 1, NULL, NULL, '2025-09-07 18:14:21', 1),
(27, 'naruto', 'naruto@gmail.com', '$2y$10$XxVH8zrkZyPvUdUXudVut.mQDP5gTfaX6PFFWAhyBL4XhLRk3fYcW', NULL, 1, NULL, NULL, '2025-09-07 18:26:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `badge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `badge_description` text DEFAULT NULL,
  `badge_icon` varchar(255) DEFAULT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_badges`
--

INSERT INTO `user_badges` (`badge_id`, `user_id`, `badge_name`, `badge_description`, `badge_icon`, `earned_at`) VALUES
(1, 23, 'First Reviewer', 'Wrote your first anime review', '📝', '2025-09-07 12:44:12'),
(2, 23, 'First Artist', 'Uploaded your first fanart', '🎨', '2025-09-07 12:44:12'),
(3, 23, 'Conversation Starter', 'Started your first discussion', '💬', '2025-09-07 12:44:12'),
(4, 24, 'First Reviewer', 'Wrote your first anime review', '📝', '2025-09-07 12:44:12'),
(5, 24, 'First Artist', 'Uploaded your first fanart', '🎨', '2025-09-07 12:44:12'),
(6, 24, 'Conversation Starter', 'Started your first discussion', '💬', '2025-09-07 12:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_experience`
--

CREATE TABLE `user_experience` (
  `exp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `points_earned` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_experience`
--

INSERT INTO `user_experience` (`exp_id`, `user_id`, `action_type`, `points_earned`, `description`, `earned_at`) VALUES
(1, 23, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(2, 24, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(3, 23, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(4, 24, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(5, 23, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(6, 24, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(7, 23, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(8, 24, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(9, 23, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(10, 24, 'review_submitted', 20, 'Review submitted', '2025-09-07 12:44:12'),
(11, 23, 'fanart_uploaded', 15, 'Uploaded fanart', '2025-09-07 12:44:12'),
(12, 23, 'discussion_started', 10, 'Started discussion', '2025-09-07 12:44:12'),
(13, 23, 'login_daily', 5, 'Daily login bonus', '2025-09-07 12:44:12'),
(14, 24, 'fanart_uploaded', 15, 'Uploaded fanart', '2025-09-07 12:44:12'),
(15, 24, 'discussion_started', 10, 'Started discussion', '2025-09-07 12:44:12'),
(16, 24, 'login_daily', 5, 'Daily login bonus', '2025-09-07 12:44:12'),
(18, 24, 'review_submitted', 10, '', '2025-09-07 14:31:27'),
(19, 24, 'review_submitted', 10, '', '2025-09-07 14:31:29'),
(20, 24, 'review_submitted', 10, '', '2025-09-07 14:31:30'),
(21, 24, 'review_submitted', 10, '', '2025-09-07 14:31:30'),
(22, 24, 'review_submitted', 10, '', '2025-09-07 14:31:30'),
(23, 24, 'review_submitted', 10, '', '2025-09-07 14:31:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`anime_id`);

--
-- Indexes for table `anime_genres`
--
ALTER TABLE `anime_genres`
  ADD PRIMARY KEY (`anime_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `badge_visibility_preferences`
--
ALTER TABLE `badge_visibility_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD UNIQUE KEY `unique_user_badge_activity` (`user_id`,`badge_name`,`activity_type`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `collectibles`
--
ALTER TABLE `collectibles`
  ADD PRIMARY KEY (`collectible_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `debates`
--
ALTER TABLE `debates`
  ADD PRIMARY KEY (`debate_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `debate_replies`
--
ALTER TABLE `debate_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `debate_id` (`debate_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `debate_reply_votes`
--
ALTER TABLE `debate_reply_votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vote` (`reply_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fanart`
--
ALTER TABLE `fanart`
  ADD PRIMARY KEY (`fanart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fanart_comments`
--
ALTER TABLE `fanart_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fanart_id` (`fanart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fanart_hearts`
--
ALTER TABLE `fanart_hearts`
  ADD PRIMARY KEY (`heart_id`),
  ADD UNIQUE KEY `unique_user_fanart` (`fanart_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fan_art`
--
ALTER TABLE `fan_art`
  ADD PRIMARY KEY (`art_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`follower_id`,`followee_id`),
  ADD KEY `followee_id` (`followee_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `genre_discussions`
--
ALTER TABLE `genre_discussions`
  ADD PRIMARY KEY (`discussion_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `collectible_id` (`collectible_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`poll_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`user_id`,`option_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `spoiler_tags`
--
ALTER TABLE `spoiler_tags`
  ADD PRIMARY KEY (`spoiler_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexes for table `thread_likes`
--
ALTER TABLE `thread_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `thread_replies`
--
ALTER TABLE `thread_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`badge_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_experience`
--
ALTER TABLE `user_experience`
  ADD PRIMARY KEY (`exp_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anime`
--
ALTER TABLE `anime`
  MODIFY `anime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `badge_visibility_preferences`
--
ALTER TABLE `badge_visibility_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `collectibles`
--
ALTER TABLE `collectibles`
  MODIFY `collectible_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `debates`
--
ALTER TABLE `debates`
  MODIFY `debate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `debate_replies`
--
ALTER TABLE `debate_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `debate_reply_votes`
--
ALTER TABLE `debate_reply_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fanart`
--
ALTER TABLE `fanart`
  MODIFY `fanart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fanart_comments`
--
ALTER TABLE `fanart_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fanart_hearts`
--
ALTER TABLE `fanart_hearts`
  MODIFY `heart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `fan_art`
--
ALTER TABLE `fan_art`
  MODIFY `art_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `genre_discussions`
--
ALTER TABLE `genre_discussions`
  MODIFY `discussion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `poll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `spoiler_tags`
--
ALTER TABLE `spoiler_tags`
  MODIFY `spoiler_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `thread_likes`
--
ALTER TABLE `thread_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `thread_replies`
--
ALTER TABLE `thread_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_experience`
--
ALTER TABLE `user_experience`
  MODIFY `exp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anime_genres`
--
ALTER TABLE `anime_genres`
  ADD CONSTRAINT `anime_genres_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anime_genres_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE;

--
-- Constraints for table `badge_visibility_preferences`
--
ALTER TABLE `badge_visibility_preferences`
  ADD CONSTRAINT `badge_visibility_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `collectibles`
--
ALTER TABLE `collectibles`
  ADD CONSTRAINT `collectibles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `debates`
--
ALTER TABLE `debates`
  ADD CONSTRAINT `debates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `debate_replies`
--
ALTER TABLE `debate_replies`
  ADD CONSTRAINT `debate_replies_ibfk_1` FOREIGN KEY (`debate_id`) REFERENCES `debates` (`debate_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `debate_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `debate_reply_votes`
--
ALTER TABLE `debate_reply_votes`
  ADD CONSTRAINT `debate_reply_votes_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `debate_replies` (`reply_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `debate_reply_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fanart`
--
ALTER TABLE `fanart`
  ADD CONSTRAINT `fanart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fanart_comments`
--
ALTER TABLE `fanart_comments`
  ADD CONSTRAINT `fanart_comments_ibfk_1` FOREIGN KEY (`fanart_id`) REFERENCES `fanart` (`fanart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fanart_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fanart_hearts`
--
ALTER TABLE `fanart_hearts`
  ADD CONSTRAINT `fanart_hearts_ibfk_1` FOREIGN KEY (`fanart_id`) REFERENCES `fanart` (`fanart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fanart_hearts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fan_art`
--
ALTER TABLE `fan_art`
  ADD CONSTRAINT `fan_art_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followee_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `genre_discussions`
--
ALTER TABLE `genre_discussions`
  ADD CONSTRAINT `genre_discussions_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`),
  ADD CONSTRAINT `genre_discussions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`collectible_id`) REFERENCES `collectibles` (`collectible_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `polls_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD CONSTRAINT `poll_options_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`poll_id`) ON DELETE CASCADE;

--
-- Constraints for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD CONSTRAINT `poll_votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `poll_votes_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `poll_options` (`option_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `spoiler_tags`
--
ALTER TABLE `spoiler_tags`
  ADD CONSTRAINT `spoiler_tags_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE SET NULL;

--
-- Constraints for table `thread_likes`
--
ALTER TABLE `thread_likes`
  ADD CONSTRAINT `thread_likes_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `genre_discussions` (`discussion_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thread_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `thread_replies`
--
ALTER TABLE `thread_replies`
  ADD CONSTRAINT `thread_replies_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `genre_discussions` (`discussion_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `thread_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_experience`
--
ALTER TABLE `user_experience`
  ADD CONSTRAINT `user_experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
