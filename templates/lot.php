<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $category): ?>
      <li class="nav__item">
        <a href="all-lots.html"><?=$category['name']; ?></a>
      </li>
    <? endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
<?php if (isset($lot)): ?>
    <h2><?=htmlspecialchars($lot['title']); ?></h2>
    <div class="lot-item__content">
      <div class="lot-item__left">
        <div class="lot-item__image">
          <img src="<?=$lot['image']; ?>" width="730" height="548" alt="<?=htmlspecialchars($lot['title']); ?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=$lot['category']; ?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
      </div>
      <div class="lot-item__right">
        <?php if (isAuth()): ?>
        <div class="lot-item__state">
          <div class="lot-item__timer timer">
            <?=countRemainingTime($lot['date_end']); ?>
          </div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=formatSum(htmlspecialchars($lot['start_price'])); ?></span>
            </div>
            <div class="lot-item__min-cost">
              Мин. ставка <span><?=formatSum(htmlspecialchars($lot['step']), '&nbsp;р'); ?></span>
            </div>
          </div>
          <?php if ($canAddBet): ?>
          <form class="lot-item__form" action="lot.php?id=<?=$lot_id?>" method="post">
            <p class="lot-item__form-item">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="number" name="price" placeholder="<?=formatSum(htmlspecialchars($min_bet), ''); ?>">
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="history">
        <?php if ($bets): ?>
          <h3>История ставок (<span><?=count($bets); ?></span>)</h3>
          <table class="history__list">
          <?php foreach($bets as $bet):?>
            <tr class="history__item">
              <td class="history__name"><?=htmlspecialchars($bet['name']); ?></td>
              <td class="history__price"><?=formatSum(htmlspecialchars($bet['price']), '&nbsp;р'); ?></td>
              <td class="history__time"><?=formatTime(htmlspecialchars($bet['date_add'])); ?></td>
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
