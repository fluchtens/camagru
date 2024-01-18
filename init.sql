CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  bio VARCHAR(255) DEFAULT NULL,
  avatar VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS post (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  caption TEXT,
  file VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS filter (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  file VARCHAR(255)
);

INSERT INTO filter (name, file) VALUES ('fire', 'fire.png');
INSERT INTO filter (name, file) VALUES ('pikachu', 'pikachu.png');
INSERT INTO filter (name, file) VALUES ('beard', 'beard.png');