<form class="form container <?= count($access_errors) > 0 ? 'form--invalid' : ''; ?>" action="/login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= isset($access_errors['email']) ? 'form__item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <?php $value = isset($user['email']) ? $user['email'] : ""; ?>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $value; ?>">
        <span class="form__error"><?= $errors_message['email']; ?></span>
    </div>
    <div class="form__item form__item--last <?= isset($access_errors['password']) ? 'form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $errors_message['password']; ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Войти</button>
</form>
