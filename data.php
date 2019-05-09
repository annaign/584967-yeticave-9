<?php

$user_name = 'User';

$categories = [
    [
        'id' => 'boards',
        'category_title' => "Доски и лыжи",
    ],
    [
        'id' => 'attachment',
        'category_title' => "Крепления",
    ],
    [
        'id' => 'boots',
        'category_title' => "Ботинки",
    ], [
        'id' => 'clothing',
        'category_title' => "Одежда",
    ], [
        'id' => 'tools',
        'category_title' => "Инструменты",
    ], [
        'id' => 'other',
        'category_title' => "Разное",
    ],
];

$lots = [
    [
        'lot_title' => "2014 Rossignol District Snowboard",
        'category_title' => "Доски и лыжи",
        'lot_price_start' => 10999,
        'lot_image' => "img/lot-1.jpg",
    ],
    [
        'lot_title' => "DC Ply Mens 2016/2017 Snowboard",
        'category_title' => "Доски и лыжи",
        'lot_price_start' => 159999,
        'lot_image' => "img/lot-2.jpg",
    ],
    [
        'lot_title' => "Крепления Union Contact Pro 2015 года размер L/XL",
        'category_title' => "Крепления",
        'lot_price_start' => 8000,
        'lot_image' => "img/lot-3.jpg",
    ],
    [
        'lot_title' => "Ботинки для сноуборда DC Mutiny Charocal",
        'category_title' => "Ботинки",
        'lot_price_start' => 10999,
        'lot_image' => "img/lot-4.jpg",
    ],
    [
        'lot_title' => "Куртка для сноуборда DC Mutiny Charocal",
        'category_title' => "Одежда",
        'lot_price_start' => 7500,
        'lot_image' => "img/lot-5.jpg",
    ],
    [
        'lot_title' => "Маска Oakley Canopy",
        'category_title' => "Разное",
        'lot_price_start' => 5400,
        'lot_image' => "img/lot-6.jpg",
    ],
];

// `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
// `lot_date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
// `category_id` CHAR(64) NOT NULL,
// `lot_title` VARCHAR(256) NOT NULL,
// `lot_description` TEXT,
// `lot_image` VARCHAR(256),
// `lot_price_start` INT UNSIGNED NOT NULL,
// `lot_step` INT NOT NULL,
// `lot_date_end` TIMESTAMP NOT NULL,
// `user_id` INT UNSIGNED NOT NULL,
// `winner_id` INT UNSIGNED,
