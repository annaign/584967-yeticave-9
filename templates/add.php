<form class="form form--add-lot container <?= count($lot_errors) > 0 ? 'form--invalid' : '' ?>" action="/add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($lot_errors['lot-name']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <?php $value = isset($new_lot['lot-name']) ? $new_lot['lot-name'] : '' ?>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= $value ?>">
            <span class="form__error"><?= $errors_message['lot-name'] ?></span>
        </div>
        <div class="form__item <?= isset($lot_errors['category']) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category) :
                    $selected = '';
                    if (isset($new_lot['category']) && ($category['id'] === $new_lot['category'])) {
                        $selected = ' selected';
                    } ?>
                    <option value="<?= $category['id'] ?>" <?= $selected ?>><?= htmlspecialchars($category['category_title']) ?></option>
                <?php endforeach ?>
            </select>
            <span class="form__error"><?= $errors_message['category'] ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($lot_errors['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <?php $value = isset($new_lot['message']) ? $new_lot['message'] : '' ?>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= $value ?></textarea>
        <span class="form__error"><?= $errors_message['message']; ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($lot_errors['lot-img']) ? 'form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="lot-img">
            <label for="lot-img">Добавить</label>
            <span class="form__error"><?= $errors_message['lot-img'] ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($lot_errors['lot-rate']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <?php $value = isset($new_lot['lot-rate']) ? $new_lot['lot-rate'] : '' ?>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= $value ?>">
            <span class="form__error"><?= $errors_message['lot-rate']; ?></span>
        </div>
        <div class="form__item form__item--small <?= isset($lot_errors['lot-step']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <?php $value = isset($new_lot['lot-step']) ? $new_lot['lot-step'] : '' ?>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= $value ?>">
            <span class="form__error"><?= $errors_message['lot-step']; ?></span>
        </div>
        <div class="form__item <?= isset($lot_errors['lot-date']) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <?php $value = isset($new_lot['lot-date']) ? $new_lot['lot-date'] : '' ?>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $value ?>">
            <span class="form__error"><?= $errors_message['lot-date'] ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
