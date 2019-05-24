# DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id CHAR(64) NOT NULL UNIQUE,
  category_title VARCHAR(256) UNIQUE NOT NULL,
  CONSTRAINT categories_pkey PRIMARY KEY (id)
);

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_email VARCHAR(128) UNIQUE NOT NULL,
  user_name VARCHAR(256) NOT NULL,
  user_password VARCHAR(256) NOT NULL,
  user_contacts VARCHAR(256) NOT NULL,
  user_avatar VARCHAR(256),
  CONSTRAINT users_pkey PRIMARY KEY (id)
);

CREATE TABLE lots (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  lot_date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  category_id CHAR(64) NOT NULL,
  lot_title VARCHAR(256) NOT NULL,
  lot_description TEXT,
  lot_image VARCHAR(256),
  lot_price_start INT UNSIGNED NOT NULL,
  lot_step INT NOT NULL,
  lot_date_end TIMESTAMP NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  winner_id INT UNSIGNED,
  CONSTRAINT lots_pkey PRIMARY KEY (id)
);

ALTER TABLE lots ADD FOREIGN KEY (category_id) REFERENCES categories (id);
ALTER TABLE lots ADD FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE lots ADD FOREIGN KEY (winner_id) REFERENCES users (id);

CREATE TABLE bets (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  bet_date_create TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id INT UNSIGNED NOT NULL,
  lot_id INT UNSIGNED NOT NULL,
  bet_price INT NOT NULL,
  CONSTRAINT bets_pkey PRIMARY KEY (id)
);

ALTER TABLE bets ADD FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE bets ADD FOREIGN KEY (lot_id) REFERENCES lots (id);

CREATE UNIQUE INDEX users_email ON users(user_email);
CREATE FULLTEXT INDEX lot_title_and_description ON lots(lot_title, lot_description);
