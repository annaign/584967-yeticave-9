CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  'code_category' CHAR(64) NOT NULL UNIQUE PRIMARY KEY,
  'category_title' CHAR(64) UNIQUE NOT NULL
);

CREATE TABLE lot (
  'id_lot' INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'lot_date_create' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'lot_title' CHAR(64) NOT NULL,
  'lot_description' TEXT,
  'lot_image' VARCHAR(256),
  'lot_price_start' INT UNSIGNED NOT NULL,
  'lot_date_end' TIMESTAMP NOT NULL,
  'lot_step' INT NOT NULL,
  'id_user' INT UNSIGNED NOT NULL,
  'id_winner' INT UNSIGNED NOT NULL,
  'code_category' INT NOT NULL
);

CREATE TABLE bet (
  'id_bet' INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'bet_date_create' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'bet_price' INT NOT NULL,
  'id_user' INT NOT NULL,
  'id_lot' INT NOT NULL
);

CREATE TABLE user (
  'id_user' INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  'user_date_create' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'user_email' VARCHAR(128) UNIQUE NOT NULL,
  'user_name' VARCHAR(64) UNIQUE NOT NULL,
  'user_password' VARCHAR(64) NOT NULL,
  'user_avatar' VARCHAR(256),
  'user_contacts' VARCHAR(256) NOT NULL,
  'id_lot' INT UNSIGNED,
  'id_bet' INT UNSIGNED,
);
