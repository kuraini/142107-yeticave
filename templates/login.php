<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $category): ?>
      <li class="nav__item">
        <a href="all-lots.html"><?=$category['name']; ?></a>
      </li>
    <? endforeach; ?>
    </ul>
</nav>
<form class="form container <?=count($errors) ? 'form--invalid' : ''; ?>" action="/login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : ''; ?>">
      <label for="email">E-mail*</label>
      <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?=$form['email'] ?? ''; ?>" required>
      <span class="form__error"><?=$errors['email'] ?? ''; ?></span>
    </div>
    <div class="form__item form__item--last <?=isset($errors['password']) ? 'form__item--invalid' : ''; ?>">
      <label for="password">Пароль*</label>
      <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?=$form['password'] ?? ''; ?>" required>
      <span class="form__error"><?=$errors['password'] ?? ''; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
