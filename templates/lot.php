<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $category): ?>
      <li class="nav__item">
        <a href="all-lots.html"><?=$category; ?></a>
      </li>
    <? endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
<?php if (isset($lot)): ?>
    <h2><?=htmlspecialchars($lot['name']); ?></h2>
    <div class="lot-item__content">
      <div class="lot-item__left">
        <div class="lot-item__image">
          <img src="<?=$lot['image']; ?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name']); ?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=$lot['category']; ?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['message']); ?></p>
      </div>
      <div class="lot-item__right">
        <?php if (isAuth()): ?>
        <div class="lot-item__state">
          <div class="lot-item__timer timer">
            <?=countRemainingTime($lot['lot-date']); ?>
          </div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=formatSum(htmlspecialchars($lot['lot-rate'])); ?></span>
            </div>
            <div class="lot-item__min-cost">
              Мин. ставка <span><?=formatSum(htmlspecialchars($lot['lot-step']), '&nbsp;р'); ?></span>
            </div>
          </div>
          <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
            <p class="lot-item__form-item">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="number" name="cost" placeholder="<?=formatSum(htmlspecialchars($lot['lot-step'] + htmlspecialchars($lot['lot-rate'])), ''); ?>">
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
        </div>
        <?php endif; ?>
        <div class="history">
        <?php if (isset($lot['bets'])): ?>
          <h3>История ставок (<span><?=count($lot['bets']); ?></span>)</h3>
          <table class="history__list">
          <?php foreach($lot['bets'] as $bet):?>
            <tr class="history__item">
              <td class="history__name"><?=htmlspecialchars($bet['name']); ?></td>
              <td class="history__price"><?=formatSum(htmlspecialchars($bet['price']), '&nbsp;р'); ?></td>
              <td class="history__time"><?=formatTime($bet['ts']); ?></td>
            </tr>
          <?php endforeach; ?>
          </table>
          <?php else: ?>
            <h3>Ставок нет</h3>
          <?php endif; ?>
        </div>
      </div>
    </div>
<?php else: ?>
    <h1>Лот с таким id не найден</h1>
<?php endif; ?>
</section>
