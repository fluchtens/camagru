CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  full_name VARCHAR(255) NOT NULL,
  avatar VARCHAR(255) DEFAULT NULL,
  active BOOLEAN DEFAULT 0,
  activation_token varchar(255) NOT NULL,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS post (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  caption TEXT NOT NULL,
  file VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS post_like (
  user_id INT NOT NULL,
  post_id INT NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (post_id) REFERENCES post(id)
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
