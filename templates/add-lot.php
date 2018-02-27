<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $category): ?>
      <li class="nav__item">
        <a href="all-lots.html"><?=$category['name']; ?></a>
      </li>
    <? endforeach; ?>
    </ul>
</nav>
<form class="form form--add-lot container <?=count($errors) ? 'form--invalid' : ''; ?>"
    action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
      <div class="form__item <?=isset($errors['title']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-name">Наименование</label>
        <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота"
            value="<?=htmlspecialchars($lot['title'] ?? ''); ?>" required>
        <span class="form__error"><?=isset($errors['title']) ?? ''; ?></span>
      </div>
      <div class="form__item <?=isset($errors['category_id']) ? 'form__item--invalid' : ''; ?>">
        <label for="category">Категория</label>
        <select id="category" name="category_id" required>
          <option value="">Выберите категорию</option>
        <?php foreach ($categories as $index => $category): ?>
          <option value="<?=++$index; ?>" <?=isset($lot['category_id']) ? 'selected' : ''; ?>><?=$category['name']; ?></option>
        <?php endforeach; ?>
        </select>
        <span class="form__error"><?=$errors['category_id'] ?? ''; ?></span>
      </div>
    </div>
    <div class="form__item form__item--wide <?=isset($errors['description']) ? 'form__item--invalid' : ''; ?>">
      <label for="message">Описание</label>
      <textarea id="message" name="description" placeholder="Напишите описание лота" required><?=htmlspecialchars($lot['description'] ?? ''); ?></textarea>
      <span class="form__error"><?=$errors['description'] ?? ''; ?></span>
    </div>
    <div class="form__item form__item--file <?=isset($errors['image']) ? 'form__item--invalid' : ''; ?>
        <?=isset($lot['image']) ? 'form__item--uploaded' : ''; ?>">
      <label>Изображение</label>
      <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
          <img src="<?=$lot['image'] ?? ''; ?>" width="113" height="113" alt="Изображение лота">
        </div>
      </div>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" id="photo2" value="" name="image">
        <label for="photo2">
          <span>+ Добавить</span>
        </label>
      </div>
      <span class="form__error"><?=$errors['image'] ?? ''; ?></span>
    </div>
    <div class="form__container-three">
      <div class="form__item form__item--small <?=isset($errors['start_price']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-rate">Начальная цена</label>
        <input id="lot-rate" type="number" name="start_price" placeholder="0"
            value="<?=htmlspecialchars($lot['start_price'] ?? ''); ?>" required>
        <span class="form__error"><?=$errors['start_price'] ?? ''; ?></span>
      </div>
      <div class="form__item form__item--small <?=isset($errors['step']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-step">Шаг ставки</label>
        <input id="lot-step" type="number" name="step" placeholder="0"
            value="<?=htmlspecialchars($lot['step'] ?? ''); ?>" required>
        <span class="form__error"><?=$errors['step'] ?? ''; ?></span>
      </div>
      <div class="form__item <?=isset($errors['date_end']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-date">Дата окончания торгов</label>
        <input class="form__input-date" id="lot-date" type="date" name="date_end"
            value="<?=htmlspecialchars($lot['date_end'] ?? ''); ?>" required>
        <span class="form__error"><?=$errors['date_end'] ?? ''; ?></span>
      </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
  </form>
