<?=renderTemplate('templates/nav.php', ['categories' => $categories]); ?>
<div class="container">
    <?php if (!empty($lots)): ?>
    <section class="lots">
      <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search); ?></span>»</h2>
      <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?=htmlspecialchars($lot['image']); ?>" width="350" height="260" alt="<?=htmlspecialchars($lot['title']); ?>">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?=htmlspecialchars($lot['category']); ?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id']; ?>"><?=htmlspecialchars($lot['title']); ?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?=formatSum(htmlspecialchars($lot['start_price'])); ?></span>
              </div>
              <div class="lot__timer timer <?= (($lot['date_end'] - time()) < 3600) ? 'timer--finishing' : ''; ?>">
                <?=countRemainingTime($lot['date_end']); ?>
              </div>
            </div>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php else: ?>
        <p>По вашему запросу ничего не найдено.</p>
    <?php endif; ?>
    <?=renderTemplate('templates/pagination.php', [
        'page_name' => $page_name,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page
    ]); ?>
  </div>
