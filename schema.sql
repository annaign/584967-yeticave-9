-- DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE `categories` (
  `code` CHAR(64) NOT NULL UNIQUE,
  `category_title` VARCHAR(256) UNIQUE NOT NULL,
  CONSTRAINT `categories_pkey` PRIMARY KEY (`code`)
);

CREATE TABLE `lots` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `lot_date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lot_title` VARCHAR(256) NOT NULL,
  `lot_description` TEXT,
  `lot_image` VARCHAR(256),
  `lot_price_start` INT UNSIGNED NOT NULL,
  `lot_date_end` TIMESTAMP NOT NULL,
  `lot_step` INT NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `winner_id` INT UNSIGNED NOT NULL,
  `category_code` CHAR(64) NOT NULL,
  CONSTRAINT `lots_pkey` PRIMARY KEY (`id`)
);

CREATE TABLE `bets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bet_date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bet_price` INT NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `lot_id` INT UNSIGNED NOT NULL,
  CONSTRAINT `bets_pkey` PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_email` VARCHAR(128) UNIQUE NOT NULL,
  `user_name` VARCHAR(256) UNIQUE NOT NULL,
  `user_password` VARCHAR(128) NOT NULL,
  `user_avatar` VARCHAR(256),
  `user_contacts` VARCHAR(256) NOT NULL,
  `lot_id` INT UNSIGNED,
  `bet_id` INT UNSIGNED,
  CONSTRAINT `users_pkey` PRIMARY KEY (`id`)
);

ALTER TABLE `lots` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `lots` ADD FOREIGN KEY (`category_code`) REFERENCES `categories` (`code`);
ALTER TABLE `lots` ADD FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`);

ALTER TABLE `bets` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `bets` ADD FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`);

ALTER TABLE `users` ADD FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`);
ALTER TABLE `users` ADD FOREIGN KEY (`bet_id`) REFERENCES `bets` (`id`);

CREATE INDEX `lot_name` ON `lots`(`lot_title`);
CREATE UNIQUE INDEX `email` ON `users`(`user_email`);
