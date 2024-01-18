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
  file VARCHAR(255)
);

INSERT INTO filter (file) VALUES ('flames.png');
INSERT INTO filter (file) VALUES ('pikachu.png');
INSERT INTO filter (file) VALUES ('beard.png');
INSERT INTO filter (file) VALUES ('rainbow.png');
INSERT INTO filter (file) VALUES ('vintage.png');
INSERT INTO filter (file) VALUES ('sleaves.png');
INSERT INTO filter (file) VALUES ('fire.png');
INSERT INTO filter (file) VALUES ('evil.png');
