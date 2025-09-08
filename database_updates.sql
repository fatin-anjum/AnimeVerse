-- Database updates for new features
-- Run this after importing animeverse.sql

-- Add some sample anime data for testing
INSERT INTO `anime` (`anime_id`, `title`, `description`, `release_year`, `status`, `cover_image`, `created_at`) VALUES
(1, 'Attack on Titan', 'Humanity fights for survival against giant humanoid Titans.', 2013, 'Completed', 'https://cdn.myanimelist.net/images/anime/10/47347.jpg', NOW()),
(2, 'Demon Slayer', 'A young boy becomes a demon slayer to save his sister.', 2019, 'Ongoing', 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg', NOW()),
(3, 'One Piece', 'Monkey D. Luffy searches for the legendary treasure One Piece.', 1999, 'Ongoing', 'https://cdn.myanimelist.net/images/anime/6/73245.jpg', NOW()),
(4, 'My Hero Academia', 'In a world of superpowers, a powerless boy aims to become a hero.', 2016, 'Ongoing', 'https://cdn.myanimelist.net/images/anime/10/78745.jpg', NOW()),
(5, 'Death Note', 'A high school student finds a supernatural notebook that can kill anyone.', 2006, 'Completed', 'https://cdn.myanimelist.net/images/anime/9/9453.jpg', NOW());

-- Add genre associations for the sample anime
INSERT INTO `anime_genres` (`anime_id`, `genre_id`) VALUES
(1, 1), -- Attack on Titan - Action
(1, 4), -- Attack on Titan - Fantasy
(2, 1), -- Demon Slayer - Action
(2, 4), -- Demon Slayer - Fantasy
(3, 1), -- One Piece - Action
(3, 3), -- One Piece - Comedy
(4, 1), -- My Hero Academia - Action
(4, 4), -- My Hero Academia - Fantasy
(5, 4); -- Death Note - Fantasy

-- Create table for user badges (extending the existing users table functionality)
CREATE TABLE IF NOT EXISTS `user_badges` (
  `badge_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `badge_description` text DEFAULT NULL,
  `badge_icon` varchar(255) DEFAULT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`badge_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create table for user experience points and level progression
CREATE TABLE IF NOT EXISTS `user_experience` (
  `exp_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `points_earned` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`exp_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create table for spoiler tags on content
CREATE TABLE IF NOT EXISTS `spoiler_tags` (
  `spoiler_id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` enum('review','fanart','discussion','debate') NOT NULL,
  `content_id` int(11) NOT NULL,
  `spoiler_warning` varchar(255) DEFAULT NULL,
  `anime_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`spoiler_id`),
  KEY `anime_id` (`anime_id`),
  CONSTRAINT `spoiler_tags_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add spoiler column to reviews table if not exists
ALTER TABLE `reviews` 
ADD COLUMN IF NOT EXISTS `spoiler_warning` varchar(255) DEFAULT NULL;

-- Add spoiler column to fanart table if not exists  
ALTER TABLE `fanart` 
ADD COLUMN IF NOT EXISTS `is_spoiler` tinyint(1) DEFAULT 0,
ADD COLUMN IF NOT EXISTS `spoiler_warning` varchar(255) DEFAULT NULL;

-- Add spoiler column to genre_discussions table if not exists
ALTER TABLE `genre_discussions` 
ADD COLUMN IF NOT EXISTS `is_spoiler` tinyint(1) DEFAULT 0,
ADD COLUMN IF NOT EXISTS `spoiler_warning` varchar(255) DEFAULT NULL;

-- Add spoiler column to debates table if not exists
ALTER TABLE `debates` 
ADD COLUMN IF NOT EXISTS `is_spoiler` tinyint(1) DEFAULT 0,
ADD COLUMN IF NOT EXISTS `spoiler_warning` varchar(255) DEFAULT NULL;