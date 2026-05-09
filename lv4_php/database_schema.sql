CREATE DATABASE IF NOT EXISTS videoteka_db;
USE videoteka_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS films (
  id INT AUTO_INCREMENT PRIMARY KEY,
  naslov VARCHAR(255) NOT NULL,
  godina INT NOT NULL,
  žanr VARCHAR(100) NOT NULL,
  trajanje INT NOT NULL,
  ocjena INT,
  redatelj VARCHAR(255) NOT NULL,
  zemlja_porijekla VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS desired_films (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  film_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_film (user_id, film_id)
);

CREATE TABLE IF NOT EXISTS film_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  film_id INT NOT NULL,
  ocjena INT NOT NULL CHECK (ocjena >= 1 AND ocjena <= 5),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_film_rating (user_id, film_id)
);

CREATE TABLE IF NOT EXISTS images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255) NOT NULL,
  title VARCHAR(255),
  description TEXT,
  path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Image ratings table
CREATE TABLE IF NOT EXISTS image_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  image_id INT NOT NULL,
  rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  rated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_image_rating (user_id, image_id)
);

-- password: admin123
INSERT INTO users (username, password_hash, role) VALUES 
('admin', '$2y$10$yJ5F7V2Ky8J1p3b5Kq7L9e6R5v3Z0x9c2a4m8k1p7N3o5S8e1q', 'admin');

-- password: user123
INSERT INTO users (username, password_hash, role) VALUES 
('marko', '$2y$10$yJ5F7V2Ky8J1p3b5Kq7L9e6R5v3Z0x9c2a4m8k1p7N3o5S8e1q', 'user'),
('ana', '$2y$10$yJ5F7V2Ky8J1p3b5Kq7L9e6R5v3Z0x9c2a4m8k1p7N3o5S8e1q', 'user');

