<?=renderTemplate('templates/nav.php', ['categories' => $categories]); ?>
<div class="container">
    <section class="lots">
        <h2>История просмотров</h2>
        <?php if (!empty($visited_lots)): ?>
        <?=renderTemplate('templates/lots.php', ['lots' => $lots]); ?>
        <?php else: ?>
            <p>Нет просмотренных лотов</p>
        <?php endif; ?>
    </section>
    <?=renderTemplate('templates/pagination.php', [
        'page_name' => $page_name,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page
    ]); ?>
</div>
