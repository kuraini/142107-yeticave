<?=renderTemplate('templates/nav.php', ['categories' => $categories]); ?>
<form class="form container <?=count($errors) ? 'form--invalid' : ''; ?>"
    action="sign-up.php" method="post" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : ''; ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail"
            value="<?=htmlspecialchars($form['email'] ?? ''); ?>" required>
        <span class="form__error"><?=$errors['email'] ?? ''; ?></span>
    </div>
    <div class="form__item <?=isset($errors['password']) ? 'form__item--invalid' : ''; ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"
            value="<?=htmlspecialchars($form['password'] ?? ''); ?>" required>
        <span class="form__error"><?=$errors['password'] ?? ''; ?></span>
    </div>
    <div class="form__item <?=isset($errors['name']) ? 'form__item--invalid' : ''; ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя"
            value="<?=htmlspecialchars($form['name'] ?? ''); ?>" required>
        <span class="form__error"><?=$errors['name'] ?? ''; ?></span>
    </div>
    <div class="form__item <?=isset($errors['contacts']) ? 'form__item--invalid' : ''; ?>">
        <label for="contacts">Контактные данные*</label>
        <textarea id="contacts" name="contacts" placeholder="Напишите как с вами связаться" required><?=htmlspecialchars($form['contacts'] ?? ''); ?></textarea>
        <span class="form__error"><?=$errors['contacts'] ?? ''; ?></span>
    </div>
    <div class="form__item form__item--file form__item--last <?=isset($errors['avatar']) ? 'form__item--invalid' : ''; ?>">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="<?=htmlspecialchars($form['avatar']); ?>" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="photo2" value="" name="avatar">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
        <span class="form__error"><?=$errors['avatar'] ?? ''; ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>
