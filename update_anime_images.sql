-- Update existing anime entries with actual cover image URLs
-- Run this script to fix the missing anime cover images

UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/10/47347.jpg' 
WHERE `title` = 'Attack on Titan';

UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg' 
WHERE `title` = 'Demon Slayer';

UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/6/73245.jpg' 
WHERE `title` = 'One Piece';

UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/10/78745.jpg' 
WHERE `title` = 'My Hero Academia';

UPDATE `anime` SET `cover_image` = 'https://cdn.myanimelist.net/images/anime/9/9453.jpg' 
WHERE `title` = 'Death Note';

-- Verify the updates
SELECT anime_id, title, cover_image FROM anime WHERE anime_id IN (1,2,3,4,5);