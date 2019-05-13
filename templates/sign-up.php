<form class="form container <?= count($registration_errors) > 0 ? 'form--invalid' : ''; ?>" action="/sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($registration_errors['email']) ? 'form__item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <?php $value = isset($new_user['email']) ? $new_user['email'] : ""; ?>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $value; ?>">
        <span class="form__error"><?= $errors_message['email']; ?></span>
    </div>
    <div class="form__item <?= isset($registration_errors['password']) ? 'form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $errors_message['password']; ?></span>
    </div>
    <div class="form__item <?= isset($registration_errors['name']) ? 'form__item--invalid' : ''; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <?php $value = isset($new_user['name']) ? $new_user['name'] : ""; ?>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $value; ?>">
        <span class="form__error"><?= $errors_message['name']; ?></span>
    </div>
    <div class="form__item <?= isset($registration_errors['message']) ? 'form__item--invalid' : ''; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <?php $value = isset($new_user['message']) ? $new_user['message'] : ""; ?>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= $value; ?></textarea>
        <span class="form__error"><?= $errors_message['message']; ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>
