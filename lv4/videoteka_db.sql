-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 09:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `videoteka_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `desired_films`
--

CREATE TABLE `desired_films` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `godina` int(11) NOT NULL,
  `zanr` varchar(100) NOT NULL,
  `trajanje` int(11) NOT NULL,
  `ocjena` float DEFAULT NULL,
  `redatelj` varchar(255) DEFAULT NULL,
  `zemlja` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `naslov`, `godina`, `zanr`, `trajanje`, `ocjena`, `redatelj`, `zemlja`) VALUES
(1, 'The Shawshank Redemption', 1994, 'Drama', 142, 9.3, 'Frank Darabont', 'USA'),
(2, 'The Godfather', 1972, 'Crime, Drama', 175, 9.2, 'Francis Ford Coppola', 'USA'),
(3, 'The Dark Knight', 2008, 'Action, Crime', 152, 9, 'Christopher Nolan', 'UK/USA'),
(4, 'Schindler\'s List', 1993, 'Biography, Drama', 195, 9, 'Steven Spielberg', 'USA'),
(5, '12 Angry Men', 1957, 'Crime, Drama', 96, 9, 'Sidney Lumet', 'USA'),
(6, 'Pulp Fiction', 1994, 'Crime, Drama', 154, 8.9, 'Quentin Tarantino', 'USA'),
(7, 'The Lord of the Rings: The Return of the King', 2003, 'Action, Adventure', 201, 9, 'Peter Jackson', 'NZ/USA'),
(8, 'Il Buono, il Brutto, il Cattivo', 1966, 'Western', 161, 8.8, 'Sergio Leone', 'Italy'),
(9, 'Fight Club', 1999, 'Drama', 139, 8.8, 'David Fincher', 'USA'),
(10, 'Inception', 2010, 'Action, Adventure', 148, 8.8, 'Christopher Nolan', 'USA/UK'),
(11, 'The Matrix', 1999, 'Action, Sci-Fi', 136, 8.7, 'Lana Wachowski', 'USA'),
(12, 'Goodfellas', 1990, 'Biography, Crime', 145, 8.7, 'Martin Scorsese', 'USA'),
(13, 'One Flew Over the Cuckoo\'s Nest', 1975, 'Drama', 133, 8.7, 'Milos Forman', 'USA'),
(14, 'Seven Samurai', 1954, 'Action, Drama', 207, 8.6, 'Akira Kurosawa', 'Japan'),
(15, 'Se7en', 1995, 'Crime, Drama', 127, 8.6, 'David Fincher', 'USA'),
(16, 'The Silence of the Lambs', 1991, 'Crime, Drama', 118, 8.6, 'Jonathan Demme', 'USA'),
(17, 'City of God', 2002, 'Crime, Drama', 130, 8.6, 'Fernando Meirelles', 'Brazil'),
(18, 'Life Is Beautiful', 1997, 'Comedy, Drama', 116, 8.6, 'Roberto Benigni', 'Italy'),
(19, 'Interstellar', 2014, 'Adventure, Drama', 169, 8.7, 'Christopher Nolan', 'USA/UK'),
(20, 'Saving Private Ryan', 1998, 'Drama, War', 169, 8.6, 'Steven Spielberg', 'USA'),
(21, 'Parasite', 2019, 'Drama, Thriller', 132, 8.5, 'Bong Joon Ho', 'South Korea'),
(22, 'The Green Mile', 1999, 'Crime, Drama', 189, 8.6, 'Frank Darabont', 'USA'),
(23, 'Star Wars: Episode IV - A New Hope', 1977, 'Action, Adventure', 121, 8.6, 'George Lucas', 'USA'),
(24, 'Terminator 2: Judgment Day', 1991, 'Action, Sci-Fi', 137, 8.6, 'James Cameron', 'USA'),
(25, 'Back to the Future', 1985, 'Adventure, Comedy', 116, 8.5, 'Robert Zemeckis', 'USA'),
(26, 'The Pianist', 2002, 'Biography, Drama', 150, 8.5, 'Roman Polanski', 'France/Poland'),
(27, 'Psycho', 1960, 'Horror, Mystery', 109, 8.5, 'Alfred Hitchcock', 'USA'),
(28, 'Gladiator', 2000, 'Action, Adventure', 155, 8.5, 'Ridley Scott', 'USA/UK'),
(29, 'The Lion King', 1994, 'Animation, Adventure', 88, 8.5, 'Roger Allers', 'USA'),
(30, 'The Departed', 2006, 'Crime, Drama', 151, 8.5, 'Martin Scorsese', 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `film_ratings`
--

CREATE TABLE `film_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `ocjena` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `filename`, `title`, `path`) VALUES
(1, 'darkknight.jpg', 'The Dark Knight', 'images/darkknight.jpg'),
(2, 'img1.jpg', 'Avengers', 'images/img1.jpg'),
(3, 'img2.jpg', 'jaws', 'images/img2.jpg'),
(4, 'img3.jpg', 'silence of the lambs', 'images/img3.jpg'),
(5, 'img4.jpg', 'titanic', 'images/img4.jpg'),
(6, 'img5.jpg', 'spiderman', 'images/img5.jpg'),
(7, 'img6.jpg', 'godfather', 'images/img6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `image_ratings`
--

CREATE TABLE `image_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image_ratings`
--

INSERT INTO `image_ratings` (`id`, `user_id`, `image_id`, `rating`) VALUES
(1, 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`) VALUES
(1, 'ivan', '$2y$10$nAEqORxAd.Wx32qysL0j.em505iDL9/PXCR2o99SkG0Gs5oF8I0me', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `desired_films`
--
ALTER TABLE `desired_films`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`film_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indexes for table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `film_ratings`
--
ALTER TABLE `film_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`film_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_ratings`
--
ALTER TABLE `image_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`image_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `desired_films`
--
ALTER TABLE `desired_films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `film_ratings`
--
ALTER TABLE `film_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `image_ratings`
--
ALTER TABLE `image_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `desired_films`
--
ALTER TABLE `desired_films`
  ADD CONSTRAINT `desired_films_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `desired_films_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `film_ratings`
--
ALTER TABLE `film_ratings`
  ADD CONSTRAINT `film_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `film_ratings_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `image_ratings`
--
ALTER TABLE `image_ratings`
  ADD CONSTRAINT `image_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `image_ratings_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
