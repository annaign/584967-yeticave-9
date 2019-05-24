<div class="container">
    <section class="lots">
        <h2>Все лоты в категории «<span><?= $category_search['category_title'] ?></span>»</h2>
        <?php if (isset($lots) && count($lots) > 0) : ?>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $lot['lot_image'] ?>" width="350" height="260" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot['category_title']) ?></span>
                            <h3 class="lot__title"><a class="text-link" href="./lot.php?id=<?= $lot['id'] ?>"><?= htmlspecialchars($lot['lot_title']) ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <?php
                                    $amount_value = 'Стартовая цена';
                                    if ($lot['bets_sum'] > 0) {
                                        $amount_value = $lot['bets_sum'] . ' ' . get_noun_plural_form((int)$lot['bets_sum'], 'ставка', 'ставки', 'ставок');
                                    }
                                    ?>
                                    <span class="lot__amount"><?= $amount_value ?></span>
                                    <span class="lot__cost"><?= price_format($lot['bet_price'] !== NULL ? $lot['bet_price'] : $lot['lot_price_start']) ?><b class="rub">р</b></span>
                                </div>
                                <div class="lot__timer timer <?= check_time2($lot['lot_date_end'], 60 * 60) ? 'timer--finishing' : '' ?>">
                                    <?= interval_before_close($lot['lot_date_end'], true); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php else : ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php endif ?>
    </section>
    <?php if (isset($pages) && $pages > 1) : ?>
        <ul class="pagination-list">
            <?php $page_href = '/all-lots.php?category=' . $category_search['id'] . '&page=' ?>
            <li class="pagination-item pagination-item-prev">
                <?php if ($current_page - 1 > 0) : ?>
                    <a href="<?= $page_href . ($current_page - 1) . '&limit=' . $items_on_page ?>">Назад</a>
                <?php else : ?>
                    <a>Назад</a>
                <?php endif ?>
            </li>
            <?php for ($page = 1; $page <= $pages; $page++) : ?>
                <li class="pagination-item <?= $page === $current_page ? 'pagination-item-active' : '' ?>">
                    <?php if ($page !== $current_page) : ?>
                        <a href="<?= $page_href . $page . '&limit=' . $items_on_page ?>"><?= $page ?></a>
                    <?php else : ?>
                        <a><?= $page ?></a>
                    <?php endif ?>
                </li>
            <?php endfor ?>
            <li class="pagination-item pagination-item-next">
                <?php if ($current_page + 1 <= $pages) : ?>
                    <a href="<?= $page_href . ($current_page + 1) . '&limit=' . $items_on_page ?>">Вперед</a>
                <?php else : ?>
                    <a>Вперед</a>
                <?php endif ?>
            </li>
        </ul>
    <?php endif ?>
</div>
