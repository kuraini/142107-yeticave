<?=renderTemplate('templates/nav.php', ['categories' => $categories]); ?>
<div class="container">
    <?php if (!empty($lots)): ?>
    <section class="lots">
      <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search); ?></span>»</h2>
      <?=renderTemplate('templates/lots.php', ['lots' => $lots]); ?>
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
