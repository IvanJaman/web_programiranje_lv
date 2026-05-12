CREATE DATABASE IF NOT EXISTS videoteka_db;
USE videoteka_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user'
);

CREATE TABLE films (
  id INT AUTO_INCREMENT PRIMARY KEY,
  naslov VARCHAR(255) NOT NULL,
  godina INT NOT NULL,
  zanr VARCHAR(100) NOT NULL,
  trajanje INT NOT NULL,
  ocjena FLOAT,
  redatelj VARCHAR(255),
  zemlja VARCHAR(100)
);

CREATE TABLE desired_films (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  film_id INT NOT NULL,
  UNIQUE(user_id, film_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
);

CREATE TABLE film_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  film_id INT NOT NULL,
  ocjena INT NOT NULL,
  UNIQUE(user_id, film_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
);

CREATE TABLE images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255),
  title VARCHAR(255),
  path VARCHAR(255)
);

CREATE TABLE image_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  image_id INT,
  rating INT,
  UNIQUE(user_id, image_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE
);