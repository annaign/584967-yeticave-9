<?php

declare (strict_types = 1);
require_once './init.php';

// Найти все лоты без победителей, дата истечения которых меньше или равна текущей дате
// Для каждого такого лота найти последнюю ставку

$sql = "SELECT lots.id AS id_lot, lots.lot_title, bets.bet_price, users.user_name, users.user_email,
            lots.winner_id, bets.user_id AS user_winner
            FROM lots
            LEFT JOIN bets ON bets.lot_id = lots.id
            LEFT JOIN users ON users.id = lots.user_id
            WHERE bets.id IN
                (SELECT MAX(bets.id) FROM bets
                LEFT JOIN lots ON lots.id = bets.lot_id
                WHERE lots.lot_date_end <= NOW() GROUP BY lots.id)
            AND lots.winner_id IS null";

$new_winners = db_fetch_data($link, $sql, []);

//конфигурация транспорта
$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 2525);
$transport->setUsername('40357ba10b6fbf');
$transport->setPassword('5f3a4324fa0b3c');

// для отправки сообщения
$mailer = new Swift_Mailer($transport);

// логи ошибок SwiftMailer
$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

if ($new_winners !== false) {
    // Записать в лот победителем автора последней ставки

    foreach ($new_winners as $new_winner) {
        $sql = "UPDATE lots SET winner_id = " . $new_winner['user_winner'] . " WHERE id = " . $new_winner['id_lot'];
        $add_new_winers = db_insert_data($link, $sql, $data = []);

        // Отправить победителю на email письмо — поздравление с победой
        $recipients[$new_winner['user_email']] = $new_winner['user_name'];

        // формирование сообщения
        $message = new Swift_Message();
        $message->setSubject('Ваша ставка победила!');
        $message->setFrom(['keks@phpdemo.ru' => '584967-yeticave-9']);
        $message->setBcc($recipients);

        $msg_content = include_template('email.php', [
            'winner' => $new_winner,
        ]);
        $message->setBody($msg_content, 'text/html');

        // отправка сообщения
        $result = $mailer->send($message);

        // if ($result) {
        //     print("Рассылка успешно отправлена");
        // } else {
        //     print("Не удалось отправить рассылку: " . $logger->dump());
        // }
    }
}
