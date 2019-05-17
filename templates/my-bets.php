<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($user_bets as $bet) : ?>
            <?php
            $rates_class = '';
            $timer_class = '';
            $timer_value = interval_before_close($bet['lot_date_end'], true);

            if (check_time2($bet['lot_date_end'], 60 * 60)) {
                $timer_class = 'timer--finishing';
            }
            if (check_time2($bet['lot_date_end'])) {
                $rates_class = 'rates__item--end';
                $timer_class = 'timer--end';
                $timer_value = 'Торги окончены';
            }
            if ($bet['user_id'] === $bet['winner_id']) {
                $rates_class = 'rates__item--win';
                $timer_class = 'timer--win';
                $timer_value = 'Ставка выиграла';
            }
            ?>
            <tr class="rates__item <?= $rates_class ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $bet['lot_image']; ?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= $bet['lot_title'] ?></a></h3>
                        <p><?= ($bet['user_id'] === $bet['winner_id']) ? $bet['user_contacts'] : '' ?></p>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bet['category_title'] ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?= $timer_class ?>">
                        <?= $timer_value ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= price_format($bet['bet_price']) ?> р
                </td>
                <td class="rates__time">
                    <?= format_bet_date($bet['bet_date_create']) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</section>
