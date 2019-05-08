USE yeticave;

# Добавление данных

INSERT INTO categories (`id`, `category_title`)
VALUES ('boards', 'Доски и лыжи'),
       ('attachment', 'Крепления'),
       ('boots', 'Ботинки'),
       ('clothing', 'Одежда'),
       ('tools', 'Инструмент'),
       ('other', 'Разное');

INSERT INTO users (`user_email`, `user_name`, `user_password`, `user_contacts`, `user_avatar`)
VALUES ('user1@mail.com', 'user1', 'Password1', 'user1@mail.com +7(000) 000-00-01', 'img/avatar.jpg'),
       ('user2@mail.com', 'user2', 'Password2', 'user2@mail.com +7(000) 000-00-02', 'img/avatar.jpg'),
       ('user3@mail.com', 'user3', 'Password3', 'user3@mail.com +7(000) 000-00-03', '');

INSERT INTO lots (`category_id`, `lot_title`, `lot_description`, `lot_image`, `lot_price_start`, `lot_step`,
  `lot_date_end`, `user_id`)
VALUES ('boards', '2014 Rossignol District Snowboard', 'В идеальном состоянии.', 'img/lot-1.jpg', 10999, 60, NOW() + INTERVAL 1 DAY, 1),
       ('boards', 'DC Ply Mens 2016/2017 Snowboard', 'Б/у.', 'img/lot-2.jpg', 159999, 150, NOW() + INTERVAL 1 DAY, 2),
       ('attachment', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Новый.', 'img/lot-3.jpg', 8000, 50, NOW() + INTERVAL 1 DAY, 3),
       ('boots', 'Ботинки для сноуборда DC Mutiny Charocal', '', 'img/lot-4.jpg', 10999, 80, NOW() + INTERVAL 1 DAY, 1),
       ('clothing', 'Куртка для сноуборда DC Mutiny Charocal', '', 'img/lot-5.jpg', 7500, 50, NOW() + INTERVAL 1 DAY, 2),
       ('other', 'Маска Oakley Canopy', '', 'img/lot-6.jpg', 5400, 20, NOW() + INTERVAL 1 DAY, 3),
       ('other', 'Закрытый лот', 'Тест отображения закрытого лота', NULL, 5400, 20, NOW() - INTERVAL 1 DAY, 3);

INSERT INtO bets (`user_id`, `lot_id`, `bet_price`)
VALUES (1, 2, 100000),
       (2, 1, 8000),
       (3, 1, 8500),
       (3, 5, 6100);


# Запрос: получить все категории
SELECT * FROM categories;

# Запрос: получить самые новые, открытые лоты
# Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории
SELECT `lot_title` AS "Название лота",
       `lot_price_start` AS "Стартовая цена",
       `lot_image` AS "Ссылка на изображение",
       MAX(`bet_price`) AS "Цена",
       `category_title` AS "Название категории"
  FROM lots
  LEFT JOIN bets ON lots.`id` = bets.`lot_id`
  JOIN categories ON lots.`category_id` = categories.`id`
  WHERE lots.`lot_date_end` > NOW()
  GROUP BY lots.`id`
  ORDER BY lots.`lot_date_create` DESC;

# Запрос: показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT `lot_date_create` AS "Дата создания",
       `category_title` AS "Название категории",
       `lot_title` AS "Название лота",
       `lot_description` AS "Описание лота",
       `lot_price_start` AS "Начальная цена",
       `lot_step` AS "Шаг ставки",
       `lot_date_end` AS "Дата завершения",
       `lot_image` AS "Ссылка на изображение"
  FROM lots
  JOIN categories ON lots.`category_id` = categories.`id`
  WHERE lots.`id` = 1;

# Запрос: обновить название лота по его идентификатору
UPDATE lots SET `lot_title` = "Rossignol District Snowboard" WHERE id = 1;

# Запрос: получить список самых свежих ставок для лота по его идентификатору
SELECT * FROM bets
  INNER JOIN lots ON bets.`lot_id` = lots.`id`
  WHERE lots.`id` = 1
  ORDER BY bets.`bet_date_create` DESC;
