<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category) : ?>
            <li class="promo__item promo__item--<?= $category['id']; ?>">
                <a class="promo__link" href="pages/all-lots.html"><?= $category['category_title']; ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['lot_image']; ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot['category_title']); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="./lot.php?id=<?= $lot['id']; ?>"><?= htmlspecialchars($lot['lot_title']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= price_format($lot['lot_price_start']); ?><b class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer <?= check_time(interval_before_midnight(), 60 * 60) ? "timer--finishing" : "" ?>">
                            <?= date_interval_format(interval_before_midnight(), "%h:%i") ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</section>
