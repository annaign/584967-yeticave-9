CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  'code_category' CHAR(64) NOT NULL UNIQUE PRIMARY KEY,
  'category_title' VARCHAR(256) UNIQUE NOT NULL
);

CREATE TABLE lot (
  'id_lot' INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'lot_date_create' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'lot_title' VARCHAR(256) NOT NULL,
  'lot_description' TEXT,
  'lot_image' VARCHAR(256),
  'lot_price_start' INT UNSIGNED NOT NULL,
  'lot_date_end' TIMESTAMP NOT NULL,
  'lot_step' INT NOT NULL,
  'user_id' INT UNSIGNED NOT NULL,
  'winner_id' INT UNSIGNED NOT NULL,
  'category_code' INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES 'user' (id_user),
  FOREIGN KEY (category_code) REFERENCES 'category' (code_category),
  FOREIGN KEY (winner_id) REFERENCES 'user' (id_user)
);

CREATE TABLE bet (
  'id_bet' INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'bet_date_create' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'bet_price' INT NOT NULL,
  'user_id' INT NOT NULL,
  'lot_id' INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES 'user' (id_user),
  FOREIGN KEY (lot_id) REFERENCES 'lot' (id_lot),
);

CREATE TABLE user (
  'id_user' INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'user_date_create' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'user_email' VARCHAR(128) UNIQUE NOT NULL,
  'user_name' VARCHAR(256) UNIQUE NOT NULL,
  'user_password' VARCHAR(128) NOT NULL,
  'user_avatar' VARCHAR(256),
  'user_contacts' VARCHAR(256) NOT NULL,
  'lot_id' INT UNSIGNED,
  'bet_id' INT UNSIGNED,
  FOREIGN KEY (lot_id) REFERENCES 'lot' (id_lot),
  FOREIGN KEY (bet_id) REFERENCES 'bet' (id_bet)
);
