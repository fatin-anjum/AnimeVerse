-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2025 at 11:54 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `anime_genres`
--

CREATE TABLE `anime_genres` (
  `anime_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debates`
--

CREATE TABLE `debates` (
  `debate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debates`
--

INSERT INTO `debates` (`debate_id`, `user_id`, `title`, `content`, `created_at`) VALUES
(7, 24, 'One piece is too long.', 'Not if you enjoy it.', '2025-08-22 09:05:55');

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
(1, 7, 23, 'yes if you are a busy person', 20, '2025-08-22 09:10:57');

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
(1, 1, 23, 1);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fanart`
--

INSERT INTO `fanart` (`fanart_id`, `user_id`, `title`, `description`, `filename`, `file_path`, `created_at`) VALUES
(1, 23, 'Gojo Satorou', '', 'fanart_68a83dbd4c3f4.jpg', '', '2025-08-22 09:51:57');

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
  `posted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre_discussions`
--

INSERT INTO `genre_discussions` (`discussion_id`, `genre_id`, `user_id`, `title`, `content`, `posted_at`) VALUES
(1, 1, 23, 'Do u think Tanjiro shouldve just left nezuko?', 'In my opinion, the anime wouldnt exist if that happened.', '2025-08-22 07:50:39'),
(2, 3, 24, 'Sakamoto days', 'Sakamoto needs to get his prime look back.', '2025-08-22 08:00:40'),
(3, 3, 24, 'School babysitters', 'This is good anime for time passing.', '2025-08-22 08:01:59'),
(4, 3, 24, 'School babysitters', 'This is good anime for time passing.', '2025-08-22 08:17:26');

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
(2, 'is baki strong enough?', '', 24, '2025-08-22 08:59:53');

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
(5, 2, 'No');

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
(24, 1),
(24, 4);

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
  `reviewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, 1, 24, 1, '2025-08-22 07:58:16'),
(8, 1, 23, 0, '2025-08-22 09:31:48');

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
(1, 1, 24, 'True that.', '2025-08-22 07:58:28');

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
(23, 'sakamoto', 'sakamoto123@gmail.com', '$2y$10$Mr.aKvT5IahGn.ftcytoourEBLCDv9xVBEKZ82euUJakYYBqg0l1q', NULL, 1, NULL, NULL, '2025-08-08 16:08:15', 1),
(24, 'rahin', 'fatin@gmail.com', '$2y$10$vMfY2DjYXirS.bgcGtRhVeHiqnmLQzB3ttOjZ48rQfqJIs/p8.Vom', NULL, 1, NULL, NULL, '2025-08-22 07:51:07', 1);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anime`
--
ALTER TABLE `anime`
  MODIFY `anime_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collectibles`
--
ALTER TABLE `collectibles`
  MODIFY `collectible_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debates`
--
ALTER TABLE `debates`
  MODIFY `debate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `debate_replies`
--
ALTER TABLE `debate_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `debate_reply_votes`
--
ALTER TABLE `debate_reply_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fanart`
--
ALTER TABLE `fanart`
  MODIFY `fanart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fanart_comments`
--
ALTER TABLE `fanart_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fanart_hearts`
--
ALTER TABLE `fanart_hearts`
  MODIFY `heart_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `discussion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `poll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thread_likes`
--
ALTER TABLE `thread_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `thread_replies`
--
ALTER TABLE `thread_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
