<section class="lot-item container">
    <h2><?= htmlspecialchars($lot['lot_title']) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['lot_image'] ?>" width="730" height="548" alt="">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category_title']) ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot['lot_description']) ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (strtotime($lot['lot_date_end']) - time() > 0) : ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?= check_time2($lot['lot_date_end'], 60 * 60) ? "timer--finishing" : "" ?>">
                        <?= interval_before_close($lot['lot_date_end'], true); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format($lot['bet_price'] !== NULL ? $lot['bet_price'] : $lot['lot_price_start']) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format($lot['bet_price'] !== NULL ? $lot['bet_price'] + $lot['lot_step'] : $lot['lot_price_start'] + $lot['lot_step']) ?> р</span>
                        </div>
                    </div>
                    <?php if ($session_user['is_auth'] === 1) : ?>
                        <form class="lot-item__form" action="/lot.php?id=<?= $lot['id'] ?>" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?= isset($bet_errors['cost']) ? 'form__item--invalid' : '' ?>">
                                <label for="cost">Ваша ставка</label>
                                <?php $min_price = ($lot['bet_price'] !== NULL) ? $lot['bet_price'] + $lot['lot_step'] : $lot['lot_price_start'] + $lot['lot_step'] ?>
                                <?php $value = isset($new_bet['cost']) ? $new_bet['cost'] : '' ?>
                                <input id="cost" type="text" name="cost" placeholder="<?= price_format($min_price) ?>" value="<?= $value; ?>">
                                <span class="form__error"><?= $errors_message['cost'] ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif ?>
                </div>
            <?php endif ?>
            <div class="history">
                <?php if (isset($lot_bets) && count($lot_bets) > 0) : ?>
                    <h3>История ставок (<span><?= count($lot_bets) ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($lot_bets as $bet) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= $bet['user_name'] ?></td>
                                <td class="history__price"><?= price_format($bet['bet_price']) ?> р</td>
                                <td class="history__time"><?= format_bet_date($bet['bet_date_create']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>
